/* This variable is defined here so it will be global so that the 
 "delPreviewImg(imgId)" function which is defined global (outside 
 of the $(document) fxn) can be used when deleting an image */
var previewImages = [];

$(document).ready(function(){
	/*************************************/
	// Check for the maximum uploadable image sizes in the server
	// Check for the total number of files that can be uploaded at a time in the server
	// Set default values
	var serverTotalUploadableSize = 100 * 1024 * 1024;
	var maxFilesUpload = 20;
	$.ajax({
		url: '../PHP-JSON/multipleImageUpload_JSON.php',
		dataType: 'json',
		type: 'post',
		data: {"action" : "getMaxUploadFilesize"},

		success: function(response) {
			if (response.success === true) {
				serverTotalUploadableSize = response.maxUploadableFilesize;
				maxFilesUpload = response.maxNumFileUploads;
			}	else {
				// Set default values
				serverTotalUploadableSize = 100 * 1024 * 1024;
				maxFilesUpload = 20;
			}
		}

	});

	$('#photosUpload').change(function(event){
		event.preventDefault();
		// Display the file reader
		$('.display_img').css('display', 'block');
				
		var totalImgSize = 0;
		var noneImgFile = 0;
		var previewImageSize = 0;
		var result1 = ""; var result2 = ""; var result3 = "";

		var formData = new FormData();
		var photos = $('#photosUpload')[0].files;

		
		// Update the number of photos already transfered to the previewImages array
		// Update the total size of the images already contained in the previewImages array
		if (previewImages.length > 0) {
			// previewImageSize = previewImages.length;
			for (var i = 0; i < previewImages.length; i++) {
				totalImgSize = totalImgSize + previewImages[i].file.size;
			}
		}

		/*
			The files data gotten is saved into the "previewImages" array that is made global
			because the files is saved by default into a fileList which is only a read-only variable.
			Hence, resaving the data into an array will give the flexibility to manipulate the files collected.
		*/
		for (var i = 0;  i < photos.length; i++) {
			previewImageSize = previewImages.length;
			// Check for total number of files that is to be uploaded
			if ((1 + previewImageSize) <= maxFilesUpload) {
				var fileName = photos[i].name;
	  		// Get the extension of the file uploaded
	    	var ext = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
	    	// check if the file extension is an allowed image file before transfering
	    	// to the working array 
	    	// Also images that are too big can be eliminated from here
		    if (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg") {
	  			var totalImgSize = totalImgSize + photos[i].size;
	  			if (totalImgSize <= serverTotalUploadableSize) {
	  				previewImages.push({
							"name" : photos[i].name,
							"url" : URL.createObjectURL(photos[i]),
							"file" : photos[i],
						});
	  			}	else {
				  	result1 = "Maximum file size is already uploaded.";
		  			break;
	  			}		
		    } else {
		    	noneImgFile++;
			  	result2 = noneImgFile+" none image file(s) were not uploaded.";
		    }
			} else {
		  	result3 = "Maximum of "+maxFilesUpload+" files are already uploaded.";
		  	break;
			}
		}
		// console.log(previewImages);

		// Validate the files uploaded
		var validationResult = validateMultipleImageUpload(previewImages);
		// console.log(validationResult);

		if (validationResult.errorType === true) {
			// Check if errors exists after validation
			$("#imgErrorReport").empty();
			if (result1 !== "") {
				$("#imgErrorReport").append("<p>"+result1+"</p>");
			}
			if (result2 !== "") {
				$("#imgErrorReport").append("<p>"+result2+"</p>");
			}
			if (result3 !== "") {
				$("#imgErrorReport").append("<p>"+result3+"</p>");
			}
		}

		$('#submitPhotos').css('display', 'inline-block');
		$('#cancelUploadImgs').css('display', 'inline-block');
	});

	$('#submitPhotos').on('click', function(event) {
		event.preventDefault();

		// Check if the preview image array is empty
		if (previewImages.length < 1) {
			$("#imgErrorReport").empty();
			$("#imgErrorReport").append("Select images to upload.");			
		} else {
			// Get the captions
			// var captionInput = $('.captionText');
			var captionInput = document.getElementsByClassName('captionText');
			var rotationInfo = document.getElementsByClassName('rotationInfo');
			// Create form data to append photos and captions
			var formData = new FormData();
			for (var i = 0;  i < previewImages.length; i++) {
				formData.append(i, previewImages[i].file);
				// formData.append("photos[]", previewImages[i].file);
				formData.append("captionText[]", captionInput[i].value);
				formData.append("rotationInfo[]", rotationInfo[i].value);
			}
			// After appending the images to save, empty the array for saving tempoary images
			previewImages.length = 0;

			$.ajax({
				url: '../PHP-JSON/multipleImageUpload_JSON.php',
				dataType: 'json',
				type: 'post',
				data: formData,
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
								$('#submitPhotos').attr('disabled', true);
								$('#cancelUploadImgs').attr('disabled', true);
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
					if (response.success === true) {
						// Get the id of the last child element in the photo gallery
						/* var children = $('#CusPhotoGallery')[0].children;
						var lastChildId = children[children.length - 2].id;
						var idNum = lastChildId.substr(14); */

						// Or we can do this
						// If you use this, no need of incrementing by 1 since it starts from the new id
						var idNum2 = document.getElementById('CusPhotoGallery').lastElementChild.value;

						// var imgNum = 0;
						// Append the images to the photo gallery
						$.each(response.result, function( i, imgPath ) {
							var imgPath = "../images/"+imgPath;
							// Ensure variables for addition are converted to number
							var imgNum = parseInt(idNum2) + parseInt(i);
							parseInt(imgNum);
							var customerId = response.customerId;
							var photo_caption = response.photo_caption[i];
							var photoId = response.photoId[i];
						  $('#imageCounter').before("<div id='displayPicture"+imgNum+"' class='displayPicture'><div name='cus-ad-image' id='cus-ad-image"+imgNum+"' class='cus-ad-image' style='background: url("+imgPath+"); background-repeat: no-repeat; background-size: cover; background-position: center;' imgurl='"+imgPath+"' onclick='galleryImg(this);' ></div> <div><p id='imageCaption"+imgNum+"' class='imageCaption'>"+photo_caption+"</p><input type='hidden' name='customerId' class='customerId' value='"+customerId+"' /> <input type='hidden' name='imageId' id='imageId"+imgNum+"' value='"+photoId+"' /> </div> </div>");
							$('#imageCounter').attr('value', imgNum+1);
						});												
					}

					// Check for errors to be displayed
					if (response.failedUpload === "image not saved") {
						$.each(response.resultError, function( i, error ) {
							$('#imgErrorReport').append("<p>"+error+"</p>");
						});
					}
					// Clear the prview div and hide it
					$('#previewDiv').empty();
					$('#previewDiv').css('display', 'none');
					// Enable the submit and cancel button again after picture upload and hide it.
					$('#submitPhotos').attr('disabled', false);
					$('#cancelUploadImgs').attr('disabled', false);
					$('#submitPhotos').css('display', 'none');
					$('#cancelUploadImgs').css('display', 'none');
					// Hide the progress bar
					$('#imageProgressBar').css('display', 'none');
				}

			});	
		}
	});

	// Cancel button
	$('#cancelUploadImgs').on('click', function(event) {
		event.preventDefault();
		// clear the temporary image object array
		previewImages.length = 0;
		// clear the preview div
		$('#previewDiv').empty();
		$('#previewDiv').css('display', 'none');
		// Clear the error div
		$('#imgErrorReport').empty();
		// $('#imgErrorReport').css('display', 'none');
		// Hide the submit and cancel button
		$('#submitPhotos').css('display', 'none');
		$('#cancelUploadImgs').css('display', 'none');		
	});

	function validateMultipleImageUpload(photos) {
		var result;
		// Get the div container to append the images for preview
		var divBox = document.getElementById("previewDiv");
		divBox.style.display = "flex";
		divBox.innerHTML = "";
		// This counter is used for the for for loop i scope is not working within the if loops
		var counter = 0;

		// Remove the none image file from the preview images array
  	// Run a loop through the uploaded file to remove the none image file
  	/* This loop is useful for an instance when a none image is uploaded 
			 after an image is contained in the array. The key will be different,
			 thus a loop over the files is necessary.
  	*/  			
		for (var i = 0; i < photos.length; i++) {
    	var reader = new FileReader();
      reader.onload = function(e) {
        attachImagePreviews(counter, photos[counter].url, divBox);
      	counter++;
      }
      reader.readAsDataURL(photos[i].file);

      result = {
	  		errorType: true,
		  	error: "Images accepted!!!"
	  	};
		}

		return result;
	}	 

	function attachImagePreviews(i, imgPath, divBox) {
		if (imgPath === "emptyImageIcon") {
			imgPath = "../images/emptyImageIcon.png";
		}

		if (typeof i === 'string')	{
			var key = "";
		} else if (typeof i === 'number') {
			var key = "_"+i;
		}

		// Create div for image canvas
		var imgCanvas = document.createElement("div");
		imgCanvas.setAttribute("id", "imgCanvas"+key);
		imgCanvas.setAttribute("style", "background: url("+imgPath+"); background-repeat: no-repeat; background-size: cover; background-position: center;");
		imgCanvas.setAttribute("class", "imgCanvasStyle");

		// Create input for caption
		var captionInput = document.createElement("input");
		captionInput.setAttribute("type", "text");
		captionInput.setAttribute("name", "caption");
		captionInput.setAttribute("id", "caption"+key);
		captionInput.setAttribute("class", "captionText");
		captionInput.setAttribute("placeholder", "Enter caption");
		captionInput.setAttribute("style", "width: 140px;");

		// Create div for rotate button
		var rotationBtn = document.createElement("div");
		rotationBtn.setAttribute("id", "rotateBtn"+key);
		rotationBtn.setAttribute("class", "rotateBtn");
		rotationBtn.setAttribute("onClick", "rotatePrevImg(this.id)");
		rotationBtn.innerHTML = "<i class='fas fa-redo'></i>";

		// Create div for delete button
		var delImgBtn = document.createElement("div");
		delImgBtn.setAttribute("id", "delImgBtn"+key);
		delImgBtn.setAttribute("class", "delPreviewImg");
		delImgBtn.setAttribute("onclick", "delPreviewImg(this.id)");
		delImgBtn.innerHTML = "<i class='fas fa-trash'></i>";

		// Create hidden input for rotate icon
		var rotationInfo = document.createElement("input");
		rotationInfo.setAttribute("type", "hidden");
		rotationInfo.setAttribute("name", "rotationInfo");
		rotationInfo.setAttribute("class", "rotationInfo");
		rotationInfo.setAttribute("id", "rotationInfo"+key);
		rotationInfo.setAttribute("value", "0");

		// Create div to hold image preview, caption, rotate btn and delete btn
		var imgCaptionDiv = document.createElement("div");
		imgCaptionDiv.setAttribute("id", "imgCaptionDiv"+key);
		imgCaptionDiv.setAttribute("class", "imgCaptionDivCSS");
		imgCaptionDiv.setAttribute("style", "display: inline-block;");

		// Append image div and caption to div
		imgCaptionDiv.appendChild(imgCanvas);
		imgCaptionDiv.appendChild(captionInput);
		imgCaptionDiv.appendChild(rotationBtn);
		imgCaptionDiv.appendChild(delImgBtn);
		imgCaptionDiv.appendChild(rotationInfo);

		// Append image to div
		divBox.appendChild(imgCaptionDiv);		
	}
});

