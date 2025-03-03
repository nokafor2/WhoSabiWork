<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file
// An array is actually created here
$jsonData = array(
	'success' => false
);
// print_r($_POST);

if(request_is_post() && request_is_same_domain()) {
	// Check if the request is post and if it is from same web page.	
	
	// Get the array of the artisans
	$artisansObj = new Artisan();
	$artisans = $artisansObj->getArtisans();
	
	// Get the array of the artisans
	$sellersObj = new Seller();
	$sellers = $sellersObj->getSellers();

	// Get the array of the technical services
	$techServicesObj = new Technical_Service();
	$techServices = $techServicesObj->getTechnicalServices();

	// Get the array of the spare parts
	$sparePartObj = new Spare_Part();
	$spareParts = $sparePartObj->getSpareParts();

	// Get the array of the car brands
	$carBrandsObj = new Car_Brand();
	$carBrands = $carBrandsObj->getVehicleBrands();

	// Get the array of the bus brands
	$busBrandsObj = new Bus_Brand();
	$busBrands = $busBrandsObj->getBusBrands();

	// Get the array of the truck brands
	$truckBrandsObj = new Truck_Brand();
	$truckBrands = $truckBrandsObj->getTruckBrands();

	$jsonData = Array("carBrands" => $carBrands, "busBrands" => $busBrands, "truckBrands" => $truckBrands, "techServices" => $techServices, "spareParts" => $spareParts, "artisans" => $artisans, "sellers" => $sellers);
}
echo json_encode($jsonData);

?>