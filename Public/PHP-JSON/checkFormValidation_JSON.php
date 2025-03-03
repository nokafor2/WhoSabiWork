<?php 
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');

// This will be set to specify as a default output upon request from the JSON file

// Response sent from genericJSs.js
// Function: $('#user_register')

// An array is actually created here
$json = array(
	'success' => false,
);

// Check if the request is post and if it is from same web page.
if(request_is_post() && request_is_same_domain()) {
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['username', 'password', 'confirm_password', 'cusUsername', 'cusPassword', 'first_name', 'last_name', 'gender', 'phone_number', 'email', 'business_name', 'state', 'town', 'address_line_1', 'address_line_2', 'address_line_3', 'business_category', 'sellers', 'artisans', 'technical_services', 'spare_parts', 'car_brands', 'bus_brands', 'truck_brands']);
	global $validate;
	
	// This should work for both user account and customer account creation.
	if (isset($post_params['first_name'], $post_params['last_name'], $post_params['gender'], $post_params['username'], $post_params['password'], $post_params['confirm_password'], $post_params['phone_number'], $post_params['email'])) {

		$validate->validate_form_submission();
		// Check for username error
		if (!empty($_SESSION["accountFormValidate"])) {
			// Append username errors to validate errors
			foreach ($_SESSION["accountFormValidate"] as $errName => $error) {
				if ($errName === 'username_exist') {
					$validate->errors[$errName] = $error;
				}	elseif ($errName === 'confirm_password_match') {
					$validate->errors[$errName] = $error;
				}
			}
		}
		if (empty($validate->errors)) {
			// No validation errors
			$json['success'] = true;
			$json['validationResult'] = "passed";
		} else {
			// Validation errors are present
			$json['success'] = true;
			$json['result'] = $validate->errors;
		}
	}
} else {
	$json['success'] = false;
	$json['noData'] = "Data was not received.";
}
echo json_encode($json);
?>