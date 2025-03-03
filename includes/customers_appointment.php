<?php
/* 
Customers_Appointment Class for Ayuanorama database containing functions to get data from the database.
*/

// require_once("../../includes/initialize.php");
// require_once("initialize.php");
// If it's going to need the database, then it's 
// probably smart to require it before we start.
// require_once('database.php');
require_once(LIB_PATH.DS.'database.php');

class Customers_Appointment{
	
	// This is variable will be used to dynamically choose a table from the database
	protected static $table_name = "customers_appointments";
	// This is a list of all fields that are database fields.
	protected static $db_fields = array('id', 'customers_id', 'customer_name', 'customer_number', 'scheduled_user', 'scheduled_customer', 'appointment_owner', 'appointer_number', 'appointment_date', 'hours', 'appointment_message', 'customer_decision', 'cus_decline_message', 'aptr_cancel_message', 'cus_cancel_message', 'date_created', 'date_edited');
	// Every column in the Customers_Appointment table have an attribute
	// These values gets instantiated when the any of the SQL request is asked.
	public $id;
	public $customers_id;
	public $customer_name;
	public $customer_number;
	public $scheduled_user;
	public $scheduled_customer;
	public $appointment_owner;
	public $appointer_number;
	public $appointment_date;
	public $hours;
	public $appointment_message;
	public $customer_decision;
	public $cus_decline_message;
	public $aptr_cancel_message;
	public $cus_cancel_message;
	public $date_created;
	public $date_edited;
	
	// This function returns all the users from the users table in a database. It will also return them as an array of objects.
	public static function find_all() {
		// The 'table_name' is used to dynamically choose a table from the database
		// Previous SQL: "SELECT * FROM users"
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
    }
  
