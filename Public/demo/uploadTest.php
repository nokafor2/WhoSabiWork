<?php
	require_once("../../includes/initialize.php");
	// require_once("../layouts/header.php");	

	echo '<pre><br/>';
		echo "Session variables are: <br/>";
		print_r($_SESSION);
	echo '</pre><br/>';
	

	/*$adLimit = 3; $adOffset = 0;

	// Get the customers for paid advertisement using limit and offset
	$advertisers = Business_Category::find_cus_advertisements($adLimit, $adOffset);
	// Get customers ids of the advertisers into an array
	$customerIds = array();
	foreach ($advertisers as $record) {
		$customerIds[] = $record->customers_id;
	}

	echo "<pre>";
	  echo "Customers Ids are: <br/>";
		print_r($customerIds);
	echo "</pre>";

	// Get the photograph of all the customers to be displayed
	$photograph = new Photograph();
	$photograph_ids = $photograph->get_photograph_ids($customerIds);
	echo "<pre>";
	  echo "Customers photographs are: <br/>";
		print_r($photograph_ids);
	echo "</pre>";

	// Get the addresses of all the customers to be displayed
	$address = new Address();
	$addresses = array();
	$address_ids = $address->find_address_id_using_customer_id($customerIds);
	echo "<pre>";
	  echo "Customers addresses are: <br/>";
		print_r($address_ids);
	echo "</pre>";

	$rating = array();
	$rate_customer = new Customer_Rating();
	// Run a loop to get all the details of the customers
	if (count($customerIds) > 0) {
		for($i = 0; $i < count($customerIds); $i++){
			$rating[] = $rate_customer->get_rating($customerIds[$i]);
		}			
	} else {
		// No advertisers available
	}
	echo "<pre>";
	  echo "Customers ratings are: <br/>";
		print_r($rating);
	echo "</pre>";*/

	/*  $cars = array("volvo", "honda", "toyota", "benz", "acura", "nissan", "volvo", "honda", "toyota", "benz");
	$places = array("abuja", "enugu", "lagos", "abuja", "enugu");
	
	$skip = 4; $counter = 0;	
	for ($i = 0; $i < count($cars); $i++) {
		echo $i." is: ".$cars[$i]."<br/>";
		$modulo = $i % 2;
		// echo "modulo i: ".$modulo."<br/>";
		$counter++;
		if ($counter > $skip) {
			for ($j = 0; $j < count($places); $j++) {
				echo $j." is: ".$places[$j]."<br/>";
				array_shift($places);
				break;
			}
			$counter = 0;
		}
		echo "<br/>";
	}

	echo "<br/><br/>Method 2</br>";
	$places = array("abuja", "enugu", "lagos", "abuja", "enugu");
	$skip = 4; $counter = 0;	$tracker = 0;
	for ($i = 0; $i < count($cars); $i++) {
		echo $i." is: ".$cars[$i]."<br/>";
		$modulo = $i % 2;
		// echo "modulo i: ".$modulo."<br/>";
		$counter++;
		if ($counter > $skip) {
			if ($tracker < count($places)) {
				echo $tracker." is: ".$places[$tracker]."<br/>";
				$tracker++;
			}			
			$counter = 0;
		}
		echo "<br/>";
	}  */

	/* echo "The uploadable file size is: ";
	echo file_upload_max_size();

	echo "<br/><br/>The maximum number of file to upload is: ".ini_get("max_file_uploads"); */

	/*
	global $session;
	$advocate = new Advocate();
	$public_user_id = $session->user_id;
	if ($session->is_user_logged_in()) {
		$public_user_id = $session->user_id;
		$advocated = $advocate->find_all_advocated_by_user($public_user_id);	
	} elseif ($session->is_customer_logged_in()) {
		$public_customer_id = $session->customer_id;
		$advocated = $advocate->find_all_advocated_by_customer($public_customer_id);
	}

	// Get customers ids of the adovated
	$customerIds = array();
	foreach ($advocated as $record) {
		$customerIds[] = $record->customers_id;
	}

	echo '<pre><br/>';
		echo "Customer ids of the advocators are: <br/>";
		print_r($customerIds);
	echo '</pre><br/>';

	$advertisers = Business_Category::find_cus_advertisements();

	// Get customers ids of the advertisers
	foreach ($advertisers as $record) {
		$customerIds[] = $record->customers_id;
	}

	echo '<pre><br/>';
		echo "Customer ids of the advocators and advertisers are: <br/>";
		print_r($customerIds);
	echo '</pre><br/>';

	// Eliminate duplicate of the customers ids
	$customerIds = array_unique($customerIds);
	sort($customerIds);

	echo '<pre><br/>';
		echo "Unique Customer ids of the advocators and advertisers are: <br/>";
		print_r($customerIds);
	echo '</pre><br/>';

	// Get photograph records for the customers ids
	$advocatedPhotos = array();
	foreach ($customerIds as $id) {
		$advocatedPhotos[] = Photograph::find_customer_images($id);
	}

	echo '<pre><br/>';
		echo "advocated photos are: <br/>";
		print_r($advocatedPhotos);
	echo '</pre><br/>';
	
	$allFilenames = array();
	$dateCreated = array();
	$allCaptions = array();
	$timeArray = array();
	$customerIdsToPhotos = array();
	$photosId = array();
	foreach ($advocatedPhotos as $key => $value) {	
		foreach ($value as $obj) {
			// photograph names
			$photosId[] = $obj->id;
			$customerIdsToPhotos[] = $customerIds[$key];
			$allFilenames[] = $obj->filename;
			// date created for photographs
			$dateCreated[] = $obj->date_created;
			$timeArray[] = strtotime($obj->date_created);
			$allCaptions[] = $obj->caption;
		}		
	}

	$liveFeedData = array();
	$num = 0;
	foreach ($advocatedPhotos as $key => $value) {	
		foreach ($value as $obj) {
			$liveFeedData['photoId'][] = $obj->id;
			$liveFeedData['customerId'][] = $customerIds[$key];
			$liveFeedData['filename'][] = $obj->filename;
			$liveFeedData['date_created'][] = $obj->date_created;
			$liveFeedData['timeString'][] = strtotime($obj->date_created);
			$liveFeedData['caption'][] = $obj->caption;
		}		
	}
	*/

	/* echo '<pre><br/>';
		echo "Multidimensional array variables are: <br/>";
		print_r($liveFeedData);
	echo '</pre><br/>';
	
	echo '<pre><br/>';
		echo "Record of selected array variables are: <br/>";
		print_r($liveFeedData['photoId'][2]);
		echo '<br/>';
		print_r($liveFeedData['customerId'][2]);
		echo '<br/>';
		print_r($liveFeedData['filename'][2]);
		echo '<br/>';
		print_r($liveFeedData['date_created'][2]);
		echo '<br/>';
		print_r($liveFeedData['timeString'][2]);
		echo '<br/>';
		print_r($liveFeedData['caption'][2]);
	echo '</pre><br/>';

	echo "Record of selected array variables are: <br/>";
	foreach ($liveFeedData as $key => $value) {
		print_r($liveFeedData[$key]);
	} */
	
	/* echo '<pre><br/>';
		echo "Session variables are: <br/>";
		print_r($_SESSION);
	echo '</pre><br/>'; */

	/*
	echo getScriptName();

	echo "<br/>";

	$scriptPath = 'loginPage.php?profile=user';
	if (strpos($scriptPath, '?') !== false) {
		$exploadedPath = explode("?", $scriptPath);
		// Get the last array value as the script name
		$passedKeyVariable = end($exploadedPath);

		$keyVariableArray = explode("=", $passedKeyVariable);
	}		

	echo '<pre><br/>';
		print_r($keyVariableArray);
	echo '</pre><br/>';

	echo getProfileType($scriptPath);
	*/
	
	/*
	function checkFailedLogin($loginId) {
		global $database;
		$loginId = $database->escape_value($loginId);
		$loginIdType = getLoginIdType($loginId);

		if ($loginIdType === 'username') {
			$userStatic = User::find_by_column_name('username', $loginId);
			// Get an array of username, email and phone_number
			if (!empty($userStatic)) {
				$userLoginDetails = array();
				$userLoginDetails['username'] = $loginId;
				$userLoginDetails['email'] = $userStatic->user_email;
				$userLoginDetails['phone_number'] = $userStatic->phone_number;

				echo "<pre>";
					echo "User login details are: ";
					print_r($userLoginDetails);
				echo "</pre>";

				$failed_login = array();
				foreach ($userLoginDetails as $loginIdPart => $value) {					
					if ($loginIdPart === 'username') {
						$failed_login[] = User_Failed_Login::find_by_column_name('user_username', $value);
					} elseif ($loginIdPart === 'email') {
						$failed_login[] = User_Failed_Login::find_by_column_name('email', $value);
					} else {
						$failed_login[] = User_Failed_Login::find_by_column_name('phone_number', $value);
					}					
				}
				echo "<pre>";
					echo "User's failed login details are: ";
					print_r($failed_login);
				echo "</pre>";

				if (empty($failed_login)) {
					// Create a record of a new failed login
					$this->user_id = $userStatic->id;
					$this->user_username = $userStatic->username;
					$this->email = NuLL;
					$this->phone_number = NuLL;
					$this->record = 1; 
					$this->last_time = $this->current_Date_Time();
					$this->create();
				} else {
					// Failed login already exist, update it.
					// Check if it is greater than the allowed failed attempt
					// $this->throttle_at
					if ($failed_login[0]->record >= 10) {				
						// Restart an existing failed_login record
						$failed_login[0]->user_username = $userStatic->username;
						$failed_login[0]->email = NuLL;
						$failed_login[0]->phone_number = NuLL;
						$failed_login[0]->record = 1; 
						$failed_login[0]->last_time = $failed_login[0]->current_Date_Time();
						$failed_login[0]->update();
					} else {
						// Update an existing failed_login record
						$failed_login[0]->user_username = $userStatic->username;
						$failed_login[0]->email = NuLL;
						$failed_login[0]->phone_number = NuLL;
						$failed_login[0]->record = $failed_login[0]->record + 1; 
						$failed_login[0]->last_time = $failed_login[0]->current_Date_Time();
						$failed_login[0]->update();
					}
				}
			}	else {
				// Username doen't exist in the user's table, create a new record
				// Create a record of a new failed login
				// Check if the new username exists in the database
				$failed_login = User_Failed_Login::find_by_column_name('user_username', $loginId);
				if (empty($failed_login)) {
					// Create a new record for a username that doesn't exist in the User table
					$this->user_id = NuLL;
					$this->user_username = $userStatic->username;
					$this->email = NuLL;
					$this->phone_number = NuLL;
					$this->record = 1; 
					$this->last_time = $this->current_Date_Time();
					$this->create();
				} else {
					// Update an existing failed_login record
					$failed_login->user_username = $userStatic->username;
					$failed_login->email = NuLL;
					$failed_login->phone_number = NuLL;
					$failed_login->record = $failed_login->record + 1; 
					$failed_login->last_time = $failed_login->current_Date_Time();
					$failed_login->update();
				}				
			}			
		} elseif ($loginIdType === 'email') {
			$userStatic = User::find_by_column_name('user_email', $loginId);
		} else {
			$userStatic = User::find_by_column_name('phone_number', $loginId);
		}
	}

	checkFailedLogin('nokafor');
	*/

	/*
	// $foundRecord =  Advocate::find_user_advocator(42, 1);
	$foundRecord =  Advocate::find_customer_advocator(25, 4);
	echo "<pre>";
		echo "Found advocates by user are: ";
		print_r($foundRecord);
	echo "</pre>";
	// $foundRecord
	*/

	/*
	$usersFeedback = new Users_Feedback();
	$feedbackBySubject = $usersFeedback->find_by_feedback_subject('suggestion');

	// Converthe mySql time to a better format
	foreach ($feedbackBySubject as $key => $feedback) {
		$feedbackBySubject[$key]->date_created = datetime_to_text($feedbackBySubject[$key]->date_created);
	}
	echo "<pre>";
		echo "Found feedbacks by subject are: ";
		print_r($feedbackBySubject);
	echo "</pre>";
	*/

	/* $mysql_date = strftime("%F", time());
	echo "Date format is: ".$mysql_date;
	echo "<br/>Date format is: ".date_to_weekday2(time());
	echo "<br/>Date format is: ".date_to_text(time()); */

	/* $user = new User();
	$foundUsers = $user->findUserByName("okafor");
	echo "<pre>";
		echo "Found users are: ";
		print_r($foundUsers);
	echo "</pre>"; */

	// unset($_SESSION['userRegisterValidate']);
	// unset($_SESSION['accountFormValidate']);
	/*
	echo "<pre>";
		echo "Session variables: ";
		print_r($_SESSION);
	echo "</pre>"; 
	*/
	/*
	$savedErrors = $_SESSION["userRegisterValidate"];
	// Eliminate error duplicates
	$uniqueArray = array_intersect_key($savedErrors, array_unique(array_map('serialize', $savedErrors)));
	
	echo "<pre>";
		echo "Saved form errors: ";
		print_r($savedErrors);
	echo "</pre>";
	*/
	/*
	$searchVal = 'aku oka eze';
	$searchVal = trim($searchVal);
	$searchPage = 'artisanPage.php';
	$customer = new Customer();
	$searchData = $customer->searchData($searchVal, $searchPage);
	echo "<pre>";
	echo "Search result: <br/>";
	print_r($searchData);
	echo "</pre>";
	*/

	/* $uniqueArray = array_intersect_key($searchData, array_unique(array_map('serialize', $searchData)));
	echo "<pre>";
	echo "Unique array: <br/>";
	print_r($uniqueArray);
	echo "</pre>"; */
	

	/*
	$customerID = 35;
	$sellerObj = new Seller();
	$sellerProducts = $sellerObj->selected_choices($customerID);
	// get the seller products saved in the keys of the array
	$sellerProducts = array_keys($sellerProducts);

	if (is_array($sellerProducts)) {
		// convert the array into a string
		$products = implode(", ", $sellerProducts);
	} else {
		$products = $sellerProducts;
	}
	echo "<pre>";
	echo "Seller products <br/>";
	print_r($products);
	echo "</pre>";
	*/

	
	/*
	// $imagePath = navigateToImagesFolder()."WhoSabiWorkL1.jpg";
	// whosabiworkLogo
	$body = '<img src="cid:whoSabiWorkLogo"/>
	<h2>Testing sending mail.</h2>
	<p>Welcome to WhoSabiWork.com</p>
	';
	if (sendMailFxn('support@whosabiwork.com', 'WhoSabiWork', 'support@whosabiwork.com', 'Testing image attachement.', $body)) {
		echo "Message sent successfully.";
	} else {
		echo "Message was not sent.";
	} */
	
	
	// sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='whosabiwork.com', $to='', $title='', $body='')

	/*
	$page = 1;
	$per_page = 9;
	$publicAdDisplay = new Public_Ad_Display($page, $per_page);
	$uniqueIdsAndCount = $publicAdDisplay->num_artisan_ids('tailor', 'abuja', '');
	echo "<pre>";
	echo "Customer ids <br/>";
	print_r($uniqueIdsAndCount);
	echo "</pre>";
	*/

	/*
	$page = 1;
	$per_page = 9;
	$publicAdDisplay = new Public_Ad_Display($page, $per_page);
	$uniqueIdsAndCount = $publicAdDisplay->count_customerIds('car', 'toyota', 'engine_service', 'technician', 'abuja', '');
	echo "<pre>";
	echo "Customer ids <br/>";
	print_r($uniqueIdsAndCount);
	echo "</pre>";
	// , 'apo'

	$page = 1;
	$per_page = 9;
	$publicAdDisplay = new Public_Ad_Display($page, $per_page);
	list($customer_ids, $total_count) = $publicAdDisplay->find_customer_ids('car', 'toyota', 'engine_service', 'technician', 'abuja', '');
	echo "<pre>";
	echo "Customer ids <br/>";
	print_r($customer_ids);
	echo "</pre>";
	echo "Total count is: ".$total_count;
	*/

	/*
	$addressObj = new Address();
	$sellerTowns = $addressObj->getSellerTowns("clothes_and_apparels", "lagos");
	echo "<pre>";
		print_r($sellerTowns);
	echo "</pre>";
	*/

	/*
	$artisanObj = new Artisan();
	$availableArtisans = $artisanObj->getAvailableArtisans();
	echo "<pre>";
		print_r($availableArtisans);
	echo "</pre>";

	$sellerObj = new Seller();
	$availableSellers = $sellerObj->getAvailableSellers();
	echo "<pre>";
		print_r($availableSellers);
	echo "</pre>";
	*/

	/*
	$photoObj = new Photograph();
	$photoIds = $photoObj->get_photograph_ids_by_customer_ids([2,3,4,26,35]);
	echo "<pre>";
		print_r($photoIds);
	echo "</pre>";
	*/
	
	/*
	$customer_Ids_Array = array('0' => 2, '1' => 5, '2' => 7, '3' => 8, '4' => 9);
	$addressObj = new Address();
	$addressIdVals = $addressObj->find_address_id_by_customer_id($customer_Ids_Array);
	echo "<pre>";
		print_r($addressIdVals);
	echo "</pre>"; 
	*/

	/*
	$customer = Customer::find_all();
	$customerObj = new Customer();
	$searchedCustomer = $customerObj->searchData('okafor', 'artisanPage.php');
	echo "<pre>";
		print_r($searchedCustomer);
	echo "</pre>";

	foreach ($searchedCustomer as $key => $customerData) {
		// print_r($customerData);
		echo $customerData->first_name."<br>";
		$customerDetails[] = array("firstName" => $customerData->first_name, "lastName" => $customerData->last_name, "username" => $customerData->username);
	}
	echo "<pre>";
		print_r($customerDetails);
	echo "</pre>";
	*/

	/*
	$addressObj = new Address();
	$towns = $addressObj->getAvailableTowns("car", "toyota", "engine_service", "technician", 'Abuja');
	echo "<pre>";
		print_r($towns);
	echo "</pre>";
	*/
	/*
	$sparePartObj = new Spare_Part();
	$spareParts = $sparePartObj->getSpareParts();
	echo "<pre>";
		print_r(array_flip($spareParts));
	echo "</pre>";

	$availableSpareParts =  $sparePartObj->getAvailableSpareParts("car", "toyota", "spare_part_seller");
	echo "<pre>";
		print_r($availableSpareParts);
	echo "</pre>";
	*/
	
	/*
	$techServObj = new Technical_Service();
	$technicalServices = $techServObj->getTechnicalServices();
	echo "<pre>";
		print_r(array_flip($technicalServices));
	echo "</pre>";

	$availableTechServ =  $techServObj->getAvailableTechServ("car", "acura", "technician");
	echo "<pre>";
		print_r($availableTechServ);
	echo "</pre>";
	*/

	/*
	$busBrandsObj = new Bus_Brand();
	$busBrands = $busBrandsObj->getBusBrands();
	$availableBusBrands = $busBrandsObj->getAvailableBusBrands("technician");
	echo "<pre>";
		print_r(array_flip($busBrands));
	echo "</pre>";
	echo "<pre>";
		print_r($availableBusBrands);
	echo "</pre>"; */

	/* 
	$truckBrandsObj = new Truck_Brand();
	$truckBrands = $truckBrandsObj->getTruckBrands();
	$availableTruckBrands = $truckBrandsObj->getAvailableTruckBrands("technician");
	echo "<pre>";
		print_r(array_flip($truckBrands));
	echo "</pre>";
	echo "<pre>";
		print_r($availableTruckBrands);
	echo "</pre>"; */
	
	/*
	$artisansObj = new Artisan();
	$artisans = $artisansObj->getArtisans();
	// print_r($artisans);
	// get an array of the artisan types
	$artisans = array_keys($artisans);
	// Sort the array in alphabetical order
	asort($artisans);
	echo "<pre>";
	echo "The artisnas are: <br/>";
	print_r($artisans);
	echo "</pre>";

	$sql = "SELECT ";
	$sql .= join(", ", array_values($artisans));
	$sql .= " FROM artisans";
	echo "<br/> SQL query is: ".$sql."<br/>";
	
	$result_set = $database->query($sql); // Relevant
		
	$selectedArtisans = array();
	// STEP2: Get the technicians ids in an array
	while($row = mysqli_fetch_assoc($result_set)){ 
		// Gets the customers ids 
		$selectedArtisans[] = $row; 
	}
	
	echo "<pre>";
	echo "The selected columns are: <br/>";
	print_r($selectedArtisans);
	echo "</pre>";

	$catererColumn = array_column($selectedArtisans, 'caterer');
	echo "<pre>";
	echo "The selected caterer column is: <br/>";
	print_r($catererColumn);
	echo "</pre>";

	if (in_array(1, $catererColumn)) {
		echo "column is valid <br/><br/>";
	}

	$availableArtisans = array();
	foreach ($artisans as $key => $value) {
		$selectedColumn = array_column($selectedArtisans, $value);
		if (in_array(1, $selectedColumn)) {
			echo $value." column is valid <br/>";
			$availableArtisans[] = $value;
		}
	}

	$showArtisans = $artisansObj->getAvailableArtisans();
	echo "<pre>";
	echo "<br/>The available artisans are: <br/>";
	print_r($showArtisans);
	echo "</pre>";
	*/

	/*
	foreach ($artisans as $key => $value) {
		$sql = "SELECT * FROM artisans WHERE ".$value." = 1";
		$output = Artisan::find_by_sql($sql);
		$output2 = array_shift($output);
		echo "<pre>";
		echo "The registered artisnas are: <br/>";
		echo "The value is ".$value;
		// echo $output2->{$value};
		// if ($output2->{$value}) {
		//	echo $key." is available. <br/>";
		// }
		echo "</pre>";
	} */
	

	/*
	$photoId = 14;
	$photoLikes = Photograph_Like::find_all_photograph_likes($photoId);
	echo "<pre>";
	echo "The records of photo likes by id is: <br/>";
	print_r($photoLikes);
	echo "</pre>";

	foreach ($photoLikes as $key => $record) {
		// echo "<br/>Like id is: ".$record->id."<br/>";
		$imageLikeProperties = Photograph_Like::find_by_id($record->id);
		if ($imageLikeProperties->delete()) {
			echo "<br/>Photo likes deleted successfully. <br/>";
		}		
	}

	$photoReplies = Photograph_Reply::find_photograph_replies_by_photoId($photoId);
	echo "<pre>";
	echo "The records of photo replies by id is: <br/>";
	print_r($photoReplies);
	echo "</pre>";

	foreach ($photoReplies as $key => $record) {
		// echo "<br/>Like id is: ".$record->id."<br/>";
		$imageReplyProperties = Photograph_Reply::find_by_id($record->id);
		if ($imageReplyProperties->delete()) {
			echo "<br/>Photo likes deleted successfully. <br/>";
		}		
	}

	$photoComments = Photograph_Comment::find_photograph_comments_by_photoId($photoId);
	echo "<pre>";
	echo "The records of photo comments by id is: <br/>";
	print_r($photoComments);
	echo "</pre>";

	foreach ($photoComments as $key => $record) {
		// echo "<br/>Like id is: ".$record->id."<br/>";
		$imageCommentProperties = Photograph_Comment::find_by_id($record->id);
		if ($imageCommentProperties->delete()) {
			echo "<br/>Photo likes deleted successfully. <br/>";
		}		
	}

	$imageProperties = Photograph::find_by_id($photoId);
	if ($imageProperties->delete()) {
		echo "<br/> Image was successfully deleted <br/>";
	}
	*/

	/*
	$encryptionObj = new Encryption();
	$unhashedId = "";
		$hashedId = "";
		$startLoop = true;
		while ($startLoop) {
			// global $unhashedId, $hashedId, $encryptionObj;
			$hashedId = $encryptionObj->encrypt(10);
			echo "hashed id is: ".$hashedId."<br/>";
			// encode the hashedId for url
			$encodedId = urlencode($hashedId);
			echo "Encoded id is: ".$encodedId."<br/>";
			$decodedId = urldecode($encodedId);
			echo "Decoded id is: ".$decodedId."<br/>";
			$unhashedId = $encryptionObj->decrypt($decodedId);
			echo "Decrypted id is: ".$unhashedId."<br/>";
			if (is_numeric($unhashedId)) {
				$startLoop = false;
			}
		}

	echo "The hashedId is: ".$hashedId;
	*/

	/*
	$encryptionObj = new Encryption();
	echo "secret key is: ".$encryptionObj->get_private_secret_key()."<br/>";
	$encryptedString = $encryptionObj->encrypt(5);
	echo "Encrypted number is: ".$encryptedString."<br/>";
	echo "Decrypted number is: ".$encryptionObj->decrypt($encryptedString)."<br/><br/>";


	function encrypt($message, $encryption_key) {
		$key = hex2bin($encryption_key);

		$nonceSize = openssl_cipher_iv_length('aes-256-ctr');
		$nonce = openssl_random_pseudo_bytes($nonceSize);

		$ciphertext = openssl_encrypt(
			$message, 
			'aes-256-ctr', 
			$key,
			OPENSSL_RAW_DATA,
			$nonce
		);

		return base64_encode($nonce.$ciphertext);
	}

	function decrypt($message, $encryption_key) {
		$key = hex2bin($encryption_key);
		$message = base64_decode($message);
		$nonceSize = openssl_cipher_iv_length('aes-256-ctr');
		$nonce = mb_substr($message, 0, $nonceSize, '8bit');
		$ciphertext = mb_substr($message, $nonceSize, null, '8bit');

		$plaintext = openssl_decrypt(
			$ciphertext, 
			'aes-256-ctr', 
			$key,
		  OPENSSL_RAW_DATA,
		  $nonce
		);

		return $plaintext;
	}

	$original_string = "23";
	// $private_secret_key = '1d3f90l2da4b78f9ca91eb3f98a35ea7b8ac3d1f';
	$private_secret_key = '208d7b51449b4b04b2ec4a3de636fff4';


	$encrypted = encrypt($original_string, $private_secret_key);
	echo 'Original string : '.$original_string.'<br/>';
	echo 'After encryption : '.$encrypted.'<br/>';
	echo 'Encoded encryption : '.urlencode($encrypted).'<br/>';
	echo 'Decoded encryption : '.urldecode($encrypted).'<br/>';
	echo 'After decryption : '.decrypt($encrypted, $private_secret_key).'<br/><br/>';
	*/


	/*
	$id = 89;
	$alteredId = (($id * 365 * 898989) / 865345);
	// $alteredId = $id;
	$encryptedId = urlencode(base64_encode($alteredId));
	echo "encrypted id is: ".$encryptedId."<br/><br/>";

	$decryptedId = base64_decode(urldecode($encryptedId));
	$recoveredId = ($decryptedId * 865345)/(365 * 898989);
	// $recoveredId = $decryptedId;
	echo "The original id is:".$recoveredId;
	*/

	/*
	$page = 1;
	$per_page = 9;
	$publicAdDisplay = new Public_Ad_Display($page, $per_page);
	// $uniqueIdsAndCount = $publicAdDisplay->get_artisan_ids('tailor', 'abuja', '');
	$uniqueIdsAndCount = $publicAdDisplay->get_customer_ids('toyota', 'engine_service', 'technician', 'car', 'abuja', '');
	echo "<pre>";
	echo "Customer ids <br/>";
	print_r($uniqueIdsAndCount[0]);
	echo "</pre>";

	// Get the customers ratings
	$customerRating = new Customer_Rating();
	$customerIdsAndRatings = array();
	$ratingsAndCounts = array();
	foreach ($uniqueIdsAndCount[0] as $key => $id) {
		// $ratingsAndCounts[$id] = $customerRating->getRatingAndCount($id);
		// $customerIdsAndRatings[$id] = $ratingsAndCounts[$id]['rating'];

		$ratingsAndCounts[$key] = $customerRating->getRatingAndCount($id);
		// $pointer = array_key_last($ratingsAndCounts); // works for PHP 7.3.0 or higher
		if (empty($ratingsAndCounts[$key]['customers_id'])) {
			// Save the customer id in the array
			$ratingsAndCounts[$key]['customers_id'] = $id;
		}
	}
	$ratings = array_column($ratingsAndCounts, "rating");
	$counts = array_column($ratingsAndCounts, 'counts');
	// Sort the array according to the rating and counts
	array_multisort($ratings, SORT_DESC, $counts, SORT_DESC, $ratingsAndCounts);
	echo "<pre>";
	echo "Sorted array according to rating and counts: <br/>";
	print_r($ratingsAndCounts);
	echo "</pre>";

	$arrangedIds = $publicAdDisplay->sortByRating($uniqueIdsAndCount[0]);
	echo "<pre>";
	echo "Sorted array according to rating and counts using function: <br/>";
	print_r($arrangedIds);
	echo "</pre>";
	*/
	
	/*
	echo "<pre>";
	echo "Get the customers ratings and counts <br/>";
	print_r($ratingsAndCounts);
	echo "</pre>";

	echo "<pre>";
	echo "Get the customers ratings <br/>";
	print_r($customerIdsAndRatings);
	echo "</pre>";

	// Sort the unique ids according to their ratings
	arsort($customerIdsAndRatings);
	echo "<pre>";
	echo "Sort the unique ids according to their ratings <br/>";
	print_r($customerIdsAndRatings);
	echo "</pre>";

	// Get the customers ids after sorting
	$sortedCusIds = array_keys($customerIdsAndRatings);
	echo "<pre>";
	echo "Get the customers ids after sorting <br/>";
	print_r($sortedCusIds);
	echo "</pre>";

	// Arrange the ratings and count according to the sorted array determined by rating.
	$newRatingsAndCounts = array();
	foreach ($sortedCusIds as $key => $id) {
		// Add the customer id in the array containing ratings and counts
		$ratingsAndCounts[$id]['id'] = $id;
		$newRatingsAndCounts[$key] = $ratingsAndCounts[$id];
	}
	echo "<pre>";
	echo "Merged arrays that is sorted<br/>";
	print_r($newRatingsAndCounts);
	echo "</pre>";

	$newRatingsAndCounts = array(
		array('counts' => 5, 'rating' => 5, 'id' => 23), 
		array('counts' => 6, 'rating' => 5, 'id' => 24), 
		array('counts' => 20, 'rating' => 5, 'id' => 25), 
		array('counts' => 4, 'rating' => 4, 'id' => 26), 
		array('counts' => 14, 'rating' => 4, 'id' => 27)
	);
	$counts = array_column($newRatingsAndCounts, 'counts');
	$ratings = array_column($newRatingsAndCounts, "rating");
	array_multisort($ratings, SORT_DESC, $counts, SORT_DESC, $newRatingsAndCounts);
	echo "<pre>";
	echo "Generated array <br/>";
	print_r($newRatingsAndCounts);
	echo "</pre>";
	*/

	/*
	// For the sorted arrays according to rating, resort it based on the highest count
	foreach ($newRatingsAndCounts as $key => $value) {
		$nextKey = $key;
		$nextKey++;
		// This is done to avoid out of bound error in checking array.
		if ($nextKey <= (count($newRatingsAndCounts) - 1)) {
			$currentRating = $newRatingsAndCounts[$key]['rating'];
			$nextRating = $newRatingsAndCounts[$nextKey]['rating'];
			// Check if the rating is equal
			if ($currentRating == $nextRating) {
				echo "</br>";
				echo "current key rating is: ".$currentRating;
				echo "</br>";
				echo "next key rating is: ".$nextRating;
				echo "</br>";
				$currentCount = $newRatingsAndCounts[$key]['counts'];
				$nextCount = $newRatingsAndCounts[$nextKey]['counts'];
				// compare the counts for the different ratings
				if ($currentCount < $nextCount) {
					// Save the current array in a temporary array that will be moved later
					$temporaryArray = $newRatingsAndCounts[$key];
					$newRatingsAndCounts[$key] = $newRatingsAndCounts[$nextKey];
					$newRatingsAndCounts[$nextKey] = $temporaryArray;
				}
			}
		}			
	}
	echo "<pre>";
	echo "Sorted arrays with counts <br/>";
	print_r($newRatingsAndCounts);
	echo "</pre>";

	/* Print out session 
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre><br/>"; */

	

	/*
	$photograph = new Photograph();
	$desktopPhotos = $photograph->getDesktopAdImages();
	$mobilePhotos = $photograph->getMobileAdImages();

	echo "The Photographs names are: ";
	echo "<pre>";
	print_r($desktopPhotos);
	print_r($mobilePhotos);
	echo "</pre><br/>";
	*/

	/*
	$emptyId = Photograph::getEmptyId();
	if (!empty($emptyId)) {
		echo "Empty id exists";
	} else {
		echo "No empty id exists";
	}
	
	$photographIds = Photograph::getEmptyId();
	$lastIndexVal = end($photographIds);
	echo "Photograph ids are: ";
	echo "<pre>";
	echo "Number of ids are: ".count($photographIds);
	echo "<br/>";
	echo "Last index: ".end($photographIds)."<br/>";
	print_r($photographIds);
	echo "</pre><br/><br/>";
	*/
	/*
	$emptyIds = array();

	// create an array the size of the last index of the ids
	$arrayCheck = range(1, end($photographIds));
	$emptyIds = array_diff($arrayCheck, $photographIds);
	$newArray = array_values($emptyIds);
	$firstEmptyVal = $newArray["0"];

	echo "Empty ids are: ";
	echo "<pre>";
	echo "First empty id is: ".$firstEmptyVal."<br/>";
	echo "Number of empty ids are: ".count($emptyIds);
	echo "<br/>";
	print_r($emptyIds);
	echo "</pre><br/><br/>";
	*/

	/*
	$currentPageName = substr($_SERVER['SCRIPT_NAME'], strpos($_SERVER['SCRIPT_NAME'], '/') + 1);
	echo "page url: ".$currentPageName."<br/><br/>";
	echo "page url: ".scriptPath()."<br/><br/>";
	echo "page name: ".scriptPathName()."<br/><br/>";
	$brokenPath = explode("/", $currentPageName);
	$pointer = 0;
	foreach ($brokenPath as $key => $value) {
		if ($value === 'Public') {
			$pointer = $key;
		}
	}
	$lengthArray = count($brokenPath);
	$filePosition = end($brokenPath);
	$lastFolderPos = $lengthArray - 1;
	if (($lengthArray - 1) > $pointer) {
		$numLoops = $lastFolderPos - ($pointer + 1);
		$path = "";
		for ($i=0; $i < $numLoops; $i++) { 
			$path .= "../";
		}
		$path .= "images/";
	} else {
		$numLoops = ($pointer + 1) - $lastFolderPos;
		$path = "";
	}
	
	echo "page path: ";
	echo "<pre>";
	print_r($brokenPath);
	echo $path."<br/>";
	echo "</pre><br/><br/>";
	

	$advocate = new Advocate();
	$usersAdvocated = $advocate->find_all_advocated_by_user(1);
	echo "customers record advocated by user are: ";
	echo "<pre>";
	// print_r($usersAdvocated);
	echo "</pre><br/><br/>";
	// Get customers ids 
	$customerIds = array();
	foreach ($usersAdvocated as $record) {
		$customerIds[] = $record->customers_id;
	}
	// Eliminate duplicate of the customers ids
	$customerIds = array_unique($customerIds);
	// Get photograph records for the customers ids
	$advocatedPhotos = array();
	foreach ($customerIds as $id) {
		$customerPhotos = Photograph::find_customer_images($id);
		$advocatedPhotos[] = $customerPhotos;
	}
	// echo "Photo records are: ";
	// echo "<pre>";
	// print_r($advocatedPhotos);
	// echo "</pre>";

	$allFilenames = array();
	$dateCreated = array();
	$allCaptions = array();
	$timeArray = array();
	$customerIdsToPhotos = array();
	foreach ($advocatedPhotos as $key => $value) {	
		foreach ($value as $obj) {
			// photograph names
			$customerIdsToPhotos[] = $customerIds[$key];
			$allFilenames[] = $obj->filename;
			// date created for photographs
			$dateCreated[] = $obj->date_created;
			$timeArray[] = strtotime($obj->date_created);
			$allCaptions[] = $obj->caption;
		}		
	}
	$sortedTimeArray = arsort($timeArray);
	$keysSortedTime = array_keys($timeArray);
	// array_walk($dateCreated, 'strtotime');
	echo "Photo filenames are: ";
	echo "<pre>";
	print_r($customerIdsToPhotos);
	print_r($allFilenames);
	print_r($allCaptions);
	print_r($dateCreated);
	print_r($timeArray);
	// print_r($sortedTimeArray);
	print_r($keysSortedTime);
	echo "</pre>";

	$output = "";
	foreach ($timeArray as $key => $value) {
		// get the customer id 
		$customerId = $customerIdsToPhotos[$key];
		// Get the customer business name
		$customer = Customer::find_by_id($customerId);
		$businessTitle = $customer->business_title;
		// Use the customer id to get the avatar of the customer
		$avatar = Photograph::find_avatar($customerId);
		if (!empty($avatar)) {
			$avatarFilename = $avatar->filename;
		} else {
			$avatarFilename = "emptyImageIcon.png";
		}
		$output .= "<div style='width:300px; height:480px; border: solid thin #808080; border-radius:5px; margin:10px; padding:5px;'>
			<img style='margin-bottom:5px;' width='300px' height='300px' src='../images/".$allFilenames[$key]."' />
			<img width='30px' height='30px' src='../images/".$avatarFilename."' />
			<a style='text-decoration: none;' href='../customer/customerHomePage.php?id=".$customerId."'><p style='margin-bottom:0px; font-style:italic; font-weight:bold; font-size: 20px; display:inline; '>".$businessTitle."</p></a><br/>
			<button style='margin-bottom:0px;  border:none; '>Like</button>
			<button style='margin-bottom:0px; display:inline; border:none;'>Views</button>
			<button style='margin-bottom:0px; display:inline; border:none;'>Comment</button>	
			<p style='margin-bottom:0px;  text-overflow: ellipsis; overflow: hidden; white-space: nowrap;'>".$allCaptions[$key]."</p>
			<p style='margin-bottom:0px;'>".date_to_text($dateCreated[$key])."</p>
			</div>";
	}
	echo $output;
	*/



	/*
	$photo = new Photograph();
	$filenames = $photo->getImageNames();
	echo "file names in image folder are: ";
	echo "<pre>";
	print_r($filenames);
	echo "</pre><br/><br/>";

	// Get all images in phtograph table
	$photos = $photo->find_all();
	$photoNames = array();
	foreach ($photos as $photo) {
		$photoNames[] = $photo->filename;
	}
	echo "file names in photograph table are: ";
	echo "<pre>";
	print_r($photoNames);
	echo "</pre><br/><br/>";

	// Get all images in user phtograph table
	$photo = new User_Photograph();
	$photos = $photo->find_all();
	$photoUserNames = array();
	foreach ($photos as $photo) {
		$photoUserNames[] = $photo->filename;
	}
	echo "file names in photograph table are: ";
	echo "<pre>";
	print_r($photoUserNames);
	echo "</pre><br/><br/>";

	// merge the two photograph arrays
	$photoMergeArray = array_merge($photoNames, $photoUserNames);
	echo "merged photograph table are: ";
	echo "<pre>";
	print_r($photoMergeArray);
	echo "</pre><br/><br/>";

	$cleanedPhotos = array();
	// Eliminate white spaces in the array
	foreach ($photoMergeArray as $value) {
		if (trim($value) !== "") {
			$cleanedPhotos[] = $value;
		}
	}
	echo "cleaned photograph array content: ";
	// print_r($filenames);
	echo "<pre>";
	print_r($cleanedPhotos);
	echo "</pre><br/><br/>";

	// get the difference between the photograph arrays in the image folder and those uploaded in the database
	$extraPhotos = array_diff($filenames, $cleanedPhotos);
	echo "Extra photo images are: ";
	echo "<pre>";
	print_r($extraPhotos);
	echo "</pre><br/><br/>";

	// Test the leftoverImages function
	$photo = new Photograph();
	$leftoverImages = $photo->leftoverImages();
	echo "Extra photo images are: ";
	echo "<pre>";
	print_r($leftoverImages);
	echo "</pre><br/><br/>";

	// Image differences
	$allowedImages = array("emptyImageIcon.png", "globalnav_bg.png");
	$imageDifference = array_diff($leftoverImages, $allowedImages);
	echo "Extra images to be deleted: ";
	echo "<pre>";
	print_r($imageDifference);
	echo "</pre><br/><br/>";

	if ($photo->deleteLeftoverImages()) {
		echo "unwanted images have been deleted";
	} else {
		echo "error occured";
	}
	echo "<br/><br/>";


	$leftoverImages = $photo->leftoverImages();
	echo "Extra photo images are: ";
	echo "<pre>";
	print_r($leftoverImages);
	echo "</pre><br/><br/>";
	*/

	/* $photoId = new Photograph();
	$imagePath = $photoId->emptyImageIcon();
	echo $imagePath."<br/>"; */


	/*
	// Code for uploading towns in a state
	$towns = array("Akure", "Itaogbolu", "Iju", "Idanre", "Ilaramokin", "Ijare", "Igbara Oke", "Ondo", "Ore", "Ile Oluji", "Okitipupa", "Ode Aye", "Igbotako", "Ilutitun", "Oniparaga", "Araromi Obu", "Ugbonla", "Okeigbo", "Bamikemon", "Odotu", "Igbokoda", "Owo", "Ikare", "Arigidi", "Irun", "Oke Agbe", "Ajowa", "Ogbagi", "Oka", "Iwaro", "Epinmi", "Akungba", "Oba", "Ifon", "Idonani", "Imeri", "Ijebu Owo", "Ipe", "Ikaramu", "Futa Community", "Oluwatuyi", "Ijapo", "Oba Ile", "Afo", "Ibaka", "Ifira", "Ipesi", "Ikun", "Isua", "Oka", "Supare", "Afin", "Akunnu Ikaram", "Auga", "Ese", "Erusu", "Gedegede", "Ibaram", "Iboropa", "Igasi", "Oyin", "Ikare", "Ifon", "Lepele", "Emure Ile", "Idoani", "Iyere", "Ute", "Ondo West", "Akinjagunla", "Bolorunduro", "College road", "Fagbo", "Igba", "Igunshiu", "Lisanu", "Oruru", "Oboto", "Odigbo", "Owena", "Erinje", "Ikoya", "Igbekebo", "Irele", "Sabomi", "Kbobomi", "Mahinido", "Kajola", "Igbo Egungun", "Igbotu");

	// print_r($abiaTowns);

	foreach ($towns as $key => $value) {
		$stateTown = State_Town::find_by_id($key + 1);
		if ($stateTown) {
			$stateTown->ondo = $value;
			$stateTown->update();
		} else {
			$town = new State_Town();
			$town->ondo = $value;
			$town->save();
		}
	}
	echo "finished";
	*/
	
	/*
	$state = "ebonyi";
	$newTown = "Gwarimpa";

	$town = new State_Town();
	$rowNumbers = $town->count_all();
	echo "The total number of rows is: ".$rowNumbers."<br/><br/>";

	$numTowns = $town->count_column($state);
	echo "The total number of elements in column is: ".$numTowns."<br/><br/>";	

	$towns = State_Town::find_by_column($state);
	echo "The towns in the states are: <br/>";
	print_r($towns);

	echo "<br/><br/>";

	foreach ($towns as $key => $value) {
		// remove the underscore in the name of towns
		$towns[$key] = str_replace('_', ' ', $value);
		// convert the text to lower case
		$towns[$key] = strtolower($value);
	}
	echo "Edited towns array: <br/>";
	print_r($towns);

	echo "<br/><br/>";
	// check if the string is in the array
	$newTown = strtolower($newTown);
	if (!in_array($newTown, $towns)) {
		// echo "Town already exists";
		echo "Town does not exit";
	} else {
		// echo "Town does not exit";
		echo "Town already exists";
	}
	*/
	// echo $_SESSION['townselect'];
	// $town = new State_Town();
	// $town->addNewTown('abuja', 'apo');

	/* if ($numTowns < $rowNumbers) {
		// update
		$stateTown = State_Town::find_by_id($numTowns + 1);
		$stateTown->{$state} = $newTown;
		$stateTown->update();
	} else {
		// save a new record
		$town = new State_Town();
		$town->{$state} = $newTown;
		$town->save();
	} */
	echo "<br/><br/>";
	echo "completed";
