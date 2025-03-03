<?php
require_once("../includes/initialize.php");

$message = "";

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
						?>
					
						<!-- First name -->
					  <input name="first_name" type="text" id="first_name_user" size="60" maxlength="50" placeholder="First name" value="" onblur="validateInput(this);" />
					  <br />
					  <div id="first_name_message" name="first_name_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

					  <!-- Last name -->
					  <input name="last_name" type="text" id="last_name_user" size="60" maxlength="50" placeholder="Last name" value="" onblur="validateInput(this);" />
					  <br />
					  <div id="last_name_message" name="last_name_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

					  <!-- Gender -->
					  <p style="padding-left: 10px;">
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
			      </p>
		        <div id="gender_message" name="gender_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

					  <!-- Username -->
					  <input name="username" type="text" id="username_user" size="60" maxlength="50" placeholder="Username" value="" onblur="usernameCheck();" autocomplete="off" autocorrect="off" autocapitalize="none"/>
					  <br />
					  <div id="username_message" name="username_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
					  
					  <!-- Password -->
					  <input name="password" type="password" id="password_user" size="60" maxlength="50" placeholder="Password" autocomplete="off" autocorrect="off" autocapitalize="none" onblur="validateInput(this);" />
					  <br />
					  <div id="password_message" name="password_user_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
					  
					  <!-- Confirm password -->
					  <input name="confirm_password" type="password" id="confirm_password_user" size="60" maxlength="50" placeholder="Confirm password" autocomplete="off" autocorrect="off" autocapitalize="none" />
					  <br />
					  <div id="confirm_password_message" name="confirm_password_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
					  
					  <!-- Phone number -->
					  <p class="smallText2">Enter phone number in this format 08012345690</p>
					  <input name="phone_number" type="tel" id="phone_number_user" size="60" maxlength="15" placeholder="Phone number" value="" onblur="validateInput(this);" />
					  <div id="phone_number_message" name="phone_number_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
					  
					  <!-- Email address -->
					  <input name="email" type="email" id="email_user" size="60" maxlength="50" placeholder="Email address" value="" autocomplete="off" autocorrect="off" autocapitalize="none" onblur="validateInput(this);" />
					  <br />
					  <div id="email_message" name="email_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
					  
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
  	include_layout_template('navigation_footer.php'); 
  ?>
</div> <!-- End of Container -->

<!-- Loader -->
<div class="loader" style="background-color: rgba(0, 0, 0, 0.5);">
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

<?php
	// Initialize the footer of the webpage
	echo getUniquePageFooter($scriptName);
?>

<?php if(isset($database)) { $database->close_connection(); } ?>