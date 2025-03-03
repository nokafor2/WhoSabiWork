<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Auth-Token');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
require_once("../includes/initialize.php");

/* require_once("../../includes/functions.php");
require_once("../../includes/session.php");
require_once("../../includes/database.php");
require_once("../../includes/user.php"); */

// If the user is already logged in, just forward them to the desired page without doing any authentication.
/* if($session->is_user_logged_in() OR $session->is_customer_logged_in()) {
  redirect_to("homePage.php");
} */

$message = "";
$username = "";
$security = new Security_Functions();
if (request_is_get() || request_is_post()) {
	// Check that only allowed parameters is passed into the url
	$get_params = allowed_get_params(['token', 'account']);

	// Eliminate HTML tags embedded in the form inputs
	foreach($get_params as $param) {
		// run htmlentities check on the parameters
		if(isset($get_params[$param])) {
			// run htmlentities check on the parameters
			$get_params[$param] = h2($param);
		} 
	}
	// Get the token from sent to the user's email from the GET global array
	$token = $get_params['token'];
	$account = $get_params['account'];
	echo "<br/> The account type is: ".$account."<br/>";
	global $session;
	if ($account == 'user') {
		// Confirm that the token sent is valid
		$user = new User();
		// $foundUser is a static variable 
		$foundUser = $user->find_user_with_token($token);
	} elseif ($account == 'customer') {
		// Confirm that the token sent is valid
		$customer = new Customer();
		// $foundCustomer is a static variable 
		$foundCustomer = $customer->find_customer_with_token($token);
	} else {
		// Not a valid account
		echo "Match not found";
		$session->message('This is not a valid account type.');
		// redirect_to('forgotPassword.php');
	}
	
	/* if(empty($foundUser) && empty($foundCustomer)) {
		// Token wasn't sent or didn't match a user.
		// $session->message("Improper attempt to modify password.");
		$session->message("Improper attempt to modify password.");
		// echo "This block ran.";
		// redirect_to('forgotPassword.php');
	} */

	if(request_is_post() && request_is_same_domain()) {
		// Check that only allowed parameters is passed into the form
		$post_params = allowed_post_params(['password', 'confirm_password', 'resetPassword', 'cusPassword', 'cusConfirmPassword', 'cusResetPassword']);

		// Eliminate HTML tags embedded in the form inputs
		foreach($post_params as $param) {
			// run htmlentities check on the parameters
			if(isset($post_params[$param])) {
				// run htmlentities check on the parameters
				$post_params[$param] = h2($param);
			} 
		}
		
		if(!$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()) {
			$message = "Invalid HTML form request. Try refreshing page.";
			// Login instances of imporoper login into the log file.
		} else {
		// CSRF tests passed--form was created by us recently.
			
			// Remember to give your form's submit tag a name="submit" attribute!
			echo "contents of post_params: <br/>";
			print_r($post_params);
			if (isset($post_params['resetPassword']) && !empty($foundUser)) { // Form has been submitted.
				// Get the user's passwords from the POST global variable.
				$password = trim($post_params['password']);
				$confirm_password = trim($post_params['confirm_password']);
				
				// validate user input
				$validate->validate_name_update();
				
				if (empty($validate->errors)) {
					// Check database to see if username exist.
						
					// The username has been sanitized to be passed into the database.
					$password = sql_prep($password);
					$confirm_password = sql_prep($confirm_password);
					$foundUser->password = $foundUser->password_encrypt($password);
					// $foundUser->reset_token = $foundUser->delete_reset_token($foundUser->username);
					$foundUser->reset_token = "";
					$foundUser->date_edited = $foundUser->current_Date_Time();
					// $savedPassword = $foundUser->update();
					
					// Condition if the password is saved
					if ($foundUser->update()) {
						$session = new Session();
						$session->message("Your password was successfully updated. Please refresh and login with your new password.");
						redirect_to("/WhoSabiWork/Public/loginPage.php");
					} else {
						// Password was not saved
						$message = "An error occurred while saving.";
					}
					
					// Message returned is the same whether the user 
					// was found or not, so that we don't reveal which 
					// usernames exist and which do not.
					// $session->message("A link to reset your password has been sent to the email address on file.");

				} else {
					$message = "User's password validation failed.";
				}
			} elseif (isset($post_params['cusResetPassword']) && !empty($foundCustomer)) {
				// Get the user's passwords from the POST global variable.
				$password = trim($post_params['cusPassword']);
				$confirm_password = trim($post_params['cusConfirmPassword']);
				
				// validate user input
				$validate->validate_name_update();
				
				if (empty($validate->errors)) {
					// Check database to see if username exist.
						
					// The username has been sanitized to be passed into the database.
					$password = sql_prep($password);
					$confirm_password = sql_prep($confirm_password);
					$foundCustomer->password = $foundCustomer->password_encrypt($password);
					// $foundCustomer->reset_token = $foundCustomer->delete_reset_token($foundCustomer->username);
					$foundCustomer->reset_token = "";
					$foundCustomer->date_edited = $foundCustomer->current_Date_Time();
					$savedPassword = $foundCustomer->update();
					
					// Condition if the password is saved
					if (isset($savedPassword)) {
						$session = new Session();
						$session->message("Your password was successfully updated. Please refresh and login with your new password.");
						redirect_to("/WhoSabiWork/Public/loginPage.php");
					} else {
						// Password was not saved
						$message = "An error occurred while saving.";
					}
					
					// Message returned is the same whether the user 
					// was found or not, so that we don't reveal which 
					// usernames exist and which do not.
					// $session->message("A link to reset your password has been sent to the email address on file.");

				} else {
					$message = "Customer's password validation failed.";
				}
			} else { 
				$message = "Select the right tab that belongs to your category. <br/>Enter your new password.";
			}
		}
		// This is a failed post login attempt, redirect to the login page again.
		// Initialize form variables again
		$password; $confirm_password;
	} /* else  {
		// form not submitted or was GET request
		$message = "Improper post request. Enter your new password.";
		// redirect to the login page.
		// redirect_to("/WhoSabiWork/Public/index.php");
		
		// Form has not been submitted.
		$password = ""; $confirm_password = "";
	} */
} else {
	// Log-in spurios attempt to get into a user's account
	$session->message("Improper page request.");
	redirect_to("/WhoSabiWork/index.php");
}

