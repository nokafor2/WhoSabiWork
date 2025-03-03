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
		// This will run when the cropped image is not going to be used
		if (!empty($_POST['path'])) {
			$imgPath = $_POST['path'];
			$strPos = strpos($imgPath, '?');
			$newImgPath = substr($imgPath, 0, $strPos);
			if (unlink($newImgPath)) {
				$json["success"] = true;
				$json["result"] = 'Image has been deleted.';
			} else {
				$json['success'] = false;
				$json['result'] = "Image could not be deleted.";
			}
		} elseif (isset($_POST['changeImage'])) {
			// This is where to unlink the initial uploaded image that was cropped from.
			$initialImage = $_SESSION['img_name'];
			if (file_exists('../images/'.$initialImage)) {
				if (unlink('../images/'.$initialImage)) {
					$json['success'] = true;
					$json['result'] = "Previous image was deleted.";
				} else {
					$json['success'] = true;
					$json['result'] = "Previous image was not deleted.";
				}	
			} else {
				$json['success'] = false;
				$json['result'] = "Previous image does not exist.";
			}

			// Delete cropped image too
			$croppedImage = $_SESSION['photo_filename'];
			if (file_exists('../images/'.$croppedImage)) {
				if (unlink('../images/'.$croppedImage)) {
					$json['success2'] = true;
					$json['result2'] = "cropped image deleted";
				} else {
					$json['success2'] = false;
					$json['result2'] = "cropped image not deleted";
				}	
			} else {
				$json['success2'] = false;
				$json['result2'] = "cropped image does not exist";
			}
		} else {
			$json["success"] = false;
			$json["result"] = "Image path was not received";
		}
	} else {
		$json["success"] = false;
		$json["result"] = "Not a valid post request or domain request";
	}
	echo json_encode($json);

?>