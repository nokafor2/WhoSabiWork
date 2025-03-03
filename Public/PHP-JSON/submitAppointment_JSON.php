<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');
// This will be set to specify as a default output upon request from the JSON file
// An array is actually created here
// Response sent from adminPageJSs.js
$jsonData = array(
	'success' => false,
	'result' => ""
);

// Check if the request is post and if it is from same web page.
// Request sent from cropImage.js
if (request_is_post() && request_is_same_domain()) {
	global $session;
		
	$post_params = allowed_post_params(['customerId', 'day', 'hours', 'message']);
	
	if (isset($post_params['customerId'], $post_params['day'], $post_params['message']) && $post_params['hours'] !== null) {

		// Extract the variables from the post
		$customerID = $post_params['customerId'];
		$day = $post_params['day'];
		$hours = $post_params['hours'];
		$message = $post_params['message'];

		$cus_appointment = new Customers_Appointment();
		
		$cus_appointment->customers_id = $customerID;
		$cus_appointment->appointment_date = $cus_appointment->makeDateForDay($day);
		$customerPageDetails = Customer::find_by_id($customerID);
		$cus_appointment->customer_name = $customerPageDetails->full_name();
		$cus_appointment->customer_number = $customerPageDetails->phone_number;
		$customerAddressObj = Address::find_by_customerId($customerID);
		$customerAddress = $customerAddressObj->full_address();
		$customerBusinessCategory = Business_Category::find_by_customerId($customerID);
		$customerEmail = $customerPageDetails->customer_email;
		
		// Determine what business category the customer is
		if ($customerBusinessCategory->seller) {
			$sellerObj = new Seller();
			$sellerProducts = $sellerObj->selected_choices($customerID);
			// get the seller products saved in the keys of the array
			$sellerProducts = array_keys($sellerProducts);
			if (is_array($sellerProducts)) {
				// convert the array into a string
				$skills = implode(", ", $sellerProducts);
			} else {
				$skills = $sellerProducts;
			}
		} elseif ($customerBusinessCategory->artisan) {
			$artisanObj = new Artisan();
			$artisanSkills = $artisanObj->selected_choices($customerID);
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
			$mechanicSkills = $mechanicObj->selected_choices($customerID);
			$mechanicSkills = array_keys($mechanicSkills);
			if (is_array($mechanicSkills)) {
				$skills = implode(", ", $mechanicSkills);
			} else {
				$skills = $mechanicSkills;
			}
		} elseif ($customerBusinessCategory->spare_part_seller) {
			$sparePartObj = new Spare_Part();
			$spareParts = $sparePartObj->selected_choices($customerID);
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
		
		$cus_appointment->appointment_message = $message;
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

					$SMSphoneNumber = strval($cus_appointment->customer_number);
					// Send SMS to the customer (Receiver)
					// If there was an error sending text send a session message.		
					$SMSoutcome = sendSMSCode($SMSphoneNumber, $SMSmessage);
					// $jsonData["SMSoutcome"] = sendSMSOutcome2($SMSoutcome);
				}

				// Check if an email is available
				if (isset($customerEmail)) {
					$to = $customerEmail;
					$title='Appoointment Schedule from Customer';
					$body = makeReceiverNotificationEmail($_SESSION['user_full_name'], $userDetails->phone_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message);
					// Send Email to the customer (Receiver)
					$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork', $to, $title, $body);
					// sendEmailOutcome2($outcome);
				}

				// Check if a phone number is available
				// Send SMS to the user (with a user account) creating the appointment
				if (isset($userDetails->phone_number)) {
					// Erase the message and number variables
					$SMSphoneNumber = '';
					$SMSmessage = '';

					// send SMS and email to customer creating the appointment
					$SMSmessage = makeSenderNotificationSMS($cus_appointment->customer_name, $skills, $cus_appointment->customer_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message, $customerAddress);

					$SMSphoneNumber = strval($userDetails->phone_number);
					// Send SMS to user
					$SMSoutcome = sendSMSCode($SMSphoneNumber, $SMSmessage);
					$jsonData["SMSoutcome"] = sendSMSOutcome2($SMSoutcome);
				}

				// Check if an email is available
				if (isset($schedulerEmail)) {
					// Send email to user
					$to = $schedulerEmail;
					$title='Your Appoointment Schedule';
					$body = makeSenderNotificationEmail($cus_appointment->customer_name, $skills, $cus_appointment->customer_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message, $customerAddress);
					// Send Email to the user (Sender)
					$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork', $to, $title, $body);
					$jsonData["emailOutcome"] = sendEmailOutcome2($outcome);
				}
			} elseif (($session->is_customer_logged_in())) {
				// send SMS and email to user (having a customer account) creating the appointment
				// Check if a phone number is available
				if (isset($cus_appointment->customer_number)) {
					$SMSmessage = makeReceiverNotificationSMS($_SESSION['customer_full_name'], $customerDetails->phone_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message);

					// This is the number of the customer (technician/artisan/spare part seller) that an appoitment is created with
					$SMSphoneNumber = strval($cus_appointment->customer_number);

					// Send SMS to the customer (Receiver)
					// If there was an error sending text, save a session message.		
					$SMSoutcome = sendSMSCode($SMSphoneNumber, $SMSmessage);
					// sendSMSOutcome2($SMSoutcome);
				}

				// Check if an email is available
				// Send an email to the customer (technician/artisan)
				if (isset($customerEmail)) {
					$to = $customerEmail;
					$title='Appoointment Schedule from Customer';
					$body = makeReceiverNotificationEmail($_SESSION['customer_full_name'], $customerDetails->phone_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message);
					// Send Email to the customer (Receiver)
					$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork', $to, $title, $body);
					// sendEmailOutcome2($outcome);
				}

				// Check if a phone number is available
				// Send an SMS to the person (with a customer account) scheduling an appointment 
				if (isset($customerDetails->phone_number)) {
					// Erase the message and number variables
					$SMSphoneNumber = '';
					$SMSmessage = '';

					// send SMS and email to customer creating the appointment
					$SMSmessage = makeSenderNotificationSMS($cus_appointment->customer_name, $skills, $cus_appointment->customer_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message, $customerAddress);

					$SMSphoneNumber = strval($customerDetails->phone_number);
					// Send SMS to user
					$SMSoutcome = sendSMSCode($SMSphoneNumber, $SMSmessage);
					$jsonData["SMSoutcome"] = sendSMSOutcome2($SMSoutcome);
				}

				// Check if an email is available
				// Send an email to the user (with a customer account) scheduling the account
				if (isset($schedulerEmail)) {
					// Send email to user
					$to = $schedulerEmail;
					$title='Your Appoointment Schedule';
					$body = makeSenderNotificationEmail($cus_appointment->customer_name, $skills, $cus_appointment->customer_number, $cus_appointment->appointment_date, $cus_appointment->hours, $cus_appointment->appointment_message, $customerAddress);
					// Send Email to the user (Sender)
					$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork', $to, $title, $body);
					$jsonData["emailOutcome"] = sendEmailOutcome2($outcome);
				}
			}

			$jsonData["success"] = true;
			$jsonData["result"] = "Your appointment was successfully scheduled.";
		} else {
			$jsonData["success"] = false;
			$jsonData["result"] = "An error occurred while saving.";
		}
				
	} else {
		$jsonData["success"] = false;
		$jsonData["result"] = "error:'An error occurred sending the data from JSON'";
	}
} else {
	$jsonData["success"] = false;
	$jsonData["result"] = "Not a valid post request or domain request";
}
echo json_encode($jsonData);

?>