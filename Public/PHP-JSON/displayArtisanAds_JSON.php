<?php
require_once("../../includes/initialize.php");

// Trick the browser, to think that it is a JSON file using PHP 
// The content-type is set to application/json.
header("Content-Type: application/json");

if(request_is_post() && request_is_same_domain()) {
	// Check if the request is post and if it is from same web page.
	
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['artisanType', 'state', 'town', 'page', 'searchVal', 'searchOrigin', 'searchPage']);
	
	$jsonData = array();
	if (isset($_POST['artisanType'], $_POST['state'], $_POST['town'], $_POST['page'])) {
		
		$artisanType = $_SESSION['searchedArtisan'] = $_POST['artisanType'];
		$state = $_SESSION['searchedState'] = $_POST['state'];
		$town = $_SESSION['searchedTown'] = $_POST['town'];
		$page = $_SESSION['searchedPage'] = $_POST['page'];
		// print_r($_POST);
		
		$selectError = array();
		if (empty($artisanType)) {
			$selectError['artisanType'] = 'noSelectedArtisan';
		} else {
			// This is set so that a variable will be sent in the JSON data to the Javascript else an error will be outputted.
			$selectError['artisanType'] = 'yesSelectedArtisan';
			// echo "Artisan was selected.";
		}
		
		/* $artisanChooseError = array();
		if (empty($artisanType)) {
			$artisanChooseError['artisanType'] = 'noSelectedArtisan';
		} else {
			// This is set so that a variable will be sent in the JSON data to the Javascript else an error will be outputted.
			$artisanChooseError['artisanType'] = 'yesSelectedArtisan';
			// echo "Artisan was selected.";
		} */
		
		if ( $selectError['artisanType'] == 'yesSelectedArtisan' ) {
			// echo "There is artisan selected.";
			// 2. records per page ($per_page)
			$per_page = 9; // changed from 3
			$displayPublicImage = new Public_Ad_Display($page, $per_page);
			
			// Get the number of artisans
			$numberOfCustomers = $displayPublicImage->count_artisan_ids($artisanType, $state, $town);
			// print_r($numberOfCustomers);
			// Set up the pagination object
			$pagination = new Pagination($page, $per_page, $numberOfCustomers);
			// Get the artisans ids which is the customer ids
			list($customer_ids, $total_count) = $displayPublicImage->get_artisan_ids($artisanType, $state, $town);
			// Get the photograph of all the customers to be displayed
			$photograph_ids = $displayPublicImage->get_photograph_ids($customer_ids);
			// Get the addresses of all the customers to be displayed
			$addresses = $displayPublicImage->find_address_id_using_customer_id($customer_ids);
			
			$rate_customer = new Customer_Rating();
			
			if (count($customer_ids) < 1) {
				$jsonData = array("available" => "noAvailArtisan");
			} else {
				for($i = 0; $i < count($customer_ids); $i++){
					// search for ad image
					$photoId = Photograph::find_ad_photo($customer_ids[$i]);
					// print_r($photoAd); 
					if (empty($photoId)) {
					// If no ad image, use the first image in the gallery
						if (isset($photograph_ids[$i])) {	
							$photoId = Photograph::find_by_id($photograph_ids[$i]); 
							// print_r($photoId);
						}
					}
										
					if (isset($customer_ids[$i])) {
						$techId = Customer::find_by_id($customer_ids[$i]); // Relevant
					}
					if (isset($addresses[$i])) {
						$addressId = Address::find_by_id($addresses[$i]);
					}
					// Get the customer id which will represent the page id.
					// $rating = array(); 
					$rating = $rate_customer->get_rating($customer_ids[$i]);
					
					if (!empty($photoId)) { 
						$image_path = $photoId->filename; 
						unset($photoId);
					} else { 
						// Display a dummy image
						$photo = new Photograph();
						$image_path = $photo->dummyImage;
					}

					if (isset($techId)) { 
						$business_title = $techId->business_title; 
						$full_name = $techId->full_name();
						$phone_number = $techId->phone_number;
						$phone_validated = $techId->phone_validated;

						// Unset id to get a new id variable
						unset($techId);
					} else { 
						$business_title = 'Business title not yet set';
						$full_name = 'Technician name not yet set';
						$phone_number = 'Technician phone number not yet set';
					}
					if (isset($addressId)) { $address = $addressId->full_address(); unset($addressId); } else { $address = 'Address not yet set'; }
					if ($rating['rating'] == NULL) { $rateValue = 0; } else { $rateValue = $rating['rating']; }
					$rateCustomerId = $rating['customers_id'];
					
					$total_pages = $pagination->total_pages();
					if ($pagination->has_previous_page()) {
						$has_previous_page = 'true';
					} else {
						$has_previous_page = 'false';
					}
					
					$previous_page = $pagination->previous_page();
					if ($pagination->has_next_page()) {
						$has_next_page = 'true';
					} else {
						$has_next_page = 'false';
					}
					
					$next_page = $pagination->next_page();					
					
					/*
					// Encrypt customer id
					$encryptionObj = new Encryption();
					$startLoop = true;
					while ($startLoop) {
						$hashedId = $encryptionObj->encrypt($customer_ids[$i]);
						// encode the hashedId for url
						$encodedId = urlencode($hashedId);
						$decodedId = urldecode($encodedId);
						$unhashedId = $encryptionObj->decrypt($decodedId);
						if (is_numeric($unhashedId)) {
							$startLoop = false;
						}
					}
					*/					
					
					// Concatenate the array of the artisans details retrieved from the database
					$jsonData["details"][] = array(
						"customerId" => $customer_ids[$i],
						"image_path" => $image_path, 
						"business_title" => $business_title, 
						"full_name" => $full_name, 
						"address" => $address, 
						"phone_number" => $phone_number, 
						"phone_validated" => $phone_validated, 
						"rateValue" => $rateValue, 
						"rateCustomerId" => $rateCustomerId, 
						"total_pages" => $total_pages, 
						"has_previous_page" => $has_previous_page, 
						"previous_page" => $previous_page, 
						"has_next_page" => $has_next_page, 
						"next_page" => $next_page, 
						"page" => $page 
					);
				}
			}
		} else {
			$jsonData = array("selectErrors" => $selectError['artisanType']);
		}
	} elseif (isset($_POST['searchVal'], $_POST['searchOrigin'], $_POST['searchPage']) && $_POST['searchOrigin'] === 'button') {

		$searchVal = $_POST['searchVal'];
		$searchPage = $_POST['searchPage'];
		$customer = new Customer();
		$searchedCustomers = $customer->searchData($searchVal, $searchPage);

		if (!empty($searchedCustomers)) {
			foreach ($searchedCustomers as $key => $customerData) {
				$customer_ids[] = $customerData->id;
			}
			// Get address ids
			$addressObj = new Address();
			$addresses = $addressObj->find_address_id_by_customer_id($customer_ids);

			// Get photograph ids
			$photoObj = new Photograph();
			$photograph_ids = $photoObj->get_photograph_ids_by_customer_ids($customer_ids);

			$rate_customer = new Customer_Rating();

			if (count($customer_ids) < 1) {
				$jsonData = array("available" => "noAvailArtisan");
			} else {
				for($i = 0; $i < count($customer_ids); $i++){
					// search for ad image
					$photoId = Photograph::find_ad_photo($customer_ids[$i]); 
					if (empty($photoId)) {
					// If no ad image, use the first image in the gallery
						if (isset($photograph_ids[$i])) {	
							$photoId = Photograph::find_by_id($photograph_ids[$i]);
						}
					}
										
					if (isset($customer_ids[$i])) {
						$techId = Customer::find_by_id($customer_ids[$i]); 
					}
					if (isset($addresses[$i])) {
						$addressId = Address::find_by_id($addresses[$i]);
					}
					// Get the customer id which will represent the page id.
					// $rating = array(); 
					$rating = $rate_customer->get_rating($customer_ids[$i]);
					
					if (!empty($photoId)) { 
						$image_path = $photoId->filename; 
						unset($photoId);
					} else { 
						// Display a dummy image
						$photo = new Photograph();
						$image_path = $photo->dummyImage;
					}

					if (isset($techId)) { 
						$business_title = $techId->business_title; 
						$full_name = $techId->full_name();
						$phone_number = $techId->phone_number;
						$phone_validated = $techId->phone_validated;

						// Unset id to get a new id variable
						unset($techId);
					} else { 
						$business_title = 'Business title not yet set';
						$full_name = 'Technician name not yet set';
						$phone_number = 'Technician phone number not yet set';
					}
					if (isset($addressId)) { $address = $addressId->full_address(); unset($addressId); } else { $address = 'Address not yet set'; }
					if ($rating['rating'] == NULL) { $rateValue = 0; } else { $rateValue = $rating['rating']; }
					$rateCustomerId = $rating['customers_id'];
					
					// Concatenate the array of the artisans details retrieved from the database
					$jsonData["customersData"][] = array("customerId" => $customer_ids[$i],
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
			}
		} else {
			$jsonData["customersData"] = "No result";
		}

	} elseif (isset($_POST['searchVal'])) {

		$searchVal = $_POST['searchVal'];
		$customer = new Customer();
		$searchedCustomers = $customer->searchData($searchVal);

		if (!empty($searchedCustomers)) {
			foreach ($searchedCustomers as $key => $customerData) {
				$jsonData["customersData"][] = array("firstName" => $customerData->first_name, "lastName" => $customerData->last_name, "username" => $customerData->username);
			}
		} else {
			$jsonData["customersData"] = "No result";
		}
	}
	echo json_encode($jsonData);
}

?>