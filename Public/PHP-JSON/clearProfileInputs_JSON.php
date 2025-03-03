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
	global $database;
	// Check if the request is post and if it is from same web page.	
	
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['input']);
	
	if ($post_params['input'] === 'firstName') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$customer = Customer::find_by_id($customerId);
			$customer->first_name = "";
			if ($customer->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'lastName') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$customer = Customer::find_by_id($customerId);
			$customer->last_name = "";
			if ($customer->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'gender') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$customer = Customer::find_by_id($customerId);
			$customer->gender = "";
			if ($customer->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'username') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$customer = Customer::find_by_id($customerId);
			$customer->username = "";
			if ($customer->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'businessName') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$customer = Customer::find_by_id($customerId);
			$customer->business_title = "";
			
			if ($customer->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}		
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'businessDescription') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$business_category = Business_Category::find_by_customerId($customerId);
			// print_r($business_category);
			$business_category->business_description = "";
			if ($business_category->save()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}				
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'email') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$customer = Customer::find_by_id($customerId);
			$customer->customer_email = "";
			if ($customer->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'phoneNumber') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$customer = Customer::find_by_id($customerId);
			$customer->phone_number = "";
			$customer->phone_validated = 0;
			
			if ($customer->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'addressLine1') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$address = Address::find_by_customerId($customerId);
			$address->address_line_1 = "";
			
			if ($address->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'addressLine2') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$address = Address::find_by_customerId($customerId);
			$address->address_line_2 = "";
			if ($address->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'addressLine3') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$address = Address::find_by_customerId($customerId);
			$address->address_line_3 = "";
			if ($address->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'state') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$address = Address::find_by_customerId($customerId);
			$address->state = "";
			$address->town = "";
			if ($address->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			}  else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'town') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$address = Address::find_by_customerId($customerId);
			// print_r($address);
			$address->town = "";
			if ($address->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'businessCategory') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$businessCategory = Business_Category::find_by_customerId($customerId);
			$businessType = $businessCategory->selected_business_categoty();
			if ($businessType === 'Technician') {
				$businessCategory->technician = "";
			} elseif ($businessType === 'Artisan') {
				$businessCategory->artisan = "";
			} else {
				$businessCategory->spare_part_seller = "";
			}			
			if ($businessCategory->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			}  else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'vehicleCategory') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$vehicleCategory = Vehicle_Category::find_by_customerId($customerId);
			$vehicleType = $vehicleCategory->selected_vehicle_categoty();
			if ($vehicleType === 'Cars') {
				$vehicleCategory->car = "";
			} elseif ($vehicleType === 'Buses') {
				$vehicleCategory->bus = "";
			} else {
				$vehicleCategory->truck = "";
			}

			if ($vehicleCategory->update()) {
				$jsonData["success"] = true;
				$jsonData["result"] = "cleared";
			} else {
				$jsonData["success"] = false;
				$jsonData["result"] = "not cleared";
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'vehicleSpecialization') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;

			$vehicleCategory = Vehicle_Category::find_by_customerId($customerId);
			$vehicleType = $vehicleCategory->selected_vehicle_categoty();

			if ($vehicleType === 'Cars') {
				// Get the array of cars selected
				$car_brands = Car_Brand::find_by_customerId($customerId); // Used in post / functions
				$carBrands = $car_brands->getVehicleBrands();

				foreach($carBrands as $car => $value) {
					$car_brands->{$car} = 0;
				}

				if ($car_brands->update()) {
					$jsonData["success"] = true;
					$jsonData["result"] = "cleared";	
				} else {
					$jsonData["success"] = false;
					$jsonData["result"] = "not cleared";
				}
			} elseif ($vehicleType === 'Buses') {
				// Get the array of cars selected
				$bus_brands = Bus_Brand::find_by_customerId($customerId); // Used in post / functions
				$busBrands = $bus_brands->getBusBrands();

				foreach($busBrands as $bus => $value) {
					$bus_brands->{$bus} = 0;
				}

				if ($bus_brands->update()) {
					$jsonData["success"] = true;
					$jsonData["result"] = "cleared";
				} else {
					$jsonData["success"] = false;
					$jsonData["result"] = "not cleared";
				}
			} elseif ($vehicleType === 'Trucks') {
				// Get the array of cars selected
				$truck_brands = Truck_Brand::find_by_customerId($customerId); // Used in post / functions
				$truckBrands = $truck_brands->getTruckBrands();

				foreach($truckBrands as $truck => $value) {
					$truck_brands->{$truck} = 0;
				}

				if ($truck_brands->update()) {
					$jsonData["success"] = true;
					$jsonData["result"] = "cleared";	
				} else {
					$jsonData["success"] = false;
					$jsonData["result"] = "not cleared";
				}
			}									
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} elseif ($post_params['input'] === 'businessServices') {
		// Get the customerId
		global $session;
		// Check if it is customer logged in
		if ($session->is_customer_logged_in()) {
			$customerId = $session->customer_id;
			$businessCategory = Business_Category::find_by_customerId($customerId);
			$busCategory = $businessCategory->selected_business_categoty();

			if ($busCategory === "Artisan") {
				$artisan_services = Artisan::find_by_customerId($customerId);
				$allArtisanServices = $artisan_services->getArtisans();

				foreach($allArtisanServices as $artisan => $value) {
					$artisan_services->{$artisan} = 0;
				}

				if ($artisan_services->update()) {
					$jsonData["success"] = true;
					$jsonData["result"] = "cleared";	
				} else {
					$jsonData["success"] = false;
					$jsonData["result"] = "not cleared";
				}
			} elseif ($busCategory === "Technician") {
				$technical_services = Technical_Service::find_by_customerId($customerId);
				$technicalServices = $technical_services->getTechnicalServices();

				foreach($technicalServices as $technician => $value) {
					$technical_services->{$technician} = 0;
				}

				if ($technical_services->update()) {
					$jsonData["success"] = true;
					$jsonData["result"] = "cleared";	
				} else {
					$jsonData["success"] = false;
					$jsonData["result"] = "not cleared";
				}
			} elseif ($busCategory === "Spare part seller") {
				$spare_part = spare_Part::find_by_customerId($customerId);
				$spareParts = $spare_part->getSpareParts();

				foreach($spareParts as $sparePart => $value) {
					$spare_part->{$sparePart} = 0;
				}

				if ($spare_part->update()) {
					$jsonData["success"] = true;
					$jsonData["result"] = "cleared";	
				} else {
					$jsonData["success"] = false;
					$jsonData["result"] = "not cleared";
				}
			}			
		} else {
			// Illegal attempt to access database records
			$jsonData["success"] = false;
			$jsonData["result"] = "not logged in";
		}			
	} else  {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
}
echo json_encode($jsonData);

?>

