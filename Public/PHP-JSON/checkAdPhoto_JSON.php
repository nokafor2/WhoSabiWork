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
	
	if (isset($post_params['imageId'], $post_params['customerId'])) {
		$photoId = $database->escape_value($post_params['imageId']);
		$customerId = $database->escape_value($post_params['customerId']);
		// Check if the photo id is the ad photo
		$photo = Photograph::find_ad_photo($customerId);
		// If it doesn't exists, no saved ad photo
		if (!empty($photo)) {
			$adPhotoId = $photo->id;
			if ($adPhotoId == $photoId) {
				// Selected image is the cover photo
				$jsonData["success"] = true;
				$jsonData["result"] = "ad photo";
			} else {
				$jsonData["success"] = true;
				$jsonData["result"] = "not ad photo";
			}
		} else {
			// No photo is set as ad photo
			$jsonData["success"] = true;
			$jsonData["result"] = "no ad photo";
		}		
	} else {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
}
echo json_encode($jsonData);

?>

