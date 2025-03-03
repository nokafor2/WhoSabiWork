<?php 

?>
<!DOCTYPE html>
<html>
<head>
	<title>Image Ajax Upload Test</title>
	<link href="../stylesheets/croppie.css" rel="stylesheet" type="text/css" />
	<link href="../stylesheets/cropImage.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../javascripts/jquery.js"></script>
	<script type="text/javascript" src="../javascripts/croppie.js"></script>
</head>
<body>
	<div class="container">
		<form method="post" action="" enctype="multipart/form-data">
			<div class="preview">
				<img src="" id="img" width="300" height="300">
			</div>
			<div>
				<input type="file" id="file" name="file">
				<input type="button" class="button" value="Upload" id="but_upload" name="but_upload">
		</form>
		<div id='imageBox' ></div>
		<button id='crop' class='crop_button croppie-result btnStyle1'><a class='scroll' id='cropLinkBtn' href='#display'> Crop </a></button>
		<br/><br/>
		<div id='display' ></div>
	</div>

	<!-- script -->
	<script type="text/javascript">
		$(document).ready(function(){
			$('#but_upload').click(function(){
				var text = "dummy photo caption";
				var fd = new FormData();
				var files = $('#file')[0].files[0];
				fd.append('file', files);
				fd.append('caption', text);

				$.ajax({
					url: 'upload.php',
					dataType: 'json',
					type: 'post',
					data: fd,
					contentType: false,
					processData: false,
					success: function(response) {
						console.log(response);
						
						pathToImg = '/WhoSabiWork/public/images/' + response.result;
						// console.log('Edited image path is: ' + pathToImg);
						
						var el = document.getElementById('imageBox');
						// Make the screen width adjustable for mobile mode
						var screenWidth = $(window).width();
						if (screenWidth <= 320) {
							var boundaryDimensions = { width: 320, height: 320 };
							var viewportDimensions = { width: 300, height: 300 };
							var desiredMaxWidth = 300; // pixels
						} else if (screenWidth <= 480) {
							var boundaryDimensions = { width: 400, height: 400 };
							var viewportDimensions = { width: 300, height: 300 };
							var desiredMaxWidth = 300; // pixels
						} else {
							var boundaryDimensions = { width: 600, height: 600 };
							var viewportDimensions = { width: 500, height: 500 };
							var desiredMaxWidth = 500; // pixels
						}
						
						const image = new Image();
						const maxZoom = image.width * viewportDimensions.width / image.width / desiredMaxWidth;
						var vanilla = new Croppie(el, {
							enableExif: true,
							viewport: viewportDimensions,
							boundary: boundaryDimensions,
							showZoomer: true,
							enableOrientation: true,
							maxZoom: maxZoom
						});

						// Old code
						/* var el = document.getElementById('imageBox');
						var vanilla = new Croppie(el, {
							enableExif: true,
							viewport: { width: 300, height: 300 },
							boundary: { width: 400, height: 400 },
							showZoomer: true,
							enableOrientation: true
							// mouseWheelZoom: 'ctrl'
						}); */
						vanilla.bind({
							url: pathToImg,
							orientation: 1
						}).then(function(){
							$('.cr-slider').attr({'min': 0.387, 'max': 1});
						}); ;
						
						$('#crop').on('click', function(e) {
							// on button click
							vanilla.result({type: 'blob', size: 'viewport'}).then(function(blob) {
								// do something with cropped blob
								
								console.log(blob);
								const formData = new FormData();
								
								// Pass the image file name
								formData.append('croppedImage', blob, pathToImg);
								
								// Use 'jquery.ajax' method
								$.ajax({
									url: "../PHP-JSON/uploadCropImage_JSON.php",
									type: "POST",
									data: formData,
									processData: false,
									contentType: false,
									success: function(data) {
										// console.log("Working, data received");
										display.innerHTML = data;
										
										// Perform the scrolling here
										// scrollToBottomOfPage();
										// var scrollAction = $('#crop');
									}
								});
							});
							
							$(document).on('click', '#remove_button', function() {
								if (confirm("Are you sure you want to change your cropped image?")) {
									var path = $('#remove_button').data('path');
									// console.log("Image path gotten from remove button is: " + path);
									$.ajax({
										url: "../PHP-JSON/removeCropImage_JSON.php",
										method: "POST",
										data: {path: path},
										success: function (data) {
											// clear the display div where the image was uploaded
											$('#display').html('');
										}
									});
								} else {
									// No action will be taken
									return false;
								}
							});

							$(document).on('click', '#upload_image', function() {
								// Run an ajax function to save image in PHP
								
								// get the image path of the cropped image
								var croppedImgPath = $('#cropped_image').attr("src");
								// get the total number of images in the gallery, there is no need incrementing by one since the id was incremented before the loop ended
								var imageCounter = $('#imageCounter').attr("value");
								// get the customer id from the hidden input element
								var customerId = $('.customerId').attr("value");
								
								$.ajax({
									url: "../PHP-JSON/saveCropImage_JSON.php",
									method: "POST",
									dataType: 'json',
									data: {upload: 'upload'},
									success: function (data) {
										// console.log(data);
										if (data.success) {
											$('#imageBox').fadeOut('slow');
											$('#crop').fadeOut('slow');
											// $('#display').fadeOut('slow');
											// infrom the user of the success in saving the image
											$('#display').html("Your photograph has been added to your gallery.");
											
											// append the image to the photo gallery div
											$('.CusPhotoGallery').append("<div id='displayPicture"+imageCounter+"' class='displayPicture'><img name='cus-id-image' id='cus-id-image"+imageCounter+"' class='cus-id-image' src='"+croppedImgPath+"' width='200' height='200' alt='customer ad image' /> <div><p id='imageCaption"+imageCounter+"' class='imageCaption'>"+data.photo_caption+"</p><input type='hidden' name='customerId' class='customerId' value='"+customerId+"' /> <input type='hidden' name='imageId' id='imageId"+imageCounter+"' value='"+data.photoId+"' /> </div> </div>");
											
										} else {
											$('#display').html(data.errors);
										}
									}
								});
								
							});
						});
					}
				});
			});
		});
	</script>
	<!-- <script type="text/javascript" src="../javascripts/cropImage.js"></script> -->
</body>
</html>