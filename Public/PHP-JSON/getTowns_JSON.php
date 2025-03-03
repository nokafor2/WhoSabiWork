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
	$post_params = allowed_post_params(['state']);
	
	if (isset($post_params['state'])) {
		$state = $post_params['state'];
		// Get the twons of the selected state from the database
		$towns = State_Town::find_by_column($state);
		// Remove underscores
		// capitalize the first words of the town names
		foreach ($towns as $key => $value) {
			// remove the underscore in the name of towns
			$towns[$key] = str_replace('_', ' ', $value);
			// convert the text to lower case
			$towns[$key] = ucwords($value);
		}
		// Sort the towns in alphabetical order
		asort($towns);

		$jsonData["success"] = true;
		$jsonData["result"] = $towns;
	} else {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
}
echo json_encode($jsonData);

?>

