<?php 
require_once("../includes/initialize.php");

function vehicleTypeOptions() {
	// get an array of the vehicle types
	$vehicle = new Vehicle_Category();

	echo "The vehicle types are: "; 
	$vehicleTypes = $vehicle->getVehicleTypes();
	print_r($vehicleTypes);
	
	$output  = "";
	$output .= "<option value='select'>Select</option>";
	foreach ($vehicleTypes as $vehicleType) {
		$output .= "<option value='{$vehicleType}'>".ucfirst(str_replace("_", " ", $vehicleType))."</option>";
	}
	
	return $output;
}

function vehicleTechServices() {
	// get an array of the vehicle types
	$techServicesObj = new Technical_Service();

	echo "The technical service types are: "; 
	$techServices = $techServicesObj->getTechnicalServices();
	print_r($techServices);
	
	$output  = "";
	$output .= "<option value='select'>Select</option>";
	foreach ($techServices as $techService) {
		$output .= "<option value='{$techService}'>".ucfirst(str_replace("_", " ", $techService))."</option>";
	}
	
	return $output;
}

// echo vehicleTechServices();
?>


<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
</script>
<script src="./javascripts/jRating/jquery/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="./javascripts/multipleSelectMenu.js"></script>
</head>
<body>
<h2>Choose Your Car</h2>
Select vehicle type:
<select id="vehicleTypes" name="vehicleTypes" onchange="populate(this.id, 'vehicleBrand', 'vehicleService', 'state')">
	<?php echo vehicleTypeOptions(); ?>
</select>
Select vehicle brand:
<select id="vehicleBrand" name="vehicleBrand">
	<option value="select">Select</option>
</select>
Select vehicle service:
<select id="vehicleService" name="vehicleService">
	<option value="select">Select</option>
</select>
Select state:
<select id="state" name="state">
	<option value="select">Select</option>
</select>
<hr/>
</body>
</html>