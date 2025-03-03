<?php 
	require_once("../../includes/initialize.php");

	if ($_POST['action']) {
		$id = $_POST['idBox'];
		$rate = $_POST['rate'];
	}

	// Initialize the customer_rating class
	$customer_rating = new Customer_Rating();
	$customer_rating->customers_id = $id;
	$customer_rating->rating = $rate;
	// Determine if its a user or customer rating and get their id
	if ( $session->is_user_logged_in()) {
		$customer_rating->rated_by_user = $session->user_id;
		
	} elseif ( $session->is_customer_logged_in()) {
		$customer_rating->rated_by_customer = $session->customer_id;
	}

	// Get the ip address of the user rating
	$customer_rating->ip_address = GetUserIP();
	$customer_rating->date_rated = current_Date_Time();

	// Save the record in the database
	$customer_rating->create();
?>