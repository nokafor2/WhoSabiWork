hh<?php 
require_once("../includes/initialize.php");


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
<title>About WhoSabiWork</title>
<link rel="shortcut icon" type="image/png" href="/WhoSabiWork/Images/WhoSabiWorkLogo.png" />
<style type="text/css">
</style>
<link href="./stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />
<link href="../HomePageStyles.css" rel="stylesheet" type="text/css" />
<link href="./stylesheets/aboutPageStyle.css" rel="stylesheet" type="text/css" />

<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>

<script src="./javascripts/jquery.js" type="text/javascript"></script>
</head>

<body>
<div id="topContainer">
	<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
	<?php include_layout_template('public_header.php'); ?>
</div>
<div id="container">
  <!-- Begining of Main Section  -->
  <div id="mainAbout" >
    <h1 style="text-align:center;">Welcome to Ayuanorama</h1>
    <img src="" alt="advertising Image" name="AdImage" width="300" height="300" id="aboutAdImage" style="margin:5px; border: thin solid #333;" />
    <p>This website helps individuals find reliable automobile technicians within your location quickly and easily.
      It provides the  name of the technician, his business name, the business address, the technician phone numbers, and ratings provided by other customers.</p>
	<p>Registered users can have the privilege to rate customers, leave a comment about the service qualities of a technician and schedule an appointment with a technician who has provided his availability. After an appointment is made, a notification will be sent to both the user and technician of the appointment schedule.  After an appointment is made with a technician, he has the option to accept or decline the appointment. If the appointment is accepted or declined, the individual will be notified.</p>
	<p>This website aims to make the search for a reputable mechanic easy. It also helps to save valuable time by finding out before hand if a mechanic will be available to assist you.</P>
	<p>If you have any suggestion on how this website can be improved upon, please don't hesitate to notify us on out contact page. Thanks for your patronage.</p>
  </div> <!-- Begining of Main Section  -->
  
  <!-- Display the footer section -->
  <?php include_layout_template('public_footer.php'); ?>
  
</div>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
</script>
<script type="text/javascript" src="./javascripts/aboutPageJSs.js"></script>
</body>
</html>