    // This function will return a single Customers_Appointment record from the Customers_Appointment table in a database. It will also return them as objects.
    public static function find_by_id($id=0) {
		global $database;
		// The 'table_name' is used to dynamically choose a table from the database
		// Previous SQL: "SELECT * FROM users WHERE id={$id} LIMIT 1"
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
	
	// Get the input of the customer from the Customers_Appointment table using the customers_id
	public static function find_by_customerId($customers_id=0) {
		global $database;
		// query the database for a record using the customers_id
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE customers_id=".$database->escape_value($customers_id));
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	// Get the input of the customer from the Customers_Appointment table using the customers_id
	public static function find_by_date_available($customers_id=0, $date_available='') {
		global $database;
		$customers_id = $database->escape_value($customers_id);
		$date_available = $database->escape_value($date_available);
		// query the database for a record using the customers_id
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE customers_id=".$customers_id." AND appointment_date='".$date_available."'");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	// Get all the future appointments of a customer using the current date as a reference
	public static function find_appointments($customers_id=0, $reference_date='') {
		global $database;
		$customers_id = $database->escape_value($customers_id);
		$reference_date = $database->escape_value($reference_date);
		// query the database for a record using the customers_id
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE customers_id=".$customers_id." AND appointment_date>='".$reference_date."'");
		return $result_array;
	}
	
	// This function will return a single user record from the Customers_Appointment table in a database using the username. It will also return them as objects.
    public static function find_by_username($username) {
		global $database;
		$safe_username = $database->escape_value($username);
		// The 'table_name' is used to dynamically choose a table from the database
		// Previous SQL: "SELECT * FROM users WHERE id={$id} LIMIT 1"
		// 'self' is replaced with 'static' because of use of late static binding to occur at run time.
		$sql = "SELECT * FROM ".static::$table_name." WHERE username='{$safe_username}' LIMIT 1";
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
	
	// Get the selected choices from a customer saved in the database
	public function selected_choices($customerID) {
		global $database;
		$sql = "SELECT * FROM ".static::$table_name." WHERE customers_id = {$customerID}";
		$result_set = $database->query($sql); // Relevant

		$rowData = array();
		while($row = mysqli_fetch_assoc($result_set)){ // Relevant
			$rowData = $row;
		}
		
		// Modify
		$selectedChoices = array();
		$serviceTypes = array("engine_service", "mechanical_service", "electrical_service", "air_conditioning_service", "computer_diagnostics_service", "panel_beating_service", "body_work_service", "shock_absorber_service", "ballon_shocks_service", "wheel_balancing_and_alignment_service", "car_wash_service", "towing_service", "buy_cars", "sell_cars");
		foreach ($rowData as $key => $value) {
			if (in_array($key, $serviceTypes) && $value == TRUE) {
				$selectedChoices[ucfirst(str_replace("_", " ", $key))] = $value;
			}
		}
		return $selectedChoices;
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
		$object = new $class_name; // This is used to make an instantiation of the Class. Its similar to ($Customers_Appointment = new Customers_Appointment();)
		
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
	
	// This function converts a day to an integer value
	public function convertDayToInt($day) {
		$day = strtolower($day);
		$weekdays = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
		
		if ($day === 'sunday') {
			return 0;
		} elseif ($day === 'monday') {
			return 1;
		} elseif ($day === 'tuesday') {
			return 2;
		} elseif ($day === 'wednesday') {
			return 3;
		} elseif ($day === 'thursday') {
			return 4;
		} elseif ($day === 'friday') {
			return 5;
		} elseif ($day === 'saturday') {
			return 6;
		}
	}
	
	public function numSecOfDays($numDays) {
		$numDays = (int)$numDays;
		
		return $numSecInDays = (86400 * $numDays);
	}
	
	// This function gets the date of a day parsed in within the current week.
	public function makeDateForDay($day) {
		// convert the date to an integer value
		$dayInt = $this->convertDayToInt($day);
		
		// get the current date properties in an array
		$todayArray = getdate();
		// get the current day of the week in an integer
		$todayInt = $todayArray['wday'];
		
		// get the timestamp of the number seconds elapsed for the current day
		$currentSeconds = $todayArray[0];
		if ($dayInt >= $todayInt) {
			// get the difference in days between the future and current day
			// This will work for current and future day search
			$diffInDays = ($dayInt - $todayInt);
			
			// convert the difference in days to seconds
			$daysInSec = $this->numSecOfDays($diffInDays) + $currentSeconds;
		} else {
			// This will work for past days search
			// $diffInDays = ($todayInt - $dayInt);
			
			// This will work for future day
			$diffInDays = (6 - $todayInt) + $dayInt + 1;
			
			// convert the difference in days to seconds
			// $daysInSec = $currentSeconds - $this->numSecOfDays($diffInDays);
			$daysInSec = $this->numSecOfDays($diffInDays) + $currentSeconds;
		}
		
		// get the properties of the future date
		$dayArray = getdate($daysInSec);
		
		// make a MYSQL date syntax
		// $completeDate = $dayArray['year'].'-'.$dayArray['mon'].'-'.$dayArray['mday'];
		$completeDate = strftime("%F", $daysInSec);
		
		// return the concatenated MYSQL date syntax
		return $completeDate;
	}
	
	public function sundayDateForWeek() {
		$sundayInt = 0;
		// get the current date properties in an array
		$todayArray = getdate();
		// get the current day of the week in an integer
		$todayInt = $todayArray['wday'];
		
		// get the timestamp of the number seconds elapsed for the current day
		$currentSeconds = $todayArray[0];
		
		// get the difference in days between the future and current day
		$diffInDays = $todayInt - $sundayInt;
		
		// convert the difference in days to seconds
		$daysInSec = $currentSeconds - $this->numSecOfDays($diffInDays);
		// get the properties of the future date
		$dayArray = getdate($daysInSec);
		
		// make a MYSQL date syntax
		// $completeDate = $dayArray['year'].'-'.$dayArray['mon'].'-'.$dayArray['mday'];
		$completeDate = strftime("%F", $daysInSec);
		
		// return the concatenated MYSQL date syntax
		return $completeDate;
	}
	
	public function saturdayDateForWeek() {
		$saturdayInt = 6;
		// get the current date properties in an array
		$todayArray = getdate();
		// get the current day of the week in an integer
		$todayInt = $todayArray['wday'];
		
		// get the timestamp of the number seconds elapsed for the current day
		$currentSeconds = $todayArray[0];
		
		// get the difference in days between the future and current day
		$diffInDays = $saturdayInt - $todayInt;
		
		// convert the difference in days to seconds
		$daysInSec = $this->numSecOfDays($diffInDays) + $currentSeconds;
		// get the properties of the future date
		$dayArray = getdate($daysInSec);
		
		// make a MYSQL date syntax
		// $completeDate = $dayArray['year'].'-'.$dayArray['mon'].'-'.$dayArray['mday'];
		$completeDate = strftime("%F", $daysInSec);
		
		// return the concatenated MYSQL date syntax
		return $completeDate;
	}
	
	// This converts the time from the database to the form time
	public function convertDbVarToFormTime($timesFromDb) {
		$timesFromDb = array();
		$timesForForm = array();
		
		$count = 0;
		foreach ($timesFromDb as $dbTime) {
			if ($dbTime === 'eight_to_nine_am') {
				$timesForForm[$count] = '8:00 AM - 9:00 AM';
			} elseif ($dbTime === 'nine_to_ten_am') {
				$timesForForm[$count] = '9:00 AM - 10:00 AM';
			} elseif ($dbTime === 'ten_to_eleven_am') {
				$timesForForm[$count] = '10:00 AM - 11:00 AM';
			} elseif ($dbTime === 'eleven_to_twelve_pm') {
				$timesForForm[$count] = '11:00 AM - 12:00 PM';
			} elseif ($dbTime === 'twelve_to_one_pm') {
				$timesForForm[$count] = '12:00 PM - 1:00 PM';
			} elseif ($dbTime === 'one_to_two_pm') {
				$timesForForm[$count] = '1:00 PM - 2:00 PM';
			} elseif ($dbTime === 'two_to_three_pm') {
				$timesForForm[$count] = '2:00 PM - 3:00 PM';
			} elseif ($dbTime === 'three_to_four_pm') {
				$timesForForm[$count] = '3:00 PM - 4:00 PM';
			}
			$count++;
		}
		
		return $timesForForm;
	}
	
	public function editDbVarToFormTime($dbTime) {
		
		if ($dbTime === 'eight_to_nine_am') {
			return '8:00 AM - 9:00 AM';
		} elseif ($dbTime === 'nine_to_ten_am') {
			return '9:00 AM - 10:00 AM';
		} elseif ($dbTime === 'ten_to_eleven_am') {
			return '10:00 AM - 11:00 AM';
		} elseif ($dbTime === 'eleven_to_twelve_pm') {
			return '11:00 AM - 12:00 PM';
		} elseif ($dbTime === 'twelve_to_one_pm') {
			return '12:00 PM - 1:00 PM';
		} elseif ($dbTime === 'one_to_two_pm') {
			return '1:00 PM - 2:00 PM';
		} elseif ($dbTime === 'two_to_three_pm') {
			return '2:00 PM - 3:00 PM';
		} elseif ($dbTime === 'three_to_four_pm') {
			return '3:00 PM - 4:00 PM';
		}
			
		
		
	}
	
	// This function returns the date of the next sixth day after the present day.
	public function nextSixthDayDate() {
		$nextSixthDay = 6;
		// get the current date properties in an array
		$todayArray = getdate();
		// get the current day of the week in an integer
		$todayInt = $todayArray['wday'];
		
		// get the timestamp of the number seconds elapsed for the current day
		$currentSeconds = $todayArray[0];
		
		// convert the difference in days to seconds
		$daysInSec = $this->numSecOfDays($nextSixthDay) + $currentSeconds;
		// get the properties of the future date
		$dayArray = getdate($daysInSec);
		
		// make a MYSQL date syntax
		// $completeDate = $dayArray['year'].'-'.$dayArray['mon'].'-'.$dayArray['mday'];
		$completeDate = strftime("%F", $daysInSec);
		
		// return the concatenated MYSQL date syntax
		return $completeDate;
	}
	
	// This function returns the 7 days of the week from the present date
	public function weekDatesFromToday() {
		// create an array to store the week dates
		$weekDatesFromToday = array();
		// get the current date properties in an array
		$todayArray = getdate();
		// get the timestamp of the number seconds elapsed for the current day
		$currentSeconds = $todayArray[0];
		// create a loop to store the dates in an array
		for ($i = 0; $i <= 6; $i++) {
			// convert the difference in days to seconds
			$daysInSec = $this->numSecOfDays($i) + $currentSeconds;
			// get the properties of the future date
			$dayArray = getdate($daysInSec);
			
			// make a MYSQL date syntax
			// $weekDatesFromToday[$i] = $dayArray['year'].'-'.$dayArray['mon'].'-'.$dayArray['mday'];
			$weekDatesFromToday[$i] = strftime("%F", $daysInSec);
		}
		
		return $weekDatesFromToday;
	}
	
	// This function returns the 7 weekdays from the present day
	public function weekDaysFromToday() {
		// create an array to store the week dates
		$weekDaysFromToday = array();
		// get the current date properties in an array
		$todayArray = getdate();
		// get the timestamp of the number seconds elapsed for the current day
		$currentSeconds = $todayArray[0];
		// create a loop to store the dates in an array
		for ($i = 0; $i <= 6; $i++) {
			// convert the difference in days to seconds
			$daysInSec = $this->numSecOfDays($i) + $currentSeconds;
			// get the properties of the future date
			$dayArray = getdate($daysInSec);
			// make a MYSQL date syntax
			$weekDaysFromToday[$i] = $dayArray['weekday'];
		}
		
		return $weekDaysFromToday;
	}
	
	public function dateToDay($dateVar) {
		
	}
}

?>