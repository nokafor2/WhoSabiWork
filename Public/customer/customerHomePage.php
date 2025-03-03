<?php
require_once("../../includes/initialize.php");
$message = "";

$customerID = 0;
// This variable contains the filtered values from the GET_global variable. It is an array
$get_params = allowed_get_params(['id']);

if (isset($get_params["id"])) {
	// decode the url to get the proper hashed id
	// $hashedId = urldecode(trim($get_params["id"]));
	// decrypt the hashed id
	// $encryptionObj = new Encryption();
	// $customerID = $encryptionObj->decrypt($hashedId);
	$customerID = $get_params["id"];
	
	// $get_params["id"] == $_SESSION["viewedCusId"]
	if (!is_numeric($customerID)) {
		global $session;
		// Return an error message to the customer and log a spurious attempt to get into someone's profile.
		$session->message("Invalid customer-id received. Please try again.");
		$session->message("Customer Id received is: .".$customerID);
		// redirect to same page if incorrect customer id is provided.
		// redirect_to("/Public/customer/customerHomePage.php?id=".urlencode($customerID));

		// Redirect to home page
		redirect_to("/Public/index.php");
	}
	
	// If the redirect page is save in session, unset it
	if (isset($_SESSION['redirectPage']) && !empty($_SESSION['redirectPage'])) {
		unset($_SESSION['redirectPage']);
	}

	$business_category = new Business_Category();
	$car_brands = new Car_Brand();
	$bus_brands = new Bus_Brand();
	$truck_brands = new Truck_Brand();
	// $photograph = new Photograph();
	$technical_services = new Technical_Service();
	$artisan_services = new Artisan();
	$spare_parts = new Spare_Part();
	$seller_products = new Seller();
	// $vehicle_category = new Vehicle_Category();

	$customerDetails = Customer::find_by_id($customerID);
	// echo $customerDetails->business_title; $customerDetails->full_name();
	// Check if business title is available
	if (empty($customerDetails->business_title) || is_null($customerDetails->business_title)) {
		$businessTitle = "No business title available";
	} else {
		$businessTitle = $customerDetails->business_title;
	}
	
	// Check for the customer's full name
	if (is_bool($customerDetails)) {
		$cus_full_name = "No name available";
	} else {
		$cus_full_name = $customerDetails->full_name();
	}
	
	// Check if a phone number is available
	if (empty($customerDetails->phone_number) || is_null($customerDetails->phone_number)) {
		$customerNumber = "No phone number available";
	} else {
		$customerNumber = $customerDetails->phone_number;
	}
	
	// Check if an email address is available
	if (empty($customerDetails->customer_email) || is_null($customerDetails->customer_email)) {
		$customerEmail = "No email available";
	} else {
		$customerEmail = $customerDetails->customer_email;
	}
	
	// Define an object to get the address
	$address = Address::find_by_customerId($customerID);
	if (is_bool($address)) {
		$full_address = FALSE;
	} else {
		$full_address = $address->full_address();
	}
	
	// Define an object to get the pictures
	$customerPictures = Photograph::find_customer_images($customerID);

	// Get the list of selected cars of the customer
	$carBrands = $car_brands->selected_choices($customerID);
	// Get the list of selected cars of the customer
	$busBrands = $bus_brands->selected_choices($customerID);
	// Get the list of selected cars of the customer
	$truckBrands = $truck_brands->selected_choices($customerID);

	// Get the list of selected technical services of the customer
	$technicalServices = $technical_services->selected_choices($customerID);
	// Get the list of selected artisans skills of the customer
	$artisanServices = $artisan_services->selected_choices($customerID);
	// Get the list of selected spare parts sold by the customer
	$spareParts = $spare_parts->selected_choices($customerID);
	// Get the list of seller products
	$sellerProducts = $seller_products->selected_choices($customerID);

	// Get the vehicle type the technician fixes
	$vehicleCategory = Vehicle_Category::find_by_customerId($customerID);
	// Check if a vehicle category exist in the database or what it is
	if (is_bool($vehicleCategory)) {
		$vehicleClassification = FALSE;
	} else {
		$vehicleClassification = $vehicleCategory->selected_vehicle_categoty();
	}

	// Get the business category of the customer
	$businessCategory = Business_Category::find_by_customerId($customerID);
	// Check if a business category exist in the database or what it is
	if (is_bool($businessCategory)) {
		$businessClassification = FALSE;
	} else {
		$businessClassification = $businessCategory->selected_business_categoty();
		$numberOfViews = $businessCategory->views;
	}
	
	if (!isset($businessCategory->business_description) || empty($businessCategory->business_description)) {
		$businessDescription = "Not specified yet";
	} else { 
		$businessDescription = $businessCategory->business_description; 
	}
} else {
	global $session;
	$session->message("No customer ID was provided.");
	// redirect to another page
	redirect_to("../homePage.php");
}

