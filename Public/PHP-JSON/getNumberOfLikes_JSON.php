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
		$post_params = allowed_post_params(['photoId', 'customerId']);
		if (isset($post_params['photoId'], $post_params['customerId'])) {
			$photoId = $post_params['photoId'];
			$customerId = $post_params['customerId'];

			// Get number of likes for photo	
			$numLikes = Photograph_Like::count_photograph_likes($photoId, $customerId);
			// check if there is a value for number of likes
			if (!isset($numLikes)) {
				$numLikes = 0;
			}

			// check if the image has been liked or commented by user or customer
			if ($session->is_user_logged_in()) {
				$public_user_id = $session->user_id;
				$isLiked = Photograph_Like::find_photograph_liked_by_user($photoId, $public_user_id);
			} elseif ($session->is_customer_logged_in()) {
				$public_customer_id = $session->customer_id;
				$isLiked = Photograph_Like::find_photograph_liked_by_customer($photoId, $public_customer_id);
			}
			if (!empty($isLiked)) {
				$photoLiked = "yes";
			} else {
				$photoLiked = "no";
			}

			$json["success"] = true;
			$json["result"]["numLikes"] = $numLikes;
			$json["result"]["photoLiked"] = $photoLiked;
		} else {
			$json["success"] = false;
			$json["result"] = "Data was not sent.";
		}
	} else {
		$json["success"] = false;
		$json["result"] = "Not a valid post request or domain request";
	}
	echo json_encode($json);

?>