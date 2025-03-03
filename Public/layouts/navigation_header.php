<?php

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

		$output .= "<div id='accountPreview' onclick='accountPreview();'>";
		$output .= "<img id='avatarPreview' src='".showUserAvatar()."' alt='profile image' />";
		$output .= "<p id='namePreview'>".$firstName."</p>";
		$output .= "</div>";
	} elseif ($session->is_customer_logged_in()) {
		// Display customer accountInfo
		$customer = Customer::find_by_id($session->customer_id);
		$phoneNumber = $customer->phone_number;
		$firstName = $customer->first_name;

		$output .= "<div id='accountPreview' onmouseup='accountPreview();'>";
		$output .= "<img id='avatarPreview' src='".showAvatar()."' alt='profile image' />";
		$output .= "<p id='namePreview'>".$firstName."</p>";
		$output .= "</div>";
	} elseif ($session->is_admin_logged_in()) {
		// Display admin accountInfo
		$admin = Admin::find_by_id($session->admin_id);
		$phoneNumber = $admin->phone_number;
		$firstName = $admin->first_name;

		$output .= "<div id='accountPreview' onmouseup='accountPreview();'>";
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
			<button id='profileBtn' class='globalNavBtn' onclick='goToProfile(event);' ><i class='fas fa-briefcase'></i>My Profile</button>";
		$output .= '<button id="logoutBtn" class="globalNavBtn linkStyleButton" name="logoutBtn" onclick="logoutBtn(event);" ><i class="fas fa-sign-out-alt"></i>Logout</button>';
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
			<button id='profileBtn' class='globalNavBtn' onclick='goToProfile(event);' ><i class='fas fa-briefcase'></i>My Profile</button>";
		$output .= '<button id="logoutBtn" class="globalNavBtn linkStyleButton" name="logoutBtn" onclick="logoutBtn(event);" ><i class="fas fa-sign-out-alt"></i>Logout</button>';
		$output .= "</div>";
	} elseif ($session->is_admin_logged_in()) {
		// Display admin accountInfo
		$admin = Admin::find_by_id($session->admin_id);
		$phoneNumber = $admin->phone_number;
		$firstName = $admin->first_name;

		$output .= "<p id='accountFullname'>".ucwords($_SESSION['admin_full_name'])."</p>";
		$output .= "<p id='accountNumber'><i class='fas fa-phone'></i>".$phoneNumber."</p>";
		$output .= "<div class='linkBtnDiv'> 
			<button id='profileBtn' class='globalNavBtn' onclick='goToProfile(event);' ><i class='fas fa-briefcase'></i>Admin Page</button>";
		$output .= '<button id="logoutBtn" class="globalNavBtn linkStyleButton" name="logoutBtn" onclick="logoutBtn(event);" ><i class="fas fa-sign-out-alt"></i>Logout</button>';
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

		$output .= "<button id='profileBtn' class='mobileProfileDetails' onclick='goToProfile(event);' ><i class='fas fa-briefcase'></i>My Profile</button>";
		$output .= '<button id="mobilelogoutBtn" class="mobileProfileDetails" name="logoutBtn" onclick="logoutBtn(event);" ><i class="fas fa-sign-out-alt"></i>Logout</button>';
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

		$output .= "<button id='profileBtn' class='mobileProfileDetails' onclick='goToProfile(event);' ><i class='fas fa-briefcase'></i>My Profile</button>";
		$output .= '<button id="mobilelogoutBtn" class="mobileProfileDetails" name="logoutBtn" onclick="logoutBtn(event);" ><i class="fas fa-sign-out-alt"></i>Logout</button>';
	} elseif ($session->is_admin_logged_in()) {
		// Display admin accountInfo
		$admin = Admin::find_by_id($session->admin_id);
		$phoneNumber = $admin->phone_number;
		$firstName = $admin->first_name;

		$output .= "<div id='mobileNamePhoneDiv'>";
		$output .= "<p class='mobileProfileDetails' id='profileFullname'>Admin: ".ucwords($_SESSION['admin_full_name'])." </p>";
		$output .= "<p class='mobileProfileDetails' id='profileNumber'><i class='fas fa-phone'></i>".$phoneNumber."</p>";
		$output .= "</div>";

		$output .= "<button id='profileBtn' class='mobileProfileDetails' onclick='goToProfile(event);' ><i class='fas fa-briefcase'></i>My Profile</button>";
		$output .= '<button id="mobilelogoutBtn" class="mobileProfileDetails" name="logoutBtn" onclick="logoutBtn(event);" ><i class="fas fa-sign-out-alt"></i>Logout</button>';
	}

	return $output;
}

?>



<!-- Begining of Header -->
<!-- this is the header div -->
<div id="navHeader"><!-- Logo div -->
	<a href="/index.php"><img id="navLogo" src="/Public/images/utilityImages/WhoSabiWorkLogo4.svg" alt="WhoSabiWork Logo"  /></a>

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


	<!-- Displays the logo icon preview when signed in for the mobile responsive use -->
	<?php echo mobileAccountPreview(); ?>
	<?php echo profileInfo(); ?>
	<!-- Displays the logo icon preview when signed in for the web view -->
	<?php echo accountPreview(); ?>
</div>
<!-- Begining of Header -->