<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');
require_once(LIB_PATH.DS.'session.php');

class User_Photograph {
	
	protected static $table_name="users_photographs";
	protected static $db_fields=array('id', 'user_id', 'filename', 'type', 'size', 'visible', 'date_created', 'date_edited');
	public $id;
	public $user_id;
	public $filename;
	public $type;
	public $size;
	public $visible;
	public $date_created;
	public $date_edited;
	
	private $temp_path;
	protected $upload_dir="images";
	public $errors = array(); // Initialize an array.
	public $admin_errors = array(); // Initialize an array.
	
	/* protected $upload_errors = array(
		// http://www.php.net/manual/en/features.file-upload.errors.php
		UPLOAD_ERR_OK 			=> "No errors.",
		UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
		UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
		UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
		UPLOAD_ERR_NO_FILE 		=> "No file.",
		UPLOAD_ERR_NO_TMP_DIR 	=> "No temporary directory.",
		UPLOAD_ERR_CANT_WRITE 	=> "Can't write to disk.",
		UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
	); */
	
	public $max_file_size = 10485760; // expressed in bytes, 10240 = 10KB, 102400 = 100KB, 1048576 = 1MB, 10485760 = 10MB
	
	// Define allowed filetypes to check against during validations
	protected $allowed_mime_types = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg'];
	protected $allowed_extensions = ['png', 'gif', 'jpg', 'jpeg'];

	protected $check_is_image = true;
	protected $check_for_php = true;
	public $file_extension = "";
	public $dummyImage = "emptyImageIcon.png";
	
	// Pass in $_FILE(['uploaded_file']) as an argument. It will be used to initialize the variable of the picture.
	public function attach_file($file) {
		// Perform error checking on the form parameters
		// You can also perform validation here
		if(!$file || empty($file) || !is_array($file)) {
			// error: nothing uploaded or wrong argument usage
			$this->errors[] = "No file was uploaded.";
			return false;
			// Check if PHP returned an upload error. If the returned array file has zero, there was not error.
		} elseif($file['error'] != 0) {
			// error: report what PHP says went wrong
			// $this->errors[] = $this->upload_errors[$file['error']];
			$this->errors[] = $this->file_upload_error($file['error']);
			return false;
		} else {
			// Set object attributes to the form parameters.
			// $this->filename      = basename($file['name']); 
			$this->filename      = $this->sanitize_file_name($file['name']);
			$this->file_extension = strtolower($this->file_extension($this->filename));
			
			// Even more secure to assign a new name of your choosing.
			// Example: 'file_536d88d9021cb.png'
			// $unique_id = uniqid('file_', true); 
			// $new_name = "{$unique_id}.{$file_extension}";
			
			// Initialize the session
			global $session;
			$username = $session->user_username;
			$unique_id = uniqid($username.'_'); 
			// Change the name of the image file
			// $this->filename = "{$unique_id}.{$this->file_extension}";
			$this->filename = $unique_id.".".$this->file_extension;
			
			$this->temp_path     = $file['tmp_name'];
			$this->type          = strtolower($file['type']);
			$this->size          = $file['size'];
			$this->visible       = true;
			$this->date_created  = $this->current_Date_Time();
			// Don't worry about saving anything to the database yet.
			return true;
		}
	}
	
