<?php
require_once("../includes/initialize.php");

$message = "";
$username = ""; // Can work for both user and customer forms
$security = new Security_Functions();

$displayTokenInput = false;
global $session;
global $sessionMessage;
if (request_is_get()) {
	// Check if a request was sent from a user or customer
	$get_params = allowed_get_params(['request']);
	
	// Eliminate HTML tags embedded in the form inputs
	foreach($get_params as $param) {
		// run htmlentities check on the parameters
		if(isset($get_params[$param])) {
			// run htmlentities check on the parameters
			$get_params[$param] = h2($param);
		} 
	}
	
	$request = $get_params['request'];
	if (($request !== 'user') && ($request !== 'customer')) {
		redirect_to("/Public/loginPage.php");
	}
	
} elseif(request_is_post() && request_is_same_domain()) {
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['username', 'submit', 'cusUsername', 'cusSubmit', 'mediaType', 'reset_token', 'submit_token', 'cus_reset_token', 'cus_submit_token']);

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
		
		$mediaType;
		// Remember to give your form's submit tag a name="submit" attribute!
		if (isset($post_params['submit'])) { // Form has been submitted.
			// Get the user's username from the POST global variable.
			$username = trim($post_params['username']);
			
			// validate user input
			$validate->validate_name_update();
			if (!isset($_POST["mediaType"])){
				$validate->errors["mediaType"] = "Please select a medium to retrieve your password.";
			} else {
				// save the variable 
				$mediaType = $_POST["mediaType"];
			}
			
			if (empty($validate->errors)) {
				// Check database to see if username exist.
					
				// The username has been sanitized to be passed into the database.
				$username = sql_prep($username);
				$userStatic = User::find_by_username($username);
				
				$message = "";
				// Condition if the user is found
				if (!empty($userStatic)) {
					// Username was found; okay to reset
					if ($mediaType === 'email') {
						$userStatic->create_reset_token($username);
						$userStatic->email_reset_token($username);
						$session->message("A link to reset your password has been sent to the email address on file.");
						redirect_to("/index.php");
					} elseif ($mediaType === 'text') {
						$phoneNumber = $userStatic->phone_number;
						$smsCode = randStrGen(6);
						// Save the generated code in the database
						$userStatic->reset_token = $smsCode;
						$userStatic->date_edited = $userStatic->current_Date_Time();
						$userStatic->update();
						// Send the SMS code to the user.
						$smsMessage = 'Your phone number has been confirmed, please use this code '.$smsCode.' to complete your password reset.';
						
						if (sendSMSCode($phoneNumber, $smsMessage)) {
							$message = "A reset token has been sent to the phone number on file. Use it to complete your password reset.";
							$displayTokenInput = true;
						} else {
							$message = "An error occured when sending your reset token. Please try again.";
							$displayTokenInput = true;
						}
					}
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
			$validate->validate_name_update();
			if (!isset($_POST["mediaType"])){
				$validate->errors["mediaType"] = "Please select a medium to retrieve your password.";
			} else {
				// save the variable 
				$mediaType = $_POST["mediaType"];
			}
			
			if (empty($validate->errors)) {
				// Check database to see if username exist.
					
				// The username has been sanitized to be passed into the database.
				$username = sql_prep($username);
				$customerStatic = Customer::find_by_username($username);
				
				$message = "";
				// Condition if the user is found
				if (!empty($customerStatic)) {
					// Username was found; okay to reset
					if ($mediaType === 'email') {
						$customerStatic->create_reset_token($username);
						$customerStatic->email_reset_token($username);
						$session->message("A link to reset your password has been sent to the email address on file.");
						redirect_to("/index.php");
					} elseif ($mediaType === 'text') {
						$phoneNumber = $customerStatic->phone_number;
						$smsCode = randStrGen(6);
						// Save the generated code in the database
						$customerStatic->reset_token = $smsCode;
						$customerStatic->date_edited = $customerStatic->current_Date_Time();
						$customerStatic->update();
						// Send the SMS code to the user.
						$smsMessage = 'Your phone number has been confirmed, please use this code '.$smsCode.' to complete your password reset.';
						
						if (sendSMSCode($phoneNumber, $smsMessage)) {
							$message = "A reset token has been sent to the phone number on file. Use it to complete your password reset.";
							$displayTokenInput = true;
						} else {
							$message = "An error occured when sending your reset token. Please try again.";
							$displayTokenInput = true;
						}
					}
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
		} elseif(isset($post_params['submit_token'])) {
			// Get the user's username reset token.
			$reset_token = trim($post_params['reset_token']);
			
			// validate user input
			$validate->validate_name_update();
			
			if (empty($validate->errors)) {
				// The reset_token has been sanitized to be passed into the database.
				$reset_token = sql_prep($reset_token);
				
				redirect_to("resetPassword.php?token=".$reset_token."&account=user");
			} else {
				$displayTokenInput = true;
				// $message = "The reset token was not validated. ";
			}
		} elseif(isset($post_params['cus_submit_token'])) {
			// Get the user's username reset token.
			$reset_token = trim($post_params['cus_reset_token']);
			
			// validate user input
			$validate->validate_name_update();
			
			if (empty($validate->errors)) {
				// The reset_token has been sanitized to be passed into the database.
				$reset_token = sql_prep($reset_token);
				
				redirect_to("resetPassword.php?token=".$reset_token."&account=customer");
			} else {
				$displayTokenInput = true;
				// $message = "The reset token was not validated. ";
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
// Destroy the token after failure
$security->destroy_csrf_tokens();

function displayTabbedPanelTab() {
	$get_params = allowed_get_params(['request']);
	$request = $get_params['request'];
	$output = "";
	if ($request === 'user') {
		$output .= "<li class='TabbedPanelsTab' tabindex='0'>Retrieve User Account Password</li>";
	} elseif($request === 'customer') {
		$output .= "<li class='TabbedPanelsTab' tabindex='0'>Retrieve Business Account Password</li>";
	}
	
	return $output;
}
	
function displayTabbedPanelContent() {
	global $security; global $validate; global $message; global $sessionMessage;
	global $username;
	$get_params = allowed_get_params(['request']);
	$request = $get_params['request'];
	$output = "";
	if ($request === 'user') {
		$output .= "
			<form action='forgotPassword.php?request=".$request."' method='post' enctype='application/x-www-form-urlencoded' name='userForgotPassword' id='userForgotPassword'>".$security->csrf_token_tag()."
			  <p><!-- Username -->
				<input name='username' type='text' id='username' size='60' maxlength='50' placeholder='Username' autocomplete='off' autocorrect='off' autocapitalize='none' value='".htmlentities($username)."'/>
				<br />
				<p>Specify your preferred media to retrieve your password.</p>
				<label>
				<input type='radio' name='mediaType' value='email' id='email_btn' /> Email </label>
				<label>
				<input type='radio' name='mediaType' value='text' id='text_btn' /> Text </label>
			  </p>
			  <input name='submit' type='submit' id='submit' value='Submit' />
			</form>
		";
	} elseif($request === 'customer') {
		$output .= "
			<form action='forgotPassword.php?request=".$request."' method='post' enctype='application/x-www-form-urlencoded' name='cusForgotPassword' id='cusForgotPassword'>".$security->csrf_token_tag()."
			  <p><!-- Username -->
				<input name='cusUsername' type='text' id='cusUsername' size='60' maxlength='50' placeholder='Username' autocomplete='off' autocorrect='off' autocapitalize='none' value='".htmlentities($username)."'/>
				<br />
				<p>Specify your preferred media to retrieve your password.</p>
				<label>
				<input type='radio' name='mediaType' value='email' id='email_btn' /> Email </label>
				<label>
				<input type='radio' name='mediaType' value='text' id='text_btn' /> Text </label>
			  </p>
			  <input name='cusSubmit' type='submit' id='cusSubmit' value='Submit' />
			</form>
		";
	}
	
	return $output;
}

function displayTokenInput() {
	global $security; global $validate; global $message; global $sessionMessage;
	$get_params = allowed_get_params(['request']);
	$request = $get_params['request'];
	$output = "";
	if ($request === 'user') {
		$output .= "
			<form action='' method='post' enctype='application/x-www-form-urlencoded' name='userResetToken' id='userForgotPassword'>".$security->csrf_token_tag()."
			  <p><!-- Username -->
				<input name='reset_token' type='text' id='reset_token' size='60' maxlength='50' placeholder='Reset token' autocomplete='off' autocorrect='off' autocapitalize='none'/>
			  </p>
			  <input name='submit_token' type='submit' id='submit_token' value='Submit Token' />
			</form>
		";
	} elseif($request === 'customer') {
		$output .= "
			<form action='' method='post' enctype='application/x-www-form-urlencoded' name='cusResetToken' id='userForgotPassword'>".$security->csrf_token_tag()."
			  <p><!-- Username -->
				<input name='cus_reset_token' type='text' id='cus_reset_token' size='60' maxlength='50' placeholder='Reset token' autocomplete='off' autocorrect='off' autocapitalize='none'/>
			  </p>
			  <input name='cus_submit_token' type='submit' id='cus_submit_token' value='Submit Token' />
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
			<div class='TabbedPanelsContent'>
				<!-- Displays message of successfully sign-in -->
				<?php echo displayMessages(); ?>
				
				<?php 
					if (!$displayTokenInput) {
						echo displayTabbedPanelContent(); 
					}
				?>
				
				<?php 
					if ($displayTokenInput) {
						echo displayTokenInput();
				    }
				?>
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