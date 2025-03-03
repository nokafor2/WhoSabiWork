function toHomePage(customerId) {
	window.location.href = '/Public/customer/customerHomePage.php?id='+customerId;
}

function populateCheckboxesOfChoice(choice="") {
	// This function will load the jquery behaviors for determining which checkboxes for the cars, buses, trucks gets displayed
	$("document").ready(function() {
		
		// Get the vehicle services for the cars
		$.ajax ({
			url: '../PHP-JSON/popVehiclesServicesParts_JSON.php', 
			dataType: 'json',
			type: 'POST',
			
			success: function(data) {
				// console.log(data);
				if (choice === "Cars") {
					populateCheckboxes(data.carBrands, "cars_specialization_form", "car_brands[]");
				} else if (choice === "Buses") {
					populateCheckboxes(data.busBrands, "cars_specialization_form", "bus_brands[]");
				} else {
					populateCheckboxes(data.truckBrands, "cars_specialization_form", "truck_brands[]");
				}
				/* populateCheckboxes(data.techServices, "technicalServiceDiv", "technical_services[]");
				populateCheckboxes(data.spareParts, "sparePartDiv", "spare_parts[]"); */
			}
		});
		
	});
}

// This funciton is not fully completed because another approach using PHP was preferred 
function populateCheckboxes(contents, divNameId="", arrayName="") {
	// Get the element id of the div to attach the checkboxes
	var divName = document.getElementById(divNameId);
	var submitbutton = document.getElementById("submit_car_brands");
		
	divName.innerHTML = "";
	for (var content in contents) {
		// Create a label tag
		var labelOption = document.createElement("label");
		// Create an input tag
		var inputOption = document.createElement("input");
		// Set the type of the input tag
		inputOption.setAttribute("type", "checkbox");
		// Set the value of the input tag
		inputOption.setAttribute("value", content);
		// set the name of the input tag
		inputOption.name = arrayName;
		// Attach the input tag into the label tag
		labelOption.appendChild(inputOption);
		// Create a text node for the label tag
		var labelText = document.createTextNode(content);
		// Attach the label text in the label tag
		labelOption.appendChild(labelText);
		// Append the label tag after the last element in the div for the checkboxes
		divName.appendChild(labelOption);
		// divName.insertBefore(labelOption, submitbutton);
	}
	var submitButton = document.createElement("input");
	submitButton.setAttribute("type", "submit");
	submitButton.setAttribute("value", "Submit");
	submitButton.name = "submit_car_brands[]";
	divName.appendChild(submitButton);
}

// This function finds out when the user has reached the bottom of the page
/* window.addEventListener('scroll', () => {
	// Get the amount of scrollabe space within a page
	// The scrollable height of the entire document - the height of the window
	const scrollable = document.documentElement.scrollHeight - window.innerHeight;
	// This will get the number of pixels scrolled by the user at an instant
	const scrolled = window.scrollY;
	
	if (Math.ceil(scrolled) === scrollable) {
		alert("You've reached the bottom!");
	}
}); */

/* ********************************************************** */
// These scripts controls the behaviour that displays a larger image when it is clicked

// This controls the behaviour when the button is clicked to close the modal
$(document).on('click', '.close-btn', function() {
	$('.bg-modal').fadeOut("slow");
	// document.querySelector('.bg-modal').style.display = 'none';
	
	// Close the caption buttons and div after the modal is closed.
	resetCaptionBtnAndDiv();
});

// This closes the photo upload modal
$(document).on('click', '.closePhotoUploadModal', function() {
	$('.photoUploadModal').fadeOut("slow");
		
});

/* // These values will be gotten when any image is selected. These properties will be be used in other functions
var photoIdVal;
var customerIdVal;
var imageDivId;
var idNumber;
// This controls the response of when an image is clicked to project it on a modal
var body = document.getElementsByTagName("body")[0];
body.addEventListener('click', function(e){
	var item = e.target;
	if (item.tagName == "IMG" && item.name == "cus-ad-image") {
		
	}
}); */


/* var body = document.getElementsByTagName("body")[0];
body.addEventListener('click', function(e){
	var item = e.target;
	if (item.tagName == "DIV" && item.className == "cus-ad-image") {
		
	}
}); */



// These values will be gotten when any image is selected. These properties will be be used in other functions
var photoIdVal;
var customerIdVal;
var imageDivId;
var idNumber;
// Define image orientation to get
var imgOreintation;
function galleryImg(item) {
	// Set the height of the modal to fit the screen of the window
	var winHeight = $(window).height();
	winHeight = winHeight + 100;
	$(".bg-modal").css("height", winHeight);

	// Get image id
	imageId = item.id;		
	// Extract the number in the id to use for the caption id
	idNumber = imageId.substring(12);
	captionId = "imageCaption"+idNumber;
	imageDivId = "displayPicture"+idNumber;
	// Get image url, you can use this function created it works, but
	// saving the url in the div tag as an attribute and getting it is efficient
	// var img_path = extractImgUrl($("#"+imageId)[0].style.backgroundImage);
	var img_path = $("#"+imageId).attr('imgurl');
	// use the caption id to get the caption of the image from the hidden properties of the photo in the gallery
	var caption = $("#"+captionId).text();
	
	// Get the photoId of the customer and the customer id
	// customerId = "customerId"+idNumber;
	photoId = "imageId"+idNumber;
	photoIdVal = $('#'+photoId).attr('value');
	customerIdVal = $('.customerId').attr('value');
	
	// Use the image path to set it on the new enlarged div
	var imageBox = document.getElementById("enlargedAdImg");		
	var image = document.getElementById("enlargedAdPix");
	image.src = img_path;
	
	// Determine the image orientation type to show when enlarged
	getImgOrientation(
		img_path,
		(width, height) => {
			if (width > height) {
				// orientation is landscape
				imageBox.style.display = "grid";
				imageBox.style.alignItems = "center";
				imageBox.style.justifyContent = "unset";
				image.style.width = "100%";
				image.style.height = "unset";
			} else {
				// orientation is portrait
				imageBox.style.display = "flex";
				imageBox.style.justifyContent = "center";
				imageBox.style.alignItems = "unset";
				image.style.height = "100%";
				image.style.width = "unset";
			}
		}
	);
	
	// set the image caption in the modal created.
	$("#imgCaptionModal").text(caption);
	
	// show the div with the enlarged image when the image is clicked
	$('.bg-modal').fadeIn("slow");
	document.querySelector('.bg-modal').style.display = 'flex';
	
	// This checks if the photo selected is the ad photo and 
	// identifies it as cover photo and make the select ad photo
	// not selectable
	$.ajax ({
		url: '../PHP-JSON/checkAdPhoto_JSON.php', 
		dataType: 'json',
		type: 'POST',
		data: {imageId: photoIdVal, customerId: customerIdVal},
		
		success: function(data) {
			if (data.result == "ad photo") {
				// Hide the ad photo button
				$('#setAdPhoto').css("display", "none");
				$('#notifyMessage').css("display", "block");
				$('#notifyMessage').text("");
				$('#notifyMessage').text("COVER PHOTO");
			} else {
				// Display the ad photo button
				$('#setAdPhoto').css("display", "block");
				$('#notifyMessage').css("display", "none");
			}
		}
	});

	// Get the number of likes for the photograph
	$.ajax ({
		url: '../PHP-JSON/getNumberOfLikes_JSON.php', 
		dataType: 'json',
		type: 'POST',
		data: {photoId: photoIdVal, customerId: customerIdVal},

		success: function(data) {
			// console.log(data);
			if (data.result) {
				$("#adLike").html("Likes "+data.result.numLikes);
				if (data.result.photoLiked === 'yes') {
					$("#adLike").css('color', '#A51300');
				} else {
					$("#adLike").css('color', '#040D14');
				}
			}
		}
	});

	// Get all the comments and replies of the image selected
	$.ajax ({
		url: '../PHP-JSON/photoCommentsAndReplies_JSON.php', 
		dataType: 'json',
		type: 'POST',
		data: {photoId: photoIdVal, customerId: customerIdVal},
		
		success: function(data) {
			// console.log(data);
			// append the total number of comments
			$('#totalComments').html("");
			if (data.totalComments) {
				//$('#totalComments').append(data.totalComments+" comment(s)");
				$('#totalComments').html(data.totalComments+" comment(s)");
			} else {
				// $('#totalComments').append("No comment");
				$('#totalComments').html("No comment");
			}
			$('#photoComments').html("");
			// append the comment to the top of the div as the most recent comment on the customer
			var i = 0;
			for (var obj in data.comments) {
				$('#photoComments').append("<div id='comment"+photoIdVal+"_"+i+"' class='comment'><div id='commentBox"+photoIdVal+"_"+i+"' class='commentBox'><div id='authorDate"+photoIdVal+"_"+i+"' class='authorDate'><div id='author"+photoIdVal+"_"+i+"' class='author' >"+data.comments[obj].author+"</div> <div id='meta-info"+photoIdVal+"_"+i+"' class='meta-info' >"+data.comments[obj].dateCreated+"</div> </div> <div id='commentBody"+photoIdVal+"_"+i+"' class='commentBody' commentId='"+data.comments[obj].commentId+"' >"+data.comments[obj].comment+"</div></div><button class='replyBtn btnStyle3' id='replyBtn"+photoIdVal+"_"+i+"' onclick='reply2(this)'>Reply Comment</button></div>");
				
				for (var val in data.replies) {
					if (data.replies[val].commentId == data.comments[obj].commentId) {
						var htmlContent = "<div id='replyContainer"+photoIdVal+"_"+i+"' class='replycontainer'><div id='replyComment"+photoIdVal+"_"+i+"' class='replyComment'><div id='authorDate"+photoIdVal+"_"+i+"' class='authorDate'><div id='replyAuthor"+photoIdVal+"_"+i+"' class='author'>"+data.replies[val].author+"</div><div id='meta-info"+photoIdVal+"_"+i+"' class='meta-info'>"+data.replies[val].dateCreated+"</div></div><div id='commentBody"+photoIdVal+"_"+i+"' class='commentBody' replyId='"+data.replies[val].replyId+"'>"+data.replies[val].reply+"</div></div></div>";
						$(htmlContent).insertAfter($("#replyBtn"+photoIdVal+"_"+i));
					}
				}
				
				i++
			}
		}
	});
}

