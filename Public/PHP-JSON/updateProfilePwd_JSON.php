<?php 
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');

// This will be set to specify as a default output upon request from the JSON file

// Response sent from adminPageJSs.js
// Function: $('#updatePwd')

// An array is actually created here
$json = array(
	'success' => false,
);

// Initialize security function
$security = new Security_Functions();
// Check if the request is post and if it is from same web page.
if(request_is_post() && request_is_same_domain()) {
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['username', 'password', 'confirm_password', 'account']);
	global $validate;
	
	// Check if the CSRF token is valid
	if(!$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()) {
		$json['success'] = false;
		$json['csrfFailed'] = "Improper login attempt.";
		// Login instances of imporoper login into the log file.

		// echo "<br/>";
		// echo "Session gloabal variables are: <br/>";
		// print_r($_SESSION);
		// echo "<br/>";
	} else {
		// This should work for both user account and customer account creation.
		if (isset($post_params['username']) && isset($post_params['password']) && isset($post_params['confirm_password'])) {

			$username = trim($post_params['username']);
			$password = trim($post_params['password']);
			$confirmPassword = trim($post_params['confirm_password']);
			$account = $post_params['account'];
			// Check that passwords matches
			$validate->password_match();

			// Validate the input
			$validate->validate_form_submission();
			if (empty($validate->errors)) {
				// No validation errors
				if ($account === 'user') {
					$user = new User();
					$foundUser = User::find_by_username($username);
					if ($foundUser) {
						$foundUser->password = $foundUser->password_encrypt($password);

						if ($foundUser->update()) {
							// Send SMS message to the user updating the password
							$SMSphoneNumber = $foundUser->phone_number;
							$SMSmessage = makeTextMessage($username, $password, $account);
							// Check if phone number exists
							if (!empty($SMSphoneNumber)) {
								$outcome = sendSMSCode($SMSphoneNumber, $SMSmessage);
							} else {
								$outcome = "User phone number is not available.";
							}
							$json['success'] = true;
							$json['updatedPwd'] = "Password was updated.";
							$json['SMSoutcome'] = SMSoutcome($outcome);
						} else {
							$json['success'] = false;
							$json['updateFailed'] = "Password update failed.";
						}
					} else {
						$json['success'] = false;
						$json['usernameError'] = "Username doesn't exists";
					}
				} elseif ($account === 'customer') {
					$customer = new Customer();
					$foundCustomer = Customer::find_by_username($username);
					if ($foundCustomer) {
						$foundCustomer->password = $foundCustomer->password_encrypt($password);

						if ($foundCustomer->update()) {
							// Send SMS message to the user updating the password
							$SMSphoneNumber = $foundCustomer->phone_number;
							$SMSmessage = makeTextMessage($username, $password, $account);
							// Check if phone number exists
							if (!empty($SMSphoneNumber)) {
								$outcome = sendSMSCode($SMSphoneNumber, $SMSmessage);
							} else {
								$outcome = "Customer phone number is not available.";
							}
							$json['success'] = true;
							$json['updatedPwd'] = "Password was updated.";
							$json['SMSoutcome'] = SMSoutcome($outcome);
						} else {
							$json['success'] = false;
							$json['updateFailed'] = "Password update failed.";
						}
					} else {
						$json['success'] = false;
						$json['usernameError'] = "Username doesn't exist.";
					}
				}
			} else {
				// Validation errors are present
				$json['success'] = false;
				$json['result'] = $validate->errors;
			}
		}
	}
	// Reset the CSRF token and time in the session
	// First get the csrfToken name and csrfTime name
	$csrfTokenVar = getCSRFtokenVar();
	$csrfTimeVar = getCSRFtimeVar();
	list($newCSRFtoken, $newCSRFtime) = updateCSRFtokenAndTime($csrfTokenVar, $csrfTimeVar);
	$json['newCSRFtoken'] = $newCSRFtoken;
	$json['newCSRFtime'] = $newCSRFtime;		
} else {
	$json['success'] = false;
	$json['noData'] = "Data was not received.";
}
echo json_encode($json);

function makeTextMessage($username, $password, $account) {
	// Send SMS to the User or Customer updating the password	
	if ($account === 'user') {
		$SMSmessage = 'Your WhoSabiWork User profile has been updated.';
	} else {
		$SMSmessage = 'Your WhoSabiWork Business profile has been updated.';
	}
	$SMSmessage .= PHP_EOL.PHP_EOL.'Your username is: '.$username;
	$SMSmessage .= PHP_EOL.PHP_EOL.'Your new password is: '.$password;
	if ($account === 'user') {
		$SMSmessage .= PHP_EOL.PHP_EOL.'Log in with your username and password at the User Sign In tab to check your profile.';
	} else {
		$SMSmessage .= PHP_EOL.PHP_EOL.'Log in with your username and password at the Business Sign In tab to check your profile.';
	}

	return $SMSmessage;
}

function SMSoutcome($result) {
	if (is_string($result)) {
		return $result;
	} else {
		$output = "";	
		if ($result) {
			$output .= "Message was sent successfully.";
		} else {
			$output .= "Message was not sent.";
		}

		return $output;
	}		
}

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
	$session = new Session();

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

?>