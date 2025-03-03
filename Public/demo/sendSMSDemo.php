<?php 
require_once("../../includes/initialize.php");

$phoneNumber = '08057368560';
$message = 'Message testing from WhoSabiWork';
// sendSMSCode($phoneNumber, $message);
if (sendSMSCode($phoneNumber, $message)) {
	echo "Message was sent successfully.";
} else {
	echo "Message could not be sent. <br/>";
	// print_r($_SESSION);
}

/*
$to = 'nokafor2@gmail.com';
$title = 'Error Mail Testing';
$body = $message;
$outcome = sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='WhoSabiWork.com', $to, $title, $body);
sendEmailOutcome($outcome);
*/
/*
echo "<br/>";
print_r($_SESSION);
echo output_message($_SESSION['emailMessage']);
// Unset the session message so the next time its used, it will not print out the old value.
unset($sessionMessage);
unset($_SESSION['emailSuccess'], $_SESSION['emailError'], $_SESSION['emailMessage']);
// unset($_SESSION['emailMessage']);
// unset($_SESSION['emailError']);
echo "<br/>";
echo "<br/>";
print_r($_SESSION);
*/

// How to get the filename of a running file
/* if (isset($_SESSION['xwirelessData'])) {
	echo "<br/>";
	print_r($_SESSION['xwirelessData']);
} */

/*
echo dirname(__FILE__)."<br/>";
$fileInfo = pathinfo(dirname(__FILE__));
print_r(array_keys($fileInfo));
echo "\\n";
echo "The dirname is: ".$fileInfo['dirname']."<br/>";
echo "The basename is: ".$fileInfo['basename']."<br/>";
// echo "The extension name is ".$fileInfo['extension']."<br/>";
echo "The filename is ".$fileInfo['filename']."<br/>";

echo "<br/>";
echo "<br/>";

// scriptPath();
echo scriptPath();
echo "<br/>";
echo "<br/>";

$pathArray = explode("\\", scriptPath());
print_r($pathArray);

echo "<br/>";
echo "<br/>";
echo "basename is: ".basename(scriptPath());

echo "<br/>";
echo "<br/>";
echo "basename is: ".extension(scriptPath());
*/

?>