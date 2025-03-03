<?php
/* 
Customer Class for Ayuanorama database containing functions to get data from the database.
*/

// require_once("../../includes/initialize.php");
// If it's going to need the database, then it's 
// probably smart to require it before we start.
// require_once('database.php');
require_once(LIB_PATH.DS.'database.php');

Class Customer {
	
	// This is variable will be used to dynamically choose a table from the database
	protected static $table_name = "customers";
	// This is a list of all fields that are database fields.
	protected static $db_fields = array('id', 'first_name', 'last_name', 'gender', 'username', 'password', 'phone_number', 'phone_validated', 'customer_email', 'business_title', 'business_page', 'account_status', 'reset_token', 'fb_user_id', 'fb_access_token', 'google_user_id', 'google_access_token', 'date_created', 'date_edited');
	// Every column in the User table have an attribute
	// These values gets instantiated when the any of the SQL request is asked.
	public $id;
	public $first_name;
	public $last_name;
	public $gender;
	public $username;
	public $password;
	public $phone_number;
	public $phone_validated;
	public $customer_email;
	public $business_title;
	public $business_page;
	public $account_status;
	public $reset_token;
	public $fb_user_id;
	public $fb_access_token;
	public $google_user_id;
	public $google_access_token;
	public $date_created;
	public $date_edited;
	
	// This function returns all the users from the users table in a database
	public static function find_all() {
		return self::find_by_sql("SELECT * FROM customers");
  }
	
	// This function will return a single user record from the user table in a database. It will also return them as objects.
  public static function find_by_id($id=0) {
		global $database;
		$result_array = self::find_by_sql("SELECT * FROM customers WHERE id={$id} LIMIT 1");
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
	
	public static function find_by_username($username) {
		global $database;
		$safe_username = $database->escape_value($username);
		// 'self' is replaced with 'static' because of use of late static binding to occur at run time.
		$sql = "SELECT * FROM ".static::$table_name." WHERE username='{$safe_username}' LIMIT 1";
		$result_array = static::find_by_sql($sql);
		/* $found = $database->fetch_array($result_set);
		return $found; */
		// This will return only the first element in the array. Using the array_shift() will convert the array type to its content type, this case, it is an object.
		return !empty($result_array) ? array_shift($result_array) : false;
		// $result_array;
  }

  // This function will return a single customer record from the customer table in a database using the email address. It will also return them as objects.
  public static function find_by_email($email) {
		global $database;
		$safe_email = $database->escape_value($email);
		// 'self' is replaced with 'static' because of use of late static binding to occur at run time.
		$sql = "SELECT * FROM ".static::$table_name." WHERE customer_email='{$safe_email}' LIMIT 1";
		$result_array = static::find_by_sql($sql);
		// This will return only the first element in the array. Using the array_shift() will convert the array type to its content type, this case, it is an object.
		return !empty($result_array) ? array_shift($result_array) : false;
		// $result_array;
  }

  // This function will return a single customer record from the customer table in a database using the phone_number. It will also return them as objects.
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
		// Previous SQL: "SELECT * FROM users WHERE id={$id} LIMIT 1"
		// 'self' is replaced with 'static' because of use of late static binding to occur at run time.
		$sql = "SELECT * FROM ".static::$table_name." WHERE {$columnName}='{$variable}' LIMIT 1";
		$result_array = static::find_by_sql($sql);
		// This will return only the first element in the array. Using the array_shift() will convert the array type to its content type, this case, it is an object.
		return !empty($result_array) ? array_shift($result_array) : false;
  }
	
	// This is a function that takes an SQL query and processes it in a table in a database.
  // The result set gotten of the various rows will be processed  and it will be instantiated
  public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		// An array to store the result set is created.
		$object_array = array();
		// Search through all the rows of the result_set (array) with the loop
		while ($row = $database->fetch_array($result_set)) {
		  // Uses the 'instantiate' function to instantiate all the attributes and values as an object. A static call is used.
		  $object_array[] = self::instantiate($row);
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
	
	// Authenticate the loginType and password that will be passed to verify if it is in the database. A default value will be passed in the argument of the function.
	public function authenticate($loginId="", $loginType="", $customer_password="") {
		global $database;
		
		// The values of the username and password are refined using the escape_value() function to check it.
		$loginId = $database->escape_value($loginId);
		$loginType = $database->escape_value($loginType);
		$customer_password = $database->escape_value($customer_password);

		// SQL to get password of customer from database
		if ($loginType === 'email') {
			$customer = static::find_by_email($loginId);
			$sql = "SELECT * FROM ".static::$table_name." WHERE customer_email='{$loginId}' LIMIT 1";
		} elseif ($loginType === 'phone_number') {
			$customer = static::find_by_phone_number($loginId);
			$sql = "SELECT * FROM ".static::$table_name." WHERE phone_number='{$loginId}' LIMIT 1";
		} else {
			$customer = static::find_by_username($loginId);
			$sql = "SELECT * FROM ".static::$table_name." WHERE username='{$loginId}' LIMIT 1";
		}
		
		$result_set = $database->query($sql);
		$row = mysqli_fetch_assoc($result_set);
		$db_password = $row["password"];
		
		if ($customer) {
			// found customer, now check password			
			// password_verify(); PHP default password for checking hashed passwords.
			if ($this->password_check($customer_password, $db_password)) {
				// password matches
				return $customer;
			} else {
				// password does not match
				return false;
			}
			
		} else {
			// customer not found
			return false;
		}
	}


	// Old authentication function for just username
	/* public function authenticate($username="", $customer_password="") {
		global $database;
		
		// The values of the username and password are refined using the escape_value() function to check it.
		$username = $database->escape_value($username);
		$customer_password = $database->escape_value($customer_password);

		$customer = static::find_by_username($username);
		
		// SQL to get password of customer from database
		$sql = "SELECT * FROM ".static::$table_name." WHERE username='{$username}' LIMIT 1";
		$result_set = $database->query($sql);
		$row = mysqli_fetch_assoc($result_set);
		$db_password = $row["password"];
		
		if ($customer) {
			// found customer, now check password			
			// password_verify(); PHP default password for checking hashed passwords.
			if ($this->password_check($customer_password, $db_password)) {
				// password matches
				return $customer;
			} else {
				// password does not match
				return false;
			}
			
		} else {
			// customer not found
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
		if ($hash === $existing_hash) {
			return true;
		} else {
			return false;
		}
	}
	
	// This returns the full name of the user from the customers table in the database
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
		$object = new self; // This is used to make an instantiation of the Class. Its similar to ($user = new User();)
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
	    // $object_vars = get_object_vars($this);
		$object_vars = $this->attributes();
	    // We don't care about the value, we just want to know if the key exists
	    // Will return true or false
	    return array_key_exists($attribute, $object_vars);
	}
	
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
	// Create a user record and insert it to the database
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
		$sql .= "phone_number, user_email, date_created"; */
		
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
	
	// update a user record in the database
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
		$sql .= "user_email='". $database->escape_value($this->user_email) ."' "; */
		
		// a space is added in front of where to mitigate for the space between the previous SQL line command
		$sql .= " WHERE id=". $database->escape_value($this->id);
		
		// Used to troubleshoot the SQL query for errors
		// echo $sql."<br/>";
		
		$database->query($sql);
		// affected_rows is used to check that only one record was updated to know the update was sucessful or not.
		return ($database->affected_rows() == 1) ? true : false;
	}
	
	// update a column in a user record in the database
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
	
		// NB: After deleting, the instance of User still 
		// exists, even though the database entry does not.
		// This can be useful, as in:
		//   echo $user->first_name . " was deleted";
		// but, for example, we can't call $user->update() 
		// after calling $user->delete().
	}
	
	public function deactivate($id) {
		global $database;
		$id = $database->escape_value($id);
		
		$customer = static::find_by_id($id);
		// static::find_by_id($id);
		$customer->account_status = 'deactivated';
		// $self::account_status = 'deactivated';
		$customer->date_edited = current_Date_Time();
		// $customer::date_edited = current_Date_Time();
		
		return $customer->update();
	}
	
	public function comments(){
		return User_Comment::find_comments_on($this->id);
	}

	/* Reset token functions */

	private function reset_token() {
		return md5(uniqid(rand()));
	}

	// Looks up a user and sets their reset_token to
	// the given value. Can be used both to create and
	// to delete the token.
	private function set_customer_reset_token($username, $token_value) {
		global $database;
		$username = $database->escape_value($username);
		$token_value = $database->escape_value($token_value);
		$customer = static::find_by_username($username);
		
		if(!empty($customer)) {
			$customer->reset_token = $token_value;
			$customer->date_edited = $this->current_Date_Time();
			$customer->update();
			return true;
		} else {
			return false;
		}
		
	}
	
	// Optional check to see if token is also recent
	public function token_is_recent($token) {
		global $database;
		$token = $database->escape_value($token);
		$customerStatic = static::find_by_column_name('reset_token', $token);
		$max_elapsed = 60 * 60 * 0.5; // Convert 30 minutes to seconds
		$stored_time = strtotime($customerStatic->date_edited); 
		// echo "<br/> The stored time was: ".$stored_time." <br/>";
		return ($stored_time + $max_elapsed) >= time();
	}

	// Add a new reset token to the customer
	public function create_reset_token($username) {
		global $database;
		$username = $database->escape_value($username);
		$token = $this->reset_token();
		return $this->set_customer_reset_token($username, $token);
	}

	// Remove any reset token for this customer.
	public function delete_reset_token($username) {
		global $database;
		$username = $database->escape_value($username);
		$token = null;
		return $this->set_customer_reset_token($username, $token);
	}

	// Returns the customer record for a given reset token.
	// If token is not found, returns null.
	public function find_customer_with_token($token) {
		global $database;
		$token = $database->escape_value($token);
		$validate = new Validation();
		if(!$validate->has_presence($token)) {
			// We were expecting a token and didn't get one.
			return null;
		} else {
			$customer = static::find_by_column_name('reset_token', $token);
			// Note: find_one_in_fake_db returns null if not found.
			return $customer;
		}
	}

	// A function to email the reset token to the email address on file for this customer.
	// This is a placeholder since we don't have email abilities set up in the demo version.
	public function email_reset_token($username) {
		global $database;
		global $session;
		$username = $database->escape_value($username);
		$customer = static::find_by_column_name('username', $username);
		
		if(!empty($customer)) {
			// This is where you would connect to your emailer
			// and send an email with a URL that includes the token.
			$from = 'support@whosabiwork.com';
			$senderName = 'WhoSabiWork';
			$to = $customer->user_email;
			if (isset($to)) {
				$title = "Reset Password Request";
				$body = '<img src="cid:whoSabiWorkLogo">';
				$body .= "<p>You can use the link below to reset your password.</p>
				
				<p>
					<a href='https://www.whosabiwork.com/Public/resetPassword.php?token=".u($customer->reset_token)."&account=customer'>https://www.whosabiwork.com/Public/resetPassword.php?token=".u($customer->reset_token)."&account=customer</a>
				</p>

				<p>If you did not make this request, you do not need to take any action. Your password cannot be changed without clicking the above link to verify the request.</p>";
				
				// This should be commented when the website is in production mode and the sendMail function uncommented.
				$emailOutcome = sendMailFxn($from, $senderName, $to, $title, $body);
				sendEmailOutcome($emailOutcome);
				// echo $body;
				return true;
			} else {
				return false;
			}				
		} else {
			return false;
		}
	}

	/* End of reset token functions */

	/* Search for customer by full name, business name, phone number with details provided in the search bar */
	public function searchData($searchVal, $searchPage) {
		global $database;
		// trim the search val for spaces
		$searchVal = trim($searchVal);

		// text to be searched in the database
		// Escape any malicious text before sending to the database
		$searchVal = $database->escape_value($searchVal);
		// web page where the search originated from to determine what table to search
		$searchPage = $database->escape_value($searchPage);

		if ($searchPage === 'servicePage.php') {
			$businessCategory = 'technician';
		} elseif ($searchPage === 'sparePartPage.php') {
			$businessCategory = 'spare_part_seller';
		} elseif ($searchPage === 'artisanPage.php') {
			$businessCategory = 'artisan';
		} else {
			$businessCategory = 'seller';
		}
		
		// Declare variables
		$foundData = array(); 
		$foundData1 = array();
		$foundData2 = array();
		$searchArray = explode(" ", $searchVal);
		// Search for business title	
			
		if (count($searchArray) >= 1) {
			$sql = "SELECT id, first_name, last_name, gender, username, phone_number, phone_validated, customer_email, business_title, account_status FROM ".static::$table_name." WHERE account_status = 'active' AND id IN (";
			$sql .= "SELECT customers_id FROM business_categories WHERE business_categories.".$businessCategory." = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT id FROM ".static::$table_name." WHERE business_title LIKE '%".$searchVal."%'));";

			$foundData = self::find_by_sql($sql);
		}

		// Search for full name
		if (count($searchArray) > 1) {
			$sql = "SELECT id, first_name, last_name, gender, username, phone_number, phone_validated, customer_email, business_title, account_status FROM ".static::$table_name." WHERE account_status = 'active' AND id IN (";
			$sql .= "SELECT customers_id FROM business_categories WHERE business_categories.".$businessCategory." = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT id FROM ".static::$table_name." WHERE first_name LIKE '%".$searchArray[0]."%' AND last_name LIKE '%".$searchArray[1]."%' OR first_name LIKE '%".$searchArray[1]."%' AND last_name LIKE '%".$searchArray[0]."%'));";

			$foundData1 = self::find_by_sql($sql);
		}
			
		// Search for single name, username, numbers, email
		foreach ($searchArray as $key => $value) {
			$sql = "SELECT id, first_name, last_name, gender, username, phone_number, phone_validated, customer_email, business_title, account_status FROM ".static::$table_name." WHERE account_status = 'active' AND id IN (";
			$sql .= "SELECT customers_id FROM business_categories WHERE business_categories.".$businessCategory." = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT id FROM ".static::$table_name." WHERE first_name LIKE '%".$value."%' OR last_name LIKE '%".$value."%' OR username LIKE '%".$value."%' OR phone_number LIKE '%".$value."%' OR customer_email LIKE '%".$value."%'));";
			
			$data = self::find_by_sql($sql);
			// Get only a 2x2 array so that it can be easily merged with the other array.
			$foundData2 = array_merge($foundData2, $data);
		}

		$mergedData = array_merge($foundData, $foundData1, $foundData2);

		// Eliminate duplicates
		// These functions will eliminate duplicates of the arrays in array.
		// Using array_unique will not work, since the array values of array are not compated as strings
		$uniqueData = array_intersect_key($mergedData, array_unique(array_map('serialize', $mergedData)));
		return $uniqueData;
	}

	/* Find Customer by the first name or last name or both */
	public function findCustomerByName($searchVal) {
		global $database;
		// trim the variable to be searched
		$searchVal = trim($searchVal);

		// text to be searched in the database
		// Escape any malicious text before sending to the database
		$searchVal = $database->escape_value($searchVal);

		// Declare variables 
		$foundData1 = array();
		$foundData2 = array();
		$searchArray = explode(" ", $searchVal);
		
		// Search for full name
		if (count($searchArray) > 1) {
			$sql = "SELECT id, first_name, last_name, gender, username, phone_number, phone_validated, customer_email, account_status, date_created, date_edited FROM ".static::$table_name." WHERE first_name LIKE '%".$searchArray[0]."%' AND last_name LIKE '%".$searchArray[1]."%';";

			$foundData1 = self::find_by_sql($sql);
		}
			
		// Search for single name, username, numbers, email
		foreach ($searchArray as $key => $value) {
			$sql = "SELECT id, first_name, last_name, gender, username, phone_number, phone_validated, customer_email, account_status, date_created, date_edited FROM ".static::$table_name." WHERE first_name LIKE '%".$value."%' OR last_name LIKE '%".$value."%';";
			
			$data = self::find_by_sql($sql);

			// Get only a 2x2 array so that it can be easily merged with the other array.
			$foundData2 = array_merge($foundData2, $data);
		}

		$mergedData = array_merge($foundData1, $foundData2);

		// Eliminate duplicates
		// These functions will eliminate duplicates of the arrays in array.
		// Using array_unique will not work, since the array values of array are not compated as strings
		$uniqueData = array_intersect_key($mergedData, array_unique(array_map('serialize', $mergedData)));
		return $uniqueData;
	}
}

?>