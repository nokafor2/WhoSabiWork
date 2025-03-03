<?php
require_once("../../includes/initialize.php");
$message = "";

if (isset($_POST['Submit'])) {
	// Get the registration details for the customer.
	// Make variables for check boxes and menu list the form which will initialized in the validation class.
	$business_category;
	$vehicle_category;
	$car_brands;
	$technical_services;
	
	// Get the registration details for the customer.
	$first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$business_name = trim($_POST['business_name']);
	$business_email = trim($_POST['business_email']);
	$business_phone_number = trim($_POST['business_phone_number']);
	$address_line_1 = trim($_POST['address_line_1']);
	$address_line_2 = trim($_POST['address_line_2']);
	$address_line_3 = trim($_POST['address_line_3']);
	$city = trim($_POST['city']);
	$state = trim($_POST['state']);
	$caption = trim($_POST['caption']);
		
	$photo = new Photograph();
	// The attach_file and save functions already check for errors
	$photo->attach_file($_FILES['photo_upload']);
	// $image_to_validate = $_FILES['photo_upload']['name'];
	$path = $photo->image_path();
	// $path = "..\\".$path;
	// Validate the customer profile completion or edit before saving in the database.
	$validate->validate_customer_edit();
		
	if (empty($validate->errors)) {
		$message = "There was no error, save the user.";
		
		$customer = new Customer();
		$address = new Address();
		
		$customer->first_name = $first_name;
		$customer->last_name = $last_name;
		$customer->username = $username;
		$customer->password = $password;
		$customer->phone_number = $business_phone_number;
		$customer->customer_email = $business_email;
		$customer->business_title = $business_name;
		$customer->business_page = TRUE;
		$customer->account_status = 'active';
		$customer->date_created = $customer->current_Date_Time();
		$customer->date_edited = $customer->current_Date_Time();
		$savedCustomer = $customer->save();
		$customerID = $database->insert_id();
		
		$address->customers_id = $customerID;
		$address->address_line_1 = $address_line_1;
		$address->address_line_2 = $address_line_2;
		$address->address_line_3 = $address_line_3;
		$address->city = $city;
		$address->state = $state;
		$savedAddress = $address->save();
		
		$vehicleCategory = new Vehicle_Category();
		switch ($vehicle_category) {
			case 'cars':
				$vehicleCategory->customers_id = $customerID;
				$vehicleCategory->car = TRUE;
				$vehicleCategory->bus = FALSE;
				$vehicleCategory->truck = FALSE;
				$savedVehicleCategory = $vehicleCategory->save();
				break;
			case 'buses':
				$vehicleCategory->customers_id = $customerID;
				$vehicleCategory->car = FALSE;
				$vehicleCategory->bus = TRUE;
				$vehicleCategory->truck = FALSE;
				$savedVehicleCategory = $vehicleCategory->save();
				break;
			case 'trucks':
				$vehicleCategory->customers_id = $customerID;
				$vehicleCategory->car = FALSE;
				$vehicleCategory->bus = FALSE;
				$vehicleCategory->truck = TRUE;
				$savedVehicleCategory = $vehicleCategory->save();
				break;
		}
		
		$businessCategory = new Business_Category();
		switch ($business_category) {
			case 'technician':
				$businessCategory->customers_id = $customerID;
				$businessCategory->technician = TRUE;
				$businessCategory->spare_part_seller = FALSE;
				$savedBusinessCategory = $businessCategory->save();
				break;
			case 'spare_part_seller':
				$businessCategory->customers_id = $customerID;
				$businessCategory->technician = FALSE;
				$businessCategory->spare_part_seller = TRUE;
				$savedBusinessCategory = $businessCategory->save();
				break;
		}
		
		$carBrand = new Car_Brand();
		foreach ($car_brands as $type) {
			$carBrand->customers_id = $customerID;
			$carBrand->{$type} = TRUE;
			$savedCarBrand = $carBrand->save();
		}
		
		$technicalService = new Technical_Service();
		foreach ($technical_services as $type) {
			$technicalService->customers_id = $customerID;
			$technicalService->{$type} = TRUE;
			$savedTechnicalService = $technicalService->save();
		}
		
		
		$photo->customers_id = $customerID;
		$photo->caption = $caption;
		$photo->date_created = $photo->current_Date_Time();
		$savedPhoto = $photo->save();
		if (isset($savedPhoto)) {
			// Success
			$message = "Photograph uploaded successfully.";
		} else {
			// Failure
			$message = join("<br/>", $photo->errors);
		}
		
		if(isset($savedCustomer) && isset($savedAddress) &&  isset($savedVehicleCategory) &&  isset($savedBusinessCategory) &&  isset($savedCarBrand) && isset($savedTechnicalService) && isset($savedPhoto)) {
		// if(isset($savedVehicleCategory) && isset($savedBusinessCategory)){
			// Success
			// $session->message("Photograph uploaded successfully.");
			// This message should be saved in the session.
			$message = "Customer was successfully saved in the database.";
			// redirect_to('list_photos.php');
		} else {
			$message = "An error occurred while saving.";
		}
	} else {
		$message = "There was an error during validation. ";
	} 
	
}else { // Form has not been submitted.
	$username = "";
	$password = "";
	$message = "Please log in.";
	// print_r($_POST);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>About Mechnorama</title>
<style type="text/css">
</style>
<link href="../../HomePageStyles.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<script src="../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>

</head>

<body>
<div id="container">
  <div id="header">
	<img src="../../Images/HeaderBackground.jpg" width="1000" height="100" alt="Website Logo" />
	<div id="GlobalNav">
		<?php 
            if ($session->is_user_logged_in() OR $session->is_customer_logged_in()) {
        ?>		
            <a href="#">Your Account</a> | <a href="#">Logout</a>
        <?php		
            } else {
        ?>
			<a href="#">Login</a> | <a href="#">Register</a> 
		<?php		
            }
        ?>
    </div> 
  </div>
  <div id="navigation">
    <ul id="MenuBar1" class="MenuBarHorizontal">
      <li><a href="../index.php">HOME</a></li>
      <li><a href="../about.php">ABOUT</a></li>
      <li><a class="MenuBarItemSubmenu" href="#">SERVICES</a>
        <ul>
          <li><a href="#" class="MenuBarItemSubmenu">Cars</a>
            <ul>
              <li><a href="../carsEngineServices.php">Engine</a></li>
              <li><a href="../carsAirConditioningServices.php">Air Conditioning</a></li>
              <li><a href="../carsElectricalServices.php">Electrical</a></li>
              <li><a href="../carsComputerDiagnosticServices.php">Computer Diagnostics</a></li>
              <li><a href="../carsShockAbsorberServices.php">Shocks Absorber &amp; Balloon Shocks</a></li>
              <li><a href="../carsPanelBeatingServices.php">Panel Beating</a></li>
              <li><a href="../carsBodyWorkServices.php">Body Work</a></li>
              <li><a href="../carsMechanicalServices.php">Mechanical</a></li>
              <li><a href="../carsWheelBalancingAndAlignmentServices.php">Wheel Balancing &amp; Alignment</a></li>
              <li><a href="../carsCarWashServices.php">Car Wash</a></li>
              <li><a href="../carsTowingServices.php">Towing</a></li>
              <li><a href="../buyCars.php">Buy Cars</a></li>
              <li><a href="../sellCars.php">Sell Cars</a></li>
            </ul>
          </li>
          <li><a href="#" class="MenuBarItemSubmenu">Buses</a>
            <ul>
              <li><a href="#">Engine</a></li>
              <li><a href="#">Air Conditioning</a></li>
              <li><a href="#">Electrical</a></li>
              <li><a href="#">Computer Diagnostics</a></li>
              <li><a href="#">Shocks Absorber &amp; Balloon Shocks</a></li>
              <li><a href="#">Panel Beating</a></li>
              <li><a href="#">Body Work</a></li>
              <li><a href="#">Mechanical</a></li>
              <li><a href="#">Wheel Balancing &amp; Alignment</a></li>
              <li><a href="#">Car Wash</a></li>
              <li><a href="#">Towing</a></li>
              <li><a href="#">Buy Cars</a></li>
              <li><a href="#">Sell Cars</a></li>
            </ul>
          </li>
          <li><a href="#" class="MenuBarItemSubmenu">Trucks</a>
            <ul>
              <li><a href="#">Engine</a></li>
              <li><a href="#">Air Conditioning</a></li>
              <li><a href="#">Electrical</a></li>
              <li><a href="#">Computer Diagnostics</a></li>
              <li><a href="#">Shocks Absorber &amp; Balloon Shocks</a></li>
              <li><a href="#">Panel Beating</a></li>
              <li><a href="#">Body Work</a></li>
              <li><a href="#">Mechanical</a></li>
              <li><a href="#">Wheel Balancing &amp; Alignment</a></li>
              <li><a href="#">Car Wash</a></li>
              <li><a href="#">Towing</a></li>
              <li><a href="#">Buy Cars</a></li>
              <li><a href="#">Sell Cars</a></li>
            </ul>
          </li>
</ul>
      </li>
<li><a href="#" class="MenuBarItemSubmenu">SPARE PARTS</a>
        <ul>
          <li><a href="#">Engine Parts</a></li>
          <li><a href="#">Tires</a></li>
          <li><a href="#">Mirrors &amp; Doors</a></li>
          <li><a href="#">Battery</a></li>
          <li><a href="#">Windscreen</a></li>
          <li><a href="#">Headlamps &amp; Rear Lights</a></li>
          <li><a href="#">Upholstery</a></li>
          <li><a href="#">Steering</a></li>
          <li><a href="#">Fluids</a></li>
          <li><a href="../../bodyPartsPage.html">Body Parts</a></li>
          <li><a href="../../mechanicalPartsPage.html">Mechanical Parts</a></li>
          <li><a href="#">Electrical Parts</a></li>
          <li><a href="#">Dashboards</a></li>
        </ul>
      </li>
      <li><a href="../contactUs.php">CONTACTS</a></li></ul>
  </div>
 
  <div id="mainTechEditPage">
	<?php echo $message; ?><br/>
	<?php echo $validate->form_errors($validate->errors); ?>
    <h2>Edit your profile</h2>
    <form action="" method="post" enctype="multipart/form-data" name="profileEdit" id="profileEdit">
      <fieldset class="technician_edit">
        <legend>Business Information</legend>
        <p>
        	<label for="first_name">first name</label>
            <input name="first_name" type="text" id="first_name" size="60" maxlength="60" />
      	</p>
	    <p>
        	<label for="last_name">last name</label>
	        <input name="last_name" type="text" id="last_name" size="60" maxlength="60" />
      	</p>
      	<p>
      		<label for="username">Username</label>
	        <input name="username" type="text" id="username" size="60" maxlength="60" />
      	</p>
      	<p>
      		<label for="password">Password</label>
	        <input name="password" type="password" id="password" size="60" maxlength="60" />
      	</p>
      	<p>
      		<label for="business_name">Business name</label>
        	<input name="business_name" type="text" id="business_name" size="60" maxlength="60" />
      	</p>
      	<p>
      		<label for="business_email">Business email</label>
        	<input name="business_email" type="text" id="business_email" size="60" maxlength="60" />
      	</p>
      	<p>
      		<label for="business_phone_number">Business phone number</label>
        	<input name="business_phone_number" type="text" id="business_phone_number" size="60" maxlength="60" />
      	</p>
      	<p>
        	<label for="address_line_1">Address line 1</label>
        	<input name="address_line_1" type="text" id="address_line_1" size="60" maxlength="100" />
      	</p>
      	<p>
      		<label for="address_line_2">Address line 2</label>
        	<input name="address_line_2" type="text" id="address_line_2" size="60" maxlength="100" />
      	</p>
      	<p>
        	<label for="address_line_3">Address line 3</label>
        	<input name="address_line_3" type="text" id="address_line_3" size="60" maxlength="100" />
      	</p>
      	<p>
            <label for="city">City</label>
        	<input name="city" type="text" id="city" size="60" maxlength="20" />
      	</p>
      	<p>
        	<label for="state">State</label>
        	<input name="state" type="text" id="state" size="60" maxlength="20" />
      	</p>
      	<br/>
      </fieldset>
	  <br/>
      <fieldset>
      	<legend>Business Description</legend>
	   	<p><label class="bussDescrpLabel">Business categories:</label>
        <br/>
           <label>
          	<input name="business_category" type="radio" value="technician" id="BusinessCategory_0" />
		    Technician
          </label>
          <!-- <br /> -->
	      <label>
          	<input name="business_category" type="radio" value="spare_part_seller" id="BusinessCategory_1" />
	        Spare part seller
          </label>
        <br />
      	</p>
		<p><label class="bussDescrpLabel">Vehicle categories:</label>
        <br/>
          <label>
            <input type="radio" name="vehicle_category" value="cars" id="vehicleCategory_0" />
            Cars
          </label>
          <!-- <br /> -->
        <label>
          <input type="radio" name="vehicle_category" value="buses" id="vehicleCategory_1" />
          Buses</label>
        <!-- <br /> -->
        <label>
          <input type="radio" name="vehicle_category" value="trucks" id="vehicleCategory_2" />
          Trucks</label>
        <br />
      </p>
      <p><label class="bussDescrpLabel">Cars specialization:</label>
      <br />
        <label>
          <input type="checkbox" name="car_brands[]" value="toyota" id="car_brands_0" />
          Toyota</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="honda" id="car_brands_1" />
          Honda</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="nissan" id="car_brands_2" />
          Nissan</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="mazda" id="car_brands_3" />
          Mazda</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="mitsubishi" id="car_brands_4" />
          Mitsubishi</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="suzuki" id="car_brands_5" />
          Suzuki</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="subaru" id="car_brands_6" />
          Subaru</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="scion" id="car_brands_7" />
          Scion</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="kia" id="car_brands_8" />
          Kia</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="hyundai" id="car_brands_9" />
          Hyundai</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="acura" id="car_brands_10" />
          Acura</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="infinity" id="car_brands_11" />
          Infinity</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="lexus" id="car_brands_12" />
          Lexus</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="mercedes_benz" id="car_brands_13" />
          Mercedes Benz</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="BMW" id="car_brands_14" />
          BMW</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="volkswagen" id="car_brands_15" />
          Volkswagen</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="audi" id="car_brands_16" />
          Audi</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="ford" id="car_brands_17" />
          Ford</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="chrystler" id="car_brands_18" />
          Chrystler</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="chevrolet" id="car_brands_19" />
          Chevrolet</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="GMC" id="car_brands_20" />
          GMC</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="peugout" id="car_brands_21" />
          Peugout</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="renault" id="car_brands_22" />
          Renault</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="innoson" id="car_brands_23" />
          Innoson</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="volvo" id="car_brands_24" />
          Volvo</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="hummer" id="car_brands_25" />
          Hummer</label>
        <label>
          <input type="checkbox" name="car_brands[]" value="range_rover" id="car_brands_26" />
          Range Rover</label>
        <br />
      </p>
	  <!--
      <p>
        <select name="car_brands[]" size="5" multiple="multiple" id="car_brands">
          <option value="toyota">Toyota</option>
          <option value="honda">Honda</option>
          <option value="nissan">Nissan</option>
          <option value="mazda">Mazda</option>
          <option value="mitsubishi">Mitsubishi</option>
          <option value="suzuki">Suzuki</option>
          <option value="subaru">Subaru</option>
          <option value="scion">Scion</option>
          <option value="kia">Kia</option>
          <option value="hyundai">Hyundai</option>
          <option value="acura">Acura</option>
          <option value="infinity">Infinity</option>
          <option value="lexus">Lexus</option>
          <option value="mercedes_benz">Mercedes Benz</option>
          <option value="BMW">BMW</option>
          <option value="volkswagen">Volkswagen</option>
          <option value="audi">Audi</option>
          <option value="ford">Ford</option>
          <option value="chrystler">Chrystler</option>
          <option value="chevrolet">Chevrolet</option>
          <option value="GMC">GMC</option>
          <option value="peugout">Peugout</option>
          <option value="renault">Renault</option>
          <option value="volvo">Volvo</option>
          <option value="innoson">Innoson</option>
        </select>
      </p>
      -->
      <p><label class="bussDescrpLabel">Technical services:</label>
      <br/>
      <label>
        <input type="checkbox" name="technical_services[]" value="engine_service" id="technical_services_0" />
        Engine service<label>
      <label>
        <input type="checkbox" name="technical_services[]" value="electrical_service" id="technical_services_1" />
        Electrical service</label>
      <label>
        <input type="checkbox" name="technical_services[]" value="mechanical_service" id="technical_services_2" />
        Mechanical service</label>
      <label>
        <input type="checkbox" name="technical_services[]" value="air_conditioning_service" id="technical_services_3" />
        Air conditioning service</label>
      <label>
        <input type="checkbox" name="technical_services[]" value="panel_beating_service" id="technical_services_4" />
        Panel beating service</label>
      <label>
        <input type="checkbox" name="technical_services[]" value="computer_diagnostics_service" id="technical_services_5" />
        Computer diagnostics service</label>
      <label>
        <input type="checkbox" name="technical_services[]" value="body_work_service" id="technical_services_6" />
        Body work service</label>
      <label>
        <input type="checkbox" name="technical_services[]" value="shock_absorber_service" id="technical_services_7" />
        Shock absorber service</label>
      <label>
        <input type="checkbox" name="technical_services[]" value="ballon_shocks_service" id="technical_services_8" />
        Ballon shocks service</label>
      <label>
        <input type="checkbox" name="technical_services[]" value="wheel_balancing_and_allignment_service" id="technical_services_9" />
        wheel balancing and allignment service</label>
      <label>
        <input type="checkbox" name="technical_services[]" value="car_wash_service" id="technical_services_10" />
        Car wash service</label>
      <label>
        <input type="checkbox" name="technical_services[]" value="towing_service" id="technical_services_11" />
        Towing service</label>
      <label>
        <input type="checkbox" name="technical_services[]" value="buy_cars" id="technical_services_12" />
        Buy cars</label>
      <label>
        <input type="checkbox" name="technical_services[]" value="sell_cars" id="technical_services_13" />
        Sell cars</label>
<!--
        <select name="technical_services[]" size="5" multiple="multiple" id="technical_services">
          <option value="engine_service">Engine service</option>
          <option value="electrical_service">Electrical service</option>
          <option value="mechanical_service">Mechanical service</option>
          <option value="air_conditioning_service">Air conditioning service</option>
          <option value="panel_beating_service">Panel beating service</option>
          <option value="computer_diagnostics_service">Computer diagnostics service</option>
          <option value="body_work_service">Body work service</option>
          <option value="shock_absorber_and_ballon_shocks_service">Shock absorber and ballon shock service</option>
          <option value="wheel_balancing_and_allignment_service">Wheel balancing and alignment service</option>
          <option value="car_wash_service">Car wash service</option>
          <option value="towing_service">Towing service</option>
          <option value="buy_cars">Buy cars</option>
          <option value="sell_cars">Sell cars</option>
        </select>
        -->
        <br />
      </p>
      <p>
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php $photo->max_file_size; ?>" />
        <label class="bussDescrpLabel">
		Upload your pictures:
          <input name="photo_upload" type="file" id="photo_upload" size="30" maxlength="30" />
        </label>
		<br/>
		<p class="bussDescrpLabel">Caption: <input type="text" name="caption" value="" /></p>
      </p>
      <p>
        <input type="submit" name="Submit" id="Submit" value="Submit" />
      </p>

      </fieldset>
    </form>
    <p>&nbsp;</p>
  </div>
  
  <div class="footerStyle" id="footer">
      <div style="text-align: center; border-top: 2px solid #999; margin-top: 1em;">
      <p><a href="#">Lorum</a> • <a href="#">Ipsum</a> • <a href="#">Dolar</a> • <a href="#">Sic Amet</a> • <a href="#">Consectetur</a></p>
      <p style="font-size: 0.6em;"> Copyright <?php echo date("Y", time()); ?>, Nna-ayua Okafor </p>
       </div>
  </div>
</div>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
</script>
</body>
</html>
