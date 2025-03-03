// Run Ajax here to retrieve the target_path of the image.
$(document).ready(function(){
	// make vanilla a global variable so it can be accessed by any button
	var vanilla;
	var pathToImg;
	var imagePath;
	var commentValid;
	// var validationResult;

	$('#photo_upload').change(function(event){
		event.preventDefault();
		// Validate image upload
		var validationResult = validateImageUpload("photo_upload", "img_show");		
		// console.log(validationResult);
		// typeof validationResult === "string"
		if (!validationResult.errorType) {
			$("#imgErrorReport").empty();
			$("#imgErrorReport").append(validationResult.error);
		} else {
			$("#imgErrorReport").empty();
			$("#imgErrorReport").append("");
		}
	});

	$("#caption").on('input', function(event){
		// Get the caption of the image
		var captionVal = $.trim($("#caption").val());		

		$.ajax({
			url: '../PHP-JSON/uploadPhoto_JSON.php',
			dataType: 'json',
			type: 'post',
			data: {caption: captionVal},

			success: function(response){
				if (response.success === true) {
					commentValid = true;
					$("#imgErrorReport").empty();
				} else {
					commentValid = false;
					$("#imgErrorReport").empty();
					$("#imgErrorReport").append(response.result);
				}
			}
		});
	});

	$('#submitPhoto').on('click', function(event){
		event.preventDefault();
		console.log("Comment valid is: "+commentValid);
		// Validate image upload
		var validationResult1 = validateImageUpload("photo_upload", "img_show");
		// console.log(validationResult1);
		if (!validationResult1.errorType) {
			$("#imgErrorReport").empty();
			$("#imgErrorReport").append(validationResult1.error);
			return false;
		} else if (!commentValid) {
			$("#imgErrorReport").empty();
			$("#imgErrorReport").append("Change your photo comment before uploading.");
		} else {
			// Get the caption of the image
			var captionVal = $.trim($("#caption").val());
			var fd = new FormData();
			var files = $('#photo_upload')[0].files[0];
			// console.log("The value is: "+document.getElementById("photo_upload").value);
			fd.append('photo', files);
			fd.append('caption', captionVal);		

			$.ajax({
				url: '../PHP-JSON/uploadPhoto_JSON.php',
				dataType: 'json',
				type: 'post',
				data: fd,
				cache: false,
				contentType: false,
				processData: false,
				
				// Custom XMLHttpRequest for progress bar display during image upload
		    xhr: function () {
		      var myXhr = $.ajaxSettings.xhr();
		      if (myXhr.upload) {
		        // For handling the progress of the upload
		        myXhr.upload.addEventListener('progress', function (e) {
		          // set percent default value to 0
		          var percent = 0;
		          if (e.lengthComputable) {
		          	percent = ((e.loaded / e.total) * 100).toFixed(0);
		          	// console.log(e.loaded+" | "+e.total);
		          	// console.log("Percent loaded is: "+percent+"%");
		          	
		          	// disable the submit button during picture upload 
		          	// to avoid multiple clicks and enable it later
								$('#submitPhoto').attr('disabled', true);
		            $('#imageProgressBar').attr({
		              value: e.loaded,
		              max: e.total,
		            });
		            $('#imageProgressBar').css('display', 'block');
		          }
		        }, false);
		      }
		      return myXhr;
		    },

				success: function(response) {
					// console.log(response);
					if (response.success === "image not saved") {
						$('#imgErrorReport').html("");
						$('#imgErrorReport').append(response.result);
					} else {
						// Clear image image error message div if it was set
						$('#imgErrorReport').html("");
						var display = document.getElementById('display');
						// Set the height of the modal to fit the screen of the window
						/* var winHeight = $(window).height();
						winHeight = winHeight + 100;
						$(".photoUploadModal").css("height", winHeight); */

						// show the photo upload modal
						$('.photoUploadModal').fadeIn("slow");
						document.querySelector('.photoUploadModal').style.display = 'flex';

						// Make cropping div visible
						$('#imageBox').css('display', 'block');
						$('#decisionBtns').css('display', 'block');
						$('#crop').css('display', 'inline-block');
						$('#display').css('display', 'block');
						$('#removeImage').css('display', 'inline-block');
						$('#rotateLeft').css('display', 'inline-block');
						$('#rotateRight').css('display', 'inline-block');
						
						pathToImg = '/Public/images/' + response.result;
						// console.log('Edited image path is: ' + pathToImg);
						
						var el = document.getElementById('imageBox');
						// Make the screen width adjustable for mobile mode
						var screenWidth = $(window).width();
						if (screenWidth <= 320) {
							var boundaryDimensions = { width: 290, height: 290 };
							var viewportDimensions = { width: 250, height: 250 };
							var desiredMaxWidth = 300; // pixels
						} else if (screenWidth <= 480) {
							var boundaryDimensions = { width: 400, height: 400 };
							var viewportDimensions = { width: 300, height: 300 };
							var desiredMaxWidth = 300; // pixels
						} else {
							var boundaryDimensions = { width: 450, height: 450 };
							var viewportDimensions = { width: 350, height: 350 };
							var desiredMaxWidth = 350; // pixels
						}
						
						const image = new Image();
						const maxZoom = image.width * viewportDimensions.width / image.width / desiredMaxWidth;
						vanilla = new Croppie(el, {
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
						});
					}
					// Enable the submit button again after picture upload.
					$('#submitPhoto').attr('disabled', false);
				} 			
			});
		}
		// End of if condition checking validation
	});

	$('#rotateLeft').click(function(){
		vanilla.rotate(parseInt($(this).data('deg')));
	});

	$('#rotateRight').click(function(){
		vanilla.rotate(parseInt($(this).data('deg')));
	});

	// This controls the cropping of the image after it has been uploaded
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
				dataType: 'json',
				data: formData,
				cache: false,
				processData: false,
				contentType: false,
				success: function(data) {
					// Clear the div of the uploaded image and decision buttons
					$('#imageBox').fadeOut("slow");
					$('#decisionBtns').fadeOut("slow");
					display.innerHTML = data.result;
					
					// Perform the scrolling here
					$('html.body').animate({scrollTop: $(document).height()}, 'slow');
				}
			});
		});
	});

	// This controls the removal of cropped image created so another can be created
	$(document).on('click', '#remove_button', function() {
		/*if (confirm("Are you sure you want to change your cropped image?")) {
			
		} else {
			// No action will be taken
			return false;
		} */
		var path = $('#remove_button').data('path');
		// console.log("Image path gotten from remove button is: " + path);
		
		$.ajax({
			url: "../PHP-JSON/removeCropImage_JSON.php",
			method: "POST",
			dataType: 'json',
			data: {path: path},
			success: function (data) {
				// clear the display div where the image was uploaded
				$('#display').html('');
				// $('#display').html('<p>'+data.result+'</p>');
				// Hide the modal
				$('#imageBox').fadeIn("slow");
				$('#decisionBtns').fadeIn("slow");
				// $('.photoUploadModal').fadeOut("slow");
			}
		});
	});

	// This function controls the reselection of image to be cropped
	$('#reselectPhoto').click(function(event){
		event.preventDefault();
		// Validate image upload
		var validationResult2 = validateImageUpload("photo_upload", "img_show");
		if (!validationResult2.errorType) {
			$("#imgErrorReport").empty();
			$("#imgErrorReport").append(validationResult2.error);
			return false;
		} else {
			// Get the caption of the image
			var captionVal = $.trim($("#caption").val());
			var fd = new FormData();
			var files = $('#photo_upload')[0].files[0];
			fd.append('photo', files);
			fd.append('caption', captionVal);		
			
			$.ajax({			
				url: '../PHP-JSON/uploadPhoto_JSON.php',
				dataType: 'json',
				type: 'post',
				data: fd,
				cache: false,
				contentType: false,
				processData: false,
				
				// Custom XMLHttpRequest for progress bar display during image upload
		    xhr: function() {
					var myXhr = $.ajaxSettings.xhr();
		      if (myXhr.upload) {
		        // For handling the progress of the upload
		        myXhr.upload.addEventListener('progress', function (e) {
		          // set percent default value to 0
		          var percent = 0;
		          if (e.lengthComputable) {
		          	percent = ((e.loaded / e.total) * 100).toFixed(0);
		          	// console.log(e.loaded+" | "+e.total);
		          	// console.log("Percent loaded is: "+percent+"%");
		          	
		          	// disable the submit button during picture upload to avoid multiple clicks
								$('#reselectPhoto').attr('disabled', true);
		            $('#imageProgressBar').attr({
		              value: e.loaded,
		              max: e.total,
		            });
		            $('#imageProgressBar').css('display', 'block');
		          }
		        }, false);
		      }						      
		      return myXhr;
		    },
				
				success: function(response) {
					if (response.success === "image not saved") {
						$('#imgErrorReport').html("");
						$('#imgErrorReport').append(response.result);
					} else {
						// Clear image image error message div if it was set
						$('#imgErrorReport').html("");
						var displayCrop = document.getElementById('displayCrop');
						// show the photo upload modal
						$('.photoUploadModal').fadeIn("slow");
						document.querySelector('.photoUploadModal').style.display = 'flex';

						// Make cropping div visible
						$('#imageBox').css('display', 'block');
						$('#crop').css('display', 'inline-block');
						$('#display').css('display', 'block');
						$('#decisionBtns').css('display', 'block');
						$('#removeImage').css('display', 'inline-block');
						$('#rotateLeft').css('display', 'inline-block');
						$('#rotateRight').css('display', 'inline-block');
						
						imagePath = '/Public/images/' + response.result;

						vanilla.bind({
							url: imagePath,
							orientation: 1
						}).then(function(){
							$('.cr-slider').attr({'min': 0.387, 'max': 1});
						});
					}
					// Enable the submit button after picture upload 
					$('#reselectPhoto').attr('disabled', false);
				}
			});
		}
			
	});

	// This function triggers the cancel photo button when clicked so 
	// another image can be selected
	$('#removeImage').click(function(){
		$.ajax({
			url: "../PHP-JSON/removeCropImage_JSON.php",
			method: "POST",
			dataType: 'json',
			data: {changeImage: "changeImage"},
			success: function (data) {
				if (data.success == true) {
					
					$('#display').html('');
					$('#rotateLeft').css('display', 'none');
					$('#rotateRight').css('display', 'none');
					$('#crop').css('display', 'none');
					$('#display').css('display', 'none');
					$('#removeImage').css('display', 'none');
					$('#imageBox').css('display', 'none');
					$('#submitPhoto').css('display', 'none');
					$('#reselectPhoto').css('display', 'inline-block');
					$('.photoUploadModal').fadeOut("slow");
					// reset the progress bar upload
					$('#imageProgressBar').attr({
            value: "0",
            max: "100",
          });
          $('#imageProgressBar').css('display', 'none');
          $('#reselectPhoto').removeAttr('disabled');
          $('#submitPhoto').removeAttr('disabled');
				} else {
					// "display" changed from "displayCrop"
					$('#display').append('<p>'+data.result+'</p>');
					$('#display').append('<p>'+data.result2+'</p>');
				}
			}
		});
	});

	// This controls the uploading / saving of the image to the database
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
					$('#rotateRight').fadeOut('slow');
					$('#rotateLeft').fadeOut('slow');
					$('#removeImage').fadeOut('slow');

					// $('#display').fadeOut('slow');
					// infrom the user of the success in saving the image
					// $('#display').html("Your photograph has been added to your gallery.");
					
					// append the image to the photo gallery div
					$('.CusPhotoGallery').append("<div id='displayPicture"+imageCounter+"' class='displayPicture'><img name='cus-id-image' id='cus-id-image"+imageCounter+"' class='cus-id-image' src='"+croppedImgPath+"' width='200' height='200' alt='customer ad image' /> <div><p id='imageCaption"+imageCounter+"' class='imageCaption'>"+data.photo_caption+"</p><input type='hidden' name='customerId' class='customerId' value='"+customerId+"' /> <input type='hidden' name='imageId' id='imageId"+imageCounter+"' value='"+data.photoId+"' /> </div> </div>");
					$('#display').html('');
					// change the button to resubmit the image										
					$('#submitPhoto').css('display', 'none');
					$('#reselectPhoto').css('display', 'inline-block');
					// Close the modal
					$('.photoUploadModal').fadeOut("slow");
					// reset the progress bar upload
					$('#imageProgressBar').attr({
	          value: "0",
	          max: "100",
	        });
	        $('#imageProgressBar').css('display', 'none');
				} else {
					$('#display').html(data.errors);
				}
			}
		});							
	});
});






