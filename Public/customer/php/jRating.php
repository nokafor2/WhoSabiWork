<?php
require_once("../../../includes/initialize.php");
$aResponse['error'] = false;
$aResponse['message'] = '';

// ONLY FOR THE DEMO, YOU CAN REMOVE THIS VAR
	$aResponse['server'] = ''; 
// END ONLY FOR DEMO
	
	
if(isset($_POST['action']))
{
	if(htmlentities($_POST['action'], ENT_QUOTES, 'UTF-8') == 'rating')
	{
		/*
		* vars
		*/
		// $id = intval($_POST['idBox']);
		// $rate = floatval($_POST['rate']);
		$id = ($_POST['idBox']);
		$rate = ($_POST['rate']);
		
		// YOUR MYSQL REQUEST HERE or other thing :)
		/*
		*
		*/
		
		if (in_array($rate, [1, 2, 3, 4, 5])) {
			global $database;
			
			$rate_customer = new Customer_Rating();
			$rate_customer->customers_id = $_SESSION["link_id"];
			$rate_customer->rating = $rate;
			if ($session->is_user_logged_in()) {
				$rate_customer->rated_by_user = $session->user_id;
				$rate_customer->rated_by_customer = NULL;
			} else {
				$rate_customer->rated_by_customer = $session->customer_id;
				$rate_customer->rated_by_user = NULL;
			}
			$rate_customer->ip_address = GetUserIP();
			$rate_customer->date_rated = $rate_customer->current_Date_Time();
			
			if ($rate_customer->save()) {
				"Your rating was successfully saved.";
				$success = true;
			} else {
				"Your rating was not successfully saved.";
				$success = false;
			}
		}
		
		// if request successful
		// $success = true;
		// else $success = false;
		
		
		// json datas send to the js file
		if($success)
		{
			$aResponse['message'] = 'Your rate has been successfully recorded. Thanks for your rate :)';
			
			// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
				$aResponse['server'] = '<strong>Success answer :</strong> Success : Your rate has been recorded. Thanks for your rate :)<br />';
				$aResponse['server'] .= '<strong>Rate received :</strong> '.$rate.'<br />';
				$aResponse['server'] .= '<strong>ID to update :</strong> '.$id;
			// END ONLY FOR DEMO
			
			echo json_encode($aResponse);
		}
		else
		{
			$aResponse['error'] = true;
			$aResponse['message'] = 'An error occured during the request. Please retry';
			
			// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
				$aResponse['server'] = '<strong>ERROR :</strong> Your error if the request crash !';
			// END ONLY FOR DEMO
			
			
			echo json_encode($aResponse);
		}
	}
	else
	{
		$aResponse['error'] = true;
		$aResponse['message'] = '"action" post data not equal to \'rating\'';
		
		// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
			$aResponse['server'] = '<strong>ERROR :</strong> "action" post data not equal to \'rating\'';
		// END ONLY FOR DEMO
			
		
		echo json_encode($aResponse);
	}
}
else
{
	$aResponse['error'] = true;
	$aResponse['message'] = '$_POST[\'action\'] not found';
	
	// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
		$aResponse['server'] = '<strong>ERROR :</strong> $_POST[\'action\'] not found';
	// END ONLY FOR DEMO
	
	
	echo json_encode($aResponse);
}