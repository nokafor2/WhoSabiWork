<?php
require_once("../includes/initialize.php");

function showUserAvatar() {
	global $session;
	$output = "";
	$userId = $session->user_id;
	$photoObj = User_Photograph::find_by_userId($userId);
	// Check if a record was found in the database
	if (!empty($photoObj)) {
		// $imgPath = '../'.$photoObj->image_path();		
		$imgPath = navigateToImagesFolder().$photoObj->filename;	
		if (file_exists($imgPath)) {
			$output .= $imgPath;
		} else {
			$output .= navigateToImagesFolder().'emptyImageIcon.png';
		}		
	} else {
		$output .= navigateToImagesFolder().'emptyImageIcon.png';
	}

	return $output;
}

function showAvatar() {
	global $session;
	$output = "";
	$customerId = $session->customer_id;
	$photoObj = Photograph::find_avatar($_SESSION['customer_id']);
	// Check if a record was found in the database
	if (!empty($photoObj)) {
		$imgPath = navigateToImagesFolder().$photoObj->filename;	
		if (file_exists($imgPath)) {
			$output .= $imgPath;
		} else {
			$output .= navigateToImagesFolder().'emptyImageIcon.png';
		}
	} else {
		$output .= navigateToImagesFolder().'emptyImageIcon.png';
	}

	return $output;
}

// This function will display the Sign In and Sign Out button if not logged in
function displayGlobalNav() {
	global $session;
	$output = "";
	if (!$session->is_user_logged_in() && !$session->is_customer_logged_in() && !$session->is_admin_logged_in()) {
		// <!-- This will be floated to the right -->
		$output .= "<a href='/Public/selectProfileType.php?page=signUp'>
			<li id='signUp'><i class='fas fa-briefcase'></i>Sign Up</li>
		</a>";
		$output .= "<a href='/Public/selectProfileType.php?page=signIn'>
			<li id='signIn'><i class='fas fa-sign-in-alt'></i>Sign In</li>
		</a>";
	} else {
		$output .= '<div id="mobileProfileInfo">';
		$output .= mobileProfileInfo();
		$output .= '</div>';
	}
	
	return $output;
}

function accountPreview() {
	global $session;
	$output = "";
	if ($session->is_user_logged_in()) {
		// Display user accountInfo
		$user = User::find_by_id($session->user_id);
		$phoneNumber = $user->phone_number;
		$firstName = $user->first_name;

		$output .= "<div id='accountPreview'>";
		$output .= "<img id='avatarPreview' src='".showUserAvatar()."' alt='profile image' />";
		$output .= "<p id='namePreview'>".$firstName."</p>";
		$output .= "</div>";
	} elseif ($session->is_customer_logged_in()) {
		// Display customer accountInfo
		$customer = Customer::find_by_id($session->customer_id);
		$phoneNumber = $customer->phone_number;
		$firstName = $customer->first_name;

		$output .= "<div id='accountPreview'>";
		$output .= "<img id='avatarPreview' src='".showAvatar()."' alt='profile image' />";
		$output .= "<p id='namePreview'>".$firstName."</p>";
		$output .= "</div>";
	} elseif ($session->is_admin_logged_in()) {
		// Display admin accountInfo
		$admin = Admin::find_by_id($session->admin_id);
		$phoneNumber = $admin->phone_number;
		$firstName = $admin->first_name;

		$output .= "<div id='accountPreview'>";
		$output .= "<p id='namePreview2'>Admin: ".ucwords($firstName)."</p>";
		$output .= "</div>";
	}

	return $output;
}