// This function is used to get the url of an image from a div with
// css style of "background: url()". The function will extract the image
// path from the url('...') returned
function extractImgUrl(stringUrl) {
	// Check if it is double quote used
	var quote1 = stringUrl.indexOf('"');
	if (quote1 > 0) {
		var stringCut = stringUrl.substr(quote1+1);
		var quote2 = stringCut.indexOf('"');
		var imgUrl = stringUrl.substr(quote1+1, quote2);
	} else {
		// single quote is used
		var quote1 = stringUrl.indexOf("'");
		var stringCut = stringUrl.substr(quote1+1);
		var quote2 = stringCut.indexOf("'");
		var imgUrl = stringUrl.substr(quote1+1, quote2);
	}
	return imgUrl;
}

/* This function will generate a reply div textarea and 
button an inset it after the reply comment button */
function reply2(caller) {
	var id = caller.id;
	var numIndex = id.match(/\d+/);
	var uniqueId = id.substring(numIndex['index']);

	var newDivIdName = "replyBox"+uniqueId;
	var newDivId = document.getElementById(newDivIdName);
	if (!newDivId) {
		var htmlContent = '<div id="replyBox'+uniqueId+'" class="replyBox"><textarea id="replyTextarea'+uniqueId+'" class="replyTextarea" name="message_content" rows="1"></textarea><button id="submitReply'+uniqueId+'" class="submitReply btnStyle3" onclick="submitReply(this)">Reply</button><button id="cancelReply'+uniqueId+'" class="cancelReply btnStyle3" onclick="cancelReply(this)">Cancel</button></div>';		
		$(htmlContent).insertAfter($(caller));
	} else {
		$('#'+newDivIdName).css('display', 'block');
	}

	return true;
}

// Hide the reply box container
function cancelReply(caller) {
	var id = caller.id;
	// get the array index of the appearance of the first number
	var numIndex = id.match(/\d+/);
	var uniqueId = id.substring(numIndex['index']);
	
	$('#replyBox'+uniqueId).css('display', 'none');

	return true;
}

/* This function will submit the message of a reply */
function submitReply(obj) {
	var id = obj.id;
	var parentId = $('#'+id).parent().parent().attr("id");
	// var idIndex = parentId.substring(7);
	var numIndex = parentId.match(/\d+/);
	var idIndex = parentId.substring(numIndex['index']);
	var commentBody = "commentBody"+idIndex;
	var commentId = $('#'+commentBody).attr('commentId');
	// console.log("Comment Id is: "+commentId);
	// Get the input from the textarea
	var reply = $('#replyTextarea'+idIndex).val();
	// console.log("Reply is: "+reply);
	
	$.ajax({
		url: "../PHP-JSON/likeComment_JSON.php", 
		type: "POST",
		dataType: 'json',
		data: {commentId: commentId, reply: reply, customerId: customerIdVal, photoId: photoIdVal},
		success: function(data) {
			// console.log(data);			
			if (data.reply) {
				var htmlContent = "<div id='replyContainer' class='replycontainer'><div id='replyComment' class='replyComment'><div id='authorDate' class='authorDate'><div id='replyAuthor' class='author'>"+data.author+"</div><div id='meta-info' class='meta-info'>"+data.created+"</div></div><div id='commentBody' class='commentBody' replyId='"+data.replyId+"'>"+data.reply+"</div></div></div>";
				$(htmlContent).insertAfter($("#replyBtn"+idIndex));

				// clear the textarea
				$('#replyTextarea'+idIndex).val("");

				// Hide the reply div after displaying a message
				$('#replyBox'+idIndex).css('display', 'none');
			} else {
				if (data.errors && data.message) {
					// Display error message if reply was not saved.
					displayMessage('Error', data.message);
					// $('#feedback').append("<p>"+data.message+"</p>");
				} else {
					$('#feedback').text("");
					if (data.validate_errors.reply) {
						// Display error message
						displayMessage('Error', data.validate_errors.reply);
						// $('#feedback').append("<p>"+data.validate_errors.reply+"</p>");
					}
					if (data.validate_errors.reply_err_long) {
						// Display error message
						displayMessage('Error', data.validate_errors.reply_err_long);
						// $('#feedback').append("<p>"+data.validate_errors.reply_err_long+"</p>");
					}
					if (data.validate_errors.reply_comment_error) {
						// Display error message
						displayMessage('Error', data.validate_errors.reply_comment_error);
						// $('#feedback').append("<p>"+data.validate_errors.reply_comment_error+"</p>");
					}
				}					
			}
		}
	});	

	return true;
}

/* Save the like when the button is clicked */
function saveLike(id) {
	// console.log("Button clicked");
	
	$.ajax ({
		url: '../PHP-JSON/likeComment_JSON.php', 
		dataType: 'json',
		type: 'POST',
		data: {photoId: photoIdVal, customerId: customerIdVal, like: "likePhoto"},
		
		success: function(data) {
			// console.log(data);
			// Get the number of likes and update the webpage
			if (data.result === "liked") {					
				$(id).html("<span style='color: #A51300;'><i class='fas fa-thumbs-up'></i>Like ("+data.numLike+")</span>");
			}
		}
	});

	return true;	
}

// Controls the button for editing the photo caption
$(document).ready(function(){
	$('#editPhoto').click(function(){
		// Display the textarea for caption change
		$('#photoCaptionBox').fadeIn(2000).css('display', 'block');
		// Display the caption submit button
		$('#changeCaption').fadeIn(2000).css('display', 'block');
		// Display the caption insruction
		$('#captionInfo').fadeIn(2000).css('display', 'block');
	});
	
	// After the change caption button is clicked, the caption in the textarea that was entered would be gotten and saved in the database then the caption displaying would be changed.
	$('#changeCaption').click(function(){
		// Trim the contents of the textarea for any trainling whitespace using the trim function
		var caption = $.trim($("#photoCaptionBox").val());
		// var caption = document.getElementById("photoCaptionBox").value;
		// console.log("The textarea caption is: "+caption);
		
		// Run an ajax script to update the caption
		// dataType could either be text, or json
		$.ajax ({
			url: '../PHP-JSON/updatePhotoCaption_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {imageId: photoIdVal, customerId: customerIdVal, photo_Caption: caption},
			
			success: function(data) {
				// console.log(data);
				if (data.success === true) {
					// set the image caption in the modal created.
					$("#imgCaptionModal").text(caption).fadeIn(2000);
					// clear the text and hide the textarea when done
					$('#photoCaptionBox').fadeOut(2000);
					$('#photoCaptionBox').val("");
					// hide the change caption button
					$('#changeCaption').fadeOut(2000);
					// hide the caption info text
					$('#captionInfo').fadeOut(2000);
					// hide the div for outputing errors
					$('#captionResultDiv').fadeOut(2000);
				} else if (data.success === false) {
					$('#captionResultDiv').css("display", "block").fadeIn(2000);
					// Clear the text for photo caption
					$('#captionResultDiv').text("");
					
					// "_address_error", "_err_long"
					if (data.errors.photo_Caption) {
						$('#captionResultDiv').append('<p class="captionResult">'+data.errors.photo_Caption+'</p>');
					}
					if (data.errors.photo_Caption_err_long) {
						$('#captionResultDiv').append('<p class="captionResult">'+data.errors.photo_Caption_err_long+'</p>');
					}
					if (data.errors.photo_Caption_address_error) {
						$('#captionResultDiv').append('<p class="captionResult">'+data.errors.photo_Caption_address_error+'</p>');
					}
				}
			}
		});
	});
		
	// Set the ad image when this button is clicked.
	$('#setAdPhoto').click(function(){
		// Get the button id
		var setAdPhoto = $('#setAdPhoto');

		$.ajax ({
			url: '../PHP-JSON/setAdPhoto_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {imageId: photoIdVal, customerId: customerIdVal},
			
			success: function(data) {
				if (data.result == "image set") {
					// Remove the button to set image as cover photo
					// Inform the customer the image has been set.
					$('#setAdPhoto').css("display", "none");
					$('#notifyMessage').css("display", "block");
					$('#notifyMessage').text("");
					$('#notifyMessage').text("Image is now saved as cover photo");
					// Update the Label in the photo gallery
					$('#adStatus'+photoIdVal).text("");
					$('#adStatus'+photoIdVal).text("Cover Photo");
					// Remove the Label of Cover Photo from the previous photo
					if (data.oldAdPhotoId !== null) {
						var oldAdPhotoId = data.oldAdPhotoId;
						$('#adStatus'+oldAdPhotoId).text("");
					}				
				}				
			}
		});
	});

	// After the delete button is clicked, the photo id and customer id will be sent to the php script that will use it to delete the photo. Also, modal that displays the picture would be closed and the picture removed from the photo gallery.
	$('#deletePhoto').click(function(){
		
		// Confirm if the user wants to delete the image before deleting.
		if (confirm("Are you sure you want to delete the image?")) {
			// Run an ajax script to delete the caption
			// dataType could either be text, or json
			$.ajax ({
				url: '../PHP-JSON/deletePhoto_JSON.php', 
				dataType: 'json',
				type: 'POST',
				data: {imageId: photoIdVal, customerId: customerIdVal},
				
				success: function(data) {					
					if (data.success === true) {
						// Hide the modal and reset the contents in it
						$('.bg-modal').fadeOut("slow");
						// Remove the image from the photogallery.
						$('#'+imageDivId).remove();
						
						// Close the caption buttons and div after the modal is closed.
						resetCaptionBtnAndDiv();

						// Update the hidden input value
						var children = $('#CusPhotoGallery')[0].children;
						var lastChildId = children[children.length - 2].id;
						var idNum = lastChildId.substr(14);
						idNum++;
						$('#imageCounter').attr('value', idNum);
					} else {
						$('#captionResultDiv').css("display", "block").fadeIn(2000);
						// Clear the text for photo caption
						$('#captionResultDiv').text("");
						
						$('#captionResultDiv').append('<p class="captionResult">'+ data.errors +'</p>');
					}
				}
			});
		} else {
			// No action will be taken
			return false;
		}
		
	});
});

