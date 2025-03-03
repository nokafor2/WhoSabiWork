<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file
// An array is actually created here
$jsonData = array(
	'success' => false,
	'result' => ""
);

// Check if the request is post and if it is from same web page.
// Request sent from cropImage.js
if (request_is_post() && request_is_same_domain()) {
	global $session; global $database;
	// Get the customer id from the session
	$customerId = $session->customer_id;

	$post_params = allowed_post_params(['advertise', 'cancelAdvert']);
	if (isset($post_params['advertise']) && ($post_params['advertise'] === 'advertise')) {
		// Get the record of the customer business details from the database
		$businessCategory = Business_Category::find_by_customerId($customerId);
		// update advertisement value
		$businessCategory->advertise = true;
		// Update the record
		$updated = $businessCategory->update();
		if ($updated) {
			$jsonData["success"] = true;
			$jsonData["result"] = "Advertising saved";
		} else {
			$jsonData["result"] = "Saving failed";
		}
	} elseif (isset($post_params['cancelAdvert']) && ($post_params['cancelAdvert'] === 'cancelAdvert')) {
		// Get the record of the customer business details from the database
		$businessCategory = Business_Category::find_by_customerId($customerId);
		// update advertisement value
		$businessCategory->advertise = false;
		// Update the record
		$updated = $businessCategory->update();
		if ($updated) {
			$jsonData["success"] = true;
			$jsonData["result"] = "Advertising canceled";
		} else {
			$jsonData["result"] = "Saving failed";
		}
	}
} else {
	$jsonData["success"] = false;
	$jsonData["result"] = "Not a valid post request or domain request";
}
echo json_encode($jsonData);

?>