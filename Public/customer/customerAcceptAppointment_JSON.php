<?php
require_once("../../includes/initialize.php");
// This JSON/PHP is called by: 

// Trick the browser, to think that it is a JSON file using PHP 
// The content-type is set to application/json.
header("Content-Type: application/json");

// Javascript caller: appointmentDecision.js 
// Function: appointmentAccepted()

$jsonData = array(
	'success' => false,
	'result' => ""
);

if(request_is_post() && request_is_same_domain()) {
	// Check if the request is post and if it is from same web page.
	
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['id']);
	
	if (isset($post_params['id'])) {
		$id = $post_params['id'];
		
		// $customers_appointment = new Customers_Appointment();
		$customersAppointment = Customers_Appointment::find_by_id($id);
		// print_r($customersAppointment);
		
		$customersAppointment->customer_decision = 'accepted';
		$customersAppointment->date_edited = current_Date_Time();
		
		$decisionUpdated = $customersAppointment->update();

		// Send an email and text message that the appointment has been accepted.
		// receiver's number
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
			$receiverFullname = $userObj->full_name();
		} else {
			$appointerCustomerId = $customersAppointment->scheduled_customer;
			$customerAppointerObj = Customer::find_by_id($appointerCustomerId);
			$receiverEmail = $customerAppointerObj->customer_email;
			$receiverFullname = $customerAppointerObj->full_name();
		}

		$SMSoutcome1 = ""; $SMSoutcome2 = "";
		$emailOutcome1 = ""; $emailOutcome2 = "";
		// Send message to appointer (Receiver)
		if (isset($phoneNumberReceiver)) {
			$SMSmessage = 'Your appointment with '.$customerName.' on '.$appointmentDate.' at '.$appointmentHours.' has been accepted.';
			$SMSoutcome = sendSMSCode($phoneNumberReceiver, $SMSmessage);
			$SMSoutcome1 = sendSMSOutcome2($SMSoutcome);
		}
		
		// Send Email to the appointer (Receiver)
		if (isset($receiverEmail)) {
			$to = $receiverEmail;
			$title = 'Your Appointment has been Accepted';
			$body = '<img src="cid:whoSabiWorkLogo">';
			$body .= '<p>Your appointment with '.$customerName.' on '.$appointmentDate.' at '.$appointmentHours.' has been accepted.</p>'.PHP_EOL;
			$body .= '<p>Objective: "'.$appointmentMessage.'"</p>'.PHP_EOL;
			$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork', $to, $title, $body);
			$emailOutcome1 = sendEmailOutcome2($outcome);
		}

		// Send message to customer (Sender)
		if (isset($customerNumber)) {
			$SMSmessage = 'You have accepted an appointment with '.$appointmentOwner.' on '.$appointmentDate.' at '.$appointmentHours;
			$SMSoutcome = sendSMSCode($customerNumber, $SMSmessage);
			$SMSoutcome2 = sendSMSOutcome2($SMSoutcome);
		}

		// Send Email to the technician/artisan (Sender)
		if (isset($emailSender)) {
			$to = $emailSender;
			$title = 'You have Accepted an Appointment';
			$body = '<img src="cid:whoSabiWorkLogo">';
			$body .= '<p>You have accepted an appointment with '.$appointmentOwner.' on '.$appointmentDate.' at '.$appointmentHours.'</p>'.PHP_EOL;
			$body .= '<p>Customer Objective: "'.$appointmentMessage.'"</p>'.PHP_EOL;
			$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork', $to, $title, $body);
			$emailOutcome2 =  sendEmailOutcome2($outcome);
		}
		
		if ($decisionUpdated) {
			$jsonData["success"] = true;
			$jsonData["SMSoutcome"] = $SMSoutcome2;
			$jsonData["emailOutcome"] = $emailOutcome2;
		} else {
			$jsonData["success"] = false;
		}
	}
}
echo json_encode($jsonData);
?>