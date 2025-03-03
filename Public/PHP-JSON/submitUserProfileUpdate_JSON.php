<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file

// Data sent from: userEditPageJSs.js
// Function: $('#submit_first_name') etc

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
		$post_params = allowed_post_params(["inputField", "first_name", "gender", "last_name", "username", "password", "confirm_password", "email", "phone_number"]);

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
		$userId = $session->user_id;
		$user = User::find_by_id($userId);
		
		if ($post_params['inputField'] === 'firstName') {
			// Check if it is user logged in
			if ($session->is_user_logged_in()) {
				$first_name = trim($_POST['first_name']);
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$user->first_name = ucfirst($first_name);
					$user->date_edited = $user->current_Date_Time();
					$savedUser = $user->update();					
					
					if($savedUser) {
						// Update session
						$session->user_full_name = $_SESSION['user_full_name'] = $user->full_name();
						$jsonData["success"] = true;
						$jsonData["fullName"] = $user->full_name();
						$jsonData["inputFieldUpdated"] = "First name was successfully updated.";						
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
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
		} elseif ($post_params['inputField'] === 'lastName') {
			// Check if it is user logged in
			if ($session->is_user_logged_in()) {
				$last_name = trim($_POST['last_name']);
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$user->last_name = ucfirst($last_name);
					$user->date_edited = $user->current_Date_Time();
					$savedUser = $user->update();					
					
					if($savedUser) {
						// Update session
						$session->user_full_name = $_SESSION['user_full_name'] = $user->full_name();					
						$jsonData["success"] = true;
						$jsonData["fullName"] = $user->full_name();
						$jsonData["inputFieldUpdated"] = "Last name was successfully updated.";						
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
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
		} elseif ($post_params['inputField'] === 'gender') {
			// Check if it is user logged in
			if ($session->is_user_logged_in()) {
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {					
					$gender = trim($_POST['gender']);
					$user->gender = $gender;
					$user->date_edited = $user->current_Date_Time();
					$savedUser = $user->update();
					
					if(isset($savedUser)) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Gender was successfully updated.";						
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
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
		} elseif ($post_params['inputField'] === 'username') {
			// Check if it is user logged in
			if ($session->is_user_logged_in()) {
				$username = trim($_POST['username']);
				// check if the username exists
				$userFound = User::find_by_username($username);
				$customerFound = Customer::find_by_username($username);
				
				if (isset($userFound->username) || isset($customerFound->username)) {
					$validate->errors["username_exists"] = "Username already exists.";
				}
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$user->username = $username;
					$user->date_edited = $user->current_Date_Time();
					$savedUser = $user->update();

					// Update session
					$session->user_username = $_SESSION['user_username'] = $username;
					
					if(isset($savedUser)) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Username was successfully updated.";						
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
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
		} elseif ($post_params['inputField'] === 'password') {
			// Check if user is logged in
			if ($session->is_user_logged_in()) {
				$password = trim($_POST['password']);
				$confirm_password = trim($_POST['confirm_password']);

				$validate->validate_form_submission();
				if ($password !== $confirm_password) {
					$validate->errors["password_match_error"] = "The passwords does not match.";
				}
				if (empty($validate->errors)) {
					// $user = new User();
					$user->password = $user->password_encrypt($password);
					$user->date_edited = $user->current_Date_Time();
					$savedPassword = $user->update();
					
					if(isset($savedPassword)) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Password was successfully updated.";						
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
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
		} elseif ($post_params['inputField'] === 'email') {
			// Check if user is logged in
			if ($session->is_user_logged_in()) {
				$email = trim($_POST['email']);
				
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$user->user_email = $email;
					$user->date_edited = $user->current_Date_Time();
					$savedUser = $user->update();
					
					if ($savedUser) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Email was successfully updated.";	
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
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
		} elseif ($post_params['inputField'] === 'phone_number') {
			// Check if user is logged in
			if ($session->is_user_logged_in()) {
				$phone_number = trim($_POST['phone_number']);
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$user->phone_number = $phone_number;
					// Unset phone validated if it is updated
					$user->phone_validated = 0;
					$user->date_edited = $user->current_Date_Time();
					$savedUser = $user->update();
					
					if ($savedUser) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Phone number was successfully updated.";	
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
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
		} elseif ($post_params['inputField'] === 'verify_number') {
			// Check if user is logged in
			if ($session->is_user_logged_in()) {
				$phone_validated = $user->phone_validated;
				
				$phone_number = $user->phone_number;
				$smsCode = randStrGen(6);
				$user->reset_token = $smsCode;
				$user->date_edited = current_Date_Time();
				if ($user->update()) {					
					$smsMessage = 'Your phone number has been confirmed, please use this code '.$smsCode.' to complete your phone number validation.';
					
					if (sendSMSCode($phone_number, $smsMessage)) {
						$jsonData["success"] = true;
						$jsonData["tokenSent"] = "A validation token has been sent to your phone. Enter it below to validate your number.";
					} else {
						$jsonData["tokenNotSent"] = "An error occured sending validation token. Please try again later.";
					}
				} else {
					$jsonData["success"] = false;
					$jsonData["saveError"] = "An error occurred while saving.";
				}	
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'submit_smsToken') {
			// Check if the token is still valid.
			$currentTime = time();
			$tokenDbTime = $user->date_edited;
			$tokenDbTimeInSec = strtotime($tokenDbTime);
			$timeCheck = 60 * 5; // 5 minutes time validity for token.
			$timeElapsed = $currentTime - $tokenDbTimeInSec;
			if (($timeElapsed) < $timeCheck) {
				$smsToken = trim($_POST['smsToken']);
				
				$savedToken = $user->reset_token;
				if ($smsToken === $savedToken) {
					$user->phone_validated = true;
					$phone_validated = true;
					$user->reset_token = "";
					$user->date_edited = current_Date_Time();
					$user->update();
					$jsonData["success"] = true;
					$jsonData["phoneNumValidated"] = "Your phone number has been successfully validated.";
				} else {					
					$user->phone_validated = false;
					$phone_validated = false;
					$user->reset_token = "";
					$user->date_edited = current_Date_Time();
					$user->update();
					$jsonData["success"] = false;
					$jsonData["phoneNumNotValidated"] = "The verification token entered wasn't a match. <br/><br/> Verify your phone number again.";
				}
			} else {
				$user->phone_validated = false;
				$phone_validated = false;
				$user->reset_token = "";
				$user->date_edited = current_Date_Time();
				$user->update();
				$jsonData["success"] = false;
				$jsonData["phoneNumNotValidated"] = "The Token has expired, please try again.";
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