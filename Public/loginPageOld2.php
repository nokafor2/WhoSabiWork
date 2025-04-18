<?php
/* header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Auth-Token');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS'); */
require_once("../includes/initialize.php");

/* require_once("../../includes/functions.php");
require_once("../../includes/session.php");
require_once("../../includes/database.php");
require_once("../../includes/user.php"); */

$message = "";
$username = "";
$security = new Security_Functions();
// 
if(request_is_post() && request_is_same_domain()) {
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['username', 'password', 'cusUsername', 'cusPassword', 'username_user', 'password_user', 'first_name', 'last_name', 'phone_number', 'email', 'userSignIn', 'customerSignIn', 'user_register']);

	// Eliminate HTML tags embedded in the form inputs
	foreach($post_params as $param) {
		// run htmlentities check on the parameters
		if(isset($post_params[$param])) {
			// run htmlentities check on the parameters
			$post_params[$param] = h2($param);
		} 
	}
	
	// || !$security->csrf_token_is_recent()
	if(!$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()) {
		$message = "Improper login attempt.";
		// Login instances of imporoper login into the log file.
	} else {
	// CSRF tests passed--form was created by us recently.
		
		// Remember to give your form's submit tag a name="submit" attribute!
		if (isset($post_params['userSignIn'])) { // Form has been submitted.
			
			// Get the user's username and password from the POST global variable.
			$username = trim($post_params['username']);
			$password = trim($post_params['password']);
			
			// validate_user();
			$validate->validate_user();
			
			if (empty($validate->errors)) {
				// Check database to see if username/password exist.
					
				// The username has been sanitized to be passed into the database.
				$username = sql_prep($username);
				$password = sql_prep($password);
				$login_check = new User_Failed_Login();
				
				// After you are sure you have a username, throttle before checking for the username and password in the database
				$user_throttle_delay = $login_check->throttle_failed_user_logins($username);
				if($user_throttle_delay > 0) {
					// Throttled at the moment, try again after delay period
					$message  = "Too many failed logins. ";
					$message .= "You must wait {$user_throttle_delay} minutes before you can attempt another login.";
					
				} else { 
					// $found_user = User::authenticate($username, $password);
					$userStatic = User::find_by_username($username);
					$user = new User();
					$found_user = $user->authenticate($username, $password);
			
					// After authentication, you can examine the user further before logging them in. Such check could be to find out it the user has paid their dues.
				
					// Condition if the user is found
					if ($found_user) {
						// Clear any attempt of login trial after authentication is passed
						$login_check->clear_user_failed_logins($userStatic->username);
						// If the user is found, tell session to log them in.
						$session->user_login($userStatic);
						$session->message("Welcome back {$found_user->full_name()}.");
						// Create a log that the user has logged into the system
						user_log_action('Login', "{$userStatic->username} logged in.");
						redirect_to("/WhoSabiWork/index.php");
					} else {
						// Record a failed login of the user in the database
						if (!empty($userStatic)){
							$login_check->record_user_failed_login($userStatic->username);
						} else {
							$login_check->record_user_failed_login($username);
						}
						// username/password combo was not found in the database
						$message = "Username/password combination incorrect.";
					}
					// set $iscustomer to false in the authenticating condition if a user was retrieved or true if a user was not retrieved
				}
			} else {
				$message = "There was a validation error. ";
			}
		} elseif (isset($post_params['customerSignIn'])) { // Form has been submitted.
			
			// Get the user's username and password from the POST global variable.
			$username = trim($post_params['cusUsername']);
			$password = trim($post_params['cusPassword']);
			
			// validate_user();
			$validate->validate_user();
			
			if (empty($validate->errors)) {
				// Check database to see if username/password exist.

				// The username has been sanitized to be passed into the database.
				$username = sql_prep($username);
				$password = sql_prep($password);
				
				$cus_login_check = new Customer_Failed_Login();
				
				// After you are sure you have a username, throttle before checking for the username and password in the database
				$cus_throttle_delay = $cus_login_check->throttle_failed_customer_logins($username);
				if($cus_throttle_delay > 0) {
					// Throttled at the moment, try again after delay period
					$message  = "Too many failed logins. ";
					$message .= "You must wait {$cus_throttle_delay} minutes before you can attempt another login.";
					
				} else { 
					// $found_customer = Customer::authenticate($username, $password);
					$customerStatic = Customer::find_by_username($username);
					// echo "<br/> contents of customer static <br/>";
					// print_r($customerStatic)."<br/>";
					$customer = new Customer();
					$found_customer = $customer->authenticate($username, $password);
			
					// After authentication, you can examine the user further before logging them in. Such check could be to find out it the user has paid their dues.
				
					// Condition if the user is found
					if ($found_customer) {
						// Clear any attempt of login trial after authentication is passed
						$cus_login_check->clear_customer_failed_logins($customerStatic->username);
						// If the user is found, tell session to log them in.
						$session->customer_login($customerStatic);
						$session->message("Welcome back {$found_customer->full_name()}.");
						// Create a log that the user has logged into the system
						cus_log_action('Login', "{$customerStatic->username} logged in.");
						redirect_to("/WhoSabiWork/index.php");
					} else {
						// Record a failed login of the user in the database
						if (!empty($customerStatic)){
							$cus_login_check->record_customer_failed_login($customerStatic->username);
						} else {
							$cus_login_check->record_customer_failed_login($username);
						}
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
			$username = trim($post_params['username_user']);
			$password = trim($post_params['password_user']);
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
			// This validation is
			/* if (isset($_SESSION['phoneValidation'])) {
				$validate->errors['phoneValidation'] = $_SESSION['phoneValidation'];
			}
			unset($_SESSION['phoneValidation']); */
			
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
				// Don't validate phone number yet.
				// $user->phone_validated = TRUE;
				$user->user_email = $email;
				$user->account_status = 'active';
				$user->date_created = $user->current_Date_Time();
				$user->date_edited = $user->current_Date_Time();
				// echo "Account was created.";
				
				if($user->save()) {
					// Success
					$message = "The user account was successfully created. You can now login.";
					// This message should be saved in the session.
					$session->message("Congratulations ".$first_name." ".$last_name.". Your user account was successfully created.");
					$newUser = User::find_by_id($database->insert_id());
					// Send an email to the user.
					if (isset($email) && !empty($email)) {
						composeEmail();
					}
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
	// Destroy the token after failure
	$security->destroy_csrf_tokens();
	// This is a failed post login attempt, redirect to the login page again.
	// $message .= " This is a post request.";
	// Initialize form variables again
	$first_name = trim($post_params['first_name']);
	$last_name = trim($post_params['last_name']);
	$username = trim($post_params['username_user']);
	$password = trim($post_params['password_user']);
	$phone_number = trim($post_params['phone_number']);
	$email = trim($post_params['email']);
} else {
	// form not submitted or was GET request
	$message = "Please login.";
	// $message = "This is a get request.";
	// redirect to the login page.
	// redirect_to("/WhoSabiWork/Public/index.php");
	
	// Form has not been submitted.
	$username = ""; $first_name = ""; $last_name = ""; $phone_number = ""; $email = ""; $password = "";
}
// Destroy the token after failure
$security->destroy_csrf_tokens();

function composeEmail() {
	global $email; global $first_name; global $last_name; global $username; global $password;
	
	$from = 'support@whosabiwork.com';
	$sender = 'WhoSabiWork.com';
	// $to = $email;
	$title = 'Welcome to WhoSabiWork';
	$body = '<p>Dear '.$first_name.' '.$last_name.', </p>
	
	<p>Thanks for taking the time to create a WhoSabiWork account.</p>
	
	<p>Your username is <strong>'.$username.'</strong>, and your password is <strong>'.$password.'</strong>. Visit our website <strong><a href="WhoSabiWork.com/Public/loginPage.php">login page</a></strong> and try logging in to ensure that it works. If you have any problem logging in, don\'t fail to contact our service support at support@whosabiwork.com.</p>
	
	<p>WhoSabiWork is committed to providing its users an eclectic source to find skilled artisans, mechanics and spare part sellers. Also, it helps its registered customers get advertised to users so that the outreach of their business will reach the global populace.</p>
	
	<p>If you have any questoin, you can contact us at our website on the <strong><a href="WhoSabiWork.com/Public/contactUs.php">contact page</a></strong>, or email us at support@whosabiwork.com. Thank you for your patronage.</p>
	
	<p>WhoSabiWork.com</p>';
	
	return sendMail($from, $sender, $email, $title, $body);
}


/* echo "<br/> Before the form page runs, these are the contents of the session and post global variable <br/>";
echo "Post gloabal variables are: <br/>";
print_r($_POST);
echo "<br/>";
echo "Session gloabal variables are: <br/>";
print_r($_SESSION); */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login / User Register</title>
<style type="text/css">
</style>
<!-- Bootstrap css file links -->
<!-- <link href="./stylesheets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> -->
<!-- <link href="./stylesheets/bootstrap/css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" /> -->
<!-- <link href="./stylesheets/bootstrap/css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" /> -->

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
			  <li class="TabbedPanelsTab" tabindex="0">User Sign In</li>
			  <li class="TabbedPanelsTab" tabindex="0">Customer Sign In</li>
			  <!-- <li class="TabbedPanelsTab" tabindex="0">Create User Account</li> -->
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
				<form action="" method="post" enctype="application/x-www-form-urlencoded" name="userForm" id="userForm">
				  <?php echo $security->csrf_token_tag(); ?>
				  <p><!-- Username -->
					<input name="username" type="text" id="username" size="60" maxlength="50" placeholder="Username" value="<?php echo htmlentities($username); ?>"/>
					<br />
					<!-- Password -->
					<input name="password" type="password" id="password" size="60" maxlength="50" placeholder="Password" />
					<br />
					<a class='linkStyleButton2' href="forgotPassword.php?request=user">I forgot my password.</a>
				  </p>
				  <input name="userSignIn" type="submit" id="userSignIn" value="User Sign In" />
				</form>
			  </div>
			  <!-- End of User Login Form -->
			  
			  <!-- Begining of Customer Login Form -->
			  <div class="TabbedPanelsContent">
				<!-- Displays message of successfully sign-in -->
				<?php echo $message; ?><br/>
				<!-- Display message of form error -->
				<?php echo $validate->form_errors_signIn($validate->errors); ?>
				<?php echo output_message($sessionMessage); ?>
				<form action="" method="post" enctype="application/x-www-form-urlencoded" name="customerForm" id="customerForm">
				  <?php echo $security->csrf_token_tag(); ?>
				  <p><!-- Username -->
					<input name="cusUsername" type="text" id="cusUsername" size="60" maxlength="50" placeholder="Username" value="<?php echo htmlentities($username); ?>"/>
					<br />
					<!-- Password -->
					<input name="cusPassword" type="password" id="cusPassword" size="60" maxlength="50" placeholder="Password" />
					<br />
					<a class='linkStyleButton2' href="forgotPassword.php?request=customer">I forgot my password.</a>
				  </p>
				  <input name="customerSignIn" type="submit" id="customerSignIn" value="Customer Sign In" />
				</form>
			  </div>
			  <!-- End of Customer Login Form -->
			  
			  <!-- Begining of User Creating Profile Form -->
			  <div class="TabbedPanelsContent">
				<?php echo $message; ?><br/>
				<?php echo $validate->form_errors_signIn($validate->errors); ?>
				  <form id="form1" name="form1" method="post" action="">
					<?php 
						 
						echo $security->csrf_token_tag(); 
						
						/* echo "The generated CSRF token is: <br/>";
						print_r($session->get_csrf_tokens());
						echo "<br/>";
						echo "The generated CSRF time is: <br/>";
						print_r($session->get_csrf_tokens_time());
						echo "<br/>";
						
						echo "Post gloabal variables are: <br/>";
						print_r($_POST);
						echo "<br/>";
						echo "Session gloabal variables are: <br/>";
						print_r($_SESSION); */
						
					?>
					<p><!-- First name -->
					  <input name="first_name" type="text" id="first_name_user" size="60" maxlength="50" placeholder="First name" value="<?php echo htmlentities($first_name);?>" />
					  <br />
					  <!-- Last name -->
					  <input name="last_name" type="text" id="last_name_user" size="60" maxlength="50" placeholder="Last name" value="<?php echo htmlentities($last_name);?>" />
					  <br />
					  <!-- Username -->
					  <input name="username_user" type="text" id="username_user" size="60" maxlength="50" placeholder="Username" value="<?php echo htmlentities($username);?>" onfocus="usernameCheck();" onblur="stopUsernameCheck();"/>
					  <br />
					  <div id="usernameMessage" style="color:red; display:none; margin:0px;"></div>
					  <!-- Password -->
					  <input name="password_user" type="password" id="password_user" size="60" maxlength="50" placeholder="Password" />
					  <br />
					  <!-- Confirm password -->
					  <input name="confirm_password" type="password" id="confirm_password_user" size="60" maxlength="50" placeholder="Confirm password" />
					  <br />
					  <div id="passwordMessage" style="color:red; display:none; margin:0px;"></div>
					  <!-- Phone number -->
					  <input name="phone_number" type="text" id="phone_number_user" size="60" maxlength="15" placeholder="Phone number" value="<?php echo htmlentities($phone_number);?>" />                      
					  <!-- <input name="verifyNumber" type="button" id="verifyNumber" value="Verify phone number" /> -->
					  <br />
					  <!--
					  <div id="confirmationCodeDiv" style="display:none">
					  <!-- Enter confirmation code --> <!--
					  <input name="verifyCode" type="text" id="verifyCode" size="15" maxlength="50" placeholder="Enter confirmation code" />
					  <input name="enterCode" type="button" id="enterCode" value="Enter code" />
					  <div id="messageUpdate" style="color:red; clear:both;"></div>
					  </div>
					  -->
					  <!-- Email address -->
					  <input name="email" type="text" id="email_user" size="60" maxlength="50" placeholder="Email address" value="<?php echo htmlentities($email);?>" />
					  <br />
					</p>
					<input name="user_register" type="submit" id="user_register" value="Register" style="display:block"/>
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
	<!-- <script src="./bootstrap/js/bootstrap.min.js" type="text/javascript" /></script> -->
	<script type="text/javascript" src="./javascripts/genericJSs.js"></script>
</body>
</html>

<?php if(isset($database)) { $database->close_connection(); } ?>