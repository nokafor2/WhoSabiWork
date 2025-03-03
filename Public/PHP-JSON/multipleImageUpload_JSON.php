<?php
	require_once("../../includes/initialize.php");

	header('Content-type: text/javascript');
	// This will be set to specify as a default output upon request from the JSON file
	// An array is actually created here
	$jsonData = array(
		'success' => false
	);

	if (request_is_post() && request_is_same_domain()) {
		global $session;		
		$post_params = allowed_post_params(['action', 'captionText', 'rotationInfo']);
		
		if (isset($post_params['action']) && ($post_params['action'] === "getMaxUploadFilesize")) {			
			$maxUploadableFilesize = file_upload_max_size();
			$maxNumFileUploads = ini_get("max_file_uploads");

			$jsonData["success"] = true;
			$jsonData["maxUploadableFilesize"] = $maxUploadableFilesize;
			$jsonData["maxNumFileUploads"] = $maxNumFileUploads;
		}	elseif (isset($_FILES)) {
			// Get the customer id for the account
			$customerId = $session->customer_id;
			$customerId = $database->escape_value($customerId);
			
			for ($i = 0; $i < count($_FILES); $i++) {				
				// Get the caption of the photo
				$caption = trim($post_params['captionText'][$i]);
				// $caption = $database->escape_value($caption);
				
				// Get the rotation position of the image
				$rotationInfo = trim($post_params['rotationInfo'][$i]);
				// Check if the rotation of the image was changed
				if ($rotationInfo > 0) {
					// Save the rotation position in the session so that it can be accessed by the photograph class
					$_SESSION['rotationInfo'] = $rotationInfo;
				}				

				$photo = new Photograph();
				$photo->attach_file($_FILES[$i]);
				$photo->customers_id = $customerId;
				$photo->caption = $caption;
				$savedPhoto = $photo->saveImage();
				if ($savedPhoto === TRUE) {
					$jsonData["success"] = true;
					$jsonData['customerId'] = $photo->customers_id;
					$jsonData['photo_caption'][] = $photo->caption;
					$jsonData['photoId'][] = $database->insert_id();
					$jsonData["result"][] = $photo->filename;
				} else {
					$message = join("<br/>", $photo->errors);
					$jsonData["failedUpload"] = "image not saved";
					$jsonData["resultError"][] = $message;
				}
			}
		}
	} else {
		$jsonData["success"] = false;
		$jsonData["result"] = "Not a valid post request or domain request";
	}

	echo json_encode($jsonData);
?>