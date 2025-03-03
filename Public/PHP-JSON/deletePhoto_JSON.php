<?php
	require_once("../../includes/initialize.php");
	
	header('Content-type: text/javascript');
	// This will be set to specify as a default output upon request from the JSON file
	// An array is actually created here
	$json = array(
		'success' => false,
		'errors' => false
	);
	
	if(request_is_post()) {
		// This variable contains the filtered values from the GET_global variable. It is an array
		$post_params = allowed_post_params(['customerId', 'imageId']);
		
		// Eliminate HTML tags embedded in the URL inputs
		foreach($post_params as $param) {
			// run htmlentities check on the parameters
			if(isset($post_params[$param])) {
				// run htmlentities check on the parameters
				$post_params[$param] = h2($param);
			} 
		}
		
		
		// must have an ID, get the ID from the $post_params global variable which will be used to delete the user
		if(empty($post_params['customerId']) && empty($post_params['imageId'])) {
			$json["errors"] = "The image was unable to be deleted. Image data was not found.";
		} else {
			$imageId = $_POST['imageId'];
			$customerId = $_POST['customerId'];

			// Delete all likes, comments and replies on photo before deleting the photo because these properties are linked to the photo through the foreign key
			
			// Delete the likes
			// Get all the likes of the photo
			$photoLikes = Photograph_Like::find_all_photograph_likes($imageId);
			foreach ($photoLikes as $key => $record) {
				$imageLikeProperties = Photograph_Like::find_by_id($record->id);
				$imageLikeProperties->delete();		
			}

			// get all the replies of the photo
			$photoReplies = Photograph_Reply::find_photograph_replies_by_photoId($imageId);
			foreach ($photoReplies as $key => $record) {
				$imageReplyProperties = Photograph_Reply::find_by_id($record->id);
				$imageReplyProperties->delete();	
			}

			// get all the comments of the photo
			$photoComments = Photograph_Comment::find_photograph_comments_by_photoId($imageId);
			foreach ($photoComments as $key => $record) {
				$imageCommentProperties = Photograph_Comment::find_by_id($record->id);
				$imageCommentProperties->delete();		
			}
			
			$photoHandle = Photograph::find_by_id($imageId);
			// Check if the photo was found and if it was deactivated
			if($photoHandle->destroy($imageId)) {
				$json["success"] = true;
				// $session->message("The image has been deleted.");
			} else {
				$json["errors"] = "The image could not be deleted.";
			}
		}

		echo json_encode($json);
	}
?>
	