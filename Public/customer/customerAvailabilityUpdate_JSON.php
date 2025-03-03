<?php
require_once("../../includes/initialize.php");
// This JSON/PHP is called by: customerAvailabilityUpdate.js located in the javascript folder

// Trick the browser, to think that it is a JSON file using PHP 
// The content-type is set to application/json.
header("Content-Type: application/json");

if(request_is_post() && request_is_same_domain()) {
	// Check if the request is post and if it is from same web page.

	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['eight_to_nine_am', 'nine_to_ten_am', 'ten_to_eleven_am', 'eleven_to_twelve_pm', 'twelve_to_one_pm', 'one_to_two_pm', 'two_to_three_pm', 'three_to_four_pm', 'four_to_five_pm', 'five_to_six_pm', 'six_to_seven_pm', 'seven_to_eight_pm', 'eight_to_nine_pm', 'nine_to_ten_pm', 'customers_id', 'date_available']);
	
	if (isset($post_params['customers_id'])) {
		// preg_replace is used to filter all characters except letters
		// $username = preg_replace('#[^a-z]#', '', $post_params['username']);
		
		// Get values passed in through the $post_params global variable
		$eight_to_nine_am = $post_params['eight_to_nine_am'];
		$nine_to_ten_am = $post_params['nine_to_ten_am'];
		$ten_to_eleven_am = $post_params['ten_to_eleven_am'];
		$eleven_to_twelve_pm = $post_params['eleven_to_twelve_pm'];
		$twelve_to_one_pm = $post_params['twelve_to_one_pm'];
		$one_to_two_pm = $post_params['one_to_two_pm'];
		$two_to_three_pm = $post_params['two_to_three_pm'];
		$three_to_four_pm = $post_params['three_to_four_pm'];
		$four_to_five_pm = $post_params['four_to_five_pm'];
		$five_to_six_pm = $post_params['five_to_six_pm'];
		$six_to_seven_pm = $post_params['six_to_seven_pm'];
		$seven_to_eight_pm = $post_params['seven_to_eight_pm'];
		$eight_to_nine_pm = $post_params['eight_to_nine_pm'];
		$nine_to_ten_pm = $post_params['nine_to_ten_pm'];
		$customers_id = $post_params['customers_id'];
		$date_available = $post_params['date_available'];
		
		// make an array for all the values
		$hours = array($eight_to_nine_am, $nine_to_ten_am, $ten_to_eleven_am, $eleven_to_twelve_pm, $twelve_to_one_pm, $one_to_two_pm, $two_to_three_pm, $three_to_four_pm, $four_to_five_pm, $five_to_six_pm, $six_to_seven_pm, $seven_to_eight_pm, $eight_to_nine_pm, $nine_to_ten_pm);
		
		// reference your database file
		// Initialize the customer availability variables
		$customerAvailability = Customers_Availability::find_by_date_available($customers_id, $date_available);
		// Get the customerAvailability id from the table
		$id = $customerAvailability->id;
		// Get the availability date
		$dateAvailable = $customerAvailability->date_available;
		
		function convertBooleanType($booleanVar) {
			if ($booleanVar == 'true') {
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		$jsonData = '{';
		// check in the array containing the hours if true is saved. This will be used to check if a checkbox was selected.
		if (in_array('true', $hours) ) {
			// This is used to troubleshoot. Do not have it uncommented, if the similar success is uncommented. 
			// $jsonData .= '"record":{"success":true}';
			
			// Update the variables to be saved
			// $customerAvailability->customers_id = $customers_id;
			$customerAvailability->eight_to_nine_am = convertBooleanType($eight_to_nine_am);
			$customerAvailability->nine_to_ten_am = convertBooleanType($nine_to_ten_am);
			$customerAvailability->ten_to_eleven_am = convertBooleanType($ten_to_eleven_am);
			$customerAvailability->eleven_to_twelve_pm = convertBooleanType($eleven_to_twelve_pm);
			$customerAvailability->twelve_to_one_pm = convertBooleanType($twelve_to_one_pm);
			$customerAvailability->one_to_two_pm = convertBooleanType($one_to_two_pm);
			$customerAvailability->two_to_three_pm = convertBooleanType($two_to_three_pm);
			$customerAvailability->three_to_four_pm = convertBooleanType($three_to_four_pm);
			$customerAvailability->four_to_five_pm = convertBooleanType($four_to_five_pm);
			$customerAvailability->five_to_six_pm = convertBooleanType($five_to_six_pm);
			$customerAvailability->six_to_seven_pm = convertBooleanType($six_to_seven_pm);
			$customerAvailability->seven_to_eight_pm = convertBooleanType($seven_to_eight_pm);
			$customerAvailability->eight_to_nine_pm = convertBooleanType($eight_to_nine_pm);
			$customerAvailability->nine_to_ten_pm = convertBooleanType($nine_to_ten_pm);
			$customerAvailability->date_created = current_Date_Time();
			
			if ($customerAvailability->update()) {
				$jsonData .= '"record":{"success":true}';
			} else {
				$jsonData .= '"record":{"success":"failedUpdate"}';
			}
		} else {
			// Takes care of the condition if a check box was not selected.
			$jsonData .= '"record":{"success":false}';
			
		}
		
		$jsonData .= '}';
		echo $jsonData;
	}
	
}

?>