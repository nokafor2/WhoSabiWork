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
		$post_params = allowed_post_params(['customerId', 'comment']);
		
		// Eliminate HTML tags embedded in the URL inputs
		foreach($post_params as $param) {
			// run htmlentities check on the parameters
			if(isset($post_params[$param])) {
				// run htmlentities check on the parameters
				$post_params[$param] = h2($param);
			} 
		}
	} */
	if (isset($_POST['customerId']) && isset($_POST['comment'])) {
		$customerID = $database->escape_value(trim($_POST['customerId']));
		// $comment = $database->escape_value(trim($_POST['comment']));
		$comment = trim($_POST['comment']);
		$accountType = "";
		$author = "";
		$userOrCusId = 0;
		
		global $database;
		// Determin if the comment is sent from a user or a customer account
		if ($session->is_user_logged_in()) {
			$author = $_SESSION['user_full_name'];
			$userOrCusId = $_SESSION['user_id'];
			$accountType = "user";
		} elseif (($session->is_customer_logged_in())) {
			$author = $_SESSION['customer_full_name'];
			$userOrCusId = $_SESSION['customer_id'];
			$accountType = "customer";
		}
		
		// Validate the input of the comment
		$fields_for_presence = array("comment");
		$validate->validate_has_presence($fields_for_presence);
		
		// Validate the maximum length of the comment
		$fields_with_max_lengths = array("comment" => 500);
		$validate->validate_max_lengths($fields_with_max_lengths);
		
		// Validate the comments to ensure it doesn't contain malicious characters
		// $fields_with_comment = array("comment");
		// $validate->validate_comment_fields($fields_with_comment);
		
		if (empty($validate->errors)) {
			// makes a comment and returns an instance that can be used to reference the properties of the comment class
			$new_comment = User_Comment::make($customerID, $userOrCusId, $author, $comment);
			if ($new_comment) {
				$new_comment->create();
				// get the id of the last inserted comment
				$currentId = $database->insert_id();
				// find comment by this id
				$commentObj = User_Comment::find_by_id($currentId);
				// Get the total number of comment for the customer
				$totalComments = User_Comment::count_by_id($customerID);
				// return a success message after saving to the database
				$json['success'] = true;
				// return the message that was saved to the database which will be displayed.
				$json['comment'] = $commentObj->body;
				// return the total number of comments for the customer
				$json['totalComments'] = $totalComments;
				$json['author'] = $author;
				$json['accountType'] = $accountType;
				$json['userOrCusId'] = $userOrCusId;
				$json['commentId'] = $currentId;
				$json['created'] = datetime_to_text($commentObj->created);
			} else {
				$json['message'] = "There was an error that prevented the comment from saving.";
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