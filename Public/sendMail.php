<?php
// Recipient
$to = 'nokafor2@gmail.com';

// Subject
$subject = 'This is our test mail';

// Message
$message = '<h1>Hi there.</h1><p>Thanks for testing!</p>';

// Headers
$headers = 'From: The Sender Name <support@whosabiwork.com>\r\n';
$headers .= 'Reply-To: support@whosabiwork.com\r\n';
// Include this line if you want to send html
$headers .= 'Content-type: text/html\r\n';

// Send email
mail($to, $subject, $message, $headers);

?>