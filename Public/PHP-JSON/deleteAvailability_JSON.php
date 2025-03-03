<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file
// An array is actually created here
$jsonData = array(
	'success' => false,
	'result' => ""
);
// Request sent from deleteAvailSch() function in customerEditPage2JSScripts.js

if(request_is_post() && request_is_same_domain()) {
	global $database;
	// Check if the request is post and if it is from same web page.	
	
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['cusAvailId']);
	// print_r($post_params);
	if (isset($post_params['cusAvailId'])) {
		$cusAvailId = $post_params['cusAvailId'];
		// Check if it is user logged in
		$cusAvailabilityId = Customers_Availability::find_by_id($cusAvailId);
		if ($cusAvailabilityId->delete()) {
			$jsonData["success"] = true;
			$jsonData["result"] = "deleted";
		} else {
			$jsonData["success"] = false;
			$jsonData["result"] = "Customer availlability deleting failed.";
		}
	} else  {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
}
echo json_encode($jsonData);

?>

