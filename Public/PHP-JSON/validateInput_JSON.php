<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file

// Response sent from genericJSs.js
// Response sent from loginAdminJSs.js
// Function: validateInput()

// An array is actually created here
$jsonData = array(
	'success' => false,
	'result' => ""
);

// Check if the request is post and if it is from same web page.
// Request sent from cropImage.js
if (request_is_post() && request_is_same_domain()) {
	global $session;
	global $validate;
		
	// $post_params = allowed_post_params(['inputName', 'value']);
	if (isset($_POST['inputName'])) {
		$inputName = $_POST['inputName'];
		$value = $_POST['value'];

		$passedValidation = array();

		// Get the presence array to validate
		$fields_required = getPresenceFields();

		// Validate presence of input in the form
		if (in_array($inputName, $fields_required)) {
			if (!($validate->has_presence($value))) {
				$validate->errors[$inputName] = ucfirst(str_replace("_", " ", $inputName))." can't be blank";
			} else {
				$passedValidation[$inputName] = "passed";
				
				// Validate the input fields for maximum length
				$fields_with_max_lengths = array("first_name" => 30, "last_name" => 30, "username" => 30, "password" => 20, "password_user" => 20, "confirm_password" => 20, "phone_number" => 11, "business_phone_number" => 11, "email" => 100, "business_name" => 100, "business_email" => 100, "address_line_1" => 100, "address_line_2" => 100, "address_line_3" => 100, "town" => 30, "other_town" => 30, "state" => 30);
				if (array_key_exists($inputName, $fields_with_max_lengths)) {
					$max = $fields_with_max_lengths[$inputName];
					if (!($validate->has_max_length($value, $max))) {
						$validate->errors[$inputName."_error_long"] = ucfirst(str_replace("_", " ", $inputName))." is too long.";
					} else {
						$passedValidation[$inputName."_error_long"] = "passed";
					}
				}
				
				// Validate that only number was entered for phone number
				$number_field = array("phone_number", "business_phone_number");
				if (in_array($inputName, $number_field)) {
					if (($validate->not_number($value))) {
						$validate->errors["phone_number_error"] = "This is an invalid phone number.";
					} else {
						$passedValidation["phone_number_error"] = "passed";						
						if (($validate->phone_number_exists($value))) {
							$validate->errors["phone_number_exists"] = "An account has already been created with this phone number.";
						} else {
							$passedValidation["phone_number_exists"] = "passed";
						}
					}
				}		
				
				// Validate the input field for minimum length
				$fields_with_min_lengths = array("password" => 6, "password_user" => 6, "confirm_password" => 6, "phone_number" => 11, "business_phone_number" => 11);
				if (array_key_exists($inputName, $fields_with_min_lengths)) {
					$min = $fields_with_min_lengths[$inputName];
					if (!($validate->has_min_length($value, $min))) {
						$validate->errors[$inputName."_error_short"] = ucfirst(str_replace("_", " ", $inputName))." is short.";
					} else {
						$passedValidation[$inputName."_error_short"] = "passed";
					}
				}
				
				// Validate proper names were entered.
				$fields_with_names = array("first_name", "last_name");
				if (in_array($inputName, $fields_with_names)) {
					if ($validate->validate_name($value)) {
						$validate->errors[$inputName."_error_name"] = ucfirst($inputName)." is not valid.";
					} else {
						$passedValidation[$inputName."_error_name"] = "passed";
					}
				}
				
				// Validate a proper email was entered.
				$email_fields = array("email", "business_email");
				if (in_array($inputName, $email_fields)) {
					if ($validate->not_email($value)) {
						$validate->errors["email_error"] = "This is not a valid email.";
					} else {
						$passedValidation["email_error"] = "passed";
						if (($validate->email_exists($value))) {
							$validate->errors["email_exists"] = "An account has already been created with this email.";
						} else {
							$passedValidation["email_exists"] = "passed";
						}
					}
				}
			}
		}				

		// Validate the gender radio button and check boxes
		$radio_button_fields = array("gender", "business_category", "vehicle_category", "sellers", "artisans", "technical_services", "spare_parts", "car_brands", "bus_brands", "truck_brands");
		if (in_array($inputName, $radio_button_fields)) {
			if (isset($value) !== "") {
				$passedValidation[$inputName] = "passed";
				// check if 
			} else {
				$validate->errors[$inputName] = ucfirst(str_replace("_", " ", $inputName))." is not selected.";
			}
		}
		
		// Remove error from session that is corrected
		foreach ($passedValidation as $key => $value) {
			if ($value === 'passed') {
				unset($_SESSION["accountFormValidate"][$key]);
			}
		}

		if (empty($validate->errors)) {
			$jsonData["success"] = true;
		} else {
			$jsonData["success"] = false;
			// save errors to session
			if ($_SESSION["accountFormValidate"] !== null) {
				$_SESSION["accountFormValidate"] = array_merge($_SESSION["accountFormValidate"], $validate->errors);
			} else {
				$_SESSION["accountFormValidate"] = $validate->errors;
			}
			// Send errors back to the front end
			$jsonData["validateError"] = $validate->errors;
		}
	} else {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
} else {
	$jsonData["success"] = false;
	$jsonData["result"] = "Not a valid post request or domain request";
}

// Determine the optional fields that are present
function getPresenceFields() {
	global $inputName;
	global $value;

	// Determine optional email field was entered
	if (($inputName === "business_email") && $value !== "") {
	  $fields_required = array("first_name", "last_name", "password", "password_user", "phone_number", "business_phone_number", "business_email", "business_name", "address_line_1", "town", "other_town", "state", "caption");
  } elseif (($inputName === "email") && $value !== "") {
		$fields_required = array("first_name", "last_name", "password", "password_user", "phone_number", "business_phone_number", "email", "business_name", "address_line_1", "town", "other_town", "state", "caption");
	} else {
		// The default prersence fields will be sent.
		$fields_required = array("first_name", "last_name", "password", "password_user", "phone_number", "business_phone_number", "business_name", "address_line_1", "town", "other_town", "state", "caption");
	}

	// Determine optional address line 2 field was entered
	if (($inputName === "address_line_2") && $value !== "") {	
		$fields_required[] = "address_line_2";
	}

	// Determine optional address line 3 field was entered
	if (($inputName === "address_line_3") && $value !== "") {	
		$fields_required[] = "address_line_3";
	} 

	return $fields_required;
}

echo json_encode($jsonData);

?>