<?php
	require_once("../../includes/initialize.php");

	header('Content-type: text/javascript');
	// This will be set to specify as a default output upon request from the JSON file
	// An array is actually created here
	$json = array(
		'success' => false,
		'errors' => false,
		'message' => ''
	);
	
	/* if(request_is_post()) {
		// This variable contains the filtered values from the GET_global variable. It is an array
		$post_params = allowed_post_params(['customerId', 'reply', 'commentId']);
		
		// Eliminate HTML tags embedded in the URL inputs
		foreach($post_params as $param) {
			// run htmlentities check on the parameters
			if(isset($post_params[$param])) {
				// run htmlentities check on the parameters
				$post_params[$param] = h2($param);
			} 
		}
	} */
	if (isset($_POST['customerId']) && isset($_POST['reply']) && isset($_POST['commentId']) && isset($_POST['userOrCusIdReplyTo']) && isset($_POST['accountType'])) {
		$customerID = $database->escape_value(trim($_POST['customerId']));
		// $reply = $database->escape_value(trim($_POST['reply']));
		$reply = trim($_POST['reply']);
		$commentId = $database->escape_value(trim($_POST['commentId']));
		$accountType = $database->escape_value($_POST['accountType']);
		$userOrCusIdReplyTo = $database->escape_value($_POST['userOrCusIdReplyTo']);
		
		global $database;
		// Determin the name of the author that is the customer making the reply with the customerId
		$author = "";
		$customer = Customer::find_by_id($customerID);
		$author = $customer->full_name();
		
		// Validate the input of the reply
		$fields_for_presence = array("reply");
		$validate->validate_has_presence($fields_for_presence);
		
		// Validate the maximum length of the reply
		$fields_with_max_lengths = array("reply" => 500);
		$validate->validate_max_lengths($fields_with_max_lengths);
		
		// Not needed because special characters will be escaped.
		// Validate the comment entered doesn't contain malicious characters
		// $fields_with_reply = array("reply");
		// $validate->validate_comment_fields($fields_with_reply);
		
		if (empty($validate->errors)) {
			// makes a reply and returns an instance that can be used to reference the properties of the reply class
			$new_reply = User_reply::make($customerID, $userOrCusIdReplyTo, $author, $reply, $commentId, $accountType);
			if ($new_reply) {
				$new_reply->create();
				// get the id of the last inserted reply
				$currentId = $database->insert_id();
				// find reply by this current id
				$replyObj = User_Reply::find_by_id($currentId);
				// Get the total number of reply for the customer
				$totalReplies = User_Reply::count_by_id($customerID);
				// return a success message after saving to the database
				$json['success'] = true;
				// return the message that was saved to the database which will be displayed.
				$json['reply'] = $replyObj->body;
				// return the total number of replys for the customer
				$json['totalReplies'] = $totalReplies;
				$json['author'] = $author;
				// $json['userOrCusIdReplyTo'] = $userOrCusIdReplyTo;
				$json['replyId'] = $currentId;
				$json['created'] = datetime_to_text($replyObj->created);
			} else {
				$json['message'] = "There was an error that prevented the reply from saving.";
				$json['errors'] = true;
			}
		} else {
			$json['errors'] = true;
			// There was an error during validation.
			$json['validate_errors'] = $validate->errors;
		}
	} else {
		$json['errors'] = true;
		// There was an error during validation.
		$json['message'] = "No data was received from ajax";
	}

	echo json_encode($json);
?>