	// Custom save function for photograph class. It returns a boolean after saving.
	public function save() {
		// A new record won't have an id yet.
		if(isset($this->id)) {
			// Really just to update
			$this->update();
		} else {
			// Make sure there are no errors
			
			// Can't save if there are pre-existing errors
			if(!empty($this->errors)) { return false; }
		
			// Can't save without filename and temp location
			if(empty($this->filename) || empty($this->temp_path)) {
				$this->errors[] = "The file location was not available.";
				return false;
			}
			
			// Check if the file uploaded is larger that what is acceptable
			/* if ($this->size > $this->max_file_size) {
				// PHP already first checks php.ini upload_max_filesize, and 
				// then form MAX_FILE_SIZE if sent.
				// But MAX_FILE_SIZE can be spoofed; check it again yourself.
				$this->errors[] = "The file {$this->filename} is too large.";
				return false;
			} */
			
			// Validate the file being uploaded is an image
			if(!in_array($this->type, $this->allowed_mime_types)) {
				$this->errors[] = "Mime Error: Not a valid image file.<br />";
				$this->admin_errors[] = "Error: Not a valid image mime type.";
				return false;
			} elseif(!in_array($this->file_extension, $this->allowed_extensions)) {
				// Checking file extension prevents files like 'evil.jpg.php' 
				$this->errors[] = "Extension Error: Not a valid image file.<br />";
				$this->admin_errors[] = "Error: Not a valid image extension.";
				return false;
			} elseif($this->check_is_image && (getimagesize($this->temp_path) === false)) {
				// getimagesize() returns image size details, but more importantly,
				// returns false if the file is not actually an image file.
				// You obviously would only run this check if expecting an image.
				$this->errors[] = "Error: Not a valid image file.<br />";
				$this->admin_errors[] = "Error: Not a valid image file.";
				return false;
			} elseif($this->check_for_php && $this->file_contains_php($this->temp_path)) {
				// A valid image can still contain embedded PHP.
				// Log the attempt to upload an improper image file.
				$this->errors[] = "Spurious image: Not a valid image file.<br />";
				$this->admin_errors[] = "Error: File contains PHP code.";
				return false;
			}
			
			// Determine the target_path
			$target_path = SITE_ROOT .DS. 'Public' .DS. $this->upload_dir .DS. $this->filename;
		  
			// Make sure a file doesn't already exist in the target location
			if(file_exists($target_path)) {
				$this->errors[] = "The file {$this->filename} already exists.";
				return false;
			}
		
			// Attempt to move the file 
			if(move_uploaded_file($this->temp_path, $target_path)) {
				// remove execute file permissions from the file
				$this->removeExePerm($target_path);	
				
				// resize the image to fit for cropping
				$max_resolution = 700;
				$file = $target_path; // file name is used in other functions
				$this->resize_image($file, $max_resolution);

				// Save target path in session so can be used for cropping.
				// $_SESSION['target_path'] = $file;
				
				// Save the target path which will be used for cropping
				$_SESSION['img_name'] = $this->filename;
				
				// Save properties of the photo
				$_SESSION['user_photo_user_id'] = $this->user_id;
				$_SESSION['user_photo_filename'] = $this->filename;
				$_SESSION['user_photo_type'] = $this->type;
				$_SESSION['user_photo_size'] = $this->size;
				$_SESSION['user_photo_visible'] = true;
				$_SESSION['user_photo_date_created'] = current_Date_Time();
				
				// We are done with temp_path, the file isn't there anymore
				unset($this->temp_path);
				
				return true;
				/* 	// Saving is done after the user has cropped the image
				// Save a corresponding entry to the database
				if($this->create()) {
					// We are done with temp_path, the file isn't there anymore
					unset($this->temp_path);
					return true;
				} */
			} else {
				// File was not moved.
				$this->errors[] = "The file upload failed.";
				$this->admin_errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
				return false;
			}
		}
	}

