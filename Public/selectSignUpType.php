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

		if (isset($get_params['page']) && $get_params['profile'] === 'signIn') {
			$sendTo = 'loginPage.php';
		}
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-171769876-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-171769876-1');
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Log In</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" type="image/png" href="/Public/images/WhoSabiWorkLogo.png" />
<style type="text/css">
</style>

<link href="../HomePageStyles.css" rel="stylesheet" type="text/css" />
<link href="./stylesheets/loginPageStyle.css" rel="stylesheet" type="text/css" />

<!-- Font Awesome Link -->
<link href="./stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />

<link href="../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>

<script src="./javascripts/jquery.js" type="text/javascript"></script>

</head>


<body>
	<div id="topContainer">
		<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
		<?php include_layout_template('public_header.php'); ?>
	</div>
	
	<!-- Begining of Container -->
	<div id="container">
	  <!-- Begining of Main Section -->
	  <div class="mainLogin">
	    <!-- Begining of Login Panel -->
			<div class="profilePanel">
				<p id="selectProfileTitle">Select your account type</p>
			  <a href="createUserAccount.php" class="adLink" >
			  	<div id="userProfile" class="profileType">
			  		<span class="centerItem" >
			  			<i class="fas fa-user fa-5x"></i>
			  		</span>
			  		<p class="centerItem">User Profile</p>
			  	</div>
			  </a>
			  <a href="createBusinessAccount.php" class="adLink">
			  	<div id="customerProfile" class="profileType">
				  	<span class="centerItem" >
				  		<i class="fas fa-building fa-5x"></i>
				  	</span>
				  	<p class="centerItem">Business Profile</p>
				  </div>
			  </a>
			</div> <!-- End of Login Panel -->
    </div> <!-- End of Main Section -->

	  <!-- Display the footer section -->
	  <?php include_layout_template('public_footer.php'); ?>
	</div> <!-- End of Container -->
	
	<script type="text/javascript" src="./javascripts/genericJSs.js"></script>
</body>
</html>

<?php if(isset($database)) { $database->close_connection(); } ?>