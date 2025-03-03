<?php
	require_once("../includes/initialize.php");

	if (request_is_get()) {
		// Check that only allowed parameters is passed into the form
		$get_params = allowed_get_params(['page']);

		// Eliminate HTML tags embedded in the form inputs
		foreach($get_params as $param) {
			// run htmlentities check on the parameters
			if(isset($get_params[$param])) {
				// run htmlentities check on the parameters
				$get_params[$param] = h2($param);
			} 
		}

		if (isset($get_params['page'])) {
			$page = $get_params['page'];
		}
	}

	function sendPageTo($profile, $page) {
		$output = "";

		if ($profile === 'user') {
			if ($page === 'signIn') {
				$output .= 'loginPage.php?profile=user';
			} elseif ($page === 'signUp') {
				$output .= 'createUserAccount.php';
			}
		} elseif ($profile === 'customer') {
			if ($page === 'signIn') {
				$output .= 'loginPage.php?profile=customer';
			} elseif ($page === 'signUp') {
				$output .= 'createBusinessAccount.php';
			}
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
	
	<!-- Begining of Container -->
	<div id="container">
	  <!-- Begining of Main Section -->
	  <div class="mainLogin">
	    <!-- Begining of Login Panel -->
			<div class="profilePanel">
				<p id="selectProfileTitle">Select your account type</p>
			  <a href="<?php echo sendPageTo('user', $page); ?>" class="adLink" >
			  	<div id="userProfile" class="profileType">
			  		<span class="centerItem" >
			  			<i class="fas fa-user fa-5x"></i>
			  		</span>
			  		<p class="centerItem">User Profile</p>
			  	</div>
			  </a>
			  <a href="<?php echo sendPageTo('customer', $page); ?>" class="adLink">
			  	<div id="customerProfile" class="profileType">
				  	<span class="centerItem" >
				  		<i class="fas fa-user-tie fa-5x"></i>
				  	</span>
				  	<p class="centerItem">Business Profile</p>
				  </div>
			  </a>
			</div> <!-- End of Login Panel -->
    </div> <!-- End of Main Section -->

	  <!-- Display the footer section -->
	  <?php include_layout_template('navigation_footer.php'); ?>
	</div> <!-- End of Container -->
	
<?php
	// Initialize the footer of the webpage
	echo getUniquePageFooter($scriptName);
?>

<?php if(isset($database)) { $database->close_connection(); } ?>