function resetCaptionBtnAndDiv() {
	// clear the text and hide the textarea when done
	$('#photoCaptionBox').fadeOut(2000);
	$('#photoCaptionBox').val("");
	// hide the change caption button
	$('#changeCaption').fadeOut(2000);
	// hide the caption info text
	$('#captionInfo').fadeOut(2000);
	// hide the div for outputing errors
	$('#captionResultDiv').fadeOut(2000);
}

// when the reply button is clicked, insert the reply dive after the reply button. This will ensure that there is only one reply div to deal with at a time.
function reply(caller) {
	var id = caller.id;
	
	$("#replyDiv").insertAfter($(caller));
	$("#replyDiv").show();
}

function addReply(caller) {
	// get the id
	var btnId = caller.id;
	// console.log("The div id is: "+btnId);
	var parentProp = $('#'+btnId).parent();
	// console.log(parentProp[0].id);
	parentId = parentProp[0].id;
	var grandProp = $('#'+parentId).parent();
	// console.log("Grandparent id is: "+grandProp[0].id);
	var grandPropId = grandProp[0].id;
	// var index = grandPropId.substring(7,8);
	var index = grandPropId.substring((grandPropId.length - 1), grandPropId.length);
	// console.log("The index is: "+index);
	// make the index
	var replyCommentId = "#replyComment"+index;
	// $(replyCommentId).show();
	
	// Get the text in the textarea
	var reply = $('#replyTextarea').val();
	// Get the customerId of the person replying
	var customerId = $('#submitReply').attr('customerId');
	// Get the comment id
	var commentdAttr = "commentid"+index;
	var commentBodyId = "commentBody"+index;
	var commentId = $('#'+commentBodyId).attr("commentid"+index);
	// console.log("The comment id is: "+commentId);
	var authorId = "author"+index;
	var userorcusidreplyto = $('#'+authorId).attr("userorcusidreplyto");
	var accountType = $('#'+authorId).attr("accounttype");
	
	// Get the comment, customerId and comment id and pass it through the ajax function to the PHP
	$.ajax({
		url: "../PHP-JSON/submitReply_JSON.php",
		type: "POST",
		dataType: 'json',
		data: {reply: reply, customerId: customerId, commentId: commentId, userOrCusIdReplyTo: userorcusidreplyto, accountType: accountType},
		success: function(data){
			// console.log(data);
			if (data.success) {
				// apend the div after the reply click button
				var replyContainerId = "#replyContainer"+index; 
				// find the last child element in the div
				var divIds = $(replyContainerId+" > div").map(function() {return this.id});
				if (divIds.length == 0) {
					// perform an append
					var subIndex = 0;
					
					$(replyContainerId).append("<div id='replyComment"+index+subIndex+"' class='replyComment'><div class='authorDate' id='replyAuthorDate"+index+subIndex+"'><div class='author' id='replyAuthor"+index+subIndex+"'>"+data.author+" replied: </div> <div class='meta-info' id='meta-info"+index+subIndex+"'>"+data.created+"</div></div> <div class='commentBody' id='replyCommentBody"+index+subIndex+"'>"+data.reply+"</div> </div>");
				} else {
					// perform a prepend
					// get the last element in the div
					var lastElement = divIds.get(-1);
					var subIndex = lastElement.substring(13,14);
					// increment the subIndex by 1
					subIndex++;
					// $(commentDivId).append
					$(replyContainerId).prepend("<div id='replyComment"+index+subIndex+"' class='replyComment'><div class='authorDate' id='replyAuthorDate"+index+subIndex+"'><div class='author' id='replyAuthor"+index+subIndex+"'>"+data.author+" replied: </div> <div class='meta-info' id='meta-info"+index+subIndex+"'>"+data.created+"</div></div> <div class='commentBody' id='replyCommentBody"+index+subIndex+"'>"+data.reply+"</div> </div>");
				}
				
				// make the div visible
				// clear the reply div textarea
				$('#replyTextarea').val("");
				// Hide the reply div containing the textarea
				$('#replyDiv').hide();
				// hide the reply button to trigger the reply div
				// $('#replyBtn'+index).hide();
			} else {
				if (data.errors && data.message) {
					// Display error message if reply was not saved.
					displayMessage('Error', data.message);
					// $('#feedback').append("<p>"+data.message+"</p>");
				} else {
					// $('#feedback').text("");
					if (data.validate_errors.reply) {
						// Display error message if comment was not saved.
						displayMessage('Error', data.validate_errors.reply);
						// $('#feedback').append("<p>"+data.validate_errors.reply+"</p>");
					}
					if (data.validate_errors.reply_err_long) {
						// Display error message if comment was not saved.
						displayMessage('Error', data.validate_errors.reply_err_long);
						// $('#feedback').append("<p>"+data.validate_errors.reply_err_long+"</p>");
					}
					if (data.validate_errors.reply_comment_error) {
						// Display error message if comment was not saved.
						displayMessage('Error', data.validate_errors.reply_comment_error);
						// $('#feedback').append("<p>"+data.validate_errors.reply_comment_error+"</p>");
					}
				}
			}
		}
	});
}

