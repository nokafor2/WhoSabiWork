<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file
// An array is actually created here
// Response sent from adminPageJSs.js
$jsonData = array(
	'success' => false,
	'result' => ""
);

// Check if the request is post and if it is from same web page.
// Request sent from cropImage.js
if (request_is_post() && request_is_same_domain()) {
	global $session;
		
	$post_params = allowed_post_params(['username']);
	
	if (isset($post_params['username'])) {
		$username = $post_params['username'];
		// The username has been sanitized to be passed into the database.
		// $username = sql_prep($username);
		// $password = sql_prep($password);

		$userStatic = User::find_by_username($username);
		$databasePwd = $userStatic->password;
		$user = new User();
		$found_user = $user->authenticate($username, $password);
		$jsonData["success"] = true;
	} else {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
} else {
	$jsonData["success"] = false;
	$jsonData["result"] = "Not a valid post request or domain request";
}
echo json_encode($jsonData);

?>