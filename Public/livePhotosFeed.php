<?php
	require_once("../includes/initialize.php");
	global $session;
	
	if (request_is_get() || request_is_post()) {
		/* if ($session->is_customer_logged_in() || $session->is_user_logged_in()) {	 
			if (!$session->is_session_valid()) {
				$session->message("Expired session: Please log-in again.");
				redirect_to("loginPage.php"); 
			} 
		} else {
			$session->message("Please log-in properly.");
			redirect_to("loginPage.php"); 
		} */
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

<div id="container" >
	<div id="mainLiveFeed">		    
		<div id="adContainer1"></div>
	</div>		

	<!-- Display the footer section -->
	<?php include_layout_template('navigation_footer.php'); ?>	  
</div>

<!-- This display the modal to project a larger image when clicked-->
<div class="bg-modal" >
	<div class="modal-content" >
		<div class="close-btn" >+</div>
		<div id="enlargedAdImg">
			<img id="enlargedAdPix" src="" alt="Enlarged selected photo of entrepreneur" >
		</div> 
		<div id="modalPhotoBtnDiv">
			<p id="imgCaptionModal" ></p>
			<div id="photoCommentDisplay">
				<div id="feedback"></div>
				<p id='totalComments'></p>
				<div id="photoComments">
				</div>
			</div>

			<?php if ($session->is_user_logged_in() || $session->is_customer_logged_in()) { ?>
				<textarea name="commentTextarea" id="commentTextarea" rows="1" placeholder="Leave a comment..." ></textarea>
				<button id="submitComment" class="btnStyle1" >Comment</button>
			<?php } else { ?>
				<p>If you want to leave a comment?</p>
				<p id="signInSignUpInfo"><a class="btnStyle1" href="<?php echo returnPageTo('livePhotosFeed.php', '/Public/selectProfileType.php?page=signIn', 0);?>">Please Sign In</a> <span style="margin-left:20px; margin-right: 20px;">OR</span> <a class="btnStyle1" href="<?php echo returnPageTo('livePhotosFeed.php', '/Public/selectProfileType.php?page=signUp', 0);?>">Please Sign Up</a></p>
			<?php } ?>
		</div>
	</div>
</div>

<div class="loader">
	<img src="images/utilityImages/ajax-loader1.gif" alt="Loading..." />
</div>

<div id="modalSignIn">
	<!-- <div id="closeSignInModal" >+</div> -->
	<div id="modalsignIncontent" >
		<p>If you want to like the photo?</p>
		<p id="signInSignUpInfo"><a class="btnStyle1" href="<?php echo returnPageTo('livePhotosFeed.php', '/Public/selectProfileType.php?page=signIn', 0)?>">Please Sign In</a> <span style="margin-left:20px; margin-right: 20px;">OR</span> <a class="btnStyle1" href="<?php echo returnPageTo('livePhotosFeed.php', '/Public/selectProfileType.php?page=signUp', 0)?>">Please Sign Up</a>
		</p>
		<button id="closeSignInModal" class="btnStyle1">close</button>
	</div>
</div>
	
<?php
	// Initialize the footer of the webpage
	echo getUniquePageFooter($scriptName);
?>