// submitting the comment
if (isset($_POST['submit'])) {
	
	global $database;
	if ($session->is_user_logged_in()) {
		$author = $_SESSION['user_full_name'];
		$userOrCusId = $_SESSION['user_id'];
	} elseif (($session->is_customer_logged_in())) {
		$author = $_SESSION['customer_full_name'];
		$userOrCusId = $_SESSION['customer_id'];
	}
	
	$message_content = $database->escape_value(trim($_POST['message_content']));
	// Validate that there was an input in the textarea before saving in the database.
	$validate->validate_name_update();
	if (empty($validate->errors)) {
		// makes a comment and returns an instance that can be used to reference the properties of the comment class
		$new_comment = User_Comment::make($customerID, $userOrCusId, $author, $message_content);
		if ($new_comment) {
			$new_comment->create();
			// redirect_to() is better used here so when the return key is clicked on the web browser, it will not spoil it. It is also used to clear the input of the user so it will not be re-inputed again when the return button is pressed on the browser
			redirect_to("customerHomePage.php?id={$customerID}");
		} else {
			$message = "There was an error that prevented the comment from saving.";
			?>
			<!-- <script> alert("There was an error that prevented the comment from saving."); </script> -->
			<?php
		}
	} else {
		// $message = "There was an error during validation. ";
	}
	
} else {
	$author = "";
	$message_content = "";
}

// Displaying all the comments
// Firstly, find all the comments, save them in an array and assign to a variable
// Secondly, display the comments by looping through that array displaying the comment one at a time.
// It is better to have the code to display the comments after here to save computing resources of having to go to the database when not needed.
$comments = User_Comment::find_comments_on($customerID);
// $comments = $customerDetails->comments();

$rate_customer = new Customer_Rating();

function check_ratable() {
	global $customerID;
	global $rate_customer;
	global $session;
	
	// Grant only the user and customer the option to rate a technician
	if ( $session->is_user_logged_in()) {
		$rateAble = '';
		// If the user has rated the customer before, disable rating
		// changed from $_SESSION["viewedCusId"]
		if ($rate_customer->check_user_rated($customerID) >= 1) {
			$rateAble = 'jDisabled';
		}
	} elseif ( $session->is_customer_logged_in()) {
		$rateAble = '';
		// If the customer has rated the customer before, disable rating
		if ($rate_customer->check_customer_rated($customerID) >= 1) {
			$rateAble = 'jDisabled';
		}
	} else {
		$rateAble = 'jDisabled';
	} 
	
	return $rateAble;
}


function displayCustomerDays() {
	global $database;
	// global $customerTime;
	// global $refinedTime;
	// global $customerAvailability;
	// global $counterForJS;
	global $customerID;
	
	$cus_availability = new Customers_Availability();
	$weekDays = $cus_availability->weekDaysFromToday();
	$weekDates = $cus_availability->weekDatesFromToday();
	$firstDateOfWeek = $weekDates[0];
	$lastDateOfWeek = $weekDates[6];
	
	$sql = "SELECT * FROM `customers_availability` WHERE date_available >= '{$firstDateOfWeek}' AND date_available <= '{$lastDateOfWeek}' AND customers_id = {$customerID} ";
	$result_set = $database->query($sql); 

	$count = 0; 
	$customerAvailability = array();
	$customerRecord = array();
	
	while($row = mysqli_fetch_assoc($result_set)){ 
		// Gets the saved schedule of the customer 
		// $customerRecord[$count] = $row;
		$customerAvailability[$count] = $row["date_available"];
		// $customerTime[$count] = array_splice($row, 3, -1);
		$count++; // Relevant
	}
	
	$editedSchedule = array();
	// remove the time part in the MYSQL datetime variable to include only the date.
	foreach ($customerAvailability as $key => $date) {
		$editedSchedule[$key] = substr($date, 0, strpos($date, ' '));
	}  
	
	// Begin concatenation of the code
	$output = "";
	// Save the customers_id in a hidden tag that will be used to send the value to the JavaScript code
	$output .= "<p id='customers_id' style='display:none;'>".$customerID."</p>";
	$index = 1; // Used to increment the date_available id name to match the array of the counting order of the select list. This can be seen in the JavaScript code.
	/* Save the week dates in a hidden tag that will be used to send the value to the JavaScript code */
	foreach ($weekDays as $key => $weekday) { 
	  if (in_array($weekDates[$key], $editedSchedule)) {
		$output .= "<p id='date_selected".$index."' style='display:none;'>".$weekDates[$key]."</p>";
		$index++;
	  }
	}
	// concatenate the menu list code that will be outputted.
	$output .= "<label for='choose_day'>Select Days:</label>
				<select name='choose_day' id='choose_day' onchange='userSelectedDay();'>
				<option value='select'>Select</option>";
				
	/* loop through the weekdays available for schedule and output it in the menu list. */
	foreach ($weekDays as $key => $weekday) { 
	  if (in_array($weekDates[$key], $editedSchedule)) {
		// $output .= '<option value="'.$weekday.'">'.ucfirst($weekday).'</option><br/>';
		// '.'selectedDay'.$key.'
		// id="'.$weekDates[$key].'"
		$output .= '<option class="selectedDay" value="'.$weekday.'" >'.ucfirst($weekday).'  '.date_to_text($weekDates[$key]).'</option><br/>'; 
	  }
	}
	
	$output .= "</select>";
	
	return $output;
}

