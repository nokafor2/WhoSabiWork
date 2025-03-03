<?php 
require_once("../../includes/initialize.php");

header('Content-type: text/javascript');

// This will be set to specify as a default output upon request from the JSON file

// Response sent from adminPageJSs.js
// Function: $('#checkCreditBal')

// An array is actually created here
$jsonData = array(
	'success' => false,
);

// Initialize security function
$security = new Security_Functions();
// Check if the request is post and if it is from same web page.
if(request_is_post() && request_is_same_domain()) {
	$apiKey = "CDqdYvVDF7P7lLTorVo%2BzKjmwYYvRls6nMUbJnJ%2FuDg%3D";
	$clientId = "7bf515f1-b9a0-43bb-a25d-e1d875def7bf";

	// url for local host connection (developer mode)
	$url = "http://45.77.146.255:6005/api/v2/Balance?ApiKey=".$apiKey."&ClientId=".$clientId;

	// url for live server internet connection (production mode)
	// $url = "https://secure.xwireless.net/api/v2/Balance?ApiKey=".$apiKey."&ClientId=".$clientId;

	$json = "";
	$json = @file_get_contents($url);

	// convert received xwireless json data to PHP variables
	$xwirelessData = json_decode($json, true);

	// $jsonData["xwirelessData"] = $xwirelessData;
	if ($xwirelessData['ErrorDescription'] === 'Success') {
		$jsonData["success"] = true;
		$jsonData["creditBalance"] = $xwirelessData['Data'][0]['Credits'];		
	} else {
		// This returns an error if there was an communicating with 'xwireless' server
		$jsonData["error"] = "Error communicating to xwireless server.";
	}
} else {
	$jsonData["sameDomainError"] = 'Request from a different domain.';
}
echo json_encode($jsonData);

?>