function saveReply(caller) {
	// get the id
	var btnId = caller.id;
	// console.log("The div id is: "+btnId);
	var parentProp = $('#'+btnId).parent();
	// console.log(parentProp[0].id);
	parentId = parentProp[0].id;
	var grandProp = $('#'+parentId).parent();
	// console.log("Grandparent id is: "+grandProp[0].id);
	var grandPropId = grandProp[0].id;
	// var index = grandPropId.substring(7,8);
	var index = grandPropId.substring((grandPropId.length - 1), grandPropId.length);
	// console.log("The index is: "+index);
	// make the index
	var replyCommentId = "#replyComment"+index;
	// $(replyCommentId).show();
	
	// Get the text in the textarea
	var reply = $('#replyTextarea2').val();
	// Get the customerId of the person replying
	var customerId = $('#submitReply2').attr('customerId');
	// get the j-idex value
	var idName = $('#replyDiv').parent()[0].id;
	var index2 = idName.substring((idName.length - 1), idName.length);
	var replyCommentBodyId = "commentBody2"+index;
	var replyId = $('#'+replyCommentBodyId).attr("replyid"+index2); // this
	// console.log("The comment id is: "+replyId);
	var authorId = "replyAuthor"+index+index2;
	var userorcusidreplyto = $('#'+authorId).attr("userorcusidreplyto"); // this
	// var accountType = $('#'+authorId).attr("accounttype"); // this
	var accountType = "customer";
	
	// Get the comment, customerId and comment id and pass it through the ajax function to the PHP
	$.ajax({
		url: "../PHP-JSON/submitReply_JSON.php",
		type: "POST",
		dataType: 'json',
		data: {reply: reply, customerId: customerId, replyId: replyId, userOrCusIdReplyTo: userorcusidreplyto, accountType: accountType},
		success: function(data){
			// console.log(data);
			if (data.success) {
				// apend the div after the reply click button
				var replyContainerId = "#replyContainer"+index; 
				// find the id of the last child element in the div
				var divIds = $(replyContainerId+" > div").map(function() {return this.id});
				if (divIds.length == 0) {
					// perform an append
					var subIndex = 0;
					
					$(replyContainerId).append("<div id='replyComment"+index+subIndex+"' class='replyComment'><div class='authorDate' id='replyAuthorDate"+index+subIndex+"'><div class='author' id='replyAuthor"+index+subIndex+"'>"+data.author+" replied: </div> <div class='meta-info' id='meta-info"+index+subIndex+"'>"+data.created+"</div></div> <div class='commentBody' id='replyCommentBody"+index+subIndex+"'>"+data.reply+"</div> </div>");
				} else {
					// perform a prepend
					// get the last element in the div
					var lastElement = divIds.get(-1);
					var subIndex = lastElement.substring(13,14);
					// increment the subIndex by 1
					subIndex++;
					// $(commentDivId).append
					$(replyContainerId).prepend("<div id='replyComment"+index+subIndex+"' class='replyComment'><div class='authorDate' id='replyAuthorDate"+index+subIndex+"'><div class='author' id='replyAuthor"+index+subIndex+"'>"+data.author+" replied: </div> <div class='meta-info' id='meta-info"+index+subIndex+"'>"+data.created+"</div></div> <div class='commentBody' id='replyCommentBody"+index+subIndex+"'>"+data.reply+"</div> </div>");
				}
				
				// make the div visible
				// clear the reply div textarea
				$('#replyTextarea2').val("");
				// Hide the reply div containing the textarea
				$('#replyDiv').hide();
				// hide the reply button to trigger the reply div
				// $('#replyBtn'+index).hide();
			} else {
				if (data.errors && data.message) {
					// Display error message if comment was not saved.
					displayMessage('Error', data.message);
					// $('#feedback2').append("<p>"+data.message+"</p>");
				} else {
					// $('#feedback2').text("");
					if (data.validate_errors.reply) {
						// Display error message
						displayMessage('Error', data.validate_errors.reply);
						// $('#feedback2').append("<p>"+data.validate_errors.reply+"</p>");
					}
					if (data.validate_errors.reply_err_long) {
						// Display error message
						displayMessage('Error', data.validate_errors.reply_err_long);
						// $('#feedback2').append("<p>"+data.validate_errors.reply_err_long+"</p>");
					}
					if (data.validate_errors.reply_comment_error) {
						// Display error message
						displayMessage('Error', data.validate_errors.reply_comment_error);
						// $('#feedback2').append("<p>"+data.validate_errors.reply_comment_error+"</p>");
					}
				}
			}
		}
	});
}

function getTowns(state, town) {
	// Get the state id
	var stateId = document.getElementById(state);
	var townId = document.getElementById(town);
	var stateVal = stateId.value;

	var towns = [];
	var phpURL = 'PHP-JSON/getTowns_JSON.php';
	var stateKey = {state: stateVal};
	$.ajax({
		type: 'POST',
		url: phpURL,
		dataType: 'json',
		data: stateKey,
		
		success: function(data) {
			// console.log(data['result']);
			if (data.success == true) {
				// This converts the object values to array values
				// const towns = Object.values(data.result);
				// This converts the object values to array values
				// This is a well supported method
				const towns = Object.keys(data.result).map(i => data.result[i]);
				// console.log(towns);
				// populate the multiple select menu of the town
				populateTown(towns, townId);
			}
		}
	});
}

// This function will populate the towns gotten in the array
// in the multiple select menu of town on the webpage
function populateTown(towns, selMenuTown) {
	// Hide the other town textarea if it is open
	$('#otherTown').css('display', 'none');
	// clear the values of the options before appending new enstries upon change
	selMenuTown.innerHTML = "";
	// sort the towns array alphabetically
	towns.sort();

	// Create the dummy select option
	var newOption = document.createElement("option");
	newOption.value = "";
	newOption.innerHTML = "Select";
	selMenuTown.options.add(newOption);
	
	// Use a loop to populate the options for the select menu
	for (var town in towns) {
		// create an 'option' tag object to be used to populate the select contents
		var newOption = document.createElement("option");
		// Use this to access the value of the array
		// replace the spaces with hyphen for the values of the option
		newOption.value = towns[town].replace(' ', '_');
		// Capitalize the first letter in the string
		towns[town] = towns[town].charAt(0).toUpperCase()+towns[town].slice(1);
		// Remove the underscores in the string
		towns[town] = towns[town].replace(/_/g, ' ');
		// Use this to access the label of the array
		newOption.innerHTML = towns[town].replace('_', ' ');
		selMenuTown.options.add(newOption);
	}

	// Create the other select option
	var newOption = document.createElement("option");
	newOption.value = "other";
	newOption.innerHTML = "Other";
	selMenuTown.options.add(newOption);
}

function showTownTextArea(selMenuTownId) {
	var townId = document.getElementById(selMenuTownId);
	town = townId.value;

	if (town === 'other') {
		$('#otherTown').css('display', 'block');
		$('.CollapsiblePanelContent').css('height', 'auto');
	} else {
		$('#otherTown').css('display', 'none');
	}
}


/* This functions will clear the input fields in the customer profile page */
$(document).ready(function(){
	$('#clear_first_name').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'firstName'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#firstNameInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_last_name').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'lastName'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#lastNameInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_gender').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'gender'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#genderInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_username').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'username'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#usernameInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_businessDescInput').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'businessDescription'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#businessDescInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_businessName').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'businessName'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#businessNameInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_email').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'email'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#emailInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_phoneNumber').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'phoneNumber'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#phoneNumberInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_addressLine1').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'addressLine1'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#addressLine1Input').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_addressLine2').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'addressLine2'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#addressLine2Input').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_addressLine3').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'addressLine3'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#addressLine3Input').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_state').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'state'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#stateInput').text(' ');
					$('#townInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_town').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'town'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#townInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_businessCategory').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'businessCategory'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#businessCategoryInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_vehicleCategory').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'vehicleCategory'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#vehicleCategoryInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_vehicleSpecialization').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'vehicleSpecialization'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#vehicleSpecializationInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_businessServices').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearProfileInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'businessServices'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#businessServicesInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					displayMessage("Error", "Unauthorized attempt to make this change.");
				}
			}
		});
	});
});


// Function to count the number of text in a comment box
document.getElementById("business_description").addEventListener("keyup", updateCount);

function updateCount() {
  var commentText = document.getElementById("business_description").value;
  var charCount = countCharacters(commentText);
  var wordCountBox = document.getElementById("wordCount");
  wordCountBox.value = charCount+"/250";
  if (charCount > 250) {
    wordCountBox.style.color = "white";
    wordCountBox.style.backgroundColor = "red";
  } else {
    wordCountBox.style.color = "black";
    wordCountBox.style.backgroundColor = "white";
  }
}

/*
var deleteScheduleBtns = document.getElementsByClassName("deleteSchedule");

for (var i = 0; i < deleteScheduleBtns.length; i++) {
	deleteScheduleBtns[i].addEventListener("click", function(e) {
		console.log("The button id is: "+e.target.id);
		var delBtnId = e.target.id;
		var delBtnObj = document.getElementById(e.target.id);
		console.log("The cus avail id is: "+delBtnObj.getAttribute("data-cusavailid"));
	});
} */

// This function deletes a customer created schedule
function deleteAvailSch(id) {
	var delBtnObj = document.getElementById(id);
	var cusAvailId = delBtnObj.getAttribute("data-cusavailid");
	var weekday = delBtnObj.parentElement.parentElement.children[2].innerHTML;
	var date = delBtnObj.parentElement.parentElement.children[1].innerHTML;
	// remove the semicolon from the weekday string
	weekday = weekday.replace(/:/g, '');
	// get the specific day of the week
	var specificDay = weekday.substring(0, weekday.indexOf(" ")).toLowerCase();
	// Define the select tag
	var selectDay = document.getElementById('set_days');

	$.ajax ({
		url: '../PHP-JSON/deleteAvailability_JSON.php', 
		dataType: 'json',
		type: 'POST',
		data: {cusAvailId: cusAvailId},
		
		success: function(data) {
			// console.log(data);
			if (data.success) {
				if (data.result === 'deleted') {
					/* if (confirm("Do you want to delete the schedule created?")) {
						delBtnObj.parentElement.parentElement.innerHTML = "";
					} */					
					// Delete the select hours div first
					$('#'+id).parent().parent().next().remove();
					// Delete the schedule appointment div
					$('#'+id).parent().parent().remove();

					// append back the option deleted in the select menu
					var optionObj = document.createElement('option');
					// Set the id for the option tag
					optionObj.setAttribute("id", date);
					// Set the value for the option tag
					optionObj.setAttribute("value", specificDay);
					optionObj.innerHTML = initialCaps(weekday);
					selectDay.appendChild(optionObj);

					// Sort the select options
					sortedOptions = sortOptions();

					selectDay.innerHTML = "";
					// append the sorted options to the parent node
					for (var i = 0; i < sortedOptions.length; i++) {
						selectDay.appendChild(sortedOptions[i]);
					}
				} else {
					alert("Unauthorized attempt to make this change.");
				}
			}			
		}
	});
}



