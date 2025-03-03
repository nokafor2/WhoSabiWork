<?php
/* 
Car_Brand Class for Ayuanorama database containing functions to get data from the database.
*/

// require_once("../../includes/initialize.php");
// require_once("initialize.php");
// If it's going to need the database, then it's 
// probably smart to require it before we start.
// require_once('database.php');
require_once(LIB_PATH.DS.'database.php');

class Car_Brand {
	
	// This is variable will be used to dynamically choose a table from the database
	protected static $table_name = "car_brands";
	// This is a list of all fields that are database fields.
	protected static $db_fields = array('id', 'customers_id', 'toyota', 'honda', 'nissan', 'mazda', 'mitsubishi', 'suzuki', 'subaru', 'scion', 'kia', 'hyundai', 'acura', 'infinity', 'lexus', 'mercedes_benz', 'BMW', 'volkswagen', 'audi', 'ford', 'chrystler', 'dodge','chevrolet', 'GMC', 'peugout', 'renault', 'innoson', 'volvo', 'citroen', 'saturn', 'opetn', 'range_rover', 'hummer');
	// Every column in the car_brand table have an attribute
	// These values gets instantiated when the any of the SQL request is asked.
	public $id, $customers_id;
	public $toyota, $honda, $nissan, $mazda, $mitsubishi, $suzuki, $subaru, $scion, $kia, $hyundai, $acura, $infinity, $lexus, $mercedes_benz, $BMW, $volkswagen, $audi, $ford, $chrystler, $dodge, $chevrolet, $GMC, $peugout, $renault, $innoson, $volvo, $citroen, $saturn, $opel, $range_rover, $hummer;
	
	// This function returns all the car_brands from the car_brands table in a database. It will also return them as an array of objects.
	public static function find_all() {
		// The 'table_name' is used to dynamically choose a table from the database
		// Previous SQL: "SELECT * FROM car_brands"
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
    }
  
    // This function will return a single car_brand record from the car_brand table in a database. It will also return them as objects.
    public static function find_by_id($id=0) {
		global $database;
		// The 'table_name' is used to dynamically choose a table from the database
		// Previous SQL: "SELECT * FROM car_brands WHERE id={$id} LIMIT 1"
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
	
	public static function find_by_customerId($customers_id=0) {
		global $database;
		// 
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE customers_id=".$database->escape_value($customers_id));
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	// Get the selected choices from a customer saved in the database
	public function selected_choices($customerID) {
		global $database;
		$sql = "SELECT * FROM ".static::$table_name." WHERE customers_id = {$customerID}";
		$result_set = $database->query($sql);

		$rowData = array();
		
		while($row = mysqli_fetch_assoc($result_set)){ 
			$rowData = $row;
		}

		$selectedChoices = array();
		// $carTypes = array("toyota", "honda", "nissan", "mazda", "mitsubishi", "suzuki", "subaru", "scion", "kia", "hyundai", "acura", "infinity", "lexus", "mercedes_benz", "BMW", "volkswagen", "audi", "ford", "chrystler", "dodge", "chevrolet", "GMC", "peugout", "renault", "innoson", "volvo", "citroen", "saturn", "opel", "range_rover", "hummer");
		$carTypes = array_keys($this->getVehicleBrands());
		foreach ($rowData as $key => $value) {
			if (in_array($key, $carTypes) && $value == TRUE) {
				$selectedChoices[ucfirst(str_replace("_", " ", $key))] = $value;
			}
		}
		return $selectedChoices;
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
		$object = new $class_name; // This is used to make an instantiation of the Class. Its similar to ($user = new User();)
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
	
	// Get the vehicle brands which are attributes of this class.
	public function getVehicleBrands() {
		$classAttributes = $this->sanitized_attributes();
		// Eliminate the the id and customers_id from the array   , 14, TRUE
		$slicedArray = array_slice($classAttributes, 2);
		// Sort the array alphabetically
		ksort($slicedArray);
		// Return the array keys only which will be the technical_service
		$vehicle_brands = array_keys($slicedArray);
		
		// return alphabetically sorted car brands;
		return array_flip($vehicle_brands); // Use the array keys so that it will be recovered from json.
	}
	
	public function getVehicleBrandsByType() {

	}
	
	// This will create an object if it doesn't exist and is needed to or it will save it. It will help check if the object is in the database or not.
	// Uses an id given to check if it exists. Then it determines if it need to create the record or it will update it.
	public function save() {
	  // A new record won't have an id yet.
	  return isset($this->id) ? $this->update() : $this->create();
	}
	
	// Chapter 09, Video 05 introduced abstraction of table name
	// Chapter 09, Video 05 introduced abstracting the attributes
	// Create a car_brand record and insert it to the database
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
		
		if($database->query($sql)) {
			// update the id inserted into the database by calling the database insert_id() method.
			$this->id = $database->insert_id();
			return true;
		} else {
			return false;
		}
	}
	
	// update a car_brand record in the database
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
		// echo $sql."<br>";		
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
	
		// NB: After deleting, the instance of Car_Brand still 
		// exists, even though the database entry does not.
	}

	public function carBrandsTypeOptions() {
		// get an array of the carBrands types
		// $carBrands = array_keys($this->getCarBrands());
		
		// Get the available carBrands registered
		$carBrands = $this->getAvailableCarBrands();
		// Sort the array in alphabetical order
		asort($carBrands);
		
		$output  = "";
		$output .= "<option value=''>Select</option>";
		foreach ($carBrands as $carBrands) {
			$output .= "<option value='{$carBrand}'>".ucfirst(str_replace("_", " ", $carBrand))."</option>";
		}
		
		return $output;
	}

	public function getAvailableCarBrands($businessCategory) {
		global $database;
		$carBrands = $this->getCarBrands();
		// get an array of the carBr types
		$carBrands = array_keys($carBrands);
		// Generate SQL query to get available carBrands
		$sql = "SELECT * FROM ".static::$table_name." WHERE ".static::$table_name.".customers_id IN (";
		$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
		$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.".$businessCategory." = 1 AND business_categories.customers_id IN (";
		$sql .= "SELECT vehicle_categories.customers_id FROM vehicle_categories WHERE vehicle_categories.car = 1";
		$sql .= ")))";
		$result_set = $database->query($sql);
		
		$selectedCarBrands = array();
		// get the various carBrands in a 2x2 array or table
		while($row = mysqli_fetch_assoc($result_set)){ 
			// Gets the rows of the table 
			$selectedCarBrands[] = $row; 
		}

		$availableCarBrands = array();
		foreach ($carBrands as $key => $value) {
			$selectedColumn = array_column($selectedCarBrands, $value);
			if (in_array(1, $selectedColumn)) {
				$availableCarBrands[] = $value;
			}
		}

		$ucfAvailableCarBrands = array();
		// capitalize the first character of each word
		foreach ($availableCarBrands as $key => $value) {
			$ucfAvailableCarBrands[] = ucfirst($value);
		}
		// Sort the arrays alphabetically in the array
		sort($ucfAvailableCarBrands);
		return $ucfAvailableCarBrands;
	}

	// Get the car_brands which are attributes of this class.
	public function getCarBrands() {
		$classAttributes = $this->sanitized_attributes();
		// Eliminate the the id and customers_id from the array   , 14, TRUE
		$slicedArray = array_slice($classAttributes, 2);
		// Sort the carBrands alphabetically
		ksort($slicedArray);

		// Return the array keys only which will be the carBrands
		$carBrands = array_keys($slicedArray);
		
		// return $technical_services;
		return $slicedArray; // Use the array keys so that it will be recovered from json.
	}
}

?>