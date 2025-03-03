<?php
require_once("../includes/initialize.php");

$message = "";
$username = "";
$security = new Security_Functions();
global $session;
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
	if ($account == 'user') {
		// Confirm that the token sent is valid
		$user = new User();
		// $foundUser is a static variable 
		$foundUser = $user->find_user_with_token($token);
		// Check if token is recent
		$tokenIsRecent = $user->token_is_recent($token);
		if (!$tokenIsRecent) {
			$session->message("Passwrod reset time has expired.");
			redirect_to("/Public/forgotPassword.php?request=user");
		}
	} elseif ($account == 'customer') {
		// Confirm that the token sent is valid
		$customer = new Customer();
		// $foundCustomer is a static variable 
		$foundCustomer = $customer->find_customer_with_token($token);
		// Check if token is recent
		$tokenIsRecent = $customer->token_is_recent($token);
		if (!$tokenIsRecent) {
			$session->message("Passwrod reset time has expired.");
			redirect_to("/Public/forgotPassword.php?request=customer");
		}
	} else {
		// Not a valid account
		// echo "Match not found";
		$session->message('This is not a valid account type.');
		redirect_to('/Public/loginPage.php');
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
			// echo "contents of post_params: <br/>";
			// print_r($post_params);
			if (isset($post_params['resetPassword']) && !empty($foundUser)) { 
				// Form has been submitted.
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
					$foundUser->reset_token = "";
					$foundUser->date_edited = $foundUser->current_Date_Time();
					// $savedPassword = $foundUser->update();
					
					// Condition if the password is saved
					if ($foundUser->update()) {
						// $session = new Session();
						$session->message("Your password was successfully updated. Please refresh and login with your new password.");
						redirect_to("/Public/loginPage.php?profile=user");
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
						// $session = new Session();
						$session->message("Your password was successfully updated. Please refresh and login with your new password.");
						redirect_to("/Public/loginPage.php?profile=customer");
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
		// redirect_to("/Public/index.php");
		
		// Form has not been submitted.
		$password = ""; $confirm_password = "";
	} */
} else {
	// Log-in spurios attempt to get into a user's account
	$session->message("Improper page request.");
	redirect_to("/index.php");
}
// Destroy the token after failure
$security->destroy_csrf_tokens();

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
				  <input name='password' type='password' id='password' size='60' maxlength='50' placeholder='Password' autocomplete='off' autocorrect='off' autocapitalize='none'/>
				  <br />
				  <!-- Confirm password -->
				  <input name='confirm_password' type='password' id='confirm_password' size='60' maxlength='50' placeholder='Confirm password' autocomplete='off' autocorrect='off' autocapitalize='none'/>
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
				  <input name='cusPassword' type='password' id='cusPassword' size='60' maxlength='50' placeholder='Password' autocomplete='off' autocorrect='off' autocapitalize='none'/>
				  <br />
				  <!-- Confirm password -->
				  <input name='cusConfirmPassword' type='password' id='cusConfirmPassword' size='60' maxlength='50' placeholder='Confirm password' autocomplete='off' autocorrect='off' autocapitalize='none'/>
				  <br />
			  </p>
			  <input name='cusResetPassword' type='submit' id='cusResetPassword' value='Reset Password' />
			</form>
		";
	}
	
	return $output;
}



?>

<?php
	$scriptName = scriptPathName();
	// Initialize the header of the webpage
	echo getUniquePageHeader($scriptName);
?>

<div id="topContainer">
	<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
	<?php include_layout_template('navigation_header.php'); ?>
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
		  <?php echo displayTabbedPanelTab(); ?>
		</ul>
		<!-- tabbed Pannels content Group div -->
		<div class="TabbedPanelsContentGroup">
		  <!-- Begining of User Login Form -->
		  <div class="TabbedPanelsContent">
			<!-- Displays message of successfully sign-in -->
			<?php echo displayMessages(); echo displayTabbedPanelContent(); ?>
		  </div>
		  <!-- End of User Login Form -->
		  
		  <!-- Begining of User Login Form -->
		  <div class="TabbedPanelsContent">
			<!-- Displays message of successfully sign-in -->
			<?php echo displayMessages(); echo displayTabbedPanelContent(); ?>
		  </div>
		  <!-- End of User Login Form -->
		  
		</div> <!-- End Tabbed Pannels Content Group div -->
	  </div> <!-- End of Tabbed Panel -->
	</div> <!-- End of Login Panel -->
    </div> <!-- End of Main Section -->

  <!-- Display the footer section -->
  <?php include_layout_template('navigation_footer.php'); ?>
</div> <!-- End of Container -->
	
<?php
	// Initialize the footer of the webpage
	echo getUniquePageFooter($scriptName);
?>	

<?php if(isset($database)) { $database->close_connection(); } ?>