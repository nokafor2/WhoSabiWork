<?php
require_once("../includes/initialize.php");

// Trick the browser, to think that it is a JSON file using PHP 
// The content-type is set to application/json.
header("Content-Type: application/json");
if (isset($_POST['send'])) {
	// preg_replace is used to filter all characters except letters
	$connecting = preg_replace('#[^a-z]#', '', $_POST['send']);
	
	// connect to the database
	global database;
	$cus_availability = new Customers_Availability();
	$weekDates = $cus_availability->weekDatesFromToday();
	$firstDateOfWeek = $weekDates[0];
	$lastDateOfWeek = $weekDates[6];
	
	$sql = "SELECT date_available FROM `customers_availability` WHERE date_available >= '{$firstDateOfWeek}' AND date_available <= '{$lastDateOfWeek}' AND customers_id = {$_SESSION['customer_page']} ";
	$result_set = $database->query($sql); 

	$count = 0; 
	$customerAvailability = array();
	while($row = mysqli_fetch_assoc($result_set)){ 
		// Gets the saved schedule of the customer 
		$customerAvailability[$count] = $row["date_available"]; 
		$count++; // Relevant
	}
	
	
	$jsonData = '{';
	
	
	$jsonData .= '}';
	echo $jsonData;
}

?>