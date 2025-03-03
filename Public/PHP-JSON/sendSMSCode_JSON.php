<?php
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');

$json;
if(request_is_post() && request_is_same_domain()) {
	// Check if the request is post and if it is from same web page.	
	
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['phoneNumber', 'smsCode']);
	
	// print_r($_POST);
	if (isset($post_params['phoneNumber'], $post_params['smsCode'])) {
		$phoneNumber = $post_params['phoneNumber'];
		$smsCode = $post_params['smsCode'];
		$username = 'nokafor';
		$password = '6QpL4X96';
		$sender = 'WhoSabiWork.com';
		$message = 'Your phone number has been confirmed, please use this code '.$smsCode.' to complete your account registration.';
		$reqid = 1;
		$format = 'json';
		$route_id = 3;
		$unique = 1;
		$send_on_date = str_replace('+00:00', '', gmdate('c', strtotime(date('Y-m-d H:i:s')) ) );
		// echo "The number passed in before concatenation is: ".$phoneNumber;
		// Eliminate the first zero in front of the telephone number if it exists
		if(substr($phoneNumber,0,1)=="0"){
			$phoneNumber	=	"+234".substr($phoneNumber,1);
		}
		// echo "The concatenated number passed in is: ".$phoneNumber;
		$phoneNumber	=	urlencode($phoneNumber);
		// echo "The number after encoding is: ".$phoneNumber;
		$message	=	urlencode($message);
		// $sender	=	urlencode($sender);
		
		// ."&callback=?"
		$url = "http://panel.xwireless.net/API/WebSMS/Http/v1.0a/index.php?username=".$username."&password=".$password."&sender=".$sender."&to=".$phoneNumber."&message=".$message."&reqid=".$reqid."&format=".$format."&route_id=".$route_id."&unique=".$unique."&sendondate=".$send_on_date."&callback=?";
		$json = "";
		$json = @file_get_contents($url);
		// print_r($json);
	} else {
		$json = "{'error' : 'An error occurred sending the data from JSON'}";
	}
echo $json;
}

?>