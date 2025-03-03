<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file

// Data sent from: customerEditPage2JSScripts.js
// Function: $('#submit_first_name') etc

// An array is actually created here
$jsonData = array(
	'success' => false
);
// print_r($_POST);

$security = new Security_Functions();
// Check if the request is post and if it is from same web page.
if(request_is_post() && request_is_same_domain()) {
	global $database;
	global $validate;
	global $session;	

	if(!$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()) {
		$jsonData["csrfFailed"] =  "Sorry, request was not valid. Session is expired.";
	} else {
		// CSRF tests passed--form was created by us recently.
		// Check that only allowed parameters is passed into the form
		$post_params = allowed_post_params(["inputField", "first_name", "gender", "last_name", "username", "password", "confirm_password", "business_name", "business_description", "business_email", "phone_number", "address_line_1", "address_line_2", "address_line_3", "town",  "edit_town", "state", "car_brands", "bus_brands", "truck_brands","artisans", "sellers", "technical_services", "spare_parts", "verifyNumber", "submit_smsToken", "smsToken"]);

		// Eliminate HTML tags embedded in the URL inputs
		foreach($post_params as $param) {
			// run htmlentities check on the parameters
			// First check if the variable is an array
			if (is_array($param) && isset($param)) {
				foreach ($param as $value) {
					// run htmlentities check on the parameters
					$params[$value] = h2($value);
				}
			} elseif (isset($param)) {
				// run htmlentities check on the parameters
				$post_params[$param] = h2($param);
			} 
		}
		
		// Get the customerId			
		$customerId = $session->customer_id;
		$customer = Customer::find_by_id($customerId);
		$address = Address::find_by_customerId($customerId);
		
		if ($post_params['inputField'] === 'firstName') {
			// Check if it is customer logged in
			if ($session->is_customer_logged_in()) {
				$first_name = trim($_POST['first_name']);
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$customer->first_name = ucfirst($first_name);
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();					
					
					if(isset($savedCustomer)) {
						// Update session
						$session->customer_full_name = $_SESSION['customer_full_name'] = $customer->full_name();
						$jsonData["success"] = true;
						$jsonData["fullName"] = $customer->full_name();
						$jsonData["inputFieldUpdated"] = "First name was successfully updated.";						
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}			
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}			
		} elseif ($post_params['inputField'] === 'lastName') {
			// Check if it is customer logged in
			if ($session->is_customer_logged_in()) {
				$last_name = trim($_POST['last_name']);
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$customer->last_name = ucfirst($last_name);
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();					
					
					if(isset($savedCustomer)) {
						// Update session
						$session->customer_full_name = $_SESSION['customer_full_name'] = $customer->full_name();					
						$jsonData["success"] = true;
						$jsonData["fullName"] = $customer->full_name();
						$jsonData["inputFieldUpdated"] = "Last name was successfully updated.";						
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}			
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'gender') {
			// Check if it is customer logged in
			if ($session->is_customer_logged_in()) {
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {					
					$gender = trim($_POST['gender']);
					$customer->gender = $gender;
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();
					
					if(isset($savedCustomer)) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Gender was successfully updated.";						
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}			
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}			
		} elseif ($post_params['inputField'] === 'username') {
			// Check if it is customer logged in
			if ($session->is_customer_logged_in()) {
				$username = trim($_POST['username']);
				// check if the username exists
				$userFound = User::find_by_username($username);
				$customerFound = Customer::find_by_username($username);
				
				if (isset($userFound->username) || isset($customerFound->username)) {
					$validate->errors["username_exists"] = "Username already exists.";
				}
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$customer->username = $username;
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();

					// Update session
					$session->customer_username = $_SESSION['customer_username'] = $username;
					
					if(isset($savedCustomer)) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Username was successfully updated.";						
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}			
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'password') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				$password = trim($_POST['password']);
				$confirm_password = trim($_POST['confirm_password']);

				$validate->validate_form_submission();
				if ($password !== $confirm_password) {
					$validate->errors["password_match_error"] = "The passwords does not match.";
				}
				if (empty($validate->errors)) {
					// $customer = new Customer();
					$customer->password = $customer->password_encrypt($password);
					$customer->date_edited = $customer->current_Date_Time();
					$savedPassword = $customer->update();
					
					if(isset($savedPassword)) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Password was successfully updated.";						
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}				
		} elseif ($post_params['inputField'] === 'business_name') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				$business_name = trim($_POST['business_name']);
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$customer->business_title = $business_name;
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();
					
					if(isset($savedCustomer)) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Business title was successfully updated.";	
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}		
		} elseif ($post_params['inputField'] === 'business_description') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				$business_description = trim($_POST['business_description']);
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$businessCategory = Business_Category::find_by_customerId($customerId);
					$businessCategory->business_description = $business_description;
					
					if ($businessCategory->update()) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "The business description was successfully updated.";	
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'business_email') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				$business_email = trim($_POST['business_email']);
				
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$customer->customer_email = $business_email;
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();
					
					if ($savedCustomer) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Email was successfully updated.";	
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'phone_number') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				$phone_number = trim($_POST['phone_number']);
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$customer->phone_number = $phone_number;
					// Unset phone validated if it is updated
					$customer->phone_validated = 0;
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();
					
					if ($savedCustomer) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Phone number was successfully updated.";	
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'verify_number') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				$phone_validated = $customer->phone_validated;
				
				$phone_number = $customer->phone_number;
				$smsCode = randStrGen(6);
				$customer->reset_token = $smsCode;
				$customer->date_edited = current_Date_Time();
					
				if ($customer->update()) {
					$smsMessage = 'Your phone number has been confirmed, please use this code '.$smsCode.' to complete your phone number validation.';
					
					if (sendSMSCode($phone_number, $smsMessage)) {
						$jsonData["success"] = true;
						$jsonData["tokenSent"] = "A validation token has been sent to your phone. Enter it below to validate your number.";
					} else {
						$jsonData["tokenNotSent"] = "An error occured sending validation token. Please try again later.";
					}
				} else {
					$jsonData["success"] = false;
					$jsonData["saveError"] = "An error occurred while saving.";
				}				
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'submit_smsToken') {
			// Check if the token is still valid.
			$currentTime = time();
			$tokenDbTime = $customer->date_edited;
			$tokenDbTimeInSec = strtotime($tokenDbTime);
			$timeCheck = 60 * 5; // 5 minutes time validity for token.
			$timeElapsed = $currentTime - $tokenDbTimeInSec;
			if (($timeElapsed) < $timeCheck) {
				$smsToken = trim($_POST['smsToken']);
				
				$savedToken = $customer->reset_token;
				if ($smsToken === $savedToken) {
					$customer->phone_validated = true;
					$phone_validated = true;
					$customer->reset_token = "";
					$customer->date_edited = current_Date_Time();
					$customer->update();
					$jsonData["success"] = true;
					$jsonData["phoneNumValidated"] = "Your phone number has been successfully validated.";
				} else {					
					$customer->phone_validated = false;
					$phone_validated = false;
					$customer->reset_token = "";
					$customer->date_edited = current_Date_Time();
					$customer->update();
					$jsonData["success"] = false;
					$jsonData["phoneNumNotValidated"] = "The verification token entered wasn't a match. <br/><br/> Verify your phone number again.";
				}
			} else {
				$customer->phone_validated = false;
				$phone_validated = false;
				$customer->reset_token = "";
				$customer->date_edited = current_Date_Time();
				$customer->update();
				$jsonData["success"] = false;
				$jsonData["phoneNumNotValidated"] = "The Token has expired, please try again.";
			}
		} elseif ($post_params['inputField'] === 'address_line_1') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				$address_line_1 = trim($_POST['address_line_1']);
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$address->address_line_1 = $address_line_1;
					$savedAddress = $address->update();
					
					if ($savedAddress) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Address line 1 was successfully updated.";	
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'address_line_2') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				$address_line_2 = trim($_POST['address_line_2']);
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$address->address_line_2 = $address_line_2;
					$savedAddress = $address->update();
					
					if ($savedAddress) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Address line 2 was successfully updated.";	
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'address_line_3') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				$address_line_3 = trim($_POST['address_line_3']);
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$address->address_line_3 = $address_line_3;
					$savedAddress = $address->update();
					
					if ($savedAddress) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Address line 3 was successfully updated.";	
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'state') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				$state = trim(str_replace("_", " ", $_POST['state']));
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$address->state = $state;
					$savedAddress = $address->update();
					
					if ($savedAddress) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "State was successfully updated.";
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'town') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				// Check if it is an update of town action or a new town is to be submitted action that is going to be performed
				if (isset($_POST['town']) && ($_POST['town'] !== 'other')) {
					$town = $_POST['town'] = trim(str_replace("_", " ", $_POST['town']));
					
					// Validate the town input
					$validate->validate_name_update();
				} elseif (isset($_POST['edit_town'])) {
					// Replace town with other town in the post global variable
					$town = $_POST['town'] = trim(str_replace("_", " ", $_POST['edit_town']));

					// Validate the town input
					$validate->validate_edit_town();
				}
				
				// $validate->validate_form_submission();
				if (empty($validate->errors)) {
					// update town record for the customer
					$address->town = $town;
					$savedAddress = $address->update();

					// If the town was not in the drop down menu, save it into the state_town table
					if (isset($_POST['edit_town'])) {
						$stateTown = new State_Town();
						$stateTown->addNewTown($address->state, $town);
					}
										
					if ($savedAddress) {
						$jsonData["success"] = true;
						$jsonData["updatedTown"] = $town;
						$jsonData["inputFieldUpdated"] = "Town was successfully updated.";
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'vehicle_category') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				// $validate->validate_name_update();
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					$vehicle_category = $_POST['vehicle_category'];
					$vehicleCategory = new Vehicle_Category();
					switch ($vehicle_category) {
						case 'cars':
							$vehicleCategory->customers_id = $customerId;
							$vehicleCategory->car = TRUE;
							$vehicleCategory->bus = FALSE;
							$vehicleCategory->truck = FALSE;
							$savedVehicleCategory = $vehicleCategory->updateColumn('car', 'bus', 'truck');
							break;
						case 'buses':
							$vehicleCategory->customers_id = $customerId;
							$vehicleCategory->car = FALSE;
							$vehicleCategory->bus = TRUE;
							$vehicleCategory->truck = FALSE;
							$savedVehicleCategory = $vehicleCategory->updateColumn('car', 'bus', 'truck');
							break;
						case 'trucks':
							$vehicleCategory->customers_id = $customerId;
							$vehicleCategory->car = FALSE;
							$vehicleCategory->bus = FALSE;
							$vehicleCategory->truck = TRUE;
							$savedVehicleCategory = $vehicleCategory->updateColumn('car', 'bus', 'truck');
							break;
					}
										
					if (isset($savedVehicleCategory)) {
						$jsonData["success"] = true;
						$jsonData["inputFieldUpdated"] = "Your vehicle category was successfully updated.";
						// Clear the vehicle brands saved in the database if the vehicle type is changed
						// Get the new checkboxes to be replaced
						$jsonData["checkboxes"] = displayVehCheckBoxes($vehicle_category);
					} else {
						$jsonData["success"] = false;
						$jsonData["saveError"] = "An error occurred while saving.";
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'vehicle_brands') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				
				$validate->validate_form_submission();
				if (empty($validate->errors)) {
					if (isset($_POST['car_brands'])) {
						$car_brands_sltd = $_POST['car_brands'];
						$carBrandObj = new Car_Brand();
						$carBrand = Car_Brand::find_by_customerId($customerId);
					
						// First initialize all the values to false before you set the selected cars to true
						// Get all the car brands
						$car_types = $carBrandObj->getVehicleBrands();
						foreach ($car_types as $type => $value) {
							$carBrand->{$type} = FALSE;
						}
						
						// save the selected choices
						foreach ($car_brands_sltd as $type) {
							$carBrand->{$type} = TRUE;
						}

						if (isset($carBrand->id)) {
							// If there is a carBrandsId update otherwise create a new record						
							$savedCarBrand = $carBrand->update();
						} else {
							$carBrand->customers_id = $customerId;
							$savedCarBrand = $carBrand->create();
						}
						
						if($savedCarBrand) {
							$jsonData["success"] = true;
							$jsonData["inputFieldUpdated"] = "Your car brands were successfully updated.";
						} else {
							$jsonData["success"] = false;
							$jsonData["saveError"] = "An error occurred while saving.";
						}
					} elseif (isset($_POST['bus_brands'])) {
						$bus_brands_sltd = $_POST['bus_brands'];
						$busBrandObj = new Bus_Brand();
						$busBrand = Bus_Brand::find_by_customerId($customerId);
						
						// First initialize all the values to false before you set the selected buses to true
						// Get all the bus brands
						$bus_types = $busBrandObj->getVehicleBrands();
						foreach ($bus_types as $type => $value) {
							$busBrand->{$type} = FALSE;
						}
						
						// save the selected choices
						foreach ($bus_brands_sltd as $type) {
							$busBrand->{$type} = TRUE;
						}

						if (isset($busBrand->id)) {
							// If there is a carBrandsId update otherwise create a new record						
							$savedBusBrand = $busBrand->update();
						} else {
							$busBrand->customers_id = $customerId;
							$savedBusBrand = $busBrand->create();
						}
						
						if($savedBusBrand) {
							$jsonData["success"] = true;
							$jsonData["inputFieldUpdated"] = "Your bus brands were successfully updated.";
						} else {
							$jsonData["success"] = false;
							$jsonData["saveError"] = "An error occurred while saving.";
						}
					} elseif (isset($_POST['truck_brands'])) {
						$truck_brands_sltd = $_POST['truck_brands'];
						$truckBrandObj = new Truck_Brand();
						$truckBrand = Truck_Brand::find_by_customerId($customerId);
					
						// First initialize all the values to false before you set the selected trucks to true
						// Get all the truck brands
						$truck_types = $truckBrandObj->getVehicleBrands();
						foreach ($truck_types as $type => $value) {
							$truckBrand->{$type} = FALSE;
						}
						
						// save the selected choices
						foreach ($truck_brands_sltd as $type) {
							$truckBrand->{$type} = TRUE;
						}

						if (isset($truckBrand->id)) {
							// If there is a carBrandsId update otherwise create a new record						
							$savedTruckBrand = $truckBrand->update();
						} else {
							$truckBrand->customers_id = $customerId;
							$savedTruckBrand = $truckBrand->create();
						}

						if($savedTruckBrand) {
							$jsonData["success"] = true;
							$jsonData["inputFieldUpdated"] = "Your truck brands were successfully updated.";
						} else {
							$jsonData["success"] = false;
							$jsonData["saveError"] = "An error occurred while saving.";
						}
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'business_services') {
			// Check if customer is logged in
			if ($session->is_customer_logged_in()) {
				// $validate->validate_name_update();
				$validate->validate_form_submission();

				if (empty($validate->errors)) {
					if (isset($_POST['artisans'])) {
						$artisans_sltd = $_POST['artisans'];
						// save the selected choices
						$artisanService = Artisan::find_by_customerId($customerId);
						
						$artisan_types = $artisanService->getArtisans();
						
						// Initially initialize all the types of technical services to false.
						foreach ($artisan_types as $type => $value) {
							$artisanService->{$type} = FALSE;
						}

						// Next, initialize all the selected technical services to true.
						foreach ($artisans_sltd as $type) {
							$artisanService->{$type} = TRUE;
						}

						// Determine if to update the record or create a new record
						if ($artisanService->customers_id) {
							// Update the artisan
							$savedArtisanService = $artisanService->update();
						} else {
							$artisanService->customers_id = $customerId;
							$savedArtisanService = $artisanService->create();
						}
						
						if($savedArtisanService) {
							$jsonData["success"] = true;
							$jsonData["inputFieldUpdated"] = "Your artisan services were successfully updated.";
						} else {
							$jsonData["success"] = false;
							$jsonData["saveError"] = "An error occurred while saving.";
						}
					} elseif (isset($post_params['sellers'])) {
						$sellers_sltd = $_POST['sellers'];
						// save the selected choices
						$sellerProduct = Seller::find_by_customerId($customerId);
						$inventory = $sellerProduct->getSellers();
												
						// Initially initialize all the types of technical services to false.
						foreach ($inventory as $type => $value) {
							$sellerProduct->{$type} = FALSE;
						}

						// Next, initialize all the selected technical services to true.
						foreach ($sellers_sltd as $type) {
							$sellerProduct->{$type} = TRUE;
						}

						// Determine if to update the record or create a new record
						if ($sellerProduct->customers_id) {
							// Update the seller inventory
							$savedSellerProduct = $sellerProduct->update();
						} else {
							$sellerProduct->customers_id = $customerId;
							$savedSellerProduct = $sellerProduct->create();
						}
						
						if(isset($savedSellerProduct)) {
							$jsonData["success"] = true;
							$jsonData["inputFieldUpdated"] = "Your inventories were successfully updated.";
						} else {
							$jsonData["success"] = false;
							$jsonData["saveError"] = "An error occurred while saving.";
						}
					} elseif (isset($post_params['technical_services'])) {
						$technical_services_sltd = $_POST['technical_services'];
						// save the selected choices
						$technicalService = Technical_Service::find_by_customerId($customerId);
						$tech_serv_types = $technicalService->getTechnicalServices();
												
						// Initially initialize all the types of technical services to false.
						foreach ($tech_serv_types as $type => $value) {
							$technicalService->{$type} = FALSE;
						}

						// Next, initialize all the selected technical services to true.
						foreach ($technical_services_sltd as $type) {
							$technicalService->{$type} = TRUE;
						}

						// Determine if to update the record or create a new record
						if ($technicalService->customers_id) {
							// Update the technical services
							$savedTechnicalService = $technicalService->update();
						} else {
							$technicalService->customers_id = $customerId;
							$savedTechnicalService = $technicalService->create();
						}
						
						if($savedTechnicalService) {
							$jsonData["success"] = true;
							$jsonData["inputFieldUpdated"] = "Your technical services were successfully updated.";
						} else {
							$jsonData["success"] = false;
							$jsonData["saveError"] = "An error occurred while saving.";
						}
					} elseif (isset($post_params['spare_parts'])) {
						$spare_parts_sltd = $_POST['spare_parts'];
						// save the selected choices
						$sparePart = Spare_Part::find_by_customerId($customerId);
						$spare_part_types = $sparePart->getSpareParts();
												
						// Initially initialize all the types of spare parts to false.
						foreach ($spare_part_types as $type => $value) {
							$sparePart->{$type} = FALSE;
						}

						// Next, initialize all the selected spare parts to true.
						foreach ($spare_parts_sltd as $type) {
							$sparePart->{$type} = TRUE;
						}

						// Determine if to update the record or create a new record
						if ($sparePart->customers_id) {
							// Update the spare parts
							$savedSparePart = $sparePart->update();
						} else {
							$sparePart->customers_id = $customerId;
							$savedSparePart = $sparePart->create();
						}
						
						if($savedSparePart) {
							$jsonData["success"] = true;
							$jsonData["inputFieldUpdated"] = "Your spare parts were successfully updated.";
						} else {
							$jsonData["success"] = false;
							$jsonData["saveError"] = "An error occurred while saving.";
						}
					}
				} else {
					// Inform validation error
					$jsonData["success"] = false;
					$jsonData["validationError"] = $validate->errors;
				}
			} else {
				// Illegal attempt to access database records
				$jsonData["success"] = false;
				$jsonData["loginError"] = "Please login again.";
			}
		} elseif ($post_params['inputField'] === 'business_category') {
			$validate->validate_name_update();
			if (empty($validate->errors)) {
				$businessCategory = new Business_Category();
				$bussCategory = Business_Category::find_by_customerId($customerId);
				if (!empty($bussCategory)) {
					switch ($business_category) {
						case 'artisan':
							$bussCategory->artisan = TRUE;
							$bussCategory->seller = FALSE;
							$bussCategory->technician = FALSE;
							$bussCategory->spare_part_seller = FALSE;
							$savedBusinessCategory = $bussCategory->update();
							break;
						case 'mobile_market':
							$bussCategory->artisan = FALSE;
							$bussCategory->seller = TRUE;
							$bussCategory->technician = FALSE;
							$bussCategory->spare_part_seller = FALSE;
							$savedBusinessCategory = $bussCategory->update();
							break;
						case 'technician':
							$bussCategory->artisan = FALSE;
							$bussCategory->seller = FALSE;
							$bussCategory->technician = TRUE;
							$bussCategory->spare_part_seller = FALSE;
							$savedBusinessCategory = $bussCategory->update();
							break;
						case 'spare_part_seller':
							$bussCategory->artisan = FALSE;
							$bussCategory->seller = FALSE;
							$bussCategory->technician = FALSE;
							$bussCategory->spare_part_seller = TRUE;
							$savedBusinessCategory = $bussCategory->update();
							break;
					}
				} else {
					// Create a new business category
					switch ($business_category) {
						case 'artisan':
							$businessCategory->customers_id = $customerId;
							$businessCategory->artisan = TRUE;
							$businessCategory->seller = FALSE;
							$businessCategory->technician = FALSE;
							$businessCategory->spare_part_seller = FALSE;
							$savedBusinessCategory = $businessCategory->create();

							// Delete user records from the related mechanic and spare part pages
							$busBrandObj = Bus_Brand::find_by_customerId($customerId);
							if (!empty($busBrandObj)) {
								$busBrandObj->delete();
							}

							$carBrandObj = Car_Brand::find_by_customerId($customerId);
							if (!empty($carBrandObj)) {
								$carBrandObj->delete();
							}

							$truckBrandObj = Truck_Brand::find_by_customerId($customerId);
							if (!empty($truckBrandObj)) {
								$truckBrandObj->delete();
							}

							$sparePartObj = Spare_Part::find_by_customerId($customerId);
							if (!empty($sparePartObj)) {
								$sparePartObj->delete();
							}

							$technicalServiceObj = Technical_Service::find_by_customerId($customerId);
							if (!empty($technicalServiceObj)) {
								$technicalServiceObj->delete();
							}

							$vehicleCategoryObj = Vehicle_Category::find_by_customerId($customerId);
							if (!empty($vehicleCategoryObj)) {
								$vehicleCategoryObj->delete();
							}
							break;
						case 'mobile_market':
							$businessCategory->customers_id = $customerId;
							$businessCategory->seller = TRUE;
							$businessCategory->artisan = FALSE;
							$businessCategory->technician = FALSE;
							$businessCategory->spare_part_seller = FALSE;
							$savedBusinessCategory = $businessCategory->create();

							// Delete user records from the related mechanic and spare part pages
							$busBrandObj = Bus_Brand::find_by_customerId($customerId);
							if (!empty($busBrandObj)) {
								$busBrandObj->delete();
							}

							$carBrandObj = Car_Brand::find_by_customerId($customerId);
							if (!empty($carBrandObj)) {
								$carBrandObj->delete();
							}

							$truckBrandObj = Truck_Brand::find_by_customerId($customerId);
							if (!empty($truckBrandObj)) {
								$truckBrandObj->delete();
							}

							$sparePartObj = Spare_Part::find_by_customerId($customerId);
							if (!empty($sparePartObj)) {
								$sparePartObj->delete();
							}

							$technicalServiceObj = Technical_Service::find_by_customerId($customerId);
							if (!empty($technicalServiceObj)) {
								$technicalServiceObj->delete();
							}

							$vehicleCategoryObj = Vehicle_Category::find_by_customerId($customerId);
							if (!empty($vehicleCategoryObj)) {
								$vehicleCategoryObj->delete();
							}
							break;
						case 'technician':
							$businessCategory->customers_id = $customerId;
							$businessCategory->artisan = FALSE;
							$businessCategory->seller = FALSE;
							$businessCategory->technician = TRUE;
							$businessCategory->spare_part_seller = FALSE;
							$savedBusinessCategory = $businessCategory->create();

							// Delete user records from the artisan table
							$artisanObj = Artisan::find_by_customerId($customerId);
							if (!empty($artisanObj)) {
								$artisanObj->delete();
							}

							// Delete user records from the seller table
							$sellerObj = Seller::find_by_customerId($customerId);
							if (!empty($sellerObj)) {
								$sellerObj->delete();
							}

							$sparePartObj = Spare_Part::find_by_customerId($customerId);
							if (!empty($sparePartObj)) {
								$sparePartObj->delete();
							}
							break;
						case 'spare_part_seller':
							$businessCategory->customers_id = $customerId;
							$businessCategory->artisan = FALSE;
							$businessCategory->seller = FALSE;
							$businessCategory->technician = FALSE;
							$businessCategory->spare_part_seller = TRUE;
							$savedBusinessCategory = $businessCategory->create();

							// Delete user records from the artisan table
							$artisanObj = Artisan::find_by_customerId($customerId);
							if (!empty($artisanObj)) {
								$artisanObj->delete();
							}

							// Delete user records from the seller table
							$sellerObj = Seller::find_by_customerId($customerId);
							if (!empty($sellerObj)) {
								$sellerObj->delete();
							}

							$technicalServiceObj = Technical_Service::find_by_customerId($customerId);
							if (!empty($technicalServiceObj)) {
								$technicalServiceObj->delete();
							}
							break;
					}
				}
				
				if(isset($savedBusinessCategory)) {
					// $session->message("");
					// This message should be saved in the session.
					$message = "Your business category was successfully updated.";
					// redirect_to('customerEditPage2.php');
				} else {
					$message = "An error occurred while saving.";
				}
			} else {
				// Inform validation error
				$jsonData["success"] = false;
				$jsonData["validationError"] = $validate->errors;
			}
		} else {
			$jsonData["success"] = false;
			$jsonData["postDataError"] = "An error occurred sending the data from JSON";
		}
	}
	// Reset the CSRF token and time in the session
	// First get the csrfToken name and csrfTime name
	$csrfTokenVar = getCSRFtokenVar();
	$csrfTimeVar = getCSRFtimeVar();
	list($newCSRFtoken, $newCSRFtime) = updateCSRFtokenAndTime($csrfTokenVar, $csrfTimeVar);
	$jsonData['newCSRFtoken'] = $newCSRFtoken;
	$jsonData['newCSRFtime'] = $newCSRFtime;	
}
echo json_encode($jsonData);

