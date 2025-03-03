$(document).ready(function() {
	$('#submit_appointment').on('click', function(event) {
		// prevent default button behaviour
		event.preventDefault();
		// Get the customer's id to schedule appointment with
		var customerId = $('#customers_id').text();

		// Get the day selected
		var day = $('#choose_day').val();
		
		// Get the selected time
		var timeObj = $("input[type='checkbox']:checked");
		var time = [];
		if (timeObj.length > 0) {
			timeObj.each(function(index) {
				time[index] = $(this).val();
			});
		}

		// Get the message 
		var message = $('#appointment_message').val();
		
		// Validate the user submitted data
		var validationResult = [];
		// Check day is selected
		if (day == 'select') {			
			validationResult.push('day');
		}
		// check hour is selected
		if (time.length < 1) {
			validationResult.push('time');
		}
		// check message was passed
		if (message === '') {
			validationResult.push('message');
		}
		
		// Check validation is empty
		if (validationResult.length < 1) {
			dataVals = {customerId: customerId, day: day, hours: time, message: message}; 

			$.ajax({
				url: '../PHP-JSON/submitAppointment_JSON.php',
				dataType: 'json',
				type: 'post',
				data: dataVals,

				beforeSend: function() {
					$(".loader").fadeIn().css("display", "flex");
					$(".loader").css("color", "grey");
					$(".loader").append("<p style='margin-left: 10px;'>Hold on! Sending email and text messages.</p>");
				},

				complete: function() {
					$(".loader").fadeOut().css("display", "none");
				},

				success: function(response) {
					if (response.success) {
						// concatenate message
						var message = "";
						message = "<p>"+response.result+"</p>";
						message += "<p>"+response.SMSoutcome+"</p>";
						message += "<p>"+response.emailOutcome+"</p>";
						// Display success message
						displayMessage('Success', message);

						// Clear the selected day, time, and message
						// change the select to default of select
						$('#choose_day').val('select').prop('selected', true);
						// clear the selected time
						timeObj.each(function(index) {
							$('#'+$(this).attr('id')).prop('checked', false);
						});
						$('#checkboxOfDays').slideUp();
						// clear the message
						$('#appointment_message').val('');
					} else {
						// Display error message
						displayMessage('Error', response.result);
					}
				}
			});
		} else {
			// display error message to user
			var errorMessage = '';
			for (var obj in validationResult) {
				if (validationResult[obj] === 'day') {
					// Display message to the user
					errorMessage += "<p>Select a weekday for an appointment.</p>";
				}
				if (validationResult[obj] === 'time') {
					// Display message to the user
					errorMessage += "<p>Select time for an appointment.</p>";
				}
				if (validationResult[obj] === 'message') {
					errorMessage += "<p>Please provide a reason for appointment.</p>";
				}
				displayMessage('Error', errorMessage);
			}
		}
	});
}); 


function userSelectedDay() {
	// get all the check boxes ids for the time and determine if it is checked.
	var choose_day = document.getElementById("choose_day").value;
	var selectedIndexVal = document.getElementById("choose_day").selectedIndex;
	
	// create a variable for the results element tag that information will be outputted to
	// get the id for the paragraph that will contain the outputted values
	var checkboxOfDays = document.getElementById("checkboxOfDays");
	
	// get the customer's id
	var customers_id = document.getElementById("customers_id").innerHTML;
	var concatIdName = "date_selected"+selectedIndexVal; // this["date_selected"+selectedIndexVal]
	if (choose_day !== 'select') {
		var date_selected = document.getElementById(concatIdName).innerHTML;
	}
	
	// Concatenate variables to send to the PHP JSON file
	var varToPHP = "customers_id="+customers_id+"&date_selected="+date_selected;
	
	// Create our XMLHttpRequest object
	var hr = new XMLHttpRequest();
	
	// Assign the link to the PHP file 
	var url = "../customer/customerAvailableDayHours_JSON.php";
	
	hr.open("POST", url, true);
	
	// Set content type header information for sending url encoded variables in the request
	// Since we are using a POST request, the content type is set to: x-www-form-urlencoded
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	// You could use an animated.gif loader here while you wait for data from the server.
	if (choose_day !== 'select') {
		checkboxOfDays.innerHTML = "requesting...";
	} else {
		checkboxOfDays.innerHTML = "";
	}
	
	// Send the data to PHP now... and wait for response to update the status div
	// When using post, a dynamic data can be sent through the send method. Here the variable is manually sent in.
	if (choose_day !== 'select') {
		hr.send(varToPHP); // Actually execute the request
	}
	
	// Access the onreadystatechange event for the XMLHttpRequest object
	hr.onreadystatechange = function() {
		if (hr.readyState == 4 && hr.status == 200) {
			// This is information from the server side through the AJAX request. This will give the HttpResponseText
			// JSON.parse() function will be used instead
			// The JSON.parse() function helps get it ready for javascript parsing. 
			var data = JSON.parse(hr.responseText);
			// console.log(data); // Used to troubleshoot.
			
			// This will clear the contents in the div before what is to be printed out comes.
			checkboxOfDays.innerHTML = " ";
			checkboxOfDays.innerHTML = "<label>Select Hours:</label><br/>";
			for (var obj in data) {
				// we will compound a list of HTML commands in here.
				if (data[obj]) {
					checkboxOfDays.innerHTML += '<label><input type="checkbox" name="choose_hours[]" value="'+data[obj].checkboxValue+'" id="'+data[obj].checkboxValue+'" />'+data[obj].availableHour+'</label><br/>';
				}
			}
			$('#checkboxOfDays').slideDown();
		}
	}
}


