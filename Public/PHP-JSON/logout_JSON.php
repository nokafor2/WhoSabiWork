<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');

$jsonData = array(
	'success' => false
);

if(request_is_post() && request_is_same_domain()) {
	// Check if the request is post and if it is from same web page.
	global $session;	
	
	$session->logout();
	$session->message("You logged out successfully. Have a nice day.");
	redirect_to("/Public/index.php");
	
	$jsonData['success'] = true;
}

echo json_encode($jsonData);

?>