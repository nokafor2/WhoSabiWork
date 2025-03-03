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
	$post_params = allowed_post_params(['carBrand', 'technicalService', 'vehicleType', 'state', 'page', 'sparePart']);
	
	if ( (isset($_POST['carBrand'], $_POST['technicalService'], $_POST['vehicleType'], $_POST['state'], $_POST['page'])) ||                     (isset($_POST['carBrand'], $_POST['sparePart'], $_POST['vehicleType'], $_POST['state'], $_POST['page'])) ) {
		$carBrand = $_POST['carBrand'];
		if (isset($_POST['technicalService'])) {
			$technicalService = $_POST['technicalService'];
			// echo "Technical service is saved.";
		} elseif (isset($_POST['sparePart'])) {
			$sparePart = $_POST['sparePart'];
			// echo "Spare part is saved.";
		}
		$vehicleType = $_POST['vehicleType'];
		$state = $_POST['state'];
		$page = $_POST['page'];
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
		
		// $jsonData = '{';
		if ( (($selectError['carBrand'] == 'yesCarBrand') && ($selectError['technicalService'] == 'yesTechnicalService') && ($selectError['vehicleType'] == 'yesVehicleType')) ||                                                                                                (($selectError['carBrand'] == 'yesCarBrand') && ($selectError['sparePart'] == 'yesSparePart') && ($selectError['vehicleType'] == 'yesVehicleType')) ) {
			// 2. records per page ($per_page)
			$per_page = 9;
			$displayPublicImage = new Public_Ad_Display($page, $per_page);
			
			// This will run for both technical services and spare parts, what changes is where technical service and spare parts variables will be parsed in. It has to be the same position.
			if (isset($technicalService)) {
				$numberOfCustomers = $displayPublicImage->count_customer_ids($carBrand, $technicalService, $cusCategoty, $vehicleType, $state);
			} elseif (isset($sparePart)) {
				$numberOfCustomers = $displayPublicImage->count_customer_ids($carBrand, $sparePart, $cusCategoty, $vehicleType, $state);
			}
			
			$pagination = new Pagination($page, $per_page, $numberOfCustomers);
			
			// This will run for both technical services and spare parts, what changes is where technical service and spare parts variables will be parsed in. It has to be the same position.
			if (isset($technicalService)) {
				list($customer_ids, $total_count) = $displayPublicImage->get_customer_ids($carBrand, $technicalService, $cusCategoty, $vehicleType, $state);
			} elseif (isset($sparePart)) {
				list($customer_ids, $total_count) = $displayPublicImage->get_customer_ids($carBrand, $sparePart, $cusCategoty, $vehicleType, $state);
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
					$photoId;
					if (isset($photograph_ids[$i])) {
						$photoId = Photograph::find_by_id($photograph_ids[$i]); // Relevant
					}
					if (isset($customer_ids[$i])) {
						$techId = Customer::find_by_id($customer_ids[$i]); // Relevant
					}
					if (isset($addresses[$i])) {
						$addressId = Address::find_by_id($addresses[$i]);
					}
					// Get the customer id which will represent the page id.
					// $_SESSION["customer_page"] = $customer_ids[$i];
					// $rating = array(); 
					$rating = $rate_customer->get_rating($customer_ids[$i]);
					
					if (isset($photoId)) { 
						$image_path = $photoId->image_path(); unset($photoId);
					} else { 
						$image_path = ''; 
					}
					
					if (isset($techId)) { 
						$business_title = $techId->business_title; 
						$full_name = $techId->full_name();
						$phone_number = $techId->phone_number;
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
					
					// Old code before using json_encode
					// $jsonData .= '"customer'.$i.'":{"customerId":"'.urlencode($customer_ids[$i]).'", "image_path":"'.$image_path.'", "business_title":"'.$business_title.'", "full_name":"'.$full_name.'", "address":"'.$address.'", "phone_number":"'.$phone_number.'", "rateValue":"'.$rateValue.'", "rateCustomerId":"'.$rateCustomerId.'", "total_pages":"'.$total_pages.'", "has_previous_page":'.$has_previous_page.', "previous_page":"'.$previous_page.'", "has_next_page":'.$has_next_page.', "next_page":"'.$next_page.'", "page":"'.$page.'"},';
					
					// Concatenate the array of the artisans details retrieved from the database
					$jsonData[] = array("customerId" => urlencode($customer_ids[$i]), "image_path" => $image_path, "business_title" => $business_title, "full_name" => $full_name, "address" => $address, "phone_number" => $phone_number, "rateValue" => $rateValue, "rateCustomerId" => $rateCustomerId, "total_pages" => $total_pages, "has_previous_page" => $has_previous_page, "previous_page" => $previous_page, "has_next_page" => $has_next_page, "next_page" => $next_page, "page" => $page );
				}
				// $jsonData = chop($jsonData, ',');
			}
		} else {
			if (isset($technicalService)) {
				// Old code before using json_encode
				// $jsonData .= '"selectErrors": {"carBrandError":"'.$selectError['carBrand'].'", "technicalServiceError":"'.$selectError['technicalService'].'", "vehicleTypeError":"'.$selectError['vehicleType'].'"}';
				
				$jsonData = array("selectErrors" => array("carBrandError" => $selectError['carBrand'], "technicalServiceError" => $selectError['technicalService'], "vehicleTypeError" => $selectError['vehicleType']));
				
			} else {
				// Old code before using json_encode
				// $jsonData .= '"selectErrors": {"carBrandError":"'.$selectError['carBrand'].'", "sparePartError":"'.$selectError['sparePart'].'", "vehicleTypeError":"'.$selectError['vehicleType'].'"}';
				
				$jsonData = array("selectErrors" => array("carBrandError" => $selectError['carBrand'], "sparePartError" => $selectError['sparePart'], "vehicleTypeError" => $selectError['vehicleType']));
			}	
		}
		
		// $jsonData .= '}';
		// echo $jsonData;
		echo json_encode($jsonData);
	}
}


?>