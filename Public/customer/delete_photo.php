<?php require_once("../../includes/initialize.php"); ?>
<?php // if (!$session->is_customer_logged_in()) { redirect_to("../loginPage.php"); } ?>
<?php
	print_r($_GET);
	// the request_is_get() fxn will ensure that a get request was sent from the webpage
	if(request_is_get()) {
		// This variable contains the filtered values from the GET_global variable. It is an array
		$get_params = allowed_get_params(['cusId', 'photoId']);
		
		// Eliminate HTML tags embedded in the URL inputs
		foreach($get_params as $param) {
			// run htmlentities check on the parameters
			if(isset($get_params[$param])) {
				// run htmlentities check on the parameters
				$get_params[$param] = h2($param);
			} 
		}
		
		
		// must have an ID, get the ID from the $get_params global variable which will be used to delete the user
		if(empty($get_params['cusId']) && empty($get_params['photoId'])) {
			$session->message("Unable to be deleted. No Customer and Photo ID was provided for deleting.");
			redirect_to('customerEditPage2.php?id={$session->customer_id}');
		}

		$photoHandle = new Photograph();
		// Check if the photo was found and if it was deactivated
		if($photoHandle->hidePhoto($get_params['photoId'])) {
			$session->message("The image has been deleted.");
			redirect_to('customerEditPage2.php?id='.$get_params['cusId']);
		} else {
			$session->message("The image could not be deleted.");
			redirect_to('customerEditPage2.php?id='.$get_params['cusId']);
		}
	}
?>

<?php // Close the database when done deleting
if(isset($database)) { $database->close_connection(); } ?>
