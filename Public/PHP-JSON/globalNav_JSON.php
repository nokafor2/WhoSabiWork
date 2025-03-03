<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file

/* This function is referenced from genericJSs.js file and the functions in use of it are $('#profileBtn') and $('#logoutBtn') */

// An array is actually created here
$jsonData = array(
	'success' => false
);

// Check if the request is post and if it is from same web page.
// Request sent from cropImage.js
if (request_is_post() && request_is_same_domain()) {
	global $session; global $database;
	
	// Check if what type of user is logged in
	if ($session->is_user_logged_in()) {
		// Get the customer id from the session
		$userId = $session->user_id;
		$path = "/Public/user/userEditPage.php?id=".$session->user_id;
	} elseif ($session->is_customer_logged_in()) {
		// Get the customer id from the session
		$customerId = $session->customer_id;
		$path = "/Public/customer/customerEditPage2.php?id=".$session->customer_id;
	} elseif ($session->is_admin_logged_in()) {
		// Get the customer id from the session
		$adminId = $session->admin_id;
		$path = "/Public/admin/adminPage.php";
	}

	$post_params = allowed_post_params(['action']);
	if (isset($post_params['action']) && ($post_params['action'] === 'profileBtn')) {
		// redirect_to($path);

		$jsonData["success"] = true;
		$jsonData["redirectPath"] = $path;
	} elseif (isset($post_params['action']) && ($post_params['action'] === 'logoutBtn')) {
		$session->logout();
		$session->message("You logged out successfully. Have a nice day.");
		// redirect_to("/Public/index.php");

		$jsonData["success"] = true;
		$jsonData["redirectPath"] = "/Public/index.php";
	} elseif (isset($post_params['action']) && ($post_params['action'] === 'checkLogin')) {
		if ($session->is_user_logged_in() || $session->is_customer_logged_in() || $session->is_admin_logged_in()) {
			$jsonData["success"] = true;
			$jsonData["result"] = "loggedInOn";
		}
	}
} else {
	$jsonData["success"] = false;
	$jsonData["result"] = "Not a valid post request or domain request";
}
echo json_encode($jsonData);

?>