<?php
	require_once("../includes/initialize.php");

	function outputAdImages() {
		$photograph = new Photograph();
		$desktopPhotos = $photograph->getDesktopAdImages();
		$mobilePhotos = $photograph->getMobileAdImages();

		$output = "";
		foreach ($desktopPhotos as $key => $imageName) {
			$count = $key + 1;
			// Use this to get the loop of maximum of 4 for the adImageIndent class name
			$result = $count % 4; // Modulo computation for the remainder
			if ($result == 0) {
				$result = 4;
			}
			$output .= '
				<!-- Ad image '.$count.' -->
				<picture>
		  		<source media="(min-width: 600px)" 
		  				srcset="../Public/images/homePageImages/jpg/'.$desktopPhotos[$key].'" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
		  		<source media="(max-width: 480px)" 
		  				srcset="../Public/images/mobileImages/jpg/'.$mobilePhotos[$key].'" class="slideShowImg" alt="WhoSabiWork Slide Show Image">
					<img src="../Public/images/homePageImages/jpg/'.$desktopPhotos[$key].'" class="slideShowImg" alt="WhoSabiWork Slide Show Image"  />
				</picture>
				<div class="adImageIndent'.$result.'">
					<button class="btnStyle4">Create a User Profile</button>
					<button class="btnStyle5">Create a Business Profile</button>
				</div>
			';
		}

		return $output;
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
<div id="container" > <!--  class="row" -->
  <?php 
  	if (isset($sessionMessage)) {
  		echo output_message($sessionMessage);
  	}
    unset($sessionMessage, $_SESSION["message"]);
  ?>
  
  <!-- Begining of advertising slide show -->
  <div id="picturePanel" style="height: auto;">
  	<?php echo outputAdImages(); ?>
  	
  </div>
  <!-- End of advertising slide show -->

  <!-- Display the footer section -->
  <?php include_layout_template('navigation_footer.php'); ?>
  
</div>

<?php
	// Initialize the footer of the webpage
	echo getUniquePageFooter($scriptName);
?>
