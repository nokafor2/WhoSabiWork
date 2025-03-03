<?php 
/**
 *  An example CORS-compliant method.  It will allow any GET, POST, or OPTIONS requests from any
 *  origin.
 *
 *  In a production environment, you probably want to be more restrictive, but this gives you
 *  the general idea of what is involved.  For the nitty-gritty low-down, read:
 *
 *  - https://developer.mozilla.org/en/HTTP_access_control
 *  - https://fetch.spec.whatwg.org/#http-cors-protocol
 *
 */
function cors() {
    
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
    
    echo "You have CORS!";
}

// cors();

/*
require("../../includes/twilio-php-main/src/Twilio/autoload.php");

$twilioAccountSid = "AC381b585115005769bf5f841d5a96d9f5";
$twilioAccountToken = "4c05e6369bb7586a8259af1b791f96aa";

$fromNumber = "+17343388511";
$toNumber = "+2348057368560";

$message = "Trying out Twilio";

$client = new Twilio\Rest\Client($twilioAccountSid, $twilioAccountToken);

$client->messages->create($toNumber, [
	'from' => $fromNumber,
	'body' => $message
]);

echo "Message sent to twilio";
*/

// Require the bundled autoload file - the path may need to change
// based on where you downloaded and unzipped the SDK
require("../../includes/twilio-php-main/src/Twilio/autoload.php");

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

$twilioAccountSid = "AC381b585115005769bf5f841d5a96d9f5";
$twilioAccountToken = "4c05e6369bb7586a8259af1b791f96aa";

$fromNumber = "+17343388511";
$toNumber = "+2348057368560";

$message = "Trying out Twilio";

$client = new Client($twilioAccountSid, $twilioAccountToken);

// Use the client to do fun stuff like send text messages!
$client->messages->create(
    // the number you'd like to send the message to
    $toNumber,
    [
        // A Twilio phone number you purchased at twilio.com/console
        'from' => $fromNumber,
        // the body of the text message you'd like to send
        'body' => $message
    ]
);

echo "Message sent to twilio";

?>