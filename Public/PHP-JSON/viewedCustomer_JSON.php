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
	$post_params = allowed_post_params(['viewedCusId', 'hashedCusId']);
	
	if (isset($post_params['viewedCusId'])) {
		$_SESSION['viewedCusId'] = $database->escape_value($post_params['viewedCusId']);
		// $_SESSION['hashedCusId'] = $post_params['viewedCusId'];

		// Save the record in the database as viewed
		$businessCategoryObj = new Business_Category();
		$businessCategory = Business_Category::find_by_customerId($post_params['viewedCusId']);
		// increment the view variable 
		$businessCategory->views = $businessCategory->views + 1;
		// Save the update;
		$businessCategory->update();

		$jsonData["success"] = true;
	} else {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
}
echo json_encode($jsonData);

?>