/* This functions will update the edited input fields in the customer profile page */
$(document).ready(function(){
	// Update first name
	$('#submit_first_name').click(function(event){
		event.preventDefault();

		// Get the first name entered
		var firstName = $('#edit_first_name').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('first_name_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('first_name_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the first name input
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'firstName', first_name: firstName, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					// Update menu name
					$('.accountInfoDetails')[1].innerHTML = data.fullName;
					$('#edit_first_name').val("");
					$('#firstNameInput').empty();
					$('#firstNamePanelContent').slideUp();
					$('#firstNameInput').append(initialCaps(firstName));
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update last name
	$('#submit_last_name').click(function(event){
		event.preventDefault();

		// Get the first name entered
		var lastName = $('#edit_last_name').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('last_name_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('last_name_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the first name input
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'lastName', last_name: lastName, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					// Update menu name
					$('.accountInfoDetails')[1].innerHTML = data.fullName;
					$('#edit_last_name').val("");
					$('#lastNameInput').empty();
					$('#lastNamePanelContent').slideUp();
					$('#lastNameInput').append(initialCaps(lastName));
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update gender
	$('#submit_gender').click(function(event){
		event.preventDefault();

		// Get the gender
		var genders = document.gender_form.gender;
		for (var i=0; i < genders.length; i++) {
			if (genders[i].checked) {
				gender = genders[i].value;
				break;
			}
		}
		if (typeof gender === "undefined" || gender === null) {
			gender = "";
		}
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('gender_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('gender_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the gender input
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'gender', gender: gender, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#genderInput').empty();
					$('#genderPanelContent').slideUp();
					$('#genderInput').append(initialCaps(gender));
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update username
	$('#submit_username').click(function(event){
		event.preventDefault();

		// Get the username
		var username = $('#edit_username').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('username_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('username_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the username input
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'username', username: username, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#edit_username').val("");
					$('#usernameInput').empty();
					$('#usernamePanelContent').slideUp();
					$('#usernameInput').append(username);
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update password
	$('#submit_password').click(function(event){
		event.preventDefault();

		// Get the password
		var password = $('#edit_password').val();
		var confirm_password = $('#confirm_password').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('password_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('password_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the password input
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'password', password: password, confirm_password: confirm_password, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#edit_password').val("");
					$('#confirm_password').val("");
					$('#passwordPanelContent').slideUp();
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update business name
	$('#submit_business_name').click(function(event){
		event.preventDefault();

		// Get the business name
		var business_name = $('#edit_business_name').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('business_name_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('business_name_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the business name
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'business_name', business_name: business_name, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#edit_business_name').val("");
					$('#businessNameInput').empty();
					$('#busNamePanelContent').slideUp();
					$('#businessNameInput').append(initialCaps(business_name));
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update business description
	$('#submit_business_description').click(function(event){
		event.preventDefault();

		// Get the business description
		var business_description = $('#business_description').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('business_description_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('business_description_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the business description
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'business_description', business_description: business_description, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#business_description').val("");
					$('#businessDescInput').empty();
					$('#busDescPanelContent').slideUp();
					$('#businessDescInput').append(initialCaps(business_description));
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update business email
	$('#submit_business_email').click(function(event){
		event.preventDefault();

		// Get the business email
		var edit_business_email = $('#edit_business_email').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('business_email_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('business_email_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the business email
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'business_email', business_email: edit_business_email, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#edit_business_email').val("");
					$('#emailInput').empty();
					$('#emailInput').append(edit_business_email);
					$('#emailPanelContent').slideUp();
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update business phone number
	$('#submit_phone_number').click(function(event){
		event.preventDefault();

		// Get the phone number
		var edit_phone_number = $('#edit_phone_number').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('phone_number_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('phone_number_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the business phone number
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'phone_number', phone_number: edit_phone_number, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('.accountInfoDetails')[2].innerHTML = edit_phone_number;
					$('#validateNumber').css('display', 'inline');
					$('#edit_phone_number').val("");
					$('#phoneNumPanelContent').slideUp();
					$('#phoneNumberInput').empty();
					$('#phoneNumberInput').append(edit_phone_number);
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Verify the phone number
	$('#verifyNumber').click(function(event){
		event.preventDefault();

		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('phone_number_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('phone_number_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Verify the business phone number
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'verify_number', [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},			
			
      beforeSend: function() {
        $(".loader").css("display", "flex");
      },

      complete: function() {
        $(".loader").fadeOut();
      },

			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					// $('.CollapsiblePanelContent').css('height', 'auto');
					$('#phoneNumPanelContent').css('height', 'auto');					
					$('#enterTokenDiv').slideDown();
					displayMessage('Success', data.tokenSent);
				} else if (data.tokenNotSent) {
					displayMessage('Error', data.tokenNotSent);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Submit the sms token
	$('#submit_smsToken').click(function(event){
		event.preventDefault();

		var smsToken = $('#smsToken').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('phone_number_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('phone_number_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Submit the sms token
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'submit_smsToken', smsToken: smsToken, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#validateNumber').css('display', 'none');
					$('#enterTokenDiv').slideUp();
					$('#verifyPhoneNumDiv').slideUp();
					$('#phoneNumPanelContent').slideUp();
					$('.CollapsiblePanelContent').css('height', 'auto');
					displayMessage('Success', data.phoneNumValidated);
				} else if (data.phoneNumNotValidated) {
					$('#enterTokenDiv').slideUp();
					displayMessage('Error', data.phoneNumNotValidated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update address line 1
	$('#submit_address_line_1').click(function(event){
		event.preventDefault();

		// Get the address line 1
		var edit_address_line_1 = $('#edit_address_line_1').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('address_line_1_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('address_line_1_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the address line 1
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'address_line_1', address_line_1: edit_address_line_1, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#edit_address_line_1').val("");
					$('#addressLine1PanelContent').slideUp();
					$('#addressLine1Input').empty();
					$('#addressLine1Input').append(initialCaps(edit_address_line_1));
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update address line 2
	$('#submit_address_line_2').click(function(event){
		event.preventDefault();

		// Get the address line 2
		var edit_address_line_2 = $('#edit_address_line_2').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('address_line_2_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('address_line_2_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the address line 2
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'address_line_2', address_line_2: edit_address_line_2, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#edit_address_line_2').val("");
					$('#addressLine2PanelContent').slideUp();
					$('#addressLine2Input').empty();
					$('#addressLine2Input').append(initialCaps(edit_address_line_2));
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update address line 3
	$('#submit_address_line_3').click(function(event){
		event.preventDefault();

		// Get the business email
		var edit_address_line_3 = $('#edit_address_line_3').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('address_line_3_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('address_line_3_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the address line 3
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'address_line_3', address_line_3: edit_address_line_3, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#edit_address_line_3').val("");
					$('#addressLine3PanelContent').slideUp();
					$('#addressLine3Input').empty();
					$('#addressLine3Input').append(initialCaps(edit_address_line_3));
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update state
	$('#submit_state').click(function(event){
		event.preventDefault();

		// Get the business email
		var edit_state = $('#edit_state').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('state_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('state_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the address line 3
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'state', state: edit_state, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					// Set the select menu back to the default select "" is the option value
					$('#edit_state').val("").prop('selected', true);
					$('#statePanelContent').slideUp();
					$('#stateInput').empty();
					$('#stateInput').append(initialCaps(edit_state));
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update town
	$('#submit_town').click(function(event){
		event.preventDefault();

		// Get the town selected from the select menu
		var town = $('#town').val();
		// Get the town inputed if other is selected in the select menu is selected
		var edit_town = $('#edit_town').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('town_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('town_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the town
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'town', town: town, edit_town: edit_town, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#town').val("").prop('selected', true);
					$('#edit_town').val("");
					$('#townContent').slideUp();
					$('#townInput').empty();
					$('#townInput').append(initialCaps(data.updatedTown));
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update vehicle category
	$('#submit_vehicle_category').click(function(event){
		event.preventDefault();

		// Get the selected vehicle
		var selectedVehicle = getSelectedVehicle();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('vehicle_category_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('vehicle_category_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the town
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'vehicle_category', vehicle_category: selectedVehicle, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					unSelectVehicle();
					$('#vehCategoryPanelContent').slideUp();
					$('#vehicleCategoryInput').empty();
					$('#vehicleCategoryInput').append(initialCaps(selectedVehicle));
					// Clear the vehicle brands if changed
					$('#vehicleSpecializationInput').empty();
					$('#vehicleSpecializationInput').html("&nbsp");
					// Set the new vehicle brands
					$('#displayVehCheckBoxes').empty();
					$('#displayVehCheckBoxes').html(data.checkboxes);
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update vehicle brands
	$('#submit_vehicle_brands').click(function(event){
		event.preventDefault();

		// Get the selected vehicle, trim out spaces and convert the text to lowercase
		var selectedVehicle = $.trim($('#vehicleCategoryInput').text()).toLowerCase();		
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('cars_specialization_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('cars_specialization_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;
		if (selectedVehicle === 'cars') {
			// Get selected car brands
			var selectedCars = getSelectedCarBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {inputField: 'vehicle_brands', car_brands: selectedCars, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime};
		} else if (selectedVehicle === 'buses') {
			// Get selected car brands
			var selectedBuses =  getSelectedBusBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {inputField: 'vehicle_brands', bus_brands: selectedBuses, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime};
		} else if (selectedVehicle === 'trucks') {
			// Get selected car brands
			var selectedTrucks =  getSelectedTruckBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {inputField: 'vehicle_brands', truck_brands: selectedTrucks, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime};
		}

		// Update the town
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: formData,
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#CollapsiblePanelContentCarBrands').slideUp();
					$('#profileULStyle').empty();
					$('#profileULStyle').append(getSelectedVehicleBrands(selectedVehicle));
					deselectVehicle(selectedVehicle);
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	// Update artisans services or entrepreneurs inventory
	$('#submit_services_parts').click(function(event){
		event.preventDefault();

		// Get the selected vehicle, trim out spaces and convert the text to lowercase
		var selectedBusiness = $.trim($('#businessCategoryInput').text()).toLowerCase();		
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('technical_services_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('technical_services_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;
		if (selectedBusiness === 'artisan') {
			// Get selected artisans
			var selectedArtisans = getSelectedArtisanSkills();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {inputField: 'business_services', artisans: selectedArtisans, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime};
		} else if (selectedBusiness === 'seller') {
			// Get selected sellers
			var selectedSellers =  getSelectedInventories();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {inputField: 'business_services', sellers: selectedSellers, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime};
		} else if (selectedBusiness === 'technician') {
			// Get selected technicians
			var selectedTechnicians =  getSelectedTechnicalSkills();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {inputField: 'business_services', technical_services: selectedTechnicians, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime};
		} else if (selectedBusiness === 'spare part seller') {
			// Get selected vehicle spare part sellers
			var selectedPartSellers =  getSelectedSpareParts();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {inputField: 'business_services', spare_parts: selectedPartSellers, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime};
		}

		// Update the town
		$.ajax ({
			url: '../PHP-JSON/submitCusProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: formData,
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#CollapsiblePanelContentTechServ').slideUp();
					$('#profileULStyle1').empty();
					$('#profileULStyle1').append(getSelectedBusinessOptions(selectedBusiness));
					deselectBusiness(selectedBusiness);
					// displayMessage('Success', data.inputFieldUpdated);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

});


function getSelectedBusinessType() {
	var business_category = "";
	var business_categories = document.profileEdit.business_category;
	for (var i=0; i < business_categories.length; i++) {
		if (business_categories[i].checked) {
			business_category = business_categories[i].value;
			break;
		}
	}

	return business_category;
}

function getSelectedInventories() {
	// Get the selected inventories
	var inventories = document.technical_services_form.sellers;
	var selected_inventories = [];
	for (var i=0; i < inventories.length; i++) {
		if (inventories[i].checked) {
			selected_inventories.push(inventories[i].value);				
		}
	}
	
	if (selected_inventories.length < 1) {
		return selected_inventories = "";
	} else {
		return selected_inventories;
	}
}

function getSelectedArtisanSkills() {
	// Get the selected artisan skills
	var artisans = document.technical_services_form.artisans;
	var selected_artisans = [];
	for (var i=0; i < artisans.length; i++) {
		if (artisans[i].checked) {
			selected_artisans.push(artisans[i].value);				
		}
	}

	if (selected_artisans.length < 1) {
		return selected_artisans = "";
	} else {
		return selected_artisans;
	}
}

function getSelectedTechnicalSkills() {
	// Get the selected technical skills
	var technical_services = document.technical_services_form.technical_services;
	var selected_technical_services = [];
	for (var i=0; i < technical_services.length; i++) {
		if (technical_services[i].checked) {
			selected_technical_services.push(technical_services[i].value);				
		}
	}

	if (selected_technical_services.length < 1) {
		return selected_technical_services = "";
	} else {
		return selected_technical_services;
	}
}

function getSelectedSpareParts() {
	// Get the selected spare parts
	var spare_parts = document.technical_services_form.spare_parts;
	var selected_spare_parts = [];
	for (var i=0; i < spare_parts.length; i++) {
		if (spare_parts[i].checked) {
			selected_spare_parts.push(spare_parts[i].value);				
		}
	}

	if (selected_spare_parts.length < 1) {
		return selected_spare_parts = "";
	} else {
		return selected_spare_parts;
	}
}

function getSelectedVehicle() {
	var vehicle_category = "";
	var vehicle_categories = document.vehicle_category_form.vehicle_category;
	for (var i=0; i < vehicle_categories.length; i++) {
		if (vehicle_categories[i].checked) {
			vehicle_category = vehicle_categories[i].value;
			break;
		}
	}

	return vehicle_category;
}

function unSelectVehicle() {
	var vehicle_category = "";
	var vehicle_categories = document.vehicle_category_form.vehicle_category;
	for (var i=0; i < vehicle_categories.length; i++) {
		if (vehicle_categories[i].checked) {
			vehicle_categories[i].checked = false;
		}
	}
}

function getSelectedCarBrands() {
	// Get the selected spare parts
	var car_brands = document.cars_specialization_form.car_brands;
	var selected_car_brands = [];
	for (var i=0; i < car_brands.length; i++) {
		if (car_brands[i].checked) {
			selected_car_brands.push(car_brands[i].value);				
		}
	}

	if (selected_car_brands.length < 1) {
		return selected_car_brands = "";
	} else {
		return selected_car_brands;
	}
}

function getSelectedBusBrands() {
	// Get the selected spare parts
	var bus_brands = document.cars_specialization_form.bus_brands;
	var selected_bus_brands = [];
	for (var i=0; i < bus_brands.length; i++) {
		if (bus_brands[i].checked) {
			selected_bus_brands.push(bus_brands[i].value);				
		}
	}

	if (selected_bus_brands.length < 1) {
		return selected_bus_brands = "";
	} else {
		return selected_bus_brands;
	}
}

function getSelectedTruckBrands() {
	// Get the selected spare parts
	var truck_brands = document.cars_specialization_form.truck_brands;
	var selected_truck_brands = [];
	for (var i=0; i < truck_brands.length; i++) {
		if (truck_brands[i].checked) {
			selected_truck_brands.push(truck_brands[i].value);				
		}
	}

	if (selected_truck_brands.length < 1) {
		return selected_truck_brands = "";
	} else {
		return selected_truck_brands;
	}
}

function getSelectedVehicleBrands(selectedVehicle) {
	var selectedVehicleBrands = "";
	if (selectedVehicle === 'cars') {
		// Get selected car brands
		var selections = getSelectedCarBrands();		
	} else if (selectedVehicle === 'buses') {
		// Get selected car brands
		var selections =  getSelectedBusBrands();
	} else if (selectedVehicle === 'trucks') {
		// Get selected car brands
		var selections =  getSelectedTruckBrands();
	}

	// Capitalize the first character of the words
	for (var i = 0; i < selections.length; i++) {
		selections[i] = initialCaps(selections[i].replace(/_/g, ' '));
	}
	// Join the selecte cars
	selectedVehicleBrands = selections.join(', ');
	return selectedVehicleBrands;
}

function deselectVehicle(selectedVehicle) {
	if (selectedVehicle === 'cars') {
		// Deselect the selected car brands
		var selections = document.cars_specialization_form.car_brands;
	} else if (selectedVehicle === 'buses') {
		// Deselect the selected car brands
		var selections = document.cars_specialization_form.bus_brands;
	} else if (selectedVehicle === 'trucks') {
		// Deselect the selected car brands
		var selections = document.cars_specialization_form.truck_brands;
	}

	for (var i=0; i < selections.length; i++) {
		if (selections[i].checked) {
			selections[i].checked = false;
		}
	}
}

function getSelectedBusinessOptions(selectedBusiness) {
	var selBbusiness = "";

	if (selectedBusiness === 'artisan') {
		var selection = getSelectedArtisanSkills();		
	} else if (selectedBusiness === 'seller') {
		var selection = getSelectedInventories();
	} else if (selectedBusiness === 'technician') {
		var selection = getSelectedTechnicalSkills();
	} else if (selectedBusiness === 'spare part seller') {
		var selection = getSelectedSpareParts(); 
	}		

	// Capitalize the first character of the words
	for (var i = 0; i < selection.length; i++) {
		selection[i] = initialCaps(selection[i].replace(/_/g, ' '));
	}
	// Join the selected options in the array
	selBbusiness = selection.join(', ');

	return selBbusiness;
}

function deselectBusiness(selectedBusiness) {
	if (selectedBusiness === 'artisan') {
		var selection = document.technical_services_form.artisans;
	} else if (selectedBusiness === 'seller') {
		var selection = document.technical_services_form.sellers;
	} else if (selectedBusiness === 'technician') {
		var selection = document.technical_services_form.technical_services;
	} else if (selectedBusiness === 'spare part seller') {
		var selection = document.technical_services_form.spare_parts; 
	}
	
	for (var i=0; i < selection.length; i++) {
		if (selection[i].checked) {
			selection[i].checked = false;
		}
	}
}


$(document).ready(function(){
	$('#submit_availability').click(function(event){
		event.preventDefault();
		// Get the day selected
		var day = $('#set_days').val();
		// Get the hours selected
		var hours = getSelectedHours();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('form2').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('form2').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;
		var formData = {set_days: day, set_hours: hours, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime};

		$.ajax({
			url: '../PHP-JSON/submitCusAvailability_JSON.php',
			type: 'POST',
			dataType: 'json',
			data: formData,

			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					// Delete the selected day as an option
					deleteSelectedOption(day);
					uncheckHoursSelected();
					// append the new div containing the time schedule created
					appendAvailability(data.customerId, data.rawDate, data.weekday, data.dateText, data.cusAvailTbId, hours);
					appendEditAvailability();
					// displayMessage('Success', data.savedAvail);
				} else if (data.saveError) {
					displayMessage('Error', data.saveError);
				} else if (data.validationError) {
					// concatenate errors
          var message = "";
          $.each(data.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
					displayMessage('Error', message);
				} else if (data.loginError) {
					displayMessage('Error', data.loginError);
				} else if (data.postDataError) {
					displayMessage('Error', data.postDataError);
				} else if (data.csrfFailed) {
					displayMessage('Error', data.csrfFailed);
				}
			}
		});
	});

	$('#selectAll').change(function(){
	  var selection = document.form2.set_hours;
	  if (this.checked) {	  	
			for (var i=0; i < selection.length; i++) {
				selection[i].checked = true;
			}
	  } else {
	  	for (var i=0; i < selection.length; i++) {
				selection[i].checked = false;
			}
	  }
	});
});

function getSelectedHours() {
	// Get the selected hours
	var hours = document.form2.set_hours;
	var selected_hours = [];
	for (var i=0; i < hours.length; i++) {
		if (hours[i].checked) {
			selected_hours.push(hours[i].value);				
		}
	}
	
	if (selected_hours.length < 1) {
		return selected_hours = "";
	} else {
		return selected_hours;
	}
}

// This function removes a option of a day after a customer availability has been set.
function deleteSelectedOption(day) {
	// Get the options of the select tag;
	var options = $('#set_days').children();
	$.each(options, function(i, option) {
    // check for the option of the day selected
    if (option.value === day) {
    	// Delete the option
    	option.remove();
    }
  });
}

function uncheckHoursSelected() {
	var selection = document.form2.set_hours; 
	
	for (var i=0; i < selection.length; i++) {
		if (selection[i].checked) {
			selection[i].checked = false;
		}
	}
	document.getElementById('selectAll').checked = false;
}

function appendAvailability(customerId, rawDate, weekday, dateText, cusAvailTbId, hours) {
	// Get the id of the last display appointment div
	var lastDivId = document.getElementById('displayAppointments').lastElementChild.id;
	// extract the 8 text of the id
	var idText = lastDivId.substring(0,8);
	if (idText === 'editTime') {
		// get the last text of the id
		var idNum = lastDivId.substring(8);
		// Convert the string to a number and increment
		var newIdNum = parseInt(idNum, 10) + 1;
	} else {
		var newIdNum = 0;
	}

	// Create the div to contain all the hour schedule
	var scheduleDiv = document.createElement("div");
	// Set the id for the div tag
	scheduleDiv.setAttribute("id", "scheduleDiv"+newIdNum);
	// Set the class for the div tag
	scheduleDiv.setAttribute("class", "schedule");

	// Create the hidden paragraph tag for customer
	var cusIdPara = document.createElement("p");
	// Set the id for the div tag
	cusIdPara.setAttribute("id", "customers_id");
	// Set the hidden display style
	cusIdPara.setAttribute("class", "hideElement");
	// Set the class for the div tag
	cusIdPara.innerHTML = customerId;

	// Create the hidden paragraph tag for date_available
	var dateAvailPara = document.createElement("p");
	// Set the id for the div tag
	dateAvailPara.setAttribute("id", "date_available");
	// Set the hidden display style
	dateAvailPara.setAttribute("class", "hideElement");
	// Set the class for the div tag
	dateAvailPara.innerHTML = rawDate;

	// Create the paragraph tag for date schedule
	var weekdayPara = document.createElement("p");
	// Set the hidden display style
	weekdayPara.setAttribute("class", "weekday");
	// Set the class for the div tag
	weekdayPara.innerHTML = initialCaps(weekday)+": "+dateText;

	// Create a div for the LI tags
	var liDiv = document.createElement("div");
	liDiv.setAttribute('class', 'hoursDiv');

	// Create the UL tag for all hours
	var ulTime = document.createElement("ul");
	// Set the id
	ulTime.setAttribute("id", "timeUL"+newIdNum);
	// Set the class
	ulTime.setAttribute("class", "timeUL");	

	// Create the LI tag for all hours
	for (var i = 0; i < hours.length; i++) {
		var liTime = document.createElement("li");
		// Set the class
		liTime.setAttribute("class", "timeList");
		liTime.innerHTML = editTimeVarToFormTime(hours[i]);
		ulTime.appendChild(liTime);
	}

	liDiv.appendChild(ulTime);

	// Create the div for the edit and delete button
	var btnsDiv = document.createElement("div");
	btnsDiv.setAttribute("class", "checkBoxBtnDiv");

	// Create the edit button
	var editBtn = document.createElement("input");
	// Set the type
	editBtn.setAttribute("type", "button");
	// Set the value
	editBtn.setAttribute("value", "Edit");
	// Set the id
	editBtn.setAttribute("id", "openDiv"+newIdNum);
	// Open the div
	editBtn.setAttribute("onclick", "openEditHoursDiv(id)");
	// Set the class
	editBtn.setAttribute("class", "editButton btnStyle1");

	// Create the delete button
	var deleteBtn = document.createElement("input");
	// Set the type
	deleteBtn.setAttribute("type", "button");
	// Set the custom property
	deleteBtn.setAttribute("data-cusavailid", cusAvailTbId.toString());
	// Set the value
	deleteBtn.setAttribute("value", "Delete");
	// Set the id
	deleteBtn.setAttribute("id", "deleteSchedule"+newIdNum);
	// Set the class
	deleteBtn.setAttribute("class", "btnStyle1 deleteSchedule");
	// Set the javascript onclick function
	deleteBtn.setAttribute("onclick", "deleteAvailSch(this.id);");

	// Append buttons
	btnsDiv.appendChild(editBtn);
	btnsDiv.appendChild(deleteBtn);

	// Append the elements to the schedule div
	scheduleDiv.appendChild(cusIdPara);
	scheduleDiv.appendChild(dateAvailPara);
	scheduleDiv.appendChild(weekdayPara);
	scheduleDiv.appendChild(liDiv);
	scheduleDiv.appendChild(btnsDiv);

	// Get the object of the div containing all the appointment schedules
	var divBox = document.getElementById('displayAppointments');
	divBox.appendChild(scheduleDiv);
}

function editTimeVarToFormTime(timeVAr) {
	if (timeVAr === 'eight_to_nine_am') {
		return '8:00 AM - 9:00 AM';
	} else if (timeVAr === 'nine_to_ten_am') {
		return '9:00 AM - 10:00 AM';
	} else if (timeVAr === 'ten_to_eleven_am') {
		return '10:00 AM - 11:00 AM';
	} else if (timeVAr === 'eleven_to_twelve_pm') {
		return '11:00 AM - 12:00 PM';
	} else if (timeVAr === 'twelve_to_one_pm') {
		return '12:00 PM - 1:00 PM';
	} else if (timeVAr === 'one_to_two_pm') {
		return '1:00 PM - 2:00 PM';
	} else if (timeVAr === 'two_to_three_pm') {
		return '2:00 PM - 3:00 PM';
	} else if (timeVAr === 'three_to_four_pm') {
		return '3:00 PM - 4:00 PM';
	} else if (timeVAr === 'four_to_five_pm') {
		return '4:00 PM - 5:00 PM';
	} else if (timeVAr === 'five_to_six_pm') {
		return '5:00 PM - 6:00 PM';
	} else if (timeVAr === 'six_to_seven_pm') {
		return '6:00 PM - 7:00 PM';
	} else if (timeVAr === 'seven_to_eight_pm') {
		return '7:00 PM - 8:00 PM';
	} else if (timeVAr === 'eight_to_nine_pm') {
		return '8:00 PM - 9:00 PM';
	} else if (timeVAr === 'nine_to_ten_pm') {
		return '9:00 PM - 10:00 PM';
	}						
}

// Edit the availability
function appendEditAvailability() {
	// Get the id of the last display appointment div
	var lastDivId = document.getElementById('displayAppointments').lastElementChild.id;
	// extract the 8 text of the id
	var idText = lastDivId.substring(0,11);
	if (idText === 'scheduleDiv') {
		// get the last text of the id
		var idNum = lastDivId.substring(11);
		// Convert the string to an integer
		var newIdNum = parseInt(idNum, 10);
	} else {
		var newIdNum = 0;
	}

	// Create the div to contain all the hour schedule
	var editTimeDiv = document.createElement("div");
	// Set the id for the div tag
	editTimeDiv.setAttribute("id", "editTime"+newIdNum);
	// Set the class for the div tag
	editTimeDiv.setAttribute("class", "editClass");

	// Create the a label element
	var timeLabel = document.createElement("label");
	timeLabel.innerHTML = "Edit Hours:";

	// Create the div for select all check box and close button
	var chkBoxDiv = document.createElement("div");
	chkBoxDiv.setAttribute("class", "checkBoxBtnDiv");

	// Create check box label
	var sltAllChkBoxLabel = document.createElement("label");
	sltAllChkBoxLabel.innerHTML = "Select All";

	// Create select all check box div
	var sltAllChkBox = document.createElement("input");
	sltAllChkBox.setAttribute("type", "checkbox");
	sltAllChkBox.setAttribute("name", "sltAllChkBox"+newIdNum);
	sltAllChkBox.setAttribute("value", "sltAllChkBox"+newIdNum);
	sltAllChkBox.setAttribute("id", "sltAllChkBox"+newIdNum);
	sltAllChkBox.setAttribute("onchange", "sltAllChkBoxFxn(this)");

	// Append checkbox to label
	sltAllChkBoxLabel.appendChild(sltAllChkBox);

	// Append elements to div
	chkBoxDiv.appendChild(sltAllChkBoxLabel);

	editTimeDiv.appendChild(timeLabel);
	editTimeDiv.appendChild(chkBoxDiv);

	timeValue = ["eight_to_nine_am", "nine_to_ten_am", "ten_to_eleven_am", "eleven_to_twelve_pm", "twelve_to_one_pm", "one_to_two_pm", "two_to_three_pm", "three_to_four_pm", "four_to_five_pm", "five_to_six_pm", "six_to_seven_pm", "seven_to_eight_pm", "eight_to_nine_pm", "nine_to_ten_pm"];

	// Create div for the check box
	var hoursChkBoxDiv = document.createElement("div");
	hoursChkBoxDiv.setAttribute("class", "hoursDiv");

	// make a loop for the checkboxes
	for(var i = 0; i < timeValue.length; i++) {
		// Create the a label element
		var timeChkBxLabel = document.createElement("label");
		// Set the class for the label tag
		timeChkBxLabel.setAttribute("class", "hoursLabel");

		// Create the checkboxes for editing time
		var timeChkBox = document.createElement("input");
		// Set the type for the input tag
		timeChkBox.setAttribute("type", "checkbox");
		// Set the name for the input tag
		timeChkBox.setAttribute("name", "edit_hours"+newIdNum);
		// Set the value for the input tag
		timeChkBox.setAttribute("value", timeValue[i].toString());
		// Set the id for the input tag
		timeChkBox.setAttribute("id", timeValue[i]+newIdNum.toString());		

		// append to label
		timeChkBxLabel.appendChild(timeChkBox);
		// append the check box content to the label
		timeChkBxLabel.append(editTimeVarToFormTime(timeValue[i]));
		// Append to edit time div
		hoursChkBoxDiv.appendChild(timeChkBxLabel);
	}

	// Create the div for the close and submit buttons
	var actionBtnsDiv = document.createElement("div");
	// Set the class for the input tag
	actionBtnsDiv.setAttribute("class", "submitAndCloseBtnsDiv");

	// Create the close button for hiding the div
	var closeBtn = document.createElement("input");
	// Set the type for the input tag
	closeBtn.setAttribute("type", "button");
	// Set the value for the input tag
	closeBtn.setAttribute("value", "Close");
	// Set the id for the input tag
	closeBtn.setAttribute("id", "closeDiv"+newIdNum);
	closeBtn.setAttribute("onclick", "closeEditHoursDiv(id)");
	// Set the class for the input tag
	closeBtn.setAttribute("class", "closeButton btnStyle1");

	// Create the submit button
	var submitBtn = document.createElement("input");
	// Set the type for the input tag
	submitBtn.setAttribute("type", "submit");
	// Set the name for the input tag
	submitBtn.setAttribute("name", "submit_edited_availability"+newIdNum);
	// Set the value for the input tag
	submitBtn.setAttribute("value", "Submit");
	// Set the id for the input tag
	submitBtn.setAttribute("id", "submit_new_availability"+newIdNum);
	// Set the class for the input tag
	submitBtn.setAttribute("class", "btnStyle1 submitEditSch");
	// concatenate the argument string
	var args = "";
	args += "customer_availability_update(";
	for (var j = 0; j < timeValue.length; j++) {
		args += "'"+timeValue[j]+newIdNum+"', ";
	}
	args += "'sltAllChkBox"+newIdNum+"', ";
	args += "'timeUL"+newIdNum+"', ";
	args += "'errorMessageDiv"+newIdNum+"'";
	args += ")";
	// Set the class for the input tag
	submitBtn.setAttribute("onclick", args.toString());

	// Append the close and submit buttons to the div
	actionBtnsDiv.appendChild(closeBtn);
	actionBtnsDiv.appendChild(submitBtn);

	// Create the error message div
	var errorMsgDiv = document.createElement("div");
	// Set the id for the div tag
	errorMsgDiv.setAttribute("id", "errorMessageDiv"+newIdNum);

	// Append elements to div
	editTimeDiv.appendChild(hoursChkBoxDiv);
	editTimeDiv.innerHTML += "<br/>";
	editTimeDiv.appendChild(actionBtnsDiv);
	editTimeDiv.appendChild(errorMsgDiv);

	// Get the object of the div containing all the appointment schedules
	var divBox = document.getElementById('displayAppointments');
	divBox.appendChild(editTimeDiv);
}

// Open the edit hours div so that the hours can edited
function openEditHoursDiv(id) {
	// Hide the edit button
	$('#'+id).hide('blind');
	$('#'+id).parent().parent().next().show('blind');
}

// Close the edit hours div
function closeEditHoursDiv(id) {
	$('#'+id).parent().parent().hide('blind');
	// Extract the number in the id name 'closeDiv#'
	var idNum = id.substring(8);	
	// show the edit button
	$('#openDiv'+idNum).show('blind');
}


function sltAllChkBoxFxn(chkBoxObj) {	 
	// Get the number in the id
	var id = chkBoxObj.id;
	var idNum = id.substring(12);
	var chkboxName = 'edit_hours'+idNum;

	var selection = document.getElementsByName(chkboxName);
  if (chkBoxObj.checked) {	  	
		for (var i=0; i < selection.length; i++) {
			selection[i].checked = true;
		}
  } else {
  	for (var i=0; i < selection.length; i++) {
			selection[i].checked = false;
		}
  }
}

function sortOptions() {
	optionArray = $('#set_days').children();

	optionArray.sort(function(a,b){
		return new Date(a.id) - new Date(b.id);
	});
	
	return optionArray;	
}



// This function will save a customer in the database for advertisement
$(document).ready(function(){
	$('#advertise').click(function(event){
		event.preventDefault();

		$.ajax({
			url: '../PHP-JSON/saveAdvertiser_JSON.php',
			type: 'POST',
			dataType: 'json',
			data: {'advertise' : 'advertise'},

			success: function(data) {
				if (data.result === "Advertising saved") {
					// Change the input Id, css and Value from cancel ad to advertising
					// Change beginAdInfo properties to cancelAdInfo properties
					$('#beginAdInfo').css('color', 'green');
					$('#beginAdInfo').attr('value', 'Advertising');
					// Change id last
					$('#beginAdInfo').attr('id', 'cancelAdInfo');

					// Hide the advertising button and show the cancel advertising button
					$('#advertise').css('display', 'none');
					$('#cancelAdvertising').css('display', 'inline-block');
				} else {
					displayMessage('Error', data.result);
				}
			}
		});
	});
});

// This function will delete a customer in the database for advertisement
$(document).ready(function(){
	$('#cancelAdvertising').click(function(event){
		event.preventDefault();

		$.ajax({
			url: '../PHP-JSON/saveAdvertiser_JSON.php',
			type: 'POST',
			dataType: 'json',
			data: {'cancelAdvert' : 'cancelAdvert'},

			success: function(data) {
				if (data.result === "Advertising canceled") {
					// Change the input Id, css and Value from advertising to cancel ad
					// Change cancelAdInfo properties to beginAdInfo properties
					$('#cancelAdInfo').css('color', '#A51300');
					$('#cancelAdInfo').attr('value', 'Advertise your business');
					// Change id last
					$('#cancelAdInfo').attr('id', 'beginAdInfo');
					
					// Hide the cancel advertising button and show the advertising button
					$('#cancelAdvertising').css('display', 'none');
					$('#advertise').css('display', 'inline-block');
				} else {
					displayMessage('Error', data.result);
				}
			}
		});
	});
});