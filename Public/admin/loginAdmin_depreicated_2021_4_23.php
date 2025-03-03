<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, X-Auth-Token');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
require_once("../../includes/initialize.php");

// If the admin is already logged in, just forward them to the desired page without doing any authentication.
/* if($session->is_admin_logged_in() OR $session->is_customer_logged_in()) {
  redirect_to("homePage.php");
} */

$message = "";
$username = "";

// Initialize the last tab index
if (isset($_SESSION['lastTabIndex'])) {
	$lastTabIndex = json_encode($_SESSION['lastTabIndex']);
	echo "<script> var lastTabIndex = ".$lastTabIndex."; </script>";
} else {
	$lastTabIndex = json_encode(0);
	echo "<script> var lastTabIndex = ".$lastTabIndex."; </script>";
}

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
			$username = trim($post_params['username']);
			$password = trim($post_params['password']);
			
			// This will validate the requirements for the username and password
			$validate->validate_user();
			
			
			if (empty($validate->errors)) {
				// Check database to see if username/password exist.
				// Check if the person signing in is a admin or a customer
				// check if the person is a admin
				
				// The username has been sanitized to be passed into the database.
				$username = sql_prep($username);
				$password = sql_prep($password);
				
				// $found_admin = Admin::authenticate($username, $password);
				$adminStatic = Admin::find_by_username($username);
				$admin = new Admin();
				$found_admin = $admin->authenticate($username, $password);

				// After authentication, you can examine the admin further before logging them in. Such check could be to find out it the admin has paid their dues.
			
				// Condition if the admin is found
				if ($found_admin) {
					$iscustomer = FALSE;
					// If the admin is found, tell session to log them in.
					$session->admin_login($adminStatic);
					// Create a log that the admin has logged into the system
					admin_log_action('Login', "{$adminStatic->username} logged in.");
					redirect_to("/Public/admin/adminPage.php");
				} else {
					// username/password combo was not found in the database
					$message = "Username/password combination is incorrect.";
				}
			} else {
				$message = "There was a validation error. ";
			}
			
		} /* elseif (isset($post_params['admin_register'])) {
			// Get the registration details for the admin.
			$gender = "";
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
			
			// Validate the admin registration before saving in the database.
			$validate->validate_user_register();
			
			if (empty($validate->errors)) {
				$message = "There was no error, save the admin.";
				
				$admin = new Admin();
				$admin->first_name = $first_name;
				$admin->last_name = $last_name;
				$admin->username = $username;
				$admin->gender = $gender;
				$admin->password = $admin->password_encrypt($password);
				$admin->phone_number = $phone_number;
				$admin->admin_email = $email;
				$admin->date_created = $admin->current_Date_Time();
				
				if($admin->save()) {
					// Success
					// $session->message("Photograph uploaded successfully.");
					// This message should be saved in the session.
					$message = "Admin was successfully saved in the database.";
				} else {
					$message = "An error occurred while saving.";
				}
			} else {
				$message = "There was an error. ";
			}			
		} */ else { 
			// Form has not been submitted.
			$username = ""; $first_name = ""; $last_name = ""; $phone_number = ""; $email = ""; $password = "";
			$message = "Please log in.";
		}

	}
} else {
	// form not submitted or was GET request
	$message = "Please login.";
	$username = ""; $first_name = ""; $last_name = ""; $phone_number = ""; $email = ""; $password = "";
}

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

<title>Admin Login for WhoSabiWork</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" type="image/png" href="/Public/images/WhoSabiWorkLogo.png" />
<style type="text/css">
</style>
<link href="../../HomePageStyles.css" rel="stylesheet" type="text/css" />
<link href="../stylesheets/loginAdminStyles.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/LoginSpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<!-- Font Awesome Link -->
<link href="../stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />

<!-- Javascripts and JQuery -->
<script src="../javascripts/jquery.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="../../SpryAssets/LoginSpryTabbedPanels.js" type="text/javascript"></script>
</head>

<body>
<div id="topContainer">
	<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
	<?php include_layout_template('public_header.php'); ?>
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
                <input name="username" type="text" id="username" size="60" maxlength="50" placeholder="Username" />
                <br />
                
                <input name="password" type="password" id="password" size="60" maxlength="50" placeholder="Password" />
                <br />
              </p>
              <input class="btnStyle1" name="signIn" type="submit" id="signIn" value="Sign in" />
            </form>
          </div>
					<div class="TabbedPanelsContent">
					<!-- Displays message of successfully sign-in -->
					<?php echo $message; ?><br/>
					<!-- Display message of form error -->
					<?php echo $validate->form_errors_signIn($validate->errors); ?>
					  <form id="adminForm" name="form1" method="post" action="">
					    <?php 
								echo $security->csrf_token_tag(); 
							?>
							<p>
							  <input name="first_name" type="text" id="first_name_admin" size="60" maxlength="50" placeholder="First name" onblur="validateInput(this);" />
							  <br />
							  
							  <input name="last_name" type="text" id="last_name_admin" size="60" maxlength="50" placeholder="Last name" onblur="validateInput(this);" />
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
							  
							  <input name="username" type="text" id="username_admin" size="60" maxlength="50" placeholder="Username" autocomplete="off" autocorrect="off" autocapitalize="none" />
							  <br />
						  	<div id="usernameMessage" style="color:red; display:none; margin:0px;"></div>
							  
							  <input name="password" type="password" id="password_admin" size="60" maxlength="50" placeholder="Password" autocomplete="off" autocorrect="off" autocapitalize="none" onblur="validateInput(this);" />
							  <br />

							  <input name="confirm_password" type="password" id="confirm_password_admin" size="60" maxlength="50" placeholder="Confirm password" autocomplete="off" autocorrect="off" autocapitalize="none" />
							  <br />
							  <div id="passwordMessage" style="color:red; display:none; margin:0px;"></div>
							  
							  <input name="phone_number" type="tel" id="phone_number_admin" size="60" maxlength="15" placeholder="Phone number" onblur="validateInput(this);" />
							  <br />
							  
							  <input name="email" type="email" id="email_admin" size="60" maxlength="50" placeholder="Email address" autocomplete="off" autocorrect="off" autocapitalize="none" onblur="validateInput(this);" />
							  <br />
							</p>
							<input class="btnStyle1" name="admin_register" type="submit" id="admin_register" value="Register" />
					  </form>
					</div>
        </div>
      </div>
    </div>
  </div> <!-- Begining of Main Section -->

  <!-- Display the footer section -->
  <?php include_layout_template('public_footer.php'); ?>
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

<script type="text/javascript">
	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
	var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
</script>
<script type="text/javascript" src="../javascripts/genericJSs.js"></script>
<script type="text/javascript" src="../javascripts/loginAdminJSs.js"></script>
</body>
<!-- InstanceEnd --></html>
