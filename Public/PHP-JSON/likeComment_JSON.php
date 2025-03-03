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
		$post_params = allowed_post_params(['photoId', 'customerId', 'comment', 'like', 'reply', 'commentId']);
		global $session;
		if (isset($post_params['like'])) {
			if (isset($post_params['customerId'], $post_params['photoId'])) {
				$customerId = $post_params['customerId'];
				$photoId = $post_params['photoId'];

				// Check if the public user has liked the photo before
				// Determine if it is user logged in or customer logged in
				$likes = new Photograph_Like();
				$alreadyLiked = "";
				if ($session->is_user_logged_in()) {
					$public_user_id = $session->user_id;
					$likeObj = Photograph_Like::find_photograph_liked_by_user($photoId, $public_user_id);
					if (!empty($likeObj)) {
						$alreadyLiked = "liked";
					} else {
						$alreadyLiked = "notLiked";
						$likes->public_user_id = $public_user_id;
					}
					$loggedIn = ture;
				} elseif ($session->is_customer_logged_in()) {
					$public_customer_id = $session->customer_id;
					$likeObj = Photograph_Like::find_photograph_liked_by_customer($photoId, $public_customer_id);
					if (!empty($likeObj)) {
						$alreadyLiked = "liked";
					} else {
						$alreadyLiked = "notLiked";
						$likes->public_customer_id = $public_customer_id;
					}
					$loggedIn = true;
				} else {
					$loggedIn = false;
				}

				// Check if the user is logged in or not
				if ($loggedIn) {
					$likes->photograph_id = $photoId;
					$likes->customers_id = $customerId;
					$likes->likes = true;
					$likes->date_created = current_Date_Time();			

					if ($alreadyLiked == "notLiked") {
						if ($likes->create()) {
							// Data was successfully saved in the database
							// Get the number of likes for the photo
							$json["success"] = true;
							$numLikes = Photograph_Like::count_photograph_likes($photoId, $customerId);
							if (isset($numLikes)) {
								$json["result"] = "liked";
								$json["numLike"] = $numLikes;
							} else {
								$json["result"] = "data saved";
							}
						} else {
							$json["success"] = false;
							$json["result"] = "data not saved";
						}
					} else {
						$json["success"] = true;
						$json["result"] = "already liked";
					}
				} else {
					$json["success"] = false;
					$json["result"] = "not logged in";
				}
			}
		} elseif (isset($post_params['comment'])) {
			if (isset($post_params['customerId']) && isset($post_params['comment']) && isset($post_params['photoId'])) {
				$comment = trim($post_params['comment']);
				$photoId = $post_params['photoId'];
				$customerID = $post_params['customerId'];

				$customerID = $database->escape_value($customerID);
				// $comment = $database->escape_value($comment);
				$photoId = $database->escape_value($photoId);
				$accountType = "";
				$userOrCusId = 0;
				
				global $database;
				// Determin if the comment is sent from a user or a customer account
				if ($session->is_user_logged_in()) {
					$userOrCusId = $_SESSION['user_id'];
					$author = $_SESSION['user_full_name'];
					$accountType = "user";
				} elseif (($session->is_customer_logged_in())) {
					$userOrCusId = $_SESSION['customer_id'];
					$author = $_SESSION['customer_full_name'];
					$accountType = "customer";
				}
				
				// Validate the input of the comment
				$fields_for_presence = array("comment");
				$validate->validate_has_presence($fields_for_presence);
				
				// Validate the maximum length of the comment
				$fields_with_max_lengths = array("comment" => 500);
				$validate->validate_max_lengths($fields_with_max_lengths);
				
				/*
				// This is not necessary because all the special characters will be escaped for safe use
				// Validate the comments to ensure it doesn't contain malicious characters				
				$fields_with_comment = array("comment");
				$validate->validate_comment_fields($fields_with_comment); */
				
				if (empty($validate->errors)) {
					// makes a comment and returns an instance that can be used to reference the properties of the comment class
					$new_comment = Photograph_Comment::make($photoId, $customerID, $userOrCusId, $comment);
					if ($new_comment) {
						$new_comment->create();
						// get the id of the last inserted comment
						$currentId = $database->insert_id();
						// find comment by this id
						$commentObj = Photograph_Comment::find_by_id($currentId);
						// Get the total number of comment for the photograph
						$totalComments = Photograph_Comment::count_photograph_comments($photoId, $customerID);
						// return a success message after saving to the database
						$json['success'] = true;
						$json['result'] = true;
						// return the message that was saved to the database which will be displayed.
						$json['comment'] = $commentObj->comment;
						// return the total number of comments for the customer
						$json['totalComments'] = $totalComments;
						$json['accountType'] = $accountType;
						$json['userOrCusId'] = $userOrCusId;
						$json['commentId'] = $currentId;
						$json['author'] = $author;
						$json['created'] = date_to_text($commentObj->date_created);
					} else {
						$json['message'] = "There was an error that prevented the comment from saving.";
						$json['errors'] = true;
					}
				} else {
					$json['errors'] = true;
					// There was an error during validation.
					$json['validate_errors'] = $validate->errors;
				}
			}
		} elseif (isset($post_params['reply'])) {	
			$reply = trim($post_params['reply']);
			$commentId = $post_params['commentId'];
			$photoId = $post_params['photoId'];
			$customerID = $post_params['customerId'];

			$customerID = $database->escape_value($customerID);
			$reply = $database->escape_value($reply);
			$photoId = $database->escape_value($photoId);
			$commentId = $database->escape_value($commentId);
			$accountType = "";
			$userOrCusId = 0;
			
			global $database;
			// Determine if the reply is sent from a user or a customer account
			if ($session->is_user_logged_in()) {
				$userOrCusId = $_SESSION['user_id'];
				$author = $_SESSION['user_full_name'];
				$accountType = "user";
			} elseif (($session->is_customer_logged_in())) {
				$userOrCusId = $_SESSION['customer_id'];
				$author = $_SESSION['customer_full_name'];
				$accountType = "customer";
			}
			
			// Validate the input of the comment
			$fields_for_presence = array("reply");
			$validate->validate_has_presence($fields_for_presence);
			
			// Validate the maximum length of the comment
			$fields_with_max_lengths = array("reply" => 500);
			$validate->validate_max_lengths($fields_with_max_lengths);
			
			// Validate the reply to ensure it doesn't contain malicious characters
			$fields_with_comment = array("reply");
			$validate->validate_comment_fields($fields_with_comment);

			if (empty($validate->errors)) {
				// makes a reply and returns an instance that can be used to reference the properties of the reply class
				$new_reply = Photograph_Reply::make($commentId, $photoId, $customerID, $userOrCusId, $reply);
				if ($new_reply) {
					$new_reply->create();
					// get the id of the last inserted comment
					$currentId = $database->insert_id();
					// find comment by this id
					$replyObj = Photograph_Reply::find_by_id($currentId);
					// Get the total number of reply for the photograph
					// $totalReplies = Photograph_Reply::count_photograph_replies($commentId, $photoId, $customerID);
					// return a success message after saving to the database
					$json['success'] = true;
					$json["result"] = true;
					// return the message that was saved to the database which will be displayed.
					$json['reply'] = $replyObj->reply;
					// return the total number of replies for the customer
					// $json['totalReplies'] = $totalReplies;
					$json['accountType'] = $accountType;
					$json['userOrCusId'] = $userOrCusId;
					$json['replyId'] = $currentId;
					$json['author'] = $author;
					$json['created'] = date_to_text($replyObj->date_created);
				} else {
					$json['message'] = "There was an error that prevented the reply message from saving.";
					$json['errors'] = true;
				}
			} else {
				$json['errors'] = true;
				// There was an error during validation.
				$json['validate_errors'] = $validate->errors;
			}
		}
	} else {
		$json["success"] = false;
		$json["result"] = "Not a valid post request or domain request";
	}
	echo json_encode($json);
?>