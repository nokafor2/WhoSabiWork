<?php
	require_once("../../includes/initialize.php");
	
	header('Content-type: text/javascript');
	// This will be set to specify as a default output upon request from the JSON file
	// An array is actually created here
	$json = array(
		'success' => false,
		'errors' => false
	);

	if (isset($_POST['imageId']) && isset($_POST['customerId']) && isset($_POST['photo_Caption'])) {
		$photoId = $_POST['imageId'];
		$customerId = $_POST['customerId'];
		$photoCaption = trim($_POST['photo_Caption']);
		
		// Validate the input of the caption
		$fields_for_presence = array("photo_Caption");
		$validate->validate_has_presence($fields_for_presence);
		
		// Validate the maximum length of the caption
		$fields_with_max_lengths = array("photo_Caption" => 250);
		$validate->validate_max_lengths($fields_with_max_lengths);
		
		// Use the validate address field to validate the caption input
		$fields_with_caption = array("photo_Caption");
		$validate->validate_address_fields($fields_with_caption);
		
		// Check for errors
		if (empty($validate->errors)) {
			// Instantiate the properties of the photo for the customer using the photoId
			$photo = Photograph::find_by_id($photoId);
			// Update the caption and save to the database
			$photo->caption = $photoCaption;
			$photo->date_edited = current_Date_Time();
			if ($photo->update()) {
				$json["success"] = true;
			}
		} else {
			$json["errors"] = $validate->errors;
		}
	}
	echo json_encode($json);
?>