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
	// Check if the request is post and if it is from same web page.	
	
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['currentTabIndex']);
	
	if (isset($post_params['currentTabIndex']) || $post_params['currentTabIndex'] == 0) {
		$_SESSION['lastTabIndex'] = $post_params['currentTabIndex'];

		$jsonData["success"] = true;
		$jsonData["result"] = $post_params['currentTabIndex'];
	} else {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
}
echo json_encode($jsonData);

?>

