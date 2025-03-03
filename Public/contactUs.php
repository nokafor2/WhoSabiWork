<?php 
require_once("../includes/initialize.php");

$message = "";
?>

<?php
	$scriptName = scriptPathName();
	// Initialize the header of the webpage
	echo getUniquePageHeader($scriptName);
?>

<?php
	$outputMessage = displayMessages();
	if (!empty($outputMessage)) {
		showErrorMessage($outputMessage);
	}
?>
<div id="topContainer">
	<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
	<?php include_layout_template('navigation_header.php'); ?>
</div>
<div id="container">
  <!-- Begining of Main Section  -->
  <div id="mainContacts" >	
	<?php // echo displayMessages();	?>
    <h2 class="pageHeading" >Contact Us Here</h1>
	<div class="contactContents">
		<div class="contactFormDiv">
			<p>Thank you for using this website. If you have any suggestion on how this website can be improved upon, please don't hesitate to notify us on our contact page. Thank you for your patronage.</p>
			<p>You can also make your complaints to us here. We will get back to you as soon as possible.</p>
			<form id="form1" name="form1" method="post" action="">
			  <?php echo csrf_token_tag(); ?>
			  <!-- First name -->
			  <input name="first_name" type="text" id="first_name"  maxlength="50" placeholder="First name" />
			  
			  <!-- Last name -->
			  <input name="last_name" type="text" id="last_name"  maxlength="50" placeholder="Last name"/>
			  
			  <!--Phone number -->
			  <input name="phone_number" type="text" id="phone_number" size="30" maxlength="15"  placeholder="Phone number"/>
			  
			  <!-- Email address -->
			  <input name="email" type="text" id="email_address" size="30" maxlength="50" placeholder="Email address"/>
			  <br />
			  <label for="message_subject" class="fontStyle">Specify how we can assist you </label> 
			  <select name="message_subject" id="message_subject" class="fontStyle" >
				<option id="select"> Select </option>
				<option id="complain"> Complain </option>
				<option id="suggestion"> Suggestion </option>
				<option id="request"> Request </option>
				<option id="other"> Other </option>
			  </select>
			  <br/>
			  <textarea name="message_content" id="message_content" cols="40" rows="5" placeholder="Enter your message here" ></textarea>
			  <label id="wordCountLabel">
			  	Character Count:
			  	<input type="text" id="wordCount" readonly value="0/250" style="width: 70px; text-align: right;">
			  </label>
				<input name="submit_complain" type="submit" id="submit_complain" value="Submit" />
			</form>
		</div>
		<div class="contactInfoDiv">
			<p class="headingText">Phone Support</p>
			<p class="details"><i class="fas fa-phone fa-1x"></i>0805-736-8560</p>
			<p class="details" style="display: inline-block;"><i class="fas fa-phone fa-1x"></i><i class="fab fa-whatsapp fa-1x" style="color: #25d366; margin-right: 5px;"></i>0907-004-6964</p>
			<!-- <p class="smallText3" style="display: inline;">Contact for both calls and whatsapp messages</p> -->
			<p class="details2">8am - 5pm (Monday - Friday)</p>
			<p class="details2">8am - 12noon Weekends</p>
		</div>
		<div class="contactInfoDiv">
			<p class="headingText">Email Support</p>
			<p class="details" style="font-size: 20px;"><i class="fas fa-envelope fa-1x"></i>support@whosabiwork.com</p>
			<p class="details2">We would ensure to get back to you.</p>
		</div>
	</div>
  </div> <!-- End of Main Section  -->
  
  <!-- Display the footer section -->
  <?php include_layout_template('navigation_footer.php'); ?>
  
</div>

<!-- Modal for the display of error messages -->
<div class="messageModal">
	<div class="messageContainer">
		<div id="messageHead">Error</div>
		<div id="messageContent">Message to appear here.</div>
		<button id="closeBtn" class="btnStyle1">Close</button>
	</div>
</div>

<!-- Loader -->
<div class="loader">
	<img src="images/utilityImages/ajax-loader1.gif" alt="Loading..." />
</div>

<?php
	// Initialize the footer of the webpage
	echo getUniquePageFooter($scriptName);
?>