function mobileAccountPreview() {
	global $session;
	$output = "";
	if ($session->is_user_logged_in()) {
		// Display user accountInfo
		$user = User::find_by_id($session->user_id);
		$phoneNumber = $user->phone_number;
		$firstName = $user->first_name;

		$output .= "<div id='mobileAccountPreview'>";
		$output .= "<img id='avatarPreview' src='".showUserAvatar()."' alt='profile image' />";
		$output .= "<p id='namePreview'>".$firstName."</p>";
		$output .= "</div>";

		// For cancel button
		$output .= '<input type="checkbox" name="check" id="check">';
		$output .= '<label for="check">';
		$output .= '<i class="fas fa-times" id="cancel2"></i>';
		$output .= '</label>';
	} elseif ($session->is_customer_logged_in()) {
		// Display customer accountInfo
		$customer = Customer::find_by_id($session->customer_id);
		$phoneNumber = $customer->phone_number;
		$firstName = $customer->first_name;

		$output .= "<div id='mobileAccountPreview'>";
		$output .= "<img id='avatarPreview' src='".showAvatar()."' alt='profile image' />";
		$output .= "<p id='namePreview'>".$firstName."</p>";
		$output .= "</div>";

		// For cancel button
		$output .= '<input type="checkbox" name="check" id="check">';
		$output .= '<label for="check">';
		$output .= '<i class="fas fa-times" id="cancel2"></i>';
		$output .= '</label>';
	} elseif ($session->is_admin_logged_in()) {
		// Display admin accountInfo
		$admin = Admin::find_by_id($session->admin_id);
		$phoneNumber = $admin->phone_number;
		$firstName = $admin->first_name;

		$output .= "<div id='mobileAccountPreview'>";
		$output .= "<p id='namePreview2'>Admin: ".ucwords($firstName)."</p>";
		$output .= "</div>";

		// For cancel button
		$output .= '<input type="checkbox" name="check" id="check">';
		$output .= '<label for="check">';
		$output .= '<i class="fas fa-times" id="cancel2"></i>';
		$output .= '</label>';
	} else {
		$output .= '<input type="checkbox" name="check" id="check">';
		$output .= '<label for="check">';
		$output .= '<i class="fas fa-bars" id="menuBtn"></i>';
		$output .= '<i class="fas fa-times" id="cancel"></i>';
		$output .= '</label>';
	}

	return $output;
}

function profileInfo() {
	global $session;
	$output = "";
	if ($session->is_user_logged_in()) {
		// Display user accountInfo
		$user = User::find_by_id($session->user_id);
		$phoneNumber = $user->phone_number;
		$firstName = $user->first_name;

		$output .= "<div id='profilePreviewDiv'>";
		$output .= "<img id='avatarPreview2' src='".showUserAvatar()."' alt='profile image' />";
		$output .= "</div>";

		$output .= "<div id='accountInfoDiv' class='accountInfoDiv'>";
		$output .= "<p id='accountFullname' class='accountInfoDetails'>".ucwords($_SESSION['user_full_name'])." </p>";
		$output .= "<p id='accountNumber' class='accountInfoDetails'><i class='fas fa-phone'></i>".$phoneNumber."</p>";
		$output .= "</div>";
		$output .= "<div class='linkBtnDiv'> 
			<button id='profileBtn' class='globalNavBtn' ><i class='fas fa-briefcase'></i>My Profile</button>";
		$output .= '<button id="logoutBtn" class="globalNavBtn" name="logoutBtn" class="linkStyleButton" ><i class="fas fa-sign-out-alt"></i>Logout</button>';
		$output .= "</div>";
	} elseif ($session->is_customer_logged_in()) {
		// Display customer accountInfo
		$customer = Customer::find_by_id($session->customer_id);
		$phoneNumber = $customer->phone_number;
		$firstName = $customer->first_name;

		$output .= "<div id='profilePreviewDiv'>";
		$output .= "<img id='avatarPreview2' src='".showAvatar()."' alt='profile image' />";
		$output .= "</div>";

		$output .= "<div id='accountInfoDiv' class='accountInfoDiv'>";
		$output .= "<p id='accountFullname' class='accountInfoDetails'>".ucwords($_SESSION['customer_full_name'])." </p>";
		$output .= "<p id='accountNumber' class='accountInfoDetails'><i class='fas fa-phone'></i>".$phoneNumber."</p>";
		$output .= "</div>";
		$output .= "<div class='linkBtnDiv'> 
			<button id='profileBtn' class='globalNavBtn' ><i class='fas fa-briefcase'></i>My Profile</button>";
		$output .= '<button id="logoutBtn" class="globalNavBtn" name="logoutBtn" class="linkStyleButton" ><i class="fas fa-sign-out-alt"></i>Logout</button>';
		$output .= "</div>";
	} elseif ($session->is_admin_logged_in()) {
		// Display admin accountInfo
		$admin = Admin::find_by_id($session->admin_id);
		$phoneNumber = $admin->phone_number;
		$firstName = $admin->first_name;

		$output .= "<p id='accountFullname'>".ucwords($_SESSION['admin_full_name'])."</p>";
		$output .= "<p id='accountNumber'><i class='fas fa-phone'></i>".$phoneNumber."</p>";
		$output .= "<div class='linkBtnDiv'> 
			<button id='profileBtn' class='globalNavBtn' ><i class='fas fa-briefcase'></i>Admin Page</button>";
		$output .= '<button id="logoutBtn" class="globalNavBtn" name="logoutBtn" class="linkStyleButton" ><i class="fas fa-sign-out-alt"></i>Logout</button>';
		$output .= "</div>";
	} 

	$finalOutput  = '<div id="accountInfo" class="accountInfo" >';
	$finalOutput .= '<div class="accountInfoContainer" >';
	$finalOutput .= $output;
	$finalOutput .= '</div>';
	$finalOutput .= '</div>';

	return $finalOutput;
}

function mobileProfileInfo() {
	global $session;
	$output = "";
	if ($session->is_user_logged_in()) {
		// Display user accountInfo
		$user = User::find_by_id($session->user_id);
		$phoneNumber = $user->phone_number;
		$firstName = $user->first_name;

		$output .= "<div id='mobileImgNameDiv'>";
		$output .= "<div id='profilePreviewDiv'>";
		$output .= "<img id='avatarPreview2' src='".showUserAvatar()."' alt='profile image' />";
		$output .= "</div>";
		$output .= "<div id='mobileNamePhoneDiv'>";
		$output .= "<p class='mobileProfileDetails' id='profileFullname'>".ucwords($_SESSION['user_full_name'])." </p>";
		$output .= "<p class='mobileProfileDetails' id='profileNumber'><i class='fas fa-phone'></i>".$phoneNumber."</p>";
		$output .= "</div>";
		$output .= "</div>";

		$output .= "<button id='profileBtn' class='mobileProfileDetails globalNavBtn' ><i class='fas fa-briefcase'></i>My Profile</button>";
		$output .= '<button id="mobilelogoutBtn" class="mobileProfileDetails globalNavBtn" name="logoutBtn" class="linkStyleButton" ><i class="fas fa-sign-out-alt"></i>Logout</button>';
	} elseif ($session->is_customer_logged_in()) {
		// Display customer accountInfo
		$customer = Customer::find_by_id($session->customer_id);
		$phoneNumber = $customer->phone_number;
		$firstName = $customer->first_name;

		$output .= "<div id='mobileImgNameDiv'>";
		$output .= "<div id='profilePreviewDiv'>";
		$output .= "<img id='avatarPreview2' src='".showAvatar()."' alt='profile image' />";
		$output .= "</div>";
		$output .= "<div id='mobileNamePhoneDiv'>";
		$output .= "<p class='mobileProfileDetails' id='profileFullname'>".ucwords($_SESSION['customer_full_name'])." </p>";
		$output .= "<p class='mobileProfileDetails' id='profileNumber'><i class='fas fa-phone'></i>".$phoneNumber."</p>";
		$output .= "</div>";
		$output .= "</div>";

		$output .= "<button id='profileBtn' class='mobileProfileDetails globalNavBtn' ><i class='fas fa-briefcase'></i>My Profile</button>";
		$output .= '<button id="mobilelogoutBtn" class="mobileProfileDetails globalNavBtn" name="logoutBtn" class="linkStyleButton" ><i class="fas fa-sign-out-alt"></i>Logout</button>';
	} elseif ($session->is_admin_logged_in()) {
		// Display admin accountInfo
		$admin = Admin::find_by_id($session->admin_id);
		$phoneNumber = $admin->phone_number;
		$firstName = $admin->first_name;

		$output .= "<div id='mobileNamePhoneDiv'>";
		$output .= "<p class='mobileProfileDetails' id='profileFullname'>Admin: ".ucwords($_SESSION['admin_full_name'])." </p>";
		$output .= "<p class='mobileProfileDetails' id='profileNumber'><i class='fas fa-phone'></i>".$phoneNumber."</p>";
		$output .= "</div>";

		$output .= "<button id='profileBtn' class='mobileProfileDetails globalNavBtn' ><i class='fas fa-briefcase'></i>My Profile</button>";
		$output .= '<button id="mobilelogoutBtn" class="mobileProfileDetails globalNavBtn" name="logoutBtn" class="linkStyleButton" ><i class="fas fa-sign-out-alt"></i>Logout</button>';
	}

	return $output;
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Header Demo</title>
	<link rel="shortcut icon" type="image/png" href="/Public/images/utilityImages/WhoSabiWorkLogo.png" />
	<!-- Load CSS files -->
	<link href="/Public/stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />
	<link href="/Public/stylesheets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />
	<link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet" type="text/css" />
	<link href="/Public/stylesheets/homePageStyles.css" rel="stylesheet" type="text/css" />
	<link href="/Public/stylesheets/navMenuBar2.css" rel="stylesheet" type="text/css" />
	<!-- <link href="/Public/stylesheets/SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" /> -->
	<style type="text/css">
		
	</style>


	<!-- Load Javascript files -->
	<script src="/Public/javascripts/jquery.js" type="text/javascript"></script>
	<!-- <script src="/Public/stylesheets/SpryAssets/SpryMenuBar.js" type="text/javascript"></script> -->	
</head>
<body>
	<div id="topContainer" style="top: 0px; left: 0px;">
		<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
		<?php // include_layout_template('navigation_header.php'); ?>		
		
		<!-- this is the header div -->
		<div id="navHeader"><!-- Logo div -->
			<a href="/Public/homePage.php"><img id="navLogo" src="/Public/images/utilityImages/WhoSabiWorkLogo4.svg" alt="WhoSabiWork Logo"  /></a>

			<nav id="navMenu1">
				<ul id="navUl" class="">
					<li><a href="/Public/livePhotosFeed.php"><i class="fas fa-users fa-1x"></i>Photo Feed</a></li>
					<li><a href="/Public/mobileMarketPage.php"><i class="fas fa-store fa-1x"></i>Mobile Market</a></li>
					<li><a href="/Public/artisanPage.php"><i class="fas fa-users-cog fa-1x"></i>Artisans</a></li>
					<li><a href="/Public/servicePage.php"><i class="fas fa-cogs fa-1x"></i>Mechanics</a></li>
					<li><a href="/Public/sparePartPage.php"><i class="fas fa-wrench fa-1x"></i>Spare Parts</a></li>
					<li id="contact"><a href="/Public/contactUs.php"><i class="fas fa-headset fa-1x"></i>Contact Us</a></li>

					<!-- Display the Sign In and Sign Out buttons if not logged in or the div for avatar, name, phone number, profile and logout buttons -->
					<?php echo displayGlobalNav(); ?>					
				</ul>
			</nav>

			<!-- Displays the logo icon preview when signed in for the web view -->
			<?php echo accountPreview(); ?>
			<!-- Displays the logo icon preview when signed in for the mobile responsive use -->
			<?php echo mobileAccountPreview(); ?>
			<?php echo profileInfo(); ?>
		</div>
	</div>
	<div id="container">
	</div>
</body>
<!-- <script type="text/javascript" src="/Public/javascripts/genericJSs.js"></script> -->
<script type="text/javascript">

	/* // Defining event listener function
	function displayWindowSize(){
    // Get width and height of the window excluding scrollbars
    var screenWidth = document.documentElement.clientWidth;
    var h = document.documentElement.clientHeight;
    
    // Display result inside a div element
    // document.getElementById("result").innerHTML = "Width: " + w + ", " + "Height: " + h;
    console.log("Width: " + screenWidth + ", " + "Height: " + h);

    // check if logged in 
		$.ajax({
			url: "./PHP-JSON/globalNav_JSON.php",
			dataType: 'json',
			type: 'post',
			data: {action : "checkLogin"},

			success: function(response) {
				// var screenWidth = $(window).width();
				if (response.success === true && response.result === 'loggedInOn') {
					// now check for width
					if (screenWidth < 1140) {
						$('#menuBtn').css('display', 'none');
						$('#accountInfo').css('display', 'none');

						$('#cancel').click(function(event){
							$('#navUl').slideUp(2000);
						  $('#mobileAccountPreview').fadeIn();	  
						  $('#cancel').fadeOut();
						});
					} else {
						// Hide buttons that don't need to be displayed
						$('#cancel').css('display', 'none');
					}

					
				} else {
					// Conditions for logged out session
					if (screenWidth < 1140) {
						$('#menuBtn').css('display', 'block');
						$('#accountInfo').css('display', 'none');
						$('#mobileAccountPreview').css('display', 'none');

						$('#menuBtn').click(function(event){
						  $('#navUl').slideDown(2000);
						  $('#menuBtn').fadeOut();
						  $('#cancel').fadeIn();
						});

						$('#cancel').click(function(event){
							$('#navUl').slideUp(2000);
						  $('#menuBtn').fadeIn();	  
						  $('#cancel').fadeOut();
						});
					} else {
						$('#menuBtn').css('display', 'none');
						$('#accountPreview').css('display', 'block');
						$('#cancel').fadeOut();
					}
				}
			}
		});
	}
	    
	// Attaching the event listener function to window's resize event
	window.addEventListener("resize", displayWindowSize);

	// Calling the function for the first time
	// displayWindowSize();
	*/

	/*// This is for when no user is logged in
	$('#menuBtn').click(function(event){
	  $('#navUl').slideDown(2000);
	  $('#menuBtn').fadeOut();
	  $('#cancel').fadeIn();
	});

	// This is for when no user is logged in
	$('#cancel').click(function(event){
		$('#navUl').slideUp(2000);
	  $('#menuBtn').fadeIn();	  
	  $('#cancel').fadeOut();
	});

	// This is for when a user is logged in
	$('#mobileAccountPreview').click(function(event){
	  $('#navUl').slideDown(2000);
	  $('#mobileAccountPreview').fadeOut();
	  $('#cancel2').fadeIn();
	});

	// This is for when a user is logged in
	$('#cancel2').click(function(event){
		$('#navUl').slideUp(2000);
	  $('#mobileAccountPreview').fadeIn();	  
	  $('#cancel2').fadeOut();
	});

	// Open the account preview div with a hover
	$('#accountPreview').mouseover(function(event){
		$('#accountInfo').css('display', 'block');
	});

	// Controls the closing of the account preview div when opened
	// Get id of the parent div containing the account preview btn
	var accountInfoDiv = document.getElementById('accountInfo');
	// Get the preview image id
	var previewImgId = document.getElementById('avatarPreview2');
	// Get the fullname paragraph id
	var accountFullnameId = document.getElementById('accountFullname');
	// Get the account number paragraph id
	var accountNumberId = document.getElementById('accountNumber');
	// Get the profile button id
	var profileBtnId = document.getElementById('profileBtn');
	// Get the logout button id
	var logoutBtnId = document.getElementById('logoutBtn');
	// Check for a clicked event on the document out side of the account preview div
	document.onclick = function(div){
		// Check that the allowed ids clicked are the account preview div container and its children divs.
		// Also check that the allowed ids clicked are the image, paragraphs and buttons within the account preview div
		if ((div.target !== accountInfoDiv) && (event.target.parentNode != accountInfoDiv) && (div.target !== previewImgId)  && (div.target !== accountFullnameId)  && (div.target !== accountNumberId)  && (div.target !== profileBtnId)  && (div.target !== logoutBtnId)) {
			accountInfoDiv.style.display = 'none';
			// $('#accountInfo').css('display', 'none');
		}
	};

	$(document).ready(function(event){
		$('#profileBtn').click(function(event){
			event.preventDefault();

			$.ajax({
				url: "./PHP-JSON/globalNav_JSON.php",
				dataType: 'json',
				type: 'post',
				data: {action : "profileBtn"},

				success: function(response) {
					if (response.success === true) {
						window.location.href = response.redirectPath;
					}
				}
			});
		});

		$('#logoutBtn').click(function(event){
			event.preventDefault();

			$.ajax({
				url: "./PHP-JSON/globalNav_JSON.php",
				dataType: 'json',
				type: 'post',
				data: {action : "logoutBtn"},

				success: function(response) {
					if (response.success === true) {
						window.location.href = response.redirectPath;
					}
				}
			});
		});	

		$('#mobilelogoutBtn').click(function(event){
			event.preventDefault();

			$.ajax({
				url: "./PHP-JSON/globalNav_JSON.php",
				dataType: 'json',
				type: 'post',
				data: {action : "logoutBtn"},

				success: function(response) {
					if (response.success === true) {
						window.location.href = response.redirectPath;
					}
				}
			});
		});
	});*/
</script>
<script src="/Public/javascripts/genericJSs.js" type="text/javascript"></script>
</html>