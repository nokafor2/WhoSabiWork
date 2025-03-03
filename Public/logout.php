<?php require_once("../includes/initialize.php"); ?>

<?php
$session->logout();
$session->message("You logged out successfully. Have a nice day.");
redirect_to("/Public/index.php");

?>