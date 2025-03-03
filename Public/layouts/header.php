<?php
// This function will return the consistent header of all pages
function genericHeader1() {
	
	$output = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-171769876-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag("js", new Date());

		  gtag("config", "UA-171769876-1");
		</script>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	';

	return $output;
}

function getWebPageTitle($scriptName) {
	global $cus_full_name;

	$output = '';

	if ($scriptName === 'homePage.php') {
		$output .= '<title>WhoSabiWork</title>';
	} elseif ($scriptName === 'selectProfileType.php') {
		$output .= '<title>Select Profile Type</title>';
	} elseif ($scriptName === 'loginPage.php') {
		$output .= '<title>Sign In</title>';
	} elseif ($scriptName === 'createUserAccount.php') {
		$output .= '<title>Create a User Account</title>';
	} elseif ($scriptName === 'createBusinessAccount.php') {
		$output .= '<title>Create a Business Account</title>';
	} elseif ($scriptName === 'livePhotosFeed.php') {
		$output .= '<title>WhoSabiWork</title>';
	} elseif ($scriptName === 'mobileMarketPage.php') {
		$output .= '<title>Seller Services</title>';
	} elseif ($scriptName === 'artisanPage.php') {
		$output .= '<title>Artisan Services</title>';
	} elseif ($scriptName === 'servicePage.php') {
		$output .= '<title>Vehicle Services</title>';
	} elseif ($scriptName === 'sparePartPage.php') {
		$output .= '<title>Vehicle Spare Parts</title>';
	} elseif ($scriptName === 'contactUs.php') {
		$output .= '<title>Contact WhoSabiWork</title>';
	} elseif ($scriptName === 'privacyPolicy.php') {
		$output .= '<title>Privacy Policy WhoSabiWork</title>';
	} elseif ($scriptName === 'termsOfUse.php') {
		$output .= '<title>Terms of Use WhoSabiWork</title>';
	} elseif ($scriptName === 'forgotPassword.php') {
		$output .= '<title>Forgot Password</title>';
	} elseif ($scriptName === 'resetPassword.php') {
		$output .= '<title>Reset Password</title>';
	} elseif ($scriptName === 'userEditPage.php') {		
		$output .= '<title>'.$_SESSION['user_full_name'].' Profile</title>';
	} elseif ($scriptName === 'customerEditPage2.php') {
		$output .= '<title>'.$_SESSION['customer_full_name'].' Profile</title>';
	} elseif ($scriptName === 'customerHomePage.php') {
		$output .= '<title>'.$cus_full_name.' Home Page</title>';
	} elseif ($scriptName === 'loginAdmin.php') {
		$output .= '<title>Admin Login for WhoSabiWork</title>';
	} elseif ($scriptName === 'adminPage.php') {
		$output .= '<title>Admin: '.$_SESSION['admin_full_name'].'</title>';
	} elseif ($scriptName === 'siteLogFile.php') {
		$output .= '<title>Log File</title>';
	}

	return $output;
}

// This function will return the continutation of the consistent header of all pages
function genericHeader2($scriptName) {

	if ($scriptName === 'livePhotosFeed.php') {
		$output = '<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">';
	} else {
		$output = '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
	}

	if ($scriptName === 'homePage.php') {
		$output .= '<meta name="keywords" content="work, artisan, seller, small scale business, entrepreneur, hand work, technician, mechanic, spare part seller, mobile market, who sabi work, nigeria businesses, nigeria entrepreneurs"/>';
	}

	$output .= '<link rel="shortcut icon" type="image/png" href="/Public/images/utilityImages/WhoSabiWorkLogo.png" />';

	return $output;
}

// This function will return the generic CSS header
function genericCSSHeader() {
	$output = '
		<link href="/Public/stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />
		<link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet" type="text/css" />
		<link href="/Public/stylesheets/homePageStyles.css" rel="stylesheet" type="text/css" />
		<link href="/Public/stylesheets/navMenuBar2.css" rel="stylesheet" type="text/css" />
		<link href="/Public/stylesheets/SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
	';

	return $output;
}