?>

<!--
Display the whole image in portrait with a scroll bar
<div style="width:200px; height:200px; overflow-y: scroll; border:thin solid gray;">
	<img style="width: 100%;" src="../images/ayuaTestImg.jpg">
</div>

<br/><br/>

Display the whole image in landscape with a scroll bar
<div style="width:200px; height:200px; overflow-x: scroll; border:thin solid gray;">
	<img style="height: 100%;" src="../images/ayuaTestImg2.jpg">
</div>

<br/><br/>

Display the whole image in landscape
<div style="width:200px; height:200px; display: grid; align-items: center; border:thin solid gray;">
	<img style="width: 100%; " src="../images/ayuaTestImg2.jpg">
</div>

<br/><br/>

Display the whole image in portrait
<div style="width:200px; height:200px; display: flex; justify-content: center; border:thin solid gray;">
	<img style="height: 100%; " src="../images/ayuaTestImg.jpg">
</div>

<br/><br/>

Center a landscape image
<div style="background: url('../images/ayuaTestImg2.jpg');
  background-repeat: no-repeat;
  background-size: cover; width:200px; height:200px; background-position: center;">	
</div>

<br/><br/>

Center a portrait image
<div style="background: url('../images/ayuaTestImg.jpg');
  background-repeat: no-repeat;
  background-size: cover; width:200px; height:200px; background-position: center;">	
</div>
-->

