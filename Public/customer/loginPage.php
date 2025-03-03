<?php
require_once("../../includes/initialize.php");
require_once(LIB_PATH.DS."functions.php");
require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."database.php");
require_once(LIB_PATH.DS."user.php");

/* require_once("../../includes/functions.php");
require_once("../../includes/session.php");
require_once("../../includes/database.php");
require_once("../../includes/user.php"); */

// If the user is already logged in, just forward them to the desired page without doing any authentication.
if($session->is_logged_in()) {
  redirect_to("photo_upload.php");
}

// Remember to give your form's submit tag a name="submit" attribute!
if (isset($_POST['submit'])) { // Form has been submitted.
	
	// Get the user's username and password from the POST global variable.
	$username = trim($_POST['username']);
    $password = trim($_POST['password']);
	
	// Check database to see if username/password exist.
	$found_user = User::authenticate($username, $password);
	
	// After authentication, you can examine the user further before logging them in. Such check could be to find out it the user has paid their dues.
	
	// Condition if the user is found
    if ($found_user) {
		// If the user is found, tell session to log them in.
		$session->login($found_user);
		// Create a log that the user has logged into the system
		log_action('Login', "{$found_user->username} logged in.");
		redirect_to("photo_upload.php");
    } else {
		// username/password combo was not found in the database
		$message = "Username/password combination incorrect.";
	}
  
} else { // Form has not been submitted.
	$username = "";
	$password = "";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/LoginPage.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>About Mechnorama</title>
<!-- InstanceEndEditable -->
<style type="text/css">
</style>
<link href="../../HomePageStyles.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/LoginSpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script src="../../SpryAssets/LoginSpryTabbedPanels.js" type="text/javascript"></script>
</head>

<body>
<div id="container">
  <div class="mainLogin">
    <div class="loginPanel">
      <div id="TabbedPanels1" class="TabbedPanels">
        <ul class="TabbedPanelsTabGroup">
          <li class="TabbedPanelsTab" tabindex="0">Sign in</li>
          <li class="TabbedPanelsTab" tabindex="0">Register</li>
        </ul>
        <div class="TabbedPanelsContentGroup">
          <div class="TabbedPanelsContent">
            <form action="" method="post" enctype="application/x-www-form-urlencoded" name="SignIn" id="SignIn">
              <p>Username
                <input name="username" type="text" id="username" size="60" maxlength="50" />
                <br />
                Password
                <input name="password" type="password" id="password" size="60" maxlength="50" />
                <br />
              </p>
              <input name="signIn" type="submit" id="signIn" value="Sign in" />
            </form>
          </div>
          <div class="TabbedPanelsContent">
          	<!-- Tabbed panel for Register -->
            <div id="TabbedPanels2" class="TabbedPanels">
              <ul class="TabbedPanelsTabGroup">
                <li class="TabbedPanelsTab" tabindex="0">User</li>
                <li class="TabbedPanelsTab" tabindex="0">Business</li>
              </ul>
              <div class="TabbedPanelsContentGroup">
                <div class="TabbedPanelsContent">
                  <form id="form1" name="form1" method="post" action="">
                    <p>First name
					  <input name="first_name" type="text" id="first_name_user" size="60" maxlength="50" />
                      <br />
                      Last name
                      <input name="last_name" type="text" id="last_name_user" size="60" maxlength="50" />
                      <br />
                      Username
                      <input name="username" type="text" id="username_user" size="60" maxlength="50" />
                      <br />
                      Password
                      <input name="password" type="password" id="password_user" size="60" maxlength="50" />
                      <br />
                      Phone number
                      <input name="phone_number" type="text" id="phone_number_user" size="60" maxlength="15" />                      
                      <br />
                      Email address
                      <input name="email" type="text" id="email_user" size="60" maxlength="50" />
                      <br />
                    </p>
                    <input name="user_register" type="submit" id="user_register" value="Register" />
                  </form>
                </div>
                <div class="TabbedPanelsContent">
                  <form id="form2" name="form2" method="post" action="">
                    <p>First name
                      <input name="first_name" type="text" id="first_name_biz" size="60" maxlength="50" />
                      <br />
                      Last name
                      <input name="last_name" type="text" id="last_name_biz" size="60" maxlength="50" />
                      <br />
                      Username
                      <input name="business_username" type="text" id="business_username" size="60" maxlength="50" />
                      <br />
                      Password
                      <input name="business_password" type="password" id="business_password" size="60" maxlength="50" />
                      <br />
                    </p>
                    <p>  
					  Business name
					    <input name="business_name" type="text" id="business_name" size="60" maxlength="50" />
				      <br />
                      Business email
                      <input name="business_email" type="text" id="business_email" size="60" maxlength="50" />
                      <br />
                      Business phone number
                      <input name="business_phone" type="text" id="business_phone" size="60" maxlength="15" />
                      <br />
                    </p>
                    <p>
                      Address Line 1
                      <input name="address_line_1" type="text" id="address_line_1" size="60" maxlength="60" />
                      <br />
                      Address Line 2 
                      <input name="address_line_2" type="text" id="address_line_2" size="60" maxlength="60" />
                      <br />
                      Address Line 3 
                      <input name="address_line_3" type="text" id="address_line_3" size="60" maxlength="60" />
                      <br />
                      City 
                      <input name="city" type="text" id="city" size="60" maxlength="15" />
                      <br />
                      State 
                      <input name="state" type="text" id="state" size="60" maxlength="15" />
                    </p>
                    <p>
                      <input name="business_register" type="submit" id="business_register" value="Register" />
                    </p>
                  </form>
                </div>
              </div>
            </div>
            <!-- End of Tabbed panel for Register -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="footerStyle" id="footer">
      <div style="text-align: center; border-top: 2px solid #999; margin-top: 1em;">
      <p><a href="#">Lorum</a> • <a href="#">Ipsum</a> • <a href="#">Dolar</a> • <a href="#">Sic Amet</a> • <a href="#">Consectetur</a></p>
      <p style="font-size: 0.6em;"></p>
       </div>
  </div>
</div>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
</script>
</body>
<!-- InstanceEnd --></html>

<?php if(isset($database)) { $database->close_connection(); } ?>