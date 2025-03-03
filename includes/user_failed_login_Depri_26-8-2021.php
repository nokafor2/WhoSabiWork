<?php
/* 
Failed Login Class of users for Whosabiwork database containing functions to get data from the database.
*/

// require_once("../../includes/initialize.php");
// require_once("initialize.php");
// If it's going to need the database, then it's 
// probably smart to require it before we start.
// require_once('database.php');
require_once(LIB_PATH.DS.'database.php');

class User_Failed_Login {
	
	// This is variable will be used to dynamically choose a table from the database
	protected static $table_name = "user_failed_logins";
	// This is a list of all fields that are database fields.
	protected static $db_fields = array('id', 'user_id', 'user_username', 'record', 'last_time');
	// Every column in the failed_login table have an attribute
	// These values gets instantiated when the any of the SQL request is asked.
	public $id;
	public $user_id;
	public $user_username;
	public $record;
	public $last_time;
	
	// This function returns all the failed_logins from the failed_logins table in a database. It will also return them as an array of objects.
	public static function find_all() {
		// The 'table_name' is used to dynamically choose a table from the database
		// Previous SQL: "SELECT * FROM failed_logins"
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
    }
  
    // This function will return a single failed_login record from the failed_login table in a database. It will also return them as objects.
    public static function find_by_id($id=0) {
		global $database;
		// The 'table_name' is used to dynamically choose a table from the database
		// Previous SQL: "SELECT * FROM failed_logins WHERE id={$id} LIMIT 1"
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
	
	// This function will return a single failed_login record from the failed_login table in a database using the username. It will also return them as objects.
    public static function find_by_user_username($username) {
		global $database;
		$safe_username = $database->escape_value($username);
		// The 'table_name' is used to dynamically choose a table from the database
		// Previous SQL: "SELECT * FROM failed_logins WHERE id={$id} LIMIT 1"
		// 'self' is replaced with 'static' because of use of late static binding to occur at run time.
		$sql = "SELECT * FROM ".static::$table_name." WHERE user_username='{$safe_username}' LIMIT 1";
		$result_array = static::find_by_sql($sql);
		/* $found = $database->fetch_array($result_set);
		return $found; */
		// This will return only the first element in the array. Using the array_shift() will convert the array type to its content type, this case, it is an object.
		return !empty($result_array) ? array_shift($result_array) : false;
		// $result_array;
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

	// This is a method that instantiate all the values from a table in a database into an object
	private static function instantiate($record) {
		// Could check that $record exists and is an array
		
		// Simple, long-form approach:
		// the get_called_class() is useful in inheritance to find out the class that is calling a parent function
		$class_name = get_called_class(); 
		$object = new $class_name; // This is used to make an instantiation of the Class. Its similar to ($failed_login = new failed_login();)
		
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
	// Create a failed_login record and insert it to the database
	public function create() {
		global $database;
		// The name of the table is abstracted so the function can be used recursively or dynamically. if {self::$table_name} it will cause an error because static variable don't work well with {}. So this text concatenation approach is preferred. 
		
		// check if there are empty ids
		$emptyIndex = $this->getEmptyId();
		if (!empty($emptyIndex)) {
			$this->id = $emptyIndex;
		}

		$attributes = $this->sanitized_attributes();
	    $sql = "INSERT INTO ".static::$table_name." (";
		
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
	
	// update a failed_login record in the database
	public function update() {
	  global $database;
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
			// the values are enclosed in single quotes
		  $attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".static::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		
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
	}
	
	/* Begining of throttle functions */
	
	// Record or update the failed attempt of a user in the database
	public function record_user_failed_login($username) {
		global $database;
		$username = $database->escape_value($username);
		$failed_login = static::find_by_user_username($username);

		if(empty($failed_login)) {
			// Find an id that matches the username in the users table in the database
			$userStatic = User::find_by_username($username);
			if (!empty($userStatic)) {
				// Get the customers id and save it
				$username_id = $userStatic->id;
			} else {
				$username_id = NULL;
			}
			
			// Create a record of a new failed login
			$this->user_id = $username_id;
			$this->user_username = $username;
			$this->record = 1; 
			$this->last_time = $this->current_Date_Time();
			$this->create();
		} else {
			if ($failed_login->record >= 3) {				
				// Restart an existing failed_login record
				$failed_login->record = 1; 
				$failed_login->last_time = $failed_login->current_Date_Time();
				$failed_login->update();
			} else {
				// Update an existing failed_login record
				$failed_login->record = $failed_login->record + 1; 
				$failed_login->last_time = $failed_login->current_Date_Time();
				$failed_login->update();
			}
		}
		
		return true;
	}
	
	// Clear the number of failures after the user successful login
	public function clear_user_failed_logins($username) {
		global $database;
		$username = $database->escape_value($username);
		$failed_login = static::find_by_user_username($username);

		if(!empty($failed_login)) {
			// reset the record of login trials and update in the database
			$failed_login->record = 0;
			$failed_login->last_time = $failed_login->current_Date_Time();
			$failed_login->update();
		}
		
		return true;
	}
	
	// Returns the number of minutes to wait until logins are allowed again for a user.
	public function throttle_failed_user_logins($username) {
		$throttle_at = 3;
		$delay_in_minutes = 10;
		$delay = 60 * $delay_in_minutes; // time delay in seconds
		
		global $database;
		$username = $database->escape_value($username);
		$failed_login = static::find_by_user_username($username);

		// Once failure count is over $throttle_at value, 
		// user must wait for the $delay period to pass.
		if(!empty($failed_login) && $failed_login->record >= $throttle_at) {
			$remaining_delay = (strtotime($failed_login->last_time) + $delay) - time();
			$remaining_delay_in_minutes = ceil($remaining_delay / 60);
			
			// Reset the record count to 0 after the delay time has expired
			if ($remaining_delay_in_minutes < 0) {
				$failed_login->record = 0; 
				$failed_login->update();
			}
			return $remaining_delay_in_minutes;
		} else {
			// The user has no record of a failed login attempt.
			// return zero is used to determine the execution of the function when it is called.
			return 0;
		}
	}
	
	/* End of throttle functions */
	
}

?>