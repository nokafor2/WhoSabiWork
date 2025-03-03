<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file

// Data sent from: customerEditPage2JSScripts.js
// Function: $('#submit_availability') etc

// An array is actually created here
$jsonData = array(
	'success' => false
);
// print_r($_POST);

$security = new Security_Functions();
// Check if the request is post and if it is from same web page.
if(request_is_post() && request_is_same_domain()) {
	global $database;
	global $validate;
	global $session;	

	if(!$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()) {
		$jsonData["csrfFailed"] =  "Sorry, request was not valid. Session is expired.";
	} else {
		// CSRF tests passed--form was created by us recently.
		// Check that only allowed parameters is passed into the form
		$post_params = allowed_post_params(["set_days", "set_hours"]);

		// Eliminate HTML tags embedded in the URL inputs
		foreach($post_params as $param) {
			// run htmlentities check on the parameters
			// First check if the variable is an array
			if (is_array($param) && isset($param)) {
				foreach ($param as $value) {
					// run htmlentities check on the parameters
					$params[$value] = h2($value);
				}
			} elseif (isset($param)) {
				// run htmlentities check on the parameters
				$post_params[$param] = h2($param);
			} 
		}
		
		// Get the userId			
		$customerId = $session->customer_id;
		$customer = Customer::find_by_id($customerId);
		
		if (isset($post_params['set_days'], $post_params['set_hours'])) {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				$cus_availability = new Customers_Availability();
				// validate the form was filled if the button is clicked. Values will be set in the validation script
				$day;
				$hours = array();
				$validate->validate_customer_availability();
				
				if (empty($validate->errors)) {
					$cus_availability->customers_id = $customerId;
					$cus_availability->date_available = $cus_availability->makeDateForDay($day);
					
					$workingHours = array("eight_to_nine_am", "nine_to_ten_am", "ten_to_eleven_am", "eleven_to_twelve_pm", "twelve_to_one_pm", "one_to_two_pm", "two_to_three_pm", "three_to_four_pm", "four_to_five_pm", "five_to_six_pm", "six_to_seven_pm", "seven_to_eight_pm", "eight_to_nine_pm", "nine_to_ten_pm");
					
					// Initially initialize all the variables of the working hours to false
					foreach ($workingHours as $period) {
						$cus_availability->{$period} = FALSE;
					}
					
					// Next, initialize all the selected working hours to true.
					foreach ($hours as $period) {
						$cus_availability->{$period} = TRUE;
					}
					
					$cus_availability->date_created = current_Date_Time();
					
					$savedCusAvailability = $cus_availability->save();
					$savedIndex = $database->insert_id();
					
					if ($savedCusAvailability) {
						$jsonData["success"] = true;
						$jsonData["savedAvail"] = "Your appointment availability was successfully saved.";
						$jsonData["rawDate"] = strftime("%F", strtotime($cus_availability->date_available));
						$jsonData["customerId"] = $customerId;
						$jsonData["weekday"] = date_to_weekday($cus_availability->date_available);
						$jsonData["dateText"] = date_to_text($cus_availability->date_available);
						$jsonData["cusAvailTbId"] = $savedIndex;
					} else {
						$jsonData["saveError"] = "An error occurred while saving your availability.";
					}				
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} else {
			$jsonData["success"] = false;
			$jsonData["postDataError"] = "An error occurred sending the data from JSON";
		}
	}
	// Reset the CSRF token and time in the session
	// First get the csrfToken name and csrfTime name
	$csrfTokenVar = getCSRFtokenVar();
	$csrfTimeVar = getCSRFtimeVar();
	list($newCSRFtoken, $newCSRFtime) = updateCSRFtokenAndTime($csrfTokenVar, $csrfTimeVar);
	$jsonData['newCSRFtoken'] = $newCSRFtoken;
	$jsonData['newCSRFtime'] = $newCSRFtime;	
}
echo json_encode($jsonData);

function getCSRFtokenVar() {
	// Get the POST global variables
	$post_params_keys = array_keys($_POST);	
	$find_str = 'csrf_token_';
	
	$post_csrf_token_key = "";
	// Check for 'csrf_token_' in the POST global keys.
	foreach ($post_params_keys as $param) {
		if (strpos($param, $find_str) === 0) {
			$post_csrf_token_key = $param;
		}
	}

	return $post_csrf_token_key;
}

function getCSRFtimeVar() {
	// Get the POST global variables
	$post_params_keys = array_keys($_POST);	
	$find_str = 'csrf_time_';
	
	$post_csrf_time_key = "";
	// Check for 'csrf_token_' in the POST global keys.
	foreach ($post_params_keys as $param) {
		if (strpos($param, $find_str) === 0) {
			$post_csrf_time_key = $param;
		}
	}

	return $post_csrf_time_key;
}

function updateCSRFtokenAndTime($csrfTokenVar, $csrfTimeVar) {
	global $security;
	global $session;

	// generate new csrf token
	$newCSRFtoken = $security->csrf_token();
	// generate new csrf time
	$newCSRFtime = time();

	// Update the CSRF token
	$session->csrf_tokens[$csrfTokenVar] = $_SESSION[$csrfTokenVar] = $newCSRFtoken;
	// Update the CSRF time
	$session->csrf_tokens_time[$csrfTimeVar] = $_SESSION[$csrfTimeVar] = $newCSRFtime;

	// return the new CSRF tokne and time
	return array($newCSRFtoken, $newCSRFtime);
}