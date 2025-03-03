<?php 
/*
Common Validations
- Presence
- String length
- Type
- Inclusion in a set
- Uniqueness
- Format
*/

class Validation {
	
	public $errors = array();

	// - Presence
	// use trim() to deal with spaces
	// use === to avoid false positives
	// empty() would consider "0" to be empty
	public function has_presence($value){
		// This function will return a true of false value
		return isset($value) && $value !== "";	
	}
	
	// Check a proper name was entered
	public function validate_name($name){
		return (!preg_match("/^[a-zA-Z ]*$/",$name));
	}
	
	// Check a proper address was entered
	public function validate_address($address){
		return (!preg_match("/^[a-zA-Z0-9., ]*$/",$address));
	}

	// - String length
	// Checks if the input is above the minimum length
	public function has_min_length($value, $min){
		return strlen($value) >= $min;
	}

	// Checks if the input is within the maximum length
	public function has_max_length($value, $max){
		return strlen($value) <= $max;
	}
	
	// - Check numeric type
	public function not_number($value) {
		return !is_numeric($value);
	}		

	// - Validate Email Format
	public function not_email($value){
		/* A threeple equal sign is used because the strpos() function returns '0' for false and '0' is also recognized by the system as false value so comparing it with '==' will result to a true value which will give the wrong programing logic, a false positive. */
		
		// return (strpos($value, "@") === false);
		return (!filter_var($value, FILTER_VALIDATE_EMAIL));
	}

	// - Inclusion in a set
	public function has_inclusion_in($value, $set){
		return in_array($value, $set);
	}
	
