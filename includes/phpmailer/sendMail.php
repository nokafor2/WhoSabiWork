<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Send mail to recepients
function sendMailFxn($fromEmail='support@whosabiwork.com', $fromName='whosabiwork.com', $to='', $title='', $body='') {
	// echo "Message to be sent in email: ".$body;
	// Load Composer's autoloader
	// require 'vendor/autoload.php';

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

		// Recipients
		$mail->setFrom($fromEmail, $fromName);
		$mail->addAddress($to);     
		// Use the address function to generate more addresses to send to.
		
		// $mail->addReplyTo('info@example.com', 'Information');

		// Embed the logo image
		$logoPath = navigateToImagesFolder().'utilityImages/WhoSabiWorkL1.jpg';
		$mail->AddEmbeddedImage($logoPath, 'whoSabiWorkLogo', $logoPath);

		// Content
		$mail->isHTML(true); // Set email format to HTML
		$mail->Subject = $title;
		$mail->Body    = $body;
		$mail->AltBody = strip_tags($body);		
		
		$session = new Session();
		
		if ($mail->send()) {
			// $session->message('An email has been sent to your email address on file.');
			// $_SESSION['emailSuccess'] = 'An email has been sent to your email address on file.';
			return true;
		}
		// Create a log for message sent.
		// echo 'Message has been sent';
	} catch (Exception $e) {
		// Log this error into the log file.
		$_SESSION['emailError'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		return false;
	}
}


?>