function getCSRFtokenVar() {
	// Get the POST global variables
	$post_params_keys = array_keys($_POST);	
	$find_str = 'csrf_token_';
	
	$post_csrf_token_key = "";
	// Check for 'csrf_token_' in the POST global keys.
	foreach ($post_params_keys as $param) {
		if (strpos($param, $find_str) === 0) {
			$post_csrf_token_key = $param;
		}
	}

	return $post_csrf_token_key;
}

function getCSRFtimeVar() {
	// Get the POST global variables
	$post_params_keys = array_keys($_POST);	
	$find_str = 'csrf_time_';
	
	$post_csrf_time_key = "";
	// Check for 'csrf_token_' in the POST global keys.
	foreach ($post_params_keys as $param) {
		if (strpos($param, $find_str) === 0) {
			$post_csrf_time_key = $param;
		}
	}

	return $post_csrf_time_key;
}

function updateCSRFtokenAndTime($csrfTokenVar, $csrfTimeVar) {
	global $security;
	global $session;

	// generate new csrf token
	$newCSRFtoken = $security->csrf_token();
	// generate new csrf time
	$newCSRFtime = time();

	// Update the CSRF token
	$session->csrf_tokens[$csrfTokenVar] = $_SESSION[$csrfTokenVar] = $newCSRFtoken;
	// Update the CSRF time
	$session->csrf_tokens_time[$csrfTimeVar] = $_SESSION[$csrfTimeVar] = $newCSRFtime;

	// return the new CSRF tokne and time
	return array($newCSRFtoken, $newCSRFtime);
}

