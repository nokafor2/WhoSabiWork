
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

if (request_is_post() && request_is_same_domain()) {
	global $session;
	// Check if the request is post and if it is from same web page.
	// print_r($_POST['caption']);

	if (isset($_FILES['file'])) {
		$photo = new Photograph();
		$photo->attach_file($_FILES['file']);

		$savedPhoto = $photo->save();
		if ($savedPhoto === TRUE) {
			// Success
			$session->message("Photograph uploaded successfully. Crop your image to your choice in the photo gallery.");


			$jsonData["result"] = $_SESSION['img_name'];
		}

		// echo "Image file data: ";
		// $filename = $_FILES['file']['name'];
		// $filename = $_FILES['file'];
		// print_r($filename);	
		
		$jsonData["success"] = true;
	} else {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
}
echo json_encode($jsonData);

?>