function backLink() {
	global $businessClassification;
	$output = "";
	// Determine the various links

	if ($businessClassification === "Technician") {
		if (isset($_SESSION['searchedTechServ'], $_SESSION['searchedVehicleType'], $_SESSION['searchedVehicleBrand'], $_SESSION['searchedState'], $_SESSION['searchedTown'], $_SESSION['searchedPage'])) {

			$output .= '<a id="backLink" href="../servicePage.php?technicalService='.$_SESSION['searchedTechServ'].'&vehicleType='.$_SESSION['searchedVehicleType'].'&vehicleBrand='.$_SESSION['searchedVehicleBrand'].'&state='.$_SESSION['searchedState'].'&town='.$_SESSION['searchedTown'].'&page='.$_SESSION['searchedPage'].'"><button id="backLinkBtn" class="btnStyle1"><i class="fas fa-angle-double-left fa-lg"></i>Back to Search</button></a>';
		} else {
			$output .= '<a id="backLink" href="../servicePage.php"><button id="backLinkBtn" class="btnStyle1"><i class="fas fa-angle-double-left fa-lg"></i>Back to Search</button></a>';
		}			
	} elseif ($businessClassification === "Artisan") {
		if (isset($_SESSION['searchedArtisan'], $_SESSION['searchedState'], $_SESSION['searchedTown'], $_SESSION['searchedPage'])) {

			$output .= '<a id="backLink" href="../artisanPage.php?artisanType='.$_SESSION['searchedArtisan'].'&state='.$_SESSION['searchedState'].'&town='.$_SESSION['searchedTown'].'&page='.$_SESSION['searchedPage'].' "><button id="backLinkBtn" class="btnStyle1"><i class="fas fa-angle-double-left fa-lg"></i>Back to Search</button></a>';
		} else {
			$output .= '<a id="backLink" href="../artisanPage.php"><button id="backLinkBtn" class="btnStyle1"><i class="fas fa-angle-double-left fa-lg"></i>Back to Search</button></a>';
		}			
	} elseif ($businessClassification === "Seller") {
		if (isset($_SESSION['searchedSeller'], $_SESSION['searchedState'], $_SESSION['searchedTown'], $_SESSION['searchedPage'])) {

			$output .= '<a id="backLink" href="../mobileMarketPage.php?sellerType='.$_SESSION['searchedSeller'].'&state='.$_SESSION['searchedState'].'&town='.$_SESSION['searchedTown'].'&page='.$_SESSION['searchedPage'].' "><button id="backLinkBtn" class="btnStyle1"><i class="fas fa-angle-double-left fa-lg"></i>Back to Search</button></a>';
		} else {
			$output .= '<a id="backLink" href="../artisanPage.php"><button id="backLinkBtn" class="btnStyle1"><i class="fas fa-angle-double-left fa-lg"></i>Back to Search</button></a>';
		}			
	} else {
		if (isset($_SESSION['searchedSparePart'], $_SESSION['searchedVehicleType'], $_SESSION['searchedVehicleBrand'], $_SESSION['searchedState'], $_SESSION['searchedTown'], $_SESSION['searchedPage'])) {

			$output .= '<a id="backLink" href="../sparePartPage.php?sparePart='.$_SESSION['searchedSparePart'].'&vehicleType='.$_SESSION['searchedVehicleType'].'&vehicleBrand='.$_SESSION['searchedVehicleBrand'].'&state='.$_SESSION['searchedState'].'&town='.$_SESSION['searchedTown'].'&page='.$_SESSION['searchedPage'].'"><button id="backLinkBtn" class="btnStyle1"><i class="fas fa-angle-double-left fa-lg"></i>Back to Search</button></a>';
		} else {
			$output .= '<a id="backLink" href="../sparePartPage.php"><button id="backLinkBtn" class="btnStyle1"><i class="fas fa-angle-double-left fa-lg"></i>Back to Search</button></a>';
		}			
	}

	return $output;
}

