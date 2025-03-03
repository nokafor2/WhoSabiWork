<?php 
require_once("../includes/initialize.php");

$message = "";
/*
// Check that only allowed parameters is passed into the form
$post_params = allowed_post_params(['username', 'password', 'first_name', 'last_name', 'phone_number', 'email', 'message_content', 'message_subject', 'submit_complain']);

// Eliminate HTML tags embedded in the form inputs
foreach($post_params as $param) {
	// run htmlentities check on the parameters
	if(isset($post_params[$param])) {
		// run htmlentities check on the parameters
		$post_params[$param] = h2($param);
	} 
}

if(request_is_post() && request_is_same_domain()) {
	
	if(!csrf_token_is_valid() || !csrf_token_is_recent()) {
		$message = "Sorry, request was not valid.";
	} else {
	// CSRF tests passed--form was created by us recently.

		if (isset($post_params["submit_complain"])) {
			$message_subject;
			
			$first_name = trim($post_params['first_name']);
			$last_name = trim($post_params['last_name']);
			$email = trim($post_params['email']);
			$phone_number = trim($post_params['phone_number']);
			$message_content = trim($post_params['message_content']);
			
			// Validate a message subject was selected
			if (array_key_exists("message_subject", $post_params)) {
				if (!isset($post_params["message_subject"]) || ($post_params["message_subject"] === "Select")){
					$validate->errors["message_subject"] = "Select a subject for your complain.";
				} else {
					// save the variable 
					$message_subject = $post_params["message_subject"];
				}
			}
			// This function is used to validate other inputs from the user
			$validate->validate_name_update();
			
			if (empty($validate->errors)) {
				$usersFeedback = new Users_Feedback();
				$usersFeedback->first_name = $first_name;
				$usersFeedback->last_name = $last_name;
				$usersFeedback->phone_number = $phone_number;
				$usersFeedback->email_address = $email;
				$usersFeedback->message_subject = $message_subject;
				$usersFeedback->message_content = $message_content;
				$usersFeedback->date_created = current_Date_Time();
				
				if($usersFeedback->save()) {
					// Success
					$message = "Thank you for the feedback. We would get back to you as soon as possible.";
					// This message should be saved in the session.
					// $session->message("Thank you for the feedback. We would get back to you as soon as possible.");

					// Send an email to support service
					$fromEmail = 'support@whosabiwork.com';
					$fromName = 'WhoSabiWork';
					$to = 'support@whosabiwork.com';
					$title = 'User Feedback';
					$body = makeEmailMessage($first_name, $last_name, $phone_number, $email, $message_subject, $message_content);
					$outcome = sendMailFxn($fromEmail, $fromName, $to, $title, $body);

					// Send a mail to the user
					$fromEmail = 'support@whosabiwork.com';
					$fromName = 'WhoSabiWork';
					$to = $email;
					$title = 'WhoSabiWork has Received Your Feedback';
					$body = makeEmailMessageUser($first_name, $last_name, $message_subject, $message_content);
					$outcome = sendMailFxn($fromEmail, $fromName, $to, $title, $body);

				} else {
					$message = "An error occurred while saving.";
				}
			} else {
				// $message = "There was an error during validation. ";
			}
		} else {
			$first_name = ""; $last_name = ""; $phone_number = ""; $email = ""; $message_content = "";
		}

	}
} else {
	$first_name = ""; $last_name = ""; $phone_number = ""; $email = ""; $message_content = "";
}

function makeEmailMessage($firstName, $lastName, $phoneNumber, $email, $subject, $content) {
	$emailMessage = '<img src="cid:whoSabiWorkLogo">';
	$emailMessage .= '<p>A customer has sent a feedback.</p>';
	$emailMessage .= '<p>Name: '.$firstName.' '.$lastName.'</p>';
	$emailMessage .= '<p>Phone number: '.$phoneNumber.'</p>';
	$emailMessage .= '<p>Email address: '.$email.'</p>';
	$emailMessage .= '<p>Message subject: '.$subject.'</p>';
	$emailMessage .= '<p>Message content: '.$content.'<p>';
	$emailMessage .= '<p>Date: '.date_to_text(current_Date_Time()).'</p>';

	return $emailMessage;
}

function makeEmailMessageUser($firstName, $lastName, $subject, $content) {
	$emailMessage = '<img src="cid:whoSabiWorkLogo">';
	$emailMessage .= '<p>Good day '.$firstName.' '.$lastName.',</p>';
	$emailMessage .= '<p>Your feedback has been well received. We will get back to you as soon as possible.</p>';
	$emailMessage .= '<p>Message subject: '.$subject.'</p>';
	$emailMessage .= '<p>Message content: '.$content.'<p>';
	$emailMessage .= '<p>Date: '.date_to_text(current_Date_Time()).'</p>';
	$emailMessage .= '<p>WhoSabiWork</p>';

	return $emailMessage;
}
*/
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
<title>Contact WhoSabiWork</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" type="image/png" href="/Public/images/WhoSabiWorkLogo.png" />
<link href="./stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />
<link href="../HomePageStyles.css" rel="stylesheet" type="text/css" />
<link href="./stylesheets/contactUsStyle.css" rel="stylesheet" type="text/css" />

