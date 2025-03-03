<?php 

	class ManageRatings {
		protected $link;
		protected $db_host = 'localhost';
		protected $db_name = 'ajax_rating';
		protected $db_user = 'root';
		protected $db_pass = 'root';
		
		function __construct() {
			// $this->link = new mysqli('localhost', 'root', 'root', 'ajax_rating');
			// connect to the database
			try {
				// PDO is a nice way to make connection with mysql
				$this->link = new PDO("mysql:host=$this->db_host; dbname=$this->db_name", $this->db_user, $this->db_pass);
				return $this->link;
			}
			catch (PDOException $e) {
				return $e->getMessage;
			}
		}
		
		// Its not compulsory to pass a variable in, by default it will be null
		function getItems($id = null) {
			// If id is passed in, get the data of only that id else get all the records in the table in the database
			if (isset($id)) {
				$query = $this->link->query("SELECT * FROM items WHERE id = '$id'");
			} else {
				$query = $this->link->query("SELECT * FROM items");
			}
			
			// Check if a data was gotten from the database and the numbers of rows of data
			$rowCount = $query->rowCount();
			if ($rowCount >= 1) {
				$result = $query->fetchAll();
			} else {
				$result = 0;
			}
			return $result;
		}
		
		// This method will insert the variables gotten from the webpage into the database
		function insertRatings($id, $rating, $total_rating, $total_rates, $ip_address) {
			$query = $this->link->query("UPDATE items SET rating = '{$rating}', total_rating = '{$total_rating}', total_rates = '{$total_rates}', ip_address = CONCAT(ip_address,',{$ip_address}') WHERE id = '{$id}'");
			
			// If $rowCount has a value of 1, there was success updating the value in the database
			$rowCount = $query->rowCount();
			return $rowCount;
		}
	}

?>