// check if the user browsing is an advocate
function checkAdvocate() {
	global $session;
	global $customerID;

	if ($session->is_user_logged_in()) {
		$public_user_id = $session->user_id;
		$advocate = Advocate::find_user_advocator($customerID, $session->user_id);
		
		return !empty($advocate) ? true : false;
	} elseif ($session->is_customer_logged_in()) {
		$public_customer_id = $session->customer_id;
		$advocate = Advocate::find_customer_advocator($customerID, $session->customer_id);
		return !empty($advocate) ? true : false;
	}
}

function getNumberOfAdvocators() {
	global $customerID;

	$numberOfAdvocators = Advocate::count_advocators($customerID);

	return $numberOfAdvocators;
}

function showCustomerAvatar() {
	global $session;
	global $customerID;
	$output = "";
	$customerId = $session->customer_id;
	$photoObj = Photograph::find_avatar($customerID);
	// Return a true or false value
	if (!empty($photoObj)) {
		$imgPath = '../'.$photoObj->image_path();
		if (file_exists($imgPath)) {
			$output .= '../'.$photoObj->image_path();	
		} else {
			$output .= '../images/emptyImageIcon.png';
		}
	} else {
		$output .= '../images/emptyImageIcon.png';
	}

	return $output;
}

function setAvatarAndName() {
	global $cus_full_name;
	global $customerID;
	global $session;

	$output = "";
	$avatarStatus = showCustomerAvatar();
	if ($avatarStatus === '../images/emptyImageIcon.png') {
		// No avatar exist to display
		$output .= '
			<h3 id="cusFullName" ><i class="far fa-user" style="padding-right:10px;"></i>'.$cus_full_name.'</h3>';
		// $output .= '<button id="setAptLinkBtn" class="btnStyle1" ><a class="setAptScrollLink scroll" href="#setAppointment" >Set Appointment</a></button>';
		$output .= backLink().'<br/>';
		// check if the browser is logged in to display the subscribe button
		if ($session->is_user_logged_in() || $session->is_customer_logged_in()) {
			// check if the broswer has advocated the user
			if (checkAdvocate()) {
				$output .= '<button id="advocated" class="btnStyle1" style="display: inline-block;" data-customerid="'.$customerID.'">ADVOCATED</button>';
				$output .= '<button id="advocate" class="btnStyle1" style="display: none;" data-customerid="'.$customerID.'">ADVOCATE</button>';
			} else {
				$output .= '<button id="advocate" class="btnStyle1" style="display: inline-block;" data-customerid="'.$customerID.'">ADVOCATE</button>';
				$output .= '<button id="advocated" class="btnStyle1" style="display: none;" data-customerid="'.$customerID.'">ADVOCATED</button>';
			}
		}
		$output .= "<p id='numberAdvocates'>".getNumberOfAdvocators()." advocators</p>";
	} else {
		$output .= '
			<img id="avatarImage" style="float:left" src="'.$avatarStatus.'" alt="customer profile image" />
			<div id="cusNameDiv">
				<h3 id="cusFullName" >'.$cus_full_name.'</h3>';
		// $output .= '<button id="setAptLinkBtn" class="btnStyle1" ><a class="setAptScrollLink scroll" href="#setAppointment" >Set Appointment</a></button>';
		$output .= backLink().'<br/>';
			// check if the browser is logged in to display the subscribe button
			if ($session->is_user_logged_in() || $session->is_customer_logged_in()) {
				// check if the broswer has advocated the user
				if (checkAdvocate()) {
					$output .= '<button id="advocated" class="btnStyle1" style="display: inline-block;" data-customerid="'.$customerID.'">ADVOCATED</button>';
					$output .= '<button id="advocate" class="btnStyle1" style="display: none;" data-customerid="'.$customerID.'">ADVOCATE</button>';
				} else {
					$output .= '<button id="advocate" class="btnStyle1" style="display: inline-block;" data-customerid="'.$customerID.'">ADVOCATE</button>';
					$output .= '<button id="advocated" class="btnStyle1" style="display: none;" data-customerid="'.$customerID.'">ADVOCATED</button>';
				}
			}
			$output .= "<p id='numberAdvocates'>".getNumberOfAdvocators()." advocators</p>";
		$output .= '</div>';
	}
	
	return $output;
}

function displayCommentTextBoxAndBtn() {
	global $session;
	$output = "";

	if ($session->is_user_logged_in() || $session->is_customer_logged_in()) {
		$output .= '
		<textarea id="photoCommentText" name="photoCommentText" rows="1" placeholder="Leave a comment..." ></textarea>
		<button id="submitPhotoComment" class="btnStyle1" >Comment</button>
		';
	}

	return $output;
}

