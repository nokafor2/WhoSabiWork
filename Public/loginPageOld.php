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
$login_check = new Denied_Login();
if(request_is_post() && request_is_same_domain()) {
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['username', 'password', 'first_name', 'last_name', 'phone_number', 'email', 'user_register', 'signIn']);

	// Eliminate HTML tags embedded in the form inputs
	foreach($post_params as $param) {
		// run htmlentities check on the parameters
		if(isset($post_params[$param])) {
			// run htmlentities check on the parameters
			$post_params[$param] = h2($param);
		} 
	}
	
	if(!$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()) {
		$message = "Improper login attempt.";
		// Login instances of imporoper login into the log file.
	} else {
	// CSRF tests passed--form was created by us recently.
		
		// Remember to give your form's submit tag a name="submit" attribute!
		if (isset($post_params['signIn'])) { // Form has been submitted.
			
			// Get the user's username and password from the POST global variable.
			$username = trim($post_params['username']);
			$password = trim($post_params['password']);
			
			// validate_user();
			$validate->validate_user();
			
			if (empty($validate->errors)) {
				// Check database to see if username/password exist.
				// Check if the person signing in is a user or a customer
				$isuser = TRUE;
				$iscustomer = FALSE;
				if ($isuser) {
					// check if the person is a user
					
					// The username has been sanitized to be passed into the database.
					$username = sql_prep($username);
					$password = sql_prep($password);
					
					// After you are sure you have a username, throttle before checking for the username and password in the database
					$throttle_delay = $login_check->throttle_failed_user_logins($username);
					if($throttle_delay > 0) {
						// Throttled at the moment, try again after delay period
						$message  = "Too many failed logins. ";
						$message .= "You must wait {$throttle_delay} minutes before you can attempt another login.";
						
					} else { 
						// $found_user = User::authenticate($username, $password);
						$userStatic = User::find_by_username($username);
						$user = new User();
						$found_user = $user->authenticate($username, $password);
				
						// After authentication, you can examine the user further before logging them in. Such check could be to find out it the user has paid their dues.
					
						// Condition if the user is found
						if ($found_user) {
							$iscustomer = FALSE;
							// Clear any attempt of login trial after authentication is passed
							$login_check->clear_user_failed_logins($userStatic->username);
							// If the user is found, tell session to log them in.
							$session->user_login($userStatic);
							$session->message("Welcome back {$found_user->full_name()}.");
							// Create a log that the user has logged into the system
							user_log_action('Login', "{$userStatic->username} logged in.");
							redirect_to("/WhoSabiWork/index.php");
						} else {
							$iscustomer = TRUE;
							// Record a failed login of the user in the database
							$login_check->record_user_failed_login($userStatic->username);
							// username/password combo was not found in the database
							$message = "Username/password combination incorrect.";
						}
						// set $iscustomer to false in the authenticating condition if a user was retrieved or true if a user was not retrieved
					}
				} 
				if ($iscustomer) {
					// Check if the person is a technician
					
					// The username has been sanitized to be passed into the database.
					$username = sql_prep($username);
					$password = sql_prep($password);
					
					// $found_customer = Customer::authenticate($username, $password);
					$customerStatic = Customer::find_by_username($username);
					$customer = new Customer();
					$found_customer = $customer->authenticate($username, $password);
			
					// After authentication, you can examine the user further before logging them in. Such check could be to find out it the user has paid their dues.
				
					// Condition if the user is found
					if ($found_customer) {
						// If the user is found, tell session to log them in.
						$session->customer_login($customerStatic);
						$session->message("Welcome back {$found_customer->full_name()}.");
						// Create a log that the user has logged into the system
						cus_log_action('Login', "{$customerStatic->username} logged in.");
						redirect_to("/WhoSabiWork/index.php");
					} else {
						// username/password combo was not found in the database
						$message = "Username/password combination is incorrect.";
					}
					// set $iscustomer to false in the authenticating condition if a user was retrieved or true if a user was not retrieved
				}
				
			} else {
				$message = "There was a validation error. ";
			}
		} elseif (isset($post_params['user_register'])) {
			// Get the registration details for the user.
			$first_name = trim($post_params['first_name']);
			$last_name = trim($post_params['last_name']);
			$username = trim($post_params['username']);
			$password = trim($post_params['password']);
			$phone_number = trim($post_params['phone_number']);
			$email = trim($post_params['email']);
			
			// Sanitize inputs from the form to be passed into the database.
			$first_name = sql_prep($first_name);
			$last_name = sql_prep($last_name);
			$username = sql_prep($username);
			$password = sql_prep($password);
			$phone_number = sql_prep($phone_number);
			$email = sql_prep($email);
			
			
			// Validate the user registration before saving in the database.
			$validate->validate_user_register();
			if (isset($_SESSION['phoneValidation'])) {
				$validate->errors['phoneValidation'] = $_SESSION['phoneValidation'];
			}
			unset($_SESSION['phoneValidation']);
			
			if (empty($validate->errors)) {
				// $message = "There was no error, save the user.";
				
				$user = new User();
				$user->customers_id = NULL;
				$user->first_name = $first_name;
				$user->last_name = $last_name;
				$user->username = $username;
				// password_hash(); PHP default function for hashing password
				$user->password = $user->password_encrypt($password);
				// $user->password = password_hash($password);
				$user->phone_number = $phone_number;
				$user->phone_validated = TRUE;
				$user->user_email = $email;
				$user->account_status = 'active';
				$user->date_created = $user->current_Date_Time();
				$user->date_edited = $user->current_Date_Time();
				
				if($user->save()) {
					// Success
					$message = "The user account was successfully created. You can now login.";
					// This message should be saved in the session.
					$session->message("Congratulations ".$first_name." ".$last_name.". Your user account was successfully created.");
					$newUser = User::find_by_id($database->insert_id());
					// Tell session to log them in.
					$session->user_login($newUser);
					// Create a log that the user has logged into the system
					user_log_action('Register', "{$newUser->username} created a user account.");
					admin_log_action('Register', "{$newUser->username} created a user account.");
					redirect_to('user/userEditPage.php?id='.$database->insert_id());
				} else {
					$message = "An error occurred while saving.";
				}
			
			} else {
				$message = "There was an error validating form inputs. ";
			}
		} else { 
			$message = "Please log in.";
		}
	}
	// This is a failed post login attempt, redirect to the login page again.
	// $message .= " This is a post request.";
	// Initialize form variables again
	$username; $first_name; $first_name; $last_name; $phone_number; $email; $password;
} else {
	// form not submitted or was GET request
	$message = "Please login.";
	// $message = "This is a get request.";
	// redirect to the login page.
	// redirect_to("/WhoSabiWork/Public/index.php");
	
	// Form has not been submitted.
	$username = ""; $first_name = ""; $last_name = ""; $phone_number = ""; $email = ""; $password = "";
}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login / User Register</title>
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
			  <li class="TabbedPanelsTab" tabindex="0">Sign in</li>
			  <li class="TabbedPanelsTab" tabindex="0">Create User Account</li>
			</ul>
			<!-- tabbed Pannels content Group div -->
			<div class="TabbedPanelsContentGroup">
			  
			  <div class="TabbedPanelsContent">
				<!-- Displays message of successfully sign-in -->
				<?php echo $message; ?><br/>
				<!-- Display message of form error -->
				<?php echo $validate->form_errors_signIn($validate->errors); ?>
				<form action="" method="post" enctype="application/x-www-form-urlencoded" name="SignIn" id="SignIn">
				  <?php 
						
						echo $security->csrf_token_tag(); 
				  ?>
				  <p><!-- Username -->
					<input name="username" type="text" id="username" size="60" maxlength="50" placeholder="Username" value="<?php echo htmlentities($username); ?>"/>
					<br />
					<!-- Password -->
					<input name="password" type="password" id="password" size="60" maxlength="50" placeholder="Password" />
					<br />
				  </p>
				  <input name="signIn" type="submit" id="signIn" value="Sign in" />
				</form>
			  </div>
			  <div class="TabbedPanelsContent">
				<?php echo $message; ?><br/>
				<?php echo $validate->form_errors_signIn($validate->errors); ?>
				  <form id="form1" name="form1" method="post" action="">
					<?php 
						 
						echo $security->csrf_token_tag(); 
						/*
						echo "The generated CSRF token is: <br/>";
						print_r($session->get_csrf_tokens());
						echo "<br/>";
						echo "The generated CSRF time is: <br/>";
						print_r($session->get_csrf_tokens_time());
						echo "<br/>";
						
						echo "Post gloabal variables are: <br/>";
						print_r($_POST);
						echo "<br/>";
						echo "Session gloabal variables are: <br/>";
						print_r($_SESSION);
						 */
					?>
					<p><!-- First name -->
					  <input name="first_name" type="text" id="first_name_user" size="60" maxlength="50" placeholder="First name" value="<?php echo htmlentities($first_name);?>" />
					  <br />
					  <!-- Last name -->
					  <input name="last_name" type="text" id="last_name_user" size="60" maxlength="50" placeholder="Last name" value="<?php echo htmlentities($last_name);?>" />
					  <br />
					  <!-- Username -->
					  <input name="username" type="text" id="username_user" size="60" maxlength="50" placeholder="Username" value="<?php echo htmlentities($username);?>" onfocus="usernameCheck();" onblur="stopUsernameCheck();"/>
					  <br />
					  <div id="usernameMessage" style="color:red; display:none; margin:0px;"></div>
					  <!-- Password -->
					  <input name="password" type="password" id="password_user" size="60" maxlength="50" placeholder="Password" />
					  <br />
					  <!-- Confirm password -->
					  <input name="confirm_password" type="password" id="confirm_password_user" size="60" maxlength="50" placeholder="Confirm password" />
					  <br />
					  <div id="passwordMessage" style="color:red; display:none; margin:0px;"></div>
					  <!-- Phone number -->
					  <input name="phone_number" type="text" id="phone_number_user" size="60" maxlength="15" placeholder="Phone number" value="<?php echo htmlentities($phone_number);?>" />                      
					  <input name="verifyNumber" type="button" id="verifyNumber" value="Verify phone number" />
					  <br />
					  <div id="confirmationCodeDiv" style="display:none">
					  <!-- Enter confirmation code -->
					  <input name="verifyCode" type="text" id="verifyCode" size="15" maxlength="50" placeholder="Enter confirmation code" />
					  <input name="enterCode" type="button" id="enterCode" value="Enter code" />
					  <div id="messageUpdate" style="color:red; clear:both;"></div>
					  </div>
					  <!-- Email address -->
					  <input name="email" type="text" id="email_user" size="60" maxlength="50" placeholder="Email address" value="<?php echo htmlentities($email);?>" />
					  <br />
					</p>
					<input name="user_register" type="submit" id="user_register" value="Register" style="display:none"/>
				  </form>
				</div>
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