<?php
require_once realpath("../../includes/vendor/autoload.php"); 

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$twilioSID = getenv("TWILIO_ACCOUNT_SID");

echo $twilioSID;


?>