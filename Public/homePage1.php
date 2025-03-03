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
	  <div id="picturePanel">
		<?php // include_layout_template('homePageSlideShow.php'); ?>
		<div class="slider">
		  <div class="slide_viewer">
			<div class="slide_group">
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage1.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage1.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage1.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
					</picture>	  
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage2.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage2.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage2.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
					</picture>
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage3.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage3.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage3.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
				</picture>
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage4.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage4.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage4.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
				</picture>
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage5.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage5.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage5.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
					</picture>
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage6.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage6.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage6.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
					</picture>
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage7.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage7.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage7.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			  	</picture>
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage8.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage8.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage8.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			  	</picture>
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage9.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage9.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage9.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			  	</picture>
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage10.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage10.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage10.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			  	</picture>
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage11.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage11.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage11.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			  	</picture>
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage12.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage12.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage12.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			  	</picture>
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage13.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage13.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage13.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			  	</picture>
			  </div>
			  <div class="slide">
			  	<picture>
			  		<source media="(min-width: 600px)" 
			  				srcset="../Public/images/homePageImages/jpg/slideShowImage14.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
			  		<source media="(max-width: 480px)" 
			  				srcset="../Public/images/mobileImages/jpg/slideImage14.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
						<img src="../Public/images/homePageImages/jpg/slideShowImage14.jpg" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
			  	</picture>
			  </div>
			</div>
		  </div>
		</div><!-- End // .slider -->

		<div class="slide_buttons">
		</div>

		<div class="directional_nav">
		  <div class="previous_btn" title="Previous">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="65px" height="65px" viewBox="-11 -11.5 65 66">
			  <g>
				<g>
				  <path fill="#474544" d="M-10.5,22.118C-10.5,4.132,4.133-10.5,22.118-10.5S54.736,4.132,54.736,22.118
					c0,17.985-14.633,32.618-32.618,32.618S-10.5,40.103-10.5,22.118z M-8.288,22.118c0,16.766,13.639,30.406,30.406,30.406 c16.765,0,30.405-13.641,30.405-30.406c0-16.766-13.641-30.406-30.405-30.406C5.35-8.288-8.288,5.352-8.288,22.118z"/>
				  <path fill="#474544" d="M25.43,33.243L14.628,22.429c-0.433-0.432-0.433-1.132,0-1.564L25.43,10.051c0.432-0.432,1.132-0.432,1.563,0	c0.431,0.431,0.431,1.132,0,1.564L16.972,21.647l10.021,10.035c0.432,0.433,0.432,1.134,0,1.564	c-0.215,0.218-0.498,0.323-0.78,0.323C25.929,33.569,25.646,33.464,25.43,33.243z"/>
				</g>
			  </g>
			</svg>
		  </div>
		  <div class="next_btn" title="Next">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="65px" height="65px" viewBox="-11 -11.5 65 66">
			  <g>
				<g>
				  <path fill="#474544" d="M22.118,54.736C4.132,54.736-10.5,40.103-10.5,22.118C-10.5,4.132,4.132-10.5,22.118-10.5	c17.985,0,32.618,14.632,32.618,32.618C54.736,40.103,40.103,54.736,22.118,54.736z M22.118-8.288	c-16.765,0-30.406,13.64-30.406,30.406c0,16.766,13.641,30.406,30.406,30.406c16.768,0,30.406-13.641,30.406-30.406 C52.524,5.352,38.885-8.288,22.118-8.288z"/>
				  <path fill="#474544" d="M18.022,33.569c 0.282,0-0.566-0.105-0.781-0.323c-0.432-0.431-0.432-1.132,0-1.564l10.022-10.035 			L17.241,11.615c 0.431-0.432-0.431-1.133,0-1.564c0.432-0.432,1.132-0.432,1.564,0l10.803,10.814c0.433,0.432,0.433,1.132,0,1.564 L18.805,33.243C18.59,33.464,18.306,33.569,18.022,33.569z"/>
				</g>
			  </g>
			</svg>
		  </div>
		</div>
		</div> <!-- End of advertising slide show -->

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