<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<!-- Javascripts and JQuery -->
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>

<script src="./javascripts/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="./javascripts/contactUsJSs.js" defer></script>
</head>

<body>
	<?php
		$outputMessage = displayMessages();
		if (!empty($outputMessage)) {
			showErrorMessage($outputMessage);
		}
	?>
	<div id="topContainer">
		<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
		<?php include_layout_template('public_header.php'); ?>
	</div>
	<div id="container">
	  <!-- Begining of Main Section  -->
	  <div id="mainContacts" >	
		<?php // echo displayMessages();	?>
	    <h1 class="pageHeading" >Contact Us Here</h1>
		<div class="contactContents">
			<div class="contactFormDiv">
				<p>Thank you for using this website. If you have any suggestion on how this website can be improved upon, please don't hesitate to notify us on our contact page. Thank you for your patronage.</p>
				<p>You can also make your complaints to us here. We will get back to you as soon as possible.</p>
				<form id="form1" name="form1" method="post" action="">
					  <?php echo csrf_token_tag(); ?>
					  <!-- First name -->
					  <input name="first_name" type="text" id="first_name"  maxlength="50" value="<?php echo htmlentities($first_name); ?>" placeholder="First name" />
					  
					  <!-- Last name -->
					  <input name="last_name" type="text" id="last_name"  maxlength="50" value="<?php echo htmlentities($last_name); ?>" placeholder="Last name"/>
					  
					  <!--Phone number -->
					  <input name="phone_number" type="text" id="phone_number" size="30" maxlength="15" value="<?php  echo htmlentities($phone_number);?>" placeholder="Phone number"/>
					  
					  <!-- Email address -->
					  <input name="email" type="text" id="email_address" size="30" maxlength="50" value="<?php echo htmlentities($email); ?>" placeholder="Email address"/>
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
					  <textarea name="message_content" id="message_content" cols="40" rows="5" value="<?php echo htmlentities($message_content); ?>" placeholder="Enter your message here" ></textarea>
					  <label id="wordCountLabel">
					  	Character Count:
					  	<input type="text" id="wordCount" readonly value="0/250" style="width: 70px; text-align: right;">
					  </label>
					<input name="submit_complain" type="submit" id="submit_complain" value="Submit" />
				</form>
			</div>
			<div class="contactInfoDiv">
				<p class="headingText">Phone Support</p>
				<p class="details">0805-736-8560</p>
				<p class="details" style="display: inline-block;">0907-004-6964</p>
				<p class="smallText3" style="display: inline;">Contact for both calls and whatsapp messages</p>
				<p class="details2">8am - 5pm (Monday - Friday)</p>
				<p class="details2">8am - 12noon Weekends</p>
			</div>
			<div class="contactInfoDiv">
				<p class="headingText">Email Support</p>
				<p class="details" style="font-size: 20px;">support@whosabiwork.com</p>
				<p class="details2">We would ensure to get back to you.</p>
			</div>
		</div>
	  </div> <!-- End of Main Section  -->
	  
	  <!-- Display the footer section -->
	  <?php include_layout_template('public_footer.php'); ?>
	  
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
		<img src="../images/utilityImages/ajax-loader1.gif" alt="Loading..." />
	</div>

	<script type="text/javascript">
	var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
	</script>
	<script type="text/javascript" src="./javascripts/genericJSs.js"></script>
</body>
</html>