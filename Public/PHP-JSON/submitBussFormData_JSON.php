<?php 
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');

// This will be set to specify as a default output upon request from the JSON file

// Response sent from genericJSs.js
// Function: saveBussAccountFormData()

// An array is actually created here
$json = array(
	'success' => false
);

if(request_is_post() && request_is_same_domain()) {
	$post_params = allowed_post_params(['first_name', 'last_name', 'gender', 'username', 'password', 'confirm_password', 'business_name', 'email', 'phone_number', 'address_line_1', 'address_line_2', 'address_line_3', 'town', 'other_town', 'state', 'business_category', 'artisans', 'sellers', 'technical_services', 'spare_parts', 'vehicle_category', 'car_brands', 'bus_brands', 'truck_brands', 'Submit']);
	// print_r($post_params);

	// Eliminate HTML tags embedded in the form inputs
	/* foreach($post_params as $param) {
		// run htmlentities check on the parameters
		if(isset($post_params[$param])) {
			// run htmlentities check on the parameters
			$post_params[$param] = h2($param);
		} 
	} */
		
	if(!csrf_token_is_valid() || !csrf_token_is_recent()) {
		$json['success'] = false;
		$json["csrf_failure"] = "Sorry, form has expired. Please refresh and try again.";
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
		// CSRF tests passed--form was created by us recently 
		if (isset($post_params['first_name'], $post_params['last_name'], $post_params['gender'], $post_params['username'], $post_params['password'], $post_params['confirm_password'], $post_params['phone_number'], $post_params['email'])) {

			// Get the registration details for the customer.
			$first_name = trim($post_params['first_name']);
			$last_name = trim($post_params['last_name']);
			$gender = trim($post_params['gender']);
			$username = trim($post_params['username']);
			$password = trim($post_params['password']);
			$business_name = trim($post_params['business_name']);
			$business_email = trim($post_params['email']);
			$phone_number = trim($post_params['phone_number']);
			$address_line_1 = trim($post_params['address_line_1']);
			$address_line_2 = trim($post_params['address_line_2']);
			$address_line_3 = trim($post_params['address_line_3']);
			$state = trim(str_replace("_", " ", $post_params['state']));
			if (isset($post_params['town']) && $post_params['town'] != 'other') {
				// The variable is replaced in post global variable after removing the presence of underscore
				$town = $_POST['town'] = trim(str_replace("_", " ", $post_params['town']));
			} else {
				// The variable is included in the post global variable so it can also be checked
				$town = $_POST['town'] = trim(str_replace("_", " ", $post_params['other_town']));
			}
			$business_category = trim($post_params['business_category']);
			$vehicle_category = trim($post_params['vehicle_category']);
			if (isset($post_params['car_brands'])) {
				$car_brands = $post_params['car_brands'];
			}
			if (isset($post_params['bus_brands'])) {
				$bus_brands = $post_params['bus_brands'];
			}
			if (isset($post_params['truck_brands'])) {
				$truck_brands = $post_params['truck_brands'];
			}
			if (isset($post_params['spare_parts'])) {
				$spare_parts = $post_params['spare_parts'];
			}	
			if (isset($post_params['technical_services'])) {
				$technical_services = $post_params['technical_services']; 
			}
			if (isset($post_params['artisans'])) {
				$artisans = $post_params['artisans'];
			}
			if (isset($post_params['sellers'])) {
				$sellers = $post_params['sellers'];	
			}

			// Save data to the database
			$customer = new Customer();
			$address = new Address();
			
			$customer->first_name = ucfirst($first_name);
			$customer->last_name = ucfirst($last_name);
			$customer->gender = $gender;
			$customer->username = $username;
			$customer->password = $customer->password_encrypt($password);
			$customer->phone_number = $phone_number;
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
				// Check only if customer data table is saved because slow internet speed can present loss of data.
				if(isset($savedCustomer)) {

					$newCustomer = Customer::find_by_id($customerID);
					// Send an email to the customer and whosabiwork.
					if (isset($business_email) && !empty($business_email)) {
						// Send mail to new customer
						$json["emailOutcome"] = sendEmailOutcome2(composeEmail());
					} else {
						$json["emailOutcome"] = sendEmailOutcome2("An email was not sent since no email was provided.");
					}
					// Send mail notifying whosabiwork
					composeWhoSabiWorkEmail();
					// Tell session to log them in.
					$session->customer_login($newCustomer);
					// Create a log that the user has logged into the system
					cus_log_action('Register', "{$newCustomer->username} created a business account.");
					admin_log_action('Register', "{$newCustomer->username} created a business account.");

					$json['success'] = true;
					$json['newCustomerId'] = $customerID;
					// This message should be saved in the session.
					$session->message("<p>Congratulations ".$first_name." ".$last_name.". Your business account was successfully created.</p><p>".$json["emailOutcome"]."</p>");
				} else {
					$json['success'] = false;
					$json["savingError"] = "An error occurred while saving.";
				}
			} else {
				// Check only if customer data table is saved because slow internet speed can present loss of data.
				if(isset($savedCustomer)) {
					
					$newCustomer = Customer::find_by_id($customerID);
					// Send an email to the customer.
					if (isset($business_email) && !empty($business_email)) {
						$json["emailOutcome"] = sendEmailOutcome2(composeEmail());
					} else {
						$json["emailOutcome"] = sendEmailOutcome2("An email was not sent since no email was provided.");
					}
					// Send mail notifying whosabiwork
					composeWhoSabiWorkEmail();
					// Tell session to log them in.
					$session->customer_login($newCustomer);
					// Create a log that the user has logged into the system
					cus_log_action('Register', "{$newCustomer->username} created a business account.");
					admin_log_action('Register', "{$newCustomer->username} created a business account.");

					$json['success'] = true;
					$json['newCustomerId'] = $customerID;
					// This message should be saved in the session.
					$session->message("<p>Congratulations ".$first_name." ".$last_name.". Your business account was successfully created.</p><p>".$json["emailOutcome"]."</p>");
				} else {
					$json['success'] = false;
					$json["savingError"] = "An error occurred while saving.";
				}
			} 
		} else { 
			// Form has not been submitted.
			$json['success'] = false;
			$json['result'] = "Data input not received";
		}
	}
} else {
	$json["success"] = false;
	$json["result"] = "Not a valid post request or domain request";
}
// Destroy the csrt token and time after usage or failure
destroy_csrf_token();
echo json_encode($json);

