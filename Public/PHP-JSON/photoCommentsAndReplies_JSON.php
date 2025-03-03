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
		$post_params = allowed_post_params(['photoId', 'customerId']);
		global $session;
		if (isset($post_params['photoId'], $post_params['customerId'])) {
			global $database;
			$photoId = $post_params['photoId'];
			$customerId = $post_params['customerId'];
			$photoId = $database->escape_value($photoId);
			$customerId = $database->escape_value($customerId);

			// Get the total comments on the photo
			$totalComments = Photograph_Comment::count_photograph_comments($photoId, $customerId);

			// Get all comments on the photo
			$photoComments = Photograph_Comment::find_photograph_comments($photoId, $customerId);
			// check if there are comments
			if (!empty($photoComments)) {
				// print_r($photoComments);
				$commentIds = array();
				foreach ($photoComments as $photoComment) {
					// Get the author name
					// Use loose comparison
					if ($photoComment->public_user_id != false) {
						$user = User::find_by_id($photoComment->public_user_id);
						$author = $user->full_name();
					} else {
						$customer = Customer::find_by_id($photoComment->public_customer_id);
						$author = $customer->full_name();
					}					
					$commentId = $photoComment->id;
					$comment = $photoComment->comment;
					$dateCreated = date_to_text($photoComment->date_created);
					// collect the comment ids that will be used to retrieve the their replies
					$commentIds[] = $commentId;

					// create an array for the data variables of the comments to be sent
					$json["comments"][] = array(
						"commentId" => $commentId, 
						"author" => $author,
						"comment" => $comment,
						"dateCreated" => $dateCreated
					);
				}
				$json["totalComments"] = $totalComments;

				// Use the comments to get the replies
				if (!empty($commentIds)) {
					foreach ($commentIds as $commentId) {
						$photoReplies = Photograph_Reply::find_photograph_replies($commentId);
						if (!empty($photoReplies)) {
							$replyIds = array();
							foreach ($photoReplies as $photoReply) {
								// Get the author name
								// Use loose comparison
								if ($photoReply->public_user_id != false) {
									$user = User::find_by_id($photoReply->public_user_id);
									$author = $user->full_name();
								} else {
									$customer = Customer::find_by_id($photoReply->public_customer_id);
									$author = $customer->full_name();
								}					
								$replyId = $photoReply->id;
								$reply = $photoReply->reply;
								$dateCreated = date_to_text($photoReply->date_created);
								// collect the reply ids that will be used to retrieve the their replies
								$replyIds[] = $replyId;

								// create an array for the data variables of the replies to be sent
								$json["replies"][] = array(
									"commentId" => $commentId,
									"replyId" => $replyId, 
									"author" => $author,
									"reply" => $reply,
									"dateCreated" => $dateCreated
								);
							}
						}						
					}
				}				

				$json["success"] = true;
				$json["result"] = "comments retrieved";
			} else {
				$json["success"] = true;
				$json["result"] = "No comments";
			}			
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