// *****************************************
// Code for profile image upload
// *****************************************
// Run Ajax here to retrieve the target_path of the profile image.
$(document).ready(function(){
	// make vanilla a global variable so it can be accessed by any button
	var vanilla;
	var pathToImg;
	var imagePath;

	$('#avatar_upload').change(function(event){
		event.preventDefault();
		// Validate image upload
		var validationResult = validateImageUpload("avatar_upload", "avatar_show");		
		// console.log(validationResult);
		// typeof validationResult === "string"
		if (!validationResult.errorType) {
			$("#avatarErrorReport").empty();
			$("#avatarErrorReport").append(validationResult.error);
		} else {
			$("#avatarErrorReport").empty();
			$("#avatarErrorReport").append("");
		}
	});

	$('#submitAvatar').click(function(event){
		event.preventDefault();

		// Validate image upload
		var validationResult1 = validateImageUpload("avatar_upload", "avatar_show");
		// console.log(validationResult1);
		if (!validationResult1.errorType) {
			$("#avatarErrorReport").empty();
			$("#avatarErrorReport").append(validationResult1.error);
			return false;
		} else {
			var fd = new FormData();
			var files = $('#avatar_upload')[0].files[0];
			fd.append('photo', files);
			var avatarImage = true;
			fd.append('avatarImage', avatarImage);				

			// Determine the URL for the php page
			var pathArray = window.location.pathname.split( '/' );
			// Get the filename of the page from the array.
			var fileName = pathArray[pathArray.length-1];
			if (fileName === "customerEditPage2.php") {
				var phpURL = '../PHP-JSON/uploadPhoto_JSON.php';
			} else if (fileName === "userEditPage.php") {
				var phpURL = '../PHP-JSON/uploadSaveDeleteUserAvatar_JSON.php';
			}

			$.ajax({			
				url: phpURL,
				dataType: 'json',
				type: 'post',
				data: fd,
				cache: false,
				contentType: false,
				processData: false,

				// Custom XMLHttpRequest for progress bar display during image upload
		    xhr: function () {
		      var myXhr = $.ajaxSettings.xhr();
		      if (myXhr.upload) {
		        // For handling the progress of the upload
		        myXhr.upload.addEventListener('progress', function (e) {
		          // set percent default value to 0
		          var percent = 0;
		          if (e.lengthComputable) {
		          	percent = ((e.loaded / e.total) * 100).toFixed(0);
		          	// console.log(e.loaded+" | "+e.total);
		          	// console.log("Percent loaded is: "+percent+"%");
		          	
		          	// disable the submit button during image upload to avoid multiple submission
								$('#submitAvatar').attr('disabled', true);
		            $('#avatarProgressBar').attr({
		              value: e.loaded,
		              max: e.total,
		            });
		            $('#avatarProgressBar').css('display', 'block');
		          }
		        }, false);
		      }
		      return myXhr;
		    },

				success: function(response) {
					// console.log(response);
					if (response.success === "image not saved") {
						// show the photo upload modal
						$('.avatarUploadModal').fadeIn("slow");
						document.querySelector('.avatarUploadModal').style.display = 'flex';
						// Clear previous content in divs
						$('#showImage').html('');
						$('#displayCrop').html('');
						$('#showImage').css('display', 'block');
						// Display the error message
						$('#showImage').append(response.result);
					} else {
						
						var displayCrop = document.getElementById('displayCrop');
						// Clear previous content in divs
						// $('#showImage').html('');
						// $('#displayCrop').html('');

						// show the photo upload modal
						$('.avatarUploadModal').fadeIn("slow");
						document.querySelector('.avatarUploadModal').style.display = 'flex';

						// Make cropping div visible
						$('#showImage').css('display', 'block');
						$('#decisionBtnsDiv').css('display', 'block');
						$('#cropImage').css('display', 'inline-block');
						$('#displayCrop').css('display', 'block');
						$('#changeImage').css('display', 'inline-block');
						$('#rotateLeftBtn').css('display', 'inline-block');
						$('#rotateRightBtn').css('display', 'inline-block');
						
						imagePath = '/Public/images/' + response.result;
						// console.log('Edited image path is: ' + imagePath);
						
						var el = document.getElementById('showImage');
						// Make the screen width adjustable for mobile mode
						var screenWidth = $(window).width();
						if (screenWidth <= 320) {
							var boundaryDimensions = { width: 320, height: 320 };
							var viewportDimensions = { width: 300, height: 300, type: 'circle' };
							var desiredMaxWidth = 300; // pixels
						} else {
							var boundaryDimensions = { width: 400, height: 400 };
							var viewportDimensions = { width: 300, height: 300, type: 'circle' };
							var desiredMaxWidth = 300; // pixels
						}
						
						const image = new Image();
						const maxZoom = image.width * viewportDimensions.width / image.width / desiredMaxWidth;
						vanilla = new Croppie(el, {
							enableExif: true,
							viewport: viewportDimensions,
							boundary: boundaryDimensions,
							showZoomer: true,
							enableOrientation: true,
							maxZoom: maxZoom
						});

						vanilla.bind({
							url: imagePath,
							orientation: 1
						}).then(function(){
							$('.cr-slider').attr({'min': 0.387, 'max': 1});
						});
					}
					// Enable the submit button after image has been uploade
					$('#submitAvatar').attr('disabled', false);
				}
			});
		}
			
	});

	// Scroll to the bottom of the web page after uploading the image
	// $('html.body').animate({scrollTop: $(document).height()}, 'slow');

	$('#rotateLeftBtn').click(function(){
		vanilla.rotate(parseInt($(this).data('deg')));
	});

	$('#rotateRightBtn').click(function(){
		vanilla.rotate(parseInt($(this).data('deg')));
	});

	// This controls the cropping of the image after it has been uploaded
	$('#cropImage').on('click', function(e) {
		// on button click
		vanilla.result({type: 'blob', size: 'viewport'}).then(function(blob) {
			// do something with cropped blob
			
			// console.log(blob);
			const formData = new FormData();
			
			// Pass the image file name
			formData.append('croppedImage', blob, imagePath);
			
			// Determine the URL for the php page
			var pathArray = window.location.pathname.split( '/' );
			// Get the filename of the page from the array.
			var fileName = pathArray[pathArray.length-1];
			// console.log("The page name is: "+fileName);
			if (fileName === "customerEditPage2.php") {
				var phpURL = '../PHP-JSON/uploadSaveDeleteAvatarImage_JSON.php';
			} else if (fileName === "userEditPage.php") {
				var phpURL = '../PHP-JSON/uploadSaveDeleteUserAvatar_JSON.php';
			}

			// Use 'jquery.ajax' method
			$.ajax({
				url: phpURL,
				type: "POST",
				dataType: 'json',
				data: formData,
				cache: false,
				processData: false,
				contentType: false,
				success: function(data) {
					// Clear the div of the uploaded image and decision buttons
					$('#showImage').fadeOut("slow");
					$('#decisionBtnsDiv').fadeOut("slow");
					displayCrop.innerHTML = data.result;
					
					// Scroll to the bottom of the page
					$('html, body').animate({scrollTop: $(document).height()}, 'slow');
				}
			});
		});
	});

	// This controls the removal of cropped image created so another can be created
	$(document).on('click', '#remove_avatar', function() {
		/* if (confirm("Are you sure you want to change your cropped image?")) {
			
		} else {
			// No action will be taken
			return false;
		} */
		var path = $('#remove_avatar').data('path');
		// console.log("Image path gotten from remove button is: " + path);
		
		// Determine the URL for the php page
		var pathArray = window.location.pathname.split( '/' );
		// Get the filename of the page from the array.
		var fileName = pathArray[pathArray.length-1];
		if (fileName === "customerEditPage2.php") {
			var phpURL = '../PHP-JSON/uploadSaveDeleteAvatarImage_JSON.php';
		} else if (fileName === "userEditPage.php") {
			var phpURL = '../PHP-JSON/uploadSaveDeleteUserAvatar_JSON.php';
		}

		$.ajax({
			url: phpURL,
			method: "POST",
			dataType: 'json',
			data: {path: path},
			success: function (data) {
				// clear the display div where the image was uploaded
				$('#displayCrop').html('');
				// $('#displayCrop').html('<p>'+data.result+'</p>');
				// Hide the modal
				$('#showImage').fadeIn("slow");
				$('#decisionBtnsDiv').fadeIn("slow");
				// $('.avatarUploadModal').fadeOut("slow");
			}
		});
	});

	// This function controls the reselection of image to be cropped
	$('#reselectAvatar').click(function(event){
		event.preventDefault();

		// Validate image upload
		var validationResult2 = validateImageUpload("avatar_upload", "avatar_show");
		if (!validationResult2.errorType) {
			$("#avatarErrorReport").empty();
			$("#avatarErrorReport").append(validationResult2.error);
			return false;
		} else {
			var fd = new FormData();
			var files = $('#avatar_upload')[0].files[0];
			fd.append('photo', files);
			var reselectedAvatar = true;
			fd.append('reselectedAvatar', reselectedAvatar);

			// Determine the URL for the php page
			var pathArray = window.location.pathname.split( '/' );
			// Get the filename of the page from the array.
			var fileName = pathArray[pathArray.length-1];
			if (fileName === "customerEditPage2.php") {
				var phpURL = '../PHP-JSON/uploadPhoto_JSON.php';
			} else if (fileName === "userEditPage.php") {
				var phpURL = '../PHP-JSON/uploadSaveDeleteUserAvatar_JSON.php';
			}

			$.ajax({			
				url: phpURL,
				dataType: 'json',
				type: 'post',
				data: fd,
				cache: false,
				contentType: false,
				processData: false,

				// Custom XMLHttpRequest for progress bar display during image upload
		    xhr: function() {
					var myXhr = $.ajaxSettings.xhr();
		      if (myXhr.upload) {
		        // For handling the progress of the upload
		        myXhr.upload.addEventListener('progress', function (e) {
		          // set percent default value to 0
		          var percent = 0;
		          if (e.lengthComputable) {
		          	percent = ((e.loaded / e.total) * 100).toFixed(0);
		          	// console.log(e.loaded+" | "+e.total);
		          	// console.log("Percent loaded is: "+percent+"%");
		          	
		          	// disable the submit button to avoid multiple click of image during submission
								$('#reselectAvatar').attr('disabled', true);
		            $('#avatarProgressBar').attr({
		              value: e.loaded,
		              max: e.total,
		            });
		            $('#avatarProgressBar').css('display', 'block');
		          }
		        }, false);
		      }						      
		      return myXhr;
		    },

				success: function(response) {
					if (response.success === "image not saved") {
						// show the photo upload modal
						$('.avatarUploadModal').fadeIn("slow");
						document.querySelector('.avatarUploadModal').style.display = 'flex';
						// Clear previous content in divs
						$('#showImage').html('');
						$('#displayCrop').html('');
						$('#showImage').css('display', 'block');
						// Display the error message
						$('#showImage').append(response.result);
					} else {
						var displayCrop = document.getElementById('displayCrop');

						// show the photo upload modal
						$('.avatarUploadModal').fadeIn("slow");
						document.querySelector('.avatarUploadModal').style.display = 'flex';
						// Clear previous content in divs
						// $('#showImage').html('');
						// $('#displayCrop').html('');
						// Make cropping div visible
						$('#showImage').css('display', 'block');
						$('#cropImage').css('display', 'inline-block');
						$('#displayCrop').css('display', 'block');
						$('#decisionBtnsDiv').css('display', 'block');
						$('#changeImage').css('display', 'inline-block');
						$('#rotateLeftBtn').css('display', 'inline-block');
						$('#rotateRightBtn').css('display', 'inline-block');
						
						imagePath = '/Public/images/' + response.result;

						vanilla.bind({
							url: imagePath,
							orientation: 1
						}).then(function(){
							$('.cr-slider').attr({'min': 0.387, 'max': 1});
						});

						// Scroll to the bottom of the web page after uploading the image
						// $('html.body').animate({scrollTop: $(document).height()}, 'slow');
					}
					// Enable the submit button after image has been uploade
					$('#reselectAvatar').attr('disabled', false);
				}
			});
		}
			
	});

	// This function triggers the cancel photo button when clicked so 
	// another image can be selected
	$('#changeImage').click(function(){
		// Determine the URL for the php page
		var pathArray = window.location.pathname.split( '/' );
		// Get the filename of the page from the array.
		var fileName = pathArray[pathArray.length-1];
		if (fileName === "customerEditPage2.php") {
			var phpURL = '../PHP-JSON/uploadSaveDeleteAvatarImage_JSON.php';
		} else if (fileName === "userEditPage.php") {
			var phpURL = '../PHP-JSON/uploadSaveDeleteUserAvatar_JSON.php';
		}

		$.ajax({
			url: phpURL,
			method: "POST",
			dataType: 'json',
			data: {changeImage: "changeImage"},
			success: function (data) {
				if (data.success == true) {
					
					$('#displayCrop').html('');
					$('#rotateLeftBtn').css('display', 'none');
					$('#rotateRightBtn').css('display', 'none');
					$('#cropImage').css('display', 'none');
					$('#displayCrop').css('display', 'none');
					$('#changeImage').css('display', 'none');
					$('#showImage').css('display', 'none');
					$('#submitAvatar').css('display', 'none');
					$('#reselectAvatar').css('display', 'inline-block');
					$('.avatarUploadModal').fadeOut("slow");
					// reset the progress bar upload
					$('#avatarProgressBar').attr({
            value: "0",
            max: "100",
          });
          $('#avatarProgressBar').css('display', 'none');
          $('#reselectAvatar').removeAttr('disabled');
          $('#submitAvatar').removeAttr('disabled');
				} else {
					// $('#displayCrop').html('');
					$('#displayCrop').append('<p>'+data.result+'</p>');
					$('#displayCrop').append('<p>'+data.result2+'</p>');
				}
			}
		});						
	});

	// This controls the uploading / saving of the image to the database
	$(document).on('click', '#upload_avatar', function() {
		// Run an ajax function to save image in PHP
		
		// get the image path of the cropped image
		var croppedImgPath = $('#cropped_avatar').attr("src");
		// get the total number of images in the gallery, there is no need incrementing by one since the id was incremented before the loop ended
		// var imageCounter = $('#imageCounter').attr("value");						
		
		// Determine the URL for the php page
		var pathArray = window.location.pathname.split( '/' );
		// Get the filename of the page from the array.
		var fileName = pathArray[pathArray.length-1];
		if (fileName === "customerEditPage2.php") {
			// get the customer id from the hidden input element
			var customerId = $('.customerId').attr("value");

			var phpURL = '../PHP-JSON/uploadSaveDeleteAvatarImage_JSON.php';
		} else if (fileName === "userEditPage.php") {
			var phpURL = '../PHP-JSON/uploadSaveDeleteUserAvatar_JSON.php';
		}

		$.ajax({
			url: phpURL,
			method: "POST",
			dataType: 'json',
			data: {upload: 'upload'},
			success: function (data) {
				// console.log(data);
				if (data.success) {
					$('#showImage').fadeOut('slow');
					$('#cropImage').fadeOut('slow');
					$('#rotateRightBtn').fadeOut('slow');
					$('#rotateLeftBtn').fadeOut('slow');
					$('#changeImage').fadeOut('slow');
					/*
					// Make image able to be reuploaded after uploading
					var imagePath = '/Public/images/' + response.result;

					vanilla.bind({
						url: imagePath,
						orientation: 1
					}).then(function(){
						$('.cr-slider').attr({'min': 0.387, 'max': 1});
					}); */

					// infrom the user of the success in saving the image
					$('#displayCrop').html("<p>Your profile image has been changed.</p>");									
					// append the image to the avatar display div
					$('#showProfileImage').html('');
					$('#showProfileImage').append("<img id='avatarImage' src='"+croppedImgPath+"' alt='customer profile image' />");
					// change the button to resubmit the image										
					$('#submitAvatar').css('display', 'none');
					$('#reselectAvatar').css('display', 'inline-block');
					// The above method rr this method
					// $('#avatarImage').css('src', '+croppedImgPath+');
					$('#display').html('');
					// Close the modal
					$('.avatarUploadModal').fadeOut("slow");
					// reset the progress bar upload
					$('#avatarProgressBar').attr({
	          value: "0",
	          max: "100",
	        });
	        $('#avatarProgressBar').css('display', 'none');
				} else {
					$('#displayCrop').html(data.errors);
				}
			}
		});							
	});

});