	// This will save the cropped image and will not resize it
	public function saveCrop() {
		// Make sure there are no errors
		// Can't save if there are pre-existing errors
		if(!empty($this->errors)) { return false; }
	
		// Can't save without filename and temp location
		if(empty($this->filename) || empty($this->temp_path)) {
			$this->errors[] = "The file location was not available.";
			return false;
		}
		
		// Check if the file uploaded is larger that what is acceptable
		/* if ($this->size > $this->max_file_size) {
			// PHP already first checks php.ini upload_max_filesize, and 
			// then form MAX_FILE_SIZE if sent.
			// But MAX_FILE_SIZE can be spoofed; check it again yourself.
			$this->errors[] = "The file {$this->filename} is too large.";
			return false;
		} */
		
		// Validate the file being uploaded is an image
		if(!in_array($this->type, $this->allowed_mime_types)) {
			$this->errors[] = "Mime Error: Not a valid image file.<br />";
			$this->admin_errors[] = "Error: Not a valid image mime type.";
			return false;
		} elseif(!in_array($this->file_extension, $this->allowed_extensions)) {
			// Checking file extension prevents files like 'evil.jpg.php' 
			$this->errors[] = "Extension Error: Not a valid image file.<br />";
			$this->admin_errors[] = "Error: Not a valid image extension.";
			return false;
		} elseif($this->check_is_image && (getimagesize($this->temp_path) === false)) {
			// getimagesize() returns image size details, but more importantly,
			// returns false if the file is not actually an image file.
			// You obviously would only run this check if expecting an image.
			$this->errors[] = "Error: Not a valid image file.<br />";
			$this->admin_errors[] = "Error: Not a valid image file.";
			return false;
		} elseif($this->check_for_php && $this->file_contains_php($this->temp_path)) {
			// A valid image can still contain embedded PHP.
			// Log the attempt to upload an improper image file.
			$this->errors[] = "Spurious image: Not a valid image file.<br />";
			$this->admin_errors[] = "Error: File contains PHP code.";
			return false;
		}
		
		// Determine the target_path
		$target_path = SITE_ROOT .DS. 'Public' .DS. $this->upload_dir .DS. $this->filename;
	  
		// Make sure a file doesn't already exist in the target location
		if(file_exists($target_path)) {
			$this->errors[] = "The file {$this->filename} already exists.";
			return false;
		}
	
		// Attempt to move the file 
		if(move_uploaded_file($this->temp_path, $target_path)) {
			// remove execute file permissions from the file
			$this->removeExePerm($target_path);	
			
			// resize the image to fit for cropping
			// $max_resolution = 700;
			// $file = $target_path; // file name is used in other functions
			// $this->resize_image($file, $max_resolution);

			// Save target path in session so can be used for cropping.
			$_SESSION['target_path_name'] = $this->filename;
			
			// Save properties of the photo
			$_SESSION['user_photo_user_id'] = $this->user_id;
			$_SESSION['user_photo_filename'] = $this->filename;
			$_SESSION['user_photo_type'] = $this->type;
			$_SESSION['user_photo_size'] = $this->size;
			$_SESSION['user_photo_visible'] = true;
			$_SESSION['user_photo_date_created'] = current_Date_Time();
			
			// We are done with temp_path, the file isn't there anymore
			unset($this->temp_path);
			
			return true;
			/* 	// Saving is done after the user has cropped the image
			// Save a corresponding entry to the database
			if($this->create()) {
				// We are done with temp_path, the file isn't there anymore
				unset($this->temp_path);
				return true;
			} */
		} else {
			// File was not moved.
			$this->errors[] = "The file upload failed.";
			$this->admin_errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
			return false;
		}
	}
	
	function resize_image($file, $max_resolution) {
		if (file_exists($file)) {
			// Get the file extension
			$extension = $this->file_extension($file);
			$quality = 75; // This value is in percentage

			// create an image copy either jpeg, jpg, gif or png
			$original_image = $this->newImageCreate($file, $extension);
			
			// Check the image orientation
			// Get the image properties
			$exif = exif_read_data($file);
			// Adjust the image orientation
			// Check if the rotation in session is specified by the user
			if (isset($_SESSION['rotationInfo']) && !empty($_SESSION['rotationInfo'])) {
				$rotation = $_SESSION['rotationInfo'];
				// CSS rotation anngle and PHP rotation angle are different, so the +90 degree is added to convert the CSS rotation angle to PHP rotation angle.
				if ($rotation == -90 || $rotation == 270) {
					$rotation = 90 + 90;
				} elseif ($rotation == -180 || $rotation == 180) {
					$rotation = 180 + 90;
				} elseif ($rotation == -270 || $rotation == 90) {
					$rotation = 270 + 90;
				}
				$original_image = imagerotate($original_image, $rotation, 0);
			} elseif (!empty($exif['Orientation'])) {			
        if ($exif['Orientation'] === 3) {
        	$original_image = imagerotate($original_image, 180, 0);
        } elseif ($exif['Orientation'] === 6) {
        	$original_image = imagerotate($original_image, -90, 0);
        } elseif ($exif['Orientation'] === 8) {
        	$original_image = imagerotate($original_image, 90, 0);
        }
	    }
	    // unset the session rotation value after use so it will not affect other images that will be uploaded after
	    unset($_SESSION['rotationInfo']);
			
			// resolution
			$original_width = imagesx($original_image);
			$original_height = imagesy($original_image);
			
			// Determine if the image is portrait or landscape
			// make the max resolution to be equal to the smaller height, so that 
			// the image will fit into the square box which will be used for cropping
			if ($original_width > $original_height) {
				// Image is landscape
				// Make the max resoultion to be equal to the width
				$ratio = $max_resolution / $original_width;
				$new_width = $max_resolution;
				$new_height = $original_height * $ratio;
			} else {
				// Image is portrait
				// Make the max resoultion to be equal to the height
				$ratio = $max_resolution / $original_height;
				$new_height = $max_resolution;
				$new_width = $original_width * $ratio;
			}
			
			if ($original_image) {
				// This is the resized version of the original image
				$new_image = imagecreatetruecolor($new_width, $new_height);
				imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
				
				// This will create a new image with a quality of 90
				// get an image output of the original cropped image either in jpeg, jpg, png or gif
				$this->imageOutput($new_image, $file, $quality, $extension);
				// Destroy the original image created, to free space
				imagedestroy($original_image);
			}
		}
	}
	
