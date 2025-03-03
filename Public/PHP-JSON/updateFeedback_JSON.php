<?php 
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');

// This will be set to specify as a default output upon request from the JSON file

// Response sent from adminPageJSs.js
// Function: resolved()

// An array is actually created here
$jsonData = array(
	'success' => false
);

// Initialize security function
$security = new Security_Functions();
// Check if the request is post and if it is from same web page.
if(request_is_post() && request_is_same_domain()) {
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(["id", "subject", "action"]);

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

	if (isset($post_params['id'], $post_params['subject']) && ($post_params['action'] === 'resolved')) {
		$id = $post_params['id'];
		$subject = $post_params['subject'];

		$feedback = Users_Feedback::find_by_id($id);
		$feedback->resolved = true;
		
		// Save update
		if ($feedback->update()) {
			$jsonData["success"] = true;
			$jsonData["feedbackUpdate"] = "Feedback has been updated to resolved.";
			// Count resolved and unresolved feedbacks
			$jsonData["numResolved"] = $feedback->count_feedback($subject, 'resolved');
			$jsonData["numUnesolved"] = $feedback->count_feedback($subject, 'unresolved');
		} else {
			$jsonData["saveError"] = "There was an error saving feedback update.";
		}
	} elseif (isset($post_params['id'], $post_params['subject']) && ($post_params['action'] === 'unresolved')) {
		$id = $post_params['id'];
		$subject = $post_params['subject'];

		$feedback = Users_Feedback::find_by_id($id);
		$feedback->resolved = false;
		
		// Save update
		if ($feedback->update()) {
			$jsonData["success"] = true;
			$jsonData["feedbackUpdate"] = "Feedback has been updated to unresolved.";
			// Count resolved and unresolved feedbacks
			$jsonData["numResolved"] = $feedback->count_feedback($subject, 'resolved');
			$jsonData["numUnesolved"] = $feedback->count_feedback($subject, 'unresolved');
		} else {
			$jsonData["saveError"] = "There was an error saving feedback update.";
		}
	} elseif (isset($post_params['id'], $post_params['subject']) && ($post_params['action'] === 'delete')) {
		$id = $post_params['id'];
		$subject = $post_params['subject'];

		$feedback = Users_Feedback::find_by_id($id);
		
		// Delete update
		if ($feedback->delete()) {
			$jsonData["success"] = true;
			$jsonData["feedbackDelete"] = "Feedback has been deleted.";
			// Count resolved and unresolved feedbacks
			$jsonData["numResolved"] = $feedback->count_feedback($subject, 'resolved');
			$jsonData["numUnesolved"] = $feedback->count_feedback($subject, 'unresolved');
		} else {
			$jsonData["deleteError"] = "There was an error deleting feedback.";
		}
	} else {
		$jsonData["success"] = false;
		$jsonData["postDataError"] = "An error occurred sending the data from JSON";
	}
} else {
	$jsonData["sameDomainError"] = 'Request from a different domain.';
}
echo json_encode($jsonData);

?>