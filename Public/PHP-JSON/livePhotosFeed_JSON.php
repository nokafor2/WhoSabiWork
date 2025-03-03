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

		// Check that only allowed parameters is passed into the form
		$post_params = allowed_post_params(['limit', 'offset', 'adLimit', 'adOffset']);

		// Get the limit and offset values
		$limit = $post_params['limit'];
		$offset = $post_params['offset'];

		if ($session->is_user_logged_in()) {
			$public_user_id = $session->user_id;
		} elseif ($session->is_customer_logged_in()) {
			$public_customer_id = $session->customer_id;
		}

		// Get photograph records for the customers ids
		$displayPhotos = array();
		// Get all the photos visible in the database sorted from the most recent with a limit and offset
		$displayPhotos = Photograph::find_customer_images_by_limit($limit, $offset);

		$allFilenames = array();
		$dateCreated = array();
		$allCaptions = array();
		$timeArray = array();
		$customerIdsToPhotos = array();
		$photosId = array();
		foreach ($displayPhotos as $obj) {
			// photograph names
			$photosId[] = $obj->id;
			$customerIdsToPhotos[] = $obj->customers_id;
			$allFilenames[] = $obj->filename;
			// date created for photographs
			$dateCreated[] = $obj->date_created;
			$timeArray[] = strtotime($obj->date_created);
			$allCaptions[] = $obj->caption;
		}
		// $sortedTimeArray = arsort($timeArray);
		// $keysSortedTime = array_keys($timeArray);

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


		/********** Get customers who are actively advertising ***********/
		// Get the limit and offset values for the ad
		$adLimit = $post_params['adLimit'];
		$adOffset = $post_params['adOffset'];

		// Get the customers for paid advertisement using limit and offset
		$advertisers = Business_Category::find_cus_advertisements($adLimit, $adOffset);
		// Get customers ids of the advertisers into an array
		$customerIds = array();
		foreach ($advertisers as $record) {
			$customerIds[] = $record->customers_id;
		}

		// Get the photograph of all the customers to be displayed
		$photograph = new Photograph();
		$photograph_ids = $photograph->get_photograph_ids($customerIds);

		// Get the addresses of all the customers to be displayed
		$cusAddressObj = new Address();
		$addressesIds = array();
		$addressesIds = $cusAddressObj->find_address_id_using_customer_id($customerIds);

		$rate_customer = new Customer_Rating();
		// Run a loop to get all the details of the customers
		for($i = 0; $i < count($customerIds); $i++){
			// search for ad image
			$photoId = Photograph::find_ad_photo($customerIds[$i]);
			if (empty($photoId)) {
				// If no ad image, use the first image in the gallery
				if (isset($photograph_ids[$i])) {	
					$photoId = Photograph::find_by_id($photograph_ids[$i]);
				}
			}

			// Get the path of the customer cover photograph
			if (!empty($photoId)) { 
				$image_path = $photoId->filename; 
				unset($photoId);
			} else { 
				// Display a dummy image
				$photo = new Photograph();
				$image_path = $photo->dummyImage;
			}

			// Get the customer obj data 
			if (isset($customerIds[$i])) {
				$customer = Customer::find_by_id($customerIds[$i]);
			}

			// Get the customer addresses Obj
			if (isset($addressesIds[$i])) {
				$addressObj = Address::find_by_id($addressesIds[$i]);
			}

			// Get the customer rating data
			$rating = array(); 
			$rating = $rate_customer->get_rating($customerIds[$i]);

			// Get the business title, fullname, and phone number
			if (isset($customer)) { 
				$business_title = $customer->business_title; 
				$full_name = $customer->full_name();
				$phone_number = $customer->phone_number;
				$phone_validated = $customer->phone_validated;

				// Unset id to get a new id variable
				unset($customer);
			} else { 
				$business_title = 'Business title not yet set';
				$full_name = 'Technician name not yet set';
				$phone_number = 'Technician phone number not yet set';
			}
			
			// Get the customer's address
			if (isset($addressObj)) { 
				$address = $addressObj->full_address();
				// Unset address obj that will be used again 
				unset($addressObj); 
			} else { 
				$address = 'Address not yet set'; 
			}

			// Get the customers rating
			if ($rating['rating'] == NULL) { 
				$rateValue = 0; 
			} else { 
				$rateValue = $rating['rating']; 
			}
			$rateCustomerId = $rating['customers_id'];

			// Concatenate the array of the artisans details retrieved from the database
			$json["cusAdverts"][] = array(
				"customerId" => $customerIds[$i],
				"image_path" => $image_path, 
				"business_title" => $business_title, 
				"full_name" => $full_name, 
				"address" => $address, 
				"phone_number" => $phone_number, 
				"phone_validated" => $phone_validated, 
				"rateValue" => $rateValue, 
				"rateCustomerId" => $rateCustomerId
			);
		}		

		$json["success"] = true;
	} else {
		$json["success"] = false;
		$json["result"] = "Not a valid post request or domain request";
	}
	echo json_encode($json);

?>