function displayVehCheckBoxes($vehicleType) {
	$checkboxes = "";
	if ($vehicleType === "cars") {
		// Get all car brands
		$car_brands = new Car_Brand(); 
		$allCarBrands = $car_brands->getVehicleBrands();
		if (!empty($allCarBrands)) {
			foreach($allCarBrands as $car => $value) {
				$checkboxes .= "<label class='checkBoxLabel'><input type='checkbox' name='car_brands' value='".$car."' id='".$car."' />".ucfirst(str_replace("_", " ", $car))."</label>";
			}
		} else {
			$output .= "&nbsp";
		}				
	} elseif ($vehicleType === "buses") {
		// Get all bus brands
		$bus_brands = new Bus_Brand();
		$allBusBrands = $bus_brands->getBusBrands(); 
		if (!empty($allBusBrands)) {
			foreach($allBusBrands as $bus => $value) {
				$checkboxes .= "<label class='checkBoxLabel'><input type='checkbox' name='bus_brands' value='".$bus."' id='".$bus."' />".ucfirst(str_replace("_", " ", $bus))."</label>";
			}
		} else {
			$output .= "&nbsp";
		}			
	} elseif ($vehicleType === "trucks") {
		// Get all truck brands
		$truck_brands = new Truck_Brand();
		$allTruckBrands = $truck_brands->getTruckBrands();
		if (!empty($allTruckBrands)) {
			foreach($allTruckBrands as $truck => $value) {
				$checkboxes .= "<label class='checkBoxLabel'><input type='checkbox' name='truck_brands' value='".$truck."' id='".$truck."' />".ucfirst(str_replace("_", " ", $truck))."</label>";
			}	
		} else {
			$output .= "&nbsp";
		}			
	}
	$checkboxes .= "<br/>";
	
	return $checkboxes;
}
?>