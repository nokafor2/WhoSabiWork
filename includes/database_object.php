<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class DatabaseObject {
	
	// This is variable will be used to dynamically choose a table from the database
	protected static $table_name = "users";

	// Common Database Methods
	
	
	
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
}