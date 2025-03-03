
<?php
	require_once("../../includes/initialize.php");
	
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$cusId = $_SESSION['customer_id'];
		header('Location: /WhoSabiWork/Public/customer/customerEditPage2.php?id="'.$cusId.'"');
		// die;
	}
	
	$imgPath = $_SESSION['img_target_path'];
	// echo "The image path to be cropped is: ".$imgPath."<br/><br/>";
	
	$imgName = $_SESSION['img_name'];
	// echo "The image name is: ".$imgName."<br/><br/>";
?>

<link href="../stylesheets/croppie.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../javascripts/jquery.js"></script>
<script type="text/javascript" src="../javascripts/croppie.js"></script>
<style> 
	#rotate {
		margin: auto;
		width: 400px;
	}
	
	.button {
		margin: auto;
		width: 400px;
		height: 30px;
		display: block;
	}
	
	#display {
		margin: auto;
		width: 400px;
		display: block;
	}
	
	.cropped_image {
		margin: auto;
		display: block;
	}
	
	.croppie-container {
		width: 100%;
		height: 500px;
	}
</style>

<h1 style="text-align: center;" > Crop Your Image Here </h1>
<div id="imageBox" ></div>

<button id='crop' class="button croppie-result" > Crop </button>
<br/><br/>
<div id="display" ></div>
<br/><br/>
<form method="post" enctype="multipart/form-data">
	<input type="submit" class="button" name="returnBtn" value="Return to Gallery" />
</form>


<script>
	var display = document.getElementById('display');
	// var pathToImg = 'uploadedImg.jpg';
	// var pathToImg = '/WhoSabiWork/public/images/ugoezeT1.jpg';
	var pathToImg;
	
	// Run Ajax here to retrieve the target_path of the image.
	/* $.ajax({
		url: "getImgPath.php",
		type: "POST",
		dataType: 'text',
		success: function(data) {
			console.log(data);
			// useReturnData(data);
			pathToImg = data;
			
			console.log(pathToImg);
			pathToImg = '/WhoSabiWork/public/images/' + pathToImg;
			console.log('Edited image path is: ' + pathToImg);
			
			var el = document.getElementById('imageBox');
			var vanilla = new Croppie(el, {
				enableExif: true,
				viewport: { width: 300, height: 300 },
				boundary: { width: 400, height: 400 },
				showZoomer: true,
				enableOrientation: true
				// mouseWheelZoom: 'ctrl'
			});
			vanilla.bind({
				url: pathToImg,
				orientation: 1
			});
			
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
						url: "uploadImg.php",
						type: "POST",
						data: formData,
						processData: false,
						contentType: false,
						success: function(data) {
							console.log("Working, data received");
							display.innerHTML = data;
						}
					});
				});
			});
		}
	}); */
	
	function useReturnData(data) {
		pathToImg = data;
		console.log(pathToImg);
	}
	
	pathToImg = $.ajax({
		async: false,
		url: "../PHP-JSON/getImageName_JSON.php",
		type: "POST",
		data: {'GetConfig': 'YES'},
		dataType: 'TEXT'
	}).responseText;
	
	/* pathToImg = $.ajax({
		async: false,
		url: "getImgPath.php",
		type: "POST",
		data: {'GetConfig': 'YES'},
		dataType: 'JSON'
	}).responseJSON; */
	
	// console.log(pathToImg);
	pathToImg = '/WhoSabiWork/public/images/' + pathToImg;
	// console.log('Edited image path is: ' + pathToImg);
	
	/* var val = "<?php echo $imgName ?>";
	console.log("Image name from PHP is: " + val); */
	
	var el = document.getElementById('imageBox');
	var vanilla = new Croppie(el, {
		enableExif: true,
		viewport: { width: 300, height: 300 },
		boundary: { width: 400, height: 400 },
		showZoomer: true,
		enableOrientation: true
		// mouseWheelZoom: 'ctrl'
	});
	vanilla.bind({
		url: pathToImg,
		orientation: 1
	});
	
	$('#crop').on('click', function(e) {
		// on button click
		vanilla.result({type: 'blob', size: 'viewport'}).then(function(blob) {
			// do something with cropped blob
			
			// console.log(blob);
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
					console.log("Working, data received");
					display.innerHTML = data;
				}
			});
		});
	});
	
	$(document).on('click', '#remove_button', function() {
		if (confirm("Are you sure you want to change your cropped image?")) {
			var path = $('#remove_button').data('path');
			// console.log("Image path gotten from remove button is: " + path);
			$.ajax({
				url: "../PHP-JSON/uploadCropImage_JSON.php",
				method: "POST",
				data: {path: path},
				success: function (data) {
					$('#display').html('');
				}
			});
		} else {
			return false;
		}
	});

	$(document).on('click', '#upload_image', function() {
		// Run an ajax function to save image in PHP
		var imgPath = $('#remove_button').data('path');
		// console.log("Image path gotten from remove button is: " + path);
		$.ajax({
			url: "../PHP-JSON/uploadCropImage_JSON.php",
			method: "POST",
			data: {upload: "submit"},
			success: function (data) {
				$('#display').html('');
			}
		});
		
		// location.reload();
		// $('#imageBox').fadeOut('slow');
		// $('#crop').fadeOut('slow');
		// $('#display').fadeOut('slow');
		
	});
	
</script>



