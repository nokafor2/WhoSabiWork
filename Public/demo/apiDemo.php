<?php
require_once("../../includes/initialize.php");

$phoneNumber = '2348057368560';
$message = urlencode('Message testing from WhoSabiWork');
$sender = 'WhoSabiWork';
$apiKey = "CDqdYvVDF7P7lLTorVo%2BzKjmwYYvRls6nMUbJnJ%2FuDg%3D";
$clientId = "7bf515f1-b9a0-43bb-a25d-e1d875def7bf";
$senderId = "whoSabiWork";

// Send SMS
$url = "http://45.77.146.255:6005/api/v2/SendSMS?SenderId=".$senderId."&Is_Unicode=false&Is_Flash=false&Message=".$message."&MobileNumbers=".$phoneNumber."&ApiKey=".$apiKey."&ClientId=".$clientId."&callback=?";

// Check balance
// $url = "http://45.77.146.255:6005/api/v2/Balance?ApiKey=".$apiKey."&ClientId=".$clientId;
/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$resp = curl_exec($ch);

if ($e = curl_error($ch)) {
	echo $e;
} else {
	$decoded = json_decode($resp);
	print_r($decoded);
}

curl_close($ch);
*/

if (sendSMSCode($phoneNumber, $message)) {
	echo "Message was sent successfully.";
} else {
	echo "Error occured sending message.";
}

?>