	public function validate_website($website) {
		return (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website));
	}
	
	public function validate_has_presence($fields_required) {
		foreach($fields_required as $field){
			if (array_key_exists($field, $_POST)) {
				$value = trim($_POST[$field]);
				if (!($this->has_presence($value))) {
					$this->errors[$field] = ucfirst(str_replace("_", " ", $field))." can't be blank";
				}
			}
		}
	}
	
	// Validate names saved in an array
	public function validate_name_fields($fields_with_names) {
		// Using an assoc. array
		foreach($fields_with_names as $field){
			if (array_key_exists($field, $_POST)) {
				$value = trim($_POST[$field]);
				if ($this->validate_name($value)) {
					$this->errors[$field."err_name"] = ucfirst($field)." is not valid.";
				}
			}
		}
	}
	
	// Validate addresses, caption, state, city saved in an array
	public function validate_address_fields($fields_with_address) {
		foreach($fields_with_address as $field){
			if (array_key_exists($field, $_POST)) {
				$value = trim($_POST[$field]);
				if ($this->validate_address($value)) {
					$this->errors[$field."address_error"] = ucfirst(str_replace("_", " ", $field))." is not valid.";
				}
			}	
		}
	}
	
	// Validate the maximum lengths of fields saved in an array
	public function validate_max_lengths($fields_with_max_lengths){
		// Using an assoc. array
		foreach($fields_with_max_lengths as $field => $max){
			$value = trim($_POST[$field]);
			if (!($this->has_max_length($value, $max))) {
				$this->errors[$field."err_long"] = ucfirst(str_replace("_", " ", $field))." is too long.";
			}
		}
	}
	
	// Validate the minimum lengths of fields saved in an array
	public function validate_min_lengths($fields_with_min_lengths){
		// Using an assoc. array
		foreach($fields_with_min_lengths as $field => $min){
			if (array_key_exists($field, $_POST)) {
				$value = trim($_POST[$field]);
				if (!($this->has_min_length($value, $min))) {
					$this->errors[$field."err_short"] = ucfirst(str_replace("_", " ", $field))." is short.";
				}
			}
		}
	}
	
	// Validate or set the radio button of the customer edit page.
	public function validate_or_set_radio_selection($fields_to_validate){
		foreach($fields_to_validate as $field){
			if (!isset($_POST[$field])){
				$this->errors[$field] = "Select a ".str_replace("_", " ", $field);
			} else {
				global $field;
				// save the variable 
				// $.{$field} = $_POST[$field];
				// $field = $_POST[$field];
			}
		}	
	}
	
	// Validate an image
	public function validate_image($image_to_validate){
		if (exif_imagetype($image_to_validate) != IMAGETYPE_GIF) {
			$this->errors['image_error'] = "The picture is not a gif.";
		}
	}
	
	public function is_image($path) {
		// Use the width to check if the getimagesize() function was successful. If there is an image file a width will be specified else if there is none no width will be specified
		if (getimagesize($path)[0] == 0) {
			$image_type = 'error';
		} else {
			$a = getimagesize($path);
			$image_type = $a['mime'];
		}
		
		// echo "The image type inputed is: ".$image_type."<br/>";
		
		// list($width, $height, $image_type, $attr) = getimagesize($path);
		// array("image/gif", "image/jpeg", "image/png", "image/bmp") array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP)
		if(!in_array($image_type, array("image/gif", "image/jpeg", "image/png", "image/bmp"))){
			$this->errors['image_error'] = "The picture is not a an accepted image format.";
		}
	}

	public function form_errors($errors = array()){
		$output = "";
		if (!empty($errors)){
			$output .= "<div class=\"error\">";
			$output .= "Please fix the following errors:";
			$output .= "<ul>";
			foreach ($errors as $key => $error){
				// style=\"float:left\"
				$output .= "<li style=\"float:left; padding-right: 30px;\">{$error}</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";
		}
		return $output;
	}
	
	public function form_errors_signIn($errors = array()){
		$output = "";
		if (!empty($errors)){
			$output .= "<div class=\"error\">";
			$output .= "Please fix the following errors:";
			$output .= "<ul>";
			foreach ($errors as $key => $error){
				// style=\"float:left\"
				$output .= "<li>{$error}</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";
		}
		return $output;
	}
	
	public function validate_user() {
		$fields_required = array("username", "password");
		foreach($fields_required as $field){
			$value = trim($_POST[$field]);
			if (!($this->has_presence($value))) {
				$this->errors[$field] = ucfirst(str_replace("_", " ", $field))." can't be blank";
			}
		}
		
		// Validate the input fields for maximum length
		$fields_with_max_lengths = array("username" => 30, "password" => 12);
		$this->validate_max_lengths($fields_with_max_lengths);
		
		// Validate the password is not short
		global $password;
		if (!$this->has_min_length($password, $min = 6)) {
			$this->errors["password_short"] = "The password is short.";
		}
	}
	
	public function validate_user_register() {
		global $phone_number;
		global $email;
		
		// Validate presence of input in the form
		$fields_required = array("first_name", "last_name", "username", "password", "phone_number", "email");
		foreach($fields_required as $field){
			$value = trim($_POST[$field]);
			if (!($this->has_presence($value))) {
				$this->errors[$field] = ucfirst(str_replace("_", " ", $field))." can't be blank";
			}
		}
		
		// Validate the input fields for maximum length
		$fields_with_max_lengths = array("first_name" => 30, "last_name" => 30, "username" => 30, "password" => 12, "phone_number" => 11, "email" => 30);
		$this->validate_max_lengths($fields_with_max_lengths);
		
		// Validate that only number was entered for phone number
		if (($this->not_number($phone_number))) {
			$this->errors["phone_number_error"] = "This is an invalid phone number.";
		}
		
		// Validate the input field for minimum length
		$fields_with_min_lengths = array("password" => 6, "phone_number" => 10);
		$this->validate_min_lengths($fields_with_min_lengths);
		
		// Validate proper names were entered.
		$fields_with_names = array("first_name", "last_name");
		$this->validate_name_fields($fields_with_names);
		
		// Validate a proper email was entered.
		if ($this->not_email($email)) {
			$this->errors["email_error"] = "This is not a valid email.";
		}
	}
	
	public function validate_customer_register() {
		global $business_phone;
		global $business_email;
		global $address_line_1;
		
		// Validate presence of input in the form
		$fields_required = array("first_name", "last_name", "business_username", "business_password", "business_name", "business_email", "business_phone", "address_line_1", "city", "state");
		foreach($fields_required as $field){
			$value = trim($_POST[$field]);
			if (!($this->has_presence($value))) {
				$this->errors[$field] = ucfirst(str_replace("_", " ", $field))." can't be blank";
			}
		}
		
		// Validate the input fields for maximum length
		$fields_with_max_lengths = array("first_name" => 30, "last_name" => 30, "business_username" => 30, "business_password" => 12, "business_name" => 30, "business_email" => 30, "business_phone" => 11, "address_line_1" => 50, "address_line_2" => 50, "address_line_3" => 50, "city" => 15, "state" => 15);
		$this->validate_max_lengths($fields_with_max_lengths);
		
		
		// Validate that only number was entered for phone number
		if (($this->not_number($business_phone))) {
			$this->errors["phone_number_error"] = "This is an invalid phone number.";
		}
		
		// Validate the input field for minimum length
		$fields_with_min_lengths = array("business_password" => 6, "business_phone" => 10);
		$this->validate_min_lengths($fields_with_min_lengths);
		
		// Validate proper names were entered.
		$fields_with_names = array("first_name", "last_name");
		$this->validate_name_fields($fields_with_names);
		
		// Validate a proper email was entered.
		if ($this->not_email($business_email)) {
			$this->errors["email_error"] = "This is not a valid email.";
		}
		
		// Validate a proper address was entered.
		$fields_with_address = array("address_line_1", "address_line_2", "address_line_3", "city", "state");
		$this->validate_address_fields($fields_with_address);
	}
	
	public function validate_customer_edit() {
		global $business_phone_number;
		global $business_email;
		global $address_line_1;
		global $business_category;
		global $vehicle_category;
		global $car_brands;
		global $bus_brands;
		global $truck_brands;
		global $technical_services;
		global $artisans;
		global $spare_parts;
		global $image_to_validate;
		// global $path;
		
		// Validate presence of input in the form
		// "business_email", 
		$fields_required = array("first_name", "last_name", "username", "password", "business_name", "business_phone_number", "address_line_1", "city", "state", "caption");
		foreach($fields_required as $field){
			$value = trim($_POST[$field]);
			if (!($this->has_presence($value))) {
				// $this->errors[$field] = ucfirst(str_replace("_", " ", $field))." can't be blank";
				$this->errors[] = ucfirst(str_replace("_", " ", $field))." can't be blank";
			}
		}
		
		// This validates the radio buttons
		// $fields_to_validate = array("business_category", "vehicle_category");
		// $this->validate_or_set_radio_selection($fields_to_validate);
		
		if (!isset($_POST["business_category"])){
			$this->errors["business_category"] = "Select a business category.";
		} else {
			// save the variable 
			$business_category = $_POST["business_category"];
			// "$".{$field} = $_POST[$field];
		}
		
		// Validate only the specified or selected business category
		switch ($business_category) {
			case 'artisan':
				// This array contains the artisans selected by the customer
				if (!isset($_POST["artisans"])){
					$this->errors["artisans_error"] = "Select an artisan.";
				} else {
					$artisans = $_POST['artisans'];
				}
				break;
			case 'technician':
				// This array contains the technical services selected by the customer
				if (!isset($_POST["technical_services"])){
					$this->errors["technical_services_error"] = "Select a technical service.";
				} else {
					$technical_services = $_POST['technical_services'];
				}
				
				// If it is a technician, check what vehicle category is specialized in
				if (!isset($_POST["vehicle_category"])){
					$this->errors["vehicle_category"] = "Select a vehicle category.";
				} else {
					// save the variable 
					$vehicle_category = $_POST["vehicle_category"];
					// "$".{$field} = $_POST[$field];
				}
				
				break;
			case 'spare_part_seller':
				// This array contains the spare parts selected by the customer
				if (!isset($_POST["spare_parts"])){
					$this->errors["spare_parts_error"] = "Select a spare part.";
				} else {
					$spare_parts = $_POST['spare_parts'];
				}
				
				// If it is a technician, check what vehicle category is specialized in
				if (!isset($_POST["vehicle_category"])){
					$this->errors["vehicle_category"] = "Select a vehicle category.";
				} else {
					// save the variable 
					$vehicle_category = $_POST["vehicle_category"];
					// "$".{$field} = $_POST[$field];
				}
				
				break;
		}
		
		switch ($vehicle_category) {
			case 'cars':
				// This array contains the brand of cars selected by the customer
				if (!isset($_POST["car_brands"])){
					$this->errors["car_brands_error"] = "Select a car brand.";
				}else {
					$car_brands = $_POST['car_brands'];
				}
				break;
			case 'buses':
				// This array contains the brand of buses selected by the customer
				if (!isset($_POST["bus_brands"])){
					$this->errors["bus_brands_error"] = "Select a bus brand.";
				}else {
					$bus_brands = $_POST['bus_brands'];
				}
				break;
			case 'trucks':
				// This array contains the brand of trucks selected by the customer
				if (!isset($_POST["truck_brands"])){
					$this->errors["truck_brands_error"] = "Select a truck brand.";
				}else {
					$truck_brands = $_POST['truck_brands'];
				}
				break;
		}
		
		// Validate the input fields for maximum length
		$fields_with_max_lengths = array("first_name" => 30, "last_name" => 30, "username" => 30, "password" => 12, "business_name" => 30, "business_email" => 30, "business_phone_number" => 11, "address_line_1" => 50, "address_line_2" => 50, "address_line_3" => 50, "city" => 15, "state" => 15);
		$this->validate_max_lengths($fields_with_max_lengths);
		
		
		// Validate that only number was entered for phone number
		if (($this->not_number($business_phone_number))) {
			$this->errors["phone_number_error"] = "This is an invalid phone number.";
		}
		
		// Validate the input field for minimum length
		$fields_with_min_lengths = array("password" => 6, "business_phone_number" => 10);
		$this->validate_min_lengths($fields_with_min_lengths);
		
		// Validate proper names were entered.
		$fields_with_names = array("first_name", "last_name");
		$this->validate_name_fields($fields_with_names);
		
		if (array_key_exists("business_email", $_POST) && !empty($_POST["business_email"])) {
			// Validate a proper email was entered.
			if ($this->not_email($business_email)) {
				$this->errors["email_error"] = "This is not a valid email.";
			}
		}
		
		// Validate a proper address or caption was entered.
		$fields_with_address = array("address_line_1", "address_line_2", "address_line_3", "city", "state");
		$this->validate_address_fields($fields_with_address);
		
		// $this->is_image($path);
	}
	
	public function validate_customer_update() {
		global $phone_number;
		global $business_email;
		global $address_line_1;
		global $business_category;
		global $vehicle_category;
		global $car_brands;
		global $technical_services;
		global $image_to_validate;
		global $path;
		
		// Validate presence of input in the form
		$fields_required = array("edit_first_name", "edit_last_name", "edit_username", "edit_password", "edit_business_name", "edit_business_email", "edit_business_phone_number", "edit_address_line_1", "edit_city", "edit_state", "edit_caption");
		foreach($fields_required as $field){
			if (in_array($field, $_POST)) {
				$value = trim($_POST[$field]);
				if (!($this->has_presence($value))) {
					$this->errors[$field] = ucfirst(str_replace("_", " ", $field))." can't be blank";
				}
			}
		}
		
		// This validates the radio buttons
		// $fields_to_validate = array("business_category", "vehicle_category");
		// $this->validate_or_set_radio_selection($fields_to_validate);
		
		if (!isset($_POST["business_category"])){
			$this->errors["business_category"] = "Select a business category.";
		} else {
			// save the variable 
			$business_category = $_POST["business_category"];
			// "$".{$field} = $_POST[$field];
		}
		
		if (!isset($_POST["vehicle_category"])){
			$this->errors["vehicle_category"] = "Select a vehicle category.";
		} else {
			// save the variable 
			$vehicle_category = $_POST["vehicle_category"];
			// "$".{$field} = $_POST[$field];
		}
		
		
		// This array contains the brand of cars selected by the customer
		if (!isset($_POST["car_brands"])){
			$this->errors["car_brands_error"] = "Select a car brand.";
		}else {
			$car_brands = $_POST['car_brands'];
		}
		
		// This array contains the technical services selected by the customer
		if (!isset($_POST["technical_services"])){
			$this->errors["technical_services_error"] = "Select a technical service.";
		}else {
			$technical_services = $_POST['technical_services'];
		}
		
		// Validate the input fields for maximum length
		$fields_with_max_lengths = array("edit_first_name" => 30, "edit_last_name" => 30, "edit_username" => 30, "edit_password" => 12, "edit_business_name" => 30, "edit_business_email" => 30, "edit_business_phone_number" => 11, "edit_address_line_1" => 50, "edit_address_line_2" => 50, "edit_address_line_3" => 50, "edit_city" => 15, "edit_state" => 15);
		foreach ($fields_with_max_lengths as $field => $max) {
			if (in_array($field, $_POST)) {
				$value = trim($_POST[$field]);
				if (!($this->has_max_length($value, $max))) {
					$this->errors[$field."err_long"] = ucfirst(str_replace("_", " ", $field))." is too long.";
				}
			}
		}
		
		
		// Validate that only number was entered for phone number
		if (($this->not_number($business_phone_number))) {
			$this->errors["phone_number_error"] = "This is an invalid phone number.";
		}
		
		// Validate the input field for minimum length
		$fields_with_min_lengths = array("edit_password" => 6, "edit_business_phone_number" => 10);
		$this->validate_min_lengths($fields_with_min_lengths);
		
		// Validate proper names were entered.
		$fields_with_names = array("first_name", "last_name");
		$this->validate_name_fields($fields_with_names);
		
		// Validate a proper email was entered.
		if ($this->not_email($business_email)) {
			$this->errors["email_error"] = "This is not a valid email.";
		}
		
		// Validate a proper address or caption was entered.
		$fields_with_address = array("address_line_1", "address_line_2", "address_line_3", "city", "state");
		$this->validate_address_fields($fields_with_address);
		
		if (isset($path)) {
			$this->is_image($path);
		}
			
	}
	
	// This is used for the update of edits made on the customer's edit page
	public function validate_name_update() {
		global $phone_number;
		global $business_email;
		global $user_email;
		global $address_line_1;
		global $business_category;
		global $vehicle_category;
		global $car_brands_sltd;
		global $bus_brands_sltd;
		global $truck_brands_sltd;
		global $spare_parts_sltd;
		global $technical_services_sltd;
		global $artisans_sltd;
		global $image_to_validate;
		global $path;
		global $caption;
		
		// Validate presence of input in the form
		$fields_required = array("edit_first_name", "first_name", "edit_last_name", "last_name", "edit_username", "username", "edit_password", "password", "edit_business_name", "edit_business_email", "email", "edit_phone_number", "phone_number", "edit_address_line_1", "edit_city", "edit_state", "caption", "business_description", "message_content");
		foreach($fields_required as $field){
			if (array_key_exists($field, $_POST)) {
				$value = trim($_POST[$field]);
				if (!($this->has_presence($value))) {
					$this->errors[$field] = ucfirst(str_replace("_", " ", $field))." can't be blank";
				}
			}
		}
		
		// Validate the input fields for maximum length
		$fields_with_max_lengths = array("edit_first_name" => 30, "first_name" => 30, "edit_last_name" => 30, "last_name" => 30, "edit_username" => 30, "username" => 30, "edit_password" => 12, "password" => 12, "edit_business_name" => 30, "edit_business_email" => 30, "email" => 30, "edit_phone_number" => 11, "phone_number" => 11, "edit_address_line_1" => 50, "edit_address_line_2" => 50, "edit_address_line_3" => 50, "edit_city" => 15, "edit_state" => 15, "business_description" => 250, "message_content" => 250);
		foreach ($fields_with_max_lengths as $field => $max) {
			if (array_key_exists($field, $_POST)) {
				$value = trim($_POST[$field]);
				if (!($this->has_max_length($value, $max))) {
					$this->errors[$field."err_long"] = ucfirst(str_replace("_", " ", $field))." is too long.";
				}
			}
		}
		
		// Validate that only number was entered for phone number
		/* if (array_key_exists("edit_phone_number", $_POST)) {
			if (($this->not_number($phone_number))) {
				$this->errors["phone_number_error"] = "This is an invalid phone number.";
			}
		} */
		
		// Validate that only number was entered for phone number
		$fields_with_phone_number = array("edit_phone_number", "phone_number");
		foreach ($fields_with_phone_number as $field) {
			if (array_key_exists($field, $_POST)) {
				$phone_number = trim($_POST[$field]);
				if (($this->not_number($phone_number))) {
					$this->errors["phone_number_error"] = "This is an invalid phone number.";
				}
			}
		}
			
		// Validate the input field for minimum length
		$fields_with_min_lengths = array("edit_password" => 6, "password" => 6, "edit_phone_number" => 10, "phone_number" => 10);
		$this->validate_min_lengths($fields_with_min_lengths);
		
		// Validate proper names were entered.
		$fields_with_names = array("edit_first_name", "first_name", "edit_last_name", "last_name");
		$this->validate_name_fields($fields_with_names);
		
		// Validate a proper email was entered.
		/* if (array_key_exists("edit_business_email", $_POST)) {
			if ($this->not_email($business_email)) {
				$this->errors["email_error"] = "This is not a valid email.";
			}
		} */
		
		// Validate a proper email was entered.
		$fields_with_email = array("edit_business_email", "email");
		foreach ($fields_with_email as $field) {
			if (array_key_exists($field, $_POST)) {
				$email = trim($_POST[$field]);
				if ($this->not_email($email)) {
					$this->errors["email_error"] = "This is not a valid email.";
				}
			}
		}	
		
		// Validate a proper address, caption, city, business_description, message_content was entered.
		$fields_with_address = array("edit_address_line_1", "edit_address_line_2", "edit_address_line_3", "edit_city", "edit_state", "business_description", "message_content");
		$this->validate_address_fields($fields_with_address);
		
		if (array_key_exists("submit_business_category", $_POST)) {
			if (!isset($_POST["business_category"])){
				$this->errors["business_category"] = "Select a business category.";
			} else {
				// save the variable 
				$business_category = $_POST["business_category"];
				// "$".{$field} = $_POST[$field];
			}
		}
		
		if (array_key_exists("submit_vehicle_category", $_POST)) {
			if (!isset($_POST["vehicle_category"])){
				$this->errors["vehicle_category"] = "Select a vehicle category.";
			} else {
				// save the variable 
				$vehicle_category = $_POST["vehicle_category"];
				// "$".{$field} = $_POST[$field];
			}
		}
		
		// This array contains the brand of cars selected by the customer
		if (array_key_exists("submit_vehicle_brands", $_POST)) {
			// Check if any of the vehicle brands array exists in the POST global array
			if (isset($_POST["car_brands"]) || isset($_POST["bus_brands"]) || isset($_POST["truck_brands"])){
				if (isset($_POST["car_brands"])) {
					$car_brands_sltd = $_POST['car_brands'];
				} elseif (isset($_POST["bus_brands"])) {
					$bus_brands_sltd = $_POST['bus_brands'];
				} elseif (isset($_POST["truck_brands"])) {
					$truck_brands_sltd = $_POST['truck_brands'];
				}
			} else {
				$this->errors["veh_brands_error"] = "Select a vehicle brand.";
			}
		}
		
		// This array contains the technical services selected by the customer
		if (array_key_exists("submit_services_parts", $_POST)) {
			// Check if any of business category array exist in the POST global array
			if (isset($_POST["technical_services"]) || isset($_POST["spare_parts"]) || isset($_POST["artisans"])){
				if (isset($_POST["artisans"])) {
					$artisans_sltd = $_POST['artisans'];
				} elseif (isset($_POST["technical_services"])) {
					$technical_services_sltd = $_POST['technical_services'];
				} elseif (isset($_POST["spare_parts"])) {
					$spare_parts_sltd = $_POST['spare_parts'];
				}				
			}else {
				$this->errors["business_category_error"] = "Select either a technical service or a spare part.";
			}
		}
		
		// Validate the submission of image
		if (array_key_exists("SubmitPhoto", $_POST)) {
			if (isset($path)) {
				$this->is_image($path);
			} else {
				$this->errors["image_error"] = "Image was not found.";
			}
		}
	}	
	
	public function validate_customer_availability() {
		global $day;
		global $hours;
		
		if (!isset($_POST["set_days"])){
			$this->errors["set_days"] = "Select a weekday.";
		} elseif ($_POST["set_days"] === 'select') {
			$this->errors["set_days_error"] = "Select a weekday.";
		} else {
			// save the variable 
			$day = $_POST["set_days"];
			// "$".{$field} = $_POST[$field];
		}
		
		
		// This array contains the brand of cars selected by the customer
		if (!isset($_POST["set_hours"])){
			$this->errors["set_hours_error"] = "Select an appointment time.";
		}else {
			$hours = $_POST['set_hours'];
		}
	}
	
	public function validate_make_appointment() {
		global $day;
		global $hours;
		
		if (!isset($_POST["choose_day"])){
			$this->errors["choose_day"] = "Select a weekday.";
		} elseif ($_POST["choose_day"] === 'select') {
			$this->errors["choose_day_error"] = "Select a weekday.";
		} else {
			// save the variable 
			$day = $_POST["choose_day"];
			// "$".{$field} = $_POST[$field];
		}
		
		
		// This array contains the brand of cars selected by the customer
		if (!isset($_POST["choose_hours"])){
			$this->errors["choose_hours_error"] = "Select an appointment time.";
		}else {
			$hours = $_POST['choose_hours'];
		}
	}
}

$validate = new Validation();

?>