	function newImageCreate($filename,$extension){
		switch($extension){
			case 'jpeg':
				return imagecreatefromjpeg($filename);
				break ;
			case 'jpg':
				return imagecreatefromjpeg($filename);
				break ;
			case 'png':
				return imagecreatefrompng($filename);
				break ;
			case 'gif':
				return imagecreatefromgif($filename);
				break ;
		}
	}

	function imageOutput($image_p, $new_name, $quality, $extension){
		switch($extension){
			case 'jpeg':
				return imagejpeg($image_p, $new_name, $quality);
				break ;
			case 'jpg':
				return imagejpeg($image_p, $new_name, $quality);
				break ;
			case 'png':
				return imagepng($image_p, $new_name, $quality);
				break ;
			case 'gif':
				return imagegif($image_p, $new_name);
				break ; 
		}
	}

	function emptyImageIcon() {
		// Determine the empty image path
		return $this->upload_dir.DS.$this->dummyImage;
	}
	
	// Common Database Methods
	public static function find_all() {
		return self::find_by_sql("SELECT * FROM ".self::$table_name);
	}
  
	public static function find_by_id($id=0) {
		global $database;
		// 
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id=".$database->escape_value($id)." LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	// check for empty ids that has not been saved into
	public static function getEmptyId() {
		global $database;
		$sql = "SELECT id FROM ".self::$table_name." ORDER BY id ASC";
		$result_set = $database->query($sql);

		// create an array for the ids
		$ids = array();
		while ($row = mysqli_fetch_assoc($result_set)) { 
			// Gets the table ids 
			$ids[] = $row["id"]; 
		}

		// create an empty array for the empty ids
		$emptyIds = array();
		// Create an array the size of the last index in the array ids
		$arrayCheck = range(1, end($ids));
		// Get the difference of the arrays from the typical array size and the saved ids
		$emptyIds = array_diff($arrayCheck, $ids);
		// reset the keys of the empty array ids
		$newEmptyIdsArray = array_values($emptyIds);
		// get the first element in the empty array ids
		$firstEmptyVal = "";
		if (!empty($newEmptyIdsArray)) {
			$firstEmptyVal = $newEmptyIdsArray["0"];
		}

		// return the first element of the empty array ids
		return $firstEmptyVal;
	}
	
	// This will only find the photos that are displayable in the photo gallery it would only output one record
	public static function find_by_userId($user_id=0) {
		global $database;
		$user_id = $database->escape_value($user_id);
		
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE user_id=".$user_id." LIMIT 1");

		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	// This will return all the specified users images that is visible
	public static function find_user_images($user_id=0) {
		global $database;
		$user_id = $database->escape_value($user_id);
		
		return self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE user=".$user_id." AND visible=1");
	}
	
	// This will only return the number of pictures counted
	public static function count_all() {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".self::$table_name;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		// The single number is returned in a row like an array, so the array_shift function is used to pull out the number.
		return array_shift($row);
	}
	
	// Display the comments specified with the user id
	public function comments() {
		// call the find_comments_on() method from the Comment class through a static method.
		return Comment::find_comments_on($this->id);
	}
  
	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}

	private static function instantiate($record) {
		// Could check that $record exists and is an array
		$object = new self;
		// Simple, long-form approach:
		// $object->id 				= $record['id'];
		// $object->username 	= $record['username'];
		// $object->password 	= $record['password'];
		// $object->first_name = $record['first_name'];
		// $object->last_name 	= $record['last_name'];
		
		// More dynamic, short-form approach:
		foreach($record as $attribute=>$value){
		  if($object->has_attribute($attribute)) {
		    $object->$attribute = $value;
		  }
		}
		return $object;
	}
	
	private function has_attribute($attribute) {
		// We don't care about the value, we just want to know if the key exists
		// Will return true or false
		return array_key_exists($attribute, $this->attributes());
	}

	protected function attributes() { 
		// return an array of attribute names and their values
		$attributes = array();
		foreach(self::$db_fields as $field) {
			if(property_exists($this, $field)) {
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}
	
	protected function sanitized_attributes() {
		global $database;
		$clean_attributes = array();
		// sanitize the values before submitting
		// Note: does not alter the actual value of each attribute
		foreach($this->attributes() as $key => $value){
			$clean_attributes[$key] = $database->escape_value($value);
		}
		return $clean_attributes;
	}
	
	// Updated with a custom save method
	/* public function save() {
		// A new record won't have an id yet.
		return isset($this->id) ? $this->update() : $this->create();
	} */
	
	// Delete the photograph specified
	public function destroy() {
		// First remove the database entry
		if($this->delete()) {
			// then remove the file
			// Note that even though the database entry is gone, this object 
			// is still around (which lets us use $this->image_path()).
			$target_path = SITE_ROOT.DS.'Public'.DS.$this->image_path();
			return unlink($target_path) ? true : false;
		} else {
			// database delete failed
			return false;
		}
	}
	
	// Provides plain-text error messages for file upload errors.
	private function file_upload_error($error_integer) {
		$upload_errors = array(
			// http://php.net/manual/en/features.file-upload.errors.php
			UPLOAD_ERR_OK 				=> "No errors.",
			UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
		    UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
		    UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
		    UPLOAD_ERR_NO_FILE 		=> "No file.",
		    UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
		    UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
		    UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
		);
		return $upload_errors[$error_integer];
	}
	
	// This will help determine the path to retrieve the uploaded file
	public function image_path() {
		return $this->upload_dir.DS.$this->filename;
	}
	
	// This will help determine the path to retrieve the uploaded file
	public function image_path2() {
		return "Public".DS.$this->upload_dir.DS.$this->filename;
	    // return SITE_ROOT.DS.'Public'.DS.$this->upload_dir.DS.$this->filename;
	}
	
	// This will make a path for the image name passed in to be used in the href
	public function make_image_path($imageName) {
		global $database;
		$imageName = $database->escape_value($imageName);
		// return SITE_ROOT.DS.'Public'.DS.$this->upload_dir.DS.$imageName;
		// return "/Public/".$this->upload_dir."/".$imageName;
		return DS."Public".DS.$this->upload_dir.DS.$imageName;
	}
	
	// Get the size of the image from the database and print its size either in bytes, KB, MB
	public function size_as_text() {
		if($this->size < 1024) {
			return "{$this->size} bytes";
		} elseif($this->size < 1048576) {
			$size_kb = round($this->size/1024);
			return "{$size_kb} KB";
		} else {
			$size_mb = round($this->size/1048576, 1);
			return "{$size_mb} MB";
		}
	}
	
	public function create() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection

		// check if there are empty ids
		$emptyIndex = $this->getEmptyId();
		if (!empty($emptyIndex)) {
			$this->id = $emptyIndex;
		}

		$attributes = $this->sanitized_attributes();
		$sql = "INSERT INTO ".self::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		
		if($database->query($sql)) {
			$this->id = $database->insert_id();
			return true;
		} else {
			return false;
		}
	}

	public function update() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - UPDATE table SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
		  $attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=". $database->escape_value($this->id);
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}

	public function delete() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - DELETE FROM table WHERE condition LIMIT 1
		// - escape all values to prevent SQL injection
		// - use LIMIT 1
		$sql = "DELETE FROM ".self::$table_name;
		$sql .= " WHERE id=". $database->escape_value($this->id);
		$sql .= " LIMIT 1";
		$database->query($sql);
		
		return ($database->affected_rows() == 1) ? true : false;
	
		// NB: After deleting, the instance of User still 
		// exists, even though the database entry does not.
	}
	