function customerSkills() {
	global $businessClassification, $artisanServices, $technicalServices, $spareParts, $sellerProducts;

	if ($businessClassification === "Artisan") { 		
		return join(", ", array_keys($artisanServices));
	} elseif ($businessClassification === "Seller") {
		return join(", ", array_keys($sellerProducts));
	} elseif ($businessClassification === "Technician") {
		return join(", ", array_keys($technicalServices));
	} elseif ($businessClassification === "Spare part seller") {
		return join(", ", array_keys($spareParts));
	} else {
		return "";
	}
}

function displayPicture($i, $photoObj) {
	global $photo;
	$output = "";

	$imgPath = '../images/'.$photoObj->filename;
	$output .= '<div name="cus-ad-image" class="cus-ad-image" id="cus-ad-image'.$i.'" style="background: url('.$imgPath.'); background-repeat: no-repeat; background-size: cover; background-position: center;" imgurl="'.$imgPath.'" onclick="galleryImg(this);">';
	$output .= '</div>';

	return $output;
}

?>


<?php
	$scriptName = scriptPathName();
	// Initialize the header of the webpage
	echo getUniquePageHeader($scriptName);

	$outputMessage = displayMessages();
	if (!empty($outputMessage)) {
		showErrorMessage($outputMessage);
	}
?>

<div id="topContainer">
	<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
	<?php include_layout_template('navigation_header.php'); ?>
</div>

