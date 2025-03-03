<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file
// An array is actually created here
$jsonData = array(
	'success' => false,
	'result' => ""
);
// print_r($_POST);

if(request_is_post() && request_is_same_domain()) {
	// Check if the request is post and if it is from same web page.	
	
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['vehicleType', 'webPage', 'state', 'artisanType', 'sellerType']);
	
	// This checks for artisanPage.php and mobileMarketPage.php
	if (isset($_POST['state'])) {
		//  && isset($_POST['webPage']) && isset($_POST['vehicleType'])
		$state = $_POST['state'];
		$webPage = $_POST['webPage'];
		if ($webPage === 'artisanPage.php') {
			$artisanType = $_POST['artisanType'];
		} elseif ($webPage === 'mobileMarketPage.php') {
			$sellerType = $_POST['sellerType'];
		} else {
			$vehicleType = $_POST['vehicleType'];
		}
		
		// Get the array of the towns
		$address = new Address();
		if ($webPage === 'artisanPage.php') {
			$towns = $address->getArtisanTowns($artisanType, $state);
		} elseif ($webPage === 'mobileMarketPage.php') {
			$towns = $address->getSellerTowns($sellerType, $state);
		} elseif ($webPage === 'servicePage.php') {
			$towns = $address->getTowns($vehicleType, "technician", $state);
		} else {
			$towns = $address->getTowns($vehicleType, "spare_part_seller", $state);
		}
		
		if ($towns === FALSE) {
			$towns = "Not available";
		}
		$jsonData = Array("success" => true, "towns" => $towns);
	} elseif (isset($post_params['vehicleType'], $post_params['webPage']) && ($post_params['vehicleType'] === 'car')) {
		$vehicleType = $post_params['vehicleType'];
		$webPage = $post_params['webPage'];
		
		// Get the array of the states
		$address = new Address();
		if ($webPage === 'servicePage.php') {
			// Get the array of the vehicle services
			$techServicesObj = new Technical_Service();
			$techServices = $techServicesObj->getTechnicalServices();
			// print_r($techServices);
			$states = $address->getStates($vehicleType, "technician");
		} else {
			$sparePartObj = new Spare_Part();
			$techServices = $sparePartObj->getSpareParts();
			$states = $address->getStates($vehicleType, "spare_part_seller");
		}
		
		// Get the array of the vehicle brands
		$carBrandsObj = new Car_Brand();
		$carBrands = $carBrandsObj->getVehicleBrands();
		
		if ($states === FALSE) {
			$states = "Not available";
		}
		
		$jsonData = Array("success" => true, "carBrands" => $carBrands, "techServices" => $techServices, "states" => $states);
		
	} elseif (isset($_POST['vehicleType'], $_POST['webPage']) && ($_POST['vehicleType'] === 'bus')) {
		$vehicleType = $_POST['vehicleType'];
		$webPage = $_POST['webPage'];
		
		// Get the array of the states
		$address = new Address();
		if ($webPage === 'servicePage.php') {
			// Get the array of the vehicle services
			$techServicesObj = new Technical_Service();
			$techServices = $techServicesObj->getTechnicalServices();
			$states = $address->getStates($vehicleType, "technician");
		} else {
			$sparePartObj = new Spare_Part();
			$techServices = $sparePartObj->getSpareParts();
			$states = $address->getStates($vehicleType, "spare_part_seller");
		}
		
		// Get the array of the vehicle brands
		$busBrandsObj = new Bus_Brand();
		$busBrands = $busBrandsObj->getBusBrands();
		
		if ($states === FALSE) {
			$states = "Not available";
		}
		
		$jsonData = Array("success" => true, "busBrands" => $busBrands, "techServices" => $techServices, "states" => $states);
		
	} elseif (isset($_POST['vehicleType'], $_POST['webPage']) && ($_POST['vehicleType'] === 'truck')) {
		$vehicleType = $_POST['vehicleType'];
		$webPage = $_POST['webPage'];
		
		// Get the array of the states
		$address = new Address();
		if ($webPage === 'servicePage.php') {
			// Get the array of the vehicle services
			$techServicesObj = new Technical_Service();
			$techServices = $techServicesObj->getTechnicalServices();
			$states = $address->getStates($vehicleType, "technician");
		} else {
			$sparePartObj = new Spare_Part();
			$techServices = $sparePartObj->getSpareParts();
			$states = $address->getStates($vehicleType, "spare_part_seller");
		}
		
		// Get the array of the vehicle brands
		$truckBrandsObj = new Truck_Brand();
		$truckBrands = $truckBrandsObj->getTruckBrands();
		
		if ($states === FALSE) {
			$states = "Not available";
		}
		
		$jsonData = Array("success" => true, "truckBrands" => $truckBrands, "techServices" => $techServices, "states" => $states);
		
	} elseif (isset($_POST['artisanType'])) {
		$artisanType = $_POST['artisanType'];
		
		// Get the array of the states
		$address = new Address();
		$states = $address->getArtisanStates($artisanType);
		if ($states === FALSE) {
			$states = "Not available";
		}
		
		$jsonData = Array("success" => true, "states" => $states);
	} elseif (isset($_POST['sellerType'])) {
		$sellerType = $_POST['sellerType'];
		
		// Get the array of the states
		$address = new Address();
		$states = $address->getSellerStates($sellerType);
		if ($states === FALSE) {
			$states = "Not available";
		}
		
		$jsonData = Array("success" => true, "states" => $states);
	}
}
echo json_encode($jsonData);

?>