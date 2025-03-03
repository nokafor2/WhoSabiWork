<?php
require_once("../../includes/initialize.php");
// This JSON/PHP is called by: displayTechnicianAds.js located in javascripts folder

// Trick the browser, to think that it is a JSON file using PHP 
// The content-type is set to application/json.
header("Content-Type: application/json");

if(request_is_post() && request_is_same_domain()) {
	// Check if the request is post and if it is from same web page.	
	
	$jsonData = array();
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['carBrand', 'technicalService', 'vehicleType', 'state', 'town', 'page', 'sparePart', 'searchVal', 'searchOrigin', 'searchPage']);
	
	if ( (isset($_POST['carBrand'], $_POST['technicalService'], $_POST['vehicleType'], $_POST['state'], $_POST['town'], $_POST['page'])) ||                     (isset($_POST['carBrand'], $_POST['sparePart'], $_POST['vehicleType'], $_POST['state'], $_POST['town'], $_POST['page'])) ) {

		$carBrand = $_SESSION['searchedVehicleBrand'] = $_POST['carBrand'];
		if (isset($_POST['technicalService'])) {
			$technicalService = $_SESSION['searchedTechServ'] = $_POST['technicalService'];
			// echo "Technical service is saved.";
		} elseif (isset($_POST['sparePart'])) {
			$sparePart = $_SESSION['searchedSparePart'] = $_POST['sparePart'];
			// echo "Spare part is saved.";
		}
		$vehicleType = $_SESSION['searchedVehicleType'] = $_POST['vehicleType'];
		$state = $_SESSION['searchedState'] = $_POST['state'];
		$town = $_SESSION['searchedTown'] = $_POST['town'];
		$page = $_SESSION['searchedPage'] = $_POST['page'];
		// print_r($_POST);
		
		if (empty($carBrand)) {
			$selectError['carBrand'] = 'noCarBrand';
		} else {
			// This is set so that a variable will be sent in the JSON data to the Javascript else an error will be outputted.
			$selectError['carBrand'] = 'yesCarBrand';
		}
		
		// Check if technical service or spare part is retrieved and saved from the post global
		if (isset($technicalService)) {
			// check if the technical service contains a variable
			if (empty($technicalService)) {
				$selectError['technicalService'] = 'noTechnicalService';
				$selectError['sparePart'] = 'yesSparePart';
			} else {
				// This is set so that a variable will be sent in the JSON data to the Javascript else an error will be outputted.
				$selectError['technicalService'] = 'yesTechnicalService';
				$selectError['sparePart'] = 'noSparePart';
			}
			// Check if the page is from the service page or spare part page and specify if its a technician or spare part seller
			$cusCategoty = 'technician';		
		} elseif (isset($sparePart)) {
			// Check if the spare part contains a variable
			if (empty($sparePart)) {
				$selectError['sparePart'] = 'noSparePart';
				$selectError['technicalService'] = 'yesTechnicalService';
			} else {
				// This is set so that a variable will be sent in the JSON data to the Javascript else an error will be outputted.
				$selectError['sparePart'] = 'yesSparePart';
				$selectError['technicalService'] = 'noTechnicalService';
			}
			// Check if the page is from the service page or spare part page and specify if its a technician or spare part seller
			$cusCategoty = 'spare_part_seller';
		}
		
		if (empty($vehicleType)) {
			$selectError['vehicleType'] = 'noVehicleType';
		} else {
			// This is set so that a variable will be sent in the JSON data to the Javascript else an error will be outputted.
			$selectError['vehicleType'] = 'yesVehicleType';
		}
		// echo "Vehicle type is: ".$selectError['carBrand']." technical service is: ".$selectError['technicalService']." vehicle brand is: ".$selectError['vehicleType'];
		// $jsonData = '{';
		if ( (($selectError['carBrand'] == 'yesCarBrand') && ($selectError['technicalService'] == 'yesTechnicalService') && ($selectError['vehicleType'] == 'yesVehicleType')) ||                                                                                                (($selectError['carBrand'] == 'yesCarBrand') && ($selectError['sparePart'] == 'yesSparePart') && ($selectError['vehicleType'] == 'yesVehicleType')) ) {
			// 2. records per page ($per_page)
			$per_page = 9;
			$displayPublicImage = new Public_Ad_Display($page, $per_page);
			
			// This will run for both technical services and spare parts, what changes is where technical service and spare parts variables will be parsed in. It has to be the same position.
			if (isset($technicalService)) {
				$numberOfCustomers = $displayPublicImage->count_customer_ids($vehicleType, $carBrand, $technicalService, $cusCategoty, $state, $town);
			} elseif (isset($sparePart)) {
				$numberOfCustomers = $displayPublicImage->count_customer_ids($vehicleType, $carBrand, $sparePart, $cusCategoty, $state, $town);
			}
			
			// This is a global object that will be used in the public_ad_display.php class
			$pagination = new Pagination($page, $per_page, $numberOfCustomers);
			
			// This will run for both technical services and spare parts, what changes is where technical service and spare parts variables will be parsed in. It has to be the same position.
			if (isset($technicalService)) {
				list($customer_ids, $total_count) = $displayPublicImage->get_customer_ids($vehicleType, $carBrand, $technicalService, $cusCategoty, $state, $town);
			} elseif (isset($sparePart)) {
				list($customer_ids, $total_count) = $displayPublicImage->get_customer_ids($vehicleType, $carBrand, $sparePart, $cusCategoty, $state, $town);
			}

			// Instantiate the photo Id array
			$photograph_ids = $displayPublicImage->get_photograph_ids($customer_ids);
			
			// Get the addresses of all the customers to be displayed
			$addresses = $displayPublicImage->find_address_id_using_customer_id($customer_ids);
			
			$rate_customer = new Customer_Rating();
			
			if (count($customer_ids) < 1) {
				// $jsonData .= '"customer":{"available":true}';
				// $jsonData .= '"available":"noTechnician"';
				$jsonData = array("available" => "noTechnician");
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
						$image_path = $photoId->filename; unset($photoId);
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

						unset($techId);
					} else { 
						$business_title = 'Business title not yet set';
						$full_name = 'Technician name not yet set';
						$phone_number = 'Technician phone_number not yet set';
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
					$jsonData["details"][] = array("customerId" => $customer_ids[$i],
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
			if (isset($technicalService)) {
				$jsonData = array("selectErrors" => array("carBrandError" => $selectError['carBrand'], "technicalServiceError" => $selectError['technicalService'], "vehicleTypeError" => $selectError['vehicleType']));
				
			} else {
				$jsonData = array("selectErrors" => array("carBrandError" => $selectError['carBrand'], "sparePartError" => $selectError['sparePart'], "vehicleTypeError" => $selectError['vehicleType']));
			}	
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
				$jsonData = array("available" => "noTechnician");
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

	}
	echo json_encode($jsonData);
}


?>