<div id="container">
	<?php // echo displayMessages(); ?>
	<input type='hidden' id='pageId' name='pageId' value='<?php echo $customerID; ?>' />
	<input type='hidden' id='customerSkills' name='customerSkills' value='<?php echo customerSkills(); ?>' />

  <!-- Begining of Main Section -->
  <div id="mainTechnician">
    <div class="techInfo">
      <h1 id="cusBusName"><?php echo $businessTitle; ?></h1>	  
	  
		  <div id="ratingDiv">
			<?php $rating = array(); $rating = $rate_customer->get_rating($customerID); ?>
		    <div class="rating <?php echo check_ratable(); ?>" id="rating" data-average="<?php if ($rating['rating'] == NULL) { echo 0; } else { echo $rating['rating']; } ?>" data-id="<?php if (!empty($rating['customers_id'])) {echo $rating['customers_id'];} else {echo $customerID;}  ?>" >
		    </div>
		    <p id="numOfRate" >view(s): <?php echo $numberOfViews; ?> rate(s): <?php echo $rate_customer->rate_count($customerID); ?> </p>
		  </div>
	  
	  	<?php echo setAvatarAndName(); ?>
	  
		  <div class="share-btn-container">
		  	<p>Share your webpage with:</p>
		  	<a href="#" id="facebook-btn">
		  		<i class="fab fa-facebook"></i>
		  	</a>

		  	<a href="#" id="twitter-btn">
		  		<i class="fab fa-twitter"></i>
		  	</a>

		  	<a href="#" id="whatsapp-btn">
		  		<i class="fab fa-whatsapp"></i>
		  	</a>

		  	<a href="#" id="pinterest-btn">
		  		<i class="fab fa-pinterest"></i>
		  	</a>

		  	<a href="#" id="linkedin-btn">
		  		<i class="fab fa-linkedin"></i>
		  	</a>	  	
		  </div>
	  
		  <!--  -->
	    <p id="businessDescrip"><strong>Description of Business:</strong> <?php echo $businessDescription; ?> </p>
	  
		  <div class="techCategoryDiv"> <!--  -->
				<ul id="techCategoryUnorderdList">
					<li id="techCategoryList"><h4 class="techCategoryStyle">Business category: <?php if ($businessClassification) {echo $businessClassification;} else { echo "Not specified yet";} ?></h4></li>
					<?php if ($businessClassification === "Technician" || $businessClassification === "Spare part seller") {?>
						<li style="vehicleCategoryList"><h4 class="techCategoryStyle">Vehicle category: <?php if ($vehicleClassification) {echo $vehicleClassification; } else {echo "Not specified yet";}?></h4></li>
					<?php } ?>
				</ul>
		  </div>
	  
		  <!--  -->
	    <div class="techCategoryDiv">
		    <!-- Check if it's an artisan, a technician or spare part seller to know the type of heading to display -->
		    <h4 class="autoSpecialtyStyle">
				<?php 
					if ($businessClassification === "Technician") {
						echo "Technical Services:";
				  } elseif ($businessClassification === "Artisan") {
						echo "Artisan services:";
				  } elseif ($businessClassification === "Seller") {
						echo "Inventories:";
				  } elseif ($businessClassification === "Spare part seller") {
						echo "Spare parts:";
				  } else {
						echo "";
				  } 
				?>
				</h4>
				<!-- Check if it a technician or spare part seller to know if to display technical services or spare parts -->
				<?php if ($businessClassification === "Artisan") { ?>
					<ul class="fa-ul">
						<?php foreach ($artisanServices as $key => $value) { ?>
							<li id="artisanServiceList"><span class="fa-li fa-lg" style="padding-left:40px;"><i class="fas fa-dot-circle"></i></span> <p style="margin:0px; padding-left:40px;"><?php echo $key; ?></p> 
						<?php } ?>
						</li>
					</ul>
				<?php } elseif ($businessClassification === "Seller") { ?>
					<ul class="fa-ul">				
						<?php foreach ($sellerProducts as $key => $value) { ?>
							<li id="inventoryList"><span class="fa-li fa-lg" style="padding-left:40px;"><i class="fas fa-dot-circle"></i></span> <p style="margin:0px; padding-left:40px;"><?php echo $key; ?></p> 
						<?php } ?>
						</li>
					</ul>
				<?php } elseif ($businessClassification === "Technician") { ?>
					<ul class="fa-ul">
						<?php foreach ($technicalServices as $key => $value) { ?>
							<li id="technicalServiceList"><span class="fa-li fa-lg" style="padding-left:40px;"><i class="fas fa-wrench"></i></span> <p style="margin:0px; padding-left:40px;"><?php echo $key; ?></p> 
						<?php } ?>
						</li>
					</ul>
				<?php } elseif ($businessClassification === "Spare part seller") { ?>  
					<ul class="fa-ul">
						<?php foreach ($spareParts as $key => $value) { ?>
							<li id="sparePartList"><span class="fa-li fa-lg" style="padding-left:40px;"><i class="fas fa-cogs"></i></span> <p style="margin:0px; padding-left:40px;"><?php echo $key; ?></p> 
						<?php } ?>
						</li>
					</ul>
				<?php } else { ?>  
					<ul class="fa-ul">				
						<li id="sparePartList"><span class="fa-li fa-lg" style="padding-left:40px;"><i class="fas fa-frown"></i></span> <p style="margin:0px; padding-left:40px;"><?php echo "Not available"; ?></p> 				
						</li>
					</ul>
				<?php } ?>
		  </div>
	  
		  <!--  -->
		  <?php if ($businessClassification === "Technician" || $businessClassification === "Spare part seller") { ?>
		    <div class="techCategoryDiv">
				<h4 class="autoSpecialtyStyle">
					<?php 
						if ($vehicleClassification === "Cars") {
							echo "Car Specialties";
						} elseif ($vehicleClassification === "Buses") {
							echo "Bus Specialties";
						} elseif ($vehicleClassification === "Trucks") {
							echo "Truck Specialties";
						} else {  
							echo "";
						}
					?>
				</h4>
				<!-- Check if it a vehicle category is a car or bus or truck to know which to display -->
				<?php if ($vehicleClassification === "Cars") { ?>
					<ul class="fa-ul">
						<?php foreach ($carBrands as $key => $value) { ?>
							<li id="carBrandList"><span class="fa-li fa-lg" style="padding-left:40px;"><i class="fas fa-car"></i></span> <p style="margin:0px; padding-left:40px;"><?php echo $key; ?></p> 
						<?php } ?>
						</li>
					</ul>
				<?php } elseif ($vehicleClassification === "Buses") { ?>
					<ul class="fa-ul">
						<?php foreach ($busBrands as $key => $value) { ?>
							<li id="busBrandList"><span class="fa-li fa-lg" style="padding-left:40px;"><i class="fas fa-car"></i></span> <p style="margin:0px; padding-left:40px;"><?php echo $key; ?></p> 
						<?php } ?>
						</li>
					</ul>
				<?php } else { ?>  
					<ul class="fa-ul">
						<?php foreach ($truckBrands as $key => $value) { ?>
							<li id="truckBrandList"><span class="fa-li fa-lg" style="padding-left:40px;"><i class="fas fa-car"></i></span> <p style="margin:0px; padding-left:40px;"><?php echo $key; ?></p> 
						<?php } ?>
						</li>
					</ul>
				<?php } ?>
		    </div>
		  <?php } ?>
	  
	  	<!--  -->
      <div class="techCategoryDiv">
				<h4 class="autoSpecialtyStyle">Contact</h4>
				<!-- Address -->
				<p class="techDetails"><i class="far fa-address-card fa-lg" style="padding-right: 10px;"></i> <?php if ($full_address) {echo $full_address;} else {echo "No available address temporarily";} ?></p>
				
				<!-- Phone -->
				<p class="techDetails"><i class="fas fa-phone fa-lg" style="padding-right: 10px;"></i> <?php echo $customerNumber; ?></p>
				
				<!-- Email -->
				<p class="techDetails"><i class="far fa-envelope fa-lg" style="padding-right: 10px;"></i> <?php echo $customerEmail; ?></p>
      </div>
    </div>

    <!-- Begining of tabbed panel contents for photo gallery, comments and set appointment -->
		<div class="TabbedPanelDiv" style="margin-top: 20px;">
		  <div id="TabbedPanels1" class="TabbedPanels">
		    <ul class="TabbedPanelsTabGroup">
		      <li class="TabbedPanelsTab" tabindex="0">Gallery</li>
		      <li class="TabbedPanelsTab" tabindex="0">Reviews</li>
		    </ul>
		    <div class="TabbedPanelsContentGroup">
		      <!-- Contents for Photo Gallery-->
		      <div class="TabbedPanelsContent">
		      	<div class="photoGalleryDiv">
							<div class="photoContainer" >
							  <?php $i = 1; ?>
							  <?php foreach($customerPictures as $photo): ?>
								<div class="displayPicture">
									<input type='hidden' id='<?php echo "customerId".$i; ?>' name='customerId' value='<?php echo $customerID; ?>' />
									<input type='hidden' id='<?php echo "photoId".$i; ?>' name='photoId' value='<?php echo $photo->id; ?>' />
									<!-- <img id="<?php echo "cus-ad-image".$i; ?>" name="cus-ad-image" class="cus-ad-image" src="<?php // echo "../".$photo->image_path(); ?>" width="200" height="200" alt="customer ad image" /> -->
									<?php echo displayPicture($i, $photo); ?>
									<!-- This caption is not displayed until the image is clicked and enlarged. Thus the imageCaption class display is set to none -->
									<p class="imageCaption" id="<?php echo "imageCaption".$i; ?>"><?php echo $photo->caption; ?></p>
								</div>
								<?php $i = $i + 1; ?>
							  <?php endforeach;?>
							</div>
				    </div>
		      </div>

		      <!-- Contents for Comments -->
		      <div class="TabbedPanelsContent">
		      	<div class="commentDiv">
							<div class="commentDisplay">
								<div id="feedback"></div>
								<p id='totalComments'><?php echo User_Comment::count_by_id($customerID); ?> comment(s)</p>
								<!-- Here we would display the comments. Firstly, we would loop through the comment array and display them one after the other. -->
								<div id="comments">
								  <?php $i = 0; ?>
								  <?php foreach($comments as $comment): ?>
									<div class="comment" id="comment<?php echo $i; ?>">
										<div class="authorDate" id="authorDate<?php echo $i; ?>">
											<?php 
												// &&($comment->user_id_comment !== 0)
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
										<!-- <button id="replyBtn<?php // echo $i; ?>" class="replyBtn" onclick="reply(this)">Reply Comment</button> -->
										<!-- The reply div will be inserted here after the reply button is clicked -->
										<div class="replyContainer" id="replyContainer<?php echo $i; ?>">
										<?php $replies = User_Reply::find_replies_on_comment($customerID, $comment->id);
										$j = 0; ?>
										<?php foreach($replies as $reply): ?>
											
											<div id="replyComment<?php echo $i; echo $j; ?>" class="replyComment">
												<div class="authorDate" id="replyAuthorDate<?php echo $i; echo $j; ?>">
													<div class="author" id="replyAuthor<?php echo $i; echo $j; ?>">
														<?php 
															$commentCustomerId = $reply->customers_id;
															$customer = Customer::find_by_id($commentCustomerId);
															$full_name = $customer->full_name();
															echo $full_name; 
														?>
														 replied:
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
										<button id="submitReply" class="submitReply" customerId="<?php echo urlencode($customerID); ?>" onclick="addReply(this)">Reply</button>
									</div>
								  <!-- If no comment, display there is none -->
								  <?php if(empty($comments)) { echo "No comments on technician."; } ?>
								</div>
						
								<div id="makeComment">
									<!-- <h4 id="newCommentTitle" >New Comments</h4> -->
									<?php if ($session->is_user_logged_in() OR $session->is_customer_logged_in()) { ?>
									<!-- <form action="customerHomePage.php?id=<?php // echo urlencode($customerID); ?>" method="post"> -->
										<!-- <input type="text" name="author" value="" /> -->
										<h4 id="userCommentName">
										<?php 
											if ($session->is_user_logged_in()) {
												// echo $_SESSION['user_full_name'];
												echo $session->user_full_name;
											} else {
												// echo $_SESSION['customer_full_name'];
												echo $session->customer_full_name;
											}
										?>
										<?php  ?>
										</h4>
										<textarea id="commentTextarea" name="message_content" rows="2"></textarea>
										<br/>
										<!-- <input type="submit" name="submit" value="Submit Comment" /> -->
										<button id="submitComment" class="btnStyle3" customerId="<?php echo urlencode($customerID); ?>" ><a class="scroll" href="#commentDisplay">Comment</a></button>
									<!-- </form>	-->
									<?php } else { ?>
										<p>If you want to leave a comment?</p>
										<p id="signInSignUpInfo"><a class="btnStyle1" href="<?php echo returnPageTo('customerHomePage.php', '/Public/selectProfileType.php?page=signIn', $customerID)?>">Please Sign In</a> <span style="margin-left:20px; margin-right: 20px;">OR</span> <a class="btnStyle1" href="<?php echo returnPageTo('customerHomePage.php', '/Public/selectProfileType.php?page=signUp', $customerID)?>">Please Sign Up</a></p>
								    <?php } ?>
									<!-- <a style="font-size: 0.8em;" href="">Delete Account</a> -->
								</div>
							</div>		
						</div>
		      </div>
		    </div>
		  </div>
		</div>
		<!-- Ending of tabbed panel div -->

		<!-- Scroll to top widget -->
		<div class="to-top-div" >
			<a class="to-top" href="#mainServicePage"> ^ </a>
		</div>

		<button id="bookAppointmentBtn"> <i class="fas fa-clock"></i> Set Appointment</button>
  </div> <!-- End of Main Section -->
  
  <!-- Display the footer section -->
  <?php include_layout_template('navigation_footer.php'); ?>
