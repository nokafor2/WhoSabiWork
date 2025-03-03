<?php
require_once("../includes/initialize.php");

$message = "";
$username = "";
$security = new Security_Functions();

if(request_is_post() && request_is_same_domain()) {
	// Check that only allowed parameters is passed into the form
	$post_params = allowed_post_params(['username', 'password', 'cusUsername', 'cusPassword', 'username_user', 'password_user', 'first_name', 'last_name', 'gender', 'phone_number', 'email', 'userSignIn', 'customerSignIn', 'user_register']);

	// Eliminate HTML tags embedded in the form inputs
	foreach($post_params as $param) {
		// run htmlentities check on the parameters
		if(isset($post_params[$param])) {
			// run htmlentities check on the parameters
			$post_params[$param] = h2($param);
		} 
	}
	
	if(!csrf_token_is_valid() || !csrf_token_is_recent()) {
		$message = "Sorry, form has expired. Please refresh and try again.";
		// Login instances of imporoper login into the log file.
		/*
		echo "<br/> session variables after failure are: </br/>";
		print_r($_SESSION);
		echo "<br/> post variables after failure are: </br/>";
		print_r($_POST);
		*/
	} else {
	// CSRF tests passed--form was created by us recently.
		
		// Remember to give your form's submit tag a name="submit" attribute!
		if (isset($post_params['user_register'])) {
			// Get the registration details for the user.
			$first_name = trim($post_params['first_name']);
			$last_name = trim($post_params['last_name']);
			$username = trim($post_params['username_user']);
			$password = trim($post_params['password_user']);
			$phone_number = trim($post_params['phone_number']);
			$email = trim($post_params['email']);
			
			// The value gets initalized after the validation is done
			$gender;
			
			
			// Validate the user registration before saving in the database.
			$validate->validate_user_register();			
			if (empty($validate->errors)) {
				// $message = "There was no error, save the user.";
				
				$user = new User();
				$user->customers_id = NULL;
				$user->first_name = $first_name;
				$user->last_name = $last_name;
				switch ($gender) {
					case 'male':
						$user->gender = 'male';
						break;
					case 'female':
						$user->gender = 'female';
						break;
				}
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
				if($user->save()) {
					// Success, disable the button
					// echo disableRegisterBtn();
					$message = "The user account was successfully created. You can now login.";
					// This message should be saved in the session.
					$session->message("Congratulations ".$first_name." ".$last_name.". Your user account was successfully created.");
					$newUser = User::find_by_id($database->insert_id());
					// Send an email to the user.
					if (isset($email) && !empty($email)) {
						// Send mail to new uer created
						composeEmail();
						// Send mail to whoSabiWork team
						composeEmailToWhoSabiWork();
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
				// $message = "There was an error validating form inputs. ";
			}
		} else { 
			$message = "Please log in.";
		}
	}
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
	// $message = "Please login.";
	// $message = "This is a get request.";
	// redirect to the login page.
	// redirect_to("/WhoSabiWork/Public/index.php");
	
	// Form has not been submitted.
	$username = ""; $first_name = ""; $last_name = ""; $phone_number = ""; $email = ""; $password = "";
}
// Destroy the token after failure
destroy_csrf_token();

/* This composes email for users after creating an account. */
function composeEmail() {
	global $email; global $first_name; global $last_name; global $username; global $password;
	
	$from = 'support@whosabiwork.com';
	$sender = 'WhoSabiWork';
	// $to = $email;
	$title = 'Welcome to WhoSabiWork';
	$body = '<img src="cid:whoSabiWorkLogo"/>
	<p>Dear '.$first_name.' '.$last_name.', </p>
	
	<p>Thanks for taking the time to create a WhoSabiWork account.</p>
	
	<p>Your username is <strong>'.$username.'</strong>, and your password is <strong>'.$password.'</strong>. Visit our website <strong><a href="https://www.whosabiwork.com/Public/loginPage.php">login page</a></strong> and try logging in to ensure that it works. If you have any problem logging in, don\'t fail to contact our service support at support@whosabiwork.com.</p>
	
	<p>WhoSabiWork is committed to providing its users an eclectic source to find skilled artisans, mechanics and spare part sellers. Also, it helps its registered customers get advertised to users so that the outreach of their business will reach the global populace.</p>
	
	<p>If you have any questoin, you can contact us at our website on the <strong><a href="WhoSabiWork.com/Public/contactUs.php">contact page</a></strong>, or email us at support@whosabiwork.com. Thank you for your patronage.</p>
	
	<p>WhoSabiWork.com</p>';
	
	return sendMailFxn($from, $sender, $email, $title, $body);
}

/* This function composses email for whosabiwork */
function composeEmailToWhoSabiWork() {
	global $email; global $first_name; global $last_name; global $username;
	
	$from = 'support@whosabiwork.com';
	$sender = 'WhoSabiWork';
	$to = 'support@whosabiwork.com';
	$title = 'User Account Creation';
	$body = '<img src="cid:whoSabiWorkLogo"/>
	<p>An account has been created by '.$first_name.' '.$last_name.' with username '.$username.' and email address '.$email.'.</p>
	
	<p>WhoSabiWork.com</p>';
	
	return sendMailFxn($from, $sender, $to, $title, $body);
}

// This is a php function that executes a javascript code when the register button is clicked and submitted successfully
function disableRegisterBtn() {
	$output = "";
	$output .= "<script>
		user_register.setAttribute('disabled', 'disabled');
		$('#user_register').css('background-color', '#FF7664');
		$('#user_register').css('color', '#FFF');
		$('#user_register').css('border', 'unset');
		$('#user_register').css('font-weight', 'unset');
		$('#user_register').css('cursor', 'unset');
	</script>";

	return $output;
}

// Set the radio button in the session as unchecked
$_SESSION['accountFormValidate']['gender'] = 'Gender not selected';


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-171769876-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-171769876-1');
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Create a User Account</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" type="image/png" href="/Public/images/WhoSabiWorkLogo.png" />
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

<script src="./javascripts/jquery.js" type="text/javascript"></script>

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
			  <li class="TabbedPanelsTab" tabindex="0">Create User Account</li>
			</ul>
			<!-- tabbed Pannels content Group div -->
			<div class="TabbedPanelsContentGroup">
			  <!-- Begining of User Creating Profile Form -->
			  <div class="TabbedPanelsContent">
				<?php echo displayMessages(); ?>
				  <form id="form1" name="form1" method="post" action="">
					<?php						
						echo csrf_token_tag();
						
						/*						
						echo "<br/>Post global variables are: <br/>";
						print_r($_POST);
						echo "<br/>";
						echo "<br/>Session global variables are: <br/>";
						print_r($_SESSION);
						*/
					?>
					<p><!-- First name -->
					  <input name="first_name" type="text" id="first_name_user" size="60" maxlength="50" placeholder="First name" value="<?php echo htmlentities($first_name);?>" onblur="validateInput(this);" />
					  <br />
					  <!-- Last name -->
					  <input name="last_name" type="text" id="last_name_user" size="60" maxlength="50" placeholder="Last name" value="<?php echo htmlentities($last_name);?>" onblur="validateInput(this);" />
					  <br />
					  <p style="padding-left: 10px"><label class="genderLabel">Gender:</label>
					   <label>
			          	<input name="gender" type="radio" value="male" id="male" onclick="validateInput(this);" />
					    Male
			          </label>
			           <label>
			          	<input name="gender" type="radio" value="female" id="female" onclick="validateInput(this);" />
					    Female
			          </label>
			        <br />
			      	</p>
					  <!-- Username -->
					  <input name="username_user" type="text" id="username_user" size="60" maxlength="50" placeholder="Username" value="<?php echo htmlentities($username);?>" onblur="usernameCheck();" autocomplete="off" autocorrect="off" autocapitalize="none"/>
					  <!-- onfocus="usernameCheck();" onblur="stopUsernameCheck(); -->
					  <br />
					  <div id="usernameMessage" style="color:red; display:none; margin:0px;"></div>
					  <!-- Password -->
					  <input name="password_user" type="password" id="password_user" size="60" maxlength="50" placeholder="Password" autocomplete="off" autocorrect="off" autocapitalize="none" onblur="validateInput(this);" />
					  <br />
					  <!-- Confirm password -->
					  <input name="confirm_password" type="password" id="confirm_password_user" size="60" maxlength="50" placeholder="Confirm password" autocomplete="off" autocorrect="off" autocapitalize="none" />
					  <br />
					  <div id="passwordMessage" style="color:red; display:none; margin:0px;"></div>
					  <!-- Phone number -->
					  <input name="phone_number" type="tel" id="phone_number_user" size="60" maxlength="15" placeholder="Phone number" value="<?php echo htmlentities($phone_number);?>" onblur="validateInput(this);" />
					  <p class="smallText2">Enter phone number in this format 08012345690</p>
					  <!-- Email address -->
					  <input name="email" type="email" id="email_user" size="60" maxlength="50" placeholder="Email address" value="<?php echo htmlentities($email);?>" autocomplete="off" autocorrect="off" autocapitalize="none" onblur="validateInput(this);" />
					  <br />
					</p>
					  
					<p style="padding:10px; font-size:10px;">By Registering, you agree that you've read and accepted our User <a href="/Public/termsOfUse.php" >Terms of Use</a>, you're at least 18 years old, you consent to our <a href="/Public/privacyPolicy.php" >Privacy Policy</a> and you have accepted to receive marketing communications from us.</p>
					
					<input name="user_register" type="submit" id="user_register" value="Register" style="display:block" />
				  </form>
				</div>
			</div> <!-- End Tabbed Pannels Content Group div -->
		  </div> <!-- End of Tabbed Panel -->
		</div> <!-- End of Login Panel -->
      </div> <!-- End of Main Section -->

	  <!-- Display the footer section -->
	  <?php
	  	include_layout_template('public_footer.php'); 
	  ?>
	</div> <!-- End of Container -->
	
	<!-- Loader -->
	<div class="loader">
		<img src="images/utilityImages/ajax-loader1.gif" alt="Loading..." />
	</div>

	<!-- Modal for the display of error messages -->
	<div class="messageModal">
		<div class="messageContainer">
			<div id="messageHead">Error</div>
			<div id="messageContent">Message to appear here.</div>
			<button id="closeBtn" class="btnStyle1">Close</button>
		</div>
	</div>

	<script type="text/javascript">
		var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
		// var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
	</script>
	
	<script type="text/javascript" src="./javascripts/usernameCheck.js"></script>
	<script type="text/javascript" src="./javascripts/passwordMatchCheck.js"></script>
	<!-- <script type="text/javascript" src="./javascripts/phoneNumberVerification.js"></script> -->
	<!-- <script src="./bootstrap/js/bootstrap.min.js" type="text/javascript" /></script> -->
	<script type="text/javascript" src="./javascripts/genericJSs.js"></script>
</body>
</html>

<?php if(isset($database)) { $database->close_connection(); } ?>