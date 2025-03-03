<?php
require_once("../../includes/initialize.php");

if (request_is_get() || request_is_post()) {
	if ($session->is_customer_logged_in()) {	 
		if (!$session->is_session_valid()) {
			$session->message("Expired session: Please log-in again.");
			redirect_to("../loginPage.php?profile=customer"); 
		} 
	} else {
		$session->message("Please log-in properly.");
		redirect_to("../loginPage.php?profile=customer"); 
	}
	
	// Initialize the display of cropping div to false
	$showCropDiv = false;
	$message = "";

	$customerID = 0;
	$security = new Security_Functions();
	$photo = new Photograph();
	$encryptionObj = new Encryption();
	// the request_is_get() fxn will ensure that a get request was sent from the webpage
	if(request_is_get()) {
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
		
		/*
		if (isset($get_params["id"])) {
			// Decrypt the id 
			// $decodedId = urldecode(trim($get_params["id"]));
			// (int)$decryptedId = $encryptionObj->decrypt($decodedId);
			// echo "Decrypted Id is ".$decryptedId."<br/>";
			// $get_params["id"] == $session->customer_id
			// $decryptedId == $session->customer_id	
		} else {
			global $session;
			$session->message("No Customer ID was provided.");
			// redirect to another page
			redirect_to("/index.php");
		} */

		if (isset($session->customer_id)) {
			// $customerID = $decryptedId;
			$customerID = $session->customer_id;
			// $customerID = (int)$get_params["id"];
		} else {
			global $session;
			// Return an error message to the customer and log a spurious attempt to get into someone's profile.
			$session->message("Sorry, you could not be logged in.");
			// redirect to home page if incorrect customer id is provided.
			// redirect_to("/Public/customer/customerEditPage2.php?id=".urlencode($session->customer_id));
			redirect_to("/Public/loginPage.php?profile=customer");
		}	
		
	} elseif(request_is_post() && request_is_same_domain()) {
		// Check if the request is post and is from same web page.
		
		if(!$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()) {
			$message = "Sorry, request was not valid.";
		} else {
			// CSRF tests passed--form was created by us recently.
			
			// Check that only allowed parameters is passed into the form
			$post_params = allowed_post_params(['submit_first_name', "edit_first_name", "submit_last_name", "submit_gender", "gender", "edit_last_name", "submit_username", "edit_username", "submit_password", "edit_password", "confirm_password", "submit_business_name", "edit_business_name", "submit_business_description", "business_description", "submit_business_email", "edit_business_email", "submit_phone_number", "edit_phone_number", "submit_address_line_1", "edit_address_line_1", "submit_address_line_2", "edit_address_line_2", "submit_address_line_3", "edit_address_line_3", "town", "submit_town", "edit_town", "submit_state", "edit_state", "submit_business_category", "submit_vehicle_category", "submit_vehicle_brands", "car_brands", "bus_brands", "truck_brands", "submit_services_parts", "artisans", "sellers", "technical_services", "spare_parts", "delete_account", "SubmitPhoto", "caption", "submit_availability", "verifyNumber", "submit_smsToken", "smsToken"]);
			
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
			
			// Sanitize inputs from the form to be passed into the database.
			$customerID = sql_prep($_SESSION["customer_id"]);

			$customer = Customer::find_by_id($customerID);
			$address = Address::find_by_customerId($customerID);
			
			$artisans_sltd;  // used in post for validation

			$sellers_sltd;  // used in post for validation
			
			$technical_services_sltd; // used in post for validation
			
			$spare_parts_sltd; // used in post for validation

			// Get the id from the Artisan table using the customerID
			$artisanServStatic = Artisan::find_by_customerId($customerID);
			if (isset($artisanServStatic->id)) {
				$artisanServId = $artisanServStatic->id; // Used in post
			}

			// Get the id from the Seller table using the customerID
			$sellerStatic = Seller::find_by_customerId($customerID);
			if (isset($sellerStatic->id)) {
				$sellerProductId = $sellerStatic->id; // Used in post
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
			$car_brands = new Car_Brand(); // Used in post / functions
			$carBrands = $car_brands->selected_choices($customerID); // Used in post

			$bus_brands_sltd; // Used in post
			// Get the array of bus selected
			$bus_brands = new Bus_Brand(); // commented in post

			$truck_brands_sltd; // Used in post
			// Get the array of cars selected
			$truck_brands = new Truck_Brand(); // Commented in post

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
			
			if (isset($post_params['submit_first_name'])) {
				$first_name = trim($post_params['edit_first_name']);
				// $first_name = $database->escape_value($first_name);
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$customer->first_name = $first_name;
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();
					
					if(isset($savedCustomer)) {
						// This message should be saved in the session.
						$message = "First name was successfully updated.";
						// redirect_to('customerEditPage2.php?id='.urlencode($customerID));
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					/* This message is already outputted in the display error function */
					// $message = "There was an error during validation. ";
				} 	
			} elseif (isset($post_params['submit_last_name'])) {
				$last_name = trim($post_params['edit_last_name']);
				// $last_name = $database->escape_value($last_name);
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$customer->last_name = $last_name;
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();
					
					if(isset($savedCustomer)) {
						// This message should be saved in the session.
						$message = "Last name was successfully updated.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				} 	
			} elseif (isset($post_params['submit_gender'])) {
				$gender = "";
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$customer->gender = $gender;
					$customer->date_edited = current_Date_Time();
					$savedGender = $customer->update();
					
					if($savedGender) {
						// This message should be saved in the session.
						$session->message("Your gender was successfully updated.");
						redirect_to('customerEditPage2.php?id='.urlencode($customerID));
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				}
			} elseif (isset($post_params['submit_username'])) {
				$username = trim($post_params['edit_username']);
				// $username = $database->escape_value($username);
				// check if the username exists
				$userFound = User::find_by_username($username);
				$customerFound = Customer::find_by_username($username);
				
				if (isset($userFound->username) || isset($customerFound->username)) {
					$validate->errors["username_exists"] = "Username already exists.";
				}

				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$customer->username = $username;
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();
					
					if(isset($savedCustomer)) {
						// This message should be saved in the session.
						$message = "Username was successfully updated.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				} 	
			} elseif (isset($post_params['submit_password'])) {
				$password = trim($post_params['edit_password']);
				// $password =  $database->escape_value($password);
				$confirm_password = trim($post_params['confirm_password']);
				// $confirm_password =  $database->escape_value($confirm_password);
				$validate->validate_name_update();
				if ($password !== $confirm_password) {
					$validate->errors["password_match_err"] = "The passwords does not match.";
				}
				if (empty($validate->errors)) {
					// $customer = new Customer();
					$customer->password = $customer->password_encrypt($password);
					$customer->date_edited = $customer->current_Date_Time();
					$savedPassword = $customer->update();
					
					if(isset($savedPassword)) {
						// This message should be saved in the session.
						$message = "Password was successfully updated.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				} 	
				
			} elseif (isset($post_params['submit_business_name'])) {
				$business_name = trim($post_params['edit_business_name']);
				// $business_name = $database->escape_value($business_name);
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$customer->business_title = $business_name;
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();
					
					if(isset($savedCustomer)) {
						// This message should be saved in the session.
						$message = "Business title was successfully updated.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				} 	
			} elseif (isset($post_params['submit_business_description'])){
				$business_description = trim($post_params['business_description']);
				// $business_description = $database->escape_value($business_description);
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					// $businessCategory = new Business_Category();
					$businessCategory = Business_Category::find_by_customerId($customerID);
					$businessCategory->business_description = $business_description;
					if ($businessCategory->update()) {
						$message = "The business description was successfully updated.";
					} else {
						$message = "An error occurred while saving.";
					}
					
				} else {
					// $message = "There was an error during validation. ";
				}
			} elseif (isset($post_params['submit_business_email'])) {
				$business_email = trim($post_params['edit_business_email']);
				// $edit_business_email = $database->escape_value($edit_business_email);
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$customer->customer_email = $business_email;
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();
					
					if(isset($savedCustomer)) {
						// This message should be saved in the session.
						$message = "Email was successfully updated.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				}
			} elseif (isset($post_params['submit_phone_number'])) {
				$phone_number = trim($post_params['edit_phone_number']);
				// $phone_number = $database->escape_value($phone_number);
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$customer->phone_number = $phone_number;
					// Unset phone validated if it is updated
					$customer->phone_validated = 0;
					$customer->date_edited = $customer->current_Date_Time();
					$savedCustomer = $customer->update();
					
					if(isset($savedCustomer)) {
						// This message should be saved in the session.
						$message = "Phone number was successfully updated.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				}
			} elseif (isset($post_params['submit_address_line_1'])) {
				$edit_address_line_1 = trim($post_params['edit_address_line_1']);
				// $edit_address_line_1 = $database->escape_value($edit_address_line_1);
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$address->address_line_1 = $edit_address_line_1;
					$savedAddress = $address->update();
					
					if(isset($savedAddress)) {
						// This message should be saved in the session.
						$message = "Address line 1 was successfully updated.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				}
			} elseif (isset($post_params['submit_address_line_2'])) {
				$edit_address_line_2 = trim($post_params['edit_address_line_2']);
				// $edit_address_line_2 = $database->escape_value($edit_address_line_2);
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$address->address_line_2 = $edit_address_line_2;
					$savedAddress = $address->update();
					
					if(isset($savedAddress)) {
						// This message should be saved in the session.
						$message = "Address line 2 was successfully updated.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				}
			} elseif (isset($post_params['submit_address_line_3'])) {
				$edit_address_line_3 = trim($post_params['edit_address_line_3']);
				// $edit_address_line_3 = $database->escape_value($edit_address_line_3);
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$address->address_line_3 = $edit_address_line_3;
					$savedAddress = $address->update();
					
					if(isset($savedAddress)) {
						// This message should be saved in the session.
						$message = "Address line 3 was successfully updated.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				}
			} elseif (isset($post_params['submit_town'])) {
				// Check if it is an update of town action or a new town is to be submitted action that is going to be performed
				if (isset($post_params['town']) && ($post_params['town'] !== 'other')) {
					$town = $_POST['town'] = trim(str_replace("_", " ", $post_params['town']));
					
					// Validate the town input
					$validate->validate_name_update();
				} elseif (isset($post_params['edit_town'])) {
					$town = $_POST['town'] = trim(str_replace("_", " ", $post_params['edit_town']));

					// Validate the town input
					$validate->validate_edit_town();
				}
				
				// $town = $database->escape_value($town);
				if (empty($validate->errors)) {
					// update town record for the customer
					$address->town = $town;
					$savedAddress = $address->update();

					// If the town was not in the drop down menu, save it into the state_town table
					if (isset($post_params['edit_town'])) {
						$stateTown = new State_Town();
						$stateTown->addNewTown($address->state, $town);
					}
					
					if(isset($savedAddress)) {
						// $session->message("Town was successfully updated.");
						// This message should be saved in the session.
						$message = "Town was successfully updated.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				}
			} elseif (isset($post_params['submit_state'])) {
				// $state = trim($post_params['edit_state']);
				$state = trim(str_replace("_", " ", $post_params['edit_state']));
				// $state = $database->escape_value($state);
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$address->state = $state;
					$savedAddress = $address->update();
					
					if(isset($savedAddress)) {
						// $session->message("");
						// This message should be saved in the session.
						$message = "State was successfully updated.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				}
			} elseif (isset($post_params['submit_business_category'])) {
				// print_r($_POST);
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					$businessCategory = new Business_Category();
					$bussCategory = Business_Category::find_by_customerId($customerID);
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
								$businessCategory->customers_id = $customerID;
								$businessCategory->artisan = TRUE;
								$businessCategory->seller = FALSE;
								$businessCategory->technician = FALSE;
								$businessCategory->spare_part_seller = FALSE;
								$savedBusinessCategory = $businessCategory->create();

								// Delete user records from the related mechanic and spare part pages
								$busBrandObj = Bus_Brand::find_by_customerId($customerID);
								if (!empty($busBrandObj)) {
									$busBrandObj->delete();
								}

								$carBrandObj = Car_Brand::find_by_customerId($customerID);
								if (!empty($carBrandObj)) {
									$carBrandObj->delete();
								}

								$truckBrandObj = Truck_Brand::find_by_customerId($customerID);
								if (!empty($truckBrandObj)) {
									$truckBrandObj->delete();
								}

								$sparePartObj = Spare_Part::find_by_customerId($customerID);
								if (!empty($sparePartObj)) {
									$sparePartObj->delete();
								}

								$technicalServiceObj = Technical_Service::find_by_customerId($customerID);
								if (!empty($technicalServiceObj)) {
									$technicalServiceObj->delete();
								}

								$vehicleCategoryObj = Vehicle_Category::find_by_customerId($customerID);
								if (!empty($vehicleCategoryObj)) {
									$vehicleCategoryObj->delete();
								}
								break;
							case 'mobile_market':
								$businessCategory->customers_id = $customerID;
								$businessCategory->seller = TRUE;
								$businessCategory->artisan = FALSE;
								$businessCategory->technician = FALSE;
								$businessCategory->spare_part_seller = FALSE;
								$savedBusinessCategory = $businessCategory->create();

								// Delete user records from the related mechanic and spare part pages
								$busBrandObj = Bus_Brand::find_by_customerId($customerID);
								if (!empty($busBrandObj)) {
									$busBrandObj->delete();
								}

								$carBrandObj = Car_Brand::find_by_customerId($customerID);
								if (!empty($carBrandObj)) {
									$carBrandObj->delete();
								}

								$truckBrandObj = Truck_Brand::find_by_customerId($customerID);
								if (!empty($truckBrandObj)) {
									$truckBrandObj->delete();
								}

								$sparePartObj = Spare_Part::find_by_customerId($customerID);
								if (!empty($sparePartObj)) {
									$sparePartObj->delete();
								}

								$technicalServiceObj = Technical_Service::find_by_customerId($customerID);
								if (!empty($technicalServiceObj)) {
									$technicalServiceObj->delete();
								}

								$vehicleCategoryObj = Vehicle_Category::find_by_customerId($customerID);
								if (!empty($vehicleCategoryObj)) {
									$vehicleCategoryObj->delete();
								}
								break;
							case 'technician':
								$businessCategory->customers_id = $customerID;
								$businessCategory->artisan = FALSE;
								$businessCategory->seller = FALSE;
								$businessCategory->technician = TRUE;
								$businessCategory->spare_part_seller = FALSE;
								$savedBusinessCategory = $businessCategory->create();

								// Delete user records from the artisan table
								$artisanObj = Artisan::find_by_customerId($customerID);
								if (!empty($artisanObj)) {
									$artisanObj->delete();
								}

								// Delete user records from the seller table
								$sellerObj = Seller::find_by_customerId($customerID);
								if (!empty($sellerObj)) {
									$sellerObj->delete();
								}

								$sparePartObj = Spare_Part::find_by_customerId($customerID);
								if (!empty($sparePartObj)) {
									$sparePartObj->delete();
								}
								break;
							case 'spare_part_seller':
								$businessCategory->customers_id = $customerID;
								$businessCategory->artisan = FALSE;
								$businessCategory->seller = FALSE;
								$businessCategory->technician = FALSE;
								$businessCategory->spare_part_seller = TRUE;
								$savedBusinessCategory = $businessCategory->create();

								// Delete user records from the artisan table
								$artisanObj = Artisan::find_by_customerId($customerID);
								if (!empty($artisanObj)) {
									$artisanObj->delete();
								}

								// Delete user records from the seller table
								$sellerObj = Seller::find_by_customerId($customerID);
								if (!empty($sellerObj)) {
									$sellerObj->delete();
								}

								$technicalServiceObj = Technical_Service::find_by_customerId($customerID);
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
					// $message = "There was an error during validation. ";
				}
			} elseif (isset($post_params['submit_vehicle_category'])) {
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
						// This message should be saved in the session.
						$message = "Your vehicle category was successfully updated.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}
				} else {
					// $message = "There was an error during validation. ";
				}
			} elseif (isset($post_params['submit_vehicle_brands'])) {
				
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					if (isset($post_params['car_brands'])) {
						$carBrand = new Car_Brand();
						// First initialize all the values to false before you set the selected cars to true
						// Get all the car brands
						$car_types = $carBrand->getVehicleBrands();
						foreach ($car_types as $type => $value) {
							$carBrand->{$type} = FALSE;
						}
						
						$carBrand->customers_id = $customerID;
						
						// save the selected choices
						foreach ($car_brands_sltd as $type) {
							$carBrand->{$type} = TRUE;
						}

						// If there is a carBrandsId update otherwise create a new record
						if (isset($carBrandsId)) {
							// Specify the id of the customer in the table
							$carBrand->id = $carBrandsId;
							$savedCarBrand = $carBrand->update();
						} else {
							$savedCarBrand = $carBrand->create();
						}
						
						if(isset($savedCarBrand)) {
							// This message should be saved in the session.
							$message = "Your car brands were successfully updated.";
							// unset($car_brands);
						} else {
							$message = "An error occurred while saving.";
						}
					} elseif (isset($post_params['bus_brands'])) {
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
							$message = "Your bus brands were successfully updated.";
							// unset($bus_brands);
						} else {
							$message = "An error occurred while saving.";
						}
					} elseif (isset($post_params['truck_brands'])) {
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
							$message = "Your truck brands were successfully updated.";
							// unset($truck_brands);
						} else {
							$message = "An error occurred while saving.";
						}
					}
				} else {
					// $message = "There was an error during validation. ";
				}
			} elseif (isset($post_params['submit_services_parts'])) {
				// print_r($post_params);
				// print_r($_POST);
				$validate->validate_name_update();
				if (empty($validate->errors)) {
					if (isset($post_params['artisans'])) {
						// save the selected choices
						$artisanService = new Artisan();
						$artisan_types = $artisanService->getArtisans();
						
						// Initially initialize all the types of technical services to false.
						foreach ($artisan_types as $type => $value) {
							$artisanService->{$type} = FALSE;
						}

						// Determine if to update the record or create a new record
						if (isset($artisanServId)) {
							// Specify the id of the customer in the table
							$artisanService->id = $artisanServId;
							$artisanService->customers_id = $customerID;
							// $artisanService->update();
							
							// Next, initialize all the selected technical services to true.
							foreach ($artisans_sltd as $type) {
								$artisanService->{$type} = TRUE;
							}
							$savedArtisanService = $artisanService->update();
						} else {
							$artisanService->customers_id = $customerID;
							// $artisanService->update();
							
							// Next, initialize all the selected technical services to true.
							foreach ($artisans_sltd as $type) {
								$artisanService->{$type} = TRUE;
							}
							$savedArtisanService = $artisanService->create();
						}
						
						if(isset($savedArtisanService)) {
							// This message should be saved in the session.
							$message = "Your artisan services were successfully updated.";
							// redirect_to('customerEditPage2.php');
						} else {
							$message = "An error occurred while saving.";
						}
					} elseif (isset($post_params['sellers'])) {
						// save the selected choices
						$sellerProduct = new Seller();
						$inventory = $sellerProduct->getSellers();
						
						// Initially initialize all the types of technical services to false.
						foreach ($inventory as $type => $value) {
							$sellerProduct->{$type} = FALSE;
						}

						// Determine if to update the record or create a new record
						if (isset($sellerProductId)) {
							// Specify the id of the customer in the table
							$sellerProduct->id = $sellerProductId;
							$sellerProduct->customers_id = $customerID;
							// $sellerProduct->update();
							
							// Next, initialize all the selected technical services to true.
							foreach ($sellers_sltd as $type) {
								$sellerProduct->{$type} = TRUE;
							}
							$savedSellerProduct = $sellerProduct->update();
						} else {
							$sellerProduct->customers_id = $customerID;
							// $sellerProduct->update();
							
							// Next, initialize all the selected technical services to true.
							foreach ($sellers_sltd as $type) {
								$sellerProduct->{$type} = TRUE;
							}
							$savedSellerProduct = $sellerProduct->create();
						}
						
						if(isset($savedSellerProduct)) {
							// This message should be saved in the session.
							$message = "Your inventories were successfully updated.";
							// redirect_to('customerEditPage2.php');
						} else {
							$message = "An error occurred while saving.";
						}
					} elseif (isset($post_params['technical_services'])) {
						// save the selected choices
						$technicalService = new Technical_Service();
						$tech_serv_types = $technicalService->getTechnicalServices();
						// $tech_serv_types = array("engine_service", "mechanical_service", "electrical_service", "air_conditioning_service", "computer_diagnostics_service", "panel_beating_service", "body_work_service", "shock_absorber_service", "ballon_shocks_service", "wheel_balancing_and_alignment_service", "car_wash_service", "towing_service", "buy_cars", "sell_cars");
						
						// Initially initialize all the types of technical services to false.
						foreach ($tech_serv_types as $type => $value) {
							$technicalService->{$type} = FALSE;
						}

						$technicalService->customers_id = $customerID;
						// $technicalService->update();
						
						// Next, initialize all the selected technical services to true.
						foreach ($technical_services_sltd as $type) {
							$technicalService->{$type} = TRUE;
						}

						// If techServId is available, update record otherwise, create a new record
						if (isset($techServId)) {
							// Specify the id of the customer in the table
							$technicalService->id = $techServId;
							$savedTechnicalService = $technicalService->update();
						} else {
							$savedTechnicalService = $technicalService->create();
						}
						
						if(isset($savedTechnicalService)) {
							// This message should be saved in the session.
							$message = "Your technical services were successfully updated.";
							// redirect_to('customerEditPage2.php');
						} else {
							$message = "An error occurred while saving.";
						}
					} elseif (isset($post_params['spare_parts'])) {
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
							// This message should be saved in the session.
							$message = "Your spare parts were successfully updated.";
							// redirect_to('customerEditPage2.php');
						} else {
							$message = "An error occurred while saving.";
						}
					}
				} else {
					// $message = "There was an error during validation. ";
				}
			} 

			if (isset($post_params['delete_account'])) {
				$customerKey = new Customer();
				$deactivateCustomer = $customerKey->deactivate($customerID);
				if($deactivateCustomer) {
					$session->message($session->customer_full_name." account was successfully deleted.");
					// redirect_to('../homePage.php');
				} else {
					// $session->message("The user could not be deleted.");
					// redirect_to('customerEditPage2.php?id='.$get_params['id']);
					$message = "Error! This account could not be deleted.";
				}
			}

			/* // Upload pictures
			if (isset($post_params['SubmitPhoto'])) {
				// Display the cropping div
				$showCropDiv = true;
				// echo '<script type="text/javascript" src="../javascripts/cropImage.js"></script>';
				
				$caption = trim($post_params['caption']);
				$caption = $database->escape_value($caption);
				
				// Validate the caption entered using the valideate address fxn.
				if ($validate->validate_address($caption)) {
					$validate->errors["Invalid_caption"] = "The image caption entered is not valid.";
				}
				
				if (empty($validate->errors)) {
					// The attach_file and save functions in the Photograph class already check for errors
					// global $photo;
					$photo->attach_file($_FILES['photo_upload']);
					$photo->customers_id = $customerID;
					
					$photo->caption = $caption;
					$savedPhoto = $photo->save();
					if ($savedPhoto === TRUE) {
						// Success
						$message = "Photograph uploaded successfully. Crop your image to your choice in the photo gallery.";
					} else {
						// Failure
						$message = join("<br/>", $photo->errors);
					}

				} else {
					// $message = "There was an error during validation. ";
				}
			} */	

			if (isset($post_params['submit_availability'])) {
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
						// This message should be saved in the session.
						$message = "Your appointment availability was successfully saved.";
						// redirect_to('customerEditPage2.php');
					} else {
						$message = "An error occurred while saving.";
					}				
				} else {
					// $message = "There was an error during validation. ";
				}				
			}
			
			if (isset($post_params['verifyNumber'])) {
				$phone_validated = sql_prep($customer->phone_validated);
				// $phone_validated = $_SESSION['phone_validated'];
				$phone_number = $customer->phone_number;
				$smsCode = randStrGen(6);
				$customer->reset_token = $smsCode;
				$customer->date_edited = current_Date_Time();
				$customer->update();
				$smsMessage = 'Your phone number has been confirmed, please use this code '.$smsCode.' to complete your phone number validation.';
				// sendSMSCode($phone_number, $smsMessage)
				if (sendSMSCode($phone_number, $smsMessage)) {
					$message = "A message has been sent to your phone.";
					$_SESSION['cus_showTokenInputField'] = true;
				} else {
					$message = "An error occured sending validation token. Please contact the support services.";
				}
			}
			
			if (isset($post_params['submit_smsToken'])) {
				// Check if the token is still valid.
				$currentTime = time();
				$tokenDbTime = $customer->date_edited;
				$tokenDbTimeInSec = strtotime($tokenDbTime);
				$timeCheck = 60 * 5; // 5 minutes time validity for token.
				$timeElapsed = $currentTime - $tokenDbTimeInSec;
				if (($timeElapsed) < $timeCheck) {
					$smsToken = trim($post_params['smsToken']);
					// $smsToken = $database->escape_value($smsToken);
					// $validate->validate_name_update();
					$savedToken = $customer->reset_token;
					if ($smsToken === $savedToken) {
						$customer->phone_validated = true;
						$phone_validated = true;
						$_SESSION['cus_showTokenInputField'] = false;
						$customer->reset_token = "";
						$customer->date_edited = current_Date_Time();
						$customer->update();
						$message = "Your phone number has been successfully validated.";
					} else {
						$_SESSION['cus_showTokenInputField'] = false;
						$customer->phone_validated = false;
						$phone_validated = false;
						$customer->reset_token = "";
						$customer->date_edited = current_Date_Time();
						$customer->update();
						$message = "The verification token entered wasn't a match.";
					}
				} else {
					$_SESSION['cus_showTokenInputField'] = false;
					$customer->phone_validated = false;
					$phone_validated = false;
					$customer->reset_token = "";
					$customer->date_edited = current_Date_Time();
					$customer->update();
					$message = "The Token has expired, please try again.";
				}
			}		
		}
	}

	// If the get or post has been checked, then perform these other functions
	$customer = Customer::find_by_id($customerID);
	if (isset($customer->first_name)) {
		$cus_first_name = sql_prep($customer->first_name);
	}
	if (isset($customer->last_name)) {
		$cus_last_name = $customer->last_name;
	}
	if (isset($customer->gender)) {
		$cus_gender = $customer->gender;
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
	if (isset($customer->phone_validated)) {
		$phone_validated = sql_prep($customer->phone_validated);
		// $_SESSION['cus_phone_validated'] = sql_prep($customer->phone_validated);
	}
	$cus_showTokenInputField = false;
	// If the variable cus_showTokenInputField is not assigned, initialize it to false. If it is assigned, leave it with the assigned variable.
	if (!isset($_SESSION['cus_showTokenInputField'])) {
		$_SESSION['cus_showTokenInputField'] = false;
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
	if (isset($address->town)) {
		$cus_town = $address->town;
	}
	if (isset($address->state)) {
		$cus_state = $address->state;
	}

	$seller_products = new Seller();
	$sellerProducts = $seller_products->selected_choices($customerID); // Used in functions
	$allSellerProducts = $seller_products->getSellers(); // Used in functions

	$artisan_services = new Artisan();
	$artisanServices = $artisan_services->selected_choices($customerID); // Used in functions
	$allArtisanServices = $artisan_services->getArtisans(); // Used in functions

	$technical_services = new Technical_Service();
	$technicalServices = $technical_services->selected_choices($customerID); // Used in function
	$allTechServices = $technical_services->getTechnicalServices(); // Used in functions

	$spare_parts = new Spare_Part(); // 
	$spareParts = $spare_parts->selected_choices($customerID); // Used in function
	$allSpareParts = $spare_parts->getSpareParts(); // Used in function

	// Get the array of cars selected
	$car_brands = new Car_Brand(); // Used in post / functions
	$carBrands = $car_brands->selected_choices($customerID); // Used in post
	$allCarBrands = $car_brands->getVehicleBrands(); // Used in functions
	// print_r($allCarBrands);

	$bus_brands = new Bus_Brand(); 
	$busBrands = $bus_brands->selected_choices($customerID); // Used in function
	$allBusBrands = $bus_brands->getBusBrands(); // Used in function

	$truck_brands = new Truck_Brand(); // Commented in post
	$truckBrands = $truck_brands->selected_choices($customerID); // Used in function
	$allTruckBrands = $truck_brands->getTruckBrands(); // Used in function

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

	// Initialize the last tab index
	if (isset($_SESSION['lastTabIndex'])) {
		$lastTabIndex = json_encode($_SESSION['lastTabIndex']);
		echo "<script> var lastTabIndex = ".$lastTabIndex."; </script>";
	} else {
		$lastTabIndex = json_encode(0);
		echo "<script> var lastTabIndex = ".$lastTabIndex."; </script>";
	}

	function displayVehCheckBoxes($vehicleType) {
		global $allCarBrands;
		global $allBusBrands;
		global $allTruckBrands;
		
		$checkboxes = "";
		if ($vehicleType === "Cars") {
			foreach($allCarBrands as $car => $value) {
				$checkboxes .= "<label class='checkBoxLabel'><input type='checkbox' name='car_brands[]' value='".$car."' id='".$car."' />".ucfirst(str_replace("_", " ", $car))."</label>";
			}
		} elseif ($vehicleType === "Buses") {
			foreach($allBusBrands as $bus => $value) {
				$checkboxes .= "<label class='checkBoxLabel'><input type='checkbox' name='bus_brands[]' value='".$bus."' id='".$bus."' />".ucfirst(str_replace("_", " ", $bus))."</label>";
			}
		} elseif ($vehicleType === "Trucks") {
			foreach($allTruckBrands as $truck => $value) {
				$checkboxes .= "<label class='checkBoxLabel'><input type='checkbox' name='truck_brands[]' value='".$truck."' id='".$truck."' />".ucfirst(str_replace("_", " ", $truck))."</label>";
			}
		}
		$checkboxes .= "<br/>";
		
		return $checkboxes;
	}

	function displayBussCateCheckBoxes($businessCategory) {
		global $allArtisanServices;
		global $allSellerProducts;
		global $allTechServices;
		global $allSpareParts;
		
		$checkboxes = "";
		if ($businessCategory === "Artisan") {
			foreach($allArtisanServices as $artisanServs => $value) {
				$checkboxes .= "<label class='checkBoxLabel'><input type='checkbox' name='artisans[]' value='".$artisanServs."' id='".$artisanServs."' />".ucfirst(str_replace("_", " ", $artisanServs))."</label>";
			}
		} elseif ($businessCategory === "Seller") {
			foreach($allSellerProducts as $sellerServs => $value) {
				$checkboxes .= "<label class='checkBoxLabel'><input type='checkbox' name='sellers[]' value='".$sellerServs."' id='".$sellerServs."' />".ucfirst(str_replace("_", " ", $sellerServs))."</label>";
			}
		} elseif ($businessCategory === "Technician") {
			foreach($allTechServices as $techServs => $value) {
				$checkboxes .= "<label class='checkBoxLabel'><input type='checkbox' name='technical_services[]' value='".$techServs."' id='".$techServs."' />".ucfirst(str_replace("_", " ", $techServs))."</label>";
			}
		} elseif ($businessCategory === "Spare part seller") {
			foreach($allSpareParts as $sParts => $value) {
				$checkboxes .= "<label class='checkBoxLabel'><input type='checkbox' name='spare_parts[]' value='".$sParts."' id='".$sParts."' />".ucfirst(str_replace("_", " ", $sParts))."</label>";
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
		$output .= "<ul class='profileULStyle'>";
		if ($vehicleType === "Cars") {
			foreach ($carBrands as $key => $value) {
				$output .= '<li class="profileLIStyle">'.$key.',';
			}
		} elseif ($vehicleType === "Buses") {
			foreach ($busBrands as $key => $value) {
				$output .= '<li class="profileLIStyle">'.$key.',';
			}
		} elseif ($vehicleType === "Trucks") {
			foreach ($truckBrands as $key => $value) {
				$output .= '<li class="profileLIStyle">'.$key.',';
			}
		}
		$output = chop($output, ',');
		$output .= "</li></ul>";
		
		return $output;
	}

	function displayArtisansServParts($businessCategory) {
		global $artisanServices;
		global $sellerProducts;
		global $technicalServices;
		global $spareParts;
		
		$output = "";
		$output .= "<ul class='profileULStyle'>";
		if ($businessCategory === "Artisan") {
			foreach ($artisanServices as $key => $value) {
				$output .= '<li class="profileLIStyle">'.$key.',';
			}
		} elseif ($businessCategory === "Seller") {
			foreach ($sellerProducts as $key => $value) {
				$output .= '<li class="profileLIStyle">'.$key.',';
			}
		} elseif ($businessCategory === "Technician") {
			foreach ($technicalServices as $key => $value) {
				$output .= '<li class="profileLIStyle">'.$key.',';
			}
		} elseif ($businessCategory === "Spare part seller") {
			foreach ($spareParts as $key => $value) {
				$output .= '<li class="profileLIStyle">'.$key.',';
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
		$customerAvailabilityId = array();
		
		while($row = mysqli_fetch_assoc($result_set)){ 
			// Gets the saved schedule of the customer 
			$customerRecord[$count] = $row;
			$customerAvailability[$count] = $row["date_available"];
			$customerAvailabilityId[$count] = $row["id"];
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
			$output .= "<input type='button' value='Edit' id='openDiv".$key."' class='editButton btnStyle1' />";
			// Show the delete button
			$output .= "<input type='button' data-cusavailid='".$customerAvailabilityId[$key]."' value='Delete' class='btnStyle1 deleteSchedule' href='#' id='deleteSchedule".$key."' onclick='deleteAvailSch(this.id);'/>";
			// $output .= "<a href=''>Edit</a>";
			$output .= "</p>";
			// list all the time available
			$output .= "<ul id=".'timeUL'.$key." class=".'timeUL'.">";
			foreach ($refinedTime[$key] as $timeKey => $timeRecord) {
				$output .= "<li class='timeList' >".$cus_availability->editDbVarToFormTime($refinedTime[$key][$timeKey])."</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";
			$output .= "<div class='editClass' id=".'editTime'.$key.">";
			$output .= '
				
					<label>Edit Hours:</label>
					<input type="button" value="Close" id="closeDiv'.$key.'" class="closeButton btnStyle1" />
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

					<label>
					<input type="checkbox" name="edit_hours[]" value="four_to_five_pm" id="four_to_five_pm'.$key.'" />
					4:00 PM - 5:00 PM</label>

					<label>
					<input type="checkbox" name="edit_hours[]" value="five_to_six_pm" id="five_to_six_pm'.$key.'" />
					5:00 PM - 6:00 PM</label>

					<label>
					<input type="checkbox" name="edit_hours[]" value="six_to_seven_pm" id="six_to_seven_pm'.$key.'" />
					6:00 PM - 7:00 PM</label>

					<label>
					<input type="checkbox" name="edit_hours[]" value="seven_to_eight_pm" id="seven_to_eight_pm'.$key.'" />
					7:00 PM - 8:00 PM</label>

					<label>
					<input type="checkbox" name="edit_hours[]" value="eight_to_nine_pm" id="eight_to_nine_pm'.$key.'" />
					8:00 PM - 9:00 PM</label>

					<label>
					<input type="checkbox" name="edit_hours[]" value="nine_to_ten_pm" id="nine_to_ten_pm'.$key.'" />
					9:00 PM - 10:00 PM</label>
					<br/>
					
					<input type="submit" name="submit_edited_availability'.$key.'" id="submit_new_availability'.$key.'" class="btnStyle1" value="Submit" onclick="customer_availability_update(\'eight_to_nine_am'.$key.'\', \'nine_to_ten_am'.$key.'\', \'ten_to_eleven_am'.$key.'\', \'eleven_to_twelve_pm'.$key.'\', \'twelve_to_one_pm'.$key.'\', \'one_to_two_pm'.$key.'\', \'two_to_three_pm'.$key.'\', \'three_to_four_pm'.$key.'\', \'four_to_five_pm'.$key.'\', \'five_to_six_pm'.$key.'\', \'six_to_seven_pm'.$key.'\', \'seven_to_eight_pm'.$key.'\', \'eight_to_nine_pm'.$key.'\', \'nine_to_ten_pm'.$key.'\', \'timeUL'.$key.'\', \'errorMessageDiv'.$key.'\' );" />
					
					<div id="errorMessageDiv'.$key.'"></div>
				';
			// onclick="customer_availability_update_'.$key.'();"
			$output .= "</div>";
			// $output .= "<br/>";			
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
			$output .= "<p>You are not logged in. Please log in to see your appointments.</p>";
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
			$output .= "<p>You are not logged in. Please log in to see your appointments.</p>";
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
				$output .= "<input type='button' class='btnStyle1' value='Accept' id='acceptButton".$key."' onclick='appointmentAccepted(this.id);' />";
				// Show the decline button
				// $output .= "<input type='button' value='Decline' id='declineButton".$key."' onclick='appointmentDeclined(this.id);' style='margin-left:10px;'/>";
				
				// Edit from here
				$output .= "<input type='button' class='btnStyle1' value='Decline' id='declineBtn".$key."' onclick='openTextareaCusDecline(this.id);' style='margin-right:10px;' />";
				$output .= "<input type='button' class='btnStyle1' value='Hide' id='hideDeclineBtn".$key."' onclick='hideTextareaCusDecline(this.id);' style='display:none;'/>";
				$output .= "</div>";
				
				// Div containing form for textarea and submit button to provide reason for declining.
				$output .= "<div class='userAppointDetails' id='decliningReasonDiv".$key."' style='display:none;' >";
				$output .= "<form action='#' id='declineReasonForm".$key."' name='declineReasonForm".$key."'>";
				$output .= "<p style='margin:0px;' >Please provide a reason for declining this appointment. </p>";
				$output .= "<textarea id='decliningReason".$key."' class='feedbackInput' name='decliningReason".$key."' cols='50' rows='4'></textarea>";
				$output .= "<br/>";
				// $output .= "<input type='button' value='Submit' id='submitMessageBtn".$key."' onclick='appointmentCustomerCanceled(this.id);' />";
				$output .= "<input type='button' class='submitBtn btnStyle1' value='Submit' id='submitDeclineBtn".$key."' onclick='appointmentDeclined(this.id);' />";
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
				$output .= "<input type='button' value='Cancel' id='cancelButton".$key."' class='btnStyle1' onclick='openTextarea(this.id);' style='margin-right:10px;'/>";
				$output .= "<input type='button' value='Hide' id='hideButton".$key."' class='btnStyle1' onclick='hideTextarea(this.id);' style='display:none;'/>";
				$output .= "</div>";
				
				
				// Div containing form for textarea and submit button
				$output .= "<div class='userAppointDetails' id='reasonForCancelDiv".$key."' style='display:none;' >";
				$output .= "<form action='#' id='cancelForm".$key."' name='cancelForm".$key."'>";
				$output .= "<p style='margin:0px;' >Please provide a reason for canceling this created appointment. </p>";
				$output .= "<textarea id='reasonForCancel".$key."' class='feedbackInput' name='reasonForCancel".$key."' cols='55' rows='4'></textarea>";
				$output .= "<br/>";
				$output .= "<input type='button' value='Submit' class='submitBtn btnStyle1' id='submitReasonBtn".$key."' onclick='appointmentCanceled(this.id);' />";
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
			$output .= "<p>You are not logged in. Please log in to see your appointments.</p>";
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
				$output .= "<input type='button' value='Cancel' id='cancelButton".$key."' class='btnStyle1' onclick='appointmentCanceled(this.id);' />"; */
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
			$output .= "<p>You are not logged in. Please log in to see your appointments.</p>";
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
				$output .= "<input type='button' value='Cancel' id='cancelBtn".$key."' class='btnStyle1' onclick='openTextareaCustomer(this.id);' style='margin-right:10px;' />";
				$output .= "<input type='button' value='Hide' id='hideBtn".$key."' class='btnStyle1' onclick='hideTextareaCustomer(this.id);' style='display:none;'/>";
				$output .= "</div>";
				
				// Div containing form for textarea and submit button
				$output .= "<div class='userAppointDetails' id='cancelingReasonDiv".$key."' style='display:none;' >";
				$output .= "<form action='#' id='cancelReasonForm".$key."' name='cancelReasonForm".$key."'>";
				$output .= "<p style='margin:0px;' >Please provide a reason for canceling this created appointment. </p>";
				$output .= "<textarea id='cancelingReason".$key."' class='feedbackInput' name='cancelingReason".$key."' cols='55' rows='4'></textarea>";
				$output .= "<br/>";
				$output .= "<input type='button' value='Submit' id='submitMessageBtn".$key."' class='submitBtn btnStyle1' onclick='appointmentCustomerCanceled(this.id);' />";
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
	
	// Display cropping div
	function showCropDiv() {
		$output = "";
		$output .= "<div id='imageBox' ></div>";
		$output .= "<button id='crop' class='crop_button croppie-result btnStyle1'><a class='scroll' id='cropLinkBtn' href='#display'> Crop </a></button>";
		$output .= "<br/><br/>";
		$output .= "<div id='display' ></div>";
		
		return $output;
	}

} else {
	// Log-in spurios attempt to get into a user's account
	$session->message("Improper page request.");
	redirect_to("/index.php");
}


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
<title><?php echo $cus_full_name." Profile"; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" type="image/png" href="/Public/images/WhoSabiWorkLogo.png" />
<style type="text/css">
</style>
<link href="../../HomePageStyles.css" rel="stylesheet" type="text/css" />
<link href="../stylesheets/customerEditPage2.css" rel="stylesheet" type="text/css" />
<link href="../stylesheets/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<link href="../stylesheets/croppie.css" rel="stylesheet" type="text/css" />
<link href="../stylesheets/cropImage.css" rel="stylesheet" type="text/css" />
<link href="../stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />

<link href="../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />

<script src="../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>

<script type="text/javascript" src="../javascripts/jquery.js"></script>
<script type="text/javascript" src="../javascripts/jquery-ui.min.js"></script>
<script type="text/javascript" src="../javascripts/croppie.js"></script>

</head>

<body>
	<?php
		$outputMessage = displayMessages();
		if (!empty($outputMessage)) {
			showErrorMessage($outputMessage);
		}
		// echo displayMessages();
	?>
	<div id="topContainer">
		<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
		<?php include_layout_template('public_header.php'); ?>
	</div>
	
	<div id="container">
		<?php // echo displayMessages(); ?>
	  <!-- Begining of Main Section -->  
	  <div id="mainCustomerEditPage">

	    <h2 id="pageTitle" ><?php echo ucwords($_SESSION['customer_full_name']); ?> Profile</h2>
	    <div class="TabbedPanelDiv">
	      <!-- Beginning of tabbed panel div -->
	      
	      <div id="TabbedPanels1" class="TabbedPanels">
	        <ul class="TabbedPanelsTabGroup">
	          <li class="TabbedPanelsTab" tabindex="0">Business Details</li>
	          <li class="TabbedPanelsTab" tabindex="0">Photo Gallery</li>
	          <li class="TabbedPanelsTab" tabindex="0">Customers Comments</li>
	          <li class="TabbedPanelsTab" tabindex="0">Your Comments</li>
	          <li class="TabbedPanelsTab" tabindex="0">Set Availability</li>
	          <li class="TabbedPanelsTab" tabindex="0">Customers Appointments</li>
	          <li class="TabbedPanelsTab" tabindex="0">My Appointments</li>
	        </ul>
	        <div class="TabbedPanelsContentGroup">
	          <div class="TabbedPanelsContent">
	          <!-- Beginning of collapsible panel div -->
	          <button id='toHomePage' class='btnStyle1' onclick="toHomePage(<?php echo $customerID; ?>);">Public view</button>
	          
	          <div class="CollapsiblePanelsDiv">
							<div id="CollapsiblePanel1" class="CollapsiblePanel">
							  <div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">First name: </label>
								<p id="firstNameInput" class='profileInput'>
									<?php  
										if(isset($cus_first_name) AND !empty($cus_first_name)){ 
											echo ucfirst($cus_first_name); 
										} else { 
											echo "&nbsp";
										} 
									?>
								</p>
							  </div>
							  <div class="CollapsiblePanelContent">
								<form action="" method="post" enctype="application/x-www-form-urlencoded" name="first_name_form" class="customer_label">
								  <?php 
									echo $security->csrf_token_tag(); 
								  ?>
								  <label for="edit_first_name">Edit first name</label>
									<input name="edit_first_name" type="text" id="edit_first_name" class="inputText" size="60" maxlength="60" />
									<div class="submitClearBtnDiv" >
									  <input type="submit" name="submit_first_name" id="submit_first_name" class="submitBtn btnStyle1" value="Submit" />

									  <button name="clear_first_name" id="clear_first_name" class="clearBtn btnStyle1" >Clear</button>
									</div>
								</form>
							  </div>
							</div>
						  <div id="CollapsiblePanel2" class="CollapsiblePanel">
							  <div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">Last name: </label>
								<p id="lastNameInput" class='profileInput'>
									<?php 
									if(isset($cus_last_name) AND !empty($cus_last_name)){ 
										echo ucfirst($cus_last_name); 
									} else { 
										echo "&nbsp";
									} 
									?>
								</p>
							  </div>
							  <div class="CollapsiblePanelContent">
								<form class="customer_label" name="last_name_form" method="post" action="" enctype="application/x-www-form-urlencoded">
								  <?php 
									echo $security->csrf_token_tag(); 
								  ?>
								  <label for="edit_last_name">Edit last name</label>
								  <input name="edit_last_name" type="text" id="edit_last_name" class="inputText" size="60" maxlength="60" />
								  <div class="submitClearBtnDiv" >
									  <input type="submit" name="submit_last_name" id="submit_last_name" class="submitBtn btnStyle1" value="Submit" />

									  <button name="clear_last_name" id="clear_last_name" class="clearBtn btnStyle1" >Clear</button>
									</div>
								</form>
							  </div>
						  </div>
						  <div id="CollapsiblePanel3" class="CollapsiblePanel">
							  <div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">Gender: </label>
								<p id="genderInput" class='profileInput'>
									<?php 
									if(isset($cus_gender) AND !empty($cus_gender)){ 
										echo ucfirst($cus_gender); 
									} else { 
										echo "&nbsp";
									} 
									?>
								</p>
							  </div>
							  <div class="CollapsiblePanelContent">
								<form class="customer_label" name="last_name_form" method="post" action="" enctype="application/x-www-form-urlencoded">
								  <?php 
									echo $security->csrf_token_tag(); 
								  ?>
						  		<label>
				          	<input name="gender" type="radio" value="male" id="male" /> Male
				          </label>
				           <label>
				          	<input name="gender" type="radio" value="female" id="female" /> Female
				          </label>
				          <div class="submitClearBtnDiv" >
	                  <input type="submit" name="submit_gender" id="submit_gender" class="submitBtn btnStyle1" value="Submit" style="clear:both" />

	                  <button name="clear_gender" id="clear_gender" class="clearBtn btnStyle1" >Clear</button>
	                </div>
								</form>
							  </div>
						  </div>
						  <div id="CollapsiblePanel4" class="CollapsiblePanel">
							<div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">Username: </label>
								<p id="usernameInput" class='profileInput'>
									<?php 
										if(isset($cus_username) AND !empty($cus_username)){ 
											echo $cus_username; 
										} else { 
											echo "&nbsp";
										} 
									?>
								</p>
							</div>
							<div class="CollapsiblePanelContent">
								<form class="customer_label" name="username_form" action="" method="post" enctype="application/x-www-form-urlencoded">
								    <?php 
										echo $security->csrf_token_tag(); 
								    ?>
									<label for="edit_username">Edit username</label>
									<input name="edit_username" id="edit_username" class="inputText" type="text" size="60" maxlength="60" autocorrect="off" autocapitalize="none" oninput="usernameBussAccCheck();" onblur="stopUsernameBussAccCheck();" />
									<br />
									<div id="usernameMessage" style="color:red; display:none; margin:0px;"></div>
									<div class="submitClearBtnDiv" >
										<input type="submit" name="submit_username" id="submit_username" class="submitBtn btnStyle1" value="Submit" />

										<button name="clear_username" id="clear_username" class="clearBtn btnStyle1" >Clear</button>
									</div>
								</form>
							</div>
						  </div>
						  <div id="CollapsiblePanel5" class="CollapsiblePanel">
							<!-- <label class="labelTitle">Password: </label> -->
							<div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">Password: </label>
								<?php echo "&nbsp"; ?>
							</div>
							<div class="CollapsiblePanelContent">
							  <form class="customer_label" name="form1" method="post" action="">
								  <?php 
									echo $security->csrf_token_tag(); 
								  ?>
								  <label for="edit_password">Edit password</label>
								  <input name="edit_password" type="password" id="edit_password" class="inputText" size="60" maxlength="60" autocomplete="off" autocorrect="off" autocapitalize="none" />
								  <br/>
								  <label for="password">Confirm password</label>
								  <input name="confirm_password" type="password" id="confirm_password" class="inputText" size="60" maxlength="60" autocomplete="off" autocorrect="off" autocapitalize="none" />
								  <div id="passwordMessage" style="color:red; display:none; margin:0px;"></div>
								  <div class="submitClearBtnDiv" >
								  	<input type="submit" name="submit_password" id="submit_password" class="submitBtn btnStyle1" value="Submit" />
								  </div>
							  </form>
							</div>
						  </div>
						  <div id="CollapsiblePanel6" class="CollapsiblePanel">
							<div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">Business name: </label>
								<p id="businessNameInput" class='profileInput'>
									<?php 
										if(isset($cus_business_title) AND !empty($cus_business_title)){ 
											echo $cus_business_title; 
										} else { 
											echo "&nbsp";
										} 
									?>
								</p>
							</div>
							<div class="CollapsiblePanelContent">
							  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="business_name_form" class="customer_label">
							    <?php 
									echo $security->csrf_token_tag(); 
								?>
								<label for="edit_business_name">Edit business name</label>
								<input name="edit_business_name" type="text" id="edit_business_name" class="inputText" size="60" maxlength="60" />
								<div class="submitClearBtnDiv" >
									<input type="submit" name="submit_business_name" id="submit_business_name" class="submitBtn btnStyle1" value="Submit" />

									<button name="clear_businessName" id="clear_businessName" class="clearBtn btnStyle1" >Clear</button>
								</div>
							  </form>
							</div>
						  </div>
			              <div id="CollapsiblePanel7" class="CollapsiblePanel">
				              <div class="CollapsiblePanelTab" tabindex="0"  >
			                	<label class="labelTitle">Business description: </label>
								<p id="businessDescInput" class='profileInput'>
								<?php 
									if(isset($cus_business_description) AND !empty($cus_business_description)){ 
										echo ucfirst($cus_business_description); 
									} else { 
										echo "&nbsp";
									}
									// echo "Provide a description of your business, so customers will have an insight about you."; // echo "&nbsp";?>
								</p>
			                  </div>
			    	          <div class="CollapsiblePanelContent">
			    	            <form method="post" action="" enctype="application/x-www-form-urlencoded" name="business_description_form" id="business_description_form">
								  			<?php 
													echo $security->csrf_token_tag(); 
								  			?>
			    	              <label for="business_description">Edit business description</label> 
												  <textarea name="business_description" id="business_description" class="inputText" cols="65" rows="2"></textarea>
												  <label id="wordCountLabel">
													  	Character Count:
													  <input type="text" id="wordCount" readonly value="0/250" style="width: 60px; text-align: right;">
													</label>
												  <br/>
												  <input type="submit" name="submit_business_description" id="submit_business_description" class="submitBtn btnStyle1" value="Submit" style="margin-top: 5px;"/>

												  <button name="clear_businessDescInput" id="clear_businessDescInput" class="clearBtn btnStyle1" >Clear</button>
			  	              </form>
			    	          </div>
			              </div>
							  <div id="CollapsiblePanel8" class="CollapsiblePanel">
								<div class="CollapsiblePanelTab" tabindex="0">
									<label class="labelTitle">Email: </label>
									<p id="emailInput" class='profileInput'>
									<?php 
										if(isset($cus_email) AND !empty($cus_email)){ 
											echo $cus_email; 
										} else { 
											echo "&nbsp";
										} 
									?>
									</p>
								</div>
								<div class="CollapsiblePanelContent">
							  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="business_email_form" class="customer_label">
							    <?php 
										echo $security->csrf_token_tag(); 
									?>
									<label for="edit_business_email">Edit email</label>
									<input name="edit_business_email" type="email" id="edit_business_email" class="inputText" size="60" maxlength="60" autocomplete="off" autocorrect="off" autocapitalize="none" />
									<div class="submitClearBtnDiv" >
										<input type="submit" name="submit_business_email" id="submit_business_email" class="submitBtn btnStyle1" value="Submit" />

										<button name="clear_email" id="clear_email" class="clearBtn btnStyle1" >Clear</button>
									</div>
							  </form>
							</div>
						  </div>
						  <div id="CollapsiblePanel9" class="CollapsiblePanel">
							<div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">Phone number: </label>
								<p id="phoneNumberInput" class='profileInput'>
								<?php 
									if(isset($cus_phone_number) AND !empty($cus_phone_number)){ 
										echo $cus_phone_number; 
									} else { 
										echo "&nbsp";
									}
								?>
								</p>
								<?php	
									if (isset($phone_validated) && !$phone_validated) { ?>
										<p id="validateNumber">Validate phone number</p> <?php
									}
								?>					
							</div>
							<div class="CollapsiblePanelContent">
							  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="business_phone_number_form" class="customer_label">
							    <?php 
									echo $security->csrf_token_tag(); 
								?>
								<label for="edit_phone_number">Edit phone number</label>
								<input name="edit_phone_number" type="tel" id="edit_phone_number" class="inputText" size="60" maxlength="60" />
								<div class="submitClearBtnDiv" >
									<input type="submit" name="submit_phone_number" id="submit_phone_number" class="submitBtn btnStyle1" value="Submit" />

									<button name="clear_phoneNumber" id="clear_phoneNumber" class="clearBtn btnStyle1" >Clear</button>
								</div>
								
								<?php
								    // $_SESSION['phone_validated']
									if (isset($phone_validated) && !$phone_validated) { ?>
										<br/><br/>
										<p> Click the button below to receive a token which you would use to verify your phone number. </p>
										<input name="verifyNumber" type="submit" id="verifyNumber" class="submitBtn btnStyle1" value="Verify phone number" /> <?php
									}
								?>
								
								<?php 
									if ($_SESSION['cus_showTokenInputField']) { ?>
										<br/> <br/>
										<p> Enter the token received from your phone in the input field below. </p>
										<input style="float:none" name="smsToken" type="text" id="smsToken" class="inputText" size="60" maxlength="60" />
										<input type="submit" name="submit_smsToken" id="submit_smsToken" class="submitBtn btnStyle1" value="Submit Token" />
										<?php
									} 
								?>
							  </form>
							</div>
						  </div>
						  <div id="CollapsiblePanel10" class="CollapsiblePanel">
							<div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">Address line 1: </label>
								<p id="addressLine1Input" class='profileInput'>
								<?php 
									if(isset($cus_addressLine1) AND !empty($cus_addressLine1)){ 
										echo ucfirst($cus_addressLine1); 
									} else { 
										echo "&nbsp";
									} 
								?>
								</p>
							</div>
							<div class="CollapsiblePanelContent">
							  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="address_line_1_form" class="customer_label">
							    <?php 
									echo $security->csrf_token_tag(); 
								?>
								<label for="edit_address_line_1">Edit address line 1</label>
								<input name="edit_address_line_1" type="text" id="edit_address_line_1" class="inputText" size="60" maxlength="60" />
									<div class="submitClearBtnDiv" >
										<input type="submit" name="submit_address_line_1" id="submit_address_line_1" class="submitBtn btnStyle1" value="Submit" />

										<button name="clear_addressLine1" id="clear_addressLine1" class="clearBtn btnStyle1" >Clear</button>
									</div>
							  </form>
							</div>
						  </div>
						  <div id="CollapsiblePanel11" class="CollapsiblePanel">
							<div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">Address line 2: </label>
								<p id="addressLine2Input" class='profileInput'>
								<?php 
									if(isset($cus_addressLine2) AND !empty($cus_addressLine2)){ 
										echo ucfirst($cus_addressLine2); 
									} else { 
										echo "&nbsp";
									} 
								?>
								</p>
							</div>
							<div class="CollapsiblePanelContent">
							  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="address_line_2_form" class="customer_label">
							    <?php 
									echo $security->csrf_token_tag(); 
								?>
								<label for="edit_address_line_2">Edit address line 2</label>
								<input name="edit_address_line_2" type="text" id="edit_address_line_2" class="inputText" size="60" maxlength="60" />
								<div class="submitClearBtnDiv" >
									<input type="submit" name="submit_address_line_2" id="submit_address_line_2" class="submitBtn btnStyle1" value="Submit" />

									<button name="clear_addressLine2" id="clear_addressLine2" class="clearBtn btnStyle1" >Clear</button>
								</div>
							  </form>
							</div>
						  </div>
						  <div id="CollapsiblePanel12" class="CollapsiblePanel">
							<!-- <label class="labelTitle">Address line 3: </label> -->
							<div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">Address line 3: </label>
								<p id="addressLine3Input" class='profileInput'>
								<?php 
									if(isset($cus_addressLine3) AND !empty($cus_addressLine3)){ 
										echo ucfirst($cus_addressLine3); 
									} else { 
										echo "&nbsp";
									} 
								?>
								</p>
							</div>
							<div class="CollapsiblePanelContent">
							  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="address_line_3_form" class="customer_label">
							    <?php 
									echo $security->csrf_token_tag(); 
								?>
								<label for="edit_address_line_3">Edit address line 3</label>
								<input name="edit_address_line_3" type="text" id="edit_address_line_3" class="inputText" size="60" maxlength="60" />
								<div class="submitClearBtnDiv" >
									<input type="submit" name="submit_address_line_3" id="submit_address_line_3" class="submitBtn btnStyle1" value="Submit" />

									<button name="clear_addressLine3" id="clear_addressLine3" class="clearBtn btnStyle1" >Clear</button>
								</div>
							  </form>
							</div>
						  </div>
						  <div id="CollapsiblePanel13" class="CollapsiblePanel">
							<div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">State: </label>
								<p id="stateInput" class='profileInput'>
								<?php 
									if(isset($cus_state) AND !empty($cus_state)){ 
										echo ucfirst($cus_state); 
									} else { 
										echo "&nbsp";
									} 
								?>
								</p>
							</div>
							<div class="CollapsiblePanelContent">
							  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="state_form" class="customer_label">
							    <?php 
									echo $security->csrf_token_tag(); 
								?>
								<p class='selectStyle'>State
									<select name='edit_state' id='edit_state' onchange='getTowns(this.id, "town")'>
										<?php echo displayStateOptions(); ?>
									</select>
						      	</p>
						      <div class="submitClearBtnDiv" >
						      	<input type="submit" name="submit_state" id="submit_state" class="submitBtn btnStyle1" value="Submit" />

						      	<button name="clear_state" id="clear_state" class="clearBtn btnStyle1" >Clear</button>
						      </div>
							  </form>
							</div>
						  </div>
						  <div id="CollapsiblePanel14" class="CollapsiblePanel">
							<div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">Town: </label>
								<p id="townInput" class='profileInput'>
								<?php 
									if(isset($cus_town) AND !empty($cus_town)){ 
										echo ucfirst($cus_town); 
									} else { 
										echo "&nbsp";
									} 
								?>
								</p>
							</div>
							<div class="CollapsiblePanelContent" id='townContent'>
							  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="town_form" class="customer_label">
							    <?php 
									echo $security->csrf_token_tag(); 
								?>

								<p class='selectStyle'> Town
									<select name='town' id='town' onchange='showTownTextArea(this.id);'>
										<?php 
											if (isset($cus_state)  AND !empty($cus_state)) {
												echo displayTownOptions($cus_state);
											} else {
												echo "Select a state";
											}
										 ?>
										<!-- <option value=''>Select</option> -->
									</select>
						      	</p>
						      	<p id='otherTown'>
								<label for="edit_town">Edit town</label>
								<input name="edit_town" type="text" id="edit_town" class="inputText" size="60" maxlength="60" />
								</p>
								<!--
								<input type="submit" name="submit_town" id="submit_town" class="btnStyle1" value="Submit" />
								-->
									<div class="submitClearBtnDiv" >
						      	<input type="submit" name="submit_town" id="submit_town" class="submitBtn btnStyle1" value="Submit" />

						      	<button name="clear_town" id="clear_town" class="clearBtn btnStyle1" >Clear</button>
						      </div>
							  </form>
							</div>
						  </div>
						  <div id="CollapsiblePanel15" class="CollapsiblePanel">
							<div class="CollapsiblePanelTab" tabindex="0">
								<label class="labelTitle">Business Category: </label>
								<p id="businessCategoryInput" class='profileInput'>
								<?php 
									// null !== $businessCategory->selected_business_categoty() AND !empty($businessCategory->selected_business_categoty())
									// $busCategory = $businessCategory->selected_business_categoty();
									if (isset($businessCategory->technician) AND $businessCategory->technician == TRUE) {
										$busCategory = "Technician";
									} elseif (isset($businessCategory->spare_part_seller) AND $businessCategory->spare_part_seller == TRUE) {
										$busCategory = "Spare part seller";
									} elseif (isset($businessCategory->artisan) AND $businessCategory->artisan == TRUE) {
										$busCategory = "Artisan";
									} elseif (isset($businessCategory->seller) AND $businessCategory->seller == TRUE) {
										$busCategory = "Seller";
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
								</p>
							</div>
							<div class="CollapsiblePanelContent">
							  <form id="business_category_form" name="business_category_form" method="post" action="" enctype="application/x-www-form-urlencoded">
							    <?php // echo $security->csrf_token_tag(); ?>
								  <!--
								  <label>
									<input type="radio" name="business_category" value="mobile_market" id="business_category_0" />
									Mobile market</label>
								  <label>
									<input type="radio" name="business_category" value="artisan" id="business_category_0" />
									Artisan</label>
								  <label>
									<input type="radio" name="business_category" value="technician" id="business_category_1" />
									Technician</label>
								  <label>
									<input type="radio" name="business_category" value="spare_part_seller" id="business_category_2" />
									Spare part seller</label>
									<br/>
								  <input type="submit" name="submit_business_category" id="submit_business_category" class="btnStyle1" value="Submit"/>
								  <button name="clear_businessCategory" id="clear_businessCategory" class="clearBtn btnStyle1" >Clear</button>
									-->
							  </form>
							</div>
						  </div>
						  <!-- $busCategory !== "Artisan" -->
						  <?php if ($busCategory === "Technician" || $busCategory === "Spare part seller") { ?>
						  <div id="CollapsiblePanel16" class="CollapsiblePanel">
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
								?>
								<p id="vehicleCategoryInput" class='profileInput'>
								<?php
									if($vehicleType){ 
										echo $vehicleType; 
									} else { 
										echo "&nbsp";
									} 
								?>
								</p>
							</div>
							<div class="CollapsiblePanelContent">
							  <form id="vehicle_category_form" name="vehicle_category_form" method="post" action="" enctype="application/x-www-form-urlencoded">
							      <?php 
									echo $security->csrf_token_tag(); 
								  ?>
								  <label>
									<input type="radio" name="vehicle_category" value="cars" id="vehicle_category_0" />
									Cars</label>
								  <label>
									<input type="radio" name="vehicle_category" value="buses" id="vehicle_category_1" />
									Buses</label>
								  <label>
									<input type="radio" name="vehicle_category" value="trucks" id="vehicle_category_2" />
									Trucks</label>
									<div class="submitClearBtnDiv" >
									  <input type="submit" name="submit_vehicle_category" id="submit_vehicle_category" class="submitBtn btnStyle1" value="Submit" style="clear:both"/>

									  <button name="clear_vehicleCategory" id="clear_vehicleCategory" class="clearBtn btnStyle1" >Clear</button>
									</div>
							  </form>
							</div>
						  </div>
						  <?php } ?>
						  
						  <!-- $busCategory !== "Artisan" || $busCategory !== "Seller" -->
						  <?php if ($busCategory === "Technician" || $busCategory === "Spare part seller") { ?>
						  <div id="CollapsiblePanel17" class="CollapsiblePanel"><!-- onclick="populateCheckboxesOfChoice('<?php // echo $vehicleType; ?>');" -->
							<div  class="CollapsiblePanelTab" tabindex="0"  >  
								<!-- <h4 style="margin:0px; float:left; padding-right:10px">Vehicle specialization: </h4> -->
								<label class="labelTitle">Vehicle specialization: </label>
								<div id="vehicleSpecializationInput" class='profileInput'>
								<?php echo displayVehSpecializatn($vehicleType); ?>
								</div>
							</div>
							<div id="CollapsiblePanelContentCarBrands" class="CollapsiblePanelContent" style="position: relative;">
							  <form id="cars_specialization_form" name="cars_specialization_form" method="post" action="" enctype="application/x-www-form-urlencoded">
							    <?php 
									echo $security->csrf_token_tag(); 
								?>
							    <?php echo displayVehCheckBoxes($vehicleType); ?>
								  <div class="submitClearBtnDiv" >
										<input type="submit" name="submit_vehicle_brands" id="submit_vehicle_brands" class="submitBtn btnStyle1" value="Submit" style="clear:both"/>

										<button name="clear_vehicleSpecialization" id="clear_vehicleSpecialization" class="clearBtn btnStyle1" >Clear</button>
									</div>
							  </form>
							</div>
						  </div>
						  <?php } ?>
						  
						  <div id="CollapsiblePanel18" class="CollapsiblePanel">
							<div  class="CollapsiblePanelTab" tabindex="0">  
								<!-- <h4 style="margin:0px; float:left; padding-right:10px">Technical services: </h4> -->
								<label id="servOrPartId" class="labelTitle">				
									<?php 
										if ($busCategory === "Artisan") {
											echo "Artisan skills:";
										} elseif ($busCategory === "Seller") {
											echo "Inventory:";
										} elseif ($busCategory === "Technician") {
											echo "Technical services:";
										} elseif ($busCategory === "Spare part seller") {
											echo "Spare parts inventory:";
										} 
									?> 
								</label>
								<div id="businessServicesInput" class='profileInput'>
								<?php echo displayArtisansServParts($busCategory); ?>
								</div>
							</div>
							<div id="CollapsiblePanelContentTechServ" class="CollapsiblePanelContent">
							  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="technical_services_form" id="technical_services_form">
							    <?php 
									echo $security->csrf_token_tag(); 
								?>
							    <?php echo displayBussCateCheckBoxes($busCategory); ?>
								<!--
								  <label>
									<input type="checkbox" name="technical_services[]" value="engine_service" id="technical_services_14" class="btnStyle1" />
									Engine service</label>
								-->
								<!-- Check if the vehicle type is selected, before the submit button for the technical services can be visible -->
								<?php 
									if ($busCategory === "Artisan" || $busCategory === "Seller") {
								?>
									<input type="submit" name="submit_services_parts" id="submit_services_parts" class="submitBtn btnStyle1" value="Submit" style="clear:both"/>

									<button name="clear_businessServices" id="clear_businessServices" class="clearBtn btnStyle1" >Clear</button>
								<?php
									} elseif ($busCategory === "Technician" || $busCategory === "Spare part seller") {
										if (isset($carBrands) || isset($busBrands) || isset($truckBrands)) {
								?>
										<div class="submitClearBtnDiv" >
											<input type="submit" name="submit_services_parts" id="submit_services_parts" class="submitBtn btnStyle1" value="Submit" style="clear:both"/>

											<button name="clear_businessServices" id="clear_businessServices" class="clearBtn btnStyle1" >Clear</button>
										</div>
										<?php
										} else {
										?>
											<p style="color:#A51300">Select a vehicle specialization.</p>
										<?
										}
										?>
								<?php } ?>
							  </form>
							</div>
						  </div>
			              
						  <!-- 
						  <div id="delete">
							  <!-- <a href="#">Delete Account</a>
							  <form action="" method="post" enctype="application/x-www-form-urlencoded" name="delete_form" id="delete_form">
								<input type="submit" name="delete_account" id="delete_account" value="Delete Account" onclick="alert(You are about to delete this account. Proceed?);"/>
							  </form>
						  </div>
						  -->
	          </div>          
	          <!-- End of collapsible panel div -->

	            <!-- Begining of Profile Image -->
	            <div id='profileImageDiv'>
	           		<div id='showProfileImage'>
	           			<img id='avatarImage' src='<?php echo showAvatar(); ?>' alt='customer profile image' />
	           		</div>
	           		<div id='selectProfileImage'>
	           			<!-- <h3 class='divHeading'>Upload Profile Photo</h3> -->
	           			<div class='divContent'>
	           			<input name="avatar_upload" type="file" id="avatar_upload" size="30" maxlength="30" accept="image/" class="fileUpload btnStyle1"/>
		                <button type="button" class="fileUploadBtn btnStyle1"><i class="fas fa-image"></i>Upload Profile Photo</button>
		                <span class="fileUploadLabel"></span>
		                <p id="imgTypeLabel">Allowed images: .jpg, .jpeg, .png, .gif</p>
		                <button id="submitAvatar" name="submitAvatar" class="submitBtn btnStyle1">Submit</button>
		                <button id="reselectAvatar" name="reselectAvatar" class="submitBtn btnStyle1" >Reselect Avatar</button>
		                <progress id="avatarProgressBar" class="progress" value="0" max="100" ></progress>
	                  <div class="display_img" >
	                    <img src="../images/emptyImageIcon.png" alt="" id="avatar_show" class="previewImg" >
	                  </div>
	                  <div id="avatarErrorReport" ></div>
		            	</div>
	           		</div>           		
	            </div>            
	          </div>
	          <!-- End of Profile Image Tab -->          
			  
			  <!-- Begining of photo gallery tab -->
	          <div class="TabbedPanelsContent">
			    <!-- <button id="uploadPhotoBtn" > Upload Photo </button> -->
				<div id="uploadCtrl">
					<a class="scroll" id="uploadPhotoBtn"  href="#photoUpload"> <i class="fas fa-image"></i> Upload Photo </a>
				</div>
	            <div class="CusPhotoGallery">
				  <?php $i = 1; ?>
	              <?php foreach($customerPictures as $photoObj): ?>
	                <div id="displayPicture<?php echo $i; ?>" class="displayPicture">
					  <img name="cus-ad-image" id="cus-ad-image<?php echo $i; ?>" class="cus-ad-image" src="<?php echo $photo->make_image_path($photoObj->filename); ?>" width="200" height="200" alt="customer ad image" />
	                  <div class="adStatusDiv">
	                  	<p id="adStatus<?php echo $photoObj->id; ?>" class="adStatus">
	                  	<?php 
	                  		if ($photoObj->ad_photo) {
	                  			echo "Cover Photo";
	                  		}
	                  	?>
	                  	</p>
	                  	<?php 
	                  		if (!$photoObj->ad_photo) {
	                  			echo '<p id="imageCaption'.$i.'" class="imageCaption" >'.$photoObj->caption.'</p>';
	                  		}
	                  	?>                  	
	                  <!-- Hide the the customer Id -->
					  <input type="hidden" name="customerId" class="customerId" value="<?php echo $customerID; ?>" />
					  <!-- Hide the the image Id -->
					  <input type="hidden" name="imageId" id="imageId<?php echo $i; ?>" value="<?php echo $photoObj->id; ?>" />
					  </div>
	                </div>
					<?php $i++; ?>
	              <?php endforeach;?>
				    <!-- Record the number of images in the div in a hidden element -->
					<input type="hidden" name="imageCounter" id="imageCounter" value="<?php echo $i; ?>" />
	            </div>
	            <div id="photoUpload">
								<form action="" method="post" enctype="multipart/form-data" name="profileEdit" >
							    <?php 
									echo $security->csrf_token_tag(); 
									?>
	                <br/>
	                <div id="uploadPhotoEdit" >
	                  <h3 class="divHeading">Upload Photos</h3>
	                  <div class="divContent">
		                  <input name="photo_upload" type="file" id="photo_upload" size="30" maxlength="30" accept="image/" class="fileUpload btnStyle1"/>
		                  <button type="button" class="fileUploadBtn btnStyle1"><i class="fas fa-image"></i>Choose Your Photos</button>
		                  <span class="fileUploadLabel"></span>
		                    <p id="imgTypeLabel">Allowed images: .jpg, .jpeg, .png, .gif</p>
		                  <p class="bussDescrpLabel">			Caption:
		                    <input type="text" id="caption" name="caption" value="" class="captionText"/>
		                  </p>
		                  
		                  <button id="submitPhoto" name="submitPhoto" class="submitBtn btnStyle1">Submit</button>
		                  <button id="reselectPhoto" name="reselectPhoto" class="submitBtn btnStyle1" >Reselect Photo</button>
		                  <br>
		                  <progress id="imageProgressBar" class="progress" value="0" max="100" ></progress>
		                  <div class="display_img" >
	                      <img src="../images/emptyImageIcon.png" alt="" id="img_show" class="previewImg">
	                    </div>
	                    <div id="imgErrorReport" ></div>
	              	  </div>
	                </div>
	              </form>			    
	            </div>
							
	          </div>
	          <!-- Begining of Customers Comments -->
			  <div class="TabbedPanelsContent">
	            <!-- Here we would display the comments. Firstly, we would loop through the comment array and display them one after the other. -->
	            <div id="comments">
					<div id="feedback"></div>
					<p id='totalComments'><?php echo User_Comment::count_by_id($customerID); ?> comment(s)</p>
					<?php $i = 0; ?>
				  <?php foreach($comments as $comment): ?>
					<div class="comment" id="comment<?php echo $i; ?>">
						<div class="authorDate" id="authorDate<?php echo $i; ?>">
							<?php 
								// 
								if (!is_null($comment->customer_id_comment) && !empty($comment->customer_id_comment)) {
									$userOrCusIdReplyTo = $comment->customer_id_comment;
									$accountType = "customer";
								} elseif (!is_null($comment->user_id_comment) && !empty($comment->user_id_comment)) {
									$userOrCusIdReplyTo = $comment->user_id_comment;
									$accountType = "user";		
								}
							?>
							<div class="author" id="author<?php echo $i; ?>" userorcusidreplyto="<?php echo $userOrCusIdReplyTo; ?>" accounttype="<?php echo $accountType; ?>">
								<?php 
									// 
									if (!is_null($comment->customer_id_comment) && !empty($comment->customer_id_comment)) {
										$userOrCusId = $comment->customer_id_comment;
										$customer = Customer::find_by_id($userOrCusId);
										$full_name = $customer->full_name();
										echo htmlentities($full_name);
									} elseif (!is_null($comment->user_id_comment) && !empty($comment->user_id_comment)) {
										$userOrCusId = $comment->user_id_comment;
										$user = User::find_by_id($userOrCusId);
										$full_name = $user->full_name();
										echo htmlentities($full_name);
									}
								?> commented:
							</div>
							<div class="meta-info" id="meta-info<?php echo $i; ?>">
								<!-- The datetime_to_text() function is in the function file. It can be referenced from any class. -->
								<?php echo datetime_to_text($comment->created); ?>
							</div>
						</div>
						<div class="commentBody" id="commentBody<?php echo $i; ?>" <?php echo "commentid".$i; ?>="<?php echo $comment->id; ?>">
							<?php 
							// The strip_tags will not allow any other HTML tags to be used for formating except these tags <strong><em><p>
							echo strip_tags($comment->body, '<strong><em><p>'); 
							?>
						</div>
						<button id="replyBtn<?php echo $i; ?>" class="replyBtn btnStyle3" onclick="reply(this)">Reply Comment</button>
						<!-- The reply div will be inserted here after the reply button is clicked -->
						<div class="replyContainer" id="replyContainer<?php echo $i; ?>">
						<?php $replies = User_Reply::find_replies_on_comment($customerID, $comment->id);
						$j = 0; ?>
						<?php foreach($replies as $reply): ?>
							
							<div id="replyComment<?php echo $i; echo $j; ?>" class="replyComment">
								<div class="authorDate" id="replyAuthorDate<?php echo $i; echo $j; ?>">
									<div class="author" id="replyAuthor<?php echo $i; echo $j; ?>">
										You replied:
									</div>
									<div class="meta-info" id="meta-info<?php echo $i; echo $j; ?>">
										<?php echo datetime_to_text($reply->created); ?>
									</div>
								</div>
								<div class="commentBody" id="replyCommentBody<?php echo $i; echo $j; ?>">
									<?php 
									echo strip_tags($reply->body, "<strong><em><p>"); 
									?>
								</div>
								<!-- <button class="replyBtn" id="replyBtn<?php // echo $j; ?>" onclick="reply(this)">Reply Comment</button> -->
							</div>
							<?php $j++ ?>
						<?php endforeach; ?>
						</div>
					</div>
					<?php $i++ ?>
				  <?php endforeach; ?>
				    <!-- This is a hidden div that only gets inserted when it is clicked -->
					<div id="replyDiv">
						<textarea id="replyTextarea" class="replyTextarea" name="message_content" rows="2"></textarea>
						<button id="submitReply" class="submitReply btnStyle3" customerId="<?php echo urlencode($customerID); ?>" onclick="addReply(this)">Reply</button>
					</div>
	                <!-- If no comment, display there is none -->
	                <?php if(empty($comments)) { echo "No Comments from users."; } ?>
	            </div>
	          </div>
			  <!-- End of Customers comments -->
			  <div class="TabbedPanelsContent" >
			    <div id="comments">
					<div id="feedback2"></div>
					<?php 
						// Find all the comments made by the user, then find all the replies to the comments
						// Get all the replies from customers to this user
						$comments = User_Comment::find_comments_made_by_user($customerID, "customer");
						
						$i = 0; 
						foreach($comments as $comment):
					?>
					<div class="comment" id="comment<?php echo $i; ?>">
						<div class="authorDate" id="authorDate<?php echo $i; ?>">
							<div class="author" id="author2<?php echo $i; ?>" >
								Your comment on
								<?php 
									$commentCustomerId = $comment->customers_id;
									$customer = Customer::find_by_id($commentCustomerId);
									$full_name = $customer->full_name();
									echo $full_name; 
								?>:
							</div>
							<div class="meta-info" id="meta-info<?php echo $i; ?>">
								<!-- The datetime_to_text() function is in the function file. It can be referenced from any class. -->
								<?php echo datetime_to_text($comment->created); ?>
							</div>
						</div>
						<div class="commentBody" id="commentBody2<?php echo $i; ?>" <?php echo "commentid".$i; ?>="<?php echo $comment->id; ?>">
							<?php 
							// The strip_tags will not allow any other HTML tags to be used for formating except these tags <strong><em><p>
							echo strip_tags($comment->body, '<strong><em><p>'); 
							?>
						</div>
						
						<div class="replyContainer" id="replyContainer<?php echo $i; ?>">
						<?php $replies = User_Reply::find_replies_on_comment($commentCustomerId, $comment->id);
						$j = 0; ?>
						<?php foreach($replies as $reply): ?>
							
							<div id="replyComment<?php echo $i; echo $j; ?>" class="replyComment">
								<div class="authorDate" id="replyAuthorDate<?php echo $i; echo $j; ?>">
									<div class="author" id="replyAuthor<?php echo $i; echo $j; ?>" userorcusidreplyto="<?php echo $customerID; ?>">
										<?php 
											$replyCustomerId = $reply->customers_id;
											$customer = Customer::find_by_id($replyCustomerId);
											$full_name = $customer->full_name();
											echo $full_name; 
										?> replied:
									</div>
									<div class="meta-info" id="meta-info<?php echo $i; echo $j; ?>">
										<?php echo datetime_to_text($reply->created); ?>
									</div>
								</div>
								<div class="commentBody" id="replyCommentBody<?php echo $i; echo $j; ?>" <?php echo "replyid".$j; ?>="<?php echo $reply->id; ?>">
									<?php 
									echo strip_tags($reply->body, "<strong><em><p>"); 
									?>
								</div>
								<!-- <button class="replyBtn2" id="replyBtn<?php // echo $j; ?>" onclick="reply(this)">Reply Comment</button> -->
							</div>
							<?php $j++ ?>
						<?php endforeach; ?>
						</div>
					</div>
					<?php endforeach; ?>
					<!-- If no comment, display there is none -->
					<?php if(empty($comments)) { echo "You have no comments made yet."; } ?>
				</div>
				<!-- This is a hidden div that only gets inserted when it is clicked -->
				<!--
				<div id="replyDiv">
					<textarea id="replyTextarea2" class="replyTextarea2" name="message_content" rows="2"></textarea>
					<button id="submitReply2" class="submitReply2" customerId="<?php // echo urlencode($customerID); ?>" onclick="saveReply(this)">Reply</button>
				</div>
				-->			
			  </div>
	          <div class="TabbedPanelsContent" >
							<div class="availabilityForm">
							<!-- class="form_style" float:left-->
				        <form id="form2" name="form2" method="post" action="" >
								<?php 
									echo $security->csrf_token_tag(); 
								?>
								<div class="set_availability">
									<h3 class='divHeading'>Set Time for Appointments</h3>
									<div class='divContent'>
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
										<br/>
										<label>
										<input type="checkbox" name="set_hours[]" value="four_to_five_pm" id="four_to_five_pm" />
										4:00 PM - 5:00 PM</label>
										<br/>
										<label>
										<input type="checkbox" name="set_hours[]" value="five_to_six_pm" id="five_to_six_pm" />
										5:00 PM - 6:00 PM</label>
										<br/>
										<label>
										<input type="checkbox" name="set_hours[]" value="six_to_seven_pm" id="six_to_seven_pm" />
										6:00 PM - 7:00 PM</label>
										<br/>
										<label>
										<input type="checkbox" name="set_hours[]" value="seven_to_eight_pm" id="seven_to_eight_pm" />
										7:00 PM - 8:00 PM</label>
										<br/>
										<label>
										<input type="checkbox" name="set_hours[]" value="eight_to_nine_pm" id="eight_to_nine_pm" />
										8:00 PM - 9:00 PM</label>
										<br/>
										<label>
										<input type="checkbox" name="set_hours[]" value="nine_to_ten_pm" id="nine_to_ten_pm" />
										9:00 PM - 10:00 PM</label>
										
									  </p>
									  <p>
										<input type="submit" name="submit_availability" id="submit_availability" class="btnStyle1" value="Submit" />
									  </p>
									</div>
								</div>
				            </form>
							</div>
							<div class="displayAppointments">
								<h3 class="divHeading"> List of your created schedules for a week </h3>
								<?php echo displayCustomerAvailability(); ?>
							</div>
	          </div>
	          <div class="TabbedPanelsContent">
	            <div class="appointmentRequestList">
	              <h3 class="divHeading"> List of requested appointments from your customers </h3>
	              <?php echo displayCustomerBookings(); ?> </div>
	            <div class="acceptedAppointmentList">
	              <h3 class="divHeading"> List of your accepted appointments</h3>
	              <?php echo displayAcceptedAppointments(); ?> </div>
	          </div>
	          <div class="TabbedPanelsContent">
							<div class="appointmentRequestList">
								<h3 class="divHeading">Appointments requested with technicians</h3>
								<?php echo displayDeclinedAppointmentsCus(); ?>
								<?php echo displayCanceledAppointmentsCus(); ?>
								<?php echo displayRequestedAppointmentsCus(); ?>
							</div>
							<div class="acceptedAppointmentList">
								<h3 class="divHeading">Appointments confirmed with technicians</h3>
								<?php echo displayConfirmedAppointmentsCus(); ?>
							</div>
			  		</div>
	        </div>
	      </div>
	      
	      <!-- Ending of tabbed panel div -->
	    </div>
		
			<!-- Scroll to top widget -->
			<div class="to-top-div" style="display: block;">
				<a class="to-top" href="#mainServicePage"> ^ </a>
			</div>
	  </div> <!-- End of Main Section -->

	  <!-- Display the footer section -->
	  <?php include_layout_template('public_footer.php'); ?>
	</div>

	<!-- This control the modal for the customer's avatar -->
	<div class="avatarUploadModal">
		<div class="avatarUploadContent">
			<!-- <div class="closeAvatarUploadModal" >+</div> -->	          		
	  		<div id='showImage'></div>
	   		<div id='decisionBtnsDiv'>
	   			<button id='rotateRightBtn' class='btnStyle1' data-deg='90'>Rotate</button>
	   			<button id='cropImage' class=' croppie-result btnStyle1'>Crop</button>
					<button id='changeImage' class='btnStyle1'>Cancel Upload</button>
				</div>
				<div id='displayCrop' ></div>							
		</div>
	</div>

	<!-- The modal that contains the uploaded photo -->
	<div class="photoUploadModal" >
		<div class="photoUploadContent" >
			<!-- <div class="closePhotoUploadModal" >+</div> -->
			<!-- Placement of uploaded image to be cropped -->
				
				<div id='imageBox' ></div>
				<!--
				<div id='rotateBtns'>
					<button id='rotateLeft' class='btnStyle1' data-deg='-90'>Rotate Left</button>
					<button id='rotateRight' class='btnStyle1' data-deg='90'>Rotate Right</button>
				</div>
				-->
				<div id='decisionBtns'>
					<button id='rotateRight' class='btnStyle1' data-deg='90'>Rotate</button>
					<button id='crop' class='crop_button croppie-result btnStyle1'>Crop</button>
					<button id='removeImage' class='btnStyle1'>Change Image</button>
				</div>
				<div id='display' ></div>
			
		</div>
	</div>

	<!-- This display the modal to project a larger image when clicked-->
	<div class="bg-modal" >
		<div class="modal-content" >
			<div class="close-btn" >+</div>
			<div id="enlargedAdImgDiv">
				<img id="enlargedAdImg" src="" alt="Enlarged selected photo of entrepreneur" > 
				<button id='adLike' class='adLike' onclick='saveLike(this)'><span><i class='fas fa-thumbs-up'></i></span></button>
				
				<p id="imgCaptionModal" ></p>
				<button id="editPhoto" class="btnStyle1" > Edit Caption </button>
				<p id='captionInfo'> Enter a minimum of 250 words </p>

				<textarea name="photoCaptionBox" id="photoCaptionBox" cols="40" rows="2" placeholder="Make changes to your photo caption here" ></textarea>

				<input id="changeCaption" class="btnStyle1" name="changeCaption" type="submit" value="Chage Caption" />
				<div id="captionResultDiv" ></div>
				<button id="setAdPhoto" class="btnStyle1"> Set Image as Cover Photo</button>
				<p id='notifyMessage'></p>
				<button id="deletePhoto" class="btnStyle1" > Delete Photo </button>
			</div>
			<div id="modalPhotoBtnDiv">
				<div id="photoCommentDisplay">
					<div id="feedback"></div>
					<p id='totalComments'></p>
					<div id="photoComments">
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Loader -->
	<div class="loader">
		<img src="../images/utilityImages/ajax-loader1.gif" alt="Loading..." />
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
	var CollapsiblePanel14 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel14", {contentIsOpen:false});
	var CollapsiblePanel15 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel15", {contentIsOpen:false});

	<?php if ($busCategory === "Technician" || $busCategory === "Spare part seller") {	 ?>
	var CollapsiblePanel16 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel16", {contentIsOpen:false});
	var CollapsiblePanel17 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel17", {contentIsOpen:false});
	<?php } ?>
	var CollapsiblePanel18 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel18", {contentIsOpen:false});
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
	<script type="text/javascript" src="../javascripts/usernameCheck.js"></script>
	<!-- <script type="text/javascript" src="../javascripts/passwordMatchCheck.js"></script> -->
	<!-- 
	<script type="text/javascript">
	var CollapsiblePanel17 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel17", {contentIsOpen:false});
	</script> -->
	<script type="text/javascript" src="../javascripts/genericJSs.js"></script>
	<script type="text/javascript" src="../javascripts/customerEditPage2JSScripts.js"></script>
	<script type="text/javascript" src="../javascripts/cropImage.js"></script>
</body>
</html>

<?php // Close the database when done deleting
	if(isset($database)) { $database->close_connection(); } 
?>