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

		// Get the limit and offset values
		$limit = $_POST['limit'];
		$offset = $_POST['offset'];

		$advocate = new Advocate();
		if ($session->is_user_logged_in()) {
			$public_user_id = $session->user_id;
			$advocated = $advocate->find_all_advocated_by_user($public_user_id, $limit, $offset);
		} elseif ($session->is_customer_logged_in()) {
			$public_customer_id = $session->customer_id;
			$advocated = $advocate->find_all_advocated_by_customer($public_customer_id, $limit, $offset);
		}

		// Get customers ids of the adovated
		$customerIds = array();
		foreach ($advocated as $record) {
			$customerIds[] = $record->customers_id;
		}
		
		// Get the customers for paid advertisement
		$advertisers = Business_Category::find_cus_advertisements();
		// Get customers ids of the advertisers and add it to the customerIds array
		/* foreach ($advertisers as $record) {
			$customerIds[] = $record->customers_id;
		} */

		// Eliminate duplicate of the customers ids
		$customerIds = array_unique($customerIds);
		// Check for array keys if they are uniform and orderly

		// Get photograph records for the customers ids
		$advocatedPhotos = array();
		foreach ($customerIds as $id) {
			$advocatedPhotos[] = Photograph::find_customer_images($id);
		}

		$allFilenames = array();
		$dateCreated = array();
		$allCaptions = array();
		$timeArray = array();
		$customerIdsToPhotos = array();
		$photosId = array();
		foreach ($advocatedPhotos as $key => $value) {	
			foreach ($value as $obj) {
				// photograph names
				$photosId[] = $obj->id;
				$customerIdsToPhotos[] = $customerIds[$key];
				$allFilenames[] = $obj->filename;
				// date created for photographs
				$dateCreated[] = $obj->date_created;
				$timeArray[] = strtotime($obj->date_created);
				$allCaptions[] = $obj->caption;
			}		
		}
		$sortedTimeArray = arsort($timeArray);
		$keysSortedTime = array_keys($timeArray);

		// Loop through the arrays and save them in json data format that will we sent to javascript
		foreach ($timeArray as $key => $value) {
			// get the customer id 
			$customerId = $customerIdsToPhotos[$key];
			// Get the customer business name
			$customer = Customer::find_by_id($customerId);
			$businessTitle = $customer->business_title;
			// Get number of likes for photo	
			$numLikes = Photograph_Like::count_photograph_likes($photosId[$key], $customerId);
			// check if there is a value for number of likes
			if (!isset($numLikes)) {
				$numLikes = 0;
			}
			// Get the number of comments of photo
			$numComments = Photograph_Comment::count_photograph_comments($photosId[$key], $customerId);
			// check if there is a value for the comments otherwise set it to zero
			if (!isset($numComments)) {
				$numComments = 0;
			}
			// check if the image has been liked or commented by user or customer
			if ($session->is_user_logged_in()) {
				$isLiked = Photograph_Like::find_photograph_liked_by_user($photosId[$key], $public_user_id);
			} elseif ($session->is_customer_logged_in()) {
				$isLiked = Photograph_Like::find_photograph_liked_by_customer($photosId[$key], $public_customer_id);
			}
			if (!empty($isLiked)) {
				$photoLiked = "yes";
			} else {
				$photoLiked = "no";
			}

			// Use the customer id to get the avatar of the customer
			$avatar = Photograph::find_avatar($customerId);
			if (!empty($avatar)) {
				$avatarFilename = $avatar->filename;
			} else {
				$avatarFilename = "emptyImageIcon.png";
			}

			$json["success"] = true;
			$json["result"] = true;
			// concatenate the array of data to be outputed
			$json["details"][] = array(
				"customerId" => $customerId, 
				"photoId" => $photosId[$key], 
				"imageName" => $allFilenames[$key], 
				"avatar" => $avatarFilename, 
				"businessTitle" => $businessTitle, 
				"caption" => $allCaptions[$key],
				"numLikes" => $numLikes,
				"photoLiked" => $photoLiked, 
				"numComments" => $numComments,
				"dateCreated" => date_to_text($dateCreated[$key])
			);
		}	
	} else {
		$json["success"] = false;
		$json["result"] = "Not a valid post request or domain request";
	}
	echo json_encode($json);

?>