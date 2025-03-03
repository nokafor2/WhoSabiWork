<?php
	require_once("../../includes/initialize.php");

	header('Content-type: text/javascript');
	// This will be set to specify as a default output upon request from the JSON file
	// An array is actually created here
	$json = array(
		'success' => false,
		'result' => false
	);
	
	if (request_is_post() && request_is_same_domain()) {
		global $session;
		if (isset($_POST['customerId']) && $_POST['action'] === 'increase') {
			$customerId = $_POST['customerId'];
			$advocate = new Advocate();
			$advocate->customers_id = $customerId;
			if ($session->is_user_logged_in()) {
				$advocate->user_advocator = $session->user_id;
			} elseif ($session->is_customer_logged_in()) {
				$advocate->customer_advocator = $session->customer_id;
			}
			$advocate->date_created = current_Date_Time();

			// save record in the database
			if ($advocate->create()) {
				$json["success"] = true;
				$json["result"] = "advocator saved";
				$json["numberOfAdvocators"] = Advocate::count_advocators($customerId);
			} else {
				$json["success"] = false;
				$json["result"] = "Error saving record.";
			}
		} elseif (isset($_POST['customerId']) && $_POST['action'] === 'decrease') {
			$customerId = $_POST['customerId'];
			if ($session->is_user_logged_in()) {
				$foundRecord =  Advocate::find_user_advocator($customerId, $session->user_id);
			} elseif ($session->is_customer_logged_in()) {
				$foundRecord =  Advocate::find_customer_advocator($customerId, $session->customer_id);
			}

			// Delete record
			$recordId = $foundRecord->id;
			if ($foundRecord->delete()) {
				$json["success"] = true;
				$json["result"] = "advocator deleted";
				$json["numberOfAdvocators"] = Advocate::count_advocators($customerId);
			} else {
				$json["success"] = false;
				$json["result"] = "Error deleting record";
			}
		} else {
			$json["success"] = false;
			$json["result"] = "Data was not sent.";
		}
	} else {
		$json["success"] = false;
		$json["result"] = "Not a valid post request or domain request";
	}
	echo json_encode($json);

?>