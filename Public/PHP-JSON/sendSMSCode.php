<?php
require_once("../../includes/initialize.php");

$json;
// Check if the request is post and if it is from same web page.	
	
	// Check that only allowed parameters is passed into the form
	// $post_params = allowed_post_params(['phoneNumber', 'smsCode']);
	$phoneNumber = '08142924903';
	$smsCode = '';
	
	// print_r($_POST);
	if (isset($phoneNumber)) {
		// $phoneNumber = $post_params['phoneNumber'];
		// $smsCode = 'P4N3eQ';
		$smsCode = randStrGen(6);
		$username = 'nokafor';
		$password = '6QpL4X96';
		$sender = 'WhoSabiWork';
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
	// echo $json;
	print_r(json_decode($json, true));
	$xwirelessData = json_decode($json, true);
	echo "<br/> <br/>";
	echo "The random code generated is: ".$smsCode;
	echo "<br/> <br/>";
	echo "The messeage id is:".$xwirelessData['msg_id'];
?>