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
	global $session;
	// check the name of the php script running, output is the name of this script running.
	$currentPageName = substr($_SERVER['SCRIPT_NAME'], strpos($_SERVER['SCRIPT_NAME'], '/') + 1);
	
	$post_params = allowed_post_params(['caption', 'avatarImage']);
	//  && isset($post_params['customerId'])
	if (isset($_FILES['photo'])) {
		global $validate;
		$caption = trim($post_params['caption']);
		$caption = $database->escape_value($caption);
		$customerId = $session->customer_id;
		$customerId = $database->escape_value($customerId);
		// Check if it is an avatar image.
		if (isset($post_params['avatarImage'])) {
			$avatarImage = $database->escape_value($post_params['avatarImage']);
		}

		// Validate the caption entered using the validate address fxn.
		if ($validate->validate_comment($caption)) {
			$validate->errors["Invalid_caption"] = "The image caption contains a special character.";
		}

		$photo = new Photograph();
		if (empty($validate->errors)) {			
			$photo->attach_file($_FILES['photo']);
			$photo->customers_id = $customerId;
			// determine if its an avatar image to be saved or an image gallery upload
			if (isset($avatarImage)) {
				$photo->avatar = $avatarImage;
			} else {
				$photo->caption = $caption;
			}			
			$savedPhoto = $photo->save();
			if ($savedPhoto === TRUE) {
				$jsonData["success"] = true;
				$jsonData["result"] = $_SESSION['img_name'];
			} else {
				$message = join("<br/>", $photo->errors);
				$jsonData["success"] = "image not saved";
				$jsonData["result"] = $message;
			}
		} else {
			// $message = join("<br/>", $photo->errors);
			$jsonData["success"] = "image not saved";
			$jsonData["result"] = $validate->errors["Invalid_caption"];
		}				
	} elseif (isset($_POST['caption'])) {
		global $validate;
		$caption = trim($post_params['caption']);
		$caption = $database->escape_value($caption);

		// Validate the caption entered using the valideate address fxn.
		if ($validate->validate_comment($caption)) {
			$validate->errors["Invalid_caption"] = "The image caption contains a special character.";
		}

		if (empty($validate->errors)) {
			$jsonData["success"] = true;
			$jsonData["result"] = "Caption is valid";
		} else {
			$jsonData["success"] = false;
			$jsonData["result"] = $validate->errors["Invalid_caption"];
		}
	} else {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
} else {
	$jsonData["success"] = false;
	$jsonData["result"] = "Not a valid post request or domain request";
}
echo json_encode($jsonData);

?>