<?php
/* 
vehicle category Class for Ayuanorama database containing functions to get data from the database.
*/

// require_once("../../includes/initialize.php");
// require_once("initialize.php");
// If it's going to need the database, then it's 
// probably smart to require it before we start.
// require_once('database.php');
require_once(LIB_PATH.DS.'database.php');

class Vehicle_Category {
	
	// This is variable will be used to dynamically choose a table from the database
	protected static $table_name = "vehicle_categories";
	// This is a list of all fields that are database fields.
	protected static $db_fields = array('id', 'customers_id', 'car', 'bus', 'truck');
	// Every column in the vehicle category table have an attribute
	// These values gets instantiated when the any of the SQL request is asked.
	public $id;
	public $customers_id;
	public $car;
	public $bus;
	public $truck;
	
	// This function returns all the vehicle_categories from the vehicle_categories table in a database. It will also return them as an array of objects.
	public static function find_all() {
		// The 'table_name' is used to dynamically choose a table from the database
		// Previous SQL: "SELECT * FROM vehicle_categories"
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
    }
  
    // This function will return a single vehicle category record from the vehicle_category table in a database. It will also return them as objects.
    public static function find_by_id($id=0) {
		global $database;
		// The 'table_name' is used to dynamically choose a table from the database
		// Previous SQL: "SELECT * FROM vehicle_categories WHERE id={$id} LIMIT 1"
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
	
	// Get the input of the customer from the vehicle_categories table using the customers_id
	public static function find_by_customerId($customers_id=0) {
		global $database;
		// 
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE customers_id=".$database->escape_value($customers_id));
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
	
	public function selected_vehicle_categoty(){
		if ($this->car == TRUE) {
			return "Cars";
		} elseif ($this->bus == TRUE) {
			return "Buses";
		} elseif ($this->truck == TRUE) {
			return "Trucks";
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
		$object = new $class_name; // This is used to make an instantiation of the Class. Its similar to ($user = new User();)
		
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
	
	// Get the vehicle types which are attributes of this class.
	public function getVehicleTypes() {
		$classAttributes = $this->sanitized_attributes();
		// Eliminate the the id and customers_id from the array
		$slicedArray = array_slice($classAttributes, 2, 3, TRUE);
		// Return the array keys only
		$arrayKeysOnly = array_keys($slicedArray);
		
		return $arrayKeysOnly;
	}
	
	// This will create an object if it doesn't exist and is needed to or it will save it. It will help check if the object is in the database or not.
	// Uses an id given to check if it exists. Then it determines if it need to create the record or it will update it.
	public function save() {
	  // A new record won't have an id yet.
	  return isset($this->id) ? $this->update() : $this->create();
	}
	
	// Chapter 09, Video 05 introduced abstraction of table name
	// Chapter 09, Video 05 introduced abstracting the attributes
	// Create a vehicle category record and insert it to the database
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
	
	// update a vehicle category record in the database
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
	
	public function updateColumn($car, $bus, $truck) {
	  global $database;
		// Don't forget your SQL syntax and good habits:
		// - UPDATE table SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		// Abstracting the SQL command
		
		// Escape the inputs for passed in 
		$car = $database->escape_value($car);
		$bus = $database->escape_value($bus);
		$truck = $database->escape_value($truck);
		$sql = "UPDATE ".static::$table_name." SET ";
		$sql .= "{$car}='". $database->escape_value($this->car) ."', ";
		$sql .= "{$bus}='". $database->escape_value($this->bus) ."', ";
		$sql .= "{$truck}='". $database->escape_value($this->truck) ."' ";
		
		// a space is added in front of where to mitigate for the space between the previous SQL line command
		$sql .= " WHERE customers_id=". $database->escape_value($this->customers_id);
		// echo $sql;
		// echo "<br/>";
		$database->query($sql);
		// affected_rows is used to check that only one record was updated to know the update was sucessful or not.
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
}

?>