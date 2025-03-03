<?php
/* 
Address Class for Ayuanorama database containing functions to get attributes of the address table in the database.
*/

// require_once("../../includes/initialize.php");
// If it's going to need the database, then it's 
// probably smart to require it before we start.
// require_once('database.php');
require_once(LIB_PATH.DS.'database.php');

Class Address {
	// This class will inherit all the functions of the User class.
	
	// This is variable will be used to dynamically choose a table from the database
	protected static $table_name = "addresses";
	// This is a list of all fields that are database fields.
	protected static $db_fields = array('id', 'customers_id', 'address_line_1', 'address_line_2', 'address_line_3', 'town', 'state');
	// Every column in the Address table have an attribute
	// These values gets instantiated when the any of the SQL request is asked.
	public $id;
	public $customers_id;
	public $address_line_1;
	public $address_line_2;
	public $address_line_3;
	public $town;
	public $state;
	
	// This function returns all the users from the users table in a database. It will also return them as an array of objects.
	public static function find_all() {
		return self::find_by_sql("SELECT * FROM addresses");
  }
  
  // This function will return a single user record from the user table in a database. It will also return them as objects.
  public static function find_by_id($id=0) {
		global $database;
		$result_array = self::find_by_sql("SELECT * FROM addresses WHERE id={$id} LIMIT 1");
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
	
	// This returns the complete address of the user specified
	public function full_address() {
		$fullAddress = "";
		$addressLine1 = ucFirst(trim($this->address_line_1));
		$addressLine2 = trim($this->address_line_2);
		$addressLine3 = trim($this->address_line_3);
		$town = ucfirst(trim($this->town));
		$state = ucfirst(trim($this->state));

		if (isset($addressLine1) && ($addressLine1 !== "")) {
			$fullAddress .= $addressLine1;
		}
		if (isset($addressLine2) && ($addressLine2 !== "")) {
			$fullAddress .= ", ".$addressLine2;
		}
		if (isset($addressLine3) && ($addressLine3 !== "")) {
			$fullAddress .= ", ".$addressLine3;
		}
		if (isset($town) && ($town !== "")) {
			$fullAddress .= ", ".$town;
		}
		if (isset($state) && ($state !== "")) {
			$fullAddress .= ", ".$state;
		}

		return $fullAddress;
	}

	// returns an array of addresses ids
	public function find_address_id_using_customer_id($customer_Ids_Array) {
		global $database;
		$addresses = array();
		
		foreach ($customer_Ids_Array as $ids) {
			$sql = "SELECT id FROM `addresses` WHERE customers_id = {$ids}";
			$result_set = $database->query($sql); // Relevant
			$row = mysqli_fetch_assoc($result_set);
			// Get all the address ids of the customers to be displayed
			$addresses[] = "{$row["id"]}";
		}
		
		// returns an array of addresses
		return $addresses;
	}
	
	// This will get the states for either technicians or spare part sellers and it will also check for account status and its more accurate and refined
	function getAvailableStates($vehicleType, $vehicleBrand, $techServOrSpareParts, $businessCategory) {
		global $database;
		$vehicleType = $database->escape_value($vehicleType);
		$vehicleBrand = $database->escape_value($vehicleBrand);
		$techServOrSpareParts = $database->escape_value($techServOrSpareParts);
		$businessCategory = $database->escape_value($businessCategory);

		// check if its a car_brand, bus_brand or truck_brand
		if ($vehicleType === 'car') {
			$motorBrandCategory = 'car_brands';
		} elseif ($vehicleType === 'bus') {
			$motorBrandCategory = 'bus_brands';
		} else {
			$motorBrandCategory = 'truck_brands';
		}

		// check if its a technician or spare part seller
		if ($businessCategory === 'technician') {
			$techOrPartsTable = 'technical_services';
		} else {
			$techOrPartsTable = 'spare_parts';
		}		

		$sql = "SELECT ".static::$table_name.".state FROM ".static::$table_name." WHERE ".static::$table_name.".customers_id IN (";
		$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
		$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.".$businessCategory." = 1 AND business_categories.customers_id IN (";
		$sql .= "SELECT ".$techOrPartsTable.".customers_id FROM ".$techOrPartsTable." WHERE ".$techOrPartsTable.".".$techServOrSpareParts." = 1 AND ".$techOrPartsTable.".customers_id IN (";
		$sql .= "SELECT ".$motorBrandCategory.".customers_id FROM ".$motorBrandCategory." WHERE ".$motorBrandCategory.".".$vehicleBrand." = 1 AND ".$motorBrandCategory.".customers_id IN (";
		$sql .= "SELECT vehicle_categories.customers_id FROM vehicle_categories WHERE vehicle_categories.".$vehicleType." = 1";
		$sql .= ")))));";

		$result_set = $database->query($sql);
		while($row = mysqli_fetch_assoc($result_set)){ 
			$states[] = $row["state"];
		}
		
		$cleanStates = array();
		// capitalize the first letter of states
		foreach ($states as $key => $state) {
			$states[$key] = ucfirst($state);
			// Eliminate white spaces
			if (trim($states[$key]) !== "") {
				$cleanStates[] = $states[$key];
			}
		}
		// Sort the states alphabetically
		$unique_states = array_unique($cleanStates);
		sort($unique_states); // changed from asort()
		return $unique_states;
	}

	function getAvailableTowns($vehicleType, $vehicleBrand, $techServOrSpareParts, $businessCategory, $state) {
		global $database;
		$vehicleType = $database->escape_value($vehicleType);
		$vehicleBrand = $database->escape_value($vehicleBrand);
		$techServOrSpareParts = $database->escape_value($techServOrSpareParts);
		$businessCategory = $database->escape_value($businessCategory);
		$state = $database->escape_value($state);

		// check if its a car_brand, bus_brand or truck_brand
		if ($vehicleType === 'car') {
			$motorBrandCategory = 'car_brands';
		} elseif ($vehicleType === 'bus') {
			$motorBrandCategory = 'bus_brands';
		} else {
			$motorBrandCategory = 'truck_brands';
		}

		// check if its a technician or spare part seller
		if ($businessCategory === 'technician') {
			$techOrPartsTable = 'technical_services';
		} else {
			$techOrPartsTable = 'spare_parts';
		}		

		$sql = "SELECT ".static::$table_name.".town FROM ".static::$table_name." WHERE ".static::$table_name.".state = '".$state."' AND ".static::$table_name.".customers_id IN (";
		$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
		$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.".$businessCategory." = 1 AND business_categories.customers_id IN (";
		$sql .= "SELECT ".$techOrPartsTable.".customers_id FROM ".$techOrPartsTable." WHERE ".$techOrPartsTable.".".$techServOrSpareParts." = 1 AND ".$techOrPartsTable.".customers_id IN (";
		$sql .= "SELECT ".$motorBrandCategory.".customers_id FROM ".$motorBrandCategory." WHERE ".$motorBrandCategory.".".$vehicleBrand." = 1 AND ".$motorBrandCategory.".customers_id IN (";
		$sql .= "SELECT vehicle_categories.customers_id FROM vehicle_categories WHERE vehicle_categories.".$vehicleType." = 1";
		$sql .= ")))));";
		// echo $sql;

		$result_set = $database->query($sql);
		while($row = mysqli_fetch_assoc($result_set)){ 
			$towns[] = $row["town"];
		}
		
		$cleanTowns = array();
		// capitalize the first letter of towns
		foreach ($towns as $key => $state) {
			$towns[$key] = ucfirst($state);
			// Eliminate white spaces
			if (trim($towns[$key]) !== "") {
				$cleanTowns[] = $towns[$key];
			}
		}
		// Eliminate duplicates and then sort the towns alphabetically
		$unique_towns = array_unique($cleanTowns);
		sort($unique_towns); // changed from asort()
		return $unique_towns;
	}

	// This will get the states for either technicians or spare part sellers
	function getStates($vehicleType, $business_cate) {
		global $database;
		$vehicleType = $database->escape_value($vehicleType);
		$business_cate = $database->escape_value($business_cate);

		$sql = "SELECT addresses.state FROM addresses, vehicle_categories, business_categories WHERE vehicle_categories.customers_id = addresses.customers_id AND business_categories.customers_id = addresses.customers_id AND vehicle_categories.{$vehicleType} = 1 AND business_categories.{$business_cate} = 1";
		$result_set = $database->query($sql);
		while($row = mysqli_fetch_assoc($result_set)){ 
			$states[] = $row["state"];
		}
		
		if (empty($states)) {
			return FALSE;
		} else {
			$cleanStates = array();
			// capitalize the first letter of states
			foreach ($states as $key => $state) {
				$states[$key] = ucfirst($state);
				// Eliminate white spaces
				if (trim($states[$key]) !== "") {
					$cleanStates[] = $states[$key];
				}
			}
			// Sort the states alphabetically
			asort($cleanStates);
			$unique_states = array_unique($cleanStates);
			return array_flip($unique_states);
		}
	}
	
	// This will get the towns for either technicians or spare part sellers
	function getTowns($vehicleType, $business_cate, $state) {
		global $database;
		$vehicleType = $database->escape_value($vehicleType);
		$business_cate = $database->escape_value($business_cate);
		$state = $database->escape_value($state);
		
		$sql = "SELECT addresses.state, addresses.town FROM addresses, vehicle_categories, business_categories WHERE vehicle_categories.customers_id = addresses.customers_id AND business_categories.customers_id = addresses.customers_id AND vehicle_categories.{$vehicleType} = 1 AND business_categories.{$business_cate} = 1 AND addresses.state = '".$state."'";
		// echo $sql;
		$result_set = $database->query($sql);
		while($row = mysqli_fetch_assoc($result_set)){ 
			$towns[] = $row["town"];
		}
		
		if (empty($towns)) {
			return FALSE;
		} else {
			$cleanTowns = array();
			// capitalize the first letter of towns
			foreach ($towns as $key => $town) {
				$towns[$key] = ucfirst($town);
				// Eliminate white spaces
				if (trim($towns[$key]) !== "") {
					$cleanTowns[] = $towns[$key];
				}
			}
			// Sort the towns alphabetically
			asort($cleanTowns);
			// Removes duplicate
			$unique_towns = array_unique($cleanTowns);
			// Switches the array keys and values
			return array_flip($unique_towns);
		}
	}
	
	// This is an overloaded method similar to the method above but with one method argument, it will get the states of the artisans 
	function getArtisanStates($artisanType) {
		global $database;
		$artisanType = $database->escape_value($artisanType);
		
		// SELECT addresses.state FROM addresses, artisans, business_categories WHERE artisans.customers_id = addresses.customers_id AND business_categories.customers_id = addresses.customers_id AND artisans.caterer = 1 AND business_categories.artisan = 1

		// $sql = "SELECT addresses.state FROM addresses, artisans WHERE artisans.customers_id = addresses.customers_id AND artisans.{$artisanType} = 1";
		
		$sql = "SELECT DISTINCT state FROM ".static::$table_name." WHERE customers_id IN (";
		$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
		$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.artisan = 1 AND business_categories.customers_id IN (";
		$sql .= "SELECT customers_id FROM artisans WHERE artisans.".$artisanType." = 1";
		$sql .= "))) ORDER BY state";


		$result_set = $database->query($sql);
		while($row = mysqli_fetch_assoc($result_set)){ 
			$states[] = $row["state"];
		}
		
		if (empty($states)) {
			return FALSE;
		} else {
			$cleanedStates = array();
			// capitalize the first letter of states
			foreach ($states as $key => $state) {
				$states[$key] = ucfirst($state);
				// Eliminate white spaces
				if (trim($states[$key]) !== "") {
					$cleanedStates[] = $states[$key];
				}
			}
			// Sort the states alphabetically
			asort($cleanedStates);
			// remove duplicates
			$unique_states = array_unique($cleanedStates);
			// Switches the array keys and values
			// return array_flip($unique_states);
			return $unique_states;
		}
	}
	
	// This will get the towns of the selected artisans
	function getArtisanTowns($artisanType, $state) {
		global $database;
		$artisanType = $database->escape_value($artisanType);
		$state = $database->escape_value($state);
		
		// SELECT addresses.town FROM addresses, artisans, business_categories WHERE artisans.customers_id = addresses.customers_id AND business_categories.customers_id = addresses.customers_id AND artisans.caterer = 1 AND business_categories.artisan = 1
		// $sql = "SELECT addresses.state, addresses.town FROM addresses, artisans WHERE artisans.customers_id = addresses.customers_id AND artisans.{$artisanType} = 1 AND addresses.state = '".$state."'";

		$sql = "SELECT DISTINCT town FROM ".static::$table_name." WHERE state = '".$state."' AND customers_id IN (";
		$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
		$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.artisan = 1 AND business_categories.customers_id IN (";
		$sql .= "SELECT customers_id FROM artisans WHERE artisans.".$artisanType." = 1";
		$sql .= "))) ORDER BY town";

		$result_set = $database->query($sql);
		while($row = mysqli_fetch_assoc($result_set)){ 
			$towns[] = $row["town"];
		}
		
		if (empty($towns)) {
			return FALSE;
		} else {
			$cleanedtowns = array();
			// capitalize the first letter of towns
			foreach ($towns as $key => $town) {
				$towns[$key] = ucfirst($town);
				// Eliminate white spaces
				if (trim($towns[$key]) !== "") {
					$cleanedtowns[] = $towns[$key];
				}
			}
			// Sort the towns alphabetically
			asort($cleanedtowns);			
			// remove duplicates
			$unique_towns = array_unique($cleanedtowns);
			// print_r($towns);
			// Switches the array keys and values
			// return array_flip($unique_towns);
			return $unique_towns;
		}
	}

	// This is an overloaded method similar to the method above but with one method argument, it will get the states of the sellers 
	function getSellerStates($sellerType) {
		global $database;
		$sellerType = $database->escape_value($sellerType);
		
		// $sql = "SELECT addresses.state FROM addresses, sellers WHERE sellers.customers_id = addresses.customers_id AND sellers.{$sellerType} = 1";

		$sql = "SELECT DISTINCT state FROM ".static::$table_name." WHERE customers_id IN (";
		$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
		$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.seller = 1 AND business_categories.customers_id IN (";
		$sql .= "SELECT customers_id FROM sellers WHERE sellers.".$sellerType." = 1";
		$sql .= "))) ORDER BY state";

		$result_set = $database->query($sql);
		while($row = mysqli_fetch_assoc($result_set)){ 
			$states[] = $row["state"];
		}
		
		if (empty($states)) {
			return FALSE;
		} else {
			$cleanedStates = array();
			// capitalize the first letter of states
			foreach ($states as $key => $state) {
				$states[$key] = ucfirst($state);
				// Eliminate white spaces
				if (trim($states[$key]) !== "") {
					$cleanedStates[] = $states[$key];
				}
			}
			// Sort the states alphabetically
			asort($cleanedStates);
			// remove duplicates
			$unique_states = array_unique($cleanedStates);
			// Switches the array keys and values
			// return array_flip($unique_states);
			return $unique_states;
		}
	}
	
	// This will get the towns of the selected sellers
	function getSellerTowns($sellerType, $state) {
		global $database;
		$sellerType = $database->escape_value($sellerType);
		$state = $database->escape_value($state);
		
		// SELECT addresses.town FROM addresses, sellers, business_categories WHERE sellers.customers_id = addresses.customers_id AND business_categories.customers_id = addresses.customers_id AND sellers.caterer = 1 AND business_categories.seller = 1
		// $sql = "SELECT addresses.state, addresses.town FROM addresses, sellers WHERE sellers.customers_id = addresses.customers_id AND sellers.{$sellerType} = 1 AND addresses.state = '".$state."'";

		$sql = "SELECT DISTINCT town FROM ".static::$table_name." WHERE state = '".$state."' AND customers_id IN (";
		$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
		$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.seller = 1 AND business_categories.customers_id IN (";
		$sql .= "SELECT customers_id FROM sellers WHERE sellers.".$sellerType." = 1";
		$sql .= "))) ORDER BY town";

		$result_set = $database->query($sql);
		while($row = mysqli_fetch_assoc($result_set)){ 
			$towns[] = $row["town"];
		}
		
		if (empty($towns)) {
			return FALSE;
		} else {
			$cleanedtowns = array();
			// capitalize the first letter of towns
			foreach ($towns as $key => $town) {
				$towns[$key] = ucfirst($town);
				// Eliminate white spaces
				if (trim($towns[$key]) !== "") {
					$cleanedtowns[] = $towns[$key];
				}
			}
			// Sort the towns alphabetically
			asort($cleanedtowns);			
			// remove duplicates
			$unique_towns = array_unique($cleanedtowns);
			// Switches the array keys and values
			// return array_flip($unique_towns);
			return $unique_towns;
		}
	}
	
	// This function will display all the states within nigeria in a select drop down menu.
	function displayStateOptions() {
		$states = array('abia', 'abuja', 'adamawa', 'akwa_ibom', 'anambra', 'bauchi', 'bayelsa', 'benue', 'borno', 'cross_river', 'delta', 'ebonyi', 'edo', 'ekiti', 'enugu', 'gombe', 'imo', 'jigawa', 'kaduna', 'kano', 'katsina', 'kebbi', 'kogi', 'kwara', 'lagos', 'nassarawa', 'niger', 'ogun', 'ondo', 'osun', 'oyo', 'plateau', 'rivers', 'sokoto', 'taraba', 'yobe', 'zamfara');
		
		// Esure the state are sorted alphabetically
		asort($states);
		
		$output = "";
		// Select value is set to empty, so that validation function will be able to identify if a state is not selected
		$output .= "<option value=''>Select</option>";
		foreach ($states as $state) {
			$output .= "<option value='".$state."'>".ucfirst(str_replace("_", " ", $state))."</option>";
		}
		
		return $output;
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
		$sql .= " WHERE customers_id=". $database->escape_value($this->customers_id);
		
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

	public function find_address_id_by_customer_id($customer_Ids_Array) {
		global $database;

		$customerIds = join(", ", array_values($customer_Ids_Array));

		$sql = "select id FROM ".static::$table_name." WHERE customers_id IN (".$customerIds.");";

		$result_set = $database->query($sql);

		$addressIds = array();
		// get the address ids from the database object
		while($row = mysqli_fetch_assoc($result_set)){ 
			// Gets the rows of the table 
			$addressIds[] = $row["id"]; 
		}

		return $addressIds;
	}
	
}

?>