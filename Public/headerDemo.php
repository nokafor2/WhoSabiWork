<?php
require_once("../includes/initialize.php");


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
	<link href="/Public/stylesheets/homePageStyles.css" rel="stylesheet" type="text/css" />
	<link href="/Public/stylesheets/SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />

	<!-- Load Javascript files -->
	<script src="/Public/javascripts/jquery.js" type="text/javascript"></script>
	<script src="/Public/stylesheets/SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
</head>
<body>
	<div id="topContainer" style="top: 0px; left: 0px;">
		<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
		<?php // include_layout_template('navigation_header.php'); ?>
		
		<div><!-- this is the header div -->
			<div style="width: 100%; height: 30px; padding: 5px; display: inline-block;"><!-- Logo div -->
				<a href="/Public/homePage.php"><img style="height: 100%; padding-left:20px; padding-right: 20px;" src="/Public/images/utilityImages/WhoSabiWorkLogo4.svg" alt="WhoSabiWork Logo"  /></a>

				<div style="display: inline-block;">
					<ul id="MenuBar1" class="MenuBarHorizontal ">
						<li><a href="/Public/livePhotosFeed.php"><i class="fas fa-users fa-1x"></i>Photo Feed</a></li>
						<li><a href="/Public/mobileMarketPage.php"><i class="fas fa-store fa-1x"></i>Mobile Market</a></li>
						<li><a href="/Public/artisanPage.php">Artisans</a></li>
						<li><a href="/Public/servicePage.php"><i class="fas fa-cogs fa-1x"></i>Mechanics</a></li>
						<li><a href="/Public/sparePartPage.php"><i class="fas fa-wrench fa-1x"></i>Spare Parts</a></li>
						<li id="contact"><a href="/Public/contactUs.php"><i class="fas fa-phone fa-1x"></i>Contact Us</a></li>					
					</ul>
				</div>

				<div style="display: inline-block; float: right;">
					<ul id="MenuBar2" class="MenuBarHorizontal fa-ul">
						<li><a href='/Public/selectProfileType.php?page=signIn'><i class='fas fa-sign-in-alt'></i>Sign In</a></li>
						<li><a href='/Public/selectProfileType.php?page=signUp'><i class='fas fa-briefcase'></i>Sign Up</a></li>
					</ul>		
				</div>
			</div>
		</div>
	</div>
	<div id="container">
	</div>
</body>
<script type="text/javascript">
	var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"/Public/stylesheets/SpryAssets/SpryMenuBarDownHover.gif", imgRight:"/Public/stylesheets/SpryAssets/SpryMenuBarRightHover.gif"});
	var MenuBar2 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"/Public/stylesheets/SpryAssets/SpryMenuBarDownHover.gif", imgRight:"/Public/stylesheets/SpryAssets/SpryMenuBarRightHover.gif"});
</script>
<script type="text/javascript" src="/Public/javascripts/genericJSs.js"></script>
</html>