// Funtion to get the unique CSS of a webpage
function getUniqueCSSHeader($scriptName) {
	$output = '';

	if ($scriptName === 'homePage.php') {
		$output .= '';
	} elseif ($scriptName === 'selectProfileType.php') {
		$output .= '<link href="/Public/stylesheets/loginPageStyle.css" rel="stylesheet" type="text/css" />';

		if ($_SERVER["SERVER_NAME"] === "localhost") {
			// $output .= '<link href="/Public/stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />';
			$output .= '<link href="/Public/stylesheets/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css" />';
		} else {
			$output .= '<link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet" type="text/css" />';
		}
	} elseif ($scriptName === 'loginPage.php') {
		$output .= '
			<link href="/Public/stylesheets/loginPageStyle.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />';
	} elseif ($scriptName === 'createUserAccount.php') {
		$output .= '
			<link href="/Public/stylesheets/loginPageStyle.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />';
	} elseif ($scriptName === 'createBusinessAccount.php') {
		$output .= '<link href="/Public/stylesheets/createBusinessAccountStyle.css" rel="stylesheet" type="text/css" />';
	} elseif ($scriptName === 'livePhotosFeed.php') {
		$output .= '
			<link href="/Public/stylesheets/livePhotosFeedStyle.css" rel="stylesheet" type="text/css" />
			<link href="/Public/javascripts/jRating/jquery/jRating.jquery.css" rel="stylesheet" type="text/css" />';
	} elseif ($scriptName === 'mobileMarketPage.php') {
		$output .= '
			<link href="/Public/stylesheets/artisansPageStyle.css" rel="stylesheet" type="text/css" />
			<link href="/Public/javascripts/jRating/jquery/jRating.jquery.css" rel="stylesheet" type="text/css" />';
	} elseif ($scriptName === 'artisanPage.php') {
		$output .= '
			<link href="/Public/stylesheets/artisansPageStyle.css" rel="stylesheet" type="text/css" />
			<link href="/Public/javascripts/jRating/jquery/jRating.jquery.css" rel="stylesheet" type="text/css" />';
	} elseif ($scriptName === 'servicePage.php') {
		$output .= '
			<link href="/Public/stylesheets/servicePageStyle.css" rel="stylesheet" type="text/css" />
			<link href="/Public/javascripts/jRating/jquery/jRating.jquery.css" rel="stylesheet" type="text/css" />
		';
	} elseif ($scriptName === 'sparePartPage.php') {
		$output .= '
			<link href="/Public/stylesheets/sparePartPageStyle.css" rel="stylesheet" type="text/css" />

			<!-- Javascripts and CSS links for the jRating -->			
			<link href="/Public/javascripts/jRating/jquery/jRating.jquery.css" rel="stylesheet" type="text/css" />
		';
	} elseif ($scriptName === 'contactUs.php') {
		$output .= '
			<link href="/Public/stylesheets/contactUsStyle.css" rel="stylesheet" type="text/css" />
			';
	} elseif ($scriptName === 'privacyPolicy.php') {
		$output .= '<link href="/Public/stylesheets/privacyPolicyStyle.css" rel="stylesheet" type="text/css" />';
	} elseif ($scriptName === 'termsOfUse.php') {
		$output .= '<link href="/Public/stylesheets/termsOfUseStyle.css" rel="stylesheet" type="text/css" />';
	} elseif ($scriptName === 'forgotPassword.php') {
		$output .= '
		<link href="./stylesheets/loginPageStyle.css" rel="stylesheet" type="text/css" />
		<link href="/Public/stylesheets/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />';
	} elseif ($scriptName === 'resetPassword.php') {
		$output .= '<link href="/Public/stylesheets/loginPageStyle.css" rel="stylesheet" type="text/css" />';
	} elseif ($scriptName === 'userEditPage.php') {
		$output .= '
			<link href="/Public/stylesheets/userEditPageStyle.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/croppie.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/cropImage.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />';
	} elseif ($scriptName === 'customerEditPage2.php') {
		$output .= '
			<link href="/Public/stylesheets/customerEditPage2.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/jquery-ui.min.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/croppie.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/cropImage.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
		';
	} elseif ($scriptName === 'customerHomePage.php') {
		$output .= '
			<!-- <style type="text/css"> .message {color: gray;} </style> -->
			<link href="/Public/stylesheets/customerHomePageStyle.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
			
			<!-- CSS links for the jRating -->
			<link href="/Public/javascripts/jRating/jquery/jRating.jquery.css" rel="stylesheet" type="text/css" />
		';
	} elseif ($scriptName === 'loginAdmin.php') {
		$output .= '
			<link href="/Public/stylesheets/loginAdminStyles.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
		';
	} elseif ($scriptName === 'adminPage.php') {
		$output .= '
			<link href="/Public/stylesheets/adminPageStyle.css" rel="stylesheet" type="text/css" />
			<link href="/Public/stylesheets/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
		';
	} elseif ($scriptName === 'siteLogFile.php') {
		$output .= '<link href="/Public/stylesheets/SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />';
	}

	return $output;
}

// This function will return the generic Javascript header
function genericJavascriptHeader() {
	$output = '
		<script src="/Public/javascripts/jquery.js" type="text/javascript"></script>
		<script src="/Public/stylesheets/SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
	';

	return $output;
}

