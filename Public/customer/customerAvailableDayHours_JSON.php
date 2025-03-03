<?php
require_once("../../includes/initialize.php");
// This JSON/PHP is called by: 

// Trick the browser, to think that it is a JSON file using PHP 
// The content-type is set to application/json.
header("Content-Type: application/json");

if(request_is_post() && request_is_same_domain()) {
	// Check if the request is post and if it is from same web page.
	
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['customers_id', 'date_selected']);
	
	if (isset($_POST['customers_id'])) {
		// Get values passed in through the $_POST global variable
		$customers_id = $_POST['customers_id'];
		$date_selected = $_POST['date_selected'];
		
		// make an array for all the values
		$hours = array("eight_to_nine_am", "nine_to_ten_am", "ten_to_eleven_am", "eleven_to_twelve_pm", "twelve_to_one_pm", "one_to_two_pm", "two_to_three_pm", "three_to_four_pm", "four_to_five_pm", "five_to_six_pm", "six_to_seven_pm", "seven_to_eight_pm", "eight_to_nine_pm", "nine_to_ten_pm");
		 
		// reference your database file
		// Initialize the customer availability variables
		$customerAvailability = Customers_Availability::find_by_date_available($customers_id, $date_selected);
		
		// run a loop to check for the saved hours set to true.
		$counter = 0;
		$cusAvailableHours = array();
		foreach ($hours as $key => $hour) {
			if ($customerAvailability->$hour) {
				$cusAvailableHours[$counter] = $hour;
				$counter++;
			}
		}
		/* echo "The available hours are: ";
		print_r($cusAvailableHours);
		echo "<br/>"; */
		
		// convert the database hours to a format that can be outputted to the website
		$customer_Availability = new Customers_Availability();
		$DBhoursToFormHours = array();
		foreach ($cusAvailableHours as $key => $period) {
			$DBhoursToFormHours[$key] = $customer_Availability->editDbVarToFormTime($period);
		}
		/* echo "The available hours in website output form are: ";
		print_r($DBhoursToFormHours);
		echo "<br/>"; */
		
		$jsonData = '{';
		// check in the array containing the hours if true is saved.
		// availHour is abbreviated for available hour
		foreach ($DBhoursToFormHours as $key => $availableHour) {
			$jsonData .= '"record'.$key.'":{"availableHour":"'.$availableHour.'", "checkboxValue":"'.$cusAvailableHours[$key].'"},';
		}
		
		// chop the last comma that was put in the JSON array with the foreach loop
		$jsonData = chop($jsonData, ",");
		$jsonData .= '}';
		echo $jsonData;
	}
}

?>