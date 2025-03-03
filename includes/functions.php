<?php

function strip_zeros_from_date( $marked_string="" ) {
  // first remove the marked zeros
  $no_zeros = str_replace('*0', '', $marked_string);
  // then remove any remaining marks
  $cleaned_string = str_replace('*', '', $no_zeros);
  return $cleaned_string;
}

function redirect_to( $location = NULL ) {
  if ($location != NULL) {
    header("Location: {$location}");
    exit;
  }
}

// This function is used to register the current page in the session.
function returnPageTo($pageFrom, $redirectUrl, $customerID) {
	if ($pageFrom === 'customerHomePage.php') {
		// Save this page to the session so it will be used for redirect later
		$_SESSION['returnPageTo'] = '/Public/customer/customerHomePage.php?id='.$customerID;
	} elseif ($pageFrom === 'livePhotosFeed.php') {
		$_SESSION['returnPageTo'] = '/Public/livePhotosFeed.php';
	}

	// link to redirect to first
	return $redirectUrl;
}

function output_message($message=" ") {
  if (!empty($message)) { 
    return "<p class=\"message\" >{$message}</p>";
  } else {
    return "";
  }
}

function display_errors($errors = array()){
	$output = "";
	if (!empty($errors)){
		$output .= "<div class=\"error\">";
		$output .= "Please fix the following errors:";
		$output .= "<ul>";
		foreach ($errors as $key => $error){
			// style=\"float:left\"
			$output .= "<li style=\"float:left; padding-right: 30px;\">{$error}</li>";
		}
		$output .= "</ul>";
		$output .= "</div>";
	}
	return $output;
}

// Displays error message in a modal
function showErrorMessage($message) {
	$output = '';
	$output .= '<div class="messageModal" style="display: flex; z-index: 2100;">
		<div class="messageContainer">
			<div id="messageHead">Message</div>
			<div id="messageContent">'.$message.'</div>
			<button id="closeBtn" class="btnStyle1">Close</button>
		</div>
	</div>';

	echo $output;
}

function displayMessages() {
	global $sessionMessage;
	global $message;
	global $validate;
	global $photo;

	$output = "";
	if (isset($message)) {
		$output .= output_message($message);
		$message = ""; 
	}
	if (isset($sessionMessage)) {
		$output .= output_message($sessionMessage);
	}
	if (isset($_SESSION['emailOutcomeMessage'])) {
		$output .= output_message($_SESSION['emailOutcomeMessage']);	
	}
	if (isset($_SESSION['SMSOutcomeMessage'])) {
		$output .= output_message($_SESSION['SMSOutcomeMessage']);	
	}
	/* 
	Validate errors already takes care of this
	if (isset($photo->errors)) {
		echo display_errors($photo->errors);
	} */
	if (isset($validate->errors)) {
		$output .= $validate->form_errors($validate->errors); 	
	}
	
	unset($_SESSION['emailSuccess'], $_SESSION['emailError'], $_SESSION['emailMessage'], $_SESSION['SMSSuccess'], $_SESSION['SMSMessage'], $_SESSION['message'], $_SESSION['emailOutcomeMessage'], $_SESSION['SMSOutcomeMessage']);

	return $output;
}

function showLoader() {
	// Determine the image path
	$imagePath = navigateToImagesFolder();
	$imagePath .= "utilityImages/ajax-loader1.gif";
	// <img src="'.$imagePath.'" alt="Loading..." />
	$output = "";
	$output .= '<div class="loader" style="display: flex;">
  	<img src="'.$imagePath.'" alt="Loading..." />
  </div>';

	return $output;
}

function hideLoader() {
	$output = "";
	$output .= '<div class="loader" style="display: none;">  	
  </div>';

	return $output;
}

// ../includes/
// __autoload() function is a standalone function that exists outside of an object. When the program loads and notices there is a class which is not defined, it goes into the __autoload() function to see if it can retrieve it from it. If it finds it, it loads it and continues the executing the program.
// This function is used to automatically load program files that will be run before a program. It is an efficient way of loading multiple program files that will be loaded with 'require_once'
// If this autoload function will be used, then you need to ensure that the name of the class is the same as the name of file ending with .php extension. Example (class_name.php)
/*function __autoload($class_name) {
	$class_name = strtolower($class_name);
	// $path = "../includes/{$class_name}.php";
	$path = LIB_PATH.DS."{$class_name}.php";
	if(file_exists($path)) {
		require_once($path);
	} else {
		die("The file {$class_name}.php could not be found.");
	}
}*/

function scriptPath() {
	list($scriptPath) = get_included_files();
	return $scriptPath;
}

function scriptPathName() {
	return basename(scriptPath());
}

function getScriptName() {
	// Get the script path from the server including passed on variables
	$scriptPath = $_SERVER['REQUEST_URI'];

	/* // Seperate the path or folders into an array
	$exploadedPath = explode("/", $scriptPath);
	// Get the last array value as the script name
	$scriptName = end($exploadedPath); */

	$scriptName = basename($scriptPath);

	return $scriptName;
}

function getProfileType($scriptFullname) {
	// Check if a key and value pair is passed to the URL
	if (strpos($scriptFullname, '?') !== false) {
		$exploadedPath = explode("?", $scriptFullname);
		// Get the last array value as the key and value pair
		$keyValuePair = end($exploadedPath);

		$keyValueArray = explode("=", $keyValuePair);
		// Get the profile type
		return end($keyValueArray);
	} else {
		return false;
	}
}

function include_layout_template($template="") {
	include(SITE_ROOT.DS.'Public'.DS.'layouts'.DS.$template);
}

// This is a function that creates a log file for the application
function log_action($action, $message="") {
	// establish where the log file we be
	$logfile = SITE_ROOT.DS.'Logs'.DS.'log.txt';
	$new = file_exists($logfile) ? false : true;
	if($handle = fopen($logfile, 'a')) { // append
		$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
		$content = "{$timestamp} | {$action}: {$message}\n";
		fwrite($handle, $content);
		fclose($handle);
		// this will only work for Mac/Linu/Unix systems
		if($new) { chmod($logfile, 0755); }
	} else {
		echo "Could not open log file for writing.";
	}
}

// This is a function that creates a log file for the application
function user_log_action($action, $message="") {
	// establish where the log file we be
	$logfile = SITE_ROOT.DS.'Logs'.DS.'usersLog.txt';
	$new = file_exists($logfile) ? false : true;
	if($handle = fopen($logfile, 'a')) { // append
		$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
		$content = "{$timestamp} | {$action}: {$message}\n";
		fwrite($handle, $content);
		fclose($handle);
		// this will only work for Mac/Linu/Unix systems
		if($new) { chmod($logfile, 0755); }
	} else {
		echo "Could not open log file for writing.";
	}
}

// This is a function that creates a log file for the application
function cus_log_action($action, $message="") {
	// establish where the log file we be
	$logfile = SITE_ROOT.DS.'Logs'.DS.'customersLog.txt';
	$new = file_exists($logfile) ? false : true;
	if($handle = fopen($logfile, 'a')) { // append
		$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
		$content = "{$timestamp} | {$action}: {$message}\n";
		fwrite($handle, $content);
		fclose($handle);
		// this will only work for Mac/Linu/Unix systems
		if($new) { chmod($logfile, 0755); }
	} else {
		echo "Could not open log file for writing.";
	}
}

