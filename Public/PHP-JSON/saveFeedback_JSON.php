<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file
// An array is actually created here
$jsonData = array(
	'success' => false
);

// Check if the request is post and if it is from same web page.
// Request sent from cropImage.js
if (request_is_post() && request_is_same_domain()) {
	global $session;
	
	$post_params = allowed_post_params(['first_name', 'last_name', 'phone_number', 'email', 'message_content', 'message_subject']);

	// Eliminate HTML tags embedded in the form inputs
	foreach($post_params as $param) {
		// run htmlentities check on the parameters
		if(isset($post_params[$param])) {
			// run htmlentities check on the parameters
			$post_params[$param] = h2($param);
		} 
	}

	if(!csrf_token_is_valid() || !csrf_token_is_recent()) {
		$jsonData["csrfFailure"] = "Sorry, request was not valid. Form is expired.";
	} else {
		// CSRF tests passed--form was created by us recently.
		if (isset($post_params['first_name'], $post_params['last_name'], $post_params['phone_number'], $post_params['email'], $post_params['message_content'], $post_params['message_subject'])) {
			
			$first_name = trim($post_params['first_name']);
			$last_name = trim($post_params['last_name']);
			$email = trim($post_params['email']);
			$phone_number = trim($post_params['phone_number']);
			$message_subject = trim($post_params['message_subject']);
			$message_content = trim($post_params['message_content']);
			
			// Validate a message subject was selected
			if (array_key_exists("message_subject", $post_params)) {
				if (!isset($post_params["message_subject"]) || ($post_params["message_subject"] === "Select")){
					$validate->errors["message_subject"] = "Select a subject for your complain.";
				} else {
					// save the variable 
					$message_subject = $post_params["message_subject"];
				}
			}
			// This function is used to validate other inputs from the user
			$validate->validate_name_update();
			
			if (empty($validate->errors)) {
				$usersFeedback = new Users_Feedback();
				$usersFeedback->first_name = $first_name;
				$usersFeedback->last_name = $last_name;
				$usersFeedback->phone_number = $phone_number;
				$usersFeedback->email_address = $email;
				$usersFeedback->message_subject = $message_subject;
				$usersFeedback->message_content = $message_content;
				$usersFeedback->date_created = current_Date_Time();
				
				if($usersFeedback->save()) {
					// Success
					$jsonData["success"] = true;
					$jsonData["feedbackSaved"] = "Thank you for the feedback. We would get back to you as soon as possible.";
					// This message should be saved in the session.
					// $session->message("Thank you for the feedback. We would get back to you as soon as possible.");

					// Send an email to support service
					$fromEmail = 'support@whosabiwork.com';
					$fromName = 'WhoSabiWork';
					$to = 'support@whosabiwork.com';
					$title = 'User Feedback';
					$body = makeEmailMessage($first_name, $last_name, $phone_number, $email, $message_subject, $message_content);
					$outcome = sendMailFxn($fromEmail, $fromName, $to, $title, $body);
					$jsonData["supportEmailOutcome"] = $outcome;

					// Send a mail to the user
					$fromEmail = 'support@whosabiwork.com';
					$fromName = 'WhoSabiWork';
					$to = $email;
					$title = 'WhoSabiWork has Received Your Feedback';
					$body = makeEmailMessageUser($first_name, $last_name, $message_subject, $message_content);
					if (isset($email)) {
						$outcome = sendMailFxn($fromEmail, $fromName, $to, $title, $body);
					} else {
						$outcome = "No email was provided.";
					}
					$jsonData["userEmailOutcome"] = sendEmailOutcome2($outcome);
				} else {
					$jsonData["savingError"] = "An error occurred while saving.";
				}
			} else {
				$jsonData["validationError"] = $validate->errors;
			}
		} else {
			$jsonData["postError"] = "Feedback data not received.";
		}	
	}
	// reset the CSRF token and time
	$jsonData["newCSRFtoken"] = create_csrf_token();
	$jsonData["newCSRFtime"] = $_SESSION['csrf_token_time'];
} else {
	$jsonData["success"] = false;
	$jsonData["sameDomainError"] = "Not a valid post request or domain request";
}
echo json_encode($jsonData);

function makeEmailMessage($firstName, $lastName, $phoneNumber, $email, $subject, $content) {
	$emailMessage = '<img src="cid:whoSabiWorkLogo">';
	$emailMessage .= '<p>A customer has sent a feedback.</p>';
	$emailMessage .= '<p>Name: '.$firstName.' '.$lastName.'</p>';
	$emailMessage .= '<p>Phone number: '.$phoneNumber.'</p>';
	$emailMessage .= '<p>Email address: '.$email.'</p>';
	$emailMessage .= '<p>Message subject: '.$subject.'</p>';
	$emailMessage .= '<p>Message content: '.$content.'<p>';
	$emailMessage .= '<p>Date: '.date_to_text(current_Date_Time()).'</p>';

	return $emailMessage;
}

function makeEmailMessageUser($firstName, $lastName, $subject, $content) {
	$emailMessage = '<img src="cid:whoSabiWorkLogo">';
	$emailMessage .= '<p>Good day '.$firstName.' '.$lastName.',</p>';
	$emailMessage .= '<p>Your feedback has been well received. We will get back to you as soon as possible.</p>';
	$emailMessage .= '<p>Message subject: '.$subject.'</p>';
	$emailMessage .= '<p>Message content: '.$content.'<p>';
	$emailMessage .= '<p>Date: '.date_to_text(current_Date_Time()).'</p>';
	$emailMessage .= '<p>WhoSabiWork</p>';

	return $emailMessage;
}
?>