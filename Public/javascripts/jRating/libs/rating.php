<?php 
	include_once('class.ManageRatings.php');
	
	$init = new ManageRatings;

	// print_r($_POST);
	
	if ($_POST['action']) {
		$id = $_POST['idBox'];
		$rate = $_POST['rate'];
	}
	
	function GetUserIP() {
		$ip;
		if (getenv("HTTP_CLIENT_IP")) {
			$ip = getenv("HTTP_CLIENT_IP");
		} elseif (getenv("HTTP_X_FORWARDED_FOR")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		} elseif (getenv("REMOtE_ADDR")) {
			$ip = getenv("REMOtE_ADDR");
		} else {
			$ip = "UNKNOWN";
		}
		
		return $ip;
	}
	
	$ip_address = GetUserIP();
	$existingData = $init->getItems($id);
	
	foreach ($existingData as $data) {
		// Find out what is the total rating in the database
		$old_total_rating = $data['total_rating'];
		$total_rates = $data['total_rates'];
	}
	
	$current_rating = $old_total_rating + $rate;
	$new_total_rates = $total_rates + 1;
	$new_rating = $current_rating / $new_total_rates;
	
	$insert = $init->insertRatings($id, $new_rating, $current_rating, $new_total_rates, $ip_address);
	
	if ($insert) {
		echo 'success';
	} else {
		echo 'error';
	}
?>