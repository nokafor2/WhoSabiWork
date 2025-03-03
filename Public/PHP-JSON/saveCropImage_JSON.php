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
		global $session;
		if (!empty($_POST['upload'])) {
			// Delete the initial uploaded image cropped from
			$initialImage = $_SESSION['img_name'];
			if (file_exists('../images/'.$initialImage)) {
				if (unlink('../images/'.$initialImage)) {
					$json['success'] = true;
					$json['result'] = "Previous image was deleted.";
				}
			}

			// Get the properties of the image to be saved in the database from the session after the photograph script must have finished running
			$photo = new Photograph();
			$photo->customers_id = $_SESSION['photo_customers_id'];
			$photo->filename = $_SESSION['photo_filename'];
			$photo->type = $_SESSION['photo_type'];
			$photo->size = $_SESSION['photo_size'];
			$photo->caption = $_SESSION['photo_caption'];
			$photo->ad_photo = $_SESSION['photo_ad_photo'];
			$photo->visible = $_SESSION['photo_visible'];
			$photo->date_created = $_SESSION['photo_date_created'];
			
			// $photo->create()
			if ($photo->create()) {
				// $session->message("Your photograph have been saved successfully.");
				$json['success'] = true;
				$json['photo_caption'] = $_SESSION['photo_caption'];
				$json['photoId'] = $database->insert_id();
				
				// After successfully saving photo details, unset the photo session variables
				unset($_SESSION['photo_customers_id']);
				unset($_SESSION['photo_filename']);
				unset($_SESSION['photo_type']);
				unset($_SESSION['photo_size']);
				unset($_SESSION['photo_caption']);
				unset($_SESSION['photo_ad_photo']);
				unset($_SESSION['photo_visible']);
				unset($_SESSION['photo_date_created']);
				unset($_SESSION['img_name']);
			} else {
				$json['result'] =  "The photo was not saved.";
			}
		} else {
			$json["success"] = false;
			$json["result"] = "Image was not received";
		}
	} else {
		$json["success"] = false;
		$json["result"] = "Not a valid post request or domain request";
	}
	echo json_encode($json);

?>