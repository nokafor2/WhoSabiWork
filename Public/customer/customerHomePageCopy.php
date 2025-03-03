<?php
require_once("../../includes/initialize.php");
$message = "";

$customerID = 0;
// This variable contains the filtered values from the GET_global variable. It is an array
$get_params = allowed_get_params(['id']);

if (isset($get_params["id"])) {
	// echo "<br/> Contents of the Session Global variable are: <br/>";
	// print_r($_SESSION);
	
	if (($get_params["id"] == $_SESSION["viewedCusId"])) {
		global $customerID;
		$customerID = (int)$get_params["id"];
		
		// Save the customer id into the session, so that it can be referenced when a post request is sent.
		// $_SESSION["viewedCusId"] = (int)$get_params["id"]; // After logout, unset viewedCusId in the Session Global
	} else {
		global $session;
		// Return an error message to the customer and log a spurious attempt to get into someone's profile.
		$session->message("Invalid customer-id received.");
		// redirect to same page if incorrect customer id is provided.
		redirect_to("/WhoSabiWork/Public/customer/customerHomePage.php?id=".urlencode($_SESSION["viewedCusId"]));
	}
	
	// $customer = new Customer();

	$business_category = new Business_Category();
	$car_brands = new Car_Brand();
	$bus_brands = new Bus_Brand();
	$truck_brands = new Truck_Brand();
	// $photograph = new Photograph();
	$technical_services = new Technical_Service();
	$artisan_services = new Artisan();
	$spare_parts = new Spare_Part();
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
	$customerPictures = Photograph::find_by_customerId($customerID);

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
		$message = "There was an error during validation. ";
	}
	
} else {
	$author = "";
	$message_content = "";
}

// Displaying all the comments
// Firstly, find all the comments, save them in an array and assign to a variable
// Secondly, display the comments by looping through that array displaying the comment one at a time.
// It is better to have the code to display the comments after here to save computing resources of having to go to the database when not needed.
$comments = User_Comment::find_comments_on($_SESSION["viewedCusId"]);
// $comments = $customerDetails->comments();

$rate_customer = new Customer_Rating();

