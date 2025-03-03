<?php
require_once("../../includes/initialize.php");

$tabIndex = $_SESSION['lastTabIndex'];
echo json_encode($tabIndex);

?>