// This is a function that creates a log file for the application
function admin_log_action($action, $message="") {
	// establish where the log file we be
	$logfile = SITE_ROOT.DS.'Logs'.DS.'adminsLog.txt';
	$new = file_exists($logfile) ? false : true;
	if($handle = fopen($logfile, 'a')) { // append
		$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
		$content = "{$timestamp} | {$action}: {$message}\n";
		fwrite($handle, $content);
		fclose($handle);
		// this will only work for Mac/Linu/Unix systems
		if($new) { chmod($logfile, 0755); }
	} else {
		echo "Could not open log file for writing.";
	}
}

/******** Begining of Server Functions **********/
// Returns a file size limit in bytes based on the PHP upload_max_filesize and post_max_size
function file_upload_max_size() {
	static $max_size = -1;

	if ($max_size < 0) {
		// Start with post_max_size
		$post_max_size = parse_size(ini_get('post_max_size'));
		if ($post_max_size > 0) {
			$max_size = $post_max_size;
		}

		// If upload_max_size is less, then reduce. Except if upload_max_size is zero, which indicates no limit.
		$upload_max = parse_size(ini_get('upload_max_filesize'));
		if ($upload_max > 0 && $upload_max < $max_size) {
			$max_size = $upload_max;
		}
	}

	return $max_size;
}

function parse_size($size) {
	// Remove the non-unit characters from the size.
	$unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
	// Remove the non-numeric characters from the size.
	$size = preg_replace('/[^0-9\.]/', '', $size);
	if ($unit) {
		// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
		return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
	} else {
		return round($size);
	}
}
/******** End of Server Functions **********/

// Check image orientation
function getImgOrientation($file) {
  list($width, $height) = getimagesize($file);

  if ($width > $height) {
    $orientation = "Landscape"; 
  } else {
    $orientation = "Portrait"; 
  }

  return $orientation;
}

if (isset($_POST['logoutBtn'])) {
	$session->logout();
	$session->message("You logged out successfully. Have a nice day.");
	redirect_to("/Public/index.php");
}

function clearFBsession() {
	unset($_SESSION['eci_login_required_to_connect_facebook']);
	unset($_SESSION['fb_access_token']);
	unset($_SESSION['fb_user_info']);
	unset($_SESSION['profilePortalType']);
  unset($_SESSION['fbLogin']);
}

function clearGoogleSession() {
	unset($_SESSION['request_to_connect_google']);
	unset($_SESSION['google_access_token']);
	unset($_SESSION['google_user_info']);
	unset($_SESSION['profilePortalType']);
  unset($_SESSION['googleLogin']);
}

// If the user signed in with FB email account, update their FB user id and FB access token
function updateFBuserAccessToken($found_user) {
	if ( isset( $_SESSION['fb_user_info']['id'], $_SESSION['fb_access_token'] ) ) {
		// if we have facebook id save it
		$found_user->fb_user_id = $_SESSION['fb_user_info']['id'];
		// if we have an FB access token save it
		$found_user->fb_access_token = $_SESSION['fb_access_token'];
		$found_user->update();
		
		// Clear Facebook session used
    clearFBsession(); 
	}
}

// If the user signed in with FB email account, update their Google user id and FB access token
function updateGoogleUserAccessToken($found_user) {
	if ( isset( $_SESSION['google_user_info']['id'], $_SESSION['google_access_token'] ) ) {
		// if we have google id save it
		$found_user->google_user_id = $_SESSION['google_user_info']['id'];
		// if we have a google access token save it
		$found_user->google_access_token = $_SESSION['google_access_token'];
		$found_user->update();
		
		// Clear Facebook session used
    clearGoogleSession(); 
	}
}

// This function navigates from any webpage futher in path to the images folder within the public folder
function navigateToImagesFolder() {
	$currentPageName = substr($_SERVER['SCRIPT_NAME'], strpos($_SERVER['SCRIPT_NAME'], '/') + 1);
	// brake the path into an array
	$brokenPath = explode("/", $currentPageName);
	$pointer = 0;
	// find the position of the Public folder
	foreach ($brokenPath as $key => $value) {
		if ($value === 'Public') {
			$pointer = $key;
		}
	}

	// make path directory from current path to image folder
	$lengthArray = count($brokenPath);
	$lastFolderPos = $lengthArray - 1;
	$path = "";
	if (($lengthArray - 1) > $pointer) {
		$numLoops = $lastFolderPos - ($pointer + 1);
		for ($i=0; $i < $numLoops; $i++) { 
			$path .= "../";
		}
		$path .= "images/";
	}

	return $path;	
}

// This converts the date and time format of MySQL to a better output.
function datetime_to_text($datetime="") {
  $unixdatetime = strtotime($datetime);
  return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
}

// This converts the date format of MySQL to a better output.
function date_to_text($datetime="") {
  $unixdatetime = strtotime($datetime);
  return strftime("%B %d, %Y", $unixdatetime);
}

// This converts the unix datetime to a custom format
function date_to_text2($unixdatetime="") {
  return strftime("%B %d, %Y", $unixdatetime);
}

function date_to_weekday($datetime="") {
	$unixdatetime = strtotime($datetime);
	$dayProperties = getdate($unixdatetime);
	return $dayProperties['weekday'];
}

// This converts the unix datetime to a custom format
function date_to_weekday2($unixdatetime="") {
	$dayProperties = getdate($unixdatetime);
	return $dayProperties['weekday'];
}

// Create the current time and date and return it in MYSQL format
function current_Date_Time() {
	// Get the current time
	$dateTime = time();
	// Convert the current time to (Y:M:D H:M:S) which MYSQL takes
	// $mysql_dateTime = strftime("%Y-%m-%d %H:%M:%S", $dateTime);
	$mysql_dateTime = strftime("%F %T", $dateTime);
	// return the formated time of (Y:M:D H:M:S) for MYSQL
	return $mysql_dateTime;
}

function getCustomerAvatarById($customerId) {
	global $session;
	$output = "";
	$photoObj = Photograph::find_avatar($customerId);
	// Check if a record was found in the database
	if (!empty($photoObj)) {
		$imgPath = $photoObj->filename;	
		if (file_exists($imgPath)) {
			$output .= $imgPath;
		} else {
			$output .= 'emptyImageIcon.png';
		}
	} else {
		$output .= 'emptyImageIcon.png';
	}

	return $output;
}

// This function will display all the states within nigeria in a select drop down menu.
// It can be moved to the address class
function displayStateOptions() {
	// Get the states from the database
	$stateTowns = new State_Town();
	$states = $stateTowns->getStates();

	// Sort the state alphabetically
	asort($states);
	
	$output = "";
	// Select value is set to empty, so that validation function will be able to identify if a state is not selected
	$output .= "<option value=''>Select</option>";
	foreach ($states as $state) {
		$output .= "<option value='".$state."'>".ucfirst(str_replace("_", " ", $state))."</option>";
	}
	
	return $output;
}

function displayTownOptions($state) {
	// Get the twons of the selected state from the database
	$towns = State_Town::find_by_column($state);
	// Sort the towns in alphabetical order
	asort($towns);

	$output = "";
	// Select value is set to empty, so that validation function will be able to identify if a town is not selected
	$output .= "<option value=''>Select</option>";
	foreach ($towns as $town) {
		$output .= "<option value='".lcfirst(str_replace(" ", "_", $town))."'>".ucfirst($town)."</option>";
	}
	$output .= "<option value='other'>Other</option>";
	
	return $output;
}

