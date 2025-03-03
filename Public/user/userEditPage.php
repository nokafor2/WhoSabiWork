<?php
require_once("../../includes/initialize.php");
if ($session->is_user_logged_in()) {	 
	if (!$session->is_session_valid()) {
		// logout the user and end the session
		$session->logout();
		if (session_id() == '') {
		  session_start();
		}
		$session->message("Expired session: Please log-in again.");
		redirect_to("../loginPage.php"); 
	} 
} else {
	$session->message("Please log-in properly.");
	redirect_to("../loginPage.php"); 
}

$message = "";
$user_full_name = "";
$user_first_name = "";
$user_last_name = "";
$user_username = "";
$user_email = "";
$user_phone_number = "";
$userId = "";

// Check that only allowed parameters is passed into the form
$get_params = allowed_get_params(['id']);

$security = new Security_Functions();
$encryptionObj = new Encryption();
// the request_is_get() fxn will ensure that a post request was sent from the webpage
if(request_is_get() ) {	
	// Eliminate HTML tags embedded in the URL inputs
	foreach($get_params as $param) {
		// run htmlentities check on the parameters
		// First check if the variable is an array
		if (is_array($param) && isset($param)) {
			foreach ($param as $value) {
				// run htmlentities check on the array parameters
				$params[$value] = h2($value);
			}
		} elseif (isset($param)) {
			// run htmlentities check on the parameters
			$get_params[$param] = h2($param);
		} 
	}
	
	/*
	if (isset($get_params["id"])) {
		// Decrypt the id 
		// $decodedId = urldecode(trim($get_params["id"]));
		// (int)$decryptedId = $encryptionObj->decrypt($decodedId);
		// echo "Decrypted Id is ".$decryptedId."<br/>";
		// $get_params["id"] == $session->user_id
		// $decryptedId == $session->user_id		
	} else {
		global $session;
		$session->message("No User ID was provided.");
		// redirect to home page if no id is provided.
		redirect_to("/index.php");
	} */

	if (isset($session->user_id)) {
		// $userId = $decryptedId;
		$userId = $session->user_id;
		// $userId = (int)$get_params["id"];
	} else {
		global $session;
		// Return an error message to the user and log a spurious attempt to get into someone's profile.
		// $session->message("Invalid user-id received.");
		$session->message("Sorry, you could not be logged in.");
		// redirect to home page / login page if incorrect user id is provided.
		// redirect_to('/Public/user/userEditPage.php?id='. urlencode($session->user_id));
		redirect_to('/Public/loginPage.php?profile=user');
	}
	
	global $database;
	// Sanitize inputs from the form to be passed into the database.
	$userId = sql_prep($userId);
			
	$user = User::find_by_id($userId);
	if (isset($user->first_name)) {
		$user_first_name = $_SESSION['user_first_name'] = sql_prep($user->first_name);
	}
	if (isset($user->last_name)) {
		$user_last_name = $_SESSION['user_last_name'] = sql_prep($user->last_name);
	}
	// Check for the user's full name
	if (is_bool($user)) {
		$user_full_name = " ";
	} else {
		$user_full_name = sql_prep($user->full_name());
	}
	if (isset($user->gender)) {
		$user_gender = $_SESSION['user_gender'] = sql_prep($user->gender);
	}
	if (isset($user->username)) {
		$user_username = $_SESSION['user_username'] = sql_prep($user->username);
	}
	if (isset($user->user_email)) {
		$user_email = $_SESSION['user_email'] = sql_prep($user->user_email);
	}
	if (isset($user->phone_number)) {
		$user_phone_number = $_SESSION['user_phone_number'] = sql_prep($user->phone_number);
	}
	if (isset($user->phone_validated)) {
		$phone_validated = sql_prep($user->phone_validated);
		$_SESSION['phone_validated'] = sql_prep($user->phone_validated);
	}

	// Functions to control appointments 
} elseif(request_is_post() && request_is_same_domain()) {
// Check if the request is post and is from same web page.

	// !$security->csrf_token_is_valid() || !$security->csrf_token_is_recent()
	if(false) {
		$message = "Sorry, request was not valid.";
	} else {
		// CSRF tests passed--form was created by us recently.
		
		// Check that only allowed parameters is passed into the form
		$post_params = allowed_post_params(['submit_first_name', "first_name", "submit_last_name", "last_name", "gender", "submit_gender", "submit_username", "username", "submit_password", "password", "confirm_password", "submit_email", "email", "submit_phone_number", "phone_number", "delete_account", "verifyNumber", "smsToken", "submit_smsToken"]);
		
		// Eliminate HTML tags embedded in the URL inputs
		foreach($post_params as $param) {
			// run htmlentities check on the parameters
			if(isset($post_params[$param])) {
				// run htmlentities check on the parameters
				$post_params[$param] = h2($param);
			} 
		}
		
		$user = User::find_by_id($get_params["id"]);
		
		if (isset($post_params['delete_account'])) {
			$userKey = new User();
			$deactivateUser = $userKey->deactivate($session->user_id);
			if($deactivateUser) {
				$session->message($session->user_full_name." account was successfully deleted.");
				redirect_to('/index.php');
			} else {
				// $session->message("The user could not be deleted.");
				// redirect_to('userEditPage.php?id='.$get_params['id']);
				$message = "The user could not be deleted.";
			}
		}	
	}
	
} else {
	// Spurios log-in attempt to get into a user's account
	$session->message("Improper page request.");
	redirect_to("/index.php");
}

