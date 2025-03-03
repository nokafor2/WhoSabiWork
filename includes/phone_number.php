<?php

require_once("initialize.php");
// require_once('generic.php');
// require_once('database.php');

class Phone_Number extends DB_Table_Class {
	// This is variable will be used to dynamically choose a table from the database
	protected static $table_name = "phone_numbers";
	// This is a list of all fields that are database fields.
	protected static $db_fields = array('id', 'customers_id', 'whatsapp_number', 'phone_number_1', 'phone_number_2', 'phone_number_3', 'date_created', 'date_edited');
	// Every column in the User table have an attribute
	// These values gets instantiated when the any of the SQL request is asked.
	public $id;
	public $customers_id;
	public $whatsapp_number;
	public $phone_number_1;
	public $phone_number_2;
	public $phone_number_3;
	public $date_created;
	public $date_edited;

	public function __construct() {
		// parent::$table_name = self::$table_name;
	}

	// This is a method that instantiate all the values from a table in a database into an object
	public static function instantiate($record) {
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
	public function has_attribute($attribute) {
	  // get_object_vars returns an associative array with all attributes 
	  // (incl. private ones!) as the keys and their current values as the value
	  $object_vars = $this->attributes();
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  return array_key_exists($attribute, $object_vars);
	}

	/* public function getTableName() {
		return self::$table_name;
	} */

	public function uploadNumber() {
		$this->id = 1;
		$this->customers_id = 1;
		$this->whatsapp_number = "08057368561";
		$this->phone_number_1 = "09093232530";
		$this->phone_number_2 = "09070046964";
		$this->phone_number_3 = "08066877275";
		$this->date_created = $this->current_Date_Time();
		$this->update();

		/* self::$id = "1";
		self::$customers_id = "2";
		self::$whatsapp_number = "08057368560";
		self::$phone_number_1 = "09093232530";
		self::$phone_number_2 = "09070046964";
		self::$phone_number_3 = "08066877275";
		self::$date_created = parent::current_Date_Time();
		self::save(); */
	}

	public function getTableName() {
		$output = "<br/>Child table name is: ".self::$table_name;
		$output .= "<br/>Parent table name is: ".parent::$table_name."<br/><br/>";

		/* $this::$table_name = "Child Table";
		parent::$table_name = "DB_Table_Class Table";
		$output = "<br/>Child table name is: ".self::$table_name;
		$output .= "<br/>Parent table name is: ".parent::$table_name."<br/><br/>"; */		

		return $output;
	}

	public function getClassMethods() {
		$methods = get_class_methods('Phone_Number');
		$output = "";
		foreach($methods as $method) {
			$output .= "Method: ".$method."<br/>";
		}
		return $output;
	}

	public function listVars() {
		$vars = get_class_vars('Phone_Number');
		$output = "<br/>";
		foreach ($vars as $var => $value) {
			if (!is_array($value)) {
				$output .= "{$var} : {$value} <br/>";
			}
		}
		return $output;
	}

	public function getClassVars() {
		$output = "<br/>Values using 'this' instance: ";
		$output .= "<br/>customers_id: ".$this->customers_id;
		$output .= "<br/>whatsapp_number: ".$this->whatsapp_number;
		$output .= "<br/>phone_number_1: ".$this->phone_number_1;
		$output .= "<br/>phone_number_2: ".$this->phone_number_2;
		$output .= "<br/>phone_number_3: ".$this->phone_number_3;
		$output .= "<br/>date_created: ".$this->date_created;
		return $output;
	}

	public function testCount() {
		return "<br/>The number of rows is: ".self::count_all()."<br/><br/>";
	}
}

$phoneNumber = new Phone_Number();
echo "The current time is: ".$phoneNumber->current_Date_Time()."<br/>";

// $phoneNumber->uploadNumber();
echo $phoneNumber->getTableName();

/* $classes = get_declared_classes();
foreach($classes as $class) {
	echo "Class: ".$class."<br/>";
} */

$methods = get_class_methods('Phone_Number');
echo "<br/>Methods for Phone_Number class are: <br/>";
foreach($methods as $method) {
	echo "Method: ".$method."<br/>";
}

// property_exists('class_name', 'variable');

echo "<br/><br/>".$phoneNumber->getClassMethods()."<br/><br/>";

/* $includedFiles = get_included_files();
echo "Included files are: <br/><br/>";
echo "<pre>";
print_r($includedFiles);
echo "</pre>"; */

$vars = get_class_vars('Phone_Number');
foreach ($vars as $var => $value) {
	echo "{$var} : {$value} <br/>";
}

// echo $phoneNumber->listVars();

// $phoneNumber->uploadNumber();

echo $phoneNumber->listVars();

echo $phoneNumber->getClassVars()."<br/><br/>";

/* echo "<br/>Declaring variables from public scope: ";
Phone_Number::$id = "1";
Phone_Number::$customers_id = "2";
Phone_Number::$whatsapp_number = "08057368560";
Phone_Number::$phone_number_1 = "09093232530";
Phone_Number::$phone_number_2 = "09070046964";
Phone_Number::$phone_number_3 = "08066877275";
Phone_Number::$date_created = $phoneNumber->current_Date_Time();

echo $phoneNumber->listVars(); */

// echo "<br/>The number of rows is: ".Phone_Number::count_all()."<br/><br/>";
echo $phoneNumber->testCount();

/* $queryResult = Phone_Number::find_by_id(3);
echo "<pre>";
echo "The query result is: ";
print_r($queryResult);
echo "</pre>"; */

$queryResult = Phone_Number::find_by_column_name('whatsapp_number', '08057368561');
echo "<pre>";
echo "The query result is: ";
print_r($queryResult);
echo "</pre>";

/*
	For Parent class to work:
	- change all self varible to static
	- change all overriding private functions to public in the child class

*/
?>