function displayTabbedPanelTab() {
	$get_params = allowed_get_params(['token', 'account']);
	$account = $get_params['account'];
	$output = "";
	if ($account === 'user') {
		$output .= "<li class='TabbedPanelsTab' tabindex='0'>User Reset Password</li>";
	} elseif($account === 'customer') {
		$output .= "<li class='TabbedPanelsTab' tabindex='0'>Customer Reset Password</li>";
	}
	
	return $output;
}
	
function displayTabbedPanelContent() {
	global $security;
	global $username;
	$get_params = allowed_get_params(['token', 'account']);
	$token = $get_params['token'];
	$account = $get_params['account'];
	$url = "resetPassword.php?token=".u($token)."&account=".u($account);
	$output = "";
	if ($account === 'user') {
		$output .= "
			<form action='".$url."' method='post' enctype='application/x-www-form-urlencoded' name='userForm' id='userForm'>
			".$security->csrf_token_tag()."
			  <p><!-- Password -->
				  <input name='password' type='password' id='password' size='60' maxlength='50' placeholder='Password' />
				  <br />
				  <!-- Confirm password -->
				  <input name='confirm_password' type='password' id='confirm_password' size='60' maxlength='50' placeholder='Confirm password' />
				  <br />
			  </p>
			  <input name='resetPassword' type='submit' id='resetPassword' value='Reset Password' />
			</form>
		";
	} elseif($account === 'customer') {
		$output .= "
			<form action='".$url."' method='post' enctype='application/x-www-form-urlencoded' name='customerForm' id='customerForm'>
			".$security->csrf_token_tag()."
			  <p><!-- Password -->
				  <input name='cusPassword' type='password' id='cusPassword' size='60' maxlength='50' placeholder='Password' />
				  <br />
				  <!-- Confirm password -->
				  <input name='cusConfirmPassword' type='password' id='cusConfirmPassword' size='60' maxlength='50' placeholder='Confirm password' />
				  <br />
			  </p>
			  <input name='cusResetPassword' type='submit' id='cusResetPassword' value='Reset Password' />
			</form>
		";
	}
	
	return $output;
}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reset Password</title>
<style type="text/css">
</style>
<!-- Bootstrap css file links -->
<link href="./stylesheets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="./stylesheets/bootstrap/css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
<link href="./stylesheets/bootstrap/css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />

<link href="../HomePageStyles.css" rel="stylesheet" type="text/css" />
<link href="./stylesheets/loginPageStyle.css" rel="stylesheet" type="text/css" />

<!-- Font Awesome Link -->
<link href="./stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />

