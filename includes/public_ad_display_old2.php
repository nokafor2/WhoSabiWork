<?php 
// Deprecated January 28, 2021
Class Public_Ad_Display {
	// Setting up pagination, initialize it so it will have a default variable
	public $pagination;
	
	public $page;
	public $per_page;

	public function __construct($page, $per_page){
		$this->page = (int)$page;
		$this->per_page = (int)$per_page;
	}
	
	function vehicleTypeOptions() {
		// get an array of the vehicle types
		$vehicle = new Vehicle_Category();
		$vehicleTypes = $vehicle->getVehicleTypes();
		
		$output  = "";
		$output .= "<option value='select'>Select</option>";
		foreach ($vehicleTypes as $vehicleType) {
			$output .= "<option value='{$vehicleType}'>".ucfirst(str_replace("_", " ", $vehicleType))."</option>";
		}
		
		return $output;
	}
	
	// Get the total number of customers that will be outputted.
	function count_customer_ids($vehicle_name = '', $service_type = '', $cus_category = '', $vehicleType = '', $state = '', $town = ''){
		global $database;
		
		$vehicle_name = $database->escape_value($vehicle_name);
		// Service type can be used for spare parts too
		$service_type = $database->escape_value($service_type);
		$cus_category = $database->escape_value($cus_category);
		$vehicleType = $database->escape_value($vehicleType);
		$state = $database->escape_value($state);
		$town = $database->escape_value($town);
		
		if ($cus_category === 'technician') {
			$techOrParts = 'technical_services';
		} elseif ($cus_category === 'spare_part_seller') {
			$techOrParts = 'spare_parts';
		}
		
		if ($vehicleType === 'car') {
			$vehicleBrand = 'car_brands';
		} elseif ($vehicleType === 'bus') {
			$vehicleBrand = 'bus_brands';
		} else {
			$vehicleBrand = 'truck_brands';
		}
		
		// Determines what query to perform if a state and town is passed
		if (empty($town)) {
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT {$vehicleBrand}.customers_id, {$vehicleBrand}.{$vehicle_name}, vehicle_categories.{$vehicleType}, {$techOrParts}.{$service_type}, business_categories.{$cus_category}, customers.account_status, addresses.state FROM {$vehicleBrand}, vehicle_categories, {$techOrParts}, business_categories, customers, addresses WHERE {$vehicleBrand}.customers_id = {$techOrParts}.customers_id AND {$vehicleBrand}.customers_id = vehicle_categories.customers_id AND {$vehicleBrand}.customers_id = business_categories.customers_id AND {$vehicleBrand}.customers_id = customers.id AND {$vehicleBrand}.customers_id = addresses.customers_id AND {$vehicleBrand}.{$vehicle_name} = 1 AND vehicle_categories.{$vehicleType} = 1 AND {$techOrParts}.{$service_type} = 1 AND business_categories.{$cus_category} = 1 AND customers.account_status = 'active' AND addresses.state = '{$state}';";
		} else {
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT {$vehicleBrand}.customers_id, {$vehicleBrand}.{$vehicle_name}, vehicle_categories.{$vehicleType}, {$techOrParts}.{$service_type}, business_categories.{$cus_category}, customers.account_status, addresses.state, addresses.town FROM {$vehicleBrand}, vehicle_categories, {$techOrParts}, business_categories, customers, addresses WHERE {$vehicleBrand}.customers_id = {$techOrParts}.customers_id AND {$vehicleBrand}.customers_id = vehicle_categories.customers_id AND {$vehicleBrand}.customers_id = business_categories.customers_id AND {$vehicleBrand}.customers_id = customers.id AND {$vehicleBrand}.customers_id = addresses.customers_id AND {$vehicleBrand}.{$vehicle_name} = 1 AND vehicle_categories.{$vehicleType} = 1 AND {$techOrParts}.{$service_type} = 1 AND business_categories.{$cus_category} = 1 AND customers.account_status = 'active' AND addresses.state = '{$state}' AND addresses.town = '{$town}';";
		}
		$result_set = $database->query($sql); // Relevant
		
		$customers_ids = array();
		// STEP2: Get the technicians ids in an array
		while($row = mysqli_fetch_assoc($result_set)){ 
			// Gets the customers ids 
			$customers_ids[] = $row["customers_id"]; 
		}
		
		// This will return the total ids.
		// Eliminate duplicates and count the array.
		return count(array_unique($customers_ids));
	}
	
	// Get the total number of artisan customers that will be outputted.
	function count_artisan_ids($artisanType = '', $state = '', $town = ''){
		global $database;
		
		$artisanType = $database->escape_value($artisanType);
		// $cus_category = $database->escape_value($cus_category);
		$state = $database->escape_value($state);
		$town = $database->escape_value($town);
		
		if (empty($town)) {
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT artisans.customers_id, business_categories.artisan, customers.account_status, addresses.state FROM artisans, business_categories, customers, addresses WHERE artisans.customers_id = business_categories.customers_id AND artisans.customers_id = customers.id AND artisans.customers_id = addresses.customers_id AND artisans.{$artisanType} = 1 AND business_categories.artisan = 1 AND customers.account_status = 'active' AND addresses.state = '{$state}';";
		} else {
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT artisans.customers_id, business_categories.artisan, customers.account_status, addresses.state, addresses.town FROM artisans, business_categories, customers, addresses WHERE artisans.customers_id = business_categories.customers_id AND artisans.customers_id = customers.id AND artisans.customers_id = addresses.customers_id AND artisans.{$artisanType} = 1 AND business_categories.artisan = 1 AND customers.account_status = 'active' AND addresses.state = '{$state}' AND addresses.town = '{$town}';";
		}
		// echo $sql;
		
		$result_set = $database->query($sql); // Relevant
		
		$customers_ids = array();
		// STEP2: Get the artisan ids in an array
		while($row = mysqli_fetch_assoc($result_set)){ // Relevant
			// Gets the customers ids 
			$customers_ids[] = $row["customers_id"]; // Relevant
		}

		// Eliminate duplicates and count the array.
		$customers_ids = array_unique($customers_ids);
		
		// This will return the total ids.
		return count($customers_ids);
	}

	// Get the total number of artisan customers that will be outputted.
	function count_seller_ids($sellerType = '', $state = '', $town = ''){
		global $database;
		
		$sellerType = $database->escape_value($sellerType);
		// $cus_category = $database->escape_value($cus_category);
		$state = $database->escape_value($state);
		$town = $database->escape_value($town);
		
		if (empty($town)) {
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT sellers.customers_id, business_categories.seller, customers.account_status, addresses.state FROM sellers, business_categories, customers, addresses WHERE sellers.customers_id = business_categories.customers_id AND sellers.customers_id = customers.id AND sellers.customers_id = addresses.customers_id AND sellers.{$sellerType} = 1 AND business_categories.seller = 1 AND customers.account_status = 'active' AND addresses.state = '{$state}';";
		} else {
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT sellers.customers_id, business_categories.seller, customers.account_status, addresses.state, addresses.town FROM sellers, business_categories, customers, addresses WHERE sellers.customers_id = business_categories.customers_id AND sellers.customers_id = customers.id AND sellers.customers_id = addresses.customers_id AND sellers.{$sellerType} = 1 AND business_categories.seller = 1 AND customers.account_status = 'active' AND addresses.state = '{$state}' AND addresses.town = '{$town}';";
		}
		// echo $sql;
		
		$result_set = $database->query($sql); // Relevant
		
		$customers_ids = array();
		// STEP2: Get the seller ids in an array
		while($row = mysqli_fetch_assoc($result_set)){ // Relevant
			// Gets the customers ids 
			$customers_ids[] = $row["customers_id"]; // Relevant
		}

		// Eliminate duplicates and count the array.
		$customers_ids = array_unique($customers_ids);
		
		// This will return the total ids.
		return count($customers_ids);
	}
	
	// Get the customers ids and order them in descending order
	function get_customer_ids($vehicle_name = '', $service_type = '', $cus_category = '', $vehicleType = '', $state = '', $town = '') {
		global $database;
		global $per_page;
		global $pagination;
		
		$vehicle_name = $database->escape_value($vehicle_name);
		$service_type = $database->escape_value($service_type);
		$cus_category = $database->escape_value($cus_category);
		$vehicleType = $database->escape_value($vehicleType);
		$state = $database->escape_value($state);
		$town = $database->escape_value($town);
		
		if ($cus_category === 'technician') {
			$techOrParts = 'technical_services';
		} elseif ($cus_category === 'spare_part_seller') {
			$techOrParts = 'spare_parts';
		}
		
		if ($vehicleType === 'car') {
			$vehicleBrand = 'car_brands';
		} elseif ($vehicleType === 'bus') {
			$vehicleBrand = 'bus_brands';
		} else {
			$vehicleBrand = 'truck_brands';
		}
		
		if (empty($town)) {
			// The SQL code was updated
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT {$vehicleBrand}.customers_id, {$vehicleBrand}.{$vehicle_name}, vehicle_categories.{$vehicleType}, {$techOrParts}.{$service_type}, business_categories.{$cus_category}, customers.account_status, addresses.state FROM {$vehicleBrand}, vehicle_categories, {$techOrParts}, business_categories, customers, addresses WHERE {$vehicleBrand}.customers_id = {$techOrParts}.customers_id AND {$vehicleBrand}.customers_id = vehicle_categories.customers_id AND {$vehicleBrand}.customers_id = business_categories.customers_id AND {$vehicleBrand}.customers_id = customers.id AND {$vehicleBrand}.customers_id = addresses.customers_id AND {$vehicleBrand}.{$vehicle_name} = 1 AND vehicle_categories.{$vehicleType} = 1 AND {$techOrParts}.{$service_type} = 1 AND business_categories.{$cus_category} = 1 AND customers.account_status = 'active' AND addresses.state = '{$state}' LIMIT {$per_page} OFFSET {$pagination->offset()} ;";
		} else {
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT {$vehicleBrand}.customers_id, {$vehicleBrand}.{$vehicle_name}, vehicle_categories.{$vehicleType}, {$techOrParts}.{$service_type}, business_categories.{$cus_category}, customers.account_status, addresses.state FROM {$vehicleBrand}, vehicle_categories, {$techOrParts}, business_categories, customers, addresses WHERE {$vehicleBrand}.customers_id = {$techOrParts}.customers_id AND {$vehicleBrand}.customers_id = vehicle_categories.customers_id AND {$vehicleBrand}.customers_id = business_categories.customers_id AND {$vehicleBrand}.customers_id = customers.id AND {$vehicleBrand}.customers_id = addresses.customers_id AND {$vehicleBrand}.{$vehicle_name} = 1 AND vehicle_categories.{$vehicleType} = 1 AND {$techOrParts}.{$service_type} = 1 AND business_categories.{$cus_category} = 1 AND customers.account_status = 'active' AND addresses.state = '{$state}' AND addresses.town = '{$town}' LIMIT {$per_page} OFFSET {$pagination->offset()};";
		}
		// echo "The SQL query is: ".$sql."<br/>";
		$result_set = $database->query($sql); // Relevant

		$count = 0; // Relevant
		$customers_ids = array();
		// STEP2: Get the photographs ids and technicians ids in an array
		while($row = mysqli_fetch_assoc($result_set)){ // Relevant
			// Gets the customers ids 
			$customers_ids[$count] = $row["customers_id"]; // Relevant
			$count++; // Relevant
		}
		
		// Eliminate duplicates and count the array.
		$sortedByRating = $this->sortByRating(array_unique($customers_ids));

		// This will return an array of customer's ids and the total number ids.
		return array($sortedByRating, count($sortedByRating));
	}
	
	// Get the artisans ids aand order them in descending order using the ratings
	function get_artisan_ids($artisanType = '', $state = '', $town = '') {
		global $database;
		global $per_page;
		global $pagination;
		
		$artisanType = $database->escape_value($artisanType);
		// $cus_category = $database->escape_value($cus_category);
		$state = $database->escape_value($state);
		$town = $database->escape_value($town);
		
		if (empty($town)) {
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT artisans.customers_id, business_categories.artisan, customers.account_status, addresses.state FROM artisans, business_categories, customers, addresses WHERE artisans.customers_id = business_categories.customers_id AND artisans.customers_id = customers.id AND artisans.customers_id = addresses.customers_id AND artisans.{$artisanType} = 1 AND business_categories.artisan = 1 AND customers.account_status = 'active' AND addresses.state = '{$state}' LIMIT {$per_page} OFFSET {$pagination->offset()} ;";				
		} else {
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT artisans.customers_id, business_categories.artisan, customers.account_status, addresses.state, addresses.town FROM artisans, business_categories, customers, addresses WHERE artisans.customers_id = business_categories.customers_id AND artisans.customers_id = customers.id AND artisans.customers_id = addresses.customers_id AND artisans.{$artisanType} = 1 AND business_categories.artisan = 1 AND customers.account_status = 'active' AND addresses.state = '{$state}' AND addresses.town = '{$town}' LIMIT {$per_page} OFFSET {$pagination->offset()};";
		}
		// echo "The SQL query is: ".$sql."<br/>";
		
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
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT sellers.customers_id, business_categories.seller, customers.account_status, addresses.state FROM sellers, business_categories, customers, addresses WHERE sellers.customers_id = business_categories.customers_id AND sellers.customers_id = customers.id AND sellers.customers_id = addresses.customers_id AND sellers.{$sellerType} = 1 AND business_categories.seller = 1 AND customers.account_status = 'active' AND addresses.state = '{$state}' LIMIT {$per_page} OFFSET {$pagination->offset()} ;";				
		} else {
			// STEP1: generate and parse the SQL command 
			$sql = "SELECT sellers.customers_id, business_categories.seller, customers.account_status, addresses.state, addresses.town FROM sellers, business_categories, customers, addresses WHERE sellers.customers_id = business_categories.customers_id AND sellers.customers_id = customers.id AND sellers.customers_id = addresses.customers_id AND sellers.{$sellerType} = 1 AND business_categories.seller = 1 AND customers.account_status = 'active' AND addresses.state = '{$state}' AND addresses.town = '{$town}' LIMIT {$per_page} OFFSET {$pagination->offset()};";
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


	// Depricated function
	// This is a function that will display all the details of the customer to the public web page. 
	function customer_display($tech_Id_Array, $photo_Id_Array, $addresses){
		$rate_customer = new Customer_Rating();

		// Display a plain div informing no technician available.
		if (count($tech_Id_Array) < 1) {
			echo '<div class="adContainer" id="adPanel1">';
			echo '<p class="adContent">There is no available technician temporarily.</p>';
			echo '</div>';
		} 
		for($i = 0; $i < count($tech_Id_Array); $i++){
			if (isset($photo_Id_Array[$i])) {
				$photoId = Photograph::find_by_id($photo_Id_Array[$i]); // Relevant
			}
			if (isset($tech_Id_Array[$i])) {
				$techId = Customer::find_by_id($tech_Id_Array[$i]); // Relevant
			}
			if (isset($addresses[$i])) {
				$addressId = Address::find_by_id($addresses[$i]);
			}
			// Get the customer id which will represent the page id.
			$_SESSION["customer_page"] = $tech_Id_Array[$i];
			// $rating = array(); 
			$rating = $rate_customer->get_rating();
			?>
			
			<div class="adContainer" id="adPanel1">
			  <!-- id="adPanel1" -->
			  <!-- ?id=<?php // echo urlencode($tech_Id_Array[$i]); ?> -->
			  <?php //global $session; $session->set_link_id($tech_Id_Array[$i]); ?>
			  <a href="./customer/customerHomePage.php?id=<?php echo urlencode($tech_Id_Array[$i]); ?>"  > 
			  <!-- Unset the objects created for photoId, techId and addressId so that you can always check if it was initialized. -->
			  <p class="adImage"><img src="<?php if(isset($photoId)){echo $photoId->image_path(); unset($photoId);} ?>" alt="Technician Image" name="AdImage" width="300" height="150" id="AdImage" /></p>
			  </a>
			  <h1 class="adTitle"><?php if(isset($techId)){echo $techId->business_title;} ?></h1>
			  
			  <!-- Display full name -->
			  <p class="adContent"><i class="far fa-user" style="padding-right:10px;"></i><?php if(isset($techId)){echo $techId->full_name();} ?></p>
			  
			  <!-- Display address -->
			  <p class="adContent"><i class="far fa-address-card fa-lg" style="padding-right:10px;"></i><?php if(isset($addressId)){echo $addressId->full_address(); unset($addressId);}  ?></p>
			  
			  <!-- Display phone number -->
			  <!-- The techId is only unset after the last techId is referenced. -->
			  <p class="adContent"><i class="fas fa-phone" style="padding-right:10px;"></i><?php if(isset($techId)){echo $techId->phone_number; unset($techId);} ?></p>
			  
			  <!-- Display rating -->
			  <div class="rating <?php echo 'jDisabled'; ?>" data-average="<?php if ($rating['rating'] == NULL) { echo 0; } else { echo $rating['rating']; } ?>" data-id="<?php echo $rating['customers_id']; ?>" ></div><!-- end rating -->
			</div>
			
		<?php } 
	} // End customer_display() function
	
	function displayPagination($page, $pagination, $link) {
		// Set float on both sides, so pagination will be placed below the images
		echo '<div id="pagination" style="clear:both;">';
		
			// First check if pagination is active to know if it will be done.
			if($pagination->total_pages() > 1) {
				// Call a function to know if the pagination has next page.
				if($pagination->has_previous_page()) { 
					// Display the link to the previous page.
					// echo "<a href=\"homePageEdit2.php?page=";
					echo "<a href='".$link."?page=";
					echo $pagination->previous_page();
					echo "'>&laquo; Previous</a> "; 
				}
				// Iterate through the list of pages if there is more than one page.
				for($i=1; $i <= $pagination->total_pages(); $i++) {
					// Checks if you are in the current page
					if($i == $page) {
						// For the current page, there will be no link
						echo " <span class=\"selected\">Page {$i}</span> ";
					} else {
						// If there is more than one page, output it by concatenating the location to the link through the $_GET global variable
						echo " <a href='".$link."?page={$i}'>Page {$i}</a> "; 
					}
				}
				// If it has next page, then it should have a previous page.
				if($pagination->has_next_page()) { 
					// Display the link to the next page and set the dynamic link in the $_GET global variable
					echo " <a href='".$link."?page=";
					echo $pagination->next_page();
					echo "'>Next &raquo;</a> "; 
				}
				
			}
		echo "</div>";
	}
	
	public function run_customer_display($carBrandSearch='', $serviceType='', $cusCategoty='', $link = '') {
		global $database;
		global $pagination;
		global $page;
		global $per_page;
		
		// 3. total record count ($total_count or $numberOfCustomers)
		$carBrandSearch = $database->escape_value($carBrandSearch);
		$serviceType = $database->escape_value($serviceType);
		$cusCategoty = $database->escape_value($cusCategoty);
		$link = $database->escape_value($link);
		
		$numberOfCustomers = $this->count_customer_ids($carBrandSearch, $serviceType, $cusCategoty);
		// $total_count = 3;
		
		$pagination = new Pagination($page, $per_page, $numberOfCustomers);
		
		list($customer_ids, $total_count) = $this->get_customer_ids($carBrandSearch, $serviceType, $cusCategoty);

		// Instantiate the photo Id array
		$photograph_ids = $this->get_photograph_ids($customer_ids);
		
		// Get the addresses of all the customers to be displayed
		$addresses = $this->find_address_id_using_customer_id($customer_ids);
		
		// Display the customer information
		$this->customer_display($customer_ids, $photograph_ids, $addresses);
		
		// Display the pagination
		// $link = 'carsEngineServices.php';
		$this->displayPagination($page, $pagination, $link);
	}
	
} // End Public_Image_Display() class

/*

*/
// End program ?>