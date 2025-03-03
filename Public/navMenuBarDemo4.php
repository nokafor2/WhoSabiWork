<?php
require_once("../includes/initialize.php");


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Nav Menu Bar Demo 4</title>
	<link rel="shortcut icon" type="image/png" href="/Public/images/utilityImages/WhoSabiWorkLogo.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Load CSS files -->
	<link href="/Public/stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />
	<link href="/Public/stylesheets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css" />
	<link href="/Public/stylesheets/navMenuBar4.css" rel="stylesheet" type="text/css" />

	<!-- Load Javascript files -->
	<script src="/Public/javascripts/jquery.js" type="text/javascript"></script>
</head>
<body>
	<header>
		<!-- This will work in place of the image -->
		<!-- <h1 class="logo">Logo</h1> -->
		<a href="/Public/homePage.php"><img style="width: 100px;" src="/Public/images/utilityImages/WhoSabiWorkLogo4.svg" alt="WhoSabiWork Logo"  /></a>
		<!-- This toggle is done before the navigation -->
		<input type="checkbox" id="nav-toggle" name="nav-toggle" class="nav-toggle">
		<nav>
			<ul class="links ">
				<li><a href="/Public/livePhotosFeed.php"><i class="fas fa-users fa-1x"></i>Photo Feed</a></li>
				<li><a href="/Public/mobileMarketPage.php"><i class="fas fa-store fa-1x"></i>Mobile Market</a></li>
				<li><a href="/Public/artisanPage.php">Artisans</a></li>
				<li><a href="/Public/servicePage.php"><i class="fas fa-cogs fa-1x"></i>Mechanics</a></li>
				<li><a href="/Public/sparePartPage.php"><i class="fas fa-wrench fa-1x"></i>Spare Parts</a></li>
				<li><a href="/Public/contactUs.php"><i class="fas fa-phone fa-1x"></i>Contact Us</a></li>
			</ul>
		</nav>
		<!-- This will transfer the event of the input checkbox to this label element -->
		<label for="nav-toggle" class="nav-toggle-label">
			<span><!--<i class="fas fa-bars" id="btn"></i>--></span>
		</label>
	</header>
	
	<div class="content">
		<h2>Your content would go here</h2>
	</div>
</body>
</html>