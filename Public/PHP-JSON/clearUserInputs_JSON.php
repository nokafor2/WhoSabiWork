<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file
// An array is actually created here
$jsonData = array(
	'success' => false,
	'result' => ""
);
// print_r($_POST);

if(request_is_post() && request_is_same_domain()) {
	global $database;
	// Check if the request is post and if it is from same web page.	
	
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['input']);
	
	if ($post_params['input'] === 'firstName') {
		// Get the userId
		global $session;
		// Check if it is user logged in
		if ($session->is_user_logged_in()) {
			$userId = $session->user_id;
			$user = User::find_by_id($userId);
			$user->first_name = "";
			if ($user->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'lastName') {
		// Get the userId
		global $session;
		// Check if it is user logged in
		if ($session->is_user_logged_in()) {
			$userId = $session->user_id;
			$user = User::find_by_id($userId);
			$user->last_name = "";
			if ($user->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'gender') {
		// Get the userId
		global $session;
		// Check if it is user logged in
		if ($session->is_user_logged_in()) {
			$userId = $session->user_id;
			$user = User::find_by_id($userId);
			$user->gender = "";
			if ($user->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'username') {
		// Get the userId
		global $session;
		// Check if it is user logged in
		if ($session->is_user_logged_in()) {
			$userId = $session->user_id;
			$user = User::find_by_id($userId);
			$user->username = "";
			if ($user->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'email') {
		// Get the userId
		global $session;
		// Check if it is user logged in
		if ($session->is_user_logged_in()) {
			$userId = $session->user_id;
			$user = User::find_by_id($userId);
			$user->user_email = "";
			if ($user->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'phoneNumber') {
		// Get the userId
		global $session;
		// Check if it is user logged in
		if ($session->is_user_logged_in()) {
			$userId = $session->user_id;
			$user = User::find_by_id($userId);
			$user->phone_number = "";
			$user->phone_validated = 0;
			
			if ($user->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} else  {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
}
echo json_encode($jsonData);

?>