/****************************************************
	Functions used in the loading of the images
****************************************************/

function validateImageUpload(fileChooser, imgHolderId) {
	var fuData = document.getElementById(fileChooser);
	var fileUploadPath = fuData.value;
	var error;
	var result;

	if (fileUploadPath == '') {
		// Put a default image in place of the image viewer
    $("#"+imgHolderId).attr('src', "../images/emptyImageIcon.png");
	  result = {
  		errorType: false,
	  	error: "Please upload an image"
  	};    
		return result;
	} else {
    var extension = fileUploadPath.substring(fileUploadPath.lastIndexOf('.') + 1).toLowerCase();
    if (extension == "gif" || extension == "png" || extension == "jpeg" || extension == "jpg") {
      if (fuData.files && fuData.files[0]) {
        var size = fuData.files[0].size;
        // Check if the image is greater than 7MB
        if(size > 7340032){
        	// Put a default image in place of the image viewer
        	$("#"+imgHolderId).attr('src', "../images/emptyImageIcon.png");
        	result = {
        		errorType: false,
        		error: "Maximum image size exceeded. Image should be less than 7MB."
        	};
          return result;
          // return false;
        }else{
          var reader = new FileReader();
          reader.onload = function(e) {
            // document.getElementById(imgHolderId).attr('src', e.target.result);
            $("#"+imgHolderId).attr('src', e.target.result);
          }
          reader.readAsDataURL(fuData.files[0]);
          result = {
			  		errorType: true,
				  	error: "Image accepted!!!"
			  	};
          return result;
        }
      }
    } else {
    	// Put a default image in place of the image viewer
      $("#"+imgHolderId).attr('src', "../images/emptyImageIcon.png");
    	result = {
	  		errorType: false,
		  	error: "Allowed image types are GIF, PNG, JPG and JPEG. "
	  	};    
			return result;
    }
	}
}