// This function converts all windows path to UNIX path
function wp_normalize_path($path) {
	$path = str_replace('\\', '/', $path);
	$path = preg_replace('|(?<=.)/+|', '/', $path);
	if (':' === substr($path, 1, 1)) {
		$path = ucfirst($path);
	}
	
	return $path;
}

// Send mail to recepients
function sendMail($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork.com', $to='', $title='', $body='') {
	// Load Composer's autoloader
	// Autoload already loaded in initialize.php
	// require_once("../phpmailer/vendor/autoload.php");

	// Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);

	try {
		//Server settings
		// $mail->SMTPDebug = 2;
		// Enable verbose debug output
		// Set mailer to use SMTP
		$mail->isSMTP();
		// Specify main and backup SMTP servers
		$mail->Host       = 'mail.whosabiwork.com';
		// Enable SMTP authentication
		$mail->SMTPAuth   = true;
		// SMTP username
		$mail->Username   = 'support@whosabiwork.com';
		// SMTP password
		$mail->Password   = 'oHL=p[aNHAV1';
		// Enable TLS encryption, `ssl` also accepted
		$mail->SMTPSecure = 'ssl';
		// TCP port to connect to
		$mail->Port       = 465;
		// Recipients
		$mail->setFrom($fromEmail, $fromName);
		$mail->addAddress($to);     
		// Use the address function to generate more addresses to send to.
		
		// $mail->addReplyTo('info@example.com', 'Information');

		// $body = "<p><strong>Hello baby!!!</strong> WhoSabiWork email is up and running. Am loving it. I love so much, missing you a 100 folds here, kiss kiss. </p>";

		// Content
		$mail->isHTML(true);
		// Set email format to HTML
		$mail->Subject = $title;
		$mail->Body    = $body;
		$mail->AltBody = strip_tags($body);

		// $result = $mail->send();
		
		$session = new Session();
		if ($mail->send()) {
			$session->message('An email has been sent to your email address on file.');
			return true;
		} else {
			$session->message('Email was unable to be sent. Inform the customer support center.');
			return false;
		}
		// Create a log for message sent.
		// echo 'Message has been sent';
	} catch (Exception $e) {
		// Log this error into the log file.
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
}

// This function gets the user IP address
function GetUserIP() {
	$ip;
	if (getenv("HTTP_CLIENT_IP")) {
		$ip = getenv("HTTP_CLIENT_IP");
	} elseif (getenv("HTTP_X_FORWARDED_FOR")) {
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	} elseif (getenv("REMOtE_ADDR")) {
		$ip = getenv("REMOtE_ADDR");
	} else {
		$ip = "UNKNOWN";
	}
	
	return $ip;
}

// Check what login Id type (Username, ) was used when trying to login in.
function getLoginIdType($loginId) {
	if (filter_var($loginId, FILTER_VALIDATE_EMAIL)) {
		$loginType = 'email';
	} elseif (is_numeric($loginId)) {
		$loginType = 'phone_number';
	} else {
		$loginType = 'username';
	}

	return $loginType;
}

// This string generates a random string of any length specified.
function passwordGen($length) {
	$result = "";
	// These chars contains both numbers from 0 - 9, small letters a - z and capital letters A - Z
	$chars = "Aa0Bb1Cc9Dd2Ee8Ff3Gg7Hh4Ii6Jj5Kk5Ll6Mm4Nn7Oo3Pp8Qq2Rr9Ss1Tt0UuVvWwXxYyZz";
	// This will split the characters into an array
	$charArray = str_split($chars);
	for ($i = 0; $i < $length; $i++) {
		// This will randomly select the key from the array "charArray" created.
		$randItem = array_rand($charArray);
		// This will concatenate the random string using the random character selected.
		$result .= "".$charArray[$randItem];
	}
	return $result;
}

// This string generates a random string of any length specified.
function randStrGen($length) {
	$result = "";
	// These chars contains both numbers from 0 - 9 and capital letters A - Z
	$chars = "A0B1C9D2E8F3G7H4I6J5K5L6M4N7O3P8Q2R9S1T0UVWXYZ";
	// This will split the characters into an array
	$charArray = str_split($chars);
	for ($i = 0; $i < $length; $i++) {
		// This will randomly select the key from the array "charArray" created.
		$randItem = array_rand($charArray);
		// This will concatenate the random string using the random character selected.
		$result .= "".$charArray[$randItem];
	}
	return $result;
}

function sendSMSCode($phoneNumber, $message) {
	$apiKey = "CDqdYvVDF7P7lLTorVo%2BzKjmwYYvRls6nMUbJnJ%2FuDg%3D";
	$clientId = "7bf515f1-b9a0-43bb-a25d-e1d875def7bf";
	
	if (isset($phoneNumber) && isset($message)) {
		$senderId = 'whoSabiWork';
		// $message = 'Your phone number has been confirmed, please use this code '.$smsCode.' to complete your password reset.';

		// Eliminate the first zero in front of the telephone number if it exists
		if(substr($phoneNumber,0,1)=="0"){
			$phoneNumber = "234".substr($phoneNumber,1);
		}
		$message = urlencode($message);

		// Check for what server you are sending the message from
		if ($_SERVER["SERVER_NAME"] === 'localhost') {
	    // Sending from localhost (development mode)
	    // This works for sending message from the localhost
  		// $url = "http://45.77.146.255:6005/api/v2/SendSMS?SenderId=".$senderId."&Is_Unicode=false&Is_Flash=false&Message=".$message."&MobileNumbers=".$phoneNumber."&ApiKey=".$apiKey."&ClientId=".$clientId."&callback=?";

  		// Recently working for localhost server
  		$url = "https://secure.xwireless.net/api/v2/SendSMS?SenderId=".$senderId."&Is_Unicode=false&Is_Flash=false&Message=".$message."&MobileNumbers=".$phoneNumber."&ApiKey=".$apiKey."&ClientId=".$clientId."&callback=?";
		} else {
	    // Sending from hosting server (production mode)
	    // This works when the website is on production mode (online)
  		$url = "https://secure.xwireless.net/api/v2/SendSMS?SenderId=".$senderId."&Is_Unicode=false&Is_Flash=false&Message=".$message."&MobileNumbers=".$phoneNumber."&ApiKey=".$apiKey."&ClientId=".$clientId."&callback=?";
		}

		$json = "";
		$json = @file_get_contents($url);
		// print_r($json);
	} else {
		$json = '{"error" : "The Phone number of message was not received."}';
	}
	// convert received xwireless json data to PHP variables
	$_SESSION['xwirelessData'] = $xwirelessData = json_decode($json, true);
	if ($xwirelessData['Data'][0]['MessageErrorDescription'] == 'Success') {
		return true;
	} elseif (isset($xwirelessData['error'])) {
		// This returns an error if the function parameters are not set right
		return false;
	} else {
		// This returns an error if there was an error communicating with 'xwireless' server
		return false;
	}
}

function sendSMSCodeOld($phoneNumber, $message) {
	// $phoneNumber = '08057368560';
	
	if (isset($phoneNumber)&& isset($message)) {
		// $phoneNumber = $post_params['phoneNumber'];
		// $smsCode = 'P9N3eQ';
		$username = 'nokafor';
		$password = '6QpL4X96';
		$sender = 'WhoSabiWork';
		// $message = 'Your phone number has been confirmed, please use this code '.$smsCode.' to complete your password reset.';
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
		$json = '{"error" : "An error occurred sending the data from JSON"}';
	}
	// convert received xwireless json data to PHP variables
	$_SESSION['xwirelessData'] = $xwirelessData = json_decode($json, true);
	if (isset($xwirelessData['msg_id'])) {
		return true;
	} elseif (isset($xwirelessData['error'])) {
		// This returns an error if the function parameters are not set right
		return false;
	} else {
		// This returns an error if there was an communicating with 'xwireless' server
		return false;
	}
}


function makeSenderNotificationSMS($fullName="", $skills="", $phoneNumber="", $date="", $time="", $reason="", $address="") {
	// send SMS and email to customer creating the appointment
	$SMSmessage = 'You have scheduled an appointment with an artisan.';
	$SMSmessage .= PHP_EOL.'Name: '.$fullName;
	// $SMSmessage .= PHP_EOL.'Expertise: '.$skills;
	$SMSmessage .= PHP_EOL.'Phone number: '.$phoneNumber;
	$SMSmessage .= PHP_EOL.'Date: '.date_to_text($date);
	$SMSmessage .= PHP_EOL.'Time: '.$time;
	// $SMSmessage .= PHP_EOL.'Reason: '.$reason;
	$SMSmessage .= PHP_EOL.'Address: '.$address;

	return $SMSmessage;
}

function makeSenderNotificationEmail($fullName="", $skills="", $phoneNumber="", $date="", $time="", $reason="", $address="") {
	// send SMS and email to customer creating the appointment
	$emailMessage = '<img src="cid:whoSabiWorkLogo">';
	$emailMessage .= '<p>You have scheduled an appointment with an artisan. The details are below.</p>';
	$emailMessage .= '<p>Name: '.$fullName.'<p>';
	$emailMessage .= '<p>Expertise: '.$skills.'<p>';
	$emailMessage .= '<p>Phone number: '.$phoneNumber.'<p>';
	$emailMessage .= '<p>Date: '.date_to_text($date).'<p>';
	$emailMessage .= '<p>Time: '.$time.'<p>';
	$emailMessage .= '<p>Reason: '.$reason.'<p>';
	$emailMessage .= '<p>Address: '.$address.'<p>';

	return $emailMessage;
}

function makeReceiverNotificationSMS($fullName="", $phoneNumber="", $date="", $time="", $reason="") {
	// send SMS and email to customer receiving the appointment
	$SMSmessage = 'A customer has scheduled an appointment with you.';
	$SMSmessage .= PHP_EOL.'Name: '.$fullName;
	$SMSmessage .= PHP_EOL.'Phone number: '.$phoneNumber;
	$SMSmessage .= PHP_EOL.'Date: '.date_to_text($date);
	$SMSmessage .= PHP_EOL.'Time: '.$time;
	// $SMSmessage .= PHP_EOL.'Reason: '.$reason;

	return $SMSmessage;
}

function makeReceiverNotificationEmail($fullName="", $phoneNumber="", $date="", $time="", $reason="") {
	// send SMS and email to customer receiving the appointment
	$emailMessage = '<img src="cid:whoSabiWorkLogo">';
	$emailMessage .= '<p>A customer has scheduled an appointment with you. The details are below. </p>';
	$emailMessage .= '<p>Name: '.$fullName.'</p>';
	$emailMessage .= '<p>Phone number: '.$phoneNumber.'</p>';
	$emailMessage .= '<p>Date: '.date_to_text($date).'</p>';
	$emailMessage .= '<p>Time: '.$time.'</p>';
	$emailMessage .= '<p>Reason: '.$reason.'</p>';

	return $emailMessage;
}

function sendEmailOutcome($outcome) {
	global $session;
	if ($outcome) {
		$_SESSION['emailSuccess'] = TRUE;
		$_SESSION['emailOutcomeMessage'] = 'A mail has been sent to your email address.';
	} else {
		$_SESSION['emailSuccess'] = FALSE;
		$_SESSION['emailOutcomeMessage'] = 'An error occured sending a mail to your email address.';
	}

	return true;
}

function sendEmailOutcome2($outcome) {
	global $session;

	$output = "";
	if (is_bool($outcome)) {
		if ($outcome) {
			$output .= 'A mail has been sent to your email address.';
		} else {
			$output .= 'An error occured sending a mail to your email address.';
		}
	} else {
		return $outcome;
	}

	return $output;
}

function sendSMSOutcome($outcome) {
	global $session;
	if ($outcome) {
		$_SESSION['SMSSuccess'] = TRUE;
		$_SESSION['SMSOutcomeMessage'] = 'A text message has been sent to your phone number in your profile.';
	} else {
		$_SESSION['SMSSuccess'] = FALSE;
		$_SESSION['SMSOutcomeMessage'] = 'An error occured sending a text message to your phone number in your profile.';
	}

	return true;
}

function sendSMSOutcome2($outcome) {
	global $session;
	
	$output = "";
	if ($outcome) {
		$output .= 'A text message has been sent to your phone number in your profile.';
	} else {
		$output .= 'An error occured sending a text message to your phone number in your profile.';
	}

	return $output;
}

// Save the number of views of a customer when clicked
function saveViewedCustomer($customerId) {
	// Save the record in the database as viewed
	$businessCategoryObj = new Business_Category();
	$businessCategory = Business_Category::find_by_customerId($customerId);
	// increment the view variable 
	$businessCategory->views = $businessCategory->views + 1;
	// Save the update;
	if ($businessCategory->update()) {
		return true;
	} else {
		return false;
	}
}


/*
// This function encrypts a string passed into it
function encrypt($message, $encryption_key) {
	$key = hex2bin($encryption_key);

	$nonceSize = openssl_cipher_iv_length('aes-256-ctr');
	$nonce = openssl_random_pseudo_bytes($nonceSize);

	$ciphertext = openssl_encrypt(
		$message, 
		'aes-256-ctr', 
		$key,
		OPENSSL_RAW_DATA,
		$nonce
	);

	return base64_encode($nonce.$ciphertext);
}

// This function decrypts a string passed into it
function decrypt($message, $encryption_key) {
	$key = hex2bin($encryption_key);
	$message = base64_decode($message);
	$nonceSize = openssl_cipher_iv_length('aes-256-ctr');
	$nonce = mb_substr($message, 0, $nonceSize, '8bit');
	$ciphertext = mb_substr($message, $nonceSize, null, '8bit');

	$plaintext = openssl_decrypt(
		$ciphertext, 
		'aes-256-ctr', 
		$key,
	  OPENSSL_RAW_DATA,
	  $nonce
	);

	return $plaintext;
}
*/


/*
// This function makes a unique associate multidimentional array using the id
function unique_multidim_array($multiArray) {
	// Get unique ids to search
	$arrayIds = array();
	foreach ($multiArray as $key => $value) {
		$arrayIds[] = $multiArray[$key]->id;
	}
	// Get unique ids 
	$uniqueIds = array_unique($arrayIds);

	foreach ($uniqueIds as $key => $value) {
		if (in_array($value, haystack)) {

		}
	}
	return $uniqueIds;
} */

/* Begining of Validation Functions */

// * validate value has string length
// leading and trailing spaces will count
// options: exact, max, min
// has_length($first_name, ['exact' => 20])
// has_length($first_name, ['min' => 5, 'max' => 100])
function has_length($value, $options=[]) {
	if(isset($options['max']) && (strlen($value) > (int)$options['max'])) {
		return false;
	}
	if(isset($options['min']) && (strlen($value) < (int)$options['min'])) {
		return false;
	}
	if(isset($options['exact']) && (strlen($value) != (int)$options['exact'])) {
		return false;
	}
	return true;
}

// * validate value has a format matching a regular expression
// Be sure to use anchor expressions to match start and end of string.
// (Use \A and \Z, not ^ and $ which allow line returns.) 
// 
// Example:
// has_format_matching('1234', '/\d{4}/') is true
// has_format_matching('12345', '/\d{4}/') is also true
// has_format_matching('12345', '/\A\d{4}\Z/') is false
function has_format_matching($value, $regex='//') {
	return preg_match($regex, $value);
}

// * validate value is a number
// submitted values are strings, so use is_numeric instead of is_int
// options: max, min
// has_number($items_to_order, ['min' => 1, 'max' => 5])
function has_number($value, $options=[]) {
	if(!is_numeric($value)) {
		return false;
	}
	if(isset($options['max']) && ($value > (int)$options['max'])) {
		return false;
	}
	if(isset($options['min']) && ($value < (int)$options['min'])) {
		return false;
	}
	return true;
}

// * validate value is inclused in a set
function has_inclusion_in($value, $set=[]) {
  return in_array($value, $set);
}

// * validate value is excluded from a set
function has_exclusion_from($value, $set=[]) {
  return !in_array($value, $set);
}

// An ultra-simple file logger
// This function logs in errors when it occurs.
function logger($level="ERROR", $msg="") {
	// log file must exist and have permissions set that allow writing
	// Example in Unix: chmod 777 errors.log
	$log_file = 'errors.log';
	// global $log_file;
	
	// Ensure all messages have a final line return
	$log_msg = $level . ": " . $msg . PHP_EOL;
	
	// FILE_APPEND adds content to the end of the file
	// LOCK_EX forbids writing to the file while in use by us
	file_put_contents($log_file, $log_msg, FILE_APPEND | LOCK_EX);
}

// Example of using the logger function
// logger("ERROR", "An unknown error occurred");
// logger("DEBUG", "x is 1");

/* End of Validation Functions */



/* Functions controlling users appointment */
function displayDeclinedAppointments() {
	global $database;
	global $customerID;
	global $session;
	
	// Instantiate the Customers_Availability class
	$cus_Appointments = new Customers_Appointment();
	// Get the date for the week using the method for it
	$weekDates = $cus_Appointments->weekDatesFromToday();
	// Get the current day date of the week
	$currentDateOfWeek = $weekDates[0];
	// Get the next 6 days date of the week
	$next6DateOfWeek = $weekDates[6];
	
	$output = "";
	if ($session->is_user_logged_in()) {
		$appointment_owner = $_SESSION['user_full_name'];
		$user_id = $_SESSION['user_id'];
		// The query code can also be included here. In this case, you will use the user_id and the scheduled_user saved in the database to check for the user as opposed to using the customer's name.
		$sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_user={$user_id} AND customer_decision='declined'";
	} 
	/* elseif (($session->is_customer_logged_in())) {
		$appointment_owner = $_SESSION['customer_full_name'];
		// $customer_id = $_SESSION['customer_id'];
		// The query code can also be included here. In this case, you will use the customer_id and the scheduled_customer saved in the database to check for the user as opposed to using the customer's name.
		// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_customer={$customer_id} AND customer_decision='neutral'";
	} */ else {
		// If the user or customer is not loggen in, inform them and don't run the remaining code.
		$output .= "<div class='setUserAppointment' >";
		$output .= "<p>You are not logged in. Please login to see your appointments.</p>";
		$output .= "</div>";
		
		return $output;
	}
	
	// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND appointment_owner='{$appointment_owner}' AND customer_decision='neutral'";
	$result_set = $database->query($sql); 

	$count = 0; 
	while($row = mysqli_fetch_assoc($result_set)){
		$appointmentId[$count] = $row["id"];
		$appointmentDates[$count] = $row["appointment_date"];
		// $appointmentHours[$count] = $row["hours"];
		// $appointmentMessage[$count] = $row["appointment_message"];
		// Get the phone number and name of the technician
		// $customerDetails = Customer::find_by_id($row["customers_id"]);
		$customerNumber[$count] = $row["customer_number"];
		$customerName[$count] = $row["customer_name"];
		$declineMessage[$count] = $row["cus_decline_message"];
		
		$count++; 
	}
	// echo "The number of record is: ".$count;
	// Concatenate string of HTML to output the appointments requested up
	if ($count > 0) {
		// Inform the customer the number of appointments accepted. The if condition is used to check for one or many conditions.
		if ($count > 1) {
			$output .= "<div class='setUserAppointment' id='appointmentInfoDecline' >You have ".$count." appointments declined.</div>";
		} elseif ($count == 1) {
			$output .= "<div class='setUserAppointment' id='appointmentInfoDecline' >You have ".$count." appointment declined.</div>";
		}
		
		foreach ($appointmentDates as $key => $record) {
			$output .= "<div class='setUserAppointment' id='userAppointCard".$key."'>";
			
			// The appointment id from the database saved in a hidden paragraph tag
			$output .= "<p style='display:none;' id='databaseId".$key."' >".$appointmentId[$key]."</p>";
			
			// The div informing the user of the canceled appointment.
			$output .= "<div class='userAppointDetails' id='userBtnCtrl".$key."' style='color:red;' >This appointment has been declined.</div>";
			
			// The title of owner of the appointment div
			$output .= "<div class='userAppointDetails' id='nameDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Name of technician: </p>";
			$output .= "<p class='labelValue' id='customerName".$key."' >".$customerName[$key]."</p>";
			$output .= "</div>";
			
			// The phone number of the person scheduling the appointment
			$output .= "<div class='userAppointDetails' id='numberDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Phone number of technician: </p>";
			$output .= "<p class='labelValue' id='customerNumber".$key."' >".$customerNumber[$key]."</p>";
			$output .= "</div>";
			
			// The div containing the day for the appointment
			$output .= "<div class='userAppointDetails' id='userAptDayDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Day of appointment: </p>";
			$output .= "<p class='labelValue' id='userAptDay".$key."' >".date_to_weekday($appointmentDates[$key]).", ".date_to_text($appointmentDates[$key])."</p>";
			$output .= "</div>";
			
			// The div containing the reason for the technician declining the appointment
			$output .= "<div class='userAppointDetails' id='customerDeclineMessageDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Reason for declining appointment: </p>";
			$output .= "<p class='labelValue' id='customerDeclineMessage".$key."' >".$declineMessage[$key]."</p>";
			$output .= "</div>";
			
			// This is a dummy div to hold the appearance of the contents.
			$output .= "<div class='userAppointDetails' id='userBtnCtrl".$key."' ></div>";
			
			$output .= "</div>";
		}
	} else {
		$output .= "<div class='setUserAppointment' >";
		$output .= "<p>You have no appointment request declined.</p>";
		$output .= "</div>";
	}
	
	return $output;
}

function displayCanceledAppointments() {
	global $database;
	global $customerID;
	global $session;
	
	// Instantiate the Customers_Availability class
	$cus_Appointments = new Customers_Appointment();
	// Get the date for the week using the method for it
	$weekDates = $cus_Appointments->weekDatesFromToday();
	// Get the current day date of the week
	$currentDateOfWeek = $weekDates[0];
	// Get the next 6 days date of the week
	$next6DateOfWeek = $weekDates[6];
	
	$output = "";
	if ($session->is_user_logged_in()) {
		$appointment_owner = $_SESSION['user_full_name'];
		$user_id = $_SESSION['user_id'];
		// The query code can also be included here. In this case, you will use the user_id and the scheduled_user saved in the database to check for the user as opposed to using the customer's name.
		$sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_user={$user_id} AND customer_decision='canceled'";
	} 
	/* elseif (($session->is_customer_logged_in())) {
		$appointment_owner = $_SESSION['customer_full_name'];
		// $customer_id = $_SESSION['customer_id'];
		// The query code can also be included here. In this case, you will use the customer_id and the scheduled_customer saved in the database to check for the user as opposed to using the customer's name.
		// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_customer={$customer_id} AND customer_decision='neutral'";
	} */ else {
		// If the user or customer is not logged in, inform them and don't run the remaining code.
		$output .= "<div class='setUserAppointment' >";
		$output .= "<p>You are not logged in. Please login to see your appointments.</p>";
		$output .= "</div>";
		
		return $output;
	}
	
	// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND appointment_owner='{$appointment_owner}' AND customer_decision='neutral'";
	$result_set = $database->query($sql); 

	$count = 0; 
	while($row = mysqli_fetch_assoc($result_set)){
		$appointmentId[$count] = $row["id"];
		$appointmentDates[$count] = $row["appointment_date"];
		// $appointmentHours[$count] = $row["hours"];
		// $appointmentMessage[$count] = $row["appointment_message"];
		// Get the phone number and name of the technician
		// $customerDetails = Customer::find_by_id($row["customers_id"]);
		$customerNumber[$count] = $row["customer_number"];
		$customerName[$count] = $row["customer_name"];
		$cancelMessage[$count] = $row["cus_cancel_message"];
		
		$count++; 
	}
	// echo "The number of record is: ".$count;
	// Concatenate string of HTML to output the appointments requested up
	if ($count > 0) {
		// Inform the customer the number of appointments accepted. The if condition is used to check for one or many conditions.
		if ($count > 1) {
			$output .= "<div class='setUserAppointment' id='appointmentInfoCancel' >You have ".$count." appointments canceled.</div>";
		} elseif ($count == 1) {
			$output .= "<div class='setUserAppointment' id='appointmentInfoCancel' >You have ".$count." appointment canceled.</div>";
		}
		
		foreach ($appointmentDates as $key => $record) {
			$output .= "<div class='setUserAppointment' id='userAppointCard".$key."'>";
			
			// The appointment id from the database saved in a hidden paragraph tag
			$output .= "<p style='display:none;' id='databaseId".$key."' >".$appointmentId[$key]."</p>";
			
			// The div informing the user of the canceled appointment.
			$output .= "<div class='userAppointDetails' id='userBtnCtrl".$key."' style='color:orange;' >This appointment has been canceled.</div>";
			
			// The title of owner of the appointment div
			$output .= "<div class='userAppointDetails' id='nameDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Name of technician: </p>";
			$output .= "<p class='labelValue' id='customerName".$key."' >".$customerName[$key]."</p>";
			$output .= "</div>";
			
			// The phone number of the person scheduling the appointment
			$output .= "<div class='userAppointDetails' id='numberDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Phone number of technician: </p>";
			$output .= "<p class='labelValue' id='customerNumber".$key."' >".$customerNumber[$key]."</p>";
			$output .= "</div>";
			
			// The div containing the day for the appointment
			$output .= "<div class='userAppointDetails' id='userAptDayDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Day of appointment: </p>";
			$output .= "<p class='labelValue' id='userAptDay".$key."' >".date_to_weekday($appointmentDates[$key]).", ".date_to_text($appointmentDates[$key])."</p>";
			$output .= "</div>";
			
			// The div containing the reason for canceling appointment
			$output .= "<div class='userAppointDetails' id='customerCancelMessageDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Reason for canceling appointment: </p>";
			$output .= "<p class='labelValue' id='customerCancelMessage".$key."' >".$cancelMessage[$key]."</p>";
			$output .= "</div>";
			
			// This is a dummy div to hold the appearance of the contents.
			$output .= "<div class='userAppointDetails' id='userBtnCtrl".$key."' ></div>";
			
			$output .= "</div>";
		}
	} else {
		$output .= "<div class='setUserAppointment' >";
		$output .= "<p>You have no appointment request canceled.</p>";
		$output .= "</div>";
	}
	
	return $output;
}	

function displayRequestedAppointments() {
	global $database;
	global $customerID;
	global $session;
	
	// Instantiate the Customers_Availability class
	$cus_Appointments = new Customers_Appointment();
	// Get the date for the week using the method for it
	$weekDates = $cus_Appointments->weekDatesFromToday();
	// Get the current day date of the week
	$currentDateOfWeek = $weekDates[0];
	// Get the next 6 days date of the week
	$next6DateOfWeek = $weekDates[6];
	
	// Concatenate string of HTML to output the appointments requested up
	$output = "";
	if ($session->is_user_logged_in()) {
		$appointment_owner = $_SESSION['user_full_name'];
		// $user_id = $_SESSION['user_id'];
		// The query code can also be included here. In this case, you will use the user_id and the scheduled_user saved in the database to check for the user as opposed to using the customer's name.
		// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_user={$user_id} AND customer_decision='neutral'";
	} 
	/* elseif (($session->is_customer_logged_in())) {
		$appointment_owner = $_SESSION['customer_full_name'];
		// $customer_id = $_SESSION['customer_id'];
		// The query code can also be included here. In this case, you will use the customer_id and the scheduled_customer saved in the database to check for the user as opposed to using the customer's name.
		// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_customer={$customer_id} AND customer_decision='neutral'";
	} */ else {
		// If the user or customer is not loggen in, inform them and don't run the remaining code.
		$output .= "<div class='setUserAppointment' >";
		$output .= "<p>You are not logged in. Please login to see your appointments.</p>";
		$output .= "</div>";
		
		return $output;
	}
	
	$sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND appointment_owner='{$appointment_owner}' AND customer_decision='neutral'";
	$result_set = $database->query($sql); 

	$count = 0; 
	while($row = mysqli_fetch_assoc($result_set)){
		$appointmentId[$count] = $row["id"];
		$appointmentDates[$count] = $row["appointment_date"];
		$appointmentHours[$count] = $row["hours"];
		$appointmentMessage[$count] = $row["appointment_message"];
		// Get the phone number and name of the technician
		// $customerDetails = Customer::find_by_id($row["customers_id"]);
		$customerNumber[$count] = $row["customer_number"];
		$customerName[$count] = $row["customer_name"];
		// Get customer phone number
		
		
		// $appointmentOwner[$count] = $row["appointment_owner"];
		// $appointerNumber[$count] = $row["appointer_number"];
		// $customerNumber[$count] = $row["customer_number"];
		$count++; 
	}
	// echo "The number of record is: ".$count;
	if ($count > 0) {
		// Inform the customer the number of appointments accepted.
		if ($count > 1) {
			$output .= "<div class='setUserAppointment' id='appointmentInfoRequest' >You have ".$count." appointments waiting for confirmation.</div>";
		} elseif ($count == 1) {
			$output .= "<div class='setUserAppointment' id='appointmentInfoRequest' >You have ".$count." appointment waiting for confirmation.</div>";
		}
		
		foreach ($appointmentDates as $key => $record) {
			$output .= "<div class='setUserAppointment' id='userAptCard".$key."'>";
			
			// The appointment id from the database saved in a hidden paragraph tag
			$output .= "<p style='display:none;' id='aptTableDBId".$key."' >".$appointmentId[$key]."</p>";
			
			// The title of owner of the appointment div
			$output .= "<div class='userAppointDetails' id='customerFullNameDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Name of technician: </p>";
			$output .= "<p class='labelValue' id='userName".$key."' >".$customerName[$key]."</p>";
			$output .= "</div>";
			
			// The phone number of the person scheduling the appointment
			$output .= "<div class='userAppointDetails' id='userNumberDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Phone number of technician: </p>";
			$output .= "<p class='labelValue' id='userNumber".$key."' >".$customerNumber[$key]."</p>";
			$output .= "</div>";
			
			// The div containing the day for the appointment
			$output .= "<div class='userAppointDetails' id='userSetAptDayDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Day of appointment: </p>";
			$output .= "<p class='labelValue' id='userSetAptDay".$key."' >".date_to_weekday($appointmentDates[$key]).", ".date_to_text($appointmentDates[$key])."</p>";
			$output .= "</div>";
			
			// The div containing the time for the appointment
			$output .= "<div class='userAppointDetails' id='userTimeHoursDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Time of appointment: </p>";
			$output .= "<p class='labelValue' id='userTimeHours".$key."' >".$appointmentHours[$key]."</p>";
			// $output .= $appointmentHours[$key];
			$output .= "</div>";
			
			// The div containing the reason for the appointment
			$output .= "<div class='userAppointDetails' id='userComplainDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Reason for appointment: </p>";
			$output .= "<p class='labelValue' id='userComplain".$key."' >".$appointmentMessage[$key]."</p>";
			// $output .= $appointmentMessage[$key];
			$output .= "</div>";
			
			
			// The div containing the cancel button for the appointment
			$output .= "<div class='userAppointDetails' id='userBtnCtrl".$key."' >";
			/* // Show the cancel button
			$output .= "<input type='button' value='Cancel' id='cancelButton".$key."' onclick='appointmentCanceled(this.id);' />"; */
			$output .= "</div>";
			
			
			$output .= "</div>";
		}
	} else {
		$output .= "<div class='setUserAppointment' >";
		$output .= "<p>You have no appointment request waiting for confirmation.</p>";
		$output .= "</div>";
	}
	
	return $output;
}

function displayConfirmedAppointments() {
	global $database;
	global $customerID;
	global $session;
	
	// Instantiate the Customers_Availability class
	$cus_Appointments = new Customers_Appointment();
	// Get the date for the week using the method for it
	$weekDates = $cus_Appointments->weekDatesFromToday();
	// Get the current day date of the week
	$currentDateOfWeek = $weekDates[0];
	// Get the next 6 days date of the week
	$next6DateOfWeek = $weekDates[6];
	
	// Concatenate string of HTML to output the appointments requested up
	$output = "";
	if ($session->is_user_logged_in()) {
		$appointment_owner = $_SESSION['user_full_name']; 
		// $user_id = $_SESSION['user_id'];
		// The query code can also be included here. In this case, you will use the user_id and the scheduled_user saved in the database to check for the user as opposed to using the customer's name.
		// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_user={$user_id} AND customer_decision='neutral'";
	} 
	/* elseif (($session->is_customer_logged_in())) {
		$appointment_owner = $_SESSION['customer_full_name'];
		// $customer_id = $_SESSION['customer_id'];
		// The query code can also be included here. In this case, you will use the customer_id and the scheduled_customer saved in the database to check for the user as opposed to using the customer's name.
		// $sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND scheduled_customer={$customer_id} AND customer_decision='neutral'";
	} */ else {
		$output .= "<div class='setUserAppointment' >";
		$output .= "<p>You are not logged in. Please login to see your appointments.</p>";
		$output .= "</div>";
		
		return $output;
	}
	
	$sql = "SELECT * FROM `customers_appointments` WHERE appointment_date >= '{$currentDateOfWeek}' AND appointment_owner='{$appointment_owner}' AND customer_decision='accepted'";
	$result_set = $database->query($sql); 

	$count = 0; 
	while($row = mysqli_fetch_assoc($result_set)){
		$appointmentId[$count] = $row["id"];
		$appointmentDates[$count] = $row["appointment_date"];
		$appointmentHours[$count] = $row["hours"];
		$appointmentMessage[$count] = $row["appointment_message"];
		// Get the phone number and name of the technician
		// $customerDetails = Customer::find_by_id($row["customers_id"]);
		$customerNumber[$count] = $row["customer_number"];
		$customerName[$count] = $row["customer_name"];
		$count++; 
	}
	// echo "The number of record is: ".$count;
	
	if ($count > 0) {
		// Inform the customer the number of appointments accepted.
		if ($count > 1) {
			$output .= "<div class='setUserAppointment' id='appointmentInfo' >You have ".$count." appointments confirmed.</div>";
		} elseif ($count == 1) {
			$output .= "<div class='setUserAppointment' id='appointmentInfo' >You have ".$count." appointment confirmed.</div>";
		}
		
		foreach ($appointmentDates as $key => $record) {
			$output .= "<div class='setUserAppointment' id='userAptInfo".$key."'>";
			
			// The appointment id from the database saved in a hidden paragraph tag
			$output .= "<p style='display:none;' id='aptDBTableId".$key."' >".$appointmentId[$key]."</p>";
			
			// The title of owner of the appointment div
			$output .= "<div class='userAppointDetails' id='technicianNameDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Name of technician: </p>";
			$output .= "<p class='labelValue' id='technicianName".$key."' >".$customerName[$key]."</p>";
			$output .= "</div>";
			
			// The phone number of the person scheduling the appointment
			$output .= "<div class='userAppointDetails' id='technicianNumberDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Phone number of technician: </p>";
			$output .= "<p class='labelValue' id='technicianNumber".$key."' >".$customerNumber[$key]."</p>";
			$output .= "</div>";
			
			// The div containing the day for the appointment
			$output .= "<div class='userAppointDetails' id='aptDayDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Day of appointment: </p>";
			$output .= "<p class='labelValue' id='aptDay".$key."' >".date_to_weekday($appointmentDates[$key]).", ".date_to_text($appointmentDates[$key])."</p>";
			$output .= "</div>";
			
			// The div containing the time for the appointment
			$output .= "<div class='userAppointDetails' id='timeHoursDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Time of appointment: </p>";
			$output .= "<p class='labelValue' id='timeHours".$key."' >".$appointmentHours[$key]."</p>";
			// $output .= $appointmentHours[$key];
			$output .= "</div>";
			
			// The div containing the reason for the appointment
			$output .= "<div class='userAppointDetails' id='userAptMessageDiv".$key."' >";
			$output .= "<p class='labelInfoStyle' >Reason for appointment: </p>";
			$output .= "<p class='labelValue' id='userAptMessage".$key."' >".$appointmentMessage[$key]."</p>";
			// $output .= $appointmentMessage[$key];
			$output .= "</div>";
			
			// The div containing the cancel button for the appointment
			$output .= "<div class='userAppointDetails' id='userBtnCtrl".$key."' >";
			// Show the cancel button
			$output .= "<input type='button' value='Cancel' id='cancelBtn".$key."' class='btnStyle1' onclick='openTextareaUser(this.id);' style='margin-right:10px;' />";
			$output .= "<input type='button' value='Hide' id='hideBtn".$key."' class='btnStyle1' onclick='hideTextareaUser(this.id);' style='display:none;'/>";
			$output .= "</div>";
			
			// Div containing form for textarea and submit button
			$output .= "<div class='userAppointDetails' id='reasonForCancelDiv".$key."' style='display:none;' >";
			$output .= "<form action='#' id='cancelForm".$key."' name='cancelForm".$key."'>";
			$output .= "<p style='margin:0px;' >Please provide a reason for canceling this created appointment. </p>";
			$output .= "<textarea id='reasonForCancel".$key."' name='reasonForCancel".$key."' cols='55' rows='4'></textarea>";
			$output .= "<br/>";
			$output .= "<input type='button' value='Submit' class='btnStyle1' id='submitReasonBtn".$key."' onclick='appointmentUserCanceled(this.id);' />";
			$output .= "</form>";
			$output .= "<div class='userAppointDetails' id='errorReportDiv".$key."' ></div>";
			$output .= "</div>";
			
			$output .= "</div>";
		}
	} else {
		$output .= "<div class='setUserAppointment' >";
		$output .= "<p>You have no appointment confirmed yet.</p>";
		$output .= "</div>";
	}
	
	return $output;
}
/* End of Functions controlling users appointment */

/* Begining of Security Functions */

// This function is a security function. It only allows parameters that are needed to be passed in into another operational function such as password check, or for checking variables from the get-globals or post-globals. A null value will be set if the parameters does not match or exceeds what is needed.
// It takes in an array input.
function allowed_get_params($allowed_params=[]) {
	$allowed_array = [];
	foreach($allowed_params as $param) {
		if(isset($_GET[$param]) && !empty($_GET[$param])) {
			$allowed_array[$param] = $_GET[$param];
		} else {
			$allowed_array[$param] = NULL;
		}
	}
	
	return $allowed_array;
}

function allowed_post_params($allowed_params=[]) {
	$allowed_array = [];
	foreach($allowed_params as $param) {
		/* if(isset($_POST[$param]) && !empty($_POST[$param])) {
			$allowed_array[$param] = $_POST[$param];
		} else {
			$allowed_array[$param] = NULL;
		} */

		if(array_key_exists($param, $_POST)) {
			$allowed_array[$param] = $_POST[$param];
		} else {
			$allowed_array[$param] = NULL;
		}
	}
	
	return $allowed_array;
}

// Sanitize for HTML output 
function h($string) {
	return htmlspecialchars($string);
}

// Sanitize for HTML output 
function h2($string) {
	return htmlentities($string);
}

// Sanitize for HTML output 
function h3($string) {
	return strip_tags($string);
}

// Sanitize for JavaScript output
function j($string) {
	return json_encode($string);
}

// Sanitize for use in a URL
function u($string) {
	return urlencode($string);
}

// Sanitize for use in a URL
function u2($string) {
	return addslashes($string);
}

// GET requests should not make changes
// Only POST requests should make changes

function request_is_get() {
	return $_SERVER['REQUEST_METHOD'] === 'GET';
}

function request_is_post() {
	return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// Must call session_start() before this loads

// Generate a token for use with CSRF protection.
// Does not store the token.
function csrf_token() {
	return md5(uniqid(rand(), TRUE));
}

// Generate and store CSRF token in user session.
// Requires session to have been started already.
function create_csrf_token() {
	global $database;
	$token = csrf_token();
  $_SESSION['csrf_token'] = $token;
 	$_SESSION['csrf_token_time'] = time();
 	/*
 	// Save token to the database
 	global $database;
 	$csrfObj = new CSRF_Token();
 	$csrfObj->token = $token;
 	$csrfObj->date_created = current_Date_Time();
 	if ($csrfObj->save()) {  	
 		$_SESSION['csrf_token_id'] = $database->insert_id();
 	} */
 	// echo "<br/> saved session token is: ".$_SESSION['csrf_token']."<br/>";
 	// echo "<br/> saved session time is: ".$_SESSION['csrf_token_time']."<br/>";
	return $token;
}

// Destroys a token by removing it from the session.
function destroy_csrf_token() {
  $_SESSION['csrf_token'] = null;
 	$_SESSION['csrf_token_time'] = null;
	return true;
}

// Return an HTML tag including the CSRF token 
// for use in a form.
// Usage: echo csrf_token_tag();
function csrf_token_tag() {	
	$token = create_csrf_token();

	return "<input id=\"csrf_token\" type=\"hidden\" name=\"csrf_token\" value=\"".$token."\"> <input id=\"csrf_token_time\" type=\"hidden\" name=\"csrf_token_time\" value=\"".$_SESSION['csrf_token_time']."\">";
}

// Returns true if user-submitted POST token is
// identical to the previously stored SESSION token.
// Returns false otherwise.
function csrf_token_is_valid() {
	if(isset($_POST['csrf_token'])) {
		$user_token = $_POST['csrf_token'];
		// echo "<br/> User token: <br/>".$user_token;
		$stored_token = $_SESSION['csrf_token'];
		// echo "<br/> Stored token: <br/>".$stored_token;

		// Using database
		/*
		if (isset($_SESSION['csrf_token_id'])) {
			$csrfObj = CSRF_Token::find_by_id($_SESSION['csrf_token_id']);
			$database_token = $csrfObj->token;
			$database_token_time = time($csrfObj->date_created);
			// echo "<br/> Database token: <br/>";
			// echo $database_token;
			// echo "<br/> Database token time: <br/>";
			// echo $database_token_time;

			return $database_token == $stored_token;
		} */
		return $user_token === $stored_token;
	} else {
		return false;
	}
}

// You can simply check the token validity and 
// handle the failure yourself, or you can use 
// this "stop-everything-on-failure" function. 
function die_on_csrf_token_failure() {
	if(!csrf_token_is_valid()) {
		die("CSRF token validation failed.");
	}
}

// Optional check to see if token is also recent
function csrf_token_is_recent() {
	$max_elapsed = 60 * 60 * 1; // 1 hour
	// echo "The token time value is: <br/>";
	// print_r($_SESSION);
	if(isset($_SESSION['csrf_token_time'])) {
		$stored_time = $_SESSION['csrf_token_time'];
		// echo "<br/> The stored time was: ".$stored_time." <br/>";
		return ($stored_time + $max_elapsed) >= time();
	} else {
		// Remove expired token
		destroy_csrf_token();
		return false;
	}
}

// Escapes a string to render it safe for SQL.
// Assumes your database connection is assigned to $database.
// Modify this if you use something else ($db, $sqli, $mysql, etc.).
function sql_prep($string) {
	global $database;
	if($database) {
		return mysqli_real_escape_string($database->get_connection(), $string);
	} else {
		// addslashes is almost the same, but not quite as secure.
		// Fallback only when there is no database connection available.
	 	return addslashes($string);
	}
}

// Use with request_is_post() to block posting from off-site forms
function request_is_same_domain() {
	if(!isset($_SERVER['HTTP_REFERER'])) {
		// No refererer sent, so can't be same domain
		return false;
	} else {
		$referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
		$raw_referer_host = $_SERVER['HTTP_REFERER'];
		$server_host = $_SERVER['HTTP_HOST'];

		// Uncomment for debugging
		// echo 'Request from: ' . $referer_host . "<br />";
		// echo 'Raw Request from: ' . $raw_referer_host . "<br />";
		// echo 'Request to: ' . $server_host . "<br />";
		
		if ($referer_host == $server_host) {
			return true;
		} elseif (strpos($raw_referer_host, $server_host) !== false) {
			// This will check for a situaton where the localhost is on a different port number other than 80.
			return true;
		} else {
			return false;
		}
	}
}

/* End of Security Functions */

?>