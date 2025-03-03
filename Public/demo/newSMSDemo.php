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
/* 
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

cors();
*/

/*
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");
*/

/*
// Allow from any origin
if(isset($_SERVER["HTTP_ORIGIN"]))
{
    // You can decide if the origin in $_SERVER['HTTP_ORIGIN'] is something you want to allow, or as we do here, just allow all
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
}
else
{
    //No HTTP_ORIGIN set, so we allow any. You can disallow if needed here
    header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 600");    // cache for 10 minutes

if($_SERVER["REQUEST_METHOD"] == "OPTIONS")
{
    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); //Make sure you remove those you do not want to support

    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    //Just exit with 200 OK with the above headers for OPTIONS method
    exit(0);
}
//From here, handle the request as it is ok
*/

/*
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
*/


$phoneNumber = urlencode('2348057368560');
$message = urlencode('Message testing from WhoSabiWork');
$sender = 'WhoSabiWork';
// $url = "http://panel.xwireless.net/API/WebSMS/Http/v1.0a/index.php?username=".$username."&password=".$password."&sender=".$sender."&to=".$phoneNumber."&message=".$message."&reqid=".$reqid."&format=".$format."&route_id=".$route_id."&unique=".$unique."&sendondate=".$send_on_date."&callback=?";

//$apiKey = "CDqdYvVDF7P7ILTorVo+zKjmwYYvRIs6nMUbJnJ/uDg=";
$apiKey = "CDqdYvVDF7P7lLTorVo%2BzKjmwYYvRls6nMUbJnJ%2FuDg%3D";
$clientId = "7bf515f1-b9a0-43bb-a25d-e1d875def7bf";
// $senderId = "IMPORTANT";
$senderId = "whoSabiWork";
// send message
// This is used when testing from local server
$url = "https://secure.xwireless.net/api/v2/SendSMS?ApiKey=".$apiKey."&ClientId=".$clientId."&SenderId=".$senderId."&Message=".$message."&MobileNumbers=".$phoneNumber."&callback=?";

///$url = "http://45.77.146.255:6005/api/v2/SendSMS?SenderId=".$senderId."&Is_Unicode=false&Is_Flash=false&Message=".$message."&MobileNumbers=".$phoneNumber."&ApiKey=".$apiKey."&ClientId=".$clientId."&callback=?";

// $url = "http://api.worldbank.org/countries?per_page=10&incomeLevel=LIC";

// check balance
// $url = "https://secure.xwireless.net/api/v2/Balance?ApiKey=".$apiKey."&ClientId=".$clientId."&callback=?";
// $url = "http://45.77.146.255:6005/api/v2/Balance?ApiKey=".$apiKey."&ClientId=".$clientId."&callback=?";

// Google Test API
// $url = "https://searchconsole.googleapis.com/$discovery/rest?version=v1";
// $url = "https://searchconsole.googleapis.com/v1/urlTestingTools/mobileFriendlyTest:run";

$json = "";
$json = @file_get_contents($url);
print_r($json);
echo "<br/>";
$xwirelessData = json_decode($json, true);
echo "<pre>";
print_r($xwirelessData);
echo "</pre>";

echo "Message status: ";

echo "<pre>";
print_r($xwirelessData['Data'][0]['MessageErrorDescription']);
echo "</pre>";
/*
$_SESSION['xwirelessData'] = $xwirelessData = json_decode($json, true);
print_r($xwirelessData);
print_r($_SESSION);
*/

?>