?>


<?php
	$scriptName = scriptPathName();
	// Initialize the header of the webpage
	echo getUniquePageHeader($scriptName);

	$outputMessage = displayMessages();
	if (!empty($outputMessage)) {
		showErrorMessage($outputMessage);
	}
?>

<!-- Contains the header and navigation of the page -->
<div id="topContainer">
	<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
	<?php include_layout_template('navigation_header.php'); ?>
</div>

<!-- Contains the content of the page -->
<div id="container">
  <!-- Beginning of Main Section -->  
  <div id="mainUserEditPage">
	<?php // echo displayMessages(); ?>
	<h2 style="text-align:center"><?php echo ucwords($_SESSION['user_full_name']); ?> Profile</h2>
    
	<div id="TabbedPanels1" class="TabbedPanels">
	  <ul class="TabbedPanelsTabGroup">
	    <li class="TabbedPanelsTab" tabindex="0">Edit  profile</li>
	    <li class="TabbedPanelsTab" tabindex="0">My appointments</li>
	    <li class="TabbedPanelsTab" tabindex="0">Comments</li>
    </ul>
	  <div class="TabbedPanelsContentGroup">
	    <div class="TabbedPanelsContent">
	    	<!-- Begining of Profile Image -->      
        <div id='profileImageDiv'>
       		<div id='showProfileImage'>
       			<img id='avatarImage' src='<?php echo showUserAvatar(); ?>' alt='customer profile image' />
       		</div>
       		<div id='selectProfileImage'>
       			<!-- <h3 class='divHeading'>Upload Profile Photo</h3> -->
       			<div class='divContent'>
       			<input name="avatar_upload" type="file" id="avatar_upload" size="30" maxlength="30" accept="image/" class="fileUpload btnStyle1"/>
              <button type="button" class="fileUploadBtn btnStyle1"><i class="fas fa-image"></i>Upload Profile Photo</button>
              <span class="fileUploadLabel"></span>
              <p id="imgTypeLabel">Allowed images: .jpg, .jpeg, .png, .gif</p>
              <button id="submitAvatar" name="submitAvatar" class="submitBtn btnStyle1">Submit</button>
              <button id="reselectAvatar" name="reselectAvatar" class="submitBtn btnStyle1" >Reselect Avatar</button>
              <progress id="avatarProgressBar" class="progress" value="0" max="100" ></progress>
              <div class="display_img" >
                <img src="../images/emptyImageIcon.png" alt="" id="avatar_show" class="previewImg" >
              </div>
              <div id="avatarErrorReport" ></div>
          	</div>
       		</div>
        </div>
      	<!-- End of Profile Image -->

    		<!-- Beginning of collapsible panel div -->
        <div class="CollapsiblePanelsDiv">
          <div id="CollapsiblePanel1" class="CollapsiblePanel">
              <div class="CollapsiblePanelTab" tabindex="0">
                <label class="labelTitle">First name: </label>
                <p id="firstNameInput" class='profileInput'>
                <?php 
                    if(isset($_SESSION['user_first_name']) AND !empty($_SESSION['user_first_name'])){ 
                        echo ucfirst($_SESSION['user_first_name']); 
                    } else { 
                        echo "&nbsp";
                    } 
                ?>
            	</p>
              </div>
              <div id="firstNamePanelContent" class="CollapsiblePanelContent">
                <form id="first_name_form" action="" method="post" enctype="application/x-www-form-urlencoded" name="first_name_form" class="customer_label">
			  					<?php echo $security->csrf_token_tag(); ?>
                  <label for="first_name">Edit first name</label>
                  <input name="first_name" type="text" id="first_name" class="inputText" size="60" maxlength="60" />
                  <div class="submitClearBtnDiv">
                    <input type="submit" name="submit_first_name" id="submit_first_name" class="btnStyle1" value="Submit" />

                    <button name="clear_first_name" id="clear_first_name" class="clearBtn btnStyle1" >Clear</button>
                  </div>
                </form>
              </div>
          </div>
          <div id="CollapsiblePanel2" class="CollapsiblePanel">
            <div class="CollapsiblePanelTab" tabindex="0">
                <label class="labelTitle">Last name: </label>
                <p id="lastNameInput" class='profileInput'>
                <?php 
                    if(isset($_SESSION['user_last_name']) AND !empty($_SESSION['user_last_name'])){ 
                        echo ucfirst($_SESSION['user_last_name']); 
                    } else { 
                        echo "&nbsp";
                    } 
                ?>
            	</p>
            </div>
            <div id="lastNamePanelContent" class="CollapsiblePanelContent">
              <form id="last_name_form" action="" method="post" enctype="application/x-www-form-urlencoded" name="last_name_form" class="customer_label">
		     			  <?php echo $security->csrf_token_tag(); ?>
                <label for="last_name">Edit last name</label>
                <input name="last_name" type="text" id="last_name" class="inputText" size="60" maxlength="60" />
                <div class="submitClearBtnDiv">
                  <input type="submit" name="submit_last_name" id="submit_last_name" class="btnStyle1" value="Submit" />

                  <button name="clear_last_name" id="clear_last_name" class="clearBtn btnStyle1" >Clear</button>
                </div>
              </form>
            </div>
          </div>
          <div id="CollapsiblePanel3" class="CollapsiblePanel">
            <div class="CollapsiblePanelTab" tabindex="0">
                <label class="labelTitle">Gender: </label>
                <p id="genderInput" class='profileInput'>
                <?php 
                    if(isset($_SESSION['user_gender']) AND !empty($_SESSION['user_gender'])){ 
                        echo ucfirst($_SESSION['user_gender']); 
                    } else { 
                        echo "&nbsp";
                    } 
                ?>
            	</p>
            </div>
            <div id="genderPanelContent" class="CollapsiblePanelContent">
              <form id="gender_form" action="" method="post" enctype="application/x-www-form-urlencoded" name="gender_form" class="customer_label">
			      		<?php echo $security->csrf_token_tag(); ?>
				  			<label>
		          		<input name="gender" type="radio" value="male"id="male" /> Male
		            </label>
			          <label>
			          	<input name="gender" type="radio" value="female" id="female" /> Female
			          </label>
			          <div class="submitClearBtnDiv">
                  <input type="submit" name="submit_gender" id="submit_gender" class="btnStyle1" value="Submit" style="clear:both" />

                  <button name="clear_gender" id="clear_gender" class="clearBtn btnStyle1" >Clear</button>
                </div>
              </form>
            </div>
          </div>
          <div id="CollapsiblePanel4" class="CollapsiblePanel">
            <div class="CollapsiblePanelTab" tabindex="0">
                <label class="labelTitle">Username: </label>
                <p id="usernameInput" class='profileInput'>
                <?php 
                    if(isset($_SESSION['user_username']) AND !empty($_SESSION['user_username'])){ 
                        echo $_SESSION['user_username']; 
                    } else { 
                        echo "&nbsp";
                    } 
                ?>
            	</p>
            </div>
            <div id="usernamePanelContent" class="CollapsiblePanelContent">
              <form id="username_form" action="" method="post" enctype="application/x-www-form-urlencoded" name="username_form" class="customer_label">
		    				<?php echo $security->csrf_token_tag(); ?>
                <label for="username">Edit username</label>
                <input name="username" type="text" id="username_user" class="inputText" size="60" maxlength="60" autocomplete="off" autocorrect="off" autocapitalize="none" oninput="usernameCheck();" onblur="stopUsernameCheck();"/>
                <br />
								<div id="usernameMessage" style="color:red; display:none; margin:0px;"></div>
								<div class="submitClearBtnDiv">
                  <input type="submit" name="submit_username" id="submit_username" class="btnStyle1" value="Submit" />

                  <button name="clear_username" id="clear_username" class="clearBtn btnStyle1" >Clear</button>
                </div>
              </form>
            </div>
          </div>
          <div id="CollapsiblePanel5" class="CollapsiblePanel">
            <div class="CollapsiblePanelTab" tabindex="0">
                <label class="labelTitle">Password: </label>
                <?php echo "&nbsp"; ?>
            </div>
            <div id="passwordPanelContent" class="CollapsiblePanelContent" >
              <form id="password_form" action="" method="post" enctype="application/x-www-form-urlencoded" name="password_form" class="customer_label">
								<?php echo $security->csrf_token_tag(); ?>
                <label for="password">Edit password</label>
                <input name="password" type="password" id="password" class="inputText" size="60" maxlength="60" autocomplete="off" autocorrect="off" autocapitalize="none" />
								<br/>
								<label for="password">Confirm password</label>
								<input name="confirm_password" type="password" id="confirm_password" class="inputText" size="60" maxlength="60" autocomplete="off" autocorrect="off" autocapitalize="none" />
								<div class="submitClearBtnDiv">
                	<input type="submit" name="submit_password" id="submit_password" class="btnStyle1" value="Submit" />
                </div>
              </form>
		  				<div id="passwordMessage" style="color:red; display:none; margin:0px;">Error Display</div>
            </div>
          </div>
          <div id="CollapsiblePanel6" class="CollapsiblePanel">
            <div class="CollapsiblePanelTab" tabindex="0">
              <label class="labelTitle">Email: </label>
              <p id="emailInput" class='profileInput'>
              <?php 
                    if(isset($_SESSION['user_email']) AND !empty($_SESSION['user_email'])){ 
                        echo $_SESSION['user_email']; 
                    } else { 
                        echo "&nbsp";
                    } 
                ?>
            	</p>
            </div>
            <div id="emailPanelContent" class="CollapsiblePanelContent">
              <form id="email_form" action="" method="post" enctype="application/x-www-form-urlencoded" name="email_form" class="customer_label">
								<?php echo $security->csrf_token_tag(); ?>
                <label for="email">Edit email</label>
                <input name="email" type="email" id="email" class="inputText" size="60" maxlength="60" autocomplete="off" autocorrect="off" autocapitalize="none" />
                <div class="submitClearBtnDiv">
                  <input type="submit" name="submit_email" id="submit_email" class="btnStyle1" value="Submit" />

                  <button name="clear_email" id="clear_email" class="clearBtn btnStyle1" >Clear</button>
                </div>
              </form>
            </div>
          </div>
          <div id="CollapsiblePanel7" class="CollapsiblePanel">
            <div class="CollapsiblePanelTab" tabindex="0">
                <label class="labelTitle">Phone number: </label>
                <p id="phoneNumberInput" class='profileInput'>
                <?php 
                    if(isset($_SESSION['user_phone_number']) AND !empty($_SESSION['user_phone_number'])){ 
                        echo $_SESSION['user_phone_number']; 
                    } else { 
                        echo "&nbsp";
                    }
                ?>
                </p>
                <?php
									// $_SESSION['phone_validated']
									if (isset($phone_validated) && !$phone_validated) { ?>
										<p id="validateNumber">Validate phone number</p> 
								<?php	} else { ?>
										<p id="validateNumber" style="display: none;">Validate phone number</p>
								<?php	} ?>
            </div>
            <div id="phoneNumPanelContent" class="CollapsiblePanelContent">
              <form id="phone_number_form" action="" method="post" enctype="application/x-www-form-urlencoded" name="phone_number_form" class="customer_label">
		    				<?php echo $security->csrf_token_tag(); ?>
                <label for="phone_number">Edit phone number</label>
                <input name="phone_number" type="tel" id="phone_number" class="inputText" size="60" maxlength="60" />
                <div class="submitClearBtnDiv">
                  <input type="submit" name="submit_phone_number" id="submit_phone_number" class="btnStyle1" value="Submit" />

                  <button name="clear_phoneNumber" id="clear_phoneNumber" class="clearBtn btnStyle1" >Clear</button>
                </div>
								<?php
								    // $_SESSION['phone_validated']
									if (isset($phone_validated) && !$phone_validated) { ?>
										<div id="verifyPhoneNumDiv" style="margin-top: 20px;">
											<p> Click the button below to receive a token which you would use to verify your phone number. </p>
											<input style="color:#A51300; float:none" name="verifyNumber" type="submit" id="verifyNumber" class="btnStyle1" value="Verify phone number" />
										</div>
								<?php } ?>
								
								<div id="enterTokenDiv">
									<p> Enter the token received from your phone in the input field below. </p>
									<input style="float:none; margin-bottom: 5px;" name="smsToken" type="text" id="smsToken" size="60" maxlength="60" />
									<input type="submit" name="submit_smsToken" id="submit_smsToken" class="btnStyle1" value="Submit Token" />
								</div>
              </form>
            </div>
          </div>
          <!--
          <div id="delete">
              <!-- <a href="delete_user.php?id=<?php //echo $userId; ?>">Delete Account</a> --> <!--
			  	<form action="" method="post" enctype="application/x-www-form-urlencoded" name="delete_form" id="delete_form">
				    <?php // echo $security->csrf_token_tag(); ?>
						<input type="submit" name="delete_account" id="delete_account" class="btnStyle1" value="Delete Account" />
			  	</form>
           </div>
       		-->
        </div>
        <!-- End of collapsible panel div -->
      </div>
	    <div class="TabbedPanelsContent">
			<div class="appointmentRequestedList">
				<h3 class="divHeading">Appointments requested with technicians</h3>
				<?php echo displayDeclinedAppointments(); ?>
				<?php echo displayCanceledAppointments(); ?>
				<?php echo displayRequestedAppointments(); ?>
			</div>
			<div class="confirmedAppointmentList">
				<h3 class="divHeading">Appointments confirmed with technicians</h3>
				<?php echo displayConfirmedAppointments(); ?>
			</div>
        </div>
		<div class="TabbedPanelsContent">
			<div id="comments">
				<div id="feedback"></div>
				<?php 
					// Find all the comments made by the user, then find all the replies to the comments
					// Get all the replies from customers to this user
					$comments = User_Comment::find_comments_made_by_user($session->user_id, "user");
					
					$i = 0; 
					foreach($comments as $comment):
				?>
				<div class="comment" id="comment<?php echo $i; ?>">
					<div class="authorDate" id="authorDate<?php echo $i; ?>">
						<div class="author" id="author<?php echo $i; ?>" >
							Your comment on
							<?php 
								$commentCustomerId = $comment->customers_id;
								$customer = Customer::find_by_id($commentCustomerId);
								$full_name = $customer->full_name();
								echo $full_name; 
							?>:
						</div>
						<div class="meta-info" id="meta-info<?php echo $i; ?>">
							<!-- The datetime_to_text() function is in the function file. It can be referenced from any class. -->
							<?php echo datetime_to_text($comment->created); ?>
						</div>
					</div>
					<div class="commentBody" id="commentBody<?php echo $i; ?>" <?php echo "commentid".$i; ?>="<?php echo $comment->id; ?>">
						<?php 
						// The strip_tags will not allow any other HTML tags to be used for formating except these tags <strong><em><p>
						echo strip_tags($comment->body, '<strong><em><p>'); 
						?>
					</div>
					<div class="replyContainer" id="replyContainer<?php echo $i; ?>">
					<?php $replies = User_Reply::find_replies_on_comment($commentCustomerId, $comment->id);
					$j = 0; ?>
					<?php foreach($replies as $reply): ?>
						
						<div id="replyComment<?php echo $i; echo $j; ?>" class="replyComment">
							<div class="authorDate" id="replyAuthorDate<?php echo $i; echo $j; ?>">
								<div class="author" id="replyAuthor<?php echo $i; echo $j; ?>">
									<?php 
										$replyCustomerId = $reply->customers_id;
										$customer = Customer::find_by_id($replyCustomerId);
										$full_name = $customer->full_name();
										echo $full_name; 
									?> replied:
								</div>
								<div class="meta-info" id="meta-info<?php echo $i; echo $j; ?>">
									<?php echo datetime_to_text($reply->created); ?>
								</div>
							</div>
							<div class="commentBody" id="replyCommentBody<?php echo $i; echo $j; ?>">
								<?php 
								echo strip_tags($reply->body, "<strong><em><p>"); 
								?>
							</div>
							<!--
							<button class="replyBtn btnStyle3" id="replyBtn<?php // echo $j; ?>" onclick="reply(this)">Reply Comment</button>
							-->
						</div>
						<?php $j++ ?>
					<?php endforeach; ?>
					</div>
				</div>
				<?php endforeach; ?>
				<!-- If no comment, display there is none -->
				<?php if(empty($comments)) { echo "You have no comments made yet."; } ?>
			</div>
			<!-- This is a hidden div that only gets inserted when it is clicked -->
			<div id="replyDiv">
				<textarea id="replyTextarea" class="replyTextarea" name="message_content" rows="2"></textarea>
				<button id="submitReply" class="submitReply btnStyle3" customerId="<?php echo urlencode($customerID); ?>" onclick="addReply(this)">Reply</button>
			</div>
		</div>
      </div>
    </div> 
          
   	
  </div> <!-- End of Main Section -->
  
  <!-- Display the footer section -->
  <?php include_layout_template('navigation_footer.php'); ?>
</div>

<!-- This control the modal for the user's avatar -->
<div class="avatarUploadModal">
	<div class="avatarUploadContent">
		<!-- <div class="closeAvatarUploadModal" >+</div> -->
		
  		<div id='showImage'></div>
   		<div id='decisionBtnsDiv'>
   			<button id='rotateRightBtn' class='btnStyle1' data-deg='90'>Rotate</button>
   			<button id='cropImage' class=' croppie-result btnStyle1'>Crop</button>
				<button id='changeImage' class='btnStyle1'>Cancel Upload</button>
			</div>
			<div id='displayCrop' ></div>
		
	</div>
</div>

<!-- Loader -->
<div class="loader">
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

<?php // Close the database when done deleting
	if(isset($database)) { $database->close_connection(); } 
?>