function check_ratable() {
	global $rate_customer;
	global $session;
	
	// Grant only the user and customer the option to rate a technician
	if ( $session->is_user_logged_in()) {
		$rateAble = '';
		// If the user has rated the customer before, disable rating
		if ($rate_customer->check_user_rated($_SESSION["viewedCusId"]) >= 1) {
			$rateAble = 'jDisabled';
		}
	} elseif ( $session->is_customer_logged_in()) {
		$rateAble = '';
		// If the customer has rated the customer before, disable rating
		if ($rate_customer->check_customer_rated($_SESSION["viewedCusId"]) >= 1) {
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
	
	$sql = "SELECT * FROM `customers_availability` WHERE date_available >= '{$firstDateOfWeek}' AND date_available <= '{$lastDateOfWeek}' AND customers_id = {$_SESSION['viewedCusId']} ";
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
	$output .= "<p id='customers_id' style='display:none;'>".$_SESSION["viewedCusId"]."</p>";
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

if (isset($_POST['submit_appoitment'])) {
	// print_r($_POST);
	$cus_appointment = new Customers_Appointment();
	
	// validate the form was filled if the button is clicked.
	// Define variables for day and hour which will get updated in the validation method
	$day;
	$hours = array();
	$validate->validate_make_appointment();
	
	if (empty($validate->errors)) {
		$cus_appointment->customers_id = $_SESSION["viewedCusId"];
		$cus_appointment->appointment_date = $cus_appointment->makeDateForDay($day);
		$customerPageDetails = Customer::find_by_id($_SESSION["viewedCusId"]);
		$cus_appointment->customer_name = $customerPageDetails->full_name();
		$cus_appointment->customer_number = $customerPageDetails->phone_number;
		$customerAddressObj = Address::find_by_customerId($_SESSION["viewedCusId"]);
		$customerAddress = $customerAddressObj->full_address();
		$customerBusinessCategory = Business_Category::find_by_customerId($_SESSION["viewedCusId"]);
		$customerEmail = $customerPageDetails->customer_email;
		
		// Determine what business category the customer is
		if ($customerBusinessCategory->artisan) {
			$artisanObj = new Artisan();
			$artisanSkills = $artisanObj->selected_choices($_SESSION["viewedCusId"]);
			// get the artisan skills saved in the keys of the array
			$artisanSkills = array_keys($artisanSkills);
			if (is_array($artisanSkills)) {
				// convert the array into a string
				$skills = implode(", ", $artisanSkills);
			} else {
				$skills = $artisanSkills;
			}
		} elseif ($customerBusinessCategory->technician) {
			$mechanicObj = new Technical_Service();
			$mechanicSkills = $mechanicObj->selected_choices($_SESSION["viewedCusId"]);
			$mechanicSkills = array_keys($mechanicSkills);
			if (is_array($mechanicSkills)) {
				$skills = implode(", ", $mechanicSkills);
			} else {
				$skills = $mechanicSkills;
			}
		} elseif ($customerBusinessCategory->spare_part_seller) {
			$sparePartObj = new Spare_Part();
			$spareParts = $sparePartObj->selected_choices($_SESSION["viewedCusId"]);
			$spareParts = array_keys($spareParts);
			if (is_array($spareParts)) {
				$skills = implode(", ", $spareParts);
			} else {
				$skills = $spareParts;
			}
		}
		
		if ($session->is_user_logged_in()) {
			$cus_appointment->scheduled_user = $_SESSION['user_id'];
			$cus_appointment->appointment_owner = $_SESSION['user_full_name']; // $user->full_name(); 
			$userDetails = User::find_by_id($_SESSION['user_id']);
			$cus_appointment->appointer_number = $userDetails->phone_number;
			$schedulerEmail = $userDetails->user_email;
		} elseif (($session->is_customer_logged_in())) {
			$cus_appointment->scheduled_customer = $_SESSION['customer_id'];
			$cus_appointment->appointment_owner = $_SESSION['customer_full_name']; // $customer->full_name();
			$customerDetails = Customer::find_by_id($_SESSION['customer_id']);
			$cus_appointment->appointer_number = $customerDetails->phone_number;
			$schedulerEmail = $customerDetails->customer_email;
		}
		
		$cus_appointment->appointment_message = $_POST['appointment_message'];
		$cus_appointment->customer_decision = 'neutral';
		$cus_appointment->date_created = current_Date_Time();
		
		$cus_appointment->date_edited = current_Date_Time();
		
		$hours_as_text_for_DB = '';
		$hours_as_text = array();
		$counter = 0;
		
		// Concatenate the hours and separate them with a comma.
		foreach ($hours as $hour) {
			// Convert the hours format to readable format
			$hours_as_text[$counter] = $cus_appointment->editDbVarToFormTime($hour);
			$hours_as_text_for_DB .= $hours_as_text[$counter].', ';
			$counter++;
		}
		// chop the last comma that was put in the concatenated string with the loop
		$hours_as_text_for_DB = chop($hours_as_text_for_DB, ", ");
		
		$cus_appointment->hours = $hours_as_text_for_DB;
		$savedCusAppointment = $cus_appointment->save();
		
		if(isset($savedCusAppointment)) {
			// send SMS and email to user creating the appointment
			if ($session->is_user_logged_in()) {
				// send SMS and email to customer receiving the appointment

				// Check if a phone number is available
				// This is the number of the customer (technician/artisan/spare part seller) that an appoitment is created with
				if (isset($cus_appointment->customer_number)) {
					$SMSmessage = makeReceiverNotificationSMS($_SESSION['user_full_name'], $userDetails->phone_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message);

					$SMSphoneNumber = ''.$cus_appointment->customer_number;
					// Send SMS to the customer (Receiver)
					// If there was an error sending text send a session message.		
					$SMSoutcome = sendSMSCode($SMSphoneNumber, $SMSmessage);
					sendSMSOutcome($SMSoutcome);
				}

				// Check if an email is available
				if (isset($customerEmail)) {
					$to = $customerEmail;
					$title='Appoointment Schedule from Customer';
					$body = makeReceiverNotificationEmail($_SESSION['user_full_name'], $userDetails->phone_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message);
					// Send Email to the customer (Receiver)
					$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork.com', $to, $title, $body);
					sendEmailOutcome($outcome);
				}

				// Check if a phone number is available
				// Send SMS to the user (with a user account) creating the appointment
				if (isset($userDetails->phone_number)) {
					// Erase the message and number variables
					$SMSphoneNumber = '';
					$SMSmessage = '';

					// send SMS and email to customer creating the appointment
					$SMSmessage = makeSenderNotificationSMS($cus_appointment->customer_name, $skills, $cus_appointment->customer_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message, $customerAddress);

					$SMSphoneNumber = ''.$userDetails->phone_number;
					// Send SMS to user
					$SMSoutcome = sendSMSCode($SMSphoneNumber, $SMSmessage);
					sendSMSOutcome($SMSoutcome);
				}

				// Check if an email is available
				if (isset($schedulerEmail)) {
					// Send email to user
					$to = $schedulerEmail;
					$title='Your Appoointment Schedule';
					$body = makeSenderNotificationEmail($cus_appointment->customer_name, $skills, $cus_appointment->customer_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message, $customerAddress);
					// Send Email to the user (Sender)
					$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork.com', $to, $title, $body);
					sendEmailOutcome($outcome);
				}
			} elseif (($session->is_customer_logged_in())) {
				// send SMS and email to user (having a customer account) creating the appointment
				// Check if a phone number is available
				if (isset($cus_appointment->customer_number)) {
					$SMSmessage = makeReceiverNotificationSMS($_SESSION['customer_full_name'], $customerDetails->phone_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message);

					// This is the number of the customer (technician/artisan/spare part seller) that an appoitment is created with
					$SMSphoneNumber = ''.$cus_appointment->customer_number;

					// Send SMS to the customer (Receiver)
					// If there was an error sending text, save a session message.		
					$SMSoutcome = sendSMSCode($SMSphoneNumber, $SMSmessage);
					sendSMSOutcome($SMSoutcome);
				}

				// Check if an email is available
				// Send an email to the customer (technician/artisan)
				if (isset($customerEmail)) {
					$to = $customerEmail;
					$title='Appoointment Schedule from Customer';
					$body = makeReceiverNotificationEmail($_SESSION['customer_full_name'], $customerDetails->phone_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message);
					// Send Email to the customer (Receiver)
					$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork.com', $to, $title, $body);
					sendEmailOutcome($outcome);
				}

				// Check if a phone number is available
				// Send an SMS to the person (with a customer account) scheduling an appointment 
				if (isset($customerDetails->phone_number)) {
					// Erase the message and number variables
					$SMSphoneNumber = '';
					$SMSmessage = '';

					// send SMS and email to customer creating the appointment
					$SMSmessage = makeSenderNotificationSMS($cus_appointment->customer_name, $skills, $cus_appointment->customer_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message, $customerAddress);

					$SMSphoneNumber = ''.$customerDetails->phone_number;
					// Send SMS to user
					$SMSoutcome = sendSMSCode($SMSphoneNumber, $SMSmessage);
					sendSMSOutcome($SMSoutcome);
				}

				// Check if an email is available
				// Send an email to the user (with a customer account) scheduling the account
				if (isset($schedulerEmail)) {
					// Send email to user
					$to = $schedulerEmail;
					$title='Your Appoointment Schedule';
					$body = makeSenderNotificationEmail($cus_appointment->customer_name, $skills, $cus_appointment->customer_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message, $customerAddress);
					// Send Email to the user (Sender)
					$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork.com', $to, $title, $body);
					sendEmailOutcome($outcome);
				}
			}


			// This message should be saved in the session.
			$message = "Your appointment was successfully scheduled.";
			// redirect_to('customerEditPage2.php');
		} else {
			$message = "An error occurred while saving.";
		}
	
	} else {
		$message = "There was an error during validation. ";
	}
}

function makeSenderNotificationSMS($fullName="", $skills="", $phoneNumber="", $date="", $time="", $reason="", $address="") {
	// send SMS and email to customer creating the appointment
	$SMSmessage = 'You have scheduled an appointment with an artisan.';
	$SMSmessage .= PHP_EOL.'Name: '.$fullName;
	// $SMSmessage .= PHP_EOL.'Expertise: '.$skills;
	$SMSmessage .= PHP_EOL.'Phone number: '.$phoneNumber;
	$SMSmessage .= PHP_EOL.'Date: '.date_to_text($date);
	$SMSmessage .= PHP_EOL.'Time: '.$time;
	// $SMSmessage .= PHP_EOL.'Reason: '.$reason;
	$SMSmessage .= PHP_EOL.'Address: '.$address;

	return $SMSmessage;
}

function makeSenderNotificationEmail($fullName="", $skills="", $phoneNumber="", $date="", $time="", $reason="", $address="") {
	// send SMS and email to customer creating the appointment
	$emailMessage = '<img src="cid:whosabiworkLogo">';
	$emailMessage .= '<p>You have scheduled an appointment with an artisan. The details are below.</p>';
	$emailMessage .= '<p>Name: '.$fullName.'<p>';
	$emailMessage .= '<p>Expertise: '.$skills.'<p>';
	$emailMessage .= '<p>Phone number: '.$phoneNumber.'<p>';
	$emailMessage .= '<p>Date: '.date_to_text($date).'<p>';
	$emailMessage .= '<p>Time: '.$time.'<p>';
	$emailMessage .= '<p>Reason: '.$reason.'<p>';
	$emailMessage .= '<p>Address: '.$address.'<p>';

	return $emailMessage;
}

function makeReceiverNotificationSMS($fullName="", $phoneNumber="", $date="", $time="", $reason="") {
	// send SMS and email to customer receiving the appointment
	$SMSmessage = 'A customer has scheduled an appointment with you.';
	$SMSmessage .= PHP_EOL.'Name: '.$fullName;
	$SMSmessage .= PHP_EOL.'Phone number: '.$phoneNumber;
	$SMSmessage .= PHP_EOL.'Date: '.date_to_text($date);
	$SMSmessage .= PHP_EOL.'Time: '.$time;
	// $SMSmessage .= PHP_EOL.'Reason: '.$reason;

	return $SMSmessage;
}

function makeReceiverNotificationEmail($fullName="", $phoneNumber="", $date="", $time="", $reason="") {
	// send SMS and email to customer receiving the appointment
	$emailMessage = '<img src="cid:whosabiworkLogo">';
	$emailMessage .= '<p>A customer has scheduled an appointment with you. The details are below. </p>';
	$emailMessage .= '<p>Name: '.$fullName.'</p>';
	$emailMessage .= '<p>Phone number: '.$phoneNumber.'</p>';
	$emailMessage .= '<p>Date: '.date_to_text($date).'</p>';
	$emailMessage .= '<p>Time: '.$time.'</p>';
	$emailMessage .= '<p>Reason: '.$reason.'</p>';

	return $emailMessage;
}

function backLink() {
	global $businessClassification;
	$output = "";
	// Determine the various links

	if ($businessClassification === "Technician") {
		$output .= '<a id="backLink" href="../servicePage.php"><i class="fas fa-angle-double-left fa-lg"></i> </a>';
	} elseif ($businessClassification === "Artisan") {
		$output .= '<a id="backLink" href="../artisanPage.php"><i class="fas fa-angle-double-left fa-lg"></i> </a>';
	} else {
		$output .= '<a id="backLink" href="../sparePartPage.php"><i class="fas fa-angle-double-left fa-lg"></i> </a>';
	}

	return $output;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo $cus_full_name." Home Page"; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style type="text/css">
.message {
	color: gray;
}
</style>
<link href="../../HomePageStyles.css" rel="stylesheet" type="text/css" />
<!-- Font Awesome Link -->
<link href="../stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />
<link href="../stylesheets/customerHomePageStyle.css" rel="stylesheet" type="text/css" />

<link href="../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script src="../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>

<!-- Javascripts and CSS links for the jRating -->
<link href="../javascripts/jRating/jquery/jRating.jquery.css" rel="stylesheet" type="text/css" />

<script src="../javascripts/jRating/jquery/jquery.js" type="text/javascript"></script>
<script src="../javascripts/jRating/jquery/jRating.jquery.js" type="text/javascript"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$(".rating").jRating({
			// decimalLength : 1, // number of decimal in the rate
			type : 'big',
			rateMax : 5, // maximal rate - integer from 0 to 9999 (or more)
			// phpPath : '../javascripts/jRating/libs/rating.php',
			phpPath : '../PHP-JSON/rating_JSON.php',
			bigStarsPath : '../javascripts/jRating/jquery/icons/stars.png', // path of the icon stars.png
			smallStarsPath : '../javascripts/jRating/jquery/icons/small.png' // path of the icon small.png
			// onSuccess : 'The post sending was successfully.',
			// onError : 'An error occured.',
			
			// canRateAgain : true,
			// nbRates : 3
		});
	});
</script>
</head>

<body>
<div id="topContainer">
	<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
	<?php include_layout_template('public_header.php'); ?>
</div>

<div id="container">
	<?php 
		if (isset($_SESSION['emailOutcomeMessage'])) {
			echo output_message($_SESSION['emailOutcomeMessage']);	
		}
		if (isset($_SESSION['SMSOutcomeMessage'])) {
			echo output_message($_SESSION['SMSOutcomeMessage']);	
		}
		if (isset($message)) {
			echo output_message($message);
			$message = ""; 
		}
		if (isset($validate->errors)) {
			echo $validate->form_errors($validate->errors); 	
		}
		unset($_SESSION['emailSuccess'], $_SESSION['emailError'], $_SESSION['emailMessage'], $_SESSION['SMSSuccess'], $_SESSION['SMSMessage']);
	?>

  <!-- Begining of Main Section -->
  <div id="mainTechnician">
    <div class="techInfo">
	  <!-- <a style="padding-left:10px; " href="">&laquo; Back</a><br/> -->
      <h1 id="cusBusName"><?php echo $businessTitle; ?></h1>
	  <!-- <a style="margin:0px" href="">&laquo; Back</a> -->
	  
	  
	  <div id="ratingDiv">
		<?php $rating = array(); $rating = $rate_customer->get_rating($_SESSION["viewedCusId"]); ?>
	    <div class="rating <?php echo check_ratable(); ?>" id="rating" data-average="<?php if ($rating['rating'] == NULL) { echo 0; } else { echo $rating['rating']; } ?>" data-id="<?php if (!empty($rating['customers_id'])) {echo $rating['customers_id'];} else {echo $_SESSION["viewedCusId"];}  ?>" >
	    </div>
	    <p id="numOfRate" >view(s): <?php echo $numberOfViews; ?> rate(s): <?php echo $rate_customer->rate_count($_SESSION["viewedCusId"]); ?> </p>
	  </div>
	  
	  <h3 id="cusFullName" ><i class="far fa-user" style="padding-right:10px;"></i><?php echo $cus_full_name; ?></h3>

	  <button id="setAptLinkBtn" class="btnStyle1" ><a class="setAptScrollLink scroll" href="#setAppointment" >Set Appointment</a></button>

	  <!--
	  <div style="padding-left:10px; ">
		<?php // $rating = array(); $rating = $rate_customer->get_rating($_SESSION["viewedCusId"]); ?>
	    <div class="rating <?php // echo check_ratable(); ?>" style="float:left; padding-right:10px;" data-average="<?php // echo $rating['rating']; ?>" data-id="<?php // echo $rating['customers_id']; ?>" >
	    </div>
	    <p style="margin:0px; " >Number of rate(s): <?php // echo $rate_customer->rate_count($_SESSION["viewedCusId"]); ?> </p>
	  </div>
	   -->
	  
	  <!--  -->
      <p id="businessDescrip">Description of Business: <?php echo $businessDescription; ?> </p>
	  
	  <div class="techCategoryDiv"> <!--  -->
		<ul id="techCategoryUnorderdList">
			<li id="techCategoryList"><h4 class="techCategoryStyle">Business category: <?php if ($businessClassification) {echo $businessClassification;} else { echo "Not specified yet";} ?></h4></li>
			<?php if ($businessClassification !== "Artisan") {?>
				<li style="vehicleCategoryList"><h4 class="techCategoryStyle">Vehicle category: <?php if ($vehicleClassification) {echo $vehicleClassification; } else {echo "Not specified yet";}?></h4></li>
			<?php } ?>
		</ul>
		<!-- <h4 class="autoSpecialtyStyle">Business category: <?php // echo $businessCategory->selected_business_categoty(); ?></h4> -->
	    <!-- <h4 class="autoSpecialtyStyle">Vehicle category: <?php // echo $vehicleCategory->selected_vehicle_categoty(); ?></h4> -->
	  </div>
	  
	  <!--  -->
      <div class="techCategoryDiv">
	    <!-- Check if it's an artisan, a technician or spare part seller to know the type of heading to display -->
	    <h4 class="autoSpecialtyStyle">
		<?php if ($businessClassification === "Technician") {
				echo "Technical Services:";
			  } elseif ($businessClassification === "Artisan") {
				echo "Artisan services:";
			  } else {
				echo "Spare parts:";
			  } 
		?>
		</h4>
		<!-- Check if it a technician or spare part seller to know if to display technical services or spare parts -->
		<?php if ($businessClassification === "Artisan") { ?>
			<ul class="fa-ul">
				<?php foreach ($artisanServices as $key => $value) { ?>
					<li id="artisanServiceList"><span class="fa-li fa-lg" style="padding-left:40px;"><i class="fas fa-wrench"></i></span> <p style="margin:0px; padding-left:40px;"><?php echo $key; ?></p> 
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
		<?php } else { ?>  
			<ul class="fa-ul">
				<?php foreach ($spareParts as $key => $value) { ?>
					<li id="sparePartList"><span class="fa-li fa-lg" style="padding-left:40px;"><i class="fas fa-cogs"></i></span> <p style="margin:0px; padding-left:40px;"><?php echo $key; ?></p> 
				<?php } ?>
				</li>
			</ul>
		<?php } ?>
	  </div>
	  
	  <!--  -->
	  <?php if ($businessClassification !== "Artisan") { ?>
	    <div class="techCategoryDiv">
			<h4 class="autoSpecialtyStyle">
				<?php 
					if ($vehicleClassification === "Cars") {
						echo "Car Specialty";
					} elseif ($vehicleClassification === "Buses") {
						echo "Bus Specialty";
					} else {  
						echo "Truck Specialty";
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
		<ul class="fa-ul">
			<!-- Address -->
			<li id="addressList"><span class="fa-li fa-lg" style="padding-left:40px;"><i class="far fa-address-card"></i></span><p class="techDetails"><?php if ($full_address) {echo $full_address;} else {echo "No available address temporarily";} ?></p></li>
			
			<!-- Phone -->
			<li id="phoneList"><span class="fa-li fa-lg"><i class="fas fa-phone"></i></span><p class="techDetails"><?php echo $customerNumber; ?></p></li>
			
			<!-- Email -->
			<li id="emailList"><span class="fa-li fa-lg"><i class="far fa-envelope"></i></span><p class="techDetails"><?php echo $customerEmail; ?></p></li>
		</ul>
      </div>
    </div>
    <div class="photoGalleryDiv">
		<div class="photoContainer" >
		  <?php $i = 1; ?>
		  <?php foreach($customerPictures as $photo): ?>
			<div class="displayPicture"> 
				<img id="<?php echo "cus-ad-image".$i; ?>" name="cus-ad-image" class="cus-ad-image" src="<?php echo "../".$photo->image_path(); ?>" width="200" height="200" alt="customer ad image" />
				<!-- This caption is not displayed until the image is clicked and enlarged. Thus the imageCaption display is set to none -->
				<p class="imageCaption" id="<?php echo "imageCaption".$i; ?>"><?php echo $photo->caption; ?></p>
			</div>
			<?php $i = $i + 1; ?>
		  <?php endforeach;?>
		</div>
    </div>
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
							<?php echo htmlentities($comment->author); ?> commented:
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
					<?php $replies = User_Reply::find_replies_on_comment($_SESSION["viewedCusId"], $comment->id);
					$j = 0; ?>
					<?php foreach($replies as $reply): ?>
						
						<div id="replyComment<?php echo $i; echo $j; ?>" class="replyComment">
							<div class="authorDate" id="replyAuthorDate<?php echo $i; echo $j; ?>">
								<div class="author" id="replyAuthor<?php echo $i; echo $j; ?>">
									<?php echo $reply->author; ?> replied:
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
				<!-- <form action="customerHomePage.php?id=<?php echo urlencode($customerID); ?>" method="post"> -->
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
					<p>If you want to leave a comment, please register.</p>
			    <?php } ?>
				<!-- <a style="font-size: 0.8em;" href="">Delete Account</a> -->
			</div>
		</div>
		
		<div id="setAppointment" class="setAppoitment">
			<?php echo $message; ?><br/>
			<?php echo $validate->form_errors($validate->errors); ?>
			
			<?php
				if ($session->is_user_logged_in() || $session->is_customer_logged_in()) {
				?>
					<form id="makeAppoitmentForm" name="makeAppoitmentForm" method="post" action="" >
						<!-- <fieldset class="makeAppoitment"><legend>Schedule an appointment</legend></fieldset> -->
						<h4 id="schAptTitle">Schedule an appointment</h4>
					    <p> 
							<?php echo displayCustomerDays(); ?>
					    </p>
						<p id="checkboxOfDays"></p>
						<!-- This section of the form could be shifted into the function that dynamically displays the form when a date for appointment is selected. -->
						<p>
							<label for="userNote">Please provide a description for your appointment</label>
							<textarea name="appointment_message" id="appointment_message" rows="5"></textarea>
						</p>
						<p>
							<input type="submit" name="submit_appoitment" id="submit_appoitment" class="btnStyle1" value="Submit" />
						</p>
					  <!-- End of section to be cut. -->
						
					</form>
				<?php	
				} else {
				?>
					<p> Please register to schedule an appointment. </p>
				<?php
				}
			?>
		</div>
		
	</div>
	
	<!-- <div class="actions" style="font-size: 0.8em;"> -->
		<!-- <a href="">Delete Account</a> -->
	<!-- </div> -->
	
	<!-- This display the modal to project a larger image when clicked-->
	<div class="bg-modal" >
		<div class="modal-content" >
			<div class="close-btn" >+</div>
			<img id="enlargedAdImg" src="" alt="Enlarged selected photo of entrepreneur" > 
			<div id="modalPhotoBtnDiv">
				<p id="imgCaptionModal" ></p>
			</div>
		</div>
	</div>
    
	<!-- Scroll to top widget -->
	<div class="to-top-div" >
		<a class="to-top" href="#mainServicePage"> ^ </a>
	</div>
  </div> <!-- End of Main Section -->
  
  <!-- Display the footer section -->
  <?php include_layout_template('public_footer.php'); ?>
</div>
<script type="text/javascript">
	var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
	/* var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1"); */
</script>
<!-- Javascript for selected days in the option tags -->
<script type="text/javascript" src="../javascripts/genericJSs.js"></script>
<script type="text/javascript" src="../javascripts/customerHomePageJSScripts.js"></script>
</body>
</html>
