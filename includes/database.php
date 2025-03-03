<?php 
require_once(LIB_PATH.DS."config.php");

class MySQLDatabase {
	
	private $connection; // the handle for connection to the database
	public $last_query; // Tells the last query we executed
	private $magic_quotes_active;
	private $real_escape_string_exists;
	private static $_instance;  
	
	// create a construct for the class
	function __construct() {
		$this->open_connection();
		$this->magic_quotes_active = get_magic_quotes_gpc();
		$this->real_escape_string_exists = function_exists("mysqli_real_escape_string");
	}

  	/**
    	Get an instance of the Database.
    	@return Database 
  	*/
  	public static function getInstance() {
    	if (!self::$_instance) {
      		self::$_instance = new self();
    	}
    	return self::$_instance;
  	}
	
	// Opens connection to the database specified
	public function open_connection() {
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
		// $this->connection = mysqli_connect("localhost", "root", "root", "db_whosabiwork", "3307");

		// Test if connection occured.
		if(mysqli_connect_errno()){
			die("Database connection failed: ".
				mysqli_connect_error().
					" (".mysqli_connect_errno().")"
		    );
		}
	}
	
	public function get_connection(){
		return $this->connection;
	}
	
	public function set_connection($value) {
		$this->connection = $value;
	}
	
	// Closes the connection created for a database opened
	public function close_connection() {
		// 5. Close database connection
		if (isset($this->connection)) {
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}
	
	// returns an associative array of the result set with keys and values. It makes it universal for any other database.
	public function fetch_array($result_set) {
		return mysqli_fetch_array($result_set);
	}
	
	// returns how many rows are in a result set
	public function num_rows($result_set){
		return mysqli_num_rows($result_set);
	}
	
	// returns the last inserted id over the database connection, it could also take an argument of a link to a database
	public function insert_id() {
		// get the last id inserted over the current db connection
		return mysqli_insert_id($this->get_connection());
	}
	
	// returns how many rows were affected (insert, update, replace or delete) by the last query
	public function affected_rows() {
		return mysqli_affected_rows($this->connection);
	}
	
	// confirms query before it is passed to the 'query' function
	private function confirm_query($result) {
		if (!$result) {
			$output  = "Database query failed: " . mysqli_error($this->connection) . "<br/><br/>";
			// Used to troubleshoot query errors
			$output .= "Last SQL query: " . $this->last_query; 
			die($output);
		}
	}
	
	// 2. Perform database query
	public function query($sql){
		$this->last_query = $sql;
		$result = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);
		// Test if there was a query error
		if (!$result){
			die("Database query failed." . mysqli_error($this->connection));
		}
		return $result;
	}
	
	// public function escape_value($string){
	// Having this function is useful incase there is a change in the preparation of mysql function, everything can be changed in one location.
	
		// global $connection;
		
	//	$escaped_string = mysqli_real_escape_string($this->get_connection(), $string);
	//	return $escaped_string;
	// }
	
	// This function prepares values for submission to SQL by removing any unwanted HTML characters that might cause error.
	// Having this function is useful in case there is a change in the preparation of mysql function, everything can be changed in one location.
	public function escape_value( $value ) {
		if( $this->real_escape_string_exists ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysqli_real_escape_string can do the work
			if( $this->magic_quotes_active ) { 
				$value = stripslashes( $value ); 
			}
			$value = mysqli_real_escape_string($this->get_connection(), $value);
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if( !$this->magic_quotes_active ) { $value = addslashes( $value ); }
			// if magic quotes are active, then the slashes already exist
		}
		return $value;
	}
	
	// This function prepares values for submission to SQL. This does the same thing as the 'escape_value' function, but redundant. 
	public function mysql_prep($value) {
		$magic_quotes_active = get_magic_quotes_gpc();
		// i.e PHP >= v4.3.0
		$new_enough_php = function_exists("mysqli_real_secape_string");
		if($new_enough_php){ // PHP v4.3.0 or higher
			// undo any magic quote effects so mysqli_real_escape_string can do the work
			if($magic_quotes_active) { 
				$value = stripslashes($value); 
			}
			$value = mysqli_real_escape_string($this->get_connection(),$value);
		} else {
			// if magic quotes aren't already on then add slashes manually
			if(!$magic_quotes_active) { $value = addslashes($value); }
			// if magic quotes are active, then the slashes already exist
		}
		return $value;
	}
}

$database = new MySQLDatabase();
// $database->close_connection();

// ($db) will point to the reference of the instance of database created
$db =& $database; 
?>