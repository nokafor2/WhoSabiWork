<?php
require_once("../../includes/initialize.php");

$message = "";
$username = "";
/*
// Initialize the last tab index
if (isset($_SESSION['lastTabIndex'])) {
	$lastTabIndex = json_encode($_SESSION['lastTabIndex']);
	echo "<script> var lastTabIndex = ".$lastTabIndex."; </script>";
} else {
	$lastTabIndex = json_encode(0);
	echo "<script> var lastTabIndex = ".$lastTabIndex."; </script>";
}
*/
$security = new Security_Functions();
// the request_is_post() fxn will ensure that a post request was sent from the webpage 
if (request_is_post() && request_is_same_domain()) {
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['username', 'password', 'first_name', 'last_name', 'phone_number', 'email', 'admin_register', 'signIn']);

	// Eliminate HTML tags embedded in the form inputs
	foreach($post_params as $param) {
		if(isset($post_params[$param])) {
			// run htmlentities check on the parameters
			$post_params[$param] = h2($param);
		} else {
			$post_params[$param] = NULL;
		}
	}
	
	// 
	if(!$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()) {
		$message = "Sorry, request was not valid.";
	} else {
		// CSRF tests passed--form was created by us recently.

		// Remember to give your form's submit tag a name="submit" attribute!
		if (isset($post_params['signIn'])) { // Form has been submitted.
			
			// Get the admin's username and password from the POST global variable.
			$loginId = trim($post_params['username']);
			$password = trim($post_params['password']);
			
			// This will validate the requirements for the username and password
			$validate->validate_user();
						
			if (empty($validate->errors)) {
				// Check database to see if username/phone number/email and password exist.
				// Check if the person signing in as an admin
				// check if the person is an admin

				$login_check = new Admin_Failed_Login();
				
				// After you are sure you have a username, throttle before checking for the username and password in the database
				$admin_throttle_delay = $login_check->throttle_failed_admin_logins($loginId);
				if($admin_throttle_delay > 0) {
					// Throttled at the moment, try again after delay period
					$message  = "Too many failed logins. ";
					$message .= "You must wait {$admin_throttle_delay} minutes before you can attempt another login.";					
				} else {
					// Check what type of login id was used such as a username, phone number or email address
					// Check email first
					$loginType = getLoginIdType($loginId);
					$admin = new Admin();
					$found_admin = $admin->authenticate($loginId, $loginType, $password);
				
					// Condition if the admin is found
					if ($found_admin) {
						// Clear any attempt of login trial after authentication is passed
						$login_check->clear_admin_failed_logins($loginId);
						// If the admin is found, tell session to log them in.
						$session->admin_login($found_admin);
						// Create a log that the admin has logged into the system
						admin_log_action('Login', "{$found_admin->username} logged in.");
						// redirect_to("/Public/admin/adminPage.php?id=".urlencode($found_admin->id));
						// redirect_to("/Public/admin/adminPage.php");
						redirect_to("adminPage.php");
						// header("Location: http://www.whosabiwork.com/Public/admin/adminPage.php");
					} else {
						// Record a failed login of the admin in the database
						$login_check->record_admin_failed_login($loginId);
						// username/password combo was not found in the database
						$message = "Username/password combination is incorrect.";
					}
				}
			} else {
				// $message = "There was a validation error. ";
			}
			
		} else { 
			// Form has not been submitted.
			/* $username = ""; $first_name = ""; $last_name = ""; $phone_number = ""; $email = ""; $password = ""; */
			// $message = "Please log in.";
		}
	}
} else {
	// form not submitted or was GET request
	// $message = "Please login.";
	/* $username = ""; $first_name = ""; $last_name = ""; $phone_number = ""; $email = ""; $password = ""; */
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

<div id="container">
  <?php
	if ($session->message() !== '') {
		echo '<p style="color: #FFF; padding-left: 10px;">'.$session->message().'</p>';
	}
  ?>
  <!-- Begining of Main Section -->
  <div class="mainLogin">
    <div class="loginPanel">
      <div id="TabbedPanels1" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Admin Sign in</li>
          <li class="TabbedPanelsTab" tabindex="0">Admin Register</li>
        </ul>
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
			  			<p>
                <input name="username" type="text" id="username" size="60" maxlength="50" placeholder="Username / Email / Phone number" />
                <br />
                
                <input name="password" type="password" id="password" size="60" maxlength="50" placeholder="Password" />
                <br />
              </p>
              <input class="btnStyle1" name="signIn" type="submit" id="signIn" value="Sign in" />
            </form>
          </div>
					<div class="TabbedPanelsContent">
					<!-- Displays message of successfully sign-in -->
					<?php // echo $message; ?><br/>
					<!-- Display message of form error -->
					<?php echo $validate->form_errors_signIn($validate->errors); ?>
					  <form id="adminForm" name="form1" method="post" action="">
					    <?php 
								echo $security->csrf_token_tag(); 
							?>
							
							<!-- First name -->
						  <input name="first_name" type="text" id="first_name_admin" size="60" maxlength="50" placeholder="First name" onblur="validateInput(this);" />
						  <br />
						  <div id="first_name_message" name="first_name_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
						  
						  <!-- Last name -->
						  <input name="last_name" type="text" id="last_name_admin" size="60" maxlength="50" placeholder="Last name" onblur="validateInput(this);" />
						  <br />
						  <div id="last_name_message" name="last_name_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

						  <!-- Gender -->
						  <div style="padding-left: 10px">
						  	<label class="genderLabel">Gender:</label>
							  <label>
			          	<input name="gender" type="radio" value="male" id="male" onclick="validateInput(this);" />
							    Male
			          </label>
				        <label>
			          	<input name="gender" type="radio" value="female" id="female" onclick="validateInput(this);" />
						    	Female
			          </label>
				        <br />
			      	</div>
			      	<div id="gender_message" name="gender_message" style="color:red; display:none; margin:0px; margin-left:10px;">Error to appear here</div>
						  
						  <!-- Username -->
						  <input name="username" type="text" id="username_admin" size="60" maxlength="50" placeholder="Username" autocomplete="off" autocorrect="off" autocapitalize="none" onblur="adminUsernameCheck();" />
						  <br />
					  	<div id="username_message" name="username_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
						  
						  <!-- Password -->
						  <input name="password" type="password" id="password_admin" size="60" maxlength="50" placeholder="Password" autocomplete="off" autocorrect="off" autocapitalize="none" onblur="validateInput(this);" />
						  <br />
						  <div id="password_message" name="password_admin_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

						  <!-- Confirm password -->
						  <input name="confirm_password" type="password" id="confirm_password_admin" size="60" maxlength="50" placeholder="Confirm password" autocomplete="off" autocorrect="off" autocapitalize="none" />
						  <br />
						  <div id="confirm_password_message" name="confirm_password_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
						  
						  <!-- Phone number -->
						  <p class="smallText2">Enter phone number in this format 08012345690</p>
						  <input name="phone_number" type="tel" id="phone_number_admin" size="60" maxlength="15" placeholder="Phone number" onblur="validateInput(this);" />
						  <br />
						  <div id="phone_number_message" name="phone_number_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
						  
						  <!-- Email address -->
						  <input name="email" type="email" id="email_admin" size="60" maxlength="50" placeholder="Email address" autocomplete="off" autocorrect="off" autocapitalize="none" onblur="validateInput(this);" />
						  <br />
						  <div id="email_message" name="email_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
							
							<input class="btnStyle1" name="admin_register" type="submit" id="admin_register" value="Register" />
					  </form>
					</div>
        </div>
      </div>
    </div>
  </div> <!-- Begining of Main Section -->

  <!-- Display the footer section -->
  <?php include_layout_template('navigation_footer.php'); ?>
</div>

<!-- Loader -->
<div class="loader" style="background-color: rgba(0, 0, 0, 0.5);">
	<img src="../images/utilityImages/ajax-loader1.gif" alt="Loading..." />
</div>

<!-- Modal for the display of error messages -->
<div class="messageModal">
	<div class="messageContainer">
		<div id="messageHead">Error</div>
		<div id="messageContent">Message to appear here.</div>
		<button id="closeBtn" class="btnStyle1">Close</button>
	</div>
</div>

<?php
	// Initialize the footer of the webpage
	echo getUniquePageFooter($scriptName);
?>
