<?php
require_once("../includes/initialize.php");
$message = "";

// print_r($_POST);
// echo "<br/><br/>";

$security = new Security_Functions();
if(request_is_post() && request_is_same_domain()) {
	$post_params = allowed_post_params(['first_name', 'last_name', 'gender', 'username', 'password', 'confirm_password', 'business_name', 'business_email', 'business_phone_number', 'address_line_1', 'address_line_2', 'address_line_3', 'town', 'other_town', 'state', 'business_category', 'artisans', 'sellers', 'technical_services', 'spare_parts', 'vehicle_category', 'car_brands', 'bus_brands', 'truck_brands', 'Submit']);
	// print_r($post_params);

	// Eliminate HTML tags embedded in the form inputs
	/* foreach($post_params as $param) {
		// run htmlentities check on the parameters
		if(isset($post_params[$param])) {
			// run htmlentities check on the parameters
			$post_params[$param] = h2($param);
		} 
	} */
	
	// !$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()
	if(!csrf_token_is_valid() || !csrf_token_is_recent()) {
		$message = "Sorry, form has expired. Please refresh and try again.";
		// Log in the failed attempt to log-in.

		// Used to troubleshoot
		/*
		echo "<br/> session variables are: </br/>";
		print_r($_SESSION);
		echo "<br/> post variables is: </br/>";
		print_r($_POST);		
		echo "<br/> post variables after failure: </br/>";
		print_r($_SESSION);
		*/
	} else {
	// CSRF tests passed--form was created by us recently.
		
		// $photo = new Photograph();
		if (isset($post_params['Submit'])) {
			// print_r($post_params);
			// Get the registration details for the customer.
			// Make variables for check boxes and menu list the form which will initialized in the validation class.
			$gender;
			$business_category;
			$vehicle_category;
			$car_brands;
			$bus_brands;
			$truck_brands;
			$spare_parts; // This is an array containing the parts selected, it is different from the object which is in a camel case.
			$technical_services;  // This is an array containing the services selected, it is different from the object which is in a camel case.
			$artisans;
			$sellers;
			
			// Get the registration details for the customer.
			$first_name = trim($post_params['first_name']);
			$last_name = trim($post_params['last_name']);
			$username = trim($post_params['username']);
			$password = trim($post_params['password']);
			$business_name = trim($post_params['business_name']);
			$business_email = trim($post_params['business_email']);
			$business_phone_number = trim($post_params['business_phone_number']);
			$address_line_1 = trim($post_params['address_line_1']);
			$address_line_2 = trim($post_params['address_line_2']);
			$address_line_3 = trim($post_params['address_line_3']);
			$state = trim(str_replace("_", " ", $post_params['state']));
			if (isset($post_params['town']) && $post_params['town'] != 'other') {
				// The variable is replaced in post global variable
				$town = $_POST['town'] = trim(str_replace("_", " ", $post_params['town']));
			} else {
				// The variable is included in the post global variable so it can also be checked
				$town = $_POST['town'] = trim(str_replace("_", " ", $post_params['other_town']));
			}

			// Validate the customer profile completion or edit before saving in the database.
			$validate->validate_customer_edit();
			
			if (empty($validate->errors)) {
				// $message = "There was no error, save the user.";
				
				$customer = new Customer();
				$address = new Address();
				
				$customer->first_name = $first_name;
				$customer->last_name = $last_name;
				$customer->username = $username;
				$customer->password = $customer->password_encrypt($password);
				$customer->phone_number = $business_phone_number;
				// Phone number validation is suspended for now.
				// $customer->phone_validated = TRUE;
				$customer->customer_email = $business_email;
				$customer->business_title = $business_name;
				$customer->business_page = TRUE;
				switch ($gender) {
					case 'male':
						$customer->gender = 'male';
						break;
					case 'female':
						$customer->gender = 'female';
						break;
				}
				$customer->account_status = 'active';
				$customer->date_created = $customer->current_Date_Time();
				$customer->date_edited = $customer->current_Date_Time();
				$savedCustomer = $customer->save();
				$customerID = $database->insert_id();
				
				$address->customers_id = $customerID;
				$address->address_line_1 = $address_line_1;
				$address->address_line_2 = $address_line_2;
				$address->address_line_3 = $address_line_3;
				$address->town = $town;
				$address->state = $state;
				$savedAddress = $address->save();
				// If the town was not in the drop down menu, save it into the state_town table
				if (isset($post_params['other_town'])) {
					$stateTown = new State_Town();
					$stateTown->addNewTown($state, $post_params['other_town']);
				}
				
				$vehicleCategory = new Vehicle_Category();
				switch ($vehicle_category) {
					case 'cars':
						$vehicleCategory->customers_id = $customerID;
						$vehicleCategory->car = TRUE;
						$vehicleCategory->bus = FALSE;
						$vehicleCategory->truck = FALSE;
						$savedVehicleCategory = $vehicleCategory->save();
						
						$carBrand = new Car_Brand();
						foreach ($car_brands as $type) {
							$carBrand->customers_id = $customerID;
							$carBrand->{$type} = TRUE;
							$savedCarBusTruckBrand = $carBrand->save();
						}
						break;
					case 'buses':
						$vehicleCategory->customers_id = $customerID;
						$vehicleCategory->car = FALSE;
						$vehicleCategory->bus = TRUE;
						$vehicleCategory->truck = FALSE;
						$savedVehicleCategory = $vehicleCategory->save();
						
						$busBrand = new Bus_Brand();
						foreach ($bus_brands as $type) {
							$busBrand->customers_id = $customerID;
							$busBrand->{$type} = TRUE;
							$savedCarBusTruckBrand = $busBrand->save();
						}
						break;
					case 'trucks':
						$vehicleCategory->customers_id = $customerID;
						$vehicleCategory->car = FALSE;
						$vehicleCategory->bus = FALSE;
						$vehicleCategory->truck = TRUE;
						$savedVehicleCategory = $vehicleCategory->save();
						
						$truckBrand = new Truck_Brand();
						foreach ($truck_brands as $type) {
							$truckBrand->customers_id = $customerID;
							$truckBrand->{$type} = TRUE;
							$savedCarBusTruckBrand = $truckBrand->save();
						}
						break;
				}
				
				$businessCategory = new Business_Category();
				
				switch ($business_category) {
					case 'mobile_market':
						$businessCategory->customers_id = $customerID;
						$businessCategory->seller = TRUE;
						$businessCategory->artisan = FALSE;
						$businessCategory->technician = FALSE;
						$businessCategory->spare_part_seller = FALSE;
						$savedBusinessCategory = $businessCategory->save();
						
						$seller = new Seller();
						$seller->customers_id = $customerID;
						foreach ($sellers as $type) {
							$seller->{$type} = TRUE;
						}
						$savedAccountOption = $seller->save();
						break;
					case 'artisan':
						$businessCategory->customers_id = $customerID;
						$businessCategory->artisan = TRUE;
						$businessCategory->technician = FALSE;
						$businessCategory->spare_part_seller = FALSE;
						$savedBusinessCategory = $businessCategory->save();
						
						$artisan = new Artisan();
						$artisan->customers_id = $customerID;
						foreach ($artisans as $type) {
							$artisan->{$type} = TRUE;
						}
						$savedAccountOption = $artisan->save();
						break;
					case 'technician':
						$businessCategory->customers_id = $customerID;
						$businessCategory->technician = TRUE;
						$businessCategory->artisan = FALSE;
						$businessCategory->spare_part_seller = FALSE;
						$savedBusinessCategory = $businessCategory->save();
						
						$technicalService = new Technical_Service();
						foreach ($technical_services as $type) {
							$technicalService->customers_id = $customerID;
							$technicalService->{$type} = TRUE;
							$savedAccountOption = $technicalService->save();
						}
						break;
					case 'spare_part_seller':
						$businessCategory->customers_id = $customerID;
						$businessCategory->artisan = FALSE;
						$businessCategory->technician = FALSE;
						$businessCategory->spare_part_seller = TRUE;
						$savedBusinessCategory = $businessCategory->save();
						
						$sparePart = new Spare_Part();
						foreach ($spare_parts as $type) {
							$sparePart->customers_id = $customerID;
							$sparePart->{$type} = TRUE;
							$savedAccountOption = $sparePart->save();
						}
						break;
				}
				
				if ($business_category === 'artisan' || $business_category === 'mobile_market'){				
					if(isset($savedCustomer) && isset($savedAddress) &&  isset($savedBusinessCategory) && isset($savedAccountOption)) {
						// Disable the submit button
						// echo disableCreateAccountBtn();
						// This message should be saved in the session.
						$session->message("Congratulations ".$first_name." ".$last_name.". Your business account was successfully created.");
						// $message = "Congratulations ".$first_name." ".$last_name.". Your business account was successfully created.";
						$newCustomer = Customer::find_by_id($customerID);
						// Send an email to the customer and whosabiwork.
						if (isset($business_email) && !empty($business_email)) {
							// Send mail to new customer
							composeEmail();
							// Send mail notifying whosabiwork
							composeWhoSabiWorkEmail();
						}
						// Tell session to log them in.
						$session->customer_login($newCustomer);
						// Create a log that the user has logged into the system
						log_action('Customer login', "{$newCustomer->username} logged in.");
						redirect_to('customer/customerEditPage2.php?id='.$customerID);
					} else {
						$message .= "<br/> An error occurred while saving.";
					}
				} else {
					if(isset($savedCustomer) && isset($savedAddress) &&  isset($savedVehicleCategory) &&  isset($savedBusinessCategory) &&  isset($savedCarBusTruckBrand) && isset($savedAccountOption)) {
						// Disable the submit button
						// echo disableCreateAccountBtn();
						// This message should be saved in the session.
						$session->message("Congratulations ".$first_name." ".$last_name.". Your business account was successfully created.");
						$newCustomer = Customer::find_by_id($customerID);
						// Send an email to the customer.
						if (isset($business_email) && !empty($business_email)) {
							composeEmail();
							// Send mail notifying whosabiwork
							composeWhoSabiWorkEmail();
						}
						// Tell session to log them in.
						$session->customer_login($newCustomer);
						// Create a log that the user has logged into the system
						cus_log_action('Customer login', "{$newCustomer->username} logged in.");
						admin_log_action('Register', "{$newCustomer->username} created a user account.");
						redirect_to('customer/customerEditPage2.php?id='.$customerID);
					} else {
						$message .= "<br/> An error occurred while saving.";
					}
				}
			} else {
				// $message = "There was an error during validation. ";
			} 
		} else { 
			// Form has not been submitted.
			$message = "An error occured in submitting the form. ";
		}
		// Initialize form variables again to maek data persist
		reinitializeForm();
		
		// Reset the token after used
		// destroy_csrf_token();
		// $security->destroy_csrf_tokens();
	}
	// This is a failed post login attempt, redirect to the login page again.
	// $message .= " This is a post request.";
	// Initialize form variables again
	reinitializeForm();
} else {
	// form not submitted or was GET request
	// $message = "Please login.";
	// Form has not been submitted.
	// Variables can be initialized to blank contents to be used to make data persist.
	$first_name = ""; $last_name = ""; $username = ""; $business_name = ""; $business_email = ""; $business_phone_number = ""; $address_line_1 = ""; $address_line_2 = ""; $address_line_3 = ""; $other_town = ""; $caption = ""; $password = ""; // $state = ""; 
}
// Destroy the token after expiry
destroy_csrf_token();

