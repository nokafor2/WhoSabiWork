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
	$post_params = allowed_post_params(['imageId', 'customerId']);
	
	if (isset($post_params['imageId'])) {
		$photoId = $database->escape_value($post_params['imageId']);
		$customerId = $database->escape_value($post_params['customerId']);
		// Find the previous saved ad photo
		$photo = Photograph::find_ad_photo($customerId);
		// If if doesn't exists, no saved ad photo
		if (!empty($photo)) {
			$oldAdPhotoId = $photo->id;
			// Erase the old ad photo
			$oldAdPhoto = Photograph::find_by_id($oldAdPhotoId);
			$oldAdPhoto->ad_photo = 0;
			$oldAdPhoto->date_edited = current_Date_Time();
			// Save the update;
			$oldAdPhoto->update();
		}

		// Save the record in the database as visible ad photo
		$newAdPhoto = Photograph::find_by_id($photoId);
		$newAdPhoto->ad_photo = 1;
		$newAdPhoto->date_edited = current_Date_Time();
		// Save the update;
		$newAdPhoto->update();

		$jsonData["success"] = true;
		if (isset($oldAdPhotoId)) {
			$jsonData["oldAdPhotoId"] = $oldAdPhotoId;
		}
		// $jsonData["oldAdPhotoId"] = $oldAdPhotoId;
		$jsonData["result"] = "image set";
	} else {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
}
echo json_encode($jsonData);

?>