/* This has to be defined here so it will be more global
   The onclick event to trigger this is embeded in the HTML tag made with the 
   javascript. This approcach is used to avoid the multiple initializing of 
   the data withiin the "reader" function */
function delPreviewImg(imgId) {
	var key = imgId.slice(10);
	// Delete the selected image from the array
	/* The id gotten is used without increment when using the splice() fxn
	because the splice() fxn begins count from 1 but the id of the images to 
	delete begins count from 0, and the implementation of the splice() has to 
	be n-1 for the nth term to delete */
	previewImages.splice(key,1);
	// Remove the image from the HTML window
	$("#imgCaptionDiv_"+key).remove();
}

// Function to respond to the rotation of a previewed image
function rotatePrevImg(rotateId) {
	var num = rotateId.substr(10);

	var img = document.getElementById('imgCanvas_'+num);
  var currentrotate = img.style.transform;
  var newrotate = '';
  var rotatevalue = 0;

  switch(currentrotate){
    case '':
      newrotate = 'rotate(90deg)';
      rotatevalue = 90;
      break;
    case 'rotate(90deg)':
      newrotate = 'rotate(180deg)';
      rotatevalue = 180;
      break;
    case 'rotate(180deg)':
      newrotate = 'rotate(270deg)';
      rotatevalue = 270;
      break;
    case 'rotate(270deg)':
      newrotate = '';
      rotatevalue = 0;
      break;
  }

  img.style.transform = newrotate;
  document.getElementById("rotationInfo_"+num).value = rotatevalue;
}

function isPortrait(img) {
  var w = img.naturalWidth || img.width,
      h = img.naturalHeight || img.height;
  return (h > w);
}