function reinitializeForm() {
	global $post_params; global $first_name; global $last_name; global $username;  global $business_name; global $business_email; global $business_phone_number; global $address_line_1; global $address_line_2; global $address_line_3; global $other_town; global $town; global $state;
	
	// Initialize form variables again to maek data persist
	$first_name = trim($post_params['first_name']);
	$last_name = trim($post_params['last_name']);
	$username = trim($post_params['username']);
	$business_name = trim($post_params['business_name']);
	$business_email = trim($post_params['business_email']);
	$business_phone_number = trim($post_params['business_phone_number']);
	$address_line_1 = trim($post_params['address_line_1']);
	$address_line_2 = trim($post_params['address_line_2']);
	$address_line_3 = trim($post_params['address_line_3']);
	if (isset($post_params['town'])) {
		$town = trim(str_replace("_", " ", $post_params['town']));
	} else {
		$other_town = trim($post_params['other_town']);
	}
	$state = trim(str_replace("_", " ", $post_params['state']));
	
	return true;
}

function composeEmail() {
	global $business_email; global $first_name; global $last_name; global $username; global $password;
	
	$from = 'support@whosabiwork.com';
	$sender = 'WhoSabiWork';
	// $to = $email;
	$title = 'Welcome to WhoSabiWork';
	$body = '<img src="cid:whoSabiWorkLogo"/>
	<p>Dear '.$first_name.' '.$last_name.', </p>
	
	<p>Thanks for taking the time to create a WhoSabiWork business account.</p>
	
	<p>Your username is <strong>'.$username.'</strong>, and your password is <strong>'.$password.'</strong>. Visit our website <strong><a href="https://www.whosabiwork.com/Public/loginPage.php">login page</a></strong> and try logging in to ensure that it works. If you have any problem logging in, don\'t fail to contact our service support at support@whosabiwork.com.</p>
	
	<p>WhoSabiWork is committed to providing its users an eclectic source to find small scale entrepreneurs, skilled artisans, mechanics and spare part sellers. Also, it helps its registered customers get advertised to users so that the outreach of their business will reach the global populace.</p>
	
	<p>If you have any questoin, you can contact us at our website on the <strong><a href="WhoSabiWork.com/Public/contactUs.php">contact page</a></strong>, or email us at <strong>support@whosabiwork.com</strong>. Thank you for your patronage.</p>
	
	<p>WhoSabiWork.com</p>';
	
	return sendMailFxn($from, $sender, $business_email, $title, $body);
}

