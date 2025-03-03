<?php 
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');

// This will be set to specify as a default output upon request from the JSON file

// Response sent from adminPageJSs.js
// Function: $('#subject')

// An array is actually created here
$jsonData = array(
	'success' => false
);

// Initialize security function
$security = new Security_Functions();
// Check if the request is post and if it is from same web page.
if(request_is_post() && request_is_same_domain()) {
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(["subject"]);

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

	if (isset($post_params['subject'])) {
		$subject = $post_params['subject'];

		$usersFeedback = new Users_Feedback();
		$feedbackBySubject = $usersFeedback->find_by_feedback_subject($subject);

		// Converthe mySql time to a better format
		foreach ($feedbackBySubject as $key => $feedback) {
			$feedbackBySubject[$key]->date_created = datetime_to_text($feedbackBySubject[$key]->date_created);
		}
		
		$jsonData["success"] = true;
		$jsonData["foundFeedbacks"] = $feedbackBySubject;
	} else {
		$jsonData["success"] = false;
		$jsonData["postDataError"] = "An error occurred sending the data from JSON";
	}
} else {
	$jsonData["sameDomainError"] = 'Request from a different domain.';
}
echo json_encode($jsonData);

?>