</div>

<!-- This display the modal to project a larger image when clicked-->
<div class="bg-modal" >
	<div class="modal-content" >
		<div class="close-btn" >+</div>
		<div id="enlargedAdImgDiv">
			<div id="enlargedAdImg">
				<img id="enlargedAdPix" src="" alt="Enlarged selected photo of entrepreneur" >
			</div>
			<button id='adLike' class='adLike' onclick='saveLike(this)'><span><i class='fas fa-thumbs-up'></i></span></button>
			<p id="imgCaptionModal" ></p>
		</div>
		<div id="modalPhotoBtnDiv">				
			<div id="photoCommentDisplay">
				<div id="feedback"></div>
				<p id='totalComments'></p>
				<div id="photoComments">
				</div>
			</div>

			<?php
				// show the textbox if a user or customer is logged in
				echo displayCommentTextBoxAndBtn();
			?>
		</div>
	</div>
</div>

<!-- Modal for the display of error messages -->
<div class="messageModal">
	<div class="messageContainer">
		<div id="messageHead">Error</div>
		<div id="messageContent">Message to appear here.</div>
		<button id="closeBtn" class="btnStyle1">Close</button>
	</div>
</div>

<!-- Modal for booking an appointment -->
<div id="appointmentModal" >
	<div id="aptModalContent">
		<div id="closeAptModal" >+</div>
		<div id="setAppointment" class="setAppoitment">
			<h4 class="divHeading" id="schAptTitle">Schedule an appointment</h4>
			<?php echo displayMessages(); ?>
			
			<?php
				if ($session->is_user_logged_in() || $session->is_customer_logged_in()) {
				?>
					<form id="makeAppoitmentForm" name="makeAppoitmentForm" method="post" action="" >
						<!-- <fieldset class="makeAppoitment"><legend>Schedule an appointment</legend></fieldset> -->
					    <p> 
							<?php echo displayCustomerDays(); ?>
					    </p>
						<p id="checkboxOfDays"></p>
						<!-- This section of the form could be shifted into the function that dynamically displays the form when a date for appointment is selected. -->
						<p id="userNoteBox">
							<label for="userNote">Please provide a description for your appointment</label>
							<textarea name="appointment_message" id="appointment_message" rows="5"></textarea>
							<label id="wordCountLabel">
							  	Character Count:
							  <input type="text" id="wordCount" readonly value="0/250" style="width: 60px; text-align: right;">
							</label>
						</p>
						<p>
							<input type="submit" name="submit_appointment" id="submit_appointment" class="btnStyle1" value="Submit" />
						</p>
					  <!-- End of section to be cut. -->
					</form>
				<?php	} else { ?>
					<!-- <p class="divContent"> Please login/register to schedule an appointment. </p> -->
					<p id="signInSignUpInfo"><a class="btnStyle1" href="<?php echo returnPageTo('customerHomePage.php', '/Public/selectProfileType.php?page=signIn', $customerID)?>">Please Sign In</a> <span style="margin-left:20px; margin-right: 20px;">OR</span> <a class="btnStyle1" href="<?php echo returnPageTo('customerHomePage.php', '/Public/selectProfileType.php?page=signUp', $customerID)?>">Please Sign Up</a></p>
				<?php } ?>
		</div>
	</div>
</div>

<!-- Loader -->
<div class="loader">
	<img src="../images/utilityImages/ajax-loader1.gif" alt="Loading..." />
</div>

<?php
	// Initialize the footer of the webpage
	echo getUniquePageFooter($scriptName);
?>