function composeWhoSabiWorkEmail() {
	global $business_email; global $first_name; global $last_name; global $username;
	
	$from = 'support@whosabiwork.com';
	$sender = 'WhoSabiWork';
	$to = $from;
	$title = 'New Customer Account';
	$body = '<img src="cid:whoSabiWorkLogo"/>
	<p>A new customer account has been created by '.$first_name.' '.$last_name.' with username '.$username.' and email '.$business_email.'.</p>
	
	<p>WhoSabiWork.com</p>';
	
	return sendMailFxn($from, $sender, $to, $title, $body);
}

// This executes a javascript code that will disable the create account button when clicked
function disableCreateAccountBtn() {
	$output = "";
	$output .= "<script>
		submit.setAttribute('disabled', 'disabled');
		$('#submit').css('background-color', '#FF7664');
		$('#submit').css('color', '#FFF');
		$('#submit').css('border', 'unset');
		$('#submit').css('font-weight', 'unset');
		$('#submit').css('cursor', 'unset');
	</script>";

	return $output;
}

/* echo "<br/> Before the form page runs, these are the contents of the session and post global variable <br/>";
echo "Post gloabal variables are: <br/>";
print_r($_POST);
echo "<br/>";
echo "Session gloabal variables are: <br/>";
print_r($_SESSION); */

