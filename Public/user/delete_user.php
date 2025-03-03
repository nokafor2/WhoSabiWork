<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_user_logged_in()) { redirect_to("loginPage.php"); } ?>
<?php
  // must have an ID, get the ID from the $_GET global variable which will be used to delete the user
  if(empty($_GET['id'])) {
  	$session->message("No User ID was provided.");
    redirect_to('userEditPage.php?id=$session->user_id');
  }

  // Find the photograph by its ID
  $user = User::find_by_id($_GET['id']);
  // Check if the photo was found and if it was destroyed
  if($user && $user->destroy()) {
    $session->message("The {$user->full_name()} was deleted.");
    redirect_to('../homePageEdit2.php');
  } else {
    $session->message("The user could not be deleted.");
    redirect_to('userEditPage.php');
  }
  
?>

<?php // Close the database when done deleting
if(isset($database)) { $database->close_connection(); } ?>
