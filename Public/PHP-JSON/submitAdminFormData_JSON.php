<?php 
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');

// This will be set to specify as a default output upon request from the JSON file

// Response sent from loginAdminJSs.js
// Function: checkErrorData()

// An array is actually created here
$json = array(
	'success' => false
);

// Initialize security function
$security = new Security_Functions();

// Check if the request is post and if it is from same web page.
if(request_is_post() && request_is_same_domain()) {
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['username', 'password', 'confirm_password', 'cusUsername', 'cusPassword', 'first_name', 'last_name', 'gender', 'phone_number', 'email']);

	// Eliminate HTML tags embedded in the form inputs
	foreach($post_params as $param) {
		// run htmlentities check on the parameters
		if(isset($post_params[$param])) {
			// run htmlentities check on the parameters
			$post_params[$param] = h2($param);
		} 
	}
	
	if(!$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()) {
		$json['success'] = false;
		$json["csrf_failure"] = "Sorry, form has expired. Please refresh and try again.";
	} else {		
		if (isset($post_params['first_name'], $post_params['last_name'], $post_params['gender'], $post_params['username'], $post_params['password'], $post_params['confirm_password'], $post_params['phone_number'], $post_params['email'])) {

			// Get the registration details for the admin.
			$first_name = trim($post_params['first_name']);
			$last_name = trim($post_params['last_name']);
			$gender = trim($post_params['gender']);
			$username = trim($post_params['username']);
			$password = trim($post_params['password']);
			$confirm_password = trim($post_params['confirm_password']);
			$phone_number = trim($post_params['phone_number']);
			$email = trim($post_params['email']);
			
			// Save the data to the database
			$admin = new Admin();
			$admin->first_name = ucfirst($first_name);
			$admin->last_name = ucfirst($last_name);
			$admin->gender = $gender;
			$admin->username = $username;
			// password_hash(); PHP default function for hashing password
			$admin->password = $admin->password_encrypt($password);
			// $user->password = password_hash($password);
			$admin->phone_number = $phone_number;
			// Don't validate phone number yet.
			// $admin->phone_validated = TRUE;
			$admin->admin_email = $email;
			$admin->account_status = 'active';
			$admin->date_created = $admin->current_Date_Time();
			$admin->date_edited = $admin->current_Date_Time();
									
			if($admin->save()) {
				// Get the database id of the recent saved record
				$newAdminId = $database->insert_id();
				$newAdmin = Admin::find_by_id($newAdminId);
				// Send an email to the user.
				if (isset($email) && !empty($email)) {
					// Send mail to new user created
					$json["emailOutcome"] = sendEmailOutcome2(composeEmail());
				} else {
					$json["emailOutcome"] = sendEmailOutcome2("An email was not sent since no email was provided.");
				}
				// Send mail to whoSabiWork team
				composeEmailToWhoSabiWork();
				// Tell session to log them in.
				$session->admin_login($newAdmin);
				// Create a log that the user has logged into the system
				admin_log_action('Register', "{$newAdmin->username} created an admin account.");

				$json['success'] = true;
				$json['newAdminId'] = $newAdminId;
				// This message should be saved in the session.
				$session->message("<p>Congratulations ".$first_name." ".$last_name.". Your admin account was successfully created.</p><p>".$json["emailOutcome"]."</p>");
			} else {
				$json['success'] = false;
				$json["savingError"] = "An error occurred while saving.";
			}
		} else { 
			$json['success'] = false;
			$json['result'] = "Data input not received";
		} 
	}
	// Reset the CSRF token and time in the session
	// First get the csrfToken name and csrfTime name
	$csrfTokenVar = getCSRFtokenVar();
	$csrfTimeVar = getCSRFtimeVar();
	list($newCSRFtoken, $newCSRFtime) = updateCSRFtokenAndTime($csrfTokenVar, $csrfTimeVar);
	$jsonData['newCSRFtoken'] = $newCSRFtoken;
	$jsonData['newCSRFtime'] = $newCSRFtime;
} else {
	$json["success"] = false;
	$json["result"] = "Not a valid post request or domain request";
}
echo json_encode($json);

/* This composes email for users after creating an account. */
function composeEmail() {
	global $email; global $first_name; global $last_name; global $username; global $password;
	
	$from = 'support@whosabiwork.com';
	$sender = 'WhoSabiWork';
	// $to = $email;
	$title = 'Welcome to WhoSabiWork';
	$body = '<img src="cid:whoSabiWorkLogo"/>
	<p>Dear '.$first_name.' '.$last_name.', </p>
	
	<p>Thanks for taking the time to create an account with WhoSabiWork.</p>
	
	<p>Your username is <strong>'.$username.'</strong>, and your password is <strong>'.$password.'</strong>. Visit our website <strong><a href="https://www.whosabiwork.com/Public/loginPage.php?profile=user">login page</a></strong> and try logging in to ensure that it works. If you have any problem logging in, don\'t fail to contact our service support at support@whosabiwork.com.</p>
	
	<p>WhoSabiWork is committed to providing its users an eclectic source to find skilled artisans, mechanics and spare part sellers. Also, it helps its registered customers get advertised to users so that the outreach of their business will reach the global populace.</p>
	
	<p>If you have any questoin, you can contact us at our website on the <strong><a href="WhoSabiWork.com/Public/contactUs.php">contact page</a></strong>, or email us at support@whosabiwork.com. Thank you for your patronage.</p>
	
	<p>WhoSabiWork</p>';
	
	return sendMailFxn($from, $sender, $email, $title, $body);
}

/* This function composses email for whosabiwork */
function composeEmailToWhoSabiWork() {
	global $email; global $first_name; global $last_name; global $username;
	if ($email === "") {
		$email = "none";
	}
	
	$from = 'support@whosabiwork.com';
	$sender = 'WhoSabiWork';
	$to = 'support@whosabiwork.com';
	$title = 'New User Account';
	$body = '<img src="cid:whoSabiWorkLogo"/>
	<p>A new user account has been created by '.$first_name.' '.$last_name.'</p>
	<p>Username: '.$username.'</p>
	<p>Email address: '.$email.'.</p>
	
	<p>WhoSabiWork</p>';
	
	return sendMailFxn($from, $sender, $to, $title, $body);
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

?>