$_SESSION['accountFormValidate']['gender'] = 'Gender is not selected';
$_SESSION['accountFormValidate']['state'] = 'State is not selected';
$_SESSION['accountFormValidate']['town'] = 'Town is not selected';
$_SESSION['accountFormValidate']['business_category'] = 'Business category is not selected';
$_SESSION['accountFormValidate']['vehicle_category'] = 'Vehicle category is not selected';
$_SESSION['accountFormValidate']['sellers'] = 'Sellers category is not selected';
$_SESSION['accountFormValidate']['artisans'] = 'Artisan category is not selected';
$_SESSION['accountFormValidate']['technical_services'] = 'Technical services is not selected';
$_SESSION['accountFormValidate']['spare_parts'] = 'Spare part category is not selected';
$_SESSION['accountFormValidate']['car_brands'] = 'Car brands is not selected';
$_SESSION['accountFormValidate']['bus_brands'] = 'Bus brands is not selected';
$_SESSION['accountFormValidate']['truck_brands'] = 'Truck brands is not selected';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-171769876-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-171769876-1');
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Create a Business Account</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" type="image/png" href="/Public/images/WhoSabiWorkLogo.png" />

<script src="./javascripts/jquery.js" type="text/javascript"></script>
<link href="../HomePageStyles.css" rel="stylesheet" type="text/css" />
<link href="./stylesheets/createBusinessAccountStyle.css" rel="stylesheet" type="text/css" />

<!-- Font Awesome Link -->
<link href="./stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />

<link href="../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>

</head>

<body>
	<div id="topContainer">
		<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
		<?php include_layout_template('public_header.php'); ?>
	</div>
	<div id="container">
	  <!-- Begining of Main Section -->
	  <div id="mainTechEditPage">
	  	<?php echo displayMessages(); ?>
	  	<!-- All messages have been combined into displayMessages() -->
	    <h2 class="pageIntro">Create your business account</h2>
	    <form action="" method="post" enctype="multipart/form-data" name="profileEdit" id="profileEdit">
			  <?php 
				echo csrf_token_tag(); 
				/*
				echo "<br/> The generated CSRF token is: ".$_SESSION['csrf_token']." <br/>";
				echo "<br/> The generated CSRF time is: ".$_SESSION['csrf_token_time']."<br/>";
				*/
			  ?>
	      <fieldset class="technician_edit">
	        <legend>Business Information</legend>
	        <p>
	          <input name="first_name" type="text" id="first_name" size="60" maxlength="60" placeholder="First name" value="<?php echo htmlentities($first_name);?>" onblur="validateInput(this);" />
	      	</p>
		    	<p>
		        <input name="last_name" type="text" id="last_name" size="60" maxlength="60" placeholder="Last name" value="<?php echo htmlentities($last_name);?>" onblur="validateInput(this);" />
	      	</p>
	      	<p style="padding-left: 10px"><label class="genderLabel">Gender:</label>
			    <label>
	          <input name="gender" type="radio" value="male" id="male" onclick="validateInput(this);" />
			    		Male
	        </label>
	        <label>
	          <input name="gender" type="radio" value="female" id="female" onclick="validateInput(this);" />
			    	Female
	        </label>
	        <br />
	      	</p>
	      	<p>
		        <input name="username" type="text" id="username" size="60" maxlength="60" autocomplete="off" autocorrect="off" autocapitalize="none" placeholder="Username" value="<?php echo htmlentities($username);?>" onblur="usernameBussAccCheck();" />
	      	</p>

					<div id="usernameMessage" style="color:red; display:none; margin:0px;"></div>
	      	<p>
		        <input name="password" type="password" id="password" size="60" maxlength="60" placeholder="Password" autocomplete="off" autocorrect="off" autocapitalize="none" onblur="validateInput(this);" />
	      	</p>
					<p>
		        <input name="confirm_password" type="password" id="confirm_password" size="60" maxlength="60" placeholder="Confirm password" autocomplete="off" autocorrect="off" autocapitalize="none" />
	      	</p>
					<div id="passwordMessage" style="color:red; display:none; margin:0px;"></div>
	      	<p>
	        	<input name="business_name" type="text" id="business_name" size="60" maxlength="60" placeholder="Business name" value="<?php echo htmlentities($business_name);?>" onblur="validateInput(this);" />
	      	</p>
	      	<p>
	        	<input name="business_email" type="email" id="business_email" size="60" maxlength="60" autocomplete="off" autocorrect="off" autocapitalize="none" placeholder="Business email" value="<?php echo htmlentities($business_email);?>" onblur="validateInput(this);"/>
	      	</p>
	      	<p class="addressPara">
	        	<input name="business_phone_number" type="tel" id="business_phone_number" size="60" maxlength="60" placeholder="Phone number" value="<?php echo htmlentities($business_phone_number);?>" onblur="validateInput(this);"/>			
	      	</p>
	      	<p class="smallText2">Enter phone number in this format 08012345690</p>
	      	<p class='selectStyle'>Select State
						<select name='state' id='state' class='marginTop' onchange='getTowns(this.id, "town")'>
						<?php echo displayStateOptions(); ?>
						</select>
	      	</p>
	      	<p class='selectStyle'>Select Town
						<select name='town' id='town' onchange='showTownTextArea(this.id);'>
							<option value=''>Select</option>
						</select>
	      	</p>
	      	<p id='otherTown'>
	        	<input name="other_town" type="text" id="other_town" size="60" maxlength="20" placeholder="Other town" value="<?php echo htmlentities($other_town);?>" onblur="validateInput(this);" />
	      	</p>		
	      	<p class="addressPara">
	        	<input name="address_line_1" type="text" id="address_line_1" size="60" maxlength="100" placeholder="Address line 1" value="<?php echo htmlentities($address_line_1);?>" onblur="validateInput(this);" />
	      	</p>
	      	<p class="smallText2">* comma (,) already added at the end of line</p>
	      	<p class="addressPara">
	        	<input name="address_line_2" type="text" id="address_line_2" size="60" maxlength="100" placeholder="Address line 2" value="<?php echo htmlentities($address_line_2);?>" onblur="validateInput(this);" />
	      	</p>
	      	<p class="smallText2">* comma (,) already added at the end of line</p>
	      	<p class="addressPara">
	        	<input name="address_line_3" type="text" id="address_line_3" size="60" maxlength="100" placeholder="Address line 3" value="<?php echo htmlentities($address_line_3);?>" onblur="validateInput(this);" />
	      	</p>
	      	<p class="smallText2">* comma (,) already added at the end of line</p>      	
	      	<br/>
	      </fieldset> <!-- End of Main Section -->
		  	<br/>
	      <fieldset class="technician_edit">
	      	<legend>Business Description</legend>
		   		<label class="bussDescrpLabel">Business categories:</label>
	        <br/>
	        <label>
	          <input name="business_category" type="radio" value="mobile_market" id="seller_btn" onclick="validateInput(this);" />
			    	Mobile Market
	        </label>
			   	<label>
	          <input name="business_category" type="radio" value="artisan" id="artisan_btn" onclick="validateInput(this);" />
			    	Artisan
	        </label>
	        <label>
	          <input name="business_category" type="radio" value="technician" id="technician_btn" onclick="validateInput(this);" />
			    	Mechanic
	        </label>
		      <label>
	          <input name="business_category" type="radio" value="spare_part_seller" id="spare_part_btn" onclick="validateInput(this);" />
		        Spare part seller
	        </label>
	        <br />
			
				  <div id="sellerDiv" style="display:none; clear:both">
					  <p class="bussDescrpLabel" >Mobile Market:</p>
				  </div>

				  <div id="artisanDiv" style="display:none; clear:both">
					  <p class="bussDescrpLabel" >Artisans:</p>
				  </div>
					
				  <div id="technicalServiceDiv" style="display:none; clear:both">
					  <p class="bussDescrpLabel">Technical services:</p>
				  </div>
				  
				  <div id="sparePartDiv" style="display:none; clear:both">
					<p class="bussDescrpLabel">Spare part categories:</p>
				  </div>
					
				  <div id="vehCategory" style="display:none; clear:both" >
						<p class="bussDescrpLabel">Vehicle categories:</p>
					  <label>
						<input type="radio" name="vehicle_category" value="cars" id="cars_btn" onclick="validateInput(this);" />
						Cars
					  </label>
					  
					  <label>
					  <input type="radio" name="vehicle_category" value="buses" id="buses_btn" onclick="validateInput(this);" />
					  Buses</label>
						
					  <label>
					  <input type="radio" name="vehicle_category" value="trucks" id="trucks_btn" onclick="validateInput(this);" />
					  Trucks</label>
				  </div>
				  
				  <div id="carBrandsDiv" style="display:none; clear:both">
					  <p class="bussDescrpLabel" >Car specialization:</p>
				  </div>
					  
				  <div id="busBrandsDiv" style="display:none; clear:both">
					  <p class="bussDescrpLabel" >Bus specialization:</p>
				  </div>
				  
				  <div id="truckBrandsDiv" style="display:none; clear:both">
					  <p class="bussDescrpLabel" >Truck specialization:</p>
				  </div>

				  <p id="smallText1">By Registering, you agree that you've read and accepted our User <a href="/Public/termsOfUse.php" >Terms of Use</a>, you're at least 18 years old, you consent to our <a href="/Public/privacyPolicy.php" >Privacy Policy</a> and you have accepted to receive marketing communications from us.</p>
				  
			    <p id="submitBtnPara">
			      <input type="submit" name="Submit" id="submit" value="Register"/>
			      <input type="submit" name="Submit2" id="submit2" value="Register2"/>
			    </p>	  
	    	</fieldset>
	    </form>
	    <p>&nbsp;</p>
	  
	  <!-- Display the footer section -->
	  <?php 
	  include_layout_template('public_footer.php'); ?>
	  </div>
	</div>

	<!-- Loader -->
	<div class="loader">
		<img src="images/utilityImages/ajax-loader1.gif" alt="Loading..." />
	</div>

	<!-- Modal for the display of error messages -->
	<div class="messageModal">
		<div class="messageContainer">
			<div id="messageHead">Error</div>
			<div id="messageContent">Message to appear here.</div>
			<button id="closeBtn" class="btnStyle1">Close</button>
		</div>
	</div>

	<script type="text/javascript">
		var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
	</script>
	<script type="text/javascript" src="./javascripts/usernameCheck.js"></script>
	<script type="text/javascript" src="./javascripts/passwordMatchCheck.js"></script>
	<!-- <script type="text/javascript" src="./javascripts/phoneNumberVerification.js"></script> -->
	<script type="text/javascript" src="./javascripts/createBusAccJavascripts.js"></script>
	<script type="text/javascript" src="./javascripts/genericJSs.js"></script>
	<!-- </div> -->
</body>
</html>
<?php // if(isset($database)) { $database->close_connection(); } ?>