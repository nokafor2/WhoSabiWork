<?php 

require_once("../includes/initialize.php");

// Check if the request is get
if (request_is_get()) {
	// Check that only allowed parameters is passed into the form
	$get_params = allowed_get_params(['sparePart', 'vehicleType', 'vehicleBrand', 'state', 'town', 'page']);

	// Receive variables from $_GET Global
	$searchedSparePart = $get_params['sparePart'];
	$searchedVehicleType = $get_params['vehicleType'];
	$searchedVehicleBrand = $get_params['vehicleBrand'];
	$searchedState = $get_params['state'];
	$searchedTown = $get_params['town'];
	$searchedPage = $get_params['page'];
}

function searchByGetRequest() {
	global $searchedSparePart, $searchedVehicleType, $searchedVehicleBrand, $searchedState, $searchedTown, $searchedPage;
	$args = '"sparePartPage", "'.$searchedSparePart.'", "'.$searchedVehicleBrand.'", "'.$searchedVehicleType.'", "'.$searchedState.'", "'.$searchedTown.'", "'.$searchedPage.'"';

	$output = "";
	$output .= "
		<script type='text/javascript'>  	
			display_Technician_Ads(".$args.");
		</script>
	";

	return $output;
}

// print_r($session); 
function vehicleTypeOptions() {
	// get an array of the vehicle types
	$vehicle = new Vehicle_Category();

	// echo "The vehicle types are: "; 
	$vehicleTypes = $vehicle->getVehicleTypes();
	// print_r($vehicleTypes);
	// Sort the vehicles
	asort($vehicleTypes);
	
	$output  = "";
	$output .= "<option value=''>Select</option>";
	foreach ($vehicleTypes as $vehicleType) {
		$output .= "<option value='{$vehicleType}'>".ucfirst(str_replace("_", " ", $vehicleType))."</option>";
	}
	
	return $output;
}


?>


<?php
	$scriptName = scriptPathName();
	// Initialize the header of the webpage
	echo getUniquePageHeader($scriptName);
?>

<div id="topContainer">
	<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
	<?php include_layout_template('navigation_header.php'); ?>
</div>
<div id="container">
  <!-- Begining of Main Section  -->
  <div id="mainServicePage">
    <div class="pageIntroDiv" >
    	<h2 class="pageIntro" id="pageHeading">Welcome! Please select the spare part category you need</h2>
    </div>    
    <!-- <div class="selectionPanel pageIntroDiv adContainer1"> -->
		<div class="selectionPanel">
   	  <form id="form1" name="form1" method="post" action="">
   	  	<div id="searchBox" class="searchDiv">
	   	  	<input id="searchInput" class="searchField" type="text" name="searchBox" placeholder="Search a spare part seller" />
	   	  	<!-- oninput="searchArtisan(this.value);" -->
	   	  	<button id="searchBtn" class="searchButton btnStyle1" ><i class="fas fa-search"></i></button>
   	  	</div>
		  	<label for="vehicleType">Select a vehicle type</label>
	      <select name="vehicleType" id="vehicleType" class="selectStyle" onchange="getVehicleBrands(this.id, 'carBrand')">
          	  <?php echo vehicleTypeOptions(); ?>
        </select>
        <br class="breaker" />
		  
        <label id="carBrandLabel" for="carBrand" class="hideElement">Select vehicle brand</label>
	      <select name="carBrand" id="carBrand" class="selectStyle hideElement" onchange="getSpareParts('vehicleType', this.id, 'sparePart')" >
			  	<option value="">Select</option>
        </select>
        <br class="breaker" />
		  
			  <label id="sparePartLabel" for="sparePart" class="hideElement">Select vehicle spare part</label>
	      <select name="sparePart" id="sparePart" class="selectStyle hideElement" onchange="getStates('vehicleType', 'carBrand', this.id, 'state')" >
				  <option value="">Select</option>
        </select>
        <br class="breaker" />
			  
		    <label id="stateLabel" for="state" class="hideElement">Select state</label>
				<select id="state" class="selectStyle hideElement"  name="state" onchange="getTowns2('vehicleType', 'carBrand', 'sparePart', this.id, 'town')" >
					<option value="select">Select</option>
				</select>
				<br class="breaker" />
				
				<label id="townLabel" for="town" class="hideElement">Select town</label>
				<select id="town" class="selectStyle hideElement" name="town" onchange="showSubmitBtn();">
					<option value="select">Select</option>
				</select>
				<br class="breaker" />
		  
        <input type="button" value="Submit" name="submitBtn" id="submitBtn" class="btnStyle1 hideElement" onclick="display_Technician_Ads(pageNumber=1);"/>
        <br class="breaker" />
      </form>
    </div>
	<!-- <div id="page" style="display:none;">1</div> class="adContainer1" -->
	<div id="adContainer1"></div>
	<!-- Div for pagination -->
	<div id="pagination" style="clear:both; color:red;"></div>
	<!-- Div for error message and progress message -->
    <div id='messageDiv' style='color:red; clear:both;'></div>
  </div> <!-- End of Main Section  -->
  
    <!-- Scroll to top widget -->
	<div class="to-top-div" style="display: block;">
		<a class="to-top" href="#mainServicePage"> ^ </a>
	</div>
  
  <!-- Display the footer section -->
  <?php include_layout_template('navigation_footer.php'); ?>
  
  <!-- This is used to search for technicians when a get request is performed. It will call a javascript function -->
  <?php 
  	if (!empty($get_params['vehicleType'])) {
   		echo searchByGetRequest();
   	}
  ?>
</div>
<div class="loader">
	<img src="images/utilityImages/ajax-loader1.gif" alt="Loading..." />
</div>
	
<?php
	// Initialize the footer of the webpage
	echo getUniquePageFooter($scriptName);
?>	
  
