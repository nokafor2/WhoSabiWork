<?php
	require_once("../includes/initialize.php");


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WhoSabiWork</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" type="image/png" href="/WhoSabiWork/Images/WhoSabiWorkLogo.png" />
<style type="text/css">
</style>

<!-- Latest compiled and minified CSS -->
<!-- <link href="./Bootstrap/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> -->
		
<!-- Optional theme -->

<!-- <link href="./Bootstrap/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" /> -->
<script src="./javascripts/Respond/respond.min.js"></script>


<!-- Bootstrap css file links -->
<!-- 
<link href="./Bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="./Bootstrap/css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
<link href="./Bootstrap/css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
-->

<link href="stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />
<!-- <link href="../myScripts/SlideshowCSSCodeAuto.css" rel="stylesheet" type="text/css" /> -->
<link href="../HomePageStyles.css" rel="stylesheet" type="text/css" />
<link href="./stylesheets/imageSlideShowCSS.css" rel="stylesheet" type="text/css" />

<!-- <link href="../myScripts/styleDemoAd.css" rel="stylesheet" type="text/css" /> -->

<link href="../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<!-- <script src="./javascripts/jRating/jquery/jquery.js" type="text/javascript"></script> -->
<!-- JavaScript files should be linked at the bottom of the page -->
<script src="./javascripts/jquery.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>

</head>

