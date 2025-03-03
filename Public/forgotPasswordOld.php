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
$username = ""; // Can work for both user and customer forms
$security = new Security_Functions();
if(request_is_post() && request_is_same_domain()) {
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['username', 'submit', 'cusUsername', 'cusSubmit']);

	// Eliminate HTML tags embedded in the form inputs
	foreach($post_params as $param) {
		// run htmlentities check on the parameters
		if(isset($post_params[$param])) {
			// run htmlentities check on the parameters
			$post_params[$param] = h2($param);
		} 
	}
		
	if(!$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()) {
		$message = "Improper password reset attempt.";
		// Login instances of imporoper login into the log file.
	} else {
	// CSRF tests passed--form was created by us recently.
		
		// Remember to give your form's submit tag a name="submit" attribute!
		if (isset($post_params['submit'])) { // Form has been submitted.
			// Get the user's username from the POST global variable.
			$username = trim($post_params['username']);
			
			// validate user input
			// $validate->validate_username();
			$validate->validate_name_update();
			
			if (empty($validate->errors)) {
				// Check database to see if username exist.
					
				// The username has been sanitized to be passed into the database.
				$username = sql_prep($username);
				$userStatic = User::find_by_username($username);
				
				$message = "";
				// Condition if the user is found
				if (!empty($userStatic)) {
					// Username was found; okay to reset
					$userStatic->create_reset_token($username);
					$userStatic->email_reset_token($username);
					// $session->message("A link to reset your password has been sent to the email address on file.");
					redirect_to("/WhoSabiWork/index.php");
				} else {
					// username was not found in the database
					$message = "Username was not found.";
				}
				
				// Message returned is the same whether the user 
				// was found or not, so that we don't reveal which 
				// usernames exist and which do not.
				// $session->message("A link to reset your password has been sent to the email address on file.");

			} else {
				$message = "The username was not validated. ";
			}
		} elseif(isset($post_params['cusSubmit'])) {
			// Get the user's username from the POST global variable.
			$username = trim($post_params['cusUsername']);
			
			// validate user input
			// $validate->validate_username();
			$validate->validate_name_update();
			
			if (empty($validate->errors)) {
				// Check database to see if username exist.
					
				// The username has been sanitized to be passed into the database.
				$username = sql_prep($username);
				$customerStatic = Customer::find_by_username($username);
				
				$message = "";
				// Condition if the user is found
				if (!empty($customerStatic)) {
					// Username was found; okay to reset
					$customerStatic->create_reset_token($username);
					$customerStatic->email_reset_token($username);
					// $session->message("A link to reset your password has been sent to the email address on file.");
					redirect_to("/WhoSabiWork/index.php");
				} else {
					// username was not found in the database
					$message = "Username was not found.";
				}
				
				// Message returned is the same whether the user 
				// was found or not, so that we don't reveal which 
				// usernames exist and which do not.
				// $session->message("A link to reset your password has been sent to the email address on file.");

			} else {
				$message = "The username was not validated. ";
			}
		} else { 
			$message = "Enter username to reset its password.";
		}
	}
	// This is a failed post login attempt, redirect to the login page again.
	// Initialize form variables again
	$username; 
} else {
	// form not submitted or was GET request
	$message = "Enter username to reset its password.";
	// redirect to the login page.
	// redirect_to("/WhoSabiWork/Public/index.php");
	
	// Form has not been submitted.
	$username = "";
}

function displayTabbedPanelTab() {
	$get_params = allowed_get_params(['request']);
	$request = $get_params['request'];
	$output = "";
	if ($request === 'user') {
		$output .= "<li class='TabbedPanelsTab' tabindex='0'>User Reset Password</li>";
	} elseif($request === 'customer') {
		$output .= "<li class='TabbedPanelsTab' tabindex='0'>Customer Reset Password</li>";
	}
	
	return $output;
}
	
function displayTabbedPanelContent() {
	global $security;
	global $username;
	$get_params = allowed_get_params(['request']);
	$request = $get_params['request'];
	$output = "";
	if ($request === 'user') {
		$output .= "
			<form action='' method='post' enctype='application/x-www-form-urlencoded' name='userForgotPassword' id='userForgotPassword'>".$security->csrf_token_tag()."
			  <p><!-- Username -->
				<input name='username' type='text' id='username' size='60' maxlength='50' placeholder='Username' value='".htmlentities($username)."'/>
				<br />
			  </p>
			  <input name='submit' type='submit' id='submit' value='Submit' />
			</form>		
		";
	} elseif($request === 'customer') {
		$output .= "
			<form action='' method='post' enctype='application/x-www-form-urlencoded' name='cusForgotPassword' id='cusForgotPassword'>".$security->csrf_token_tag()."
			  <p><!-- Username -->
				<input name='cusUsername' type='text' id='cusUsername' size='60' maxlength='50' placeholder='Username' value='".htmlentities($username)."'/>
				<br />
			  </p>
			  <input name='cusSubmit' type='submit' id='cusSubmit' value='Submit' />
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
<title>Forgot Password</title>
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
			  <!-- <li class="TabbedPanelsTab" tabindex="0">User Reset Password</li>
			  <li class="TabbedPanelsTab" tabindex="0">Customer Reset Password</li> -->
			  <?php echo displayTabbedPanelTab(); ?>
			</ul>
			<!-- tabbed Pannels content Group div -->
			<div class="TabbedPanelsContentGroup">
			  <!-- Begining of User Login Form -->
			  <div class="TabbedPanelsContent">
				<!-- Displays message of successfully sign-in -->
				<?php echo $message; ?><br/>
				<!-- Display message of form error -->
				<?php echo $validate->form_errors_signIn($validate->errors); ?>
				<?php echo output_message($sessionMessage); ?>
				<?php echo output_message($sessionMessage); ?>
				<?php echo displayTabbedPanelContent(); ?>
				<!-- <form action="" method="post" enctype="application/x-www-form-urlencoded" name="userForgotPassword" id="userForgotPassword">
				  <?php 
						
						// echo $security->csrf_token_tag(); 
				  ?>
				  <p><!-- Username --><!-- 
					<input name="username" type="text" id="username" size="60" maxlength="50" placeholder="Username" value="<?php // echo htmlentities($username); ?>"/>
					<br />
				  </p>
				  <input name="submit" type="submit" id="submit" value="Submit" />
				</form> -->
			  </div>
			  <!-- End of User Login Form -->
			  
			  <!-- Begining of User Login Form -->
			  <div class="TabbedPanelsContent">
				<!-- Displays message of successfully sign-in -->
				<?php echo $message; ?><br/>
				<!-- Display message of form error -->
				<?php echo $validate->form_errors_signIn($validate->errors); ?>
				<?php echo output_message($sessionMessage); ?>
				<?php echo displayTabbedPanelContent(); ?>
				<!--
				<form action="" method="post" enctype="application/x-www-form-urlencoded" name="cusForgotPassword" id="cusForgotPassword">
				  <?php 
						
						// echo $security->csrf_token_tag(); 
				  ?>
				  <p><!-- Username --> <!--
					<input name="cusUsername" type="text" id="cusUsername" size="60" maxlength="50" placeholder="Username" value="<?php // echo htmlentities($username); ?>"/>
					<br />
				  </p>
				  <input name="cusSubmit" type="submit" id="cusSubmit" value="Submit" />
				</form>
				-->
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