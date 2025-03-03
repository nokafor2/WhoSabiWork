<?php 
Class Public_Ad_Display {
	// Setting up pagination, initialize it so it will have a default variable
	public $pagination;
	
	public $page;
	public $per_page;

	public function __construct($page, $per_page){
		$this->page = (int)$page;
		$this->per_page = (int)$per_page;
	}
	
	// Get the total number of customers that will be outputted.
	function count_customer_ids($vehicleType, $vehicleBrand, $techServOrSpareParts, $businessCategory, $state, $town) {
		global $database;
		$vehicleType = $database->escape_value($vehicleType);
		$vehicleBrand = $database->escape_value($vehicleBrand);
		$techServOrSpareParts = $database->escape_value($techServOrSpareParts);
		$businessCategory = $database->escape_value($businessCategory);
		$state = $database->escape_value($state);
		$town = $database->escape_value($town);

		// check if its a car_brand, bus_brand or truck_brand
		if ($vehicleType === 'car') {
			$motorBrandCategory = 'car_brands';
		} elseif ($vehicleType === 'bus') {
			$motorBrandCategory = 'bus_brands';
		} else {
			$motorBrandCategory = 'truck_brands';
		}

		// check if its a technician or spare part seller
		if ($businessCategory === 'technician') {
			$techOrPartsTable = 'technical_services';
		} else {
			$techOrPartsTable = 'spare_parts';
		}

		// Determines what query to perform if a state and town is passed
		if (empty($town)) {
			// This runs if a all is selected
			$sql = "SELECT DISTINCT COUNT(customers_id) FROM addresses WHERE state = '".$state."' AND addresses.customers_id IN (";
			$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
			$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.".$businessCategory." = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT ".$techOrPartsTable.".customers_id FROM ".$techOrPartsTable." WHERE ".$techOrPartsTable.".".$techServOrSpareParts." = 1 AND ".$techOrPartsTable.".customers_id IN (";
			$sql .= "SELECT ".$motorBrandCategory.".customers_id FROM ".$motorBrandCategory." WHERE ".$motorBrandCategory.".".$vehicleBrand." = 1 AND ".$motorBrandCategory.".customers_id IN (";
			$sql .= "SELECT vehicle_categories.customers_id FROM vehicle_categories WHERE vehicle_categories.".$vehicleType." = 1";
			$sql .= ")))));";
		} else {
			// This runs if a town is selected
			$sql = "SELECT DISTINCT COUNT(customers_id) FROM addresses WHERE state = '".$state."' AND town = '".$town."' AND addresses.customers_id IN (";
			$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
			$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.".$businessCategory." = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT ".$techOrPartsTable.".customers_id FROM ".$techOrPartsTable." WHERE ".$techOrPartsTable.".".$techServOrSpareParts." = 1 AND ".$techOrPartsTable.".customers_id IN (";
			$sql .= "SELECT ".$motorBrandCategory.".customers_id FROM ".$motorBrandCategory." WHERE ".$motorBrandCategory.".".$vehicleBrand." = 1 AND ".$motorBrandCategory.".customers_id IN (";
			$sql .= "SELECT vehicle_categories.customers_id FROM vehicle_categories WHERE vehicle_categories.".$vehicleType." = 1";
			$sql .= ")))));";
		}		
		// echo $sql;

		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		// The single number is returned in a row like an array, so the array_shift function is used to pull out the number.
		return array_shift($row);
	}

	// Get the customers ids and order them in descending order
	function get_customer_ids($vehicleType, $vehicleBrand, $techServOrSpareParts, $businessCategory, $state, $town) {
		global $database;
		global $per_page;
		global $pagination;

		$vehicleType = $database->escape_value($vehicleType);
		$vehicleBrand = $database->escape_value($vehicleBrand);
		$techServOrSpareParts = $database->escape_value($techServOrSpareParts);
		$businessCategory = $database->escape_value($businessCategory);
		$state = $database->escape_value($state);
		$town = $database->escape_value($town);

		// check if its a car_brand, bus_brand or truck_brand
		if ($vehicleType === 'car') {
			$motorBrandCategory = 'car_brands';
		} elseif ($vehicleType === 'bus') {
			$motorBrandCategory = 'bus_brands';
		} else {
			$motorBrandCategory = 'truck_brands';
		}

		// check if its a technician or spare part seller
		if ($businessCategory === 'technician') {
			$techOrPartsTable = 'technical_services';
		} else {
			$techOrPartsTable = 'spare_parts';
		}

		// Determines what query to perform if a state and town is passed
		if (empty($town)) {
			// This runs if a all is selected
			$sql = "SELECT DISTINCT customers_id FROM addresses WHERE state = '".$state."' AND addresses.customers_id IN (";
			$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
			$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.".$businessCategory." = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT ".$techOrPartsTable.".customers_id FROM ".$techOrPartsTable." WHERE ".$techOrPartsTable.".".$techServOrSpareParts." = 1 AND ".$techOrPartsTable.".customers_id IN (";
			$sql .= "SELECT ".$motorBrandCategory.".customers_id FROM ".$motorBrandCategory." WHERE ".$motorBrandCategory.".".$vehicleBrand." = 1 AND ".$motorBrandCategory.".customers_id IN (";
			$sql .= "SELECT vehicle_categories.customers_id FROM vehicle_categories WHERE vehicle_categories.".$vehicleType." = 1";
			$sql .= "))))) LIMIT {$per_page} OFFSET {$pagination->offset()};";
		} else {
			// This runs if a town is selected
			$sql = "SELECT DISTINCT customers_id FROM addresses WHERE state = '".$state."' AND town = '".$town."' AND addresses.customers_id IN (";
			$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
			$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.".$businessCategory." = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT ".$techOrPartsTable.".customers_id FROM ".$techOrPartsTable." WHERE ".$techOrPartsTable.".".$techServOrSpareParts." = 1 AND ".$techOrPartsTable.".customers_id IN (";
			$sql .= "SELECT ".$motorBrandCategory.".customers_id FROM ".$motorBrandCategory." WHERE ".$motorBrandCategory.".".$vehicleBrand." = 1 AND ".$motorBrandCategory.".customers_id IN (";
			$sql .= "SELECT vehicle_categories.customers_id FROM vehicle_categories WHERE vehicle_categories.".$vehicleType." = 1";
			$sql .= "))))) LIMIT {$per_page} OFFSET {$pagination->offset()};";
		}		
		// echo $sql;

		$result_set = $database->query($sql); // Relevant

		$count = 0; // Relevant
		$customers_ids = array();
		while($row = mysqli_fetch_assoc($result_set)){
			// Gets the customers ids 
			$customers_ids[$count] = $row["customers_id"]; 
			$count++;
		}
		
		// Eliminate duplicates and count the array.
		$sortedByRating = $this->sortByRating(array_unique($customers_ids));

		// This will return an array of customer's ids and the total number ids.
		return array($sortedByRating, count($sortedByRating));
	}

	// Get the total number of artisan customers that will be outputted.
	function count_artisan_ids($artisanType = '', $state = '', $town = ''){
		global $database;
		
		$artisanType = $database->escape_value($artisanType);
		$state = $database->escape_value($state);
		$town = $database->escape_value($town);
		
		if (empty($town)) {
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT DISTINCT COUNT(customers_id) FROM addresses WHERE state = '".$state."' AND customers_id IN (";
			$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
			$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.artisan = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT customers_id FROM artisans WHERE artisans.".$artisanType." = 1";
			$sql .= ")))";
		} else {
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT DISTINCT COUNT(customers_id) FROM addresses WHERE state = '".$state."' AND town = '".$town."' AND customers_id IN (";
			$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
			$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.artisan = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT customers_id FROM artisans WHERE artisans.".$artisanType." = 1";
			$sql .= ")))";
		}
		// echo $sql;
		
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		// The single number is returned in a row like an array, so the array_shift function is used to pull out the number.
		return array_shift($row);
	}

	// Get the artisans ids aand order them in descending order using the ratings
	function get_artisan_ids($artisanType = '', $state = '', $town = '') {
		global $database;
		global $per_page;
		global $pagination;
		
		$artisanType = $database->escape_value($artisanType);
		$state = $database->escape_value($state);
		$town = $database->escape_value($town);
		
		if (empty($town)) {
			// This will execute if all towns is selected
			$sql = "SELECT DISTINCT customers_id FROM addresses WHERE state = '".$state."' AND customers_id IN (";
			$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
			$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.artisan = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT customers_id FROM artisans WHERE artisans.".$artisanType." = 1";
			$sql .= "))) LIMIT {$per_page} OFFSET {$pagination->offset()};";				
		} else {
			// This will execute if a town is selected
			$sql = "SELECT DISTINCT customers_id FROM addresses WHERE state = '".$state."' AND town = '".$town."' AND customers_id IN (";
			$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
			$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.artisan = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT customers_id FROM artisans WHERE artisans.".$artisanType." = 1";
			$sql .= "))) LIMIT {$per_page} OFFSET {$pagination->offset()};";
		}
		
		$result_set = $database->query($sql); 

		$count = 0; 
		$customers_ids = array();
		// STEP2: Get the artisans ids in an array
		while($row = mysqli_fetch_assoc($result_set)){
			// Gets the customers ids 
			$customers_ids[$count] = $row["customers_id"]; 
			$count++; 
		}
		
		// Eliminate duplicates and count the array and sort them by ratings.
		$sortedByRating = $this->sortByRating(array_unique($customers_ids));

		// This will return an array of customer's ids and the total number ids.
		return array($sortedByRating, count($sortedByRating));
	}

	// Get the total number of artisan customers that will be outputted.
	function count_seller_ids($sellerType = '', $state = '', $town = ''){
		global $database;
		
		$sellerType = $database->escape_value($sellerType);
		$state = $database->escape_value($state);
		$town = $database->escape_value($town);
		
		if (empty($town)) {
			// This will run if all is selected for town
			$sql = "SELECT DISTINCT COUNT(customers_id) FROM addresses WHERE state = '".$state."' AND customers_id IN (";
			$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
			$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.seller = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT customers_id FROM sellers WHERE sellers.".$sellerType." = 1";
			$sql .= ")))";
		} else {
			// This will execute if a town is selected
			$sql = "SELECT DISTINCT COUNT(customers_id) FROM addresses WHERE state = '".$state."' AND town = '".$town."' AND customers_id IN (";
			$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
			$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.seller = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT customers_id FROM sellers WHERE sellers.".$sellerType." = 1";
			$sql .= ")))";
		}
		// echo $sql;
		
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		// The single number is returned in a row like an array, so the array_shift function is used to pull out the number.
		return array_shift($row);
	}	

	// Get the sellers ids aand order them in descending order using the ratings
	function get_seller_ids($sellerType = '', $state = '', $town = '') {
		global $database;
		global $per_page;
		global $pagination;
		
		$sellerType = $database->escape_value($sellerType);
		// $cus_category = $database->escape_value($cus_category);
		$state = $database->escape_value($state);
		$town = $database->escape_value($town);
		
		if (empty($town)) {
			// This will run if all is selected for town
			$sql = "SELECT DISTINCT customers_id FROM addresses WHERE state = '".$state."' AND customers_id IN (";
			$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
			$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.seller = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT customers_id FROM sellers WHERE sellers.".$sellerType." = 1";
			$sql .= "))) LIMIT {$per_page} OFFSET {$pagination->offset()};";
		} else {
			// This will execute if a town is selected
			$sql = "SELECT DISTINCT customers_id FROM addresses WHERE state = '".$state."' AND town = '".$town."' AND customers_id IN (";
			$sql .= "SELECT customers.id FROM customers WHERE customers.account_status = 'active' AND customers.id IN (";
			$sql .= "SELECT business_categories.customers_id FROM business_categories WHERE business_categories.seller = 1 AND business_categories.customers_id IN (";
			$sql .= "SELECT customers_id FROM sellers WHERE sellers.".$sellerType." = 1";
			$sql .= "))) LIMIT {$per_page} OFFSET {$pagination->offset()};";
		}
		// echo "The SQL query is: ".$sql."<br/>";
		
		$result_set = $database->query($sql); 

		$count = 0; 
		$customers_ids = array();
		// STEP2: Get the sellers ids in an array
		while($row = mysqli_fetch_assoc($result_set)){
			// Gets the customers ids 
			$customers_ids[$count] = $row["customers_id"]; 
			$count++; 
		}
		
		// Eliminate duplicates and count the array and sort them by ratings.
		$sortedByRating = $this->sortByRating(array_unique($customers_ids));

		// This will return an array of customer's ids and the total number ids.
		return array($sortedByRating, count($sortedByRating));
	}
	
	function get_photograph_ids($customer_ids) {
		global $database;
		$photograph_ids = array();
		$count = 0;
		
		foreach($customer_ids as $id) {
			$sql = "SELECT * FROM photographs WHERE customers_id = {$id} AND visible = 1 AND avatar = 0 LIMIT 1;";
			$result_set = $database->query($sql);
			
			while($row = mysqli_fetch_assoc($result_set)){ 
				// Gets the photograph ids of the customers
				$photograph_ids[] = $row["id"]; 
				$count++; 
			}
		}
		
		return $photograph_ids;
	}
	
	// returns an array of addresses ids
	function find_address_id_using_customer_id($customer_Ids_Array) {
		global $database;
		$count1 = 0;
		$addresses = array();
		
		foreach ($customer_Ids_Array as $ids) {
			$sql = "SELECT id FROM `addresses` WHERE customers_id = {$ids}";
			$result_set = $database->query($sql); // Relevant
			$row = mysqli_fetch_assoc($result_set);
			// Get all the address ids of the customers to be displayed
			$addresses[$count1] = "{$row["id"]}";
			$count1++;
		}
		
		// returns an array of addresses
		return $addresses;
	}

	// This function takes in an array of unique Ids of the customers and sort them according to the highest rating and highest counts within same rating
	function sortByRating($uniqueIds) {
		if (!empty($uniqueIds)) {
			// Get the customers ratings and counts
			$customerRating = new Customer_Rating();
			$ratingsAndCounts = array();
			foreach ($uniqueIds as $key => $id) {
				$ratingsAndCounts[$key] = $customerRating->getRatingAndCount($id);
				// $pointer = array_key_last($ratingsAndCounts); // works for PHP 7.3.0 or higher
				if (empty($ratingsAndCounts[$key]['customers_id'])) {
					// Save the customer id in the array
					$ratingsAndCounts[$key]['customers_id'] = $id;
				}
			}
			// Get the ratings column from highest to lowest
			$ratings = array_column($ratingsAndCounts, "rating");
			// Get the counts column from highest to lowest
			$counts = array_column($ratingsAndCounts, 'counts');
			// Get the customers ids in ascending order to be used to compare by age of account if there is a tie in ratings and counts
			$customers_ids = array_column($ratingsAndCounts, 'customers_id');
			// Sort the array according to the rating and counts
			array_multisort($ratings, SORT_DESC, $counts, SORT_DESC, $customers_ids, SORT_ASC, $ratingsAndCounts);
		} else {
			return false;
		}
		
		return array_column($ratingsAndCounts, 'customers_id');
	}
	
} // End Public_Image_Display() class

/*

*/
// End program ?>