/* ********************************************************** */
// These scripts controls the behaviour that displays a larger image when it is clicked

// This controls the behaviour when the button is clicked to close the modal
$(document).on('click', '.close-btn', function() {
	$('.bg-modal').fadeOut("slow");
	// document.querySelector('.bg-modal').style.display = 'none';
});

/* // Initialize the customerIdVal and photoIdVal global variables
var customerIdVal = 0;
var photoIdVal = 0;
// This controls the response of when an image is clicked to project it on a modal
var body = document.getElementsByTagName("body")[0];
body.addEventListener('click', function(e){
	var item = e.target;
	if (item.tagName == "IMG" && item.name == "cus-ad-image") {
		
	}
}); */



// Initialize the customerIdVal and photoIdVal global variables
var customerIdVal = 0;
var photoIdVal = 0;
// This controls the response of when an image is clicked to project it on a modal
function galleryImg(item) {
	// Get image id
	imageId = item.id;
	// Extract the number in the id to use for the caption id
	idNumber = imageId.substring(12);
	// Get the customer id
	var customerId = "customerId"+idNumber;
	customerIdVal = $("#"+customerId).attr("value");
	// Get the image id
	var photoId = "photoId"+idNumber;
	photoIdVal = $("#"+photoId).attr("value");
	// get the caption id
	var captionId = "imageCaption"+idNumber;
	// use the image id to get the image path 
	var img_path = $("#"+imageId).attr("imgurl");
	// use the caption id to get the caption of the image
	var caption = $("#"+captionId).text();
	
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

/* controls the comment submit button when clicked */
$(document).ready(function(){
	// Use this variable for increment when the submit button is clicked
	// It is used to increment the number of comment div to be created when the comment button is clicked
	var j = 0;
	$('#submitPhotoComment').click(function(){
		j++;
		// Get the input from the textarea
		var comment = $('#photoCommentText').val();
		
		$.ajax({
			url: "../PHP-JSON/likeComment_JSON.php", 
			type: "POST",
			dataType: 'json',
			data: {comment: comment, customerId: customerIdVal, photoId: photoIdVal},
			success: function(data) {
				if (data.comment) {
					// Update the number of comments on the user
					$('#totalComments').text(data.totalComments+" comments");
					
					// prepend the comment to the top of the div as the most recent comment on the customer
					$('#photoComments').prepend("<div id='comment"+photoIdVal+"_"+j+"' class='comment'><div id='commentBox"+photoIdVal+"_"+j+"' class='commentBox'><div id='authorDate"+photoIdVal+"_"+j+"' class='authorDate'><div id='author"+photoIdVal+"_"+j+"' class='author' >"+data.author+"</div> <div id='meta-info"+photoIdVal+"_"+j+"' class='meta-info' >"+data.created+"</div> </div> <div id='commentBody"+photoIdVal+"_"+j+"' class='commentBody' commentId='"+data.commentId+"' >"+data.comment+"</div></div><button class='replyBtn btnStyle3' id='replyBtn"+photoIdVal+"_"+j+"' onclick='reply2(this)'>Reply Comment</button></div>");
					
					// clear the textarea of the input field for the comment box
					$('#photoCommentText').val("");
				} else if (data.errors) {
					if (data.errors && data.message) {
						// Display error message if the comment was not saved
						displayMessage('Error', data.message);
						// $('#feedback').append("<p>"+data.message+"</p>");
					} else {
						$('#feedback').text("");
						if (data.validate_errors.comment) {
							// Display error message
							displayMessage('Error', data.validate_errors.comment);
							// $('#feedback').append("<p>"+data.validate_errors.comment+"</p>");
						}
						if (data.validate_errors.comment_err_long) {
							// Display error message
							displayMessage('Error', data.validate_errors.comment_err_long);
							// $('#feedback').append("<p>"+data.validate_errors.comment_err_long+"</p>");
						}
						if (data.validate_errors.comment_comment_error) {
							// Display error message
							displayMessage('Error', data.validate_errors.comment_comment_error);
							// $('#feedback').append("<p>"+data.validate_errors.comment_comment_error+"</p>");
						}
					}
				}
			}
		});
	});
});

// This will like the photo when clicked
/*
$(document).ready(function(){
	$('#adLike').click(function(){
		console.log("Button clicked");
		
		$.ajax ({
			url: '../PHP-JSON/likeComment_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {photoId: photoIdVal, customerId: customerIdVal, like: "likePhoto"},
			
			success: function(data) {
				console.log(data);
				// Get the number of likes and update the webpage
				if (data.result === "liked") {					
					$("#adLike").html("<span style='color: #A51300;'><i class='fas fa-thumbs-up'></i>Like ("+data.numLike+")</span>");
				}
			}
		});
	});
}); */

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

/* ******************************************************************* */
// This functions controls the behaviour for scrolling the page up or down
$(document).ready(function(){
	var offset = 250;
	var duration = 500;
	
	$(window).scroll(function(){
		if ($(this).scrollTop() > offset) {
			$('.to-top').fadeIn(duration);
		} else {
			$('.to-top').fadeOut(duration);
		}
	})
});

// Display the comment button
$(document).ready(function(){
	$('#commentTextarea').on('input', function() {
		var commentVar = $.trim($("#commentTextarea").val());		
		if (commentVar !== '') {
			$('#submitComment').css('display', 'inline-block');
		} else {
			$('#submitComment').css('display', 'none');			
		}
	});
});

/* */
$(document).ready(function(){
	$('#submitComment').click(function(e){
		// Get the input from the textarea
		var comment = $('#commentTextarea').val();
		// Get the customers id
		var customerId = $('#submitComment').attr("customerId");
		// console.log("The customer id is: "+customerId);
		// var author = "<?php echo ?>";
		
		$.ajax({
			url: "../PHP-JSON/submitComment_JSON.php",
			type: "POST",
			dataType: 'json',
			data: {comment: comment, customerId: customerId},
			success: function(data) {
				// console.log(data);
				if (data.success) {
					// Update the number of comments on the user
					$('#totalComments').text(data.totalComments+" comments");
					
					// prepend the comment to the top of the div as the most recent comment on the customer
					$('#comments').prepend("<div class='comment'> <div class='authorDate'><div class='author' >"+data.author+"</div> <div class='meta-info' >"+data.created+"</div> </div> <div class='commentBody' commentId='"+data.commentId+"' >"+data.comment+"</div> </div>");
					
					// clear the textarea of the input field for the comment box
					$('#commentTextarea').val("");
				} else {
					if (data.errors && data.message) {
						// Display error message if comment was not saved.
						displayMessage('Error', data.message);
						// $('#feedback').append("<p>"+data.message+"</p>");
					} else {
						// $('#feedback').text("");
						if (data.validate_errors.comment) {
							// Display error message
							displayMessage('Error', data.validate_errors.comment);
							// $('#feedback').append("<p>"+data.validate_errors.comment+"</p>");
						}
						if (data.validate_errors.comment_err_long) {
							// Display error message
							displayMessage('Error', data.validate_errors.comment_err_long);
							// $('#feedback').append("<p>"+data.validate_errors.comment_err_long+"</p>");
						}
						if (data.validate_errors.comment_comment_error) {
							// Display error message
							displayMessage('Error', data.validate_errors.comment_comment_error);							
							// $('#feedback').append("<p>"+data.validate_errors.comment_comment_error+"</p>");
						}
					}
				}
			}
		});
	});
});

function displayMessage(title, content) {
	// Show the modal
	$('.messageModal').fadeIn().css('display', 'flex');
	// Set the header
	$('#messageHead').empty();
	$('#messageHead').append(title);
	// Set the content
	$('#messageContent').empty();
	$('#messageContent').append(content);
}

// when the reply button is clicked, insert the reply dive after the reply button. This will ensure that there is only one reply div to deal with at a time.
function reply(caller) {
	var id = caller.id;
	$("#replyDiv").insertAfter($(caller));
	$("#replyDiv").show();

	return true;
}

/* */
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
	var index = grandPropId.substring(7,8);
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
					// $(commentDivId).append
					$(replyContainerId).append("<div id='replyComment"+index+subIndex+"' class='replyComment'><div class='authorDate' id='replyAuthorDate"+index+subIndex+"'><div class='author' id='replyAuthor"+index+subIndex+"'>"+data.author+" replied: </div> <div class='meta-info' id='meta-info"+index+subIndex+"'>"+data.created+"</div></div> <div class='commentBody' id='replyCommentBody"+index+subIndex+"'>"+data.reply+"</div> </div>");
				} else {
					// perform a prepend
					// get the last element in the div
					var lastElement = divIds.get(-1);
					var subIndex = lastElement.substring(13,14);
					// increment the subIndex by 1
					subIndex++;
					$(replyContainerId).prepend("<div id='replyComment"+index+subIndex+"' class='replyComment'><div class='authorDate' id='replyAuthorDate"+index+subIndex+"'><div class='author' id='replyAuthor"+index+subIndex+"'>"+data.author+" replied: </div> <div class='meta-info' id='meta-info"+index+subIndex+"'>"+data.created+"</div></div> <div class='commentBody' id='replyCommentBody"+index+subIndex+"'>"+data.reply+"</div> </div>");
				}
				
				// make the div visible
				// clear the reply div textarea
				$('#replyTextarea').val("");
				// Hide the reply div
				$('#replyDiv').hide();
				// hide the reply button
				$('#replyBtn'+index).hide();
			} else {
				if (data.errors && data.message) {
					// Displays errors if message was not saved.
					// Display error message
					displayMessage('Error', data.message);
					// $('#feedback').append("<p>"+data.message+"</p>");
				} else {
					// $('#feedback').text("");
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
						$('#feedback').append("<p>"+data.validate_errors.reply_comment_error+"</p>");
					}
				}
			}
		}
	});

	return true;
}

