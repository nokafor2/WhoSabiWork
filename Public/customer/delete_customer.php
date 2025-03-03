<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_customer_logged_in()) { redirect_to("loginPage.php"); } ?>
<?php
	// must have an ID, get the ID from the $_GET global variable which will be used to delete the user
  if(empty($_GET['id'])) {
  	$session->message("No Customer ID was provided.");
    redirect_to('customerEditPage2.php?id=$session->customer_id');
  }

  // Find the customer by its ID
  $customer = Customer::find_by_id($_GET['id']);
  // Check if the customer was found and if it was destroyed
  // Delete all the records of the customer from the other related tables.
  if($customer && $customer->deactivate($_GET['id'])) {
    $session->message("The {$customer->full_name()} was deleted.");
    redirect_to('../homePage.php');
  } else {
    $session->message("The user could not be deleted.");
    redirect_to('customerEditPage2.php?id='.$_GET['id']);
  }
  
?>

<?php // Close the database when done deleting
if(isset($database)) { $database->close_connection(); } ?>
