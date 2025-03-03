<?php



?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Multiple Image Upload Tutorial</title>
	<!-- Load CSS files -->
	<link href="/Public/stylesheets/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet" type="text/css" />
		<link href="/Public/stylesheets/homePageStyles.css" rel="stylesheet" type="text/css" />
	<link href="/Public/stylesheets/customerEditPage2.css" rel="stylesheet" type="text/css" />
	<link href="/Public/stylesheets/cropImage.css" rel="stylesheet" type="text/css" />

	<!-- Load Javascript files -->
	<script src="/Public/javascripts/jquery.js" type="text/javascript"></script>
	<script type="text/javascript" src="../javascripts/multipleImageUpload.js"></script>
</head>
<body>
	<div id="container">
		<div id="mainCustomerEditPage">
			<form action="" method="post" enctype="multipart/form-data" name="profileEdit" >
		    <div id="uploadPhotoEdit" style="width: 100%">
		      <h3 class="divHeading">Upload Photos</h3>
		      <div class="divContent">
		        <input name="photosUpload" type="file" id="photosUpload" size="30" maxlength="30" accept="image/" class=" btnStyle1" multiple /> <!-- fileUpload -->
		        <!-- <button type="button" class="fileUploadBtn btnStyle1"><i class="fas fa-image"></i>Choose Your Photos</button> -->
		        <span class="fileUploadLabel"></span>
	          <p id="imgTypeLabel">Allowed images: .jpg, .jpeg, .png, .gif</p>
		        <p id="imgTypeLabel">You can upload a maxiumum of 20 pictures at a time.</p>        
		        <button id="submitPhotos" name="submitPhotos" class="submitBtn btnStyle1">Submit</button>
		        <br>
		        <progress id="imageProgressBar" class="progress" value="0" max="100" ></progress>
		        <div id="previewDiv" class="display_img" style="width: 100%; height: 100%;">
		          <!-- <img src="../images/emptyImageIcon.png" alt="" id="img_show" class="previewImg"> -->
		        </div>
		        <div id="imgErrorReport" ></div>
		  	  </div>
		    </div>
		  </form>
		</div>
	</div>

	<!-- Load Javascript files -->
	<script type="text/javascript" src="/Public/javascripts/customerEditPage2JSScripts.js"></script>
	<!-- <script type="text/javascript" src="/Public/javascripts/cropImage.js"></script> -->
</body>
</html>