<body > <!-- style="overflow-y: hidden;" -->
	<div id="topContainer">
		<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
		<?php include_layout_template('public_header.php'); ?>
	</div>
	<div id="container" > <!--  class="row" -->
	  <?php 
	  	if (isset($sessionMessage)) {
	  		echo output_message($sessionMessage);
	  	}
	    unset($sessionMessage, $_SESSION["message"]);
	  ?>
	  
	  <!-- Begining of advertising slide show -->
	  <div id="picturePanel" style="height: auto;">
	  	<!-- Ad image 1 -->
			<picture>
	  		<source media="(min-width: 600px)" 
	  				srcset="../Public/images/homePageImages/jpg/slideShowImage1.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
	  		<source media="(max-width: 480px)" 
	  				srcset="../Public/images/mobileImages/jpg/slideImage1.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
				<img src="../Public/images/homePageImages/jpg/slideShowImage1.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			</picture>
			<div class="adImageIndent1"></div>
			<!-- Ad image 2 -->
			<picture>
	  		<source media="(min-width: 600px)" 
	  				srcset="../Public/images/homePageImages/jpg/slideShowImage2.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
	  		<source media="(max-width: 480px)" 
	  				srcset="../Public/images/mobileImages/jpg/slideImage2.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
				<img src="../Public/images/homePageImages/jpg/slideShowImage2.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			</picture>
			<div class="adImageIndent2"></div>
			<!-- Ad image 3 -->
			<picture>
	  		<source media="(min-width: 600px)" 
	  				srcset="../Public/images/homePageImages/jpg/slideShowImage3.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
	  		<source media="(max-width: 480px)" 
	  				srcset="../Public/images/mobileImages/jpg/slideImage3.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
				<img src="../Public/images/homePageImages/jpg/slideShowImage3.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			</picture>
			<div class="adImageIndent3"></div>
			<!-- Ad image 4 -->
			<picture>
	  		<source media="(min-width: 600px)" 
	  				srcset="../Public/images/homePageImages/jpg/slideShowImage4.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
	  		<source media="(max-width: 480px)" 
	  				srcset="../Public/images/mobileImages/jpg/slideImage4.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
				<img src="../Public/images/homePageImages/jpg/slideShowImage4.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			</picture>
			<div class="adImageIndent4"></div>
			<!-- Ad image 5 -->
			<picture>
	  		<source media="(min-width: 600px)" 
	  				srcset="../Public/images/homePageImages/jpg/slideShowImage5.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
	  		<source media="(max-width: 480px)" 
	  				srcset="../Public/images/mobileImages/jpg/slideImage5.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
				<img src="../Public/images/homePageImages/jpg/slideShowImage5.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			</picture>
			<div class="adImageIndent1"></div>
			<!-- Ad image 6 -->
			<picture>
	  		<source media="(min-width: 600px)" 
	  				srcset="../Public/images/homePageImages/jpg/slideShowImage6.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
	  		<source media="(max-width: 480px)" 
	  				srcset="../Public/images/mobileImages/jpg/slideImage6.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
				<img src="../Public/images/homePageImages/jpg/slideShowImage6.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			</picture>
			<div class="adImageIndent2"></div>
			<!-- Ad image 7 -->
			<picture>
	  		<source media="(min-width: 600px)" 
	  				srcset="../Public/images/homePageImages/jpg/slideShowImage7.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
	  		<source media="(max-width: 480px)" 
	  				srcset="../Public/images/mobileImages/jpg/slideImage7.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
				<img src="../Public/images/homePageImages/jpg/slideShowImage7.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
	  	</picture>
	  	<div class="adImageIndent3"></div>
	  	<!-- Ad image 8 -->
	  	<picture>
	  		<source media="(min-width: 600px)" 
	  				srcset="../Public/images/homePageImages/jpg/slideShowImage8.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
	  		<source media="(max-width: 480px)" 
	  				srcset="../Public/images/mobileImages/jpg/slideImage8.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
				<img src="../Public/images/homePageImages/jpg/slideShowImage8.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
	  	</picture>
	  	<div class="adImageIndent4"></div>
	  	<!-- Ad image 9 -->
	  	<picture>
	  		<source media="(min-width: 600px)" 
	  				srcset="../Public/images/homePageImages/jpg/slideShowImage9.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
	  		<source media="(max-width: 480px)" 
	  				srcset="../Public/images/mobileImages/jpg/slideImage9.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
				<img src="../Public/images/homePageImages/jpg/slideShowImage9.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
	  	</picture>
		  <div class="adImageIndent1"></div>
		  <!-- Ad image 10 -->
		  <picture>
		  		<source media="(min-width: 600px)" 
		  				srcset="../Public/images/homePageImages/jpg/slideShowImage10.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
		  		<source media="(max-width: 480px)" 
		  				srcset="../Public/images/mobileImages/jpg/slideImage10.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
					<img src="../Public/images/homePageImages/jpg/slideShowImage10.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
		  </picture>
		  <div class="adImageIndent2"></div>
		  <!-- Ad image 11 -->
		  <picture>
		  		<source media="(min-width: 600px)" 
		  				srcset="../Public/images/homePageImages/jpg/slideShowImage11.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
		  		<source media="(max-width: 480px)" 
		  				srcset="../Public/images/mobileImages/jpg/slideImage11.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
					<img src="../Public/images/homePageImages/jpg/slideShowImage11.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
		  </picture>
	  	<div class="adImageIndent3"></div>
	  	<!-- Ad image 12 -->
	  	<picture>
	  		<source media="(min-width: 600px)" 
	  				srcset="../Public/images/homePageImages/jpg/slideShowImage12.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
	  		<source media="(max-width: 480px)" 
	  				srcset="../Public/images/mobileImages/jpg/slideImage12.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
				<img src="../Public/images/homePageImages/jpg/slideShowImage12.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
	  	</picture>
	  	<div class="adImageIndent4"></div>
	  	<!-- Ad image 13 -->
	  	<picture>
	  		<source media="(min-width: 600px)" 
	  				srcset="../Public/images/homePageImages/jpg/slideShowImage13.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
	  		<source media="(max-width: 480px)" 
	  				srcset="../Public/images/mobileImages/jpg/slideImage13.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
				<img src="../Public/images/homePageImages/jpg/slideShowImage13.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
	  	</picture>
	  	<div class="adImageIndent1"></div>
	  	<!-- Ad image 14 -->
	  	<picture>
	  		<source media="(min-width: 600px)" 
	  				srcset="../Public/images/homePageImages/jpg/slideShowImage14.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
	  		<source media="(max-width: 480px)" 
	  				srcset="../Public/images/mobileImages/jpg/slideImage14.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
				<img src="../Public/images/homePageImages/jpg/slideShowImage14.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
	  	</picture>
	  	<div class="adImageIndent2"></div>
	  </div>
	  <!-- End of advertising slide show -->

	  <!-- Display the footer section -->
	  <?php include_layout_template('public_footer.php'); ?>
	  
	</div>
	
	<script type="text/javascript">
	var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
	</script>
	
	<!-- <script type="text/javascript" src="../myScripts/demoAd.js"></script> -->
	<script type="text/javascript" src="./javascripts/genericJSs.js"></script>
	<script type="text/javascript" src="./javascripts/homePageJSs.js"></script>
	
	<!-- <script src="./bootstrap/js/bootstrap.min.js" type="text/javascript" /></script> -->
	
	<!-- Latest complied and minified JavaScript -->
	<!-- <script src="./Bootstrap/bootstrap-3.3.7-dist/js/bootstrap.min.js" type="text/javascript"></script> -->
	<script src="javascripts/imageSlideShowJQuery.js" type="text/javascript"></script>
</body>
</html>
