<?php
require_once("../../includes/initialize.php");

// Trick the browser, to think that it is a JSON file using PHP 
// The content-type is set to application/json.
header("Content-Type: application/json");

// Response sent from usernameCheck.js
// Function: usernameCheck()

// An array is actually created here
$jsonData = array(
	'success' => false,
	'exist' => ""
);

if(request_is_post() && request_is_same_domain()) {
	// Check if the request is post and if it is from same web page.
	global $validate;	
	
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['username', 'account']);
	
	if (isset($post_params['username'])) {
		// preg_replace is used to filter all characters except letters
		// This will can be tricky
		// $username = preg_replace('#[^a-z0-9_@.]#', '', $post_params['username']);
		$username = $post_params['username'];
		$account = $post_params['account'];
		$passedValidation = array();

		// Valiate username
		// Check for presence
		if (!($validate->has_presence($username))) {
			$validate->errors['username'] = "Username can't be blank";
		} else {
			$passedValidation['username'] = "passed";
			// Check proper username is entered
			if ($validate->validate_username_input($username)) {
				$validate->errors["username_valid"] = "Username is not valid.";
			} else {
				$passedValidation["username_valid"] = "passed";
			}

			// Check for max length
			$max = 30;
			if (!($validate->has_max_length($username, $max))) {
				$validate->errors["username_error_long"] = "Username is too long.";
			} else {
				$passedValidation['username_error_long'] = "passed";
			}
		}			
		
		// Remove error from session that is corrected
		foreach ($passedValidation as $key => $value) {
			if ($value === "passed") {
				unset($_SESSION["accountFormValidate"][$key]);
			}
		}

		// Check for validation errors
		if (empty($validate->errors)) {
			// reference your database file
			if ($account !== 'admin') {
				$userFound = User::find_by_username($username);
				$customerFound = Customer::find_by_username($username);
			} else {
				// This will reference the admin table
				$adminFound = Admin::find_by_username($username);
			}
			
			// One of these objects will only be true at a time
			if (isset($userFound->username) || isset($customerFound->username) || isset($adminFound->username)) {
				$jsonData['success'] = false;
				$jsonData['exist'] = $username;

				// save errors to session
				// This will check if the array for saving session errors has been created or doesn't exist, so it can be saved into it.
				if ($_SESSION["accountFormValidate"] !== null) {
					$usernameExist = array("username_exist" => "Username already exists.");
					$_SESSION["accountFormValidate"] = array_merge($_SESSION["accountFormValidate"], $usernameExist);
				} else {
					$_SESSION["accountFormValidate"]['username_exist'] = "Username already exists.";
				}
			} else {
				$jsonData['success'] = true;
				$jsonData['exist'] = false;
				// Remove error from session
				unset($_SESSION["accountFormValidate"]["username"]);
				unset($_SESSION["accountFormValidate"]["username_error_long"]);
				unset($_SESSION["accountFormValidate"]["username_exist"]);
			}
		} else {
			// save errors to session
			if ($_SESSION["accountFormValidate"] !== null) {
				$_SESSION["accountFormValidate"] = array_merge($_SESSION["accountFormValidate"], $validate->errors);
			} else {
				$_SESSION["accountFormValidate"] = $validate->errors;
			}
			
			$jsonData['success'] = false;
			$jsonData["validateErrors"] = $validate->errors;
		}
	}
} else {
	$jsonData["success"] = false;
	$jsonData["result"] = "Not a valid post request or domain request";
}
echo json_encode($jsonData);
?>