<link href="../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>

<link href="../SpryAssets/LoginSpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/LoginSpryTabbedPanels.js" type="text/javascript"></script>

<script src="./javascripts/jRating/jquery/jquery.js" type="text/javascript"></script>

</head>


<body>
	<div id="topContainer">
		<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
		<?php include_layout_template('public_header.php'); ?>
	</div>
	
	<!-- Begining of Container -->
	<div id="container">
	  <!-- Begining of Main Section -->
	  <div class="mainLogin">
	    <!-- Begining of Login Panel -->
		<div class="loginPanel">
		  <!-- Begining of Tabbed Panel -->
		  <div id="TabbedPanels1" class="TabbedPanels">
			<ul class="TabbedPanelsTabGroup">
			  <!--
			  <li class="TabbedPanelsTab" tabindex="0">User Reset Password</li>
			  <li class="TabbedPanelsTab" tabindex="0">Customer Reset Password</li> -->
			  <?php echo displayTabbedPanelTab(); ?><br/>
			</ul>
			<!-- tabbed Pannels content Group div -->
			<div class="TabbedPanelsContentGroup">
			  <!-- Begining of User Login Form -->
			  <div class="TabbedPanelsContent">
				<!-- Displays message of successfully sign-in -->
				<?php echo $message; ?><br/>
				<!-- Display message of form error -->
				<?php echo $validate->form_errors_signIn($validate->errors); ?>
				<?php echo displayTabbedPanelContent(); ?><br/>
				<?php //$url = "resetPassword.php?token=".u($token)."&account=".u($account); ?>
				<!-- <form action="<?php // echo $url; ?>" method="post" enctype="application/x-www-form-urlencoded" name="userForm" id="userForm">
				  <?php // echo $security->csrf_token_tag(); ?>
				  <p><!-- Password --> <!--
					  <input name="password" type="password" id="password" size="60" maxlength="50" placeholder="Password" />
					  <br />
					  <!-- Confirm password --> <!--
					  <input name="confirm_password" type="password" id="confirm_password" size="60" maxlength="50" placeholder="Confirm password" />
					  <br />
				  </p>
				  <input name="resetPassword" type="submit" id="resetPassword" value="Reset Password" />
				</form> -->
			  </div>
			  <!-- End of User Login Form -->
			  
			  <!-- Begining of User Login Form -->
			  <div class="TabbedPanelsContent">
				<!-- Displays message of successfully sign-in -->
				<?php echo $message; ?><br/>
				<!-- Display message of form error -->
				<?php echo $validate->form_errors_signIn($validate->errors); ?>
				<?php echo displayTabbedPanelContent(); ?><br/>
				<?php // $url = "resetPassword.php?token=".u($token)."&account=".u($account); ?>
				<!-- <form action="<?php // echo $url; ?>" method="post" enctype="application/x-www-form-urlencoded" name="customerForm" id="customerForm">
				  <?php // echo $security->csrf_token_tag(); ?>
				  <p><!-- Password --> <!--
					  <input name="cusPassword" type="password" id="cusPassword" size="60" maxlength="50" placeholder="Password" />
					  <br />
					  <!-- Confirm password --> <!--
					  <input name="cusConfirmPassword" type="password" id="cusConfirmPassword" size="60" maxlength="50" placeholder="Confirm password" />
					  <br />
				  </p>
				  <input name="cusResetPassword" type="submit" id="cusResetPassword" value="Reset Password" />
				</form> -->
			  </div>
			  <!-- End of User Login Form -->
			  
			</div> <!-- End Tabbed Pannels Content Group div -->
		  </div> <!-- End of Tabbed Panel -->
		</div> <!-- End of Login Panel -->
      </div> <!-- End of Main Section -->

	  <!-- Display the footer section -->
	  <?php include_layout_template('public_footer.php'); ?>
	</div> <!-- End of Container -->
	
	<script type="text/javascript">
		var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
		// var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
	</script>
	
	<script type="text/javascript" src="./javascripts/usernameCheck.js"></script>
	<script type="text/javascript" src="./javascripts/passwordMatchCheck.js"></script>
	<script type="text/javascript" src="./javascripts/phoneNumberVerification.js"></script>
	<script src="./bootstrap/js/bootstrap.min.js" type="text/javascript" /></script>
	<script type="text/javascript" src="./javascripts/genericJSs.js"></script>
</body>
</html>

<?php if(isset($database)) { $database->close_connection(); } ?>