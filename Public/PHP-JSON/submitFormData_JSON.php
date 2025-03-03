<?php 
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');

// This will be set to specify as a default output upon request from the JSON file

// Response sent from createUserAccountJSs.js
// Function: saveUserAccountFormData()

// An array is actually created here
$json = array(
	'success' => false
);

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
	
	if(!csrf_token_is_valid() || !csrf_token_is_recent()) {
		$json['success'] = false;
		$json["csrf_failure"] = "Sorry, form has expired. Please refresh and try again.";
	} else {		
		if (isset($post_params['first_name'], $post_params['last_name'], $post_params['gender'], $post_params['username'], $post_params['password'], $post_params['confirm_password'], $post_params['phone_number'], $post_params['email'])) {

			// Get the registration details for the user.
			$first_name = trim($post_params['first_name']);
			$last_name = trim($post_params['last_name']);
			$gender = trim($post_params['gender']);
			$username = trim($post_params['username']);
			$password = trim($post_params['password']);
			$confirm_password = trim($post_params['confirm_password']);
			$phone_number = trim($post_params['phone_number']);
			$email = trim($post_params['email']);
			
			// Save the data to the database
			$user = new User();
			$user->customers_id = NULL;
			$user->first_name = ucfirst($first_name);
			$user->last_name = ucfirst($last_name);
			$user->gender = $gender;
			$user->username = $username;
			// password_hash(); PHP default function for hashing password
			$user->password = $user->password_encrypt($password);
			// $user->password = password_hash($password);
			$user->phone_number = $phone_number;
			// Don't validate phone number yet.
			// $user->phone_validated = TRUE;
			$user->user_email = $email;
			$user->account_status = 'active';
			$user->date_created = $user->current_Date_Time();
			$user->date_edited = $user->current_Date_Time();
									
			if($user->save()) {
				// Get the database id of the recent saved record
				$newUserId = $database->insert_id();
				$newUser = User::find_by_id($newUserId);
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
				$session->user_login($newUser);
				// Create a log that the user has logged into the system
				user_log_action('Register', "{$newUser->username} created a user account.");
				admin_log_action('Register', "{$newUser->username} created a user account.");

				$json['success'] = true;
				$json['newUserId'] = $newUserId;
				// This message should be saved in the session.
				$session->message("<p>Congratulations ".$first_name." ".$last_name.". Your user account was successfully created.</p><p>".$json["emailOutcome"]."</p>");
			} else {
				$json['success'] = false;
				$json["savingError"] = "An error occurred while saving.";
			}
		} else { 
			$json['success'] = false;
			$json['result'] = "Data input not received";
		} 
	}
} else {
	$json["success"] = false;
	$json["result"] = "Not a valid post request or domain request";
}	
// Destroy the csrt token and time after usage or failure
destroy_csrf_token();	
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
?>