/* Save record of public_user if advocating a customer */
$(document).ready(function(){
	// check if the advocated button has been clicked
	var advocateVal = $('#advocate').text();
	var advocatedVal = $('#advocated').text();
	if (advocatedVal === 'ADVOCATED') {
		$('#advocated').css('font-weight', 'normal');
	}

	/* if (advocateVal === 'ADVOCATE') {

	}	*/
	$('#advocate').click(function(){
		var customerId = $('#advocate').attr("data-customerid");
		// console.log("customer id is: "+ customerId);

		// Send data to be saved in the database
		$.ajax({
			url: "../PHP-JSON/saveAdvocate_JSON.php",
			type: "POST",
			dataType: 'json',
			data: {customerId: customerId, action: 'increase'},
			success: function(data){
				if (data.result === "advocator saved") {
					// activate the advocated button and disable the advocate button
					$('#advocate').css('display', 'none');
					$('#advocated').css('display', 'block');
					$('#numberAdvocates').text(data.numberOfAdvocators+' advocators');
				}
			}
		});
	});

	$('#advocated').click(function(){
		var customerId = $('#advocate').attr("data-customerid");
		// console.log("customer id is: "+ customerId);

		// Send data to be saved in the database
		$.ajax({
			url: "../PHP-JSON/saveAdvocate_JSON.php",
			type: "POST",
			dataType: 'json',
			data: {customerId: customerId, action: 'decrease'},
			success: function(data){
				if (data.result === "advocator deleted") {
					// activate the advocated button and disable the advocate button
					$('#advocate').css('display', 'block');
					$('#advocated').css('display', 'none');
					$('#numberAdvocates').text(data.numberOfAdvocators+' advocators');
				}
			}
		});
	});
});

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
					// Display error message if the reply was not saved
					displayMessage('Error', data.message);
					// $('#feedback').append("<p>"+data.message+"</p>");
				} else {
					// $('#feedback').text("");
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




/***********    This controls the display of the book appointment modal     *********/
// This controls the behaviour when the button is clicked to close the modal
$(document).on('click', '#closeAptModal', function() {
	$('#appointmentModal').fadeOut("slow");
	// document.querySelector('.bg-modal').style.display = 'none';	
});

$(document).on('click', '#bookAppointmentBtn', function(event) {
	// event.preventDefault();
	// show the div with the enlarged image when the image is clicked
	$('#appointmentModal').fadeIn("slow");
	document.querySelector('#appointmentModal').style.display = 'flex';
});
	



/*
Get social media sharelinks on crunchify.com
- search for share links
https://crunchify.com/list-of-all-social-sharing-urls-for-handy-reference-social-media-sharing-buttons-without-javascript/


Social Share Links:

Whatsapp:
https://wa.me/?text=[post-title] [post-url]

Facebook:
https://www.facebook.com/sharer.php?u=[post-url]

Twitter:
https://twitter.com/share?url=[post-url]&text=[post-title]&via=[via]&hashtags=[hashtags]
// In twitter, the hastag can be omitted hence the url becomes:
https://twitter.com/share?url=[post-url]&text=[post-title]

Pinterest:
https://pinterest.com/pin/create/bookmarklet/?media=[post-img]&url=[post-url]&is_video=[is_video]&description=[post-title]

LinkedIn:
https://www.linkedin.com/shareArticle?url=[post-url]&title=[post-title]

*/

// window.load = socialMediaFxn;
function socialMediaFxn() {
	// var facebookBtn = document.querySelector("#facebook-btn");
	// var twitterBtn = document.querySelector("#twitter-btn");
	// var pinterestBtn = document.querySelector("#pinterest-btn");
	// var linkedinBtn = document.querySelector("#linkedin-btn");
	// var whatsappBtn = document.querySelector("#whatsapp-btn"); 
	var facebookBtn = document.getElementById("facebook-btn");
	var twitterBtn = document.getElementById("twitter-btn");
	var pinterestBtn = document.getElementById("pinterest-btn");
	var linkedinBtn = document.getElementById("linkedin-btn");
	var whatsappBtn = document.getElementById("whatsapp-btn");

	// select the image to share
	// const pinterestImg = document.querySelector(".pinteres-img");
	// get image source to share
	// let postImg = encodeURI(pinterestImg.src);

	// get the customer skills
	var skills = $('#customerSkills').attr('value'); 

	// get the link of the current page
	let postUrl = encodeURI(document.location.href);
	let postTitle = encodeURI("Hi everyone, please checkout this "+skills+" profile on whoSabiWork");
	
	// share content on facebook
	facebookBtn.setAttribute("href", `https://www.facebook.com/sharer.php?u=${postUrl}`);
	// facebookBtn.setAttribute("href", "https://www.facebook.com/sharer.php?u="+postUrl); // ${postUrl}

	// share content on twitter
	twitterBtn.setAttribute("href", `https://twitter.com/share?url=${postUrl}&text=${postTitle}`);

	// share content on whatsapp
	whatsappBtn.setAttribute("href", `https://wa.me/?text=${postTitle} ${postUrl}`);

	// share content on linkedin
	linkedinBtn.setAttribute("href", `https://www.linkedin.com/shareArticle?url=${postUrl}&title=${postTitle}`);

	// share content on pinterest
	pinterestBtn.setAttribute("href", `https://pinterest.com/pin/create/bookmarklet/?url=${postUrl}&description=${postTitle}`);
}

socialMediaFxn();

// check if the appointmen_message id is visible
var appointMessage = document.getElementById("appointment_message");
if (typeof appointMessage !== 'null') {
	// Function to count the number of text in a comment box
	appointMessage.addEventListener("keyup", updateCount);
}

function updateCount() {
  var commentText = document.getElementById("appointment_message").value;
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
