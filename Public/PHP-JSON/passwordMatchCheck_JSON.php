<?php 
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');

// This will be set to specify as a default output upon request from the JSON file
// An array is actually created here
$json = array(
	'success' => false,
	'result' => ""
);

if(request_is_post() && request_is_same_domain()) {
	// Check if the request is post and if it is from same web page.
	global $validate;	
	
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['firstPassword', 'confirmPassword']);
	
	// Check if the data was sent then initialize them and work with it
	if (isset($post_params['firstPassword'], $post_params['confirmPassword'])) {
		$firstPassword = $post_params['firstPassword'];
		$confirmPassword = $post_params['confirmPassword'];
		$passedValidation = array();
		
		// The initial values of the JSON will be overwritten here
		/* $fields_for_presence = array("firstPassword", "confirmPassword");
		$validate->validate_has_presence($fields_for_presence);
		
		$fields_with_min_lengths = array("firstPassword" => 6, "confirmPassword" => 6);
		$validate->validate_min_lengths($fields_with_min_lengths); */

		// Check for presence
		if (!($validate->has_presence($firstPassword))) {
			$validate->errors['firstPassword'] = "Password can't be blank";
		} else {
			$passedValidation['firstPassword'] = "passed";
			// Check for min length
			$min = 6;
			if (!($validate->has_min_length($firstPassword, $min))) {
				$validate->errors["password_error_short"] = "Password is short.";
			} else {
				$passedValidation['password_error_short'] = "passed";
			}

			// Check for max length
			$max = 20;
			if (!($validate->has_max_length($firstPassword, $max))) {
				$validate->errors["password_error_long"] = "Password is long.";
			} else {
				$passedValidation['password_error_long'] = "passed";
			}
		}

		if (!($validate->has_presence($confirmPassword))) {
			$validate->errors['confirmPassword'] = "Confirm password can't be blank";
		} else {
			$passedValidation['confirmPassword'] = "passed";
			// Check for min length
			if (!($validate->has_min_length($confirmPassword, $min))) {
				$validate->errors["confirmPassword_error_short"] = "Confirm password is short.";
			} else {
				$passedValidation['confirmPassword_error_short'] = "passed";
			}

			// Check for max length
			if (!($validate->has_max_length($confirmPassword, $max))) {
				$validate->errors["confirmPassword_error_long"] = "Confirm password is long.";
			} else {
				$passedValidation['confirmPassword_error_long'] = "passed";
			}
		}
		
		// Remove error from session that is corrected
		foreach ($passedValidation as $key => $value) {
			if ($value === "passed") {
				unset($_SESSION["accountFormValidate"][$key]);
			}
		}

		// Check for errors
		if (empty($validate->errors)) {
			// Check if the passwords match
			if ($firstPassword === $confirmPassword) {
				$json["success"] = true;
				$json["result"] = "Your password matches.";

				// Remove error from session
				unset($_SESSION["accountFormValidate"]["firstPassword"]);
				unset($_SESSION["accountFormValidate"]["confirmPassword"]);
				unset($_SESSION["accountFormValidate"]["password_error_short"]);
				unset($_SESSION["accountFormValidate"]["confirmPassword_error_short"]);
				unset($_SESSION["accountFormValidate"]["confirm_password_match"]);
			} else {
				// save errors to session
				if ($_SESSION["accountFormValidate"] !== null) {
					$passwordMatches = array("confirm_password_match" => "Passwords do not match.");
					$_SESSION["accountFormValidate"] = array_merge($_SESSION["accountFormValidate"], $passwordMatches);
				} else {
					$_SESSION["accountFormValidate"]['confirm_password_match'] = "Passwords does not match.";
				}

				$json["success"] = false;
				$json["result"] = "Your password does not match.";
			}
		} else {
			// save errors to session
			if ($_SESSION["accountFormValidate"] !== null) {
				$_SESSION["accountFormValidate"] = array_merge($_SESSION["accountFormValidate"], $validate->errors);
			} else {
				$_SESSION["accountFormValidate"] = $validate->errors;
			}
			
			$json["result"] = "There was an error";
			$json["errors"] = $validate->errors;
		}
		
	}
}
echo json_encode($json);
?>