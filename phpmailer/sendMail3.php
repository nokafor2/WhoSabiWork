<?php 
require_once("vendor/phpmailer/phpmailer/src/PHPMailer.php");
require_once("vendor/phpmailer/phpmailer/src/SMTP.php");
require_once("vendor/phpmailer/phpmailer/src/Exception.php");
require_once("vendor/phpmailer/phpmailer/src/OAuth.php");

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
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('support@whosabiwork.com', 'Nna-ayua');
    $mail->addAddress('nokafor2@gmail.com');     
    // $mail->addReplyTo('info@example.com', 'Information');

	$body = "<p><strong>Hello!!!</strong> This is my first email with PHPMailer.</p>";

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'This is a test email.';
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>