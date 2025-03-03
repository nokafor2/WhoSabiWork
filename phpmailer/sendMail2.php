<?php
require_once("../includes/initialize.php");
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    // $mail->SMTPDebug = 2;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'mail.whosabiwork.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'support@whosabiwork.com';                     // SMTP username
    $mail->Password   = 'oHL=p[aNHAV1';                               // SMTP password
    $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('support@whosabiwork.com', 'WhoSabiWork.com');
    $mail->addAddress('nokafor2@gmail.com');     
	// $mail->addAddress('akuchukwu042@gmail.com');
	
    // $mail->addReplyTo('info@example.com', 'Information');

    // $body = '<img src="/WhoSabiWork/Images/WhoSabiWorkL1.jpg" width="200" height="100" alt="WhoSabiWork Logo"  />';
    // $body .= '\\n';
    $body = '<img src="cid:whosabiworkLogo">';

	$body .= "<p><strong>Hello baby!!!</strong> WhoSabiWork email is up and running. Am loving it. I love you so much, missing you a 100 folds here, kiss kiss. </p>";

    $mail->AddEmbeddedImage('../Images/WhoSabiWorkL1.jpg', 'whosabiworkLogo');
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Brought to you from WhoSabiWork';
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

	set_time_limit(300);
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>