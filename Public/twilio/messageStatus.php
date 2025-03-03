<?php

// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md
require("../../includes/twilio-php-main/src/Twilio/autoload.php");
// require_once '/path/to/vendor/autoload.php';

use Twilio\Rest\Client;

// Find your Account Sid and Auth Token at twilio.com/console
// and set the environment variables. See http://twil.io/secure
$sid = "AC381b585115005769bf5f841d5a96d9f5";
$token = "4c05e6369bb7586a8259af1b791f96aa";
$twilio = new Client($sid, $token);

$message = $twilio->messages->create("+2348057368560", // to
  [
     "body" => "Message from localhost.",
     "from" => "+17343388511",
     "statusCallback" => "http://postb.in/1234abcd"
  ]
);

print($message->sid);