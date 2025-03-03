<?php
require_once("../../includes/initialize.php");
// if (!$session->is_user_logged_in() OR !$session->is_customer_logged_in()) { redirect_to("../loginPage.php"); }
$message = "";

// the request_is_get() fxn will ensure that a get request was sent from the webpage
if(request_is_get() ) {
	// This variable contains the filtered values from the GET_global variable. It is an array
	$get_params = allowed_get_params(['id']);
	
	// Eliminate HTML tags embedded in the URL inputs
	foreach($get_params as $param) {
		// run htmlentities check on the parameters
		if(isset($get_params[$param])) {
			// run htmlentities check on the parameters
			$get_params[$param] = h2($param);
		} 
	}
	
	if (isset($get_params["id"])) {
		if (($get_params["id"] == $session->customer_id)) {
			$customerID = (int)$get_params["id"];
			
			$customer = Customer::find_by_id($customerID);
			if (isset($customer->first_name)) {
				$cus_first_name = sql_prep($customer->first_name);
			}
			if (isset($customer->last_name)) {
				$cus_last_name = $customer->last_name;
			}
			if (isset($customer->username)) {
				$cus_username = $customer->username;
			}
			// Check for the customer's full name
			if (is_bool($customer)) {
				$cus_full_name = " ";
			} else {
				$cus_full_name = $customer->full_name();
			}
			if (isset($customer->business_title)) {
				$cus_business_title = $customer->business_title;
			}
			if (isset($customer->customer_email)) {
				$cus_email = $customer->customer_email;
			}
			if (isset($customer->phone_number)) {
				$cus_phone_number = $customer->phone_number;
			}

			$address = Address::find_by_customerId($customerID);
			if (isset($address->address_line_1)) {
				$cus_addressLine1 = $address->address_line_1;
			}
			if (isset($address->address_line_2)) {
				$cus_addressLine2 = $address->address_line_2;
			}
			if (isset($address->address_line_3)) {
				$cus_addressLine3 = $address->address_line_3;
			}
			if (isset($address->city)) {
				$cus_city = $address->city;
			}
			if (isset($address->state)) {
				$cus_state = $address->state;
			}
		} else {
			global $session;
			// Return an error message to the customer and log a spurious attempt to get into someone's profile.
			$session->message("Invalid customer-id received.");
			// redirect to home page if incorrect customer id is provided.
			redirect_to("/WhoSabiWork/Public/customer/customerEditPage2.php?id=".urlencode($session->customer_id));
		}
	} else {
		global $session;
		$session->message("No Customer ID was provided.");
		// redirect to another page
		redirect_to("/WhoSabiWork/index.php");
	}
	
} /* else {
	// Log-in spurios attempt to get into a user's account
	$session->message("Improper page request.");
	redirect_to("/WhoSabiWork/index.php");
} */

	// Sanitize inputs from the form to be passed into the database.
	$customerID = sql_prep($_SESSION["customer_id"]);

	$customer = Customer::find_by_id($customerID);
	

	$artisans_sltd;  // used in post 
	$artisan_services = new Artisan();
	$artisanServices = $artisan_services->selected_choices($customerID); // Used in functions
	$allArtisanServices = $artisan_services->getArtisans(); // Used in functions

	$technical_services_sltd; // used in post
	$technical_services = new Technical_Service();
	$technicalServices = $technical_services->selected_choices($customerID); // Used in function
	$allTechServices = $technical_services->getTechnicalServices(); // Used in functions

	$spare_parts_sltd; // Used in post
	$spare_parts = new Spare_Part(); // 
	$spareParts = $spare_parts->selected_choices($customerID); // Used in function
	$allSpareParts = $spare_parts->getSpareParts(); // Used in function

	// Get the id from the Artisan table using the customerID
	$artisanServStatic = Artisan::find_by_customerId($customerID);
	if (isset($artisanServStatic->id)) {
		$artisanServId = $artisanServStatic->id; // Used in post
	}

	// Get the id from the Technical_Service table using the customerID
	$techServStatic = Technical_Service::find_by_customerId($customerID);
	if (isset($techServStatic->id)) {
		$techServId = $techServStatic->id; // Used in post
	}

	// Get the id from the Spare_Part table using the customerID
	$sparePartStatic = Spare_Part::find_by_customerId($customerID);
	if (isset($sparePartStatic->id)) {
		$sparePartId = $sparePartStatic->id; // Used in post
	}

	$car_brands_sltd; // Used in post
	// Get the array of cars selected
	$car_brands = new Car_Brand(); // Used in post / functions
	$carBrands = $car_brands->selected_choices($customerID); // Used in post
	$allCarBrands = $car_brands->getVehicleBrands(); // Used in functions
	// print_r($allCarBrands);

	$bus_brands_sltd; // Used in post
	// Get the array of bus selected
	$bus_brands = new Bus_Brand(); // commented in post
	$busBrands = $bus_brands->selected_choices($customerID); // Used in function
	$allBusBrands = $bus_brands->getBusBrands(); // Used in function

	$truck_brands_sltd; // Used in post
	// Get the array of cars selected
	$truck_brands = new Truck_Brand(); // Commented in post
	$truckBrands = $truck_brands->selected_choices($customerID); // Used in function
	$allTruckBrands = $truck_brands->getTruckBrands(); // Used in function

	// Get the id from the Car_Brand table using the customerID
	$carBrandsStatic = Car_Brand::find_by_customerId($customerID); // dependent
	if (isset($carBrandsStatic->id)) {
		$carBrandsId = $carBrandsStatic->id; // Used in post
	}

	// Get the id from the Bus_Brand table using the customerID
	$busBrandsStatic = Bus_Brand::find_by_customerId($customerID); // dependent
	if (isset($busBrandsStatic->id)) {
		$busBrandsId = $busBrandsStatic->id; // Used in post
	}

	// Get the id from the Truck_Brand table using the customerID
	$truckBrandsStatic = Truck_Brand::find_by_customerId($customerID); // dependent
	if (isset($truckBrandsStatic->id)) {
		$truckBrandsId = $truckBrandsStatic->id; // Used in post
	}

	// Get the vehicle type the technician fixes
	$vehicleCategory = Vehicle_Category::find_by_customerId($customerID); // Used in post and get

	// Get the business category of the customer
	$businessCategory = Business_Category::find_by_customerId($customerID); // Used in post(already decleared), functions, get
	if (isset($businessCategory->business_description)) {
		$cus_business_description = $businessCategory->business_description; // Used in get
	}

	$customerPictures = Photograph::find_customer_images($customerID); // Used in get

	$comments = User_Comment::find_comments_on($customerID); // Used in get
	// $comments = $customerDetails->comments();

	// global $business_category; // Used in post
	// global $vehicle_category; // Used in post
	// global $car_brands; // Already used
	// global $technical_services; // Already used

	if (isset($_POST['submit_first_name'])) {
		$first_name = trim($_POST['edit_first_name']);
		$first_name = $database->escape_value($first_name);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$customer = new Customer();
			$customer->first_name = $first_name;
			$customer->id = $customerID;
			$customer->date_edited = $customer->current_Date_Time();
			$savedCustomer = $customer->updateColumn('first_name');
			
			if(isset($savedCustomer)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "First name was successfully updated in the database.";
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		} 	
	} elseif (isset($_POST['submit_last_name'])) {
		$last_name = trim($_POST['edit_last_name']);
		$last_name = $database->escape_value($last_name);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$customer = new Customer();
			$customer->last_name = $last_name;
			$customer->id = $customerID;
			$customer->date_edited = $customer->current_Date_Time();
			$savedCustomer = $customer->updateColumn('last_name');
			
			if(isset($savedCustomer)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "Last name was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		} 	
	} elseif (isset($_POST['submit_username'])) {
		$username = trim($_POST['edit_username']);
		$username = $database->escape_value($username);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$customer = new Customer();
			$customer->username = $username;
			$customer->id = $customerID;
			$customer->date_edited = $customer->current_Date_Time();
			$savedCustomer = $customer->updateColumn('username');
			
			if(isset($savedCustomer)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "Username was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		} 	
	} elseif (isset($_POST['submit_password'])) {
		$password = trim($_POST['edit_password']);
		$password =  $database->escape_value($password);
		$confirm_password = trim($_POST['confirm_password']);
		$confirm_password =  $database->escape_value($confirm_password);
		$validate->validate_name_update();
		if ($password !== $confirm_password) {
			$validate->errors["password_match_err"] = "The passwords does not match.";
		}
		if (empty($validate->errors)) {
			// $customer = new Customer();
			$customer->password = $customer->password_encrypt($password);
			$customer->date_edited = $customer->current_Date_Time();
			$savedPassword = $customer->updateColumn('password');
			
			if(isset($savedPassword)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "Password was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		} 	
		
	} elseif (isset($_POST['submit_business_name'])) {
		$business_name = trim($_POST['edit_business_name']);
		$business_name = $database->escape_value($business_name);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$customer = new Customer();
			$customer->business_title = $business_name;
			$customer->id = $customerID;
			$customer->date_edited = $customer->current_Date_Time();
			$savedCustomer = $customer->updateColumn('business_title');
			
			if(isset($savedCustomer)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "Business title was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		} 	
	} elseif (isset($_POST['submit_business_description'])){
		$business_description = trim($_POST['business_description']);
		$business_description = $database->escape_value($business_description);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			// $businessCategory = new Business_Category();
			$businessCategory = Business_Category::find_by_customerId($_SESSION["customer_id"]);
			$businessCategory->business_description = $business_description;
			if ($businessCategory->update()) {
				$message = "The business description was successfully updated in the database.";
			} else {
				$message = "An error occurred while saving.";
			}
			
		} else {
			$message = "There was an error during validation. ";
		}
	} elseif (isset($_POST['submit_business_email'])) {
		$business_email = trim($_POST['edit_business_email']);
		$edit_business_email = $database->escape_value($edit_business_email);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$customer = new Customer();
			$customer->customer_email = $business_email;
			$customer->id = $customerID;
			$customer->date_edited = $customer->current_Date_Time();
			$savedCustomer = $customer->updateColumn('customer_email');
			
			if(isset($savedCustomer)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "Email was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		}
	} elseif (isset($_POST['submit_phone_number'])) {
		$phone_number = trim($_POST['edit_phone_number']);
		$phone_number = $database->escape_value($phone_number);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$customer = new Customer();
			$customer->phone_number = $phone_number;
			$customer->id = $customerID;
			$customer->date_edited = $customer->current_Date_Time();
			$savedCustomer = $customer->updateColumn('phone_number');
			
			if(isset($savedCustomer)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "Email was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		}
	} elseif (isset($_POST['submit_address_line_1'])) {
		$address_line_1 = trim($_POST['edit_address_line_1']);
		$edit_address_line_1 = $database->escape_value($edit_address_line_1);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$address = new Address();
			$address->address_line_1 = $address_line_1;
			$address->customers_id = $customerID;
			$savedAddress = $address->updateColumn('address_line_1');
			
			if(isset($savedAddress)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "Address line 1 was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		}
	} elseif (isset($_POST['submit_address_line_2'])) {
		$address_line_2 = trim($_POST['edit_address_line_2']);
		$edit_address_line_2 = $database->escape_value($edit_address_line_2);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$address = new Address();
			$address->address_line_2 = $address_line_2;
			$address->customers_id = $customerID;
			$savedAddress = $address->updateColumn('address_line_2');
			
			if(isset($savedAddress)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "Address line 2 was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		}
	} elseif (isset($_POST['submit_address_line_3'])) {
		$address_line_3 = trim($_POST['edit_address_line_3']);
		$edit_address_line_3 = $database->escape_value($edit_address_line_3);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$address = new Address();
			$address->address_line_3 = $address_line_3;
			$address->customers_id = $customerID;
			$savedAddress = $address->updateColumn('address_line_3');
			
			if(isset($savedAddress)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "Address line 3 was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		}
	} elseif (isset($_POST['submit_city'])) {
		$city = trim($_POST['edit_city']);
		$city = $database->escape_value($city);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$address = new Address();
			$address->city = $city;
			$address->customers_id = $customerID;
			$savedAddress = $address->updateColumn('city');
			
			if(isset($savedAddress)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "City was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		}
	} elseif (isset($_POST['submit_state'])) {
		$state = trim($_POST['edit_state']);
		$state = $database->escape_value($state);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$address = new Address();
			$address->state = $state;
			$address->customers_id = $customerID;
			$savedAddress = $address->updateColumn('state');
			
			if(isset($savedAddress)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "State was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		}
	} elseif (isset($_POST['submit_business_category'])) {
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$businessCategory = new Business_Category();
			$bussCategory = Business_Category::find_by_customerId($customerID);
			switch ($business_category) {
				case 'artisan':
					$bussCategory->artisan = TRUE;
					$bussCategory->technician = FALSE;
					$bussCategory->spare_part_seller = FALSE;
					$savedBusinessCategory = $bussCategory->update();
					break;
				case 'technician':
					$bussCategory->artisan = FALSE;
					$bussCategory->technician = TRUE;
					$bussCategory->spare_part_seller = FALSE;
					$savedBusinessCategory = $bussCategory->update();
					break;
				case 'spare_part_seller':
					$bussCategory->artisan = FALSE;
					$bussCategory->technician = FALSE;
					$bussCategory->spare_part_seller = TRUE;
					$savedBusinessCategory = $bussCategory->update();
					break;
			}
			
			if(isset($savedBusinessCategory)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "Your business category was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		}
	} elseif (isset($_POST['submit_vehicle_category'])) {
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			$vehicleCategory = new Vehicle_Category();
			switch ($vehicle_category) {
				case 'cars':
					$vehicleCategory->customers_id = $customerID;
					$vehicleCategory->car = TRUE;
					$vehicleCategory->bus = FALSE;
					$vehicleCategory->truck = FALSE;
					$savedVehicleCategory = $vehicleCategory->updateColumn('car', 'bus', 'truck');
					break;
				case 'buses':
					$vehicleCategory->customers_id = $customerID;
					$vehicleCategory->car = FALSE;
					$vehicleCategory->bus = TRUE;
					$vehicleCategory->truck = FALSE;
					$savedVehicleCategory = $vehicleCategory->updateColumn('car', 'bus', 'truck');
					break;
				case 'trucks':
					$vehicleCategory->customers_id = $customerID;
					$vehicleCategory->car = FALSE;
					$vehicleCategory->bus = FALSE;
					$vehicleCategory->truck = TRUE;
					$savedVehicleCategory = $vehicleCategory->updateColumn('car', 'bus', 'truck');
					break;
			}
			
			if(isset($savedVehicleCategory)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "Your vehicle category was successfully updated in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		} else {
			$message = "There was an error during validation. ";
		}
	} elseif (isset($_POST['submit_vehicle_brands'])) {
		
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			if (isset($_POST['car_brands'])) {
				$carBrand = new Car_Brand();
				// First initialize all the values to false before you set the selected cars to true
				// Get all the car brands
				$car_types = $carBrand->getVehicleBrands();
				foreach ($car_types as $type => $value) {
					$carBrand->{$type} = FALSE;
				}
				// Specify the id of the customer in the table
				$carBrand->id = $carBrandsId;
				$carBrand->customers_id = $customerID;
				
				// save the selected choices
				foreach ($car_brands_sltd as $type) {
					$carBrand->{$type} = TRUE;
				}
				$savedCarBrand = $carBrand->update();
				
				if(isset($savedCarBrand)) {
					// This message should be saved in the session.
					$message = "Your car brands were successfully updated in the database.";
					// unset($car_brands);
				} else {
					$message = "An error occurred while saving.";
				}
			} elseif (isset($_POST['bus_brands'])) {
				$busBrand = new Bus_Brand();
				// First initialize all the values to false before you set the selected buses to true
				// Get all the bus brands
				$bus_types = $busBrand->getBusBrands();
				foreach ($bus_types as $type => $value) {
					$busBrand->{$type} = FALSE;
				}
				// Specify the id of the customer in the table
				$busBrand->id = $busBrandsId;
				$busBrand->customers_id = $customerID;
				
				// save the selected choices
				foreach ($bus_brands_sltd as $type) {
					$busBrand->{$type} = TRUE;
				}
				$savedBusBrand = $busBrand->update();
				
				if(isset($savedBusBrand)) {
					// This message should be saved in the session.
					$message = "Your bus brands were successfully updated in the database.";
					// unset($bus_brands);
				} else {
					$message = "An error occurred while saving.";
				}
			} elseif (isset($_POST['truck_brands'])) {
				$truckBrand = new Truck_Brand();
				// First initialize all the values to false before you set the selected buses to true
				// Get all the truck brands
				$truck_types = $truckBrand->getTruckBrands();
				foreach ($truck_types as $type => $value) {
					$truckBrand->{$type} = FALSE;
				}
				// Specify the id of the customer in the table
				$truckBrand->id = $truckBrandsId;
				$truckBrand->customers_id = $customerID;
				
				// save the selected choices
				foreach ($truck_brands_sltd as $type) {
					$truckBrand->{$type} = TRUE;
				}
				$savedTruckBrand = $truckBrand->update();
				
				if(isset($savedTruckBrand)) {
					// This message should be saved in the session.
					$message = "Your truck brands were successfully updated in the database.";
					// unset($truck_brands);
				} else {
					$message = "An error occurred while saving.";
				}
			}
		} else {
			$message = "There was an error during validation. ";
		}
	} elseif (isset($_POST['submit_services_parts'])) {
		print_r($_POST);
		$validate->validate_name_update();
		if (empty($validate->errors)) {
			if (isset($_POST['artisans'])) {
				// save the selected choices
				$artisanService = new Artisan();
				$artisan_types = $artisanService->getArtisans();
				
				// Initially initialize all the types of technical services to false.
				foreach ($artisan_types as $type => $value) {
					$artisanService->{$type} = FALSE;
				}
				// Specify the id of the customer in the table
				$artisanService->id = $artisanServId;
				$artisanService->customers_id = $customerID;
				// $artisanService->update();
				
				// Next, initialize all the selected technical services to true.
				foreach ($artisans_sltd as $type) {
					$artisanService->{$type} = TRUE;
				}
				$savedArtisanService = $artisanService->update();
				
				if(isset($savedArtisanService)) {
					// $session->message("First name was successfully updated.");
					// This message should be saved in the session.
					$message = "Your artisan services were successfully updated in the database.";
					// redirect_to('customerEditPage2.php');
				} else {
					$message = "An error occurred while saving.";
				}
			} elseif (isset($_POST['technical_services'])) {
				// save the selected choices
				$technicalService = new Technical_Service();
				$tech_serv_types = $technicalService->getTechnicalServices();
				// $tech_serv_types = array("engine_service", "mechanical_service", "electrical_service", "air_conditioning_service", "computer_diagnostics_service", "panel_beating_service", "body_work_service", "shock_absorber_service", "ballon_shocks_service", "wheel_balancing_and_alignment_service", "car_wash_service", "towing_service", "buy_cars", "sell_cars");
				
				// Initially initialize all the types of technical services to false.
				foreach ($tech_serv_types as $type => $value) {
					$technicalService->{$type} = FALSE;
				}
				// Specify the id of the customer in the table
				$technicalService->id = $techServId;
				$technicalService->customers_id = $customerID;
				// $technicalService->update();
				
				// Next, initialize all the selected technical services to true.
				foreach ($technical_services_sltd as $type) {
					$technicalService->{$type} = TRUE;
				}
				$savedTechnicalService = $technicalService->update();
				
				if(isset($savedTechnicalService)) {
					// $session->message("First name was successfully updated.");
					// This message should be saved in the session.
					$message = "Your technical services were successfully updated in the database.";
					// redirect_to('customerEditPage2.php');
				} else {
					$message = "An error occurred while saving.";
				}
			} elseif (isset($_POST['spare_parts'])) {
				// save the selected choices
				$sparePart = new Spare_Part();
				$spare_part_types = $sparePart->getSpareParts();
				
				// Initially initialize all the types of spare parts to false.
				foreach ($spare_part_types as $type => $value) {
					$sparePart->{$type} = FALSE;
				}
				// Specify the id of the customer in the table
				$sparePart->id = $sparePartId;
				// Specify the id of the customer from the customer table
				$sparePart->customers_id = $customerID;
				// $sparePart->update();
				
				// Next, initialize all the selected spare parts to true.
				foreach ($spare_parts_sltd as $type) {
					$sparePart->{$type} = TRUE;
				}
				$savedSparePart = $sparePart->update();
				
				if(isset($savedSparePart)) {
					// $session->message("First name was successfully updated.");
					// This message should be saved in the session.
					$message = "Your spare parts were successfully updated in the database.";
					// redirect_to('customerEditPage2.php');
				} else {
					$message = "An error occurred while saving.";
				}
			}
		} else {
			$message = "There was an error during validation. ";
		}
	} 

	if (isset($_POST['delete_account'])) {
		$customerKey = new Customer();
		$deactivateCustomer = $customerKey->deactivate($_SESSION["customer_id"]);
		if($deactivateCustomer) {
			$session->message($session->customer_full_name." account was successfully deleted.");
			redirect_to('../homePage.php');
		} else {
			// $session->message("The user could not be deleted.");
			// redirect_to('customerEditPage2.php?id='.$get_params['id']);
			$message = "Error! This account could not be deleted.";
		}
	}

	// Upload pictures
	$photo = new Photograph();
	if (isset($_POST['SubmitPhoto'])) {
		$caption = trim($_POST['caption']);
		$caption = $database->escape_value($caption);
			
		// The attach_file and save functions in the Photograph class already check for errors
		$photo->attach_file($_FILES['photo_upload']);
		$photo->customers_id = $_SESSION["customer_id"];
		$photo->caption = $caption;
		$savedPhoto = $photo->save();
		if ($savedPhoto === TRUE) {
			// Success
			$message = "Photograph uploaded successfully.";
		} else {
			// Failure
			$message = join("<br/>", $photo->errors);
		}
	}	

	if (isset($_POST['submit_availability'])) {
		$cus_availability = new Customers_Availability();
		
		// validate the form was filled if the button is clicked.
		$day;
		$hours = array();
		$validate->validate_customer_availability();
		
		if (empty($validate->errors)) {
			$cus_availability->customers_id = $customerID;
			$cus_availability->date_available = $cus_availability->makeDateForDay($day);
			
			$workingHours = array("eight_to_nine_am", "nine_to_ten_am", "ten_to_eleven_am", "eleven_to_twelve_pm", "twelve_to_one_pm", "one_to_two_pm", "two_to_three_pm", "three_to_four_pm");
			
			// Initially initialize all the variables of the working hours to false
			foreach ($workingHours as $period) {
				$cus_availability->{$period} = FALSE;
			}
			
			// Next, initialize all the selected working hours to true.
			foreach ($hours as $period) {
				$cus_availability->{$period} = TRUE;
			}
			
			$cus_availability->date_created = current_Date_Time();
			
			$savedCusAvailability = $cus_availability->save();
			
			if(isset($savedCusAvailability)) {
				// $session->message("First name was successfully updated.");
				// This message should be saved in the session.
				$message = "Your appointment availability was successfully saved in the database.";
				// redirect_to('customerEditPage2.php');
			} else {
				$message = "An error occurred while saving.";
			}
		
		} else {
			$message = "There was an error during validation. ";
		}
		
	}

	function displayVehCheckBoxes($vehicleType) {
		global $allCarBrands;
		global $allBusBrands;
		global $allTruckBrands;
		
		$checkboxes = "";
		if ($vehicleType === "Cars") {
			foreach($allCarBrands as $car => $value) {
				$checkboxes .= "<label><input type='checkbox' name='car_brands[]' value='".$car."' id='".$car."' />".ucfirst(str_replace("_", " ", $car))."</label>";
			}
		} elseif ($vehicleType === "Buses") {
			foreach($allBusBrands as $bus => $value) {
				$checkboxes .= "<label><input type='checkbox' name='bus_brands[]' value='".$bus."' id='".$bus."' />".ucfirst(str_replace("_", " ", $bus))."</label>";
			}
		} elseif ($vehicleType === "Trucks") {
			foreach($allTruckBrands as $truck => $value) {
				$checkboxes .= "<label><input type='checkbox' name='truck_brands[]' value='".$truck."' id='".$truck."' />".ucfirst(str_replace("_", " ", $truck))."</label>";
			}
		}
		$checkboxes .= "<br/>";
		
		return $checkboxes;
	}

	function displayBussCateCheckBoxes($businessCategory) {
		global $allArtisanServices;
		global $allTechServices;
		global $allSpareParts;
		
		$checkboxes = "";
		if ($businessCategory === "Artisan") {
			foreach($allArtisanServices as $artisanServs => $value) {
				$checkboxes .= "<label><input type='checkbox' name='artisans[]' value='".$artisanServs."' id='".$artisanServs."' />".ucfirst(str_replace("_", " ", $artisanServs))."</label>";
			}
		} elseif ($businessCategory === "Technician") {
			foreach($allTechServices as $techServs => $value) {
				$checkboxes .= "<label><input type='checkbox' name='technical_services[]' value='".$techServs."' id='".$techServs."' />".ucfirst(str_replace("_", " ", $techServs))."</label>";
			}
		} elseif ($businessCategory === "Spare part seller") {
			foreach($allSpareParts as $sParts => $value) {
				$checkboxes .= "<label><input type='checkbox' name='spare_parts[]' value='".$sParts."' id='".$sParts."' />".ucfirst(str_replace("_", " ", $sParts))."</label>";
			}
		}
		$checkboxes .= "<br/>";
		
		return $checkboxes;
	}

	function displayVehSpecializatn($vehicleType) {
		global $carBrands;
		global $busBrands;
		global $truckBrands;
		
		$output = "";
		$output .= "<ul style='margin:0px'>";
		if ($vehicleType === "Cars") {
			foreach ($carBrands as $key => $value) {
				$output .= '<li style="float:left; padding-right:10px; list-style-type:none">'.$key.',';
			}
		} elseif ($vehicleType === "Buses") {
			foreach ($busBrands as $key => $value) {
				$output .= '<li style="float:left; padding-right:10px; list-style-type:none">'.$key.',';
			}
		} elseif ($vehicleType === "Trucks") {
			foreach ($truckBrands as $key => $value) {
				$output .= '<li style="float:left; padding-right:10px; list-style-type:none">'.$key.',';
			}
		}
		$output = chop($output, ',');
		$output .= "</li></ul>";
		
		return $output;
	}

	function displayArtisansServParts($businessCategory) {
		global $artisanServices;
		global $technicalServices;
		global $spareParts;
		
		$output = "";
		$output .= "<ul style='margin:0px'>";
		if ($businessCategory === "Artisan") {
			foreach ($artisanServices as $key => $value) {
				$output .= '<li style="float:left; padding-right:10px; list-style-type:none">'.$key.',';
			}
		} elseif ($businessCategory === "Technician") {
			foreach ($technicalServices as $key => $value) {
				$output .= '<li style="float:left; padding-right:10px; list-style-type:none">'.$key.',';
			}
		} elseif ($businessCategory === "Spare part seller") {
			foreach ($spareParts as $key => $value) {
				$output .= '<li style="float:left; padding-right:10px; list-style-type:none">'.$key.',';
			}
		}
		// $output = rtrim($output, ','); rtrim or chop can do the same task.
		$output = chop($output, ',');
		$output .= "</li></ul>";
		
		return $output;
	}


	$customerAvailability;
	function selectDays() {
		$cus_availability = new Customers_Availability();
		$weekDays = $cus_availability->weekDaysFromToday();
		$weekDates = $cus_availability->weekDatesFromToday();
		$output = "";
		$output .= "<label for='set_days'>Select Days:</label>
					<select name='set_days' id='set_days'>
					<option value='select'>Select</option>";
					
		global $database;
		$cus_availability = new Customers_Availability();
		$weekDates = $cus_availability->weekDatesFromToday();
		$firstDateOfWeek = $weekDates[0];
		$lastDateOfWeek = $weekDates[6];
		
		$sql = "SELECT date_available FROM `customers_availability` WHERE date_available >= '{$firstDateOfWeek}' AND date_available <= '{$lastDateOfWeek}' AND customers_id = {$_SESSION['customer_id']} ";
		$result_set = $database->query($sql); 

		$count = 0; 
		$customerAvailability = array();
		while($row = mysqli_fetch_assoc($result_set)){ 
			// Gets the saved schedule of the customer 
			$customerAvailability[$count] = $row["date_available"]; 
			$count++; // Relevant
		}
		
		$editedSchedule = array();
		// remove the time part in the MYSQL datetime variable to include only the date.
		foreach ($customerAvailability as $key => $date) {
			$editedSchedule[$key] = substr($date, 0, strpos($date, ' '));
		}
		
		// loop through the weekdays available and output it. 
		foreach ($weekDays as $key => $weekday) { 
		  if (!in_array($weekDates[$key], $editedSchedule)) {
			// $output .= '<option value="'.$weekday.'">'.ucfirst($weekday).'</option><br/>';
			
			$output .= '<option value="'.$weekday.'">'.ucfirst($weekday).'  '.date_to_text($weekDates[$key]).'</option><br/>'; 
		  }
		}
		$output .= "</select>";
		
		return $output;
	}

	$customerTime = array(); // Can be relocated inside the function
	$refinedTime = array(); // Can be relocated inside the function
	$customerAvailability = array(); // Can be relocated inside the function
	$counterForJS = 0; // This will determine the number of times the JavaScript function should be loaded.
	function displayCustomerAvailability() {
		global $database;
		global $customerTime;
		global $refinedTime;
		global $customerAvailability;
		global $counterForJS;
		global $customerID;
		
		// Instantiate the Customers_Availability class
		$cus_availability = new Customers_Availability();
		// Get the date for the week using the method for it
		$weekDates = $cus_availability->weekDatesFromToday();
		// Get the current day date of the week
		$firstDateOfWeek = $weekDates[0];
		// Get the next 6 days date of the week
		$lastDateOfWeek = $weekDates[6];
		
		$sql = "SELECT * FROM `customers_availability` WHERE date_available >= '{$firstDateOfWeek}' AND date_available <= '{$lastDateOfWeek}' AND customers_id = {$_SESSION['customer_id']} ";
		$result_set = $database->query($sql); 

		$count = 0; 
		// $customerAvailability = array();
		$customerRecord = array();
		
		while($row = mysqli_fetch_assoc($result_set)){ 
			// Gets the saved schedule of the customer 
			$customerRecord[$count] = $row;
			$customerAvailability[$count] = $row["date_available"];
			$customerTime[$count] = array_splice($row, 3, -1);
			$count++; // Relevant
		}
		
		$editedSchedule = array();
		// remove the time part in the MYSQL datetime variable to include only the date.
		foreach ($customerAvailability as $key => $date) {
			$editedSchedule[$key] = substr($date, 0, strpos($date, ' '));
		}  
		
		// eliminate the zeros/false values from the array.
		foreach ($customerTime as $key => $value) {
			$count = 0;
			foreach ($customerTime[$key] as $time => $timeRecord) {
				if ($timeRecord == TRUE) {
					$refinedTime[$key][$count] = $time;
					$count++;
				}
			}
		}
		
		$output = "";
		foreach ($editedSchedule as $key => $period) {
			$counterForJS++;
			// Display the saved availability days and times of the customer.
			$output .= "<div class='schedule' id='scheduleDiv".$key."'>";
			$output .= "<p id='customers_id' style='display:none;'>".$customerID."</p>";
			$output .= "<p id='date_available' style='display:none;'>".$editedSchedule[$key]."</p>";
			$output .= "<p style='margin-bottom:0px; clear:both;'>";
			$output .= date_to_weekday($editedSchedule[$key]);
			$output .= ":  ".date_to_text($editedSchedule[$key]);
			$output .= " ";
			// Show the edit button
			$output .= "<input type='button' value='Edit' id='openDiv".$key."' class='editButton' />";
			// Show the delete button
			$output .= "<input type='button' value='Delete' href='#' id='deleteSchedule".$key."' style='margin-left:10px;'/>";
			// $output .= "<a href=''>Edit</a>";
			$output .= "</p>";
			// list all the time available
			$output .= "<ul id=".'timeUL'.$key." >";
			foreach ($refinedTime[$key] as $timeKey => $timeRecord) {
				$output .= "<li class='timeList' >".$cus_availability->editDbVarToFormTime($refinedTime[$key][$timeKey])."</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";
			$output .= "<div class='editClass' id=".'editTime'.$key.">";
			$output .= '
				
					<label>Edit Hours:</label>
					<input type="button" value="Close" id="closeDiv'.$key.'" class="closeButton" />
					<br/>
					
					<label>
					<input type="checkbox" name="edit_hours[]" value="eight_to_nine_am" id="eight_to_nine_am'.$key.'" />
					8:00 AM - 9:00 AM</label>
					
					<label>
					<input type="checkbox" name="edit_hours[]" value="nine_to_ten_am" id="nine_to_ten_am'.$key.'" />
					9:00 AM - 10:00 AM</label>
					
					<label>
					<input type="checkbox" name="edit_hours[]" value="ten_to_eleven_am" id="ten_to_eleven_am'.$key.'" />
					10:00 AM - 11:00 AM</label>
					
					<label>
					<input type="checkbox" name="edit_hours[]" value="eleven_to_twelve_pm" id="eleven_to_twelve_pm'.$key.'" />
					11:00 AM - 12:00 PM</label>
					
					<label>
					<input type="checkbox" name="edit_hours[]" value="twelve_to_one_pm" id="twelve_to_one_pm'.$key.'" />
					12:00 PM - 1:00 PM</label>
					
					<label>
					<input type="checkbox" name="edit_hours[]" value="one_to_two_pm" id="one_to_two_pm'.$key.'" />
					1:00 PM - 2:00 PM</label>
					
					<label>
					<input type="checkbox" name="edit_hours[]" value="two_to_three_pm" id="two_to_three_pm'.$key.'" />
					2:00 PM - 3:00 PM</label>
					
					<label>
					<input type="checkbox" name="edit_hours[]" value="three_to_four_pm" id="three_to_four_pm'.$key.'" />
					3:00 PM - 4:00 PM</label>
					<br/>
					
					<input type="submit" name="submit_edited_availability'.$key.'" id="submit_new_availability'.$key.'" value="Submit" onclick="customer_availability_update(\'eight_to_nine_am'.$key.'\', \'nine_to_ten_am'.$key.'\', \'ten_to_eleven_am'.$key.'\', \'eleven_to_twelve_pm'.$key.'\', \'twelve_to_one_pm'.$key.'\', \'one_to_two_pm'.$key.'\', \'two_to_three_pm'.$key.'\', \'three_to_four_pm'.$key.'\', \'timeUL'.$key.'\', \'errorMessageDiv'.$key.'\' );" />
					
					<div id="errorMessageDiv'.$key.'"></div>
				';
			// onclick="customer_availability_update_'.$key.'();"
			$output .= "</div>";
			$output .= "<br/>";
			
		}
		return $output;
	}

	function displayDeclinedAppointmentsCus() {
		global $database;
		global $customerID;
		global $session;
		
		// Instantiate the Customers_Availability class
		$cus_Appointments = new Customers_Appointment();
		// Get the date for the week using the method for it
		$weekDates = $cus_Appointments->weekDatesFromToday();
		// Get the current day date of the week
		$currentDateOfWeek = $weekDates[0];
		// Get the next 6 days date of the week
		$next6DateOfWeek = $weekDates[6];
		
		$output = "";
		/* if ($session->is_user_logged_in()) {
			$appointment_owner = $_SESSION['user_full_name'];
			$user_id = $_SESSION['user_id'];
			// The query code can also be included here. In this case, you will use the user_id and the scheduled_user saved in the database to check for the user as opposed to using the customer's name.
			$sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_user={$user_id} AND customer_decision='declined'";
		} 
		else */ if (($session->is_customer_logged_in())) {
			$appointment_owner = $_SESSION['customer_full_name'];
			$customer_id = $_SESSION['customer_id'];
			// The query code can also be included here. In this case, you will use the customer_id and the scheduled_customer saved in the database to check for the user as opposed to using the customer's name.
			$sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_customer={$customer_id} AND customer_decision='declined'";
		} else {
			// If the user or customer is not logged in, inform them and don't run the remaining code.
			$output .= "<div class='setUserAppointment' >";
			$output .= "<p>You are not logged in. Please login to see your appointments.</p>";
			$output .= "</div>";
			
			return $output;
		}
		
		// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND appointment_owner='{$appointment_owner}' AND customer_decision='neutral'";
		$result_set = $database->query($sql); 

		$count = 0; 
		while($row = mysqli_fetch_assoc($result_set)){
			$appointmentId[$count] = $row["id"];
			$appointmentDates[$count] = $row["appointment_date"];
			// $appointmentHours[$count] = $row["hours"];
			// $appointmentMessage[$count] = $row["appointment_message"];
			// Get the phone number and name of the technician
			// $customerDetails = Customer::find_by_id($row["customers_id"]);
			$customerNumber[$count] = $row["customer_number"];
			$customerName[$count] = $row["customer_name"];
			$declineMessage[$count] = $row["cus_decline_message"];
			
			$count++; 
		}
		// echo "The number of record is: ".$count;
		// Concatenate string of HTML to output the appointments requested up
		if ($count > 0) {
			// Inform the customer the number of appointments accepted. The if condition is used to check for one or many conditions.
			if ($count > 1) {
				$output .= "<div class='setUserAppointment' id='appointmentInfo' >You have ".$count." appointments declined.</div>";
			} elseif ($count == 1) {
				$output .= "<div class='setUserAppointment' id='appointmentInfo' >You have ".$count." appointment declined.</div>";
			}
			
			foreach ($appointmentDates as $key => $record) {
				$output .= "<div class='setUserAppointment' id='userAppointCard".$key."'>";
				
				// The appointment id from the database saved in a hidden paragraph tag
				$output .= "<p style='display:none;' id='databaseId".$key."' >".$appointmentId[$key]."</p>";
				
				// The div informing the user of the canceled appointment.
				$output .= "<div class='userAppointDetails' id='userBtnCtrl".$key."' style='color:red;' >This appointment has been declined.</div>";
				
				// The title of owner of the appointment div
				$output .= "<div class='userAppointDetails' id='nameDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Name of technician: </p>";
				$output .= "<p class='labelValue' id='customerName".$key."' >".$customerName[$key]."</p>";
				$output .= "</div>";
				
				// The phone number of the person scheduling the appointment
				$output .= "<div class='userAppointDetails' id='numberDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Phone number of technician: </p>";
				$output .= "<p class='labelValue' id='customerNumber".$key."' >".$customerNumber[$key]."</p>";
				$output .= "</div>";
				
				// The div containing the day for the appointment
				$output .= "<div class='userAppointDetails' id='userAptDayDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Day of appointment: </p>";
				$output .= "<p class='labelValue' id='userAptDay".$key."' >".date_to_weekday($appointmentDates[$key]).", ".date_to_text($appointmentDates[$key])."</p>";
				$output .= "</div>";
				
				// The div containing the reason for the technician declining the appointment
				$output .= "<div class='userAppointDetails' id='customerDeclineMessageDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Reason for declining appointment: </p>";
				$output .= "<p class='labelValue' id='customerDeclineMessage".$key."' >".$declineMessage[$key]."</p>";
				$output .= "</div>";
				
				// This is a dummy div to hold the appearance of the contents.
				$output .= "<div class='userAppointDetails' id='userBtnCtrl".$key."' ></div>";
				
				$output .= "</div>";
			}
		} else {
			$output .= "<div class='setUserAppointment' >";
			$output .= "<p>You have no appointment request declined.</p>";
			$output .= "</div>";
		}
		
		return $output;
	}

	function displayCanceledAppointmentsCus() {
		global $database;
		global $customerID;
		global $session;
		
		// Instantiate the Customers_Availability class
		$cus_Appointments = new Customers_Appointment();
		// Get the date for the week using the method for it
		$weekDates = $cus_Appointments->weekDatesFromToday();
		// Get the current day date of the week
		$currentDateOfWeek = $weekDates[0];
		// Get the next 6 days date of the week
		$next6DateOfWeek = $weekDates[6];
		
		$output = "";
		/* if ($session->is_user_logged_in()) {
			$appointment_owner = $_SESSION['user_full_name'];
			$user_id = $_SESSION['user_id'];
			// The query code can also be included here. In this case, you will use the user_id and the scheduled_user saved in the database to check for the user as opposed to using the customer's name.
			$sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_user={$user_id} AND customer_decision='canceled'";
		} 
		else */ if (($session->is_customer_logged_in())) {
			$appointment_owner = $_SESSION['customer_full_name'];
			$customer_id = $_SESSION['customer_id'];
			// The query code can also be included here. In this case, you will use the customer_id and the scheduled_customer saved in the database to check for the user as opposed to using the customer's name.
			$sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_customer={$customer_id} AND customer_decision='canceled'";
		} else {
			// If the user or customer is not logged in, inform them and don't run the remaining code.
			$output .= "<div class='setUserAppointment' >";
			$output .= "<p>You are not logged in. Please login to see your appointments.</p>";
			$output .= "</div>";
			
			return $output;
		}
		
		// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND appointment_owner='{$appointment_owner}' AND customer_decision='neutral'";
		$result_set = $database->query($sql); 

		$count = 0; 
		while($row = mysqli_fetch_assoc($result_set)){
			$appointmentId[$count] = $row["id"];
			$appointmentDates[$count] = $row["appointment_date"];
			// $appointmentHours[$count] = $row["hours"];
			// $appointmentMessage[$count] = $row["appointment_message"];
			// Get the phone number and name of the technician
			// $customerDetails = Customer::find_by_id($row["customers_id"]);
			$customerNumber[$count] = $row["customer_number"];
			$customerName[$count] = $row["customer_name"];
			$cancelMessage[$count] = $row["cus_cancel_message"];
			
			$count++; 
		}
		// echo "The number of record is: ".$count;
		// Concatenate string of HTML to output the appointments requested up
		if ($count > 0) {
			// Inform the customer the number of appointments accepted. The if condition is used to check for one or many conditions.
			if ($count > 1) {
				$output .= "<div class='setUserAppointment' id='appointmentInfo' >You have ".$count." appointments canceled.</div>";
			} elseif ($count == 1) {
				$output .= "<div class='setUserAppointment' id='appointmentInfo' >You have ".$count." appointment canceled.</div>";
			}
			
			foreach ($appointmentDates as $key => $record) {
				$output .= "<div class='setUserAppointment' id='userAppointCard".$key."'>";
				
				// The appointment id from the database saved in a hidden paragraph tag
				$output .= "<p style='display:none;' id='databaseId".$key."' >".$appointmentId[$key]."</p>";
				
				// The div informing the user of the canceled appointment.
				$output .= "<div class='userAppointDetails' id='userBtnCtrl".$key."' style='color:orange;' >This appointment has been canceled.</div>";
				
				// The title of owner of the appointment div
				$output .= "<div class='userAppointDetails' id='nameDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Name of technician: </p>";
				$output .= "<p class='labelValue' id='customerName".$key."' >".$customerName[$key]."</p>";
				$output .= "</div>";
				
				// The phone number of the person scheduling the appointment
				$output .= "<div class='userAppointDetails' id='numberDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Phone number of technician: </p>";
				$output .= "<p class='labelValue' id='customerNumber".$key."' >".$customerNumber[$key]."</p>";
				$output .= "</div>";
				
				// The div containing the day for the appointment
				$output .= "<div class='userAppointDetails' id='userAptDayDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Day of appointment: </p>";
				$output .= "<p class='labelValue' id='userAptDay".$key."' >".date_to_weekday($appointmentDates[$key]).", ".date_to_text($appointmentDates[$key])."</p>";
				$output .= "</div>";
				
				// The div containing the reason for canceling appointment
				$output .= "<div class='userAppointDetails' id='customerCancelMessageDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Reason for canceling appointment: </p>";
				$output .= "<p class='labelValue' id='customerCancelMessage".$key."' >".$cancelMessage[$key]."</p>";
				$output .= "</div>";
				
				// This is a dummy div to hold the appearance of the contents.
				$output .= "<div class='userAppointDetails' id='userBtnCtrl".$key."' ></div>";
				
				$output .= "</div>";
			}
		} else {
			$output .= "<div class='setUserAppointment' >";
			$output .= "<p>You have no appointment request canceled.</p>";
			$output .= "</div>";
		}
		
		return $output;
	}

	function displayCustomerBookings() {
		global $database;
		global $customerID;
		
		// Instantiate the Customers_Availability class
		$cus_Appointments = new Customers_Appointment();
		// Get the date for the week using the method for it
		$weekDates = $cus_Appointments->weekDatesFromToday();
		// Get the current day date of the week
		$currentDateOfWeek = $weekDates[0];
		// Get the next 6 days date of the week
		$next6DateOfWeek = $weekDates[6];
		
		$sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND customers_id = {$_SESSION['customer_id']} AND customer_decision='neutral'";
		$result_set = $database->query($sql); 

		$count = 0; 
		// $customerAvailability = array();
		// $customerRecord = array();
		while($row = mysqli_fetch_assoc($result_set)){
			$appointmentId[$count] = $row["id"];		
			$appointmentDates[$count] = $row["appointment_date"];
			$appointmentHours[$count] = $row["hours"];
			$appointmentMessage[$count] = $row["appointment_message"];
			$appointmentOwner[$count] = $row["appointment_owner"];
			$appointerNumber[$count] = $row["appointer_number"];
			$count++; 
		}
		// echo "The number of record is: ".$count;
		// Concatenate string of HTML to output the appointments requested up
		$output = "";
		if ($count > 0) {
			// Inform the customer the number of appointments accepted.
			if ($count > 1) {
				$output .= "<div class='setUserAppointment' id='eventMessage' >You have ".$count." appointments request from your customers.</div>";
			} elseif ($count == 1) {
				$output .= "<div class='setUserAppointment' id='eventMessage' >You have ".$count." appointment request from a customer.</div>";
			}
				
			foreach ($appointmentDates as $key => $record) {
				$output .= "<div class='setUserAppointment' id='userSchDetails".$key."'>";
				
				// The appointment id from the database saved in a hidden paragraph tag
				$output .= "<p style='display:none;' id='aptDBId".$key."' >".$appointmentId[$key]."</p>";
				
				// The title of owner of the appointment div
				$output .= "<div class='userAppointDetails' id='userTitleDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Name of customer: </p>";
				$output .= "<p class='labelValue' id='userTitle".$key."' >".$appointmentOwner[$key]."</p>";
				$output .= "</div>";
				
				// The phone number of the person scheduling the appointment
				$output .= "<div class='userAppointDetails' id='userPhoneDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Phone number of customer: </p>";
				$output .= "<p class='labelValue' id='userPhone".$key."' >".$appointerNumber[$key]."</p>";
				$output .= "</div>";
				
				// The div containing the day for the appointment
				$output .= "<div class='userAppointDetails' id='userAptDayDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Day of appointment: </p>";
				$output .= "<p class='labelValue' id='userAptDay".$key."' >".date_to_weekday($appointmentDates[$key]).", ".date_to_text($appointmentDates[$key])."</p>";
				$output .= "</div>";
				
				// The div containing the time for the appointment
				$output .= "<div class='userAppointDetails' id='userTimeDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Time of appointment: </p>";
				$output .= "<p class='labelValue' id='userTime".$key."' >".$appointmentHours[$key]."</p>";
				$output .= "</div>";
				
				// The div containing the reason for the appointment
				$output .= "<div class='userAppointDetails' id='userReasonDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Reason for appointment: </p>";
				$output .= "<p class='labelValue' id='userReason".$key."' >".$appointmentMessage[$key]."</p>";
				$output .= "</div>";
				
				// The div containing the accept and decline buttons for the appointment
				$output .= "<div class='userAppointDetails' id='userBtnCtrl".$key."' >";
				// Show the accept button
				$output .= "<input type='button' value='Accept' id='acceptButton".$key."' onclick='appointmentAccepted(this.id);' />";
				// Show the decline button
				// $output .= "<input type='button' value='Decline' id='declineButton".$key."' onclick='appointmentDeclined(this.id);' style='margin-left:10px;'/>";
				
				// Edit from here
				$output .= "<input type='button' value='Decline' id='declineBtn".$key."' onclick='openTextareaCusDecline(this.id);' style='margin-right:10px;' />";
				$output .= "<input type='button' value='Hide' id='hideDeclineBtn".$key."' onclick='hideTextareaCusDecline(this.id);' style='display:none;'/>";
				$output .= "</div>";
				
				// Div containing form for textarea and submit button to provide reason for declining.
				$output .= "<div class='userAppointDetails' id='decliningReasonDiv".$key."' style='display:none;' >";
				$output .= "<form action='#' id='declineReasonForm".$key."' name='declineReasonForm".$key."'>";
				$output .= "<p style='margin:0px;' >Please provide a reason for declining this appointment. </p>";
				$output .= "<textarea id='decliningReason".$key."' name='decliningReason".$key."' cols='50' rows='4'></textarea>";
				$output .= "<br/>";
				// $output .= "<input type='button' value='Submit' id='submitMessageBtn".$key."' onclick='appointmentCustomerCanceled(this.id);' />";
				$output .= "<input type='button' value='Submit' id='submitDeclineBtn".$key."' onclick='appointmentDeclined(this.id);' />";
				$output .= "</form>";
				// the id was changed from 'errorInformDiv'
				$output .= "<div class='userAppointDetails' id='errorDeliverDiv".$key."' ></div>";
				$output .= "</div>";
				
				$output .= "</div>";
			}
		} else {
			$output .= "<div class='setUserAppointment' >";
			$output .= "<p>You do not have any appointment available.</p>";
			$output .= "</div>";
		}
		
		return $output;
	}

	function displayAcceptedAppointments() {
		global $database;
		global $customerID;
		
		// Instantiate the Customers_Availability class
		$cus_Appointments = new Customers_Appointment();
		// Get the date for the week using the method for it
		$weekDates = $cus_Appointments->weekDatesFromToday();
		// Get the current day date of the week
		$currentDateOfWeek = $weekDates[0];
		// Get the next 6 days date of the week
		$next6DateOfWeek = $weekDates[6];
		
		$sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND customers_id = {$_SESSION['customer_id']} AND customer_decision='accepted'";
		$result_set = $database->query($sql); 

		$count = 0; 
		while($row = mysqli_fetch_assoc($result_set)){
			$appointmentId[$count] = $row["id"];
			$appointmentDates[$count] = $row["appointment_date"];
			$appointmentHours[$count] = $row["hours"];
			$appointmentMessage[$count] = $row["appointment_message"];
			$appointmentOwner[$count] = $row["appointment_owner"];
			$appointerNumber[$count] = $row["appointer_number"];
			// $customerNumber[$count] = $row["customer_number"];
			$count++; 
		}
		// echo "The number of record is: ".$count;
		// Concatenate string of HTML to output the appointments requested up
		$output = "";
		if ($count > 0) {
			// Inform the customer the number of appointments accepted.
			if ($count > 1) {
				$output .= "<div class='setUserAppointment' id='appointmentInfo' >You have ".$count." appointments scheduled with your customers.</div>";
			} elseif ($count == 1) {
				$output .= "<div class='setUserAppointment' id='appointmentInfo' >You have ".$count." appointment scheduled with a customer.</div>";
			}
			
			foreach ($appointmentDates as $key => $record) {
				$output .= "<div class='setUserAppointment' id='userSchCard".$key."'>";
				
				// The appointment id from the database saved in a hidden paragraph tag
				$output .= "<p style='display:none;' id='aptTableDBId".$key."' >".$appointmentId[$key]."</p>";
				
				// The title of owner of the appointment div
				$output .= "<div class='userAppointDetails' id='userNameDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Name of customer: </p>";
				$output .= "<p class='labelValue' id='userName".$key."' >".$appointmentOwner[$key]."</p>";
				$output .= "</div>";
				
				// The phone number of the person scheduling the appointment
				$output .= "<div class='userAppointDetails' id='userNumberDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Phone number of customer: </p>";
				$output .= "<p class='labelValue' id='userNumber".$key."' >".$appointerNumber[$key]."</p>";
				$output .= "</div>";
				
				// The div containing the day for the appointment
				$output .= "<div class='userAppointDetails' id='userSetAptDayDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Day of appointment: </p>";
				$output .= "<p class='labelValue' id='userSetAptDay".$key."' >".date_to_weekday($appointmentDates[$key]).", ".date_to_text($appointmentDates[$key])."</p>";
				$output .= "</div>";
				
				// The div containing the time for the appointment
				$output .= "<div class='userAppointDetails' id='userTimeHoursDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Time of appointment: </p>";
				$output .= "<p class='labelValue' id='userTimeHours".$key."' >".$appointmentHours[$key]."</p>";
				// $output .= $appointmentHours[$key];
				$output .= "</div>";
				
				// The div containing the reason for the appointment
				$output .= "<div class='userAppointDetails' id='userComplainDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Reason for appointment: </p>";
				$output .= "<p class='labelValue' id='userComplain".$key."' >".$appointmentMessage[$key]."</p>";
				// $output .= $appointmentMessage[$key];
				$output .= "</div>";
				
				
				// The div containing the cancel button for the appointment
				$output .= "<div class='userAppointDetails' id='userBtnCtrl".$key."' >";
				// Show the cancel button 
				$output .= "<input type='button' value='Cancel' id='cancelButton".$key."' onclick='openTextarea(this.id);' style='margin-right:10px;'/>";
				$output .= "<input type='button' value='Hide' id='hideButton".$key."' onclick='hideTextarea(this.id);' style='display:none;'/>";
				$output .= "</div>";
				
				
				// Div containing form for textarea and submit button
				$output .= "<div class='userAppointDetails' id='reasonForCancelDiv".$key."' style='display:none;' >";
				$output .= "<form action='#' id='cancelForm".$key."' name='cancelForm".$key."'>";
				$output .= "<p style='margin:0px;' >Please provide a reason for canceling this created appointment. </p>";
				$output .= "<textarea id='reasonForCancel".$key."' name='reasonForCancel".$key."' cols='55' rows='4'></textarea>";
				$output .= "<br/>";
				$output .= "<input type='button' value='Submit' id='submitReasonBtn".$key."' onclick='appointmentCanceled(this.id);' />";
				$output .= "</form>";
				$output .= "<div class='userAppointDetails' id='errorReportDiv".$key."' ></div>";
				$output .= "</div>";
				
				$output .= "</div>";
			}
		} else {
			$output .= "<div class='setUserAppointment' >";
			$output .= "<p>You have not accepted any appointment yet.</p>";
			$output .= "</div>";
		}
		
		return $output;
	}

	function displayRequestedAppointmentsCus() {
		global $database;
		global $customerID;
		global $session;
		
		// Instantiate the Customers_Availability class
		$cus_Appointments = new Customers_Appointment();
		// Get the date for the week using the method for it
		$weekDates = $cus_Appointments->weekDatesFromToday();
		// Get the current day date of the week
		$currentDateOfWeek = $weekDates[0];
		// Get the next 6 days date of the week
		$next6DateOfWeek = $weekDates[6];
		
		// Concatenate string of HTML to output the appointments requested up
		$output = "";
		/* if ($session->is_user_logged_in()) {
			$appointment_owner = $_SESSION['user_full_name']; 
			// $user_id = $_SESSION['user_id'];
			// The query code can also be included here. In this case, you will use the user_id and the scheduled_user saved in the database to check for the user as opposed to using the customer's name.
			// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_user={$user_id} AND customer_decision='neutral'";
		} else */
		if (($session->is_customer_logged_in())) {
			$appointment_owner = $_SESSION['customer_full_name'];
			// $customer_id = $_SESSION['customer_id'];
			// The query code can also be included here. In this case, you will use the customer_id and the scheduled_customer saved in the database to check for the user as opposed to using the customer's name.
			// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_customer={$customer_id} AND customer_decision='neutral'";
		} else {
			// If the user or customer is not logged in, inform them and don't run the remaining code.
			$output .= "<div class='setUserAppointment' >";
			$output .= "<p>You are not logged in. Please login to see your appointments.</p>";
			$output .= "</div>";
			
			return $output;
		}
		
		$sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND appointment_owner='{$appointment_owner}' AND customer_decision='neutral'";
		$result_set = $database->query($sql); 

		$count = 0; 
		while($row = mysqli_fetch_assoc($result_set)){
			$appointmentId[$count] = $row["id"];
			$appointmentDates[$count] = $row["appointment_date"];
			$appointmentHours[$count] = $row["hours"];
			$appointmentMessage[$count] = $row["appointment_message"];
			// Get the phone number and name of the technician
			// $customerDetails = Customer::find_by_id($row["customers_id"]);
			$customerNumber[$count] = $row["customer_number"];
			$customerName[$count] = $row["customer_name"];
			
			$count++; 
		}
		// echo "The number of record is: ".$count;
		
		if ($count > 0) {
			// Inform the customer the number of appointments accepted.
			if ($count > 1) {
				$output .= "<div class='setUserAppointment' id='appointmentInfo' >You have ".$count." appointments waiting for confirmation.</div>";
			} elseif ($count == 1) {
				$output .= "<div class='setUserAppointment' id='appointmentInfo' >You have ".$count." appointment waiting for confirmation.</div>";
			}
			
			foreach ($appointmentDates as $key => $record) {
				$output .= "<div class='setUserAppointment' id='userAptCard".$key."'>";
				
				// The appointment id from the database saved in a hidden paragraph tag
				$output .= "<p style='display:none;' id='appointTableDBId".$key."' >".$appointmentId[$key]."</p>";
				
				// The title of owner of the appointment div
				$output .= "<div class='userAppointDetails' id='customerFullNameDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Name of technician: </p>";
				$output .= "<p class='labelValue' id='user_Name".$key."' >".$customerName[$key]."</p>";
				$output .= "</div>";
				
				// The phone number of the person scheduling the appointment
				$output .= "<div class='userAppointDetails' id='userPhoneNumDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Phone number of technician: </p>";
				$output .= "<p class='labelValue' id='userPhoneNum".$key."' >".$customerNumber[$key]."</p>";
				$output .= "</div>";
				
				// The div containing the day for the appointment
				$output .= "<div class='userAppointDetails' id='userSetAppointDayDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Day of appointment: </p>";
				$output .= "<p class='labelValue' id='userAppointDay".$key."' >".date_to_weekday($appointmentDates[$key]).", ".date_to_text($appointmentDates[$key])."</p>";
				$output .= "</div>";
				
				// The div containing the time for the appointment
				$output .= "<div class='userAppointDetails' id='userTimeSchDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Time of appointment: </p>";
				$output .= "<p class='labelValue' id='userTimeHourSch".$key."' >".$appointmentHours[$key]."</p>";
				// $output .= $appointmentHours[$key];
				$output .= "</div>";
				
				// The div containing the reason for the appointment
				$output .= "<div class='userAppointDetails' id='userNoteDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Reason for appointment: </p>";
				$output .= "<p class='labelValue' id='userInfo".$key."' >".$appointmentMessage[$key]."</p>";
				// $output .= $appointmentMessage[$key];
				$output .= "</div>";
				
				
				// The div containing the cancel button for the appointment
				$output .= "<div class='userAppointDetails' id='userBtnCtrlDiv".$key."' >";
				/* // Show the cancel button
				$output .= "<input type='button' value='Cancel' id='cancelButton".$key."' onclick='appointmentCanceled(this.id);' />"; */
				$output .= "</div>";
				
				
				$output .= "</div>";
			}
		} else {
			$output .= "<div class='setUserAppointment' >";
			$output .= "<p>You have no appointment request waiting for confirmation.</p>";
			$output .= "</div>";
		}
		
		return $output;
	}

	function displayConfirmedAppointmentsCus() {
		global $database;
		global $customerID;
		global $session;
		
		// Instantiate the Customers_Availability class
		$cus_Appointments = new Customers_Appointment();
		// Get the date for the week using the method for it
		$weekDates = $cus_Appointments->weekDatesFromToday();
		// Get the current day date of the week
		$currentDateOfWeek = $weekDates[0];
		// Get the next 6 days date of the week
		$next6DateOfWeek = $weekDates[6];
		
		// Concatenate string of HTML to output the appointments requested up
		$output = "";
		/* if ($session->is_user_logged_in()) {
			$appointment_owner = $_SESSION['user_full_name']; 
			// $user_id = $_SESSION['user_id'];
			// The query code can also be included here. In this case, you will use the user_id and the scheduled_user saved in the database to check for the user as opposed to using the customer's name.
			// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_user={$user_id} AND customer_decision='accepted'";
		} else */
		if (($session->is_customer_logged_in())) {
			$appointment_owner = $_SESSION['customer_full_name'];
			// $customer_id = $_SESSION['customer_id'];
			// The query code can also be included here. In this case, you will use the customer_id and the scheduled_customer saved in the database to check for the user as opposed to using the customer's name.
			// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_customer={$customer_id} AND customer_decision='accepted'";
		} else {
			$output .= "<div class='setUserAppointment' >";
			$output .= "<p>You are not logged in. Please login to see your appointments.</p>";
			$output .= "</div>";
			
			return $output;
		}
		
		$sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND appointment_owner='{$appointment_owner}' AND customer_decision='accepted'";
		$result_set = $database->query($sql); 

		$count = 0; 
		while($row = mysqli_fetch_assoc($result_set)){
			$appointmentId[$count] = $row["id"];
			$appointmentDates[$count] = $row["appointment_date"];
			$appointmentHours[$count] = $row["hours"];
			$appointmentMessage[$count] = $row["appointment_message"];
			// Get the phone number and name of the technician
			// $customerDetails = Customer::find_by_id($row["customers_id"]);
			$customerNumber[$count] = $row["customer_number"];
			$customerName[$count] = $row["customer_name"];
			$count++; 
		}
		// echo "The number of record is: ".$count;
		
		if ($count > 0) {
			// Inform the customer the number of appointments accepted.
			if ($count > 1) {
				$output .= "<div class='setUserAppointment' id='appointmentInfo' >You have ".$count." appointments confirmed.</div>";
			} elseif ($count == 1) {
				$output .= "<div class='setUserAppointment' id='appointmentInfo' >You have ".$count." appointment confirmed.</div>";
			}
			
			foreach ($appointmentDates as $key => $record) {
				$output .= "<div class='setUserAppointment' id='userAptInfo".$key."'>";
				
				// The appointment id from the database saved in a hidden paragraph tag
				$output .= "<p style='display:none;' id='aptDBTableId".$key."' >".$appointmentId[$key]."</p>";
				
				// The title of owner of the appointment div
				$output .= "<div class='userAppointDetails' id='technicianNameDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Name of technician: </p>";
				$output .= "<p class='labelValue' id='technicianName".$key."' >".$customerName[$key]."</p>";
				$output .= "</div>";
				
				// The phone number of the person scheduling the appointment
				$output .= "<div class='userAppointDetails' id='technicianNumberDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Phone number of technician: </p>";
				$output .= "<p class='labelValue' id='technicianNumber".$key."' >".$customerNumber[$key]."</p>";
				$output .= "</div>";
				
				// The div containing the day for the appointment
				$output .= "<div class='userAppointDetails' id='aptDayDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Day of appointment: </p>";
				$output .= "<p class='labelValue' id='aptDay".$key."' >".date_to_weekday($appointmentDates[$key]).", ".date_to_text($appointmentDates[$key])."</p>";
				$output .= "</div>";
				
				// The div containing the time for the appointment
				$output .= "<div class='userAppointDetails' id='timeHoursDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Time of appointment: </p>";
				$output .= "<p class='labelValue' id='timeHours".$key."' >".$appointmentHours[$key]."</p>";
				// $output .= $appointmentHours[$key];
				$output .= "</div>";
				
				// The div containing the reason for the appointment
				$output .= "<div class='userAppointDetails' id='userAptMessageDiv".$key."' >";
				$output .= "<p class='labelInfoStyle' >Reason for appointment: </p>";
				$output .= "<p class='labelValue' id='userAptMessage".$key."' >".$appointmentMessage[$key]."</p>";
				// $output .= $appointmentMessage[$key];
				$output .= "</div>";
				
				// The div containing the cancel button for the appointment
				$output .= "<div class='userAppointDetails' id='cancelBtnDiv".$key."' >";
				// Show the cancel button
				$output .= "<input type='button' value='Cancel' id='cancelBtn".$key."' onclick='openTextareaCustomer(this.id);' style='margin-right:10px;' />";
				$output .= "<input type='button' value='Hide' id='hideBtn".$key."' onclick='hideTextareaCustomer(this.id);' style='display:none;'/>";
				$output .= "</div>";
				
				// Div containing form for textarea and submit button
				$output .= "<div class='userAppointDetails' id='cancelingReasonDiv".$key."' style='display:none;' >";
				$output .= "<form action='#' id='cancelReasonForm".$key."' name='cancelReasonForm".$key."'>";
				$output .= "<p style='margin:0px;' >Please provide a reason for canceling this created appointment. </p>";
				$output .= "<textarea id='cancelingReason".$key."' name='cancelingReason".$key."' cols='55' rows='4'></textarea>";
				$output .= "<br/>";
				$output .= "<input type='button' value='Submit' id='submitMessageBtn".$key."' onclick='appointmentCustomerCanceled(this.id);' />";
				$output .= "</form>";
				$output .= "<div class='userAppointDetails' id='errorInformDiv".$key."' ></div>";
				$output .= "</div>";
				
				$output .= "</div>";
			}
		} else {
			$output .= "<div class='setUserAppointment' >";
			$output .= "<p>You have no appointment confirmed yet.</p>";
			$output .= "</div>";
		}
		
		return $output;
	}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cus_full_name." Profile"; ?></title>
<style type="text/css">
</style>
<link href="../../HomePageStyles.css" rel="stylesheet" type="text/css" />
<link href="../stylesheets/customerEditPage2.css" rel="stylesheet" type="text/css" />
<link href="../stylesheets/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<link href="../stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />

<link href="../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />

<script src="../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>

<script type="text/javascript" src="../javascripts/jquery.js"></script>
<script type="text/javascript" src="../javascripts/jquery-ui.min.js"></script>

</head>

<body>
<div id="topContainer">
	<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
	<?php include_layout_template('public_header.php'); ?>
</div>
<div id="container">
  <!-- Begining of Main Section -->  
  <div id="mainTechEditPage">
	<?php if (!empty($message)) {echo $message;} ?><br/>
	<?php echo output_message($sessionMessage); ?><br/>
	<?php echo $validate->form_errors($validate->errors); ?>
    <h2>Edit your profile</h2>
    <div class="TabbedPanelDiv">
      <!-- Beginning of tabbed panel div -->
      
      <div id="TabbedPanels1" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Business Information</li>
          <li class="TabbedPanelsTab" tabindex="0">Photo Gallery</li>
          <li class="TabbedPanelsTab" tabindex="0">Comments</li>
          <li class="TabbedPanelsTab" tabindex="0">Set Your Availability</li>
          <li class="TabbedPanelsTab" tabindex="0">Customers Appointments</li>
          <li class="TabbedPanelsTab" tabindex="0">Your Appointments</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
          <!-- Beginning of collapsible panel div -->
          
           <div class="CollapsiblePanelsDiv">
				<div id="CollapsiblePanel1" class="CollapsiblePanel">
				  <div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">First name: </label>
					<?php  
						if(isset($cus_first_name) AND !empty($cus_first_name)){ 
							echo $cus_first_name; 
						} else { 
							echo "&nbsp";
						} 
					?>
				  </div>
				  <div class="CollapsiblePanelContent">
					<form action="" method="post" enctype="application/x-www-form-urlencoded" name="first_name_form" class="customer_label">
					  <label for="edit_last_name">Edit first name</label>
						<input name="edit_first_name" type="text" id="edit_first_name" size="60" maxlength="60" />
					  <input type="submit" name="submit_first_name" id="submit_first_name" value="Submit" />
					</form>
				  </div>
				</div>
			  <div id="CollapsiblePanel2" class="CollapsiblePanel">
				  <div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">Last name: </label>
					<?php 
					if(isset($cus_last_name) AND !empty($cus_last_name)){ 
						echo $cus_last_name; 
					} else { 
						echo "&nbsp";
					} 
					?>
				  </div>
				  <div class="CollapsiblePanelContent">
					<form class="customer_label" name="last_name_form" method="post" action="" enctype="application/x-www-form-urlencoded">
					  <label for="edit_last_name">Edit last name</label>
					  <input name="edit_last_name" type="text" id="edit_last_name" size="60" maxlength="60" />
					  <input type="submit" name="submit_last_name" id="submit_last_name" value="Submit" />
					</form>
				  </div>
			  </div>
			  <div id="CollapsiblePanel3" class="CollapsiblePanel">
				<div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">Username: </label>
					<?php 
						if(isset($cus_username) AND !empty($cus_username)){ 
							echo $cus_username; 
						} else { 
							echo "&nbsp";
						} 
					?>
				</div>
				<div class="CollapsiblePanelContent">
					<form class="customer_label" name="username_form" action="" method="post" enctype="application/x-www-form-urlencoded">
						<label for="edit_username">Edit username</label>
						<input name="edit_username" id="edit_username" type="text" size="60" maxlength="60" />
						<input type="submit" name="submit_username" id="submit_username" value="Submit" />
					</form>
				</div>
			  </div>
			  <div id="CollapsiblePanel4" class="CollapsiblePanel">
				<!-- <label class="labelTitle">Password: </label> -->
				<div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">Password: </label>
					<?php echo "&nbsp"; ?>
				</div>
				<div class="CollapsiblePanelContent">
				  <form class="customer_label" name="form1" method="post" action="">
					  <label for="edit_password">Edit password</label>
					  <input name="edit_password" type="password" id="edit_password" size="60" maxlength="60" />
					  <br/>
					  <label for="password">Confirm password</label>
					  <input name="confirm_password" type="password" id="confirm_password" size="60" maxlength="60" />
					  <input type="submit" name="submit_password" id="submit_password" value="Submit" />
				  </form>
				</div>
			  </div>
			  <div id="CollapsiblePanel5" class="CollapsiblePanel">
				<div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">Business name: </label>
					<?php 
						if(isset($cus_business_title) AND !empty($cus_business_title)){ 
							echo $cus_business_title; 
						} else { 
							echo "&nbsp";
						} 
					?>
				</div>
				<div class="CollapsiblePanelContent">
				  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="business_name_form" class="customer_label">
					<label for="edit_business_name">Edit business name</label>
					<input name="edit_business_name" type="text" id="edit_business_name" size="60" maxlength="60" />
					<input type="submit" name="submit_business_name" id="submit_business_name" value="Submit" />
				  </form>
				</div>
			  </div>
			  <!-- Begining of Collapsible Panel 17 -->
              <div id="CollapsiblePanel17" class="CollapsiblePanel">
	              <div class="CollapsiblePanelTab" tabindex="0"  >
                	<label class="labelTitle">Business description: </label>
					<?php 
						if(isset($cus_business_description) AND !empty($cus_business_description)){ 
							echo $cus_business_description; 
						} else { 
							echo "&nbsp";
						}
						// echo "Provide a description of your business, so customers will have an insight about you."; // echo "&nbsp";?>
                  </div>
    	          <div class="CollapsiblePanelContent">
    	            <form method="post" action="" enctype="application/x-www-form-urlencoded" name="business_description_form" id="business_description_form">
    	              <label for="business_description">Edit business description</label> 
					  <textarea name="business_description" id="business_description" cols="65" rows="2"></textarea>
					  <input type="submit" name="submit_business_description" id="submit_business_description" value="Submit" />
  	                </form>
    	          </div>
              </div>
              <!-- End of Collapsible Panel 17 -->
			  <div id="CollapsiblePanel6" class="CollapsiblePanel">
				<div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">Email: </label>
					<?php 
						if(isset($cus_email) AND !empty($cus_email)){ 
							echo $cus_email; 
						} else { 
							echo "&nbsp";
						} 
					?>
				</div>
				<div class="CollapsiblePanelContent">
				  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="business_email_form" class="customer_label">
					<label for="edit_business_email">Edit email</label>
					<input name="edit_business_email" type="text" id="edit_business_email" size="60" maxlength="60" />
					<input type="submit" name="submit_business_email" id="submit_business_email" value="Submit" />
				  </form>
				</div>
			  </div>
			  <div id="CollapsiblePanel7" class="CollapsiblePanel">
				<div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">Phone number: </label>
					<?php 
						if(isset($cus_phone_number) AND !empty($cus_phone_number)){ 
							echo $cus_phone_number; 
						} else { 
							echo "&nbsp";
						} 
					?>
				</div>
				<div class="CollapsiblePanelContent">
				  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="business_phone_number_form" class="customer_label">
					<label for="edit_phone_number">Edit phone number</label>
					<input name="edit_phone_number" type="text" id="edit_phone_number" size="60" maxlength="60" />
					<input type="submit" name="submit_phone_number" id="submit_phone_number" value="Submit" />
				  </form>
				</div>
			  </div>
			  <div id="CollapsiblePanel8" class="CollapsiblePanel">
				<div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">Address line 1: </label>
					<?php 
						if(isset($cus_addressLine1) AND !empty($cus_addressLine1)){ 
							echo $cus_addressLine1; 
						} else { 
							echo "&nbsp";
						} 
					?>
				</div>
				<div class="CollapsiblePanelContent">
				  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="address_line_1_form" class="customer_label">
					<label for="edit_address_line_1">Edit address line 1</label>
					<input name="edit_address_line_1" type="text" id="edit_address_line_1" size="60" maxlength="60" />
					<input type="submit" name="submit_address_line_1" id="submit_address_line_1" value="Submit" />
				  </form>
				</div>
			  </div>
			  <div id="CollapsiblePanel9" class="CollapsiblePanel">
				<div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">Address line 2: </label>
					<?php 
						if(isset($cus_addressLine2) AND !empty($cus_addressLine2)){ 
							echo $cus_addressLine2; 
						} else { 
							echo "&nbsp";
						} 
					?>
				</div>
				<div class="CollapsiblePanelContent">
				  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="address_line_2_form" class="customer_label">
					<label for="edit_address_line_2">Edit address line 2</label>
					<input name="edit_address_line_2" type="text" id="edit_address_line_2" size="60" maxlength="60" />
					<input type="submit" name="submit_address_line_2" id="submit_address_line_2" value="Submit" />
				  </form>
				</div>
			  </div>
			  <div id="CollapsiblePanel10" class="CollapsiblePanel">
				<!-- <label class="labelTitle">Address line 3: </label> -->
				<div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">Address line 3: </label>
					<?php 
						if(isset($cus_addressLine3) AND !empty($cus_addressLine3)){ 
							echo $cus_addressLine3; 
						} else { 
							echo "&nbsp";
						} 
					?>
				</div>
				<div class="CollapsiblePanelContent">
				  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="address_line_3_form" class="customer_label">
					<label for="edit_address_line_3">Edit address line 3</label>
					<input name="edit_address_line_3" type="text" id="edit_address_line_3" size="60" maxlength="60" />
					<input type="submit" name="submit_address_line_3" id="submit_address_line_3" value="Submit" />
				  </form>
				</div>
			  </div>
			  <div id="CollapsiblePanel11" class="CollapsiblePanel">
				<div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">City: </label>
					<?php 
						if(isset($cus_city) AND !empty($cus_city)){ 
							echo $cus_city; 
						} else { 
							echo "&nbsp";
						} 
					?>
				</div>
				<div class="CollapsiblePanelContent">
				  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="city_form" class="customer_label">
					<label for="edit_city">Edit city</label>
					<input name="edit_city" type="text" id="edit_city" size="60" maxlength="60" />
					<input type="submit" name="submit_city" id="submit_city" value="Submit" />
				  </form>
				</div>
			  </div>
			  <div id="CollapsiblePanel12" class="CollapsiblePanel">
				<div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">State: </label>
					<?php 
						if(isset($cus_state) AND !empty($cus_state)){ 
							echo $cus_state; 
						} else { 
							echo "&nbsp";
						} 
					?>
				</div>
				<div class="CollapsiblePanelContent">
				  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="state_form" class="customer_label">
					<label for="edit_state">Edit state</label>
					<input name="edit_state" type="text" id="edit_state" size="60" maxlength="60" />
					<input type="submit" name="submit_state" id="submit_state" value="Submit" />
				  </form>
				</div>
			  </div>
			  <div id="CollapsiblePanel13" class="CollapsiblePanel">
				<div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">Business Category: </label>
					<?php 
						// null !== $businessCategory->selected_business_categoty() AND !empty($businessCategory->selected_business_categoty())
						// $busCategory = $businessCategory->selected_business_categoty();
						if (isset($businessCategory->technician) AND $businessCategory->technician == TRUE) {
							$busCategory = "Technician";
						} elseif (isset($businessCategory->spare_part_seller) AND $businessCategory->spare_part_seller == TRUE) {
							$busCategory = "Spare part seller";
						} elseif (isset($businessCategory->artisan) AND $businessCategory->artisan == TRUE) {
							$busCategory = "Artisan";
						} else {
							$busCategory = FALSE;
						} 
						if( $busCategory ){ 
							// echo $businessCategory->selected_business_categoty(); 
							echo $busCategory;
						} else { 
							echo "&nbsp";
						} 
					?> 
				</div>
				<div class="CollapsiblePanelContent">
				  <form id="business_category_form" name="business_category_form" method="post" action="" enctype="application/x-www-form-urlencoded">
					  <label>
						<input type="radio" name="business_category" value="artisan" id="business_category_0" />
						Artisan</label>
					  <label>
						<input type="radio" name="business_category" value="technician" id="business_category_1" />
						Technician</label>
					  <label>
						<input type="radio" name="business_category" value="spare_part_seller" id="business_category_2" />
						Spare part seller</label>
					  <input type="submit" name="submit_business_category" id="submit_business_category" value="Submit"/>
				  </form>
				</div>
			  </div>
			  
			  <?php if ($busCategory !== "Artisan") { ?>
			  <div id="CollapsiblePanel14" class="CollapsiblePanel">
				<div class="CollapsiblePanelTab" tabindex="0">
					<label class="labelTitle">Vehicle category: </label>
					<?php 
						if (isset($vehicleCategory->car) AND $vehicleCategory->car == TRUE) {
							$vehicleType = "Cars";
						} elseif (isset($vehicleCategory->bus) AND $vehicleCategory->bus == TRUE) {
							$vehicleType = "Buses";
						} elseif (isset($vehicleCategory->truck) AND $vehicleCategory->truck == TRUE) {
							$vehicleType = "Trucks";
						} else {
							$vehicleType = FALSE;
						}
						
						if($vehicleType){ 
							echo $vehicleType; 
						} else { 
							echo "&nbsp";
						} 
					?>
				</div>
				<div class="CollapsiblePanelContent">
				  <form id="vehicle_category_form" name="vehicle_category_form" method="post" action="" enctype="application/x-www-form-urlencoded">
					  <label>
						<input type="radio" name="vehicle_category" value="cars" id="vehicle_category_0" />
						Cars</label>
					  <label>
						<input type="radio" name="vehicle_category" value="buses" id="vehicle_category_1" />
						Buses</label>
					  <label>
						<input type="radio" name="vehicle_category" value="trucks" id="vehicle_category_2" />
						Trucks</label>
					  <input type="submit" name="submit_vehicle_category" id="submit_vehicle_category" value="Submit" style="clear:both"/>
				  </form>
				</div>
			  </div>
			  <?php } ?>
			  
			  <?php if ($busCategory !== "Artisan") { ?>
			  <div id="CollapsiblePanel15" class="CollapsiblePanel"><!-- onclick="populateCheckboxesOfChoice('<?php // echo $vehicleType; ?>');" -->
				<div id="CollapsiblePanelTabCarBrands" class="CollapsiblePanelTab" tabindex="0"  >  
					<!-- <h4 style="margin:0px; float:left; padding-right:10px">Vehicle specialization: </h4> -->
					<label class="labelTitle">Vehicle specialization: </label>
					<?php echo displayVehSpecializatn($vehicleType); ?>
				</div>
				<div id="CollapsiblePanelContentCarBrands" class="CollapsiblePanelContent" style="position: relative;">
				  <form id="cars_specialization_form" name="cars_specialization_form" method="post" action="" enctype="application/x-www-form-urlencoded">
				    <?php echo displayVehCheckBoxes($vehicleType); ?>
					<input type="submit" name="submit_vehicle_brands[]" id="submit_vehicle_brands" value="Submit" style="clear:both"/>
				  </form>
				</div>
			  </div>
			  <?php } ?>
			  
			  <div id="CollapsiblePanel16" class="CollapsiblePanel">
				<div id="CollapsiblePanelTabTechServ" class="CollapsiblePanelTab" tabindex="0">  
					<!-- <h4 style="margin:0px; float:left; padding-right:10px">Technical services: </h4> -->
					<label id="servOrPartId" class="labelTitle">
						<?php 
							if ($busCategory === "Artisan") {
								echo "Artisan skills:";
							} elseif ($busCategory === "Technician") {
								echo "Technical services:";
							} elseif ($busCategory === "Spare part seller") {
								echo "Spare parts inventory:";
							} 
						?> 
					</label>
					<?php echo displayArtisansServParts($busCategory); ?>
				</div>
				<div id="CollapsiblePanelContentTechServ" class="CollapsiblePanelContent">
				  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="technical_services_form" id="technical_services_form">
				    <?php echo displayBussCateCheckBoxes($busCategory); ?>
					<!--
					  <label>
						<input type="checkbox" name="technical_services[]" value="engine_service" id="technical_services_14" />
						Engine service</label>
					-->
					<input type="submit" name="submit_services_parts[]" id="submit_services_parts" value="Submit" style="clear:both"/>
				  </form>
				</div>
			  </div>
              
			  <!-- 
			  <div id="delete">
				  <!-- <a href="#">Delete Account</a> --> <!--
				  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="delete_form" id="delete_form">
					<input type="submit" name="delete_account" id="delete_account" value="Delete Account" onclick="alert(You are about to delete this account. Proceed?);"/>
				  </form>
			  </div>
			  -->
           </div>
          
          <!-- End of collapsible panel div -->
          </div>
          <div class="TabbedPanelsContent">
            <div class="CusPhotoGallery">
              <?php foreach($customerPictures as $photo): ?>
                <div class="displayPicture"> 
				  <img name="" src="<?php echo "../".$photo->image_path(); ?>" width="300" height="150" alt="customer ad image" /><!-- style="float:right;"-->
                  <div style="padding-left:5px; padding-right:5px;"><?php echo $photo->caption; ?> <a href="delete_photo.php?cusId=<?php echo $_SESSION["customer_id"]; ?>&photoId=<?php echo $photo->id; ?>" style="float:right;">Delete</a> <!-- <a href="#" style="float:right; padding-right:5px">Edit Caption</a> --> </div>
                </div>
              <?php endforeach;?>
            </div>
            <div id="photoUpload">
              <form action="" method="post" enctype="multipart/form-data" name="profileEdit" id="profileEdit">
                <br/>
                <fieldset>
                  <legend>Photos</legend>
                  <p>
                    <!-- <input type="hidden" name="MAX_FILE_SIZE" value="<?php // $photo->max_file_size; ?>" /> -->
                    <label class="bussDescrpLabel"> Upload your pictures:
                      <input name="photo_upload" type="file" id="photo_upload" size="30" maxlength="30" />
                    </label>
                    <br/>
                  </p>
                  <p class="bussDescrpLabel">Caption:
                    <input type="text" name="caption" value="" />
                  </p>
                  <p></p>
                  <input type="submit" name="SubmitPhoto" id="Submit" value="Submit" />
                  <br/>
                </fieldset>
              </form>
            </div>
          </div>
          <div class="TabbedPanelsContent">
            <!-- Here we would display the comments. Firstly, we would loop through the comment array and display them one after the other. -->
            <div id="comments">
              <?php foreach($comments as $comment): ?>
              <div class="comment" style="margin-bottom: 2em;">
                <div class="author"> <?php echo htmlentities($comment->author); ?> wrote: </div>
                <div class="body">
                  <?php 
                        // The strip_tags will not allow any other HTML tags to be used for formating except these tags <strong><em><p>
                        echo strip_tags($comment->body, '<strong><em><p>'); 
                        ?>
                </div>
                <div class="meta-info" style="font-size: 0.8em;">
                  <!-- The datetime_to_text() function is in the function file. It can be referenced from any class. -->
                  <?php echo datetime_to_text($comment->created); ?> </div>
              </div>
              <?php endforeach; ?>
              <!-- If no comment, display there is none -->
              <?php if(empty($comments)) { echo "No Comments from users."; } ?>
            </div>
          </div>
          <div class="TabbedPanelsContent" >
			<div class="availabilityForm">
			<!-- class="form_style" float:left-->
            <form id="form2" name="form2" method="post" action="" >
				<fieldset class="set_availability">
					<legend>Set Your Availability for Appointments</legend>
					  <p> 
					    <?php echo selectDays(); ?>
					  </p>
					  <p>
					    <label>Select Hours:</label>
						<br/>
						<label>
						<input type="checkbox" name="set_hours[]" value="eight_to_nine_am" id="eight_to_nine_am" />
						8:00 AM - 9:00 AM</label>
						<br/>
					    <label>
						<input type="checkbox" name="set_hours[]" value="nine_to_ten_am" id="nine_to_ten_am" />
						9:00 AM - 10:00 AM</label>
						<br/>
					    <label>
						<input type="checkbox" name="set_hours[]" value="ten_to_eleven_am" id="ten_to_eleven_am" />
						10:00 AM - 11:00 AM</label>
						<br/>
					    <label>
						<input type="checkbox" name="set_hours[]" value="eleven_to_twelve_pm" id="eleven_to_twelve_pm" />
						11:00 AM - 12:00 PM</label>
						</br>
					    <label>
						<input type="checkbox" name="set_hours[]" value="twelve_to_one_pm" id="twelve_to_one_pm" />
						12:00 PM - 1:00 PM</label>
						<br/>
					    <label>
						<input type="checkbox" name="set_hours[]" value="one_to_two_pm" id="one_to_two_pm" />
						1:00 PM - 2:00 PM</label>
						<br/>
					    <label>
						<input type="checkbox" name="set_hours[]" value="two_to_three_pm" id="two_to_three_pm" />
						2:00 PM - 3:00 PM</label>
						<br/>
					    <label>
						<input type="checkbox" name="set_hours[]" value="three_to_four_pm" id="three_to_four_pm" />
						3:00 PM - 4:00 PM</label>
						
					  </p>
					  <p>
						<input type="submit" name="submit_availability" id="submit_availability" value="Submit" />
					  </p>
				</fieldset>
            </form>
			</div>
			<div class="displayAppointments">
				<h3 style="margin-top:0px"> List of your created schedules for a week </h3>
				<?php echo displayCustomerAvailability(); ?>
			</div>
          </div>
          <div class="TabbedPanelsContent">
            <div class="appointmentRequestList">
              <h3 style="text-align:center; margin-top:0px"> List of requested appointments from your customers </h3>
              <?php echo displayCustomerBookings(); ?> </div>
            <div class="acceptedAppointmentList">
              <h3 style="text-align:center; margin-top:0px"> List of your accepted appointments</h3>
              <?php echo displayAcceptedAppointments(); ?> </div>
          </div>
          <div class="TabbedPanelsContent">
			<div class="appointmentRequestList">
				<h3 style="text-align:center; margin-top:0px">Appointments requested with technicians</h3>
				<?php echo displayDeclinedAppointmentsCus(); ?>
				<?php echo displayCanceledAppointmentsCus(); ?>
				<?php echo displayRequestedAppointmentsCus(); ?>
			</div>
			<div class="acceptedAppointmentList">
				<h3 style="text-align:center; margin-top:0px">Appointments confirmed with technicians</h3>
				<?php echo displayConfirmedAppointmentsCus(); ?>
			</div>
		  </div>
        </div>
      </div>
      
      <!-- Ending of tabbed panel div -->
    </div>
    <p>&nbsp;</p>
  </div> <!-- End of Main Section -->

  <!-- Display the footer section -->
  <?php include_layout_template('public_footer.php'); ?>
</div>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen:false});
var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2", {contentIsOpen:false});
var CollapsiblePanel3 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel3", {contentIsOpen:false});
var CollapsiblePanel4 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel4", {contentIsOpen:false});
var CollapsiblePanel5 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel5", {contentIsOpen:false});
var CollapsiblePanel6 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel6", {contentIsOpen:false});
var CollapsiblePanel7 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel7", {contentIsOpen:false});
var CollapsiblePanel8 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel8", {contentIsOpen:false});
var CollapsiblePanel9 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel9", {contentIsOpen:false});
var CollapsiblePanel10 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel10", {contentIsOpen:false});
var CollapsiblePanel11 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel11", {contentIsOpen:false});
var CollapsiblePanel12 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel12", {contentIsOpen:false});
var CollapsiblePanel13 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel13", {contentIsOpen:false});
<?php if ($busCategory !== "Artisan") { ?>
var CollapsiblePanel14 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel14", {contentIsOpen:false});
var CollapsiblePanel15 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel15", {contentIsOpen:false});
<?php } ?>
var CollapsiblePanel16 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel16", {contentIsOpen:false});
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
</script>
<?php 
// 
for ($i=0; $i < $counterForJS; $i++) {
	echo '<script type="text/javascript" src="../javascripts/editDayTime.js"></script>';
	echo '<script type="text/javascript" src="../javascripts/customerAvailabilityUpdate.js"></script>';
}
?>
<script type="text/javascript" src="../javascripts/appointmentDecision.js"></script>
<script type="text/javascript" src="../javascripts/cancelAppointment.js"></script>
<script type="text/javascript">
var CollapsiblePanel17 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel17", {contentIsOpen:false});
</script>
<script type="text/javascript" src="../javascripts/customerEditPage2JSScripts.js"></script>
</body>
</html>

<?php // Close the database when done deleting
	if(isset($database)) { $database->close_connection(); } 
?>