// Funtion to get the unique Javascript of a webpage
function getUniqueJavascriptHeader($scriptName) {
	$output = '';

	if ($scriptName === 'homePage.php') {
		$output .= '';
	} elseif ($scriptName === 'selectProfileType.php') {
		$output .= '';
	} elseif ($scriptName === 'loginPage.php') {
		$output .= '<script src="/Public/stylesheets/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>';
	} elseif ($scriptName === 'createUserAccount.php') {
		$output .= '<script src="/Public/stylesheets/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>';
	} elseif ($scriptName === 'createBusinessAccount.php') {
		$output .= '';
	} elseif ($scriptName === 'livePhotosFeed.php') {
		$output .= '<script src="/Public/javascripts/jRating/jquery/jRating.jquery.js" type="text/javascript"></script>';
	} elseif ($scriptName === 'mobileMarketPage.php') {
		$output .= '
		<script src="/Public/javascripts/jRating/jquery/jRating.jquery.js" type="text/javascript"></script>
		<script type="text/javascript" src="/Public/javascripts/multipleSelectMenu.js"></script>';
	} elseif ($scriptName === 'artisanPage.php') {
		$output .= '
		<script src="/Public/javascripts/jRating/jquery/jRating.jquery.js" type="text/javascript"></script>
		<script type="text/javascript" src="/Public/javascripts/multipleSelectMenu.js"></script>
		<script type="text/javascript" src="/Public/javascripts/typeahead.js"></script>';
	} elseif ($scriptName === 'servicePage.php') {
		$output .= '
			<script src="/Public/javascripts/jRating/jquery/jRating.jquery.js" type="text/javascript"></script>
			<script type="text/javascript" src="/Public/javascripts/multipleSelectMenu.js"></script>
		';
	} elseif ($scriptName === 'sparePartPage.php') {
		$output .= '
			<script src="/Public/javascripts/jRating/jquery/jRating.jquery.js" type="text/javascript"></script>
			<script type="text/javascript" src="/Public/javascripts/multipleSelectMenu.js"></script>
		';
	} elseif ($scriptName === 'contactUs.php') {
		$output .= '<script type="text/javascript" src="/Public/javascripts/contactUsJSs.js" defer></script>';
	} elseif ($scriptName === 'privacyPolicy.php') {
		$output .= '';
	} elseif ($scriptName === 'termsOfUse.php') {
		$output .= '';
	} elseif ($scriptName === 'forgotPassword.php') {
		$output .= '';
	} elseif ($scriptName === 'resetPassword.php') {
		$output .= '';
	} elseif ($scriptName === 'userEditPage.php') {
		$output .= '
			<script src="/Public/stylesheets/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
			<script src="/Public/stylesheets/SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
			<script type="text/javascript" src="/Public/javascripts/croppie.js"></script>
		';
	} elseif ($scriptName === 'customerEditPage2.php') {
		$output .= '
			<script src="/Public/stylesheets/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
			<script src="/Public/stylesheets/SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
			<script type="text/javascript" src="/Public/javascripts/jquery-ui.min.js"></script>
			<script type="text/javascript" src="/Public/javascripts/croppie.js"></script>
			<script type="text/javascript" src="/Public/javascripts/multipleImageUpload.js"></script>
		';
	} elseif ($scriptName === 'customerHomePage.php') {
		$output .= '
			<script src="/Public/stylesheets/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
			<script src="/Public/javascripts/jRating/jquery/jRating.jquery.js" type="text/javascript"></script>
			<script type="text/javascript" src="/Public/javascripts/customerHomePageJSScripts.js" defer></script>

			<script type="text/javascript">
				$(document).ready(function(){
					$(".rating").jRating({
						// decimalLength : 1, // number of decimal in the rate
						type : "big",
						rateMax : 5, // maximal rate - integer from 0 to 9999 (or more)
						// phpPath : "/Public/javascripts/jRating/libs/rating.php",
						phpPath : "../PHP-JSON/rating_JSON.php",
						bigStarsPath : "/Public/javascripts/jRating/jquery/icons/stars.png", // path of the icon stars.png
						smallStarsPath : "/Public/javascripts/jRating/jquery/icons/small.png" // path of the icon small.png
						// onSuccess : "The post sending was successfully.",
						// onError : "An error occured.",
						
						// canRateAgain : true,
						// nbRates : 3
					});
				});
			</script>
		';
	} elseif ($scriptName === 'loginAdmin.php') {
		$output .= '<script src="/Public/stylesheets/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>';
	} elseif ($scriptName === 'adminPage.php') {
		$output .= '
			<script src="/Public/stylesheets/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
			<script src="/Public/javascripts/adminPageJSs.js" type="text/javascript"></script>
		';
	} elseif ($scriptName === 'siteLogFile.php') {
		$output .= '<script src="/Public/stylesheets/SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>';
	}

	return $output;
}

// Get the compiled header for the given page
function getUniquePageHeader($scriptName) {
	$output = '';

	$output .= genericHeader1().getWebPageTitle($scriptName).genericHeader2($scriptName).genericCSSHeader().getUniqueCSSHeader($scriptName).genericJavascriptHeader().getUniqueJavascriptHeader($scriptName).'</head>
			<body>
	';

	return $output;
}

?>