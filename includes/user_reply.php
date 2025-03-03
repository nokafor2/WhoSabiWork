<?php

// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');
require_once(LIB_PATH.DS.'session.php');

class User_Reply {

	protected static $table_name="users_replies";
	protected static $db_fields=array('id', 'comment_id', 'user_id_reply', 'customer_id_reply', 'customers_id', 'author', 'body', 'created');

	public $id;
	public $comment_id;
	public $user_id_reply;
	public $customer_id_reply;
	public $customers_id;
	public $author;
	public $body;
	public $created;

	// This makes a reply that was sent to a user
	public static function make($customersId, $userOrCusId, $author="Anonymous", $body="", $commentId, $accountType) {
		global $session;
		
		if(!empty($customersId) && !empty($userOrCusId) && !empty($author) && !empty($body) && !empty($commentId) && !empty($accountType)) {
			// an instance of a class is created in this fuction because the function is a static one. The this keyword can't be used in here. so the created instance will be used to reference the variables in the User_Reply class.
			$reply = new User_Reply();
			
			$reply->comment_id = $commentId;
			$reply->customers_id = $customersId;
			if ($accountType === 'user') {
				$reply->user_id_reply = $userOrCusId;
				$reply->customer_id_reply = NULL;
			} else {
				$reply->user_id_reply = NULL;
				$reply->customer_id_reply = $userOrCusId;
			}
			$reply->author = $author;
			$reply->body = $body;
			$reply->created = strftime("%Y-%m-%d %H:%M:%S", time());
			
			// The instance of the object is returned as opposed to a specific variable of the instance. With the instance you can reference any of its variable or its function.
			return $reply;
		} else {
			return false;
		}
	}
	
	// This makes a reply that was sent to a customer
	public static function make_to_customer($customersId, $cusId, $author="Anonymous", $body="", $commentId) {
		global $session;
		
		if(!empty($customersId) && !empty($cusId) && !empty($author) && !empty($body) && !empty($commentId)) {
			// an instance of a class is created in this fuction because the function is a static one. The this keyword can't be used in here. so the created instance will be used to reference the variables in the User_Reply class.
			$reply = new User_Reply();
			
			$reply->comment_id = $commentId;
			$reply->customers_id = $customersId;
			$reply->user_id_reply = NULL;
			$reply->customer_id_reply = $cusId;
			$reply->author = $author;
			$reply->body = $body;
			$reply->created = strftime("%Y-%m-%d %H:%M:%S", time());
			
			// The instance of the object is returned as opposed to a specific variable of the instance. With the instance you can reference any of its variable or its function.
			return $reply;
		} else {
			return false;
		}
	}
	
	// Use a desceding order so that it will be arranged from the most recent.
	public static function find_replies_on($customersId=0) {
		global $database;
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE customers_id=" .$database->escape_value($customersId);
		$sql .= " ORDER BY created DESC";
		return self::find_by_sql($sql);
	}
	
	// Use a desceding order so that it will be arranged from the most recent.
	public static function find_replies_on_comment($customersId=0, $commentId=0) {
		global $database;
		$customersId = $database->escape_value($customersId);
		$commentId = $database->escape_value($commentId);
		
		$sql = "SELECT * FROM " . self::$table_name;
		$sql .= " WHERE customers_id=" .$customersId;
		$sql .= " AND comment_id=" .$commentId;
		$sql .= " ORDER BY created DESC";
		return self::find_by_sql($sql);
	}
	
	// Common Database Methods
	public static function find_all() {
		return self::find_by_sql("SELECT * FROM ".self::$table_name);
	}
  
	public static function find_by_id($id=0) {
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
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
  
	public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}
	
	// This will only return the number of replies counted
	public static function count_all() {
		global $database;
		$sql = "SELECT COUNT(*) FROM ".self::$table_name;
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		// The single number is returned in a row like an array, so the array_shift function is used to pull out the number.
		return array_shift($row);
	}
	
	// Count all the replys from a particular customer using the customer's id
	// This will only return the number of replies counted
	public static function count_by_id($id=0) {
		global $database;
		$id = $database->escape_value($id);
		
		$sql = "SELECT COUNT(*) FROM ".self::$table_name." WHERE customers_id={$id}";
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		// The single number is returned in a row like an array, so the array_shift function is used to pull out the number.
		return array_shift($row);
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
	
	public function save() {
		// A new record won't have an id yet, so it will create a new entry
		// If an id exists, it will update it.
		return isset($this->id) ? $this->update() : $this->create();
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
		// echo $sql."</br>";
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
	}

}

?>