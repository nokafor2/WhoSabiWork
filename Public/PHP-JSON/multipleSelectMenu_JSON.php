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
	$post_params = allowed_post_params(['vehicleType', 'vehicleBrand', 'vehicleService', 'techServOrSparePart', 'webPage', 'state', 'artisanType', 'sellerType']);
	
	// This checks for artisanPage.php and mobileMarketPage.php
	if (isset($_POST['vehicleType']) && isset($_POST['vehicleBrand']) && isset($_POST['techServOrSparePart']) && isset($_POST['state']) && isset($_POST['webPage'])) {
		$vehicleType = $_POST['vehicleType'];
		$vehicleBrand = $_POST['vehicleBrand'];
		$techServOrSparePart = $_POST['techServOrSparePart'];
		$state = $_POST['state'];
		$webPage = $_POST['webPage'];

		$address = new Address();
		if ($webPage === 'servicePage.php') {
			$towns = $address->getAvailableTowns($vehicleType, $vehicleBrand, $techServOrSparePart, "technician", $state);
		} else {
			$towns = $address->getAvailableTowns($vehicleType, $vehicleBrand, $techServOrSparePart, "spare_part_seller", $state);
		}
		$jsonData = Array("success" => true, "towns" => $towns);

	} elseif (isset($_POST['vehicleType']) && isset($_POST['vehicleBrand']) && isset($_POST['techServOrSparePart']) && isset($_POST['webPage'])) {
		$vehicleType = $_POST['vehicleType'];
		$vehicleBrand = $_POST['vehicleBrand'];
		$techServOrSparePart = $_POST['techServOrSparePart'];
		$webPage = $_POST['webPage'];

		$address = new Address();
		if ($webPage === 'servicePage.php') {
			$states = $address->getAvailableStates($vehicleType, $vehicleBrand, $techServOrSparePart, "technician");
		} else {
			$states = $address->getAvailableStates($vehicleType, $vehicleBrand, $techServOrSparePart, "spare_part_seller");
		}
		$jsonData = Array("success" => true, "states" => $states);

	} elseif (isset($_POST['vehicleType']) && isset($_POST['vehicleBrand']) && isset($_POST['webPage'])) {
		$vehicleType = $_POST['vehicleType'];
		$vehicleBrand = $_POST['vehicleBrand'];
		$webPage = $_POST['webPage'];
		
		if ($webPage === 'servicePage.php') {
			// Get the array of the vehicle services
			$techServicesObj = new Technical_Service();
			$techServices = $techServicesObj->getAvailableTechServ($vehicleType, $vehicleBrand, "technician");

			$jsonData = Array("success" => true, "techServices" => $techServices);
		} else {
			$sparePartObj = new Spare_Part();
			$spareParts = $sparePartObj->getAvailableSpareParts($vehicleType, $vehicleBrand, "spare_part_seller");

			$jsonData = Array("success" => true, "spareParts" => $spareParts);
		}

	} elseif (isset($post_params['vehicleType'], $post_params['webPage']) && ($post_params['vehicleType'] === 'car')) {
		$vehicleType = $post_params['vehicleType'];
		$webPage = $post_params['webPage'];
		
		// Get the array of the vehicle brands
		$carBrandObj = new Car_Brand();
		if ($webPage === 'servicePage.php') {
			$carBrands = $carBrandObj->getAvailableCarBrands('technician');
		} else {
			$carBrands = $carBrandObj->getAvailableCarBrands('spare_part_seller');
		}		
		
		$jsonData = Array("success" => true, "carBrands" => $carBrands);
		
	} elseif (isset($_POST['vehicleType'], $_POST['webPage']) && ($_POST['vehicleType'] === 'bus')) {
		$vehicleType = $_POST['vehicleType'];
		$webPage = $_POST['webPage'];
		
		// Get the array of the vehicle brands
		$busBrandsObj = new Bus_Brand();
		if ($webPage === 'servicePage.php') {
			$busBrands = $busBrandsObj->getAvailableBusBrands("technician");
		} else {
			$busBrands = $busBrandsObj->getAvailableBusBrands("spare_part_seller");
		}		
		
		$jsonData = Array("success" => true, "busBrands" => $busBrands);
		
	} elseif (isset($_POST['vehicleType'], $_POST['webPage']) && ($_POST['vehicleType'] === 'truck')) {
		$vehicleType = $_POST['vehicleType'];
		$webPage = $_POST['webPage'];
		
		// Get the array of the vehicle brands
		$truckBrandsObj = new Truck_Brand();
		if ($webPage === 'servicePage.php') {
			$truckBrands = $truckBrandsObj->getAvailableTruckBrands("technician");
		} else {
			$truckBrands = $truckBrandsObj->getAvailableTruckBrands("spare_part_seller");
		}
		
		$jsonData = Array("success" => true, "truckBrands" => $truckBrands);
		
	} elseif (isset($_POST['state']) && isset($_POST['webPage'])) {
		
		$state = $_POST['state'];
		$webPage = $_POST['webPage'];
		if ($webPage === 'artisanPage.php') {
			$artisanType = $_POST['artisanType'];
		} elseif ($webPage === 'mobileMarketPage.php') {
			$sellerType = $_POST['sellerType'];
		}
		
		// Get the array of the towns
		$address = new Address();
		if ($webPage === 'artisanPage.php') {
			$towns = $address->getArtisanTowns($artisanType, $state);
		} elseif ($webPage === 'mobileMarketPage.php') {
			$towns = $address->getSellerTowns($sellerType, $state);
		}
		
		if ($towns === FALSE) {
			$towns = "Not available";
		}
		$jsonData = Array("success" => true, "towns" => $towns);
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
	} else {
		$jsonData = Array("success" => false, "result" => "Condition not met.");
	}
}
echo json_encode($jsonData);

?>