	public function hidePhoto($photoId=0) {
		global $database;
		
		// Find the photograph by its ID
		$photoId = $database->escape_value($photoId);
		$photoHandle = static::find_by_id($photoId);
		$photoHandle->visible = false;
		
		return $photoHandle->update();
	}
	
	// Create the current time and date and return it in MYSQL format
	public function current_Date_Time() {
		// Get the current time
		$dateTime = time();
		// Convert the current time to (Y:M:D H:M:S) which MYSQL takes
		// $mysql_dateTime = strftime("%Y-%m-%d %H:%M:%S", $dateTime);
		$mysql_dateTime = strftime("%F %T", $dateTime);
		// return the formated time of (Y:M:D H:M:S) for MYSQL
		return $mysql_dateTime;
	}

	/* public function is_image($path) {
		$a = getimagesize($path);
		$image_type = $a[2];
     
		if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,	IMAGETYPE_PNG , IMAGETYPE_BMP))){
			return true;
		}
		return false;
	} */
	
	private function is_image($path) {
		// Use the width to check if the getimagesize() function was successful. If there is an image file a width will be specified else if there is none no width will be specified
		if (getimagesize($path)[0] == 0) {
			$image_type = 'error';
			$this->errors['image_error'] = "The file uploaded is not an image type.";
			
			return false;
		} else {
			$a = getimagesize($path);
			$image_type = $a['mime'];
			
			if(!in_array($image_type, array("image/gif", "image/jpeg", "image/png", "image/bmp"))){
				$this->errors['image_error'] = "The picture is not a an accepted image format.";
				return false;
			}
			return true;
		}
		
		// list($width, $height, $image_type, $attr) = getimagesize($path);
		// array("image/gif", "image/jpeg", "image/png", "image/bmp") array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP)
		
	}
	
	// Sanitizes a file name to ensure it is harmless
	private function sanitize_file_name($filename) {
		// Remove characters that could alter file path.
		// I disallowed spaces because they cause other headaches.
		// "." is allowed (e.g. "photo.jpg") but ".." is not.
		$filename = preg_replace("/([^A-Za-z0-9_\-\.]|[\.]{2})/", "", $filename);
		// basename() ensures a file name and not a path
		$filename = basename($filename);
		return $filename;
	}
	
	// Returns the file permissions in octal format.
	private function file_permissions($file) {
		// fileperms returns a numeric value
		$numeric_perms = fileperms($file);
		// but we are used to seeing the octal value
		$octal_perms = sprintf('%o', $numeric_perms);
		return substr($octal_perms, -4);
	}
	
	// This function will remove the execute file permissions attached to an image uploaded to prevent malicious program from running.
	private function removeExePerm($file_path) {
		// remove execute file permissions from the file
		if(chmod($file_path, 0644)) {
			$file_permissions = $this->file_permissions($file_path);
			return true;
		} else {
			$this->errors['permission_error'] = "Error: Execute permissions could not be removed.<br />";
			return false;
		}
	}
	
	// Returns the file extension of a file
	private function file_extension($file) {
		$path_parts = pathinfo($file);
		return $path_parts['extension'];
	}

	// Searches the contents of a file for a PHP embed tag
	// The problem with this check is that file_get_contents() reads 
	// the entire file into memory and then searches it (large, slow).
	// Using fopen/fread might have better performance on large files.
	private function file_contains_php($file) {
		$contents = file_get_contents($file);
		$position = strpos($contents, '<?php');
		return $position !== false;
	}
}

?>