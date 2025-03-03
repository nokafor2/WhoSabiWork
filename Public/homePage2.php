<?php

?>

<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title> Artisansdiary.com </title>
		
		<!-- Latest compiled and minified CSS -->
		<link href="./Bootstrap/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		
		<!-- Optional theme -->
		<link href="./Bootstrap/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
		
		<!-- Fontawesome CSS style file -->
		<link href="./stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />
		
		<!-- This is my CSS file -->
		<link href="./stylesheets/homePageStyle.css" rel="stylesheet" type="text/css" />
		<script src="./javascripts/Respond/respond.min.js"></script>
	</head>
	<body>
		<div class="container-fluid">
			<!-- Header of the web page -->
			<header>
				<nav class="navbar navbar-default navbar-fixed-top">
				  <div class="container-fluid">
				  
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
					  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					  </button>
					  <a class="navbar-brand" href="#">Artisansdiary.com</a> 
					  <!-- <img src="/WhoSabiWork/Images/WhoSabiWorkL1.jpg" width="200" height="100" alt="WhoSabiWork Logo"  /> -->
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					
					  <ul class="nav navbar-nav">
						<li class="active"><a href="#">Home <span class="sr-only">(current)</span></a></li>
						<li><a href="#">Services</a></li>
						<li><a href="#">Mechanics</a></li>
						<li><a href="#">Spare part Dealers</a></li>
						<li><a href="#">Contact us</a></li>
						<!--
						<li class="dropdown">
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
						  <ul class="dropdown-menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#">Separated link</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#">One more separated link</a></li>
						  </ul>
						</li> -->
					  </ul>
					  
					  <!-- 
					  <form class="navbar-form navbar-left">
						<div class="form-group">
						  <input type="text" class="form-control" placeholder="Search">
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					  </form>
					   -->
					  
					  <ul class="nav navbar-nav navbar-right">
						<!-- <li><a href="#">Link</a></li> -->
						<li class="dropdown">
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
						  <ul class="dropdown-menu">
							<li><a href="#">Log in</a></li>
							<li><a href="#">User Account</a></li>
							<li><a href="#">Business Account</a></li>
							<!--
							<li role="separator" class="divider"></li>
							<li><a href="#">Separated link</a></li>
							-->
						  </ul>
						</li>
					  </ul>
					  
					</div><!-- /.navbar-collapse -->
				  </div><!-- /.container-fluid -->
				</nav>
			</header>
			
			<!-- Add the image slide here -->
			<div class="row">
				<div class="col-sm-12">
					<div id="my-slider" class="carousel slide" data-ride="carousel">
						<!-- Indicators dot nav -->
						<ol class="carousel-indicators">
							<li data-target="#my-slider" data-slide-to="0 " class="active"></li>
							<li data-target="#my-slider" data-slide-to="1"></li>
							<li data-target="#my-slider" data-slide-to="2"></li>
						</ol>
						
						<!-- Wrapper for slides -->
						<div class="carousel-inner" role="listbox">
							<div class="item active">
								<img src="../Images/slideShowImage11.jpg" alt="Apo Mechanic Site" style="width:100%"/>
								<div class="carousel-caption">
									<h1>Tire Site, Apo, Abuja</h1>
								</div>
							</div>
							<div class="item">
								<img src="../Images/slideShowImage22.jpg" alt="Apo Mechanic Site" style="width:100%" />
								<div class="carousel-caption">
									<h1>Apo Mechanic Village</h1>
								</div>
							</div>
							<div class="item">
								<img src="../Images/slideShowImage33.jpg" alt="Apo Mechanic Site" style="width:100%" />
								<div class="carousel-caption">
									<h1>Mercedes-Benz Mechanics Site, Apo, Abuja</h1>
								</div>
							</div>
						</div>

						<!-- controls for next and prev buttons -->
						<a class="left carousel-control" href="#my-slider" role="button" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="right carousel-control" href="#my-slider" role="button" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
					</div>
				</div>
			</div>
			
			<!-- Jumbotron -->
			<div class="jumbotron" >
				<img src="../Images/OnewuElectrical.jpg" class="pull-right" />
				<h1> Jumbotron Trial 1 </h1>
				<p> This is our first Jumbotron. We hope it will turn out good. </p>
			</div>
			
			<!-- Jumbotron 2 -->
			<div class="jumbotron" >
				<img src="../Images/OnewuElectrical2.jpg" class="pull-left" />
				<h1> Jumbotron Trial 2 </h1>
				<p> This is our second Jumbotron. We hope it will turn out good. </p>
			</div>
			
			<!-- Jumbotron 3 -->
			<div class="jumbotron" >
				<img src="../Images/OnewuElectrical3.jpg" class="pull-right" />
				<h1> Jumbotron Trial 3 </h1>
				<p> This is our third Jumbotron. We hope it will turn out good. </p>
			</div>
			
			<!-- This will be ad sections for the home page -->
			<div class="row">
				<section class=" col-xs-12 col-lg-12 bg-success" >
					<!-- <div id="main"> -->
					<!-- <div class="container"> -->
						<div>
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-11.5" >
								<div class="adContainer col-lg-12 col-md-12 col-xs-12"> <!-- style="width:100%" -->
								<p class="adImage"><img src="../Images/DozieMechanic1.jpg" alt="Image of technician business" name="AdImage" height="150" class="img-responsive" style="width:100%" /></p>             
								  <h1 class="adTitle">Description of technician Business</h1>
								  <p class="adContent">Name of Technician</p>
								  <p class="adContent">Address of Technician</p>
								  <p class="adContent">Contact of technician</p>
								</div>
							</div>
							
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-11.5" >
								<div class="adContainer col-lg-12 col-md-12 col-xs-12" > <!-- style="width:100%" -->
									<p class="adImage"><img src="../Images/DozieMechanic3.jpg" alt="Image of technician business" name="AdImage" height="150" class="img-responsive" style="width:100%" /></p>              
									<h1 class="adTitle">Description of technician Business</h1>
									<p class="adContent">Name of Technician</p>
									<p class="adContent">Address of Technician</p>
									<p class="adContent">Contact of technician</p>
								</div>
							</div>
							
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-11.5">
								<div class="adContainer col-lg-12 col-md-12 col-xs-12" > <!-- style="width:100%" -->
									<p class="adImage"><img src="../Images/DozieMechanic11.jpg" alt="Image of technician business" name="AdImage" height="150" class="img-responsive" style="width:100%" /></p>             
									<h1 class="adTitle">Description of technician Business</h1>
									<p class="adContent">Name of Technician</p>
									<p class="adContent">Address of Technician</p>
									<p class="adContent">Contact of technician</p>
								</div>
							</div>
							
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-11.5">
								<div class="adContainer col-lg-12 col-md-12 col-xs-12" > <!-- style="width:100%" -->
									<p class="adImage"><img src="../Images/DozieMechanic22.jpg" alt="Image of technician business" name="AdImage" style="width:100%" height="150" class="img-responsive" /></p>              
									<h1 class="adTitle">Description of technician Business</h1>
									<p class="adContent">Name of Technician</p>
									<p class="adContent">Address of Technician</p>
									<p class="adContent">Contact of technician</p>
								</div>
							</div>
						</div>
					<!-- </div> -->
					<!-- </div>	-->
				</section>
			</div>
			
			<!-- Footer section -->
			<div class="row">
				<footer class="col-xs-12">
					<p class="text-center">&copy; copyright 2018</p>
				</footer>
			</div>
		</div>
		
		<!-- JavaScript files should be linked at the bottom of the page -->
		<script src="./javascripts/jquery.js" type="text/javascript"></script>
		<!-- Latest complied and minified JavaScript -->
		<script src="./Bootstrap/bootstrap-3.3.7-dist/js/bootstrap.min.js" type="text/javascript"></script>
	</body>
</html>