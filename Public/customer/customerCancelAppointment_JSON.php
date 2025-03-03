<?php
require_once("../../includes/initialize.php");

// Trick the browser, to think that it is a JSON file using PHP 
// The content-type is set to application/json.
header("Content-Type: application/json");

// Javascript caller: appointmentDecision.js 
// Function: appointmentCanceled()

if(request_is_post() && request_is_same_domain()) {
	// Check if the request is post and if it is from same web page.
	
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['id', 'cancelingReason', 'userType']);
	
	if (isset($post_params['id'])) {
		$id = $database->escape_value($post_params['id']);
		// $cancelingReason = $database->escape_value($post_params['cancelingReason']);
		$cancelingReason = $post_params['cancelingReason'];
		$userType = $database->escape_value($post_params['userType']);
		// print_r($post_params);
		
		// echo "The reason for canceling is: ".$cancelingReason;
		// Validate the contents from the textarea.
		global $validate;
		// Using the validate_address function, check if a proper message was inputed.
		// The function returns a true or false.
		// If it is true, the content in the textarea failed validation.
		// If it is false, the content in the textarea passed validaiton.
		// echo "The textarea has an input: ".$validate->has_presence($cancelingReason);
		// echo "The message is valid: ".$validate->validate_address($cancelingReason);
		
		if (!$validate->has_presence($cancelingReason)) {
			$validate->errors["presence_error"] = "Please provide a reason for canceling.";
		}

		// Don't get lost, just using the validate address function.
		/*
		if ($validate->validate_address($cancelingReason)) {
			$validate->errors["invalid_input"] = "An invalid character was entered.";
		} */
		
		// $jsonData = '{';
		if (empty($validate->errors)) {
			// $customers_appointment = new Customers_Appointment();
			$customersAppointment = Customers_Appointment::find_by_id($id);
			// print_r($customersAppointment);
			
			$customersAppointment->customer_decision = 'canceled';
			if ($userType === 'customer') {
				$customersAppointment->cus_cancel_message = $cancelingReason;
			} else {
				$customersAppointment->aptr_cancel_message = $cancelingReason;
			}
			$customersAppointment->date_edited = current_Date_Time();
			
			// $customers_appointment->update();
			$decisionUpdated = $customersAppointment->update();

			// $decisionUpdated = TRUE;  // used to troubleshoot
			
			/* $customersAppointment = Customers_Appointment::find_by_id($id);
			print_r($customersAppointment); */

			// Send an email and text message that the appointment has been canceled.
			$phoneNumberReceiver = $customersAppointment->appointer_number;
			$customerId = $customersAppointment->customers_id;
			$customerName = $customersAppointment->customer_name;
			// customer number (sender's number)
			$customerNumber = $customersAppointment->customer_number;
			$appointmentOwner = $customersAppointment->appointment_owner;
			$appointmentDate = date_to_text($customersAppointment->appointment_date);
			$appointmentHours = $customersAppointment->hours;
			$appointmentMessage = $customersAppointment->appointment_message;
			// Use the customers Id to get the email address
			$customerObj = Customer::find_by_id($customerId);
			$emailSender = $customerObj->customer_email;

			// Determine if the appointment was created by a user or a customer
			$scheduledUserId = $customersAppointment->scheduled_user; // Save the variable in order not to get error of comparing an object with a string
			if ($scheduledUserId != 0 || !isset($scheduledUserId)) {
				$appointerUserId = $scheduledUserId;
				$userObj = User::find_by_id($appointerUserId);
				$receiverEmail = $userObj->user_email;
			} else {
				$appointerCustomerId = $customersAppointment->scheduled_customer;
				$customerAppointerObj = Customer::find_by_id($appointerCustomerId);
				$receiverEmail = $customerAppointerObj->customer_email;
			}

			$SMSoutcome1 = ""; $SMSoutcome2 = "";
			$emailOutcome1 = ""; $emailOutcome2 = "";
			// Send message to appointer (Receiver)
			if (isset($phoneNumberReceiver)) {
				$SMSmessage = 'Your appointment with '.$customerName.' on '.$appointmentDate.' at '.$appointmentHours.' has been canceled.'.PHP_EOL.'Reason: '.$cancelingReason;
				$outcome = sendSMSCode($phoneNumberReceiver, $SMSmessage);
				$SMSoutcome1 = sendSMSOutcome2($outcome);
			}
			
			// Send Email to the appointer (Receiver)
			if (isset($receiverEmail)) {
				$to = $receiverEmail;
				$title = 'Your Appointment has been canceled';
				$body = '<img src="cid:whoSabiWorkLogo">';
				$body .= '<p>Your appointment with '.$customerName.' on '.$appointmentDate.' at '.$appointmentHours.' has been canceled.</p>'.PHP_EOL;
				$body .= '<p>Reason: "'.$cancelingReason.'"</p>';
				$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork', $to, $title, $body);
				$emailOutcome1 = sendEmailOutcome2($outcome);
			}

			// Send message to artisan/technician/spare part seller (Sender)
			if (isset($customerNumber)) {
				$SMSmessage = 'You have canceled your appointment with '.$appointmentOwner.' on '.$appointmentDate.' at '.$appointmentHours.PHP_EOL.'Reason: '.$cancelingReason;
				$outcome = sendSMSCode($customerNumber, $SMSmessage);
				$SMSoutcome2 = sendSMSOutcome2($outcome);
			}
			
			// Send Email to the technician/artisan (Sender)
			if (isset($emailSender)) {
				$to = $emailSender;
				$title = 'Your have canceled an Appointment.';
				$body = '<img src="cid:whoSabiWorkLogo">';
				$body .= '<p>You canceled an appointment with '.$appointmentOwner.' on '.$appointmentDate.' at '.$appointmentHours.'</p>'.PHP_EOL;
				$body .= '<p>Reason: "'.$cancelingReason.'"</p>';
				$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork', $to, $title, $body);
				$emailOutcome2 = sendEmailOutcome2($outcome);
			}
			/*
			if ($decisionUpdated) {
				$jsonData .= '"record":{"success":true}';
			} else {
				$jsonData .= '"record":{"failed":true}';
			} */
			if ($decisionUpdated) {
				$jsonData["success"] = true;
				$jsonData["SMSoutcome"] = $SMSoutcome2;
				$jsonData["emailOutcome"] = $emailOutcome2;
			} else { 
				$jsonData["failed"] = true;
			}
		} else {
			// Output the validation errors
			/*
			if (isset($validate->errors["presence_error"])) {
				$jsonData .= '"record":{"presence_error":"'.$validate->errors["presence_error"].'"},';
			}
			if (isset($validate->errors["invalid_input"])) {
				$jsonData .= '"record":{"invalid_input":"'.$validate->errors["invalid_input"].'"},';
			}
			$jsonData = chop($jsonData, ","); */

			if (isset($validate->errors["presence_error"])) {
				$jsonData["presence_error"] = $validate->errors["presence_error"];
			}
			if (isset($validate->errors["invalid_input"])) {
				$jsonData["invalid_input"] = $validate->errors["invalid_input"];
			}
		}
		
		// $jsonData .= '}';
		// echo $jsonData;
	}
}
echo json_encode($jsonData);
?>