/* This composes email for users after creating an account. */
function composeEmail() {
	global $business_email; global $first_name; global $last_name; global $username; global $password;
	
	$from = 'support@whosabiwork.com';
	$sender = 'WhoSabiWork';
	// $to = $email;
	$title = 'Welcome to WhoSabiWork';
	$body = '<img src="cid:whoSabiWorkLogo"/>
	<p>Dear '.$first_name.' '.$last_name.', </p>
	
	<p>Thanks for taking the time to create a business account with WhoSabiWork.</p>
	
	<p>Your username is <strong>'.$username.'</strong>, and your password is <strong>'.$password.'</strong>. Visit our website <strong><a href="https://www.whosabiwork.com/Public/loginPage.php?profile=customer">login page</a></strong> and try logging in to ensure that it works. If you have any problem logging in, don\'t fail to contact our service support at support@whosabiwork.com.</p>
	
	<p>WhoSabiWork is committed to providing its users an eclectic source to find small scale entrepreneurs, skilled artisans, mechanics and spare part sellers. Also, it helps its registered customers get advertised to users so that the outreach of their business will reach the global populace.</p>
	
	<p>If you have any questoin, you can contact us at our website on the <strong><a href="WhoSabiWork.com/Public/contactUs.php">contact page</a></strong>, or email us at <strong>support@whosabiwork.com</strong>. Thank you for your patronage.</p>
	
	<p>WhoSabiWork</p>';
	
	return sendMailFxn($from, $sender, $business_email, $title, $body);
}

/* This function composses email for whosabiwork */
function composeWhoSabiWorkEmail() {
	global $business_email; global $first_name; global $last_name; global $username; global $business_category;
	if ($business_email === "") {
		$business_email = "none";
	}
	
	$from = 'support@whosabiwork.com';
	$sender = 'WhoSabiWork';
	$to = $from;
	$title = 'New Customer Account';
	$body = '<img src="cid:whoSabiWorkLogo"/>
	<p>A new customer account has been created by '.$first_name.' '.$last_name.'</p>
	<p>Username: '.$username.'</p>
	<p>Email address: '.$business_email.'</p>
	<p>Business category: '.str_replace("_", " ", $business_category).'.</p>
	
	<p>WhoSabiWork</p>';
	
	return sendMailFxn($from, $sender, $to, $title, $body);
}

?>
