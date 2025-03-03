<?php 
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');

// This will be set to specify as a default output upon request from the JSON file

// Response sent from adminPageJSs.js
// Function: $('#searchName')

// An array is actually created here
$json = array(
	'success' => false,
);

// Initialize security function
$security = new Security_Functions();
// Check if the request is post and if it is from same web page.
if(request_is_post() && request_is_same_domain()) {
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['first_name', 'last_name', 'account']);
	global $validate;
	
	// Check if the CSRF token is valid
	if(!$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()) {
		$json['success'] = false;
		$json['csrfFailed'] = "Improper login attempt.";
		// Login instances of improper login into the log file.

		// echo "<br/>";
		// echo "Session gloabal variables are: <br/>";
		// print_r($_SESSION);
		// echo "<br/>";
	} else {
		// This should work for both user account and customer account creation.
		if (isset($post_params['first_name']) || isset($post_params['last_name'])) {

			$first_name = trim($post_params['first_name']);
			$last_name = trim($post_params['last_name']);
			$account = $post_params['account'];
			$fullName = $first_name." ".$last_name;

			// Validate the input
			if (isset($first_name) && ($first_name !== "")) {
				validateName("first_name", $first_name);
			}
			if (isset($last_name) && ($last_name !== "")) {
				validateName("last_name", $last_name);
			}

			if (empty($validate->errors)) {
				// No validation errors
				if ($account === 'user') {
					$user = new User();
					$foundUsers = $user->findUserByName($fullName);
					// convert the date time format from the database before sending to the front end
					for ($i=0; $i < count($foundUsers); $i++) { 
						$foundUsers[$i]->date_created = datetime_to_text($foundUsers[$i]->date_created);
						$foundUsers[$i]->date_edited = datetime_to_text($foundUsers[$i]->date_edited);
					}			

					$json['success'] = true;
					$json['foundUsers'] = $foundUsers;
				} elseif ($account === 'customer') {
					$customer = new Customer();
					$foundCustomers = $customer->findCustomerByName($fullName);
					// convert the date time format from the database before sending to the front end
					for ($i=0; $i < count($foundCustomers); $i++) { 
						$foundCustomers[$i]->date_created = datetime_to_text($foundCustomers[$i]->date_created);
						$foundCustomers[$i]->date_edited = datetime_to_text($foundCustomers[$i]->date_edited);
					}			

					$json['success'] = true;
					$json['foundCustomers'] = $foundCustomers;
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

function validateName($inputName, $value) {
	global $validate;

	// Check for presence
	if (!($validate->has_presence($value))) {
		$validate->errors[$inputName] = ucfirst(str_replace("_", " ", $inputName))." can't be blank";
	}

	// Check for max length
	$max = 30;
	if (!($validate->has_max_length($value, $max))) {
		$validate->errors[$inputName."_error_long"] = ucfirst(str_replace("_", " ", $inputName))." is too long.";
	}

	// Check for proper name
	if ($validate->validate_name($value)) {
		$validate->errors[$inputName."_error_name"] = ucfirst($inputName)." is not valid.";
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