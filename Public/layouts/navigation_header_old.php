<?php
// require("../includes/session.php");
// require("../includes/photograph.php");
// require("../includes/user_photograph.php");

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

function accountInfo() {
	global $session;
	$output = "";
	// Encrypt the ids of the user, customer or admin
	// $encryptionObj = new Encryption();
	if ($session->is_user_logged_in()) {
		// Display user accountInfo
		$user = User::find_by_id($session->user_id);
		$phoneNumber = $user->phone_number;
		// Encrypt user-id
		// $hashedUserId = $encryptionObj->encrypt($session->user_id);

		$output .= "<div class='accountInfoImgDiv'>";
		$output .= "<img id='avatarImage' src='".showUserAvatar()."' alt='profile image' />";
		$output .= "</div>";
		$output .= "<div class='accountInfoDiv'>";
		$output .= "<p class='accountInfoDetails'>Welcome</p>";
		$output .= "<p class='accountInfoDetails'>".ucwords($_SESSION['user_full_name'])." </p>";
		$output .= "<p class='accountInfoDetails'><i class='fas fa-phone'></i>".$phoneNumber."</p>";
		$output .= "</div>";
		$output .= "<div class='linkBtnDiv'> 
			<a class='linkStyleButton' href='/Public/user/userEditPage.php?id=".$session->user_id."' ><i class='fas fa-briefcase'></i>My Profile</a>";
		$output .= logoutForm();
		$output .= "</div>";
			// <a class='linkStyleButton' href='/Public/logout.php' ><i class='fas fa-sign-out-alt'></i>Logout</a>
	} elseif ($session->is_customer_logged_in()) {
		// Display customer accountInfo
		$customer = Customer::find_by_id($session->customer_id);
		$phoneNumber = $customer->phone_number;
		// Encrypt customer-id
		// $hashedCusId = $encryptionObj->encrypt($session->customer_id);

		$output .= "<div class='accountInfoImgDiv'>";
		$output .= "<img id='avatarImage' src='".showAvatar()."' alt='profile image' />";
		$output .= "</div>";
		$output .= "<div class='accountInfoDiv'>";
		$output .= "<p class='accountInfoDetails'>Welcome</p>";
		$output .= "<p class='accountInfoDetails'>".ucwords($_SESSION['customer_full_name'])."</p>";
		$output .= "<p class='accountInfoDetails'><i class='fas fa-phone'></i>".$phoneNumber."</p>";
		$output .= "</div>";
		$output .= "<div class='linkBtnDiv'> 
			<a class='linkStyleButton' href='/Public/customer/customerEditPage2.php?id=".$session->customer_id."' ><i class='fas fa-briefcase'></i>My Profile</a>".logoutForm()."			 
			</div>";
			// <a class='linkStyleButton' href='/Public/logout.php' ><i class='fas fa-sign-out-alt'></i>Logout</a>
	} elseif ($session->is_admin_logged_in()) {
		// Display admin accountInfo
		$admin = Admin::find_by_id($session->admin_id);
		$phoneNumber = $admin->phone_number;
		// Encrypt admin-id
		// $hashedAdminId = $encryptionObj->encrypt($session->admin_id);

		$output .= "<p>Welcome ".ucwords($_SESSION['admin_full_name'])."</p>";
		$output .= "<p><i class='fas fa-phone'></i>".$phoneNumber."</p>";
		$output .= "<div class='linkBtnDiv'> 
			<a class='linkStyleButton' href='/Public/admin/adminPage.php' ><i class='fas fa-briefcase'></i>Admin Page</a>".logoutForm()."			 
			</div>";
			// <a class='linkStyleButton' href='/Public/logout.php' ><i class='fas fa-sign-out-alt'></i>Logout</a>
	} else {
		$output .= "<div class='accountInfoButton'> 
			<a class='linkStyleButton' href='/Public/selectProfileType.php?page=signIn' ><i class='fas fa-sign-in-alt'></i>Sign In</a>
			</div>
			<div class='accountInfoButton'>  
			<a class='linkStyleButton' href='/Public/selectProfileType.php?page=signUp'><i class='fas fa-briefcase'></i>Sign Up</a>
			</div>";

		/*
		$output .= "<div class='accountInfoButton'> 
			<a class='linkStyleButton' href='/Public/loginPage.php?profile=user' ><i class='fas fa-sign-in-alt'></i>User Log In</a>
			</div>
			<div class='accountInfoButton'>
			<a class='linkStyleButton' href='/Public/loginPage.php?profile=customer' ><i class='fas fa-sign-in-alt'></i>Business Log In</a>
			</div>
			<div class='accountInfoButton'>  
			<a class='linkStyleButton' href='/Public/createUserAccount.php'><i class='fas fa-briefcase'></i>User Sign Up</a>
			</div>
			<div class='accountInfoButton'>  
			<a class='linkStyleButton' href='/Public/createBusinessAccount.php'><i class='fas fa-briefcase'></i>Business Sign Up</a> 
			</div>";
		*/
	}
	
	return $output;
}

function displayGlobalNav() {
	global $session;
	$output = "";
	if (!$session->is_user_logged_in() && !$session->is_customer_logged_in() && !$session->is_admin_logged_in()) {
		$output .= "<a href='/Public/selectProfileType.php?page=signIn'><i class='fas fa-sign-in-alt'></i>Sign In</a> | <a href='/Public/selectProfileType.php?page=signUp'><i class='fas fa-briefcase'></i>Sign Up</a>";
	}
	
	return $output;
}

/*
// Old global nav function
function displayGlobalNav() {
	global $session;
	$output = "";
	if (!$session->is_user_logged_in() && !$session->is_customer_logged_in() && !$session->is_admin_logged_in()) {
		$output .= "<a href='/Public/loginPage.php?profile=user'><i class='fas fa-sign-in-alt'></i>User Log In</a> | <a href='/Public/loginPage.php?profile=customer'><i class='fas fa-sign-in-alt'></i>Business Log In</a> | <a href='/Public/createUserAccount.php'><i class='fas fa-briefcase'></i>User Sign Up</a> | <a href='/Public/createBusinessAccount.php'><i class='fas fa-briefcase'></i>Business Sign Up</a>";
	}
	
	return $output;
}
*/

function logoutForm() {
	$output = "";
	$output .= '
		<form id="logoutForm" action="" method="post" enctype="application/x-www-form-urlencoded" name="logoutForm" >
			<button id="logoutBtn" name="logoutBtn" type="submit" class="linkStyleButton" ><i class="fas fa-sign-out-alt"></i>Logout</button> 			
		</form>';	
		// <input type="submit" name="logoutBtn" id="logoutBtn" class="linkStyleButton" value="Logout" />
	return $output;
}

function logoutSession() {
	global $session;
	$session->logout();
	$session->message("You logged out successfully. Have a nice day.");

	return "/Public/index.php";
}



?>
<!-- Begining of Header -->
<div id="header" > <!-- class="row" -->	
	<div id="logo" > <!-- WhoSabiWorkL1.jpg -->
		<a href="/Public/homePage.php"><img id="logoImg" src="/Public/images/utilityImages/WhoSabiWorkTBG2.svg" alt="WhoSabiWork Logo"  /></a> <!-- AyuanoramaLogo3.gif WhoSabiWorkL1.jpg -->
	</div>
	<div id="GlobalNav" style="color:grey">
		<?php echo displayGlobalNav(); ?>
	</div>
	
	<!-- <div id="accountInfoMenu"><i class="fas fa-ellipsis-v fa-li fa-2x"></i></div> -->
	<div id="accountInfoMenu"><i class="fa fa-bars fa-li fa-2x"></i></div>
	<!-- <div class="arrow-up" ></div> -->
	<div class="accountInfo" >
		<div class="accountInfoContainer" >
			<?php echo accountInfo(); ?>
		</div>
	</div>

	<!-- Begining of Navigation -->
	<div id="navigation">
		<button class="btnStyle1 menuNavBtn">Menu
			<!-- <i class="fa fa-bars"></i> -->
		</button>

		<ul id="MenuBar1" class="MenuBarHorizontal fa-ul">
			<li><a href="/Public/livePhotosFeed.php"><i class="fas fa-users" style='font-size: 20px'></i></a></li>
			<!-- <li><a href="/Public/about.php">ABOUT</a></li> -->
			<li><a href="/Public/mobileMarketPage.php">Mobile Market</a></li>
			<li><a href="/Public/artisanPage.php">Artisans</a></li>
			<li><a href="/Public/servicePage.php">Mechanics</a></li>
			<li><a href="/Public/sparePartPage.php">Spare Parts</a></li>
			<li><a href="/Public/contactUs.php">Contact Us</a></li>
			<!-- <li style="float:right;"><span class="fa-li fa-lg" ><i class="fas fa-bars"></i></span><a style="display:none" href="#">Menu</a></li> -->
		</ul>		
	</div>
	<!-- End of Navigation  -->

</div> <!-- End of Header -->
	  
