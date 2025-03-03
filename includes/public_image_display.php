<?php 
Class Public_Image_Display {
		// Setting up pagination
	public $pagination;

	// Use this to get the total number of ads to display
	// SELECT car_brands.toyota, technical_services.engine_service FROM car_brands, technical_services WHERE car_brands.customers_id = technical_services.customers_id AND car_brands.toyota = 1 AND technical_services.engine_service = 1

	// Get the total number of pictures that will be outputted.
	function get_total_ids($vehicle_name = '', $service_type = '', $cus_category = ''){
		global $database;
		// $number_of_ids = array();
		
		$vehicle_brands_table = 'car_brands';
		$customers_id = 'customers_id';
		// $vehicle_name = 'toyota';
		$photograph_table = 'photographs';
		$photograph_id = 'id';
		$photograph_filename = 'filename';
		
		// STEP1: generate and parse the SQL command 
		// $sql = "SELECT car_brands.customers_id, car_brands.toyota, photographs.id, photographs.filename FROM car_brands, photographs WHERE car_brands.customers_id = photographs.customers_id AND car_brands.toyota = 1";
		
		// $sql = "SELECT {$vehicle_brands_table}.{$customers_id}, {$vehicle_brands_table}.{$vehicle_name}, {$photograph_table}.{$photograph_id}, {$photograph_table}.{$photograph_filename} FROM {$vehicle_brands_table}, {$photograph_table} WHERE {$vehicle_brands_table}.{$customers_id} = {$photograph_table}.{$customers_id} AND {$vehicle_brands_table}.{$vehicle_name} = 1";
		
		// STEP1: generate and parse the SQL command 
		$sql = "SELECT car_brands.customers_id, car_brands.{$vehicle_name}, photographs.id, photographs.filename, technical_services.{$service_type}, business_categories.{$cus_category} FROM car_brands, photographs, technical_services, business_categories WHERE car_brands.customers_id = photographs.customers_id AND car_brands.customers_id = technical_services.customers_id AND car_brands.customers_id = business_categories.customers_id AND car_brands.{$vehicle_name} = 1 AND technical_services.{$service_type} = 1 AND business_categories.{$cus_category} = 1;";
		//  LIMIT 3 OFFSET 0
		// echo $sql."</br>";
		
		// $sql = "SELECT {$vehicle_brands_table}.{$customers_id}, {$vehicle_brands_table}.{$vehicle_name}, {$photograph_table}.{$photograph_id}, {$photograph_table}.{$photograph_filename} FROM {$vehicle_brands_table}, {$photograph_table} WHERE {$vehicle_brands_table}.{$customers_id} = {$photograph_table}.{$customers_id} AND {$vehicle_brands_table}.{$vehicle_name} = 1";
		$result_set = $database->query($sql); // Relevant

		$count = 0; // Relevant
		$number_of_ids = array();
		// STEP2: Get the photographs ids and technicians ids in an array
		while($row = mysqli_fetch_assoc($result_set)){ // Relevant
			// Gets the customers ids 
			$number_of_ids[$count] = $row["customers_id"]; // Relevant
			$count++; // Relevant
		}
		// echo $number_of_ids."</br>";
		// echo $count."</br>";
		
		// This will return the total ids.
		// Eliminate duplicates and count the array.
		return count(array_unique($number_of_ids));
	}
		
		// Need to add ?page=$page to all links we want to 
		// maintain the current page (or store $page in $session)

	// get the customer and photograph ids from the database
	function get_photo_and_customer_ids($vehicle_name = '', $service_type = '', $cus_category = ''){
		global $database;
		// global $photograph_ids;
		// global $customers_ids;
		global $per_page;
		global $pagination;
		
		$vehicle_brands_table = 'car_brands';
		$customers_id = 'customers_id';
		// $vehicle_name = 'toyota';
		$photograph_table = 'photographs';
		$photograph_id = 'id';
		$photograph_filename = 'filename';
		
		// STEP1: generate and parse the SQL command 
		// $sql = "SELECT car_brands.customers_id, car_brands.toyota, photographs.id, photographs.filename FROM car_brands, photographs WHERE car_brands.customers_id = photographs.customers_id AND car_brands.toyota = 1 LIMIT {$per_page} OFFSET {$pagination->offset()}";
		
		// Abstracting the SQL tables and columns
		// $sql = "SELECT {$vehicle_brands_table}.{$customers_id}, {$vehicle_brands_table}.{$vehicle_name}, {$photograph_table}.{$photograph_id}, {$photograph_table}.{$photograph_filename} FROM {$vehicle_brands_table}, {$photograph_table} WHERE {$vehicle_brands_table}.{$customers_id} = {$photograph_table}.{$customers_id} AND {$vehicle_brands_table}.{$vehicle_name} = 1 LIMIT {$per_page} OFFSET {$pagination->offset()}";
		
		$sql = "SELECT car_brands.customers_id, car_brands.{$vehicle_name}, photographs.id, photographs.filename, technical_services.{$service_type}, business_categories.{$cus_category} FROM car_brands, photographs, technical_services, business_categories WHERE car_brands.customers_id = photographs.customers_id AND car_brands.customers_id = technical_services.customers_id AND car_brands.customers_id = business_categories.customers_id AND car_brands.{$vehicle_name} = 1 AND technical_services.{$service_type} = 1 AND business_categories.{$cus_category} = 1  LIMIT {$per_page} OFFSET {$pagination->offset()};";
		
		// echo $sql."</br>";
		
		$result_set = $database->query($sql); // Relevant
		
		/* if (isset($result_set)) {
			echo "Query was executed. <br/>";
		} else {
			echo "Query was not executed. <br/>";
		} */

		$count = 0; // Relevant
		// STEP2: Get the photographs ids and technicians ids in an array
		$photograph_ids = array();
		$customers_ids = array();
		while($row = mysqli_fetch_assoc($result_set)){ // Relevant
			// Gets the photograph ids of the customers
			$photograph_ids[$count] = $row["id"]; // Relevant
			// Gets the customers ids 
			$customers_ids[$count] = $row["customers_id"]; // Relevant
			
			/* echo "The customers id from the function is: <br/>";
			print_r($customers_ids);
			echo "<br/>"; */
			
			$count++; // Relevant
		}
		
		// return an array of the customers_ids and photograph_ids
		return array($customers_ids, $photograph_ids);
	}

	// echo "The pagination offset value is: ".$pagination->offset();
	// Initialize the array for customers_ids and photograph_ids

	/* commented */
	// get_photo_and_customer_ids();

	// $tech_Id_Array = array();
	function eliminate_duplicates($arrayKey, $arrayCheck){
		$tech_Id_Array = array();
		$new_array = array();
		// global $tech_Id_Array;
		// This sorts the customers_ids array to eliminate duplicates
		$new_array = array_unique($arrayKey);
		$photo_ids = array(); // Relevant
		// Use the array keys of the sorted customers_ids array to get the array of pictures that will be displayed containing no duplicates
		// contains non duplicated photo ids
		$photo_ids = array_keys($new_array); // Relevant
		$refined_ids = array(); // Relevant
		$i = 0; // Relevant
		foreach($photo_ids as $ids){ // Relevant
		// get the photograph ids of the non duplicated photos from the photograph_ids passed into the function
			$refined_ids[$i] = $arrayCheck[$ids]; // Relevant
			// Save the sorted customers_ids array into a new array with consistent numeral keys beginning from zero.
			$tech_Id_Array[$i] = $new_array[$ids];
			$i++; // Relevant
		}
		return array($refined_ids, $tech_Id_Array);
	}

	/* commented */
	// $photo_Id_Array = eliminate_duplicates($customers_ids, $photograph_ids); // Relevant

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

	// This is a function that will display all the details of the customer to the public webpage. 
	// $rating = array(); 
	function customer_display($tech_Id_Array, $photo_Id_Array, $addresses){
		/* global $tech_Id_Array;
		global $photo_Id_Array;
		global $addresses; */

		$rate_customer = new Customer_Rating();

		// Display a plain div informing no technician available.
		if (count($photo_Id_Array) < 1) {
			echo '<div class="adContainer" id="adPanel1">';
			echo '<p class="adContent">There is no available technician temporarily.</p>';
			echo '</div>';
		} 
		for($i = 0; $i < count($photo_Id_Array); $i++){
			$photoId = Photograph::find_by_id($photo_Id_Array[$i]); // Relevant
			$techId = Customer::find_by_id($tech_Id_Array[$i]); // Relevant
			$addressId = Address::find_by_id($addresses[$i]);
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
			  <p class="adImage"><img src="<?php echo $photoId->image_path(); ?>" alt="Technician Image" name="AdImage" width="300" height="150" id="AdImage" /></p>
			  </a>
			  <h1 class="adTitle"><?php echo $techId->business_title; ?></h1>
			  
			  <!-- Display full name -->
			  <p class="adContent"><i class="far fa-user" style="padding-right:10px;"></i><?php echo $techId->full_name(); ?></p>
			  
			  <!-- Display address -->
			  <p class="adContent"><i class="far fa-address-card fa-lg" style="padding-right:10px;"></i><?php echo $addressId->full_address(); ?></p>
			  
			  <!-- Display phone number -->
			  <p class="adContent"><i class="fas fa-phone" style="padding-right:10px;"></i><?php echo $techId->phone_number; ?></p>
			  
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
	
} // End Public_Image_Display() class
// End program ?>
