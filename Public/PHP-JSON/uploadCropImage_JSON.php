<?php
	require_once("../../includes/initialize.php");

	header('Content-type: text/javascript');
	// This will be set to specify as a default output upon request from the JSON file
	// An array is actually created here
	$json = array(
		'success' => false,
		'result' => false
	);
	
	
	if (request_is_post() && request_is_same_domain()) {
		// This runs when the image is cropped
		if (isset($_FILES["croppedImage"])) {
			/* $croppedImage = explode(".", $_FILES['croppedImage']['name']);
			// print_r($croppedImage);
			$to_be_uploaded = $_FILES['croppedImage']['tmp_name'];
			$croppedImageName = $_FILES['croppedImage']['name'];
			$size = $_FILES['croppedImage']['size'];
			$type = $_FILES['croppedImage']['type'];
			$extention = end($croppedImage);
			$new_file = '../images/'.$_FILES['croppedImage']['name'];
			if (file_exists($new_file)) {
				unlink($new_file);
			}

			move_uploaded_file($to_be_uploaded, $new_file);
			// Unset temporary path after upload of image
			unset($to_be_uploaded);

			// Update the image properties saved in the session with the cropped image.
			$_SESSION['photo_filename'] = $croppedImageName;
			$_SESSION['photo_type'] = $type;
			$_SESSION['photo_size'] = $size; */
			
			// Save the image properties to the databse
			$customerId = $session->customer_id;
			$customerId = $database->escape_value($customerId);
			$photo = new Photograph();			
			$photo->attach_file($_FILES['croppedImage']);
			$photo->customers_id = $customerId;
			// determine if its an avatar image to be saved or an image gallery upload
			$photo->avatar = true;			
			$savedPhoto = $photo->saveCrop();
			if ($savedPhoto === TRUE) {
				$json["success"] = true;
				$json["result"] = $_SESSION['img_name'];
			} else {
				$message = join("<br/>", $photo->errors);
				$json["success"] = "image not saved";
				$json["result"] = $message;
			}

			// Append filetime to the image, so that an updated image can always be uploaded.
			$new_file = $_SESSION['target_path_name'];
			$new_file = '../images/'.$new_file;
			$filetime = filemtime($new_file);
			
			// After moving the cropped image, save to the database
			
			// Send back data to be displayed
			// The 'new_file' path can still be used since the respective codes have to move back one folder and into the images folder
			// Appending the filetime with the '?' helps the browser/cache to use the current image since it has the same filename.
			$json['success'] = true;			
			$json['result'] = '<img id="cropped_image" src="'.$new_file.'?'.$filetime.'" />
					<div id="actionBtns">
					<button type="button" data-path="'.$new_file.'?'.$filetime.'" id="remove_button" class="crop_button btnStyle1" > Remove Image </button>			
					<button type="button" data-path="'.$new_file.'?'.$filetime.'" id="upload_image" class="crop_button btnStyle1" > Upload Image </button> 
					</div>';
			// echo true;
		} else {
			$json["success"] = false;
			$json["result"] = "Image not received.";
		}
	} else {
		$json["success"] = false;
		$json["result"] = "Not a valid post request or domain request";
	}
	echo json_encode($json);
?>