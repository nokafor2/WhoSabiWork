<?php
/* 
Admin Class for Ayuanorama database containing functions to get data from the database.
*/

// require_once("../../includes/initialize.php");
// require_once("initialize.php");
// If it's going to need the database, then it's 
// probably smart to require it before we start.
// require_once('database.php');
require_once(LIB_PATH.DS.'database.php');

class Admin {
	
	// This is variable will be used to dynamically choose a table from the database
	protected static $table_name = "admins";
	// This is a list of all fields that are database fields.
	protected static $db_fields = array('id', 'first_name', 'last_name', 'username', 'password', 'phone_number', 'phone_validated', 'admin_email', 'account_status', 'date_created', 'date_edited');
	// Every column in the admin table have an attribute
	// These values gets instantiated when the any of the SQL request is asked.
	public $id;
	public $first_name;
	public $last_name;
	public $username;
	public $password;
	public $phone_number;
	public $phone_validated;
	public $admin_email;
	public $account_status;
	public $date_created;
	public $date_edited;
	
	// This function returns all the admins from the admins table in a database. It will also return them as an array of objects.
	public static function find_all() {
		// The 'table_name' is used to dynamically choose a table from the database
		// Previous SQL: "SELECT * FROM admins"
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
    }
  
    // This function will return a single admin record from the admin table in a database. It will also return them as objects.
    public static function find_by_id($id=0) {
		global $database;
		// The 'table_name' is used to dynamically choose a table from the database
		// Previous SQL: "SELECT * FROM admins WHERE id={$id} LIMIT 1"
		// 'self' is replaced with 'static' because of use of late static binding to occur at run time.
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE id={$id} LIMIT 1");
		/* $found = $database->fetch_array($result_set);
		return $found; */
		// This will return only the first element in the array. Using the array_shift() will convert the array type to its content type, this case it is an object.
		return !empty($result_array) ? array_shift($result_array) : false;
		// $result_array;
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
	
	// This function will return a single admin record from the admin table in a database using the username. It will also return them as objects.
  public static function find_by_username($username) {
		global $database;
		$safe_username = $database->escape_value($username);
		// The 'table_name' is used to dynamically choose a table from the database
		// 'self' is replaced with 'static' because of use of late static binding to occur at run time.
		$sql = "SELECT * FROM ".static::$table_name." WHERE username='{$safe_username}' LIMIT 1";
		$result_array = static::find_by_sql($sql);
		/* $found = $database->fetch_array($result_set);
		return $found; */
		// This will return only the first element in the array. Using the array_shift() will convert the array type to its content type, this case, it is an object.
		return !empty($result_array) ? array_shift($result_array) : false;
		// $result_array;
  }

  // This function will return a single admin record from the admin table in a database using the email address. It will also return them as objects.
  public static function find_by_email($email) {
		global $database;
		$safe_email = $database->escape_value($email);
		// 'self' is replaced with 'static' because of use of late static binding to occur at run time.
		$sql = "SELECT * FROM ".static::$table_name." WHERE admin_email='{$safe_email}' LIMIT 1";
		$result_array = static::find_by_sql($sql);
		// This will return only the first element in the array. Using the array_shift() will convert the array type to its content type, this case, it is an object.
		return !empty($result_array) ? array_shift($result_array) : false;
		// $result_array;
  }

  // This function will return a single admin record from the admin table in a database using the phone_number. It will also return them as objects.
  public static function find_by_phone_number($phone_number) {
		global $database;
		$safe_phone_number = $database->escape_value($phone_number);
		// 'self' is replaced with 'static' because of use of late static binding to occur at run time.
		$sql = "SELECT * FROM ".static::$table_name." WHERE phone_number='{$safe_phone_number}' LIMIT 1";
		$result_array = static::find_by_sql($sql);
		// This will return only the first element in the array. Using the array_shift() will convert the array type to its content type, this case, it is an object.
		return !empty($result_array) ? array_shift($result_array) : false;
		// $result_array;
  }

  public static function find_by_column_name($columnName, $variable) {
		global $database;
		$columnName = $database->escape_value($columnName);
		$variable = $database->escape_value($variable);
		// The 'table_name' is used to dynamically choose a table from the database
		// 'self' is replaced with 'static' because of use of late static binding to occur at run time.
		$sql = "SELECT * FROM ".static::$table_name." WHERE {$columnName}='{$variable}' LIMIT 1";
		$result_array = static::find_by_sql($sql);
		// This will return only the first element in the array. Using the array_shift() will convert the array type to its content type, this case, it is an object.
		return !empty($result_array) ? array_shift($result_array) : false;
  }
  
  // This is a function that takes an sql query and processes it in a database.
  // The result set gotten of the various rows will be processed  and it will be instantiated
  public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		// An array to store the result set is created.
		$object_array = array();
		// Search through all the rows of the result_set (array) with the loop
		while ($row = $database->fetch_array($result_set)) {
		  // Uses the 'instantiate' function to instantiate all the attributes and values as an object. A static call is used.
		  $object_array[] = static::instantiate($row);
		}
		return $object_array; // An array containing objects is returned.
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
	
	// Authenticate the username and password that will be passed to verify if it is in the database. A default value will be passed in the argument of the function.
	public function authenticate($loginId="", $loginType="", $admin_password="") {
		global $database;
		
	 	// The values of the username and password are refined using the escape_value() function to check it.
		$loginId = $database->escape_value($loginId);
		$loginType = $database->escape_value($loginType);
		$admin_password = $database->escape_value($admin_password);
		
		// SQL to get password of admin from database
		if ($loginType === 'email') {
			$admin = static::find_by_email($loginId);
			$sql = "SELECT * FROM ".static::$table_name." WHERE admin_email='{$loginId}' LIMIT 1";
		} elseif ($loginType === 'phone_number') {
			$admin = static::find_by_phone_number($loginId);
			$sql = "SELECT * FROM ".static::$table_name." WHERE phone_number='{$loginId}' LIMIT 1";
		} else {
			$admin = static::find_by_username($loginId);
			$sql = "SELECT * FROM ".static::$table_name." WHERE username='{$loginId}' LIMIT 1";
		}		
		$result_set = $database->query($sql);
		$row = mysqli_fetch_assoc($result_set);
		$db_password = $row["password"];
		
		if ($admin) {
			// found admin, now check password
			// $this->password is the saved password in the database
			// password_verify(); PHP default password for checking hashed passwords.
			if ($this->password_check($admin_password, $db_password)) {
				// password_verify($admin_password, $db_password)
				// $this->password_check($admin_password, $db_password)
				// password matches
				return $admin;
			} else {
				// password does not match
				return false;
			}
			
		} else {
			// admin not found
			return false;
		}		
	}

	// Old authenticate function
	/* public function authenticate($username="", $admin_password="") {
		global $database;
		
		 // The values of the username and password are refined using the escape_value() function to check it.
		$username = $database->escape_value($username);
		$admin_password = $database->escape_value($admin_password);
		
		$admin = static::find_by_username($username);
		
		// SQL to get password of admin from database
		$sql = "SELECT * FROM ".static::$table_name." WHERE username='{$username}' LIMIT 1";
		$result_set = $database->query($sql);
		$row = mysqli_fetch_assoc($result_set);
		$db_password = $row["password"];
		
		// echo "<br/>";
		// echo "The admin password passed in is :".$admin_password;
		// echo "<br/>";
		// echo "The admin password from database is :".$db_password;
		// echo "<br/>";
		// echo "The admin hashed password is :".password_verify($admin_password, $db_password);
		// echo "<br/>";
		if ($admin) {
			// found admin, now check password
			// $this->password is the saved password in the database
			// password_verify(); PHP default password for checking hashed passwords.
			if ($this->password_check($admin_password, $db_password)) {
				// password_verify($admin_password, $db_password)
				// $this->password_check($admin_password, $db_password)
				// password matches
				return $admin;
			} else {
				// echo "Password does not match: ".$admin_password; 
				// password does not match
				return false;
			}
			
		} else {
			// admin not found
			return false;
		}
	} */
	
	public function password_encrypt($password){
		// Process of hashing, salting and encrypting a password
		$hash_format = "$2y$10$"; //2y signifies Blowfish should be used and 10 or more is the "cost" parameter, the number of times the blowfish is to be run.
		// blowfish uses salt that are 22 charaters or more, so ensure you use at least more.
		$salt_length = 22; // Blowfish salts should be 22-characters or more
		$salt = $this->generate_salt($salt_length);
		$format_and_salt = $hash_format.$salt;
		$hash = crypt($password, $format_and_salt);
		return $hash;
	}
	
	public function generate_salt($length) {
		// Not 100% unique, not 100$ random, but good enough for a salt
		// MD5 returns 32 characters
		$unique_random_string = md5(uniqid(mt_rand(), true));
		
		// Valid characters for a salt are [a-zA-Z0-9./] Base64 return '+' instead of the '.', so the '+' has to be eliminated too
		$base64_string = base64_encode($unique_random_string);
		
		// But not '+' which is valid in base64 encoding
		$modified_base64_string = str_replace('+', '.', $base64_string);
		
		// Truncate string to the correct length
		$salt = substr($modified_base64_string, 0, $length);
		
		return $salt;
	}
	
	public function password_check($password, $existing_hash) {
		// existing hash contains format and salt at start
		$hash = crypt($password, $existing_hash);
		// echo "The password hash reproduced is: ".$hash;
		// echo "<br/>";
		if ($hash === $existing_hash) {
			return true;
		} else {
			return false;
		}
	}

	// This returns the full name of the admin from the admins table in the database
  public function full_name() {
		if(isset($this->first_name) && isset($this->last_name)) {
		  return $this->first_name . " " . $this->last_name;
		} else {
		  return "";
		}
  }
	
	// This is a method that instantiate all the values from a table in a database into an object
	private static function instantiate($record) {
		// Could check that $record exists and is an array
		
		// Simple, long-form approach:
		// the get_called_class() is useful in inheritance to find out the class that is calling a parent function
		$class_name = get_called_class(); 
		$object = new $class_name; // This is used to make an instantiation of the Class. Its similar to ($admin = new Admin();)
		// $object->id 			= $record['id'];
		// $object->username 	= $record['username'];
		// $object->password 	= $record['password'];
		// $object->first_name  = $record['first_name'];
		// $object->last_name 	= $record['last_name'];
		// return $object;
		
		// More dynamic, short-form approach:
		foreach($record as $attribute=>$value){
		  if($object->has_attribute($attribute)) {
			// this is saving a value through a reference
		    $object->$attribute = $value;
		  }
		}
		return $object; // returns an instance of the class created that will be used.
	}
	
	// This function checks if the attributes of record from a table in a database exists and returns them
	private function has_attribute($attribute) {
	  // get_object_vars returns an associative array with all attributes 
	  // (incl. private ones!) as the keys and their current values as the value
	  $object_vars = $this->attributes();
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  return array_key_exists($attribute, $object_vars);
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
	
	// returns an array of attribute keys and their values
	protected function attributes() { 
	  // This will return all the classes including the private and protected ones. Using this method will reduce the security 
	  // return get_object_vars($this);
	  
	  // return an array of attribute names and their values
	  $attributes = array(); // creates an array attributes
	  foreach(static::$db_fields as $field) {
	    if(property_exists($this, $field)) {
	      $attributes[$field] = $this->$field;
	    }
	  }
	  return $attributes;
	}

	// Sanitized attribute is a refined form of attribute. It returns the escaped values gotten. 
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
	
	// This will create an object if it doesn't exist and is needed to or it will save it. It will help check if the object is in the database or not.
	// Uses an id given to check if it exists. Then it determines if it need to create the record or it will update it.
	public function save() {
	  // A new record won't have an id yet.
	  return isset($this->id) ? $this->update() : $this->create();
	}
	
	// Chapter 09, Video 05 introduced abstraction of table name
	// Chapter 09, Video 05 introduced abstracting the attributes
	// Create a admin record and insert it to the database
	public function create() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		// id is not included in the query because it is set to be auto incremented in the database
		// The name of the table is abstracted so the function can be used recursively or dynamically. if {self::$table_name} it will cause an error because static variable don't work well with {}. So this text concatenation approach is preferred. 

		// check if there are empty ids
		$emptyIndex = $this->getEmptyId();
		if (!empty($emptyIndex)) {
			$this->id = $emptyIndex;
		}

		$attributes = $this->sanitized_attributes();
    $sql = "INSERT INTO ".static::$table_name." (";
		/* $sql .= "first_name, last_name, username, password, "; 
		$sql .= "phone_number, admin_email, date_created"; */
		// abstracting the attributes. The join() function will concatenate the attributes with comma separated between them.
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		// The join() function concatenates the values gotten from array_value() separated with comma
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		// Used to troubleshoot the SQL query for errors
		// echo $sql."<br/>";
		
		if($database->query($sql)) {
			// update the id inserted into the database by calling the database insert_id() method.
			$this->id = $database->insert_id();
			return true;
		} else {
			return false;
		}
	}
	
	// update a admin record in the database
	public function update() {
	  global $database;
		// Don't forget your SQL syntax and good habits:
		// - UPDATE table SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		// Abstracting the SQL command
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
			// the values are enclosed in single quotes
		  $attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".static::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		/* $sql .= "first_name='". $database->escape_value($this->first_name) ."', ";
		$sql .= "last_name='". $database->escape_value($this->last_name) ."', ";
		$sql .= "username='". $database->escape_value($this->username) ."', ";
		$sql .= "password='". $database->escape_value($this->password) ."', ";
		$sql .= "phone_number='". $database->escape_value($this->phone_number) ."', ";
		$sql .= "admin_email='". $database->escape_value($this->admin_email) ."' "; */
		
		// a space is added in front of where to mitigate for the space between the previous SQL line command
		$sql .= " WHERE id=". $database->escape_value($this->id);
		
		// Used to troubleshoot the SQL query for errors
		// echo $sql."<br/>";
		
		$database->query($sql);
		// affected_rows is used to check that only one record was updated to know the update was sucessful or not.
		return ($database->affected_rows() == 1) ? true : false;
	}
	
	public function updateColumn($columnName) {
	  global $database;
		// Don't forget your SQL syntax and good habits:
		// - UPDATE table SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		// Abstracting the SQL command
		
		// Concatenate the SQL string
		$sql = "UPDATE ".static::$table_name." SET ";
		$sql .= "{$columnName}='". $database->escape_value($this->{$columnName}) ."' ";
		// a space is added in front of where to mitigate for the space between the previous SQL line command
		$sql .= " WHERE id=". $database->escape_value($this->id);
		
		// Used to troubleshoot the SQL query for errors
		// echo $sql."<br/>";
		
		$database->query($sql);
		// affected_rows is used to check that only one record was updated to know the update was successful or not.
		return ($database->affected_rows() == 1) ? true : false;
	}
	
	public function delete() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - DELETE FROM table WHERE condition LIMIT 1
		// - escape all values to prevent SQL injection
		// - use LIMIT 1
		$sql = "DELETE FROM ".static::$table_name." ";
		$sql .= "WHERE id=". $database->escape_value($this->id);
		$sql .= " LIMIT 1";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	
		// NB: After deleting, the instance of Admin still 
		// exists, even though the database entry does not.
		// This can be useful, as in:
		//   echo $admin->first_name . " was deleted";
		// but, for example, we can't call $admin->update() 
		// after calling $admin->delete().
	}
	
	public function deactivate($id) {
		global $database;
		$id = $database->escape_value($id);
		
		$admin = static::find_by_id($id);
		$admin->account_status = 'deactivated';
		$admin->date_edited = current_Date_Time();
		
		return $admin->update();
	}
}

?>