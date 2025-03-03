function viewedCustomer(customerId) {
	// console.log("The customer's id viewed is: "+customerId);
	var url = "./PHP-JSON/viewedCustomer_JSON.php";
	
	$.post(url, {viewedCusId : customerId}, function(data) {}, 'json');
	return true;
}


// Get photographs of the advocated customers
$(document).ready(function(){
	// console.log("User Id is: " + public_user_id);
	// get the div where the ad images will be displayed
	var adContainer1 = document.getElementById("adContainer1");
	
	/* // set interval for the page to refresh within 5 minutes
	function getLiveFeeds() {	
	}
	// Decleare the function first so that it runs before the timer starts
	getLiveFeeds();
	// Let the timer run every 5 minutes
	// setInterval(getLiveFeeds, 300000); */

	// This will keep track of the offset for when the next set of ad or photos
	// will be displayed
	var offset = 0; var adOffset = 0;
	// This be used once in the beginning of the query before the offset continues
	var limit = 8; var adLimit = 2;
	// Declare variables to control the intermetent display of ads
	// This determines the number of client photos to display before displaying an ad
	var skip = 3; 
	// This track the number of client photos to display 
	var counter = 0;    
	// This track the number of account ad to display
	var tracker = 0;

	$.ajax({
		url: "PHP-JSON/livePhotosFeed_JSON.php",
		method: "POST",
		dataType: 'json',
		data: {
			'offset' : 0,
			'limit' : limit,
			'adOffset' : 0,
			'adLimit' : adLimit
		},

		beforeSend: function() {
			$(".loader").css("display", "flex");
		},

		complete: function() {
			// $(".loader").css("display", "none");
			$(".loader").fadeOut(2000);
		},

		success: function (data) {
			if (data.details) {
				adContainer1.innerHTML = "";
				for (obj in data.details) {
			    var imgPath = "images/"+data.details[obj].imageName;
			    adContainer1.innerHTML += "<div class='adDisplay'><input type='hidden' id='customerId"+data.details[obj].photoId+"' name='customerId' value='"+data.details[obj].customerId+"' /><input type='hidden' name='photoId' id='photoId"+data.details[obj].photoId+"' value='"+data.details[obj].photoId+"' /><img class='adAvatar' src='./images/"+data.details[obj].avatar+"' alt='avatar'/><p class='adBusinessTitle' onclick='redirectTo("+data.details[obj].customerId+");'>"+data.details[obj].businessTitle+"</p><div id='adImgDisplay"+data.details[obj].photoId+"' class='adImgDisplay' style='background: url("+imgPath+"); background-repeat: no-repeat; background-size: cover; background-position: center;' imgurl='"+imgPath+"'></div><button id='adLike"+data.details[obj].photoId+"' class='adLike' name='adLike'><span><i class='fas fa-thumbs-up'></i></span>Likes "+data.details[obj].numLikes+"</button><button id='adComment"+data.details[obj].photoId+"' class='adComment' name='adComment'><i class='fas fa-comment'></i>Comments "+data.details[obj].numComments+"</button><p id='adCaption"+data.details[obj].photoId+"'  class='adCaption'>"+data.details[obj].caption+"</p><p class='adDate'>"+data.details[obj].dateCreated+"</p></div>";
			    if (data.details[obj].photoLiked === 'yes') {
		        $("#adLike"+data.details[obj].photoId).css('color', '#A51300');
			    }
			    // Increment counter for number of client photos displayed
			    counter++;
			    if (counter > skip) {
          	// Display the photographs of business clients for adverts
            for (obj in data.cusAdverts) {
            	if (data.cusAdverts[obj].phone_validated == 1) {
                var validatedOuput = "";
	            } else {
                var validatedOuput = "<p class='adContent phoneNotValidated' >Number not validated</p>";
	            }
	            var imgPath = "images/"+data.cusAdverts[obj].image_path;
	            adContainer1.innerHTML += '<div class="adContainer" ><p class="adImage" onclick="redirectTo('+data.cusAdverts[obj].customerId+')"><div id="AdImage'+obj+'" name="AdImage" style="background: url('+imgPath+'); background-repeat: no-repeat; background-size: cover; background-position: center; width: 300px; height: 300px;" ></div></p><h1 class="adTitle max-lines" onclick="redirectTo('+data.cusAdverts[obj].customerId+')">'+data.cusAdverts[obj].business_title+'</h1><p class="adContent max-lines"><i class="far fa-user" style="padding-right:10px;"></i>'+data.cusAdverts[obj].full_name+'</p><p class="adContent max-lines"><i class="far fa-address-card fa-lg" style="padding-right:10px;"></i>'+data.cusAdverts[obj].address+'</p><p class="adContent telephone"><i class="fas fa-phone" style="padding-right:10px;"></i>'+data.cusAdverts[obj].phone_number+'</p>'+validatedOuput+'<div class="rating jDisabled" data-average="'+data.cusAdverts[obj].rateValue+'" data-id="'+data.cusAdverts[obj].rateCustomerId+'" ></div></div>';
	            // initialize the rating
	            getCustomerRating();
	            // delete array position
	            // Due to the break in the for loop, the array will aways start from
	            // zero after
	            data.cusAdverts.shift();
              break;
            }
            counter = 0;
			    }					
				}
				// Increment the offset after it is used by the limit
				offset += limit; 
				adOffset += adLimit; 
			} else {
				// adContainer1.innerHTML += "<div class='adDisplay'>Advocate an Artisan to see their updates.</div>";
			}
		}
	});

	// Used to check if proceed is valid to control multiple ajax request
	var proceed = true;
	// This will listen for the event when the scroll bar reaches the bottom
	window.addEventListener("scroll", function () {
		if ((window.innerHeight + window.pageYOffset)	>= document.body.offsetHeight) {
			if (proceed === true) {
				// Ensure only 1 page can load at once
				proceed = false;	
				$.ajax({
					url: "PHP-JSON/livePhotosFeed_JSON.php",
					method: "POST",
					dataType: 'json',
					data: {
						'offset' : offset,
						'limit' : limit,
						'adLimit' : adLimit,
						'adOffset' : adOffset,
					},

					beforeSend: function() {
						$(".loader").css("display", "flex");
					},

					complete: function() {
						// $(".loader").css("display", "none");
						$(".loader").fadeOut(2000);
					},

					success: function (data) {
						if (data.details) {
							for (obj in data.details) {
						    var imgPath = "images/"+data.details[obj].imageName;
						    adContainer1.innerHTML += "<div class='adDisplay'><input type='hidden' id='customerId"+data.details[obj].photoId+"' name='customerId' value='"+data.details[obj].customerId+"' /><input type='hidden' name='photoId' id='photoId"+data.details[obj].photoId+"' value='"+data.details[obj].photoId+"' /><img class='adAvatar' src='./images/"+data.details[obj].avatar+"' alt='avatar'/><p class='adBusinessTitle' onclick='redirectTo("+data.details[obj].customerId+");' >"+data.details[obj].businessTitle+"</p><div id='adImgDisplay"+data.details[obj].photoId+"' class='adImgDisplay' style='background: url("+imgPath+"); background-repeat: no-repeat; background-size: cover; background-position: center;' imgurl='"+imgPath+"'></div><button id='adLike"+data.details[obj].photoId+"' class='adLike' name='adLike'><span><i class='fas fa-thumbs-up'></i></span>Likes "+data.details[obj].numLikes+"</button><button id='adComment"+data.details[obj].photoId+"' class='adComment' name='adComment'><i class='fas fa-comment'></i>Comments "+data.details[obj].numComments+"</button><p id='adCaption"+data.details[obj].photoId+"'  class='adCaption'>"+data.details[obj].caption+"</p><p class='adDate'>"+data.details[obj].dateCreated+"</p></div>";
						    if (data.details[obj].photoLiked === 'yes') {
					        $("#adLike"+data.details[obj].photoId).css('color', '#A51300');
						    }
						    // Increment counter for number of client photos displayed
						    counter++;
						    if (counter > skip) {
			          	// Display the photographs of business clients for adverts
			            for (obj in data.cusAdverts) {
			            	if (data.cusAdverts[obj].phone_validated == 1) {
			                var validatedOuput = "";
				            } else {
			                var validatedOuput = "<p class='adContent phoneNotValidated' >Number not validated</p>";
				            }
				            var imgPath = "images/"+data.cusAdverts[obj].image_path;
				            adContainer1.innerHTML += '<div class="adContainer" ><p class="adImage" onclick="redirectTo('+data.cusAdverts[obj].customerId+')"><div id="AdImage'+obj+'" name="AdImage" style="background: url('+imgPath+'); background-repeat: no-repeat; background-size: cover; background-position: center; width: 300px; height: 300px;" ></div></p><h1 class="adTitle max-lines" onclick="redirectTo('+data.cusAdverts[obj].customerId+')">'+data.cusAdverts[obj].business_title+'</h1><p class="adContent max-lines"><i class="far fa-user" style="padding-right:10px;"></i>'+data.cusAdverts[obj].full_name+'</p><p class="adContent max-lines"><i class="far fa-address-card fa-lg" style="padding-right:10px;"></i>'+data.cusAdverts[obj].address+'</p><p class="adContent telephone"><i class="fas fa-phone" style="padding-right:10px;"></i>'+data.cusAdverts[obj].phone_number+'</p>'+validatedOuput+'<div class="rating jDisabled" data-average="'+data.cusAdverts[obj].rateValue+'" data-id="'+data.cusAdverts[obj].rateCustomerId+'" ></div></div>';
				            // initialize the rating
				            getCustomerRating();
				            // delete array position
				            // Due to the break in the for loop, the array will aways start from
				            // zero after
				            data.cusAdverts.shift();
			              break;
			            }
			            counter = 0;      
						    }
							}
							// Increment the offset after it is used
							offset += limit; 
							adOffset += adLimit; 						
						} else {
							// adContainer1.innerHTML += "<div class='adDisplay'>Advocate an Artisan to see their updates.</div>";
						}
						proceed = true
					}
				});
			}
		}
	});
});

function getCustomerRating() {
	// Display the ratings of the various users
	// this has to be declared after the rating class has been initialized
	$(document).ready(function(){
		$(".rating").jRating({
			// decimalLength : 1, // number of decimal in the rate
			rateMax : 5, // maximal rate - integer from 0 to 9999 (or more)
			// phpPath: 'libs/rating.php',
			bigStarsPath : 'javascripts/jRating/jquery/icons/stars.png', // path of the icon stars.png
			smallStarsPath : 'javascripts/jRating/jquery/icons/small.png', // path of the icon small.png
			
			// canRateAgain : true,
			// nbRates : 3
		});
	});
}


// This controls the behaviour when the button is clicked to close the modal
$(document).on('click', '.close-btn', function() {
	$('.bg-modal').fadeOut("slow");
	// document.querySelector('.bg-modal').style.display = 'none';
	
	// Close the caption buttons and div after the modal is closed.
	// resetCaptionBtnAndDiv();
});

// This controls the behaviour when the button is clicked to close the modal that shows sign in or sign up
$(document).on('click', '#closeSignInModal', function() {
	$('#modalSignIn').fadeOut("slow");
});


// These values will be gotten when any image is selected. These properties will be be used in other functions
var photoIdVal;
var customerIdVal;
var imageDivId;
var idNumber;
// This controls the response of when a like button is clicked
var body = document.getElementsByTagName("body")[0];
body.addEventListener('click', function(e){
	var item = e.target;
	if (item.tagName == "BUTTON" && item.name == "adLike") {
		// Get image id
		var likeId = item.id;
		// Extract the number in the id to use for the caption id
		photoIdVal = likeId.substring(6);
		// console.log("The id number of the image is: "+photoIdVal);
		var customerIdName = "customerId"+photoIdVal;
		// use the customerIdName to get the image path 
		customerIdVal = $("#"+customerIdName).attr("value");

		$.ajax ({
			url: './PHP-JSON/likeComment_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {photoId: photoIdVal, customerId: customerIdVal, like: "likePhoto"},
			
			success: function(data) {
				// Get the number of likes and update the webpage
				if (data.result === "liked") {					
					$("#"+likeId).html("<span style='color: #A51300;'><i class='fas fa-thumbs-up'></i>Like ("+data.numLike+")</span>");
				} else if (data.result === "not logged in") {
					// Inform the user to log in or sign up
					$("#modalSignIn").css('display', 'flex');
				}
			}
		});
	}
});


// This controls the response of when a comment is clicked to project it on a modal
// var body = document.getElementsByTagName("body")[0];
body.addEventListener('click', function(e){
	var item = e.target;
	if (item.tagName == "BUTTON" && item.name == "adComment") {
		// Get image id
		commentId = item.id;
		// Extract the number in the id to use for the caption id
		photoIdVal = commentId.substring(9);
		
		captionId = "adCaption"+photoIdVal;
		imageNameId = "adImgDisplay"+photoIdVal;
		// use the image id to get the image path
		var img_path = $("#"+imageNameId).attr("imgurl");
		// use the caption id to get the caption of the image from the hidden properties of the photo in the gallery
		var caption = $("#"+captionId).text();

		var customerIdName = "customerId"+photoIdVal;
		// use the customerIdName to get the customer id
		customerIdVal = $("#"+customerIdName).attr("value");
		
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
		
		// Get all the comments and replies of the image selected
		$.ajax ({
			url: './PHP-JSON/photoCommentsAndReplies_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {photoId: photoIdVal, customerId: customerIdVal},
			
			success: function(data) {
				// append the total number of comments
				$('#totalComments').html("");
				if (data.totalComments) {
					$('#totalComments').append(data.totalComments+" comment(s)");
				} else {
					$('#totalComments').append("No comment");
				}
				$('#photoComments').html("");
				// append the comment to the top of the div as the most recent comment on the customer
				var i = 0;
				for (var obj in data.comments) {
					$('#photoComments').append("<div id='comment"+photoIdVal+"_"+i+"' class='comment'><div id='commentBox"+photoIdVal+"_"+i+"' class='commentBox'><div id='authorDate"+photoIdVal+"_"+i+"' class='authorDate'><div id='author"+photoIdVal+"_"+i+"' class='author' >"+data.comments[obj].author+"</div> <div id='meta-info"+photoIdVal+"_"+i+"' class='meta-info' >"+data.comments[obj].dateCreated+"</div> </div> <div id='commentBody"+photoIdVal+"_"+i+"' class='commentBody' commentId='"+data.comments[obj].commentId+"' >"+data.comments[obj].comment+"</div></div><button class='replyBtn btnStyle3' id='replyBtn"+photoIdVal+"_"+i+"' onclick='reply(this)'>Reply Comment</button></div>");
					
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
});

/* controls the comment submit button when clicked */
$(document).ready(function(){
	// Use this variable for increment when the submit button is clicked
	// It is used to increment the number of comment div to be created when the comment button is clicked
	var j = 0;
	$('#submitComment').click(function(){
		j++;
		// Get the input from the textarea
		var comment = $('#commentTextarea').val();		
		// Get the customers id
		var customerIdName = "customerId"+photoIdVal;
		// use the customerIdName to get the image path 
		customerIdVal = $("#"+customerIdName).attr("value");

		
		$.ajax({
			url: "./PHP-JSON/likeComment_JSON.php", 
			type: "POST",
			dataType: 'json',
			data: {comment: comment, customerId: customerIdVal, photoId: photoIdVal},
			success: function(data) {
				if (data.comment) {
					// Update the number of comments on the user
					$('#totalComments').text(data.totalComments+" comments");
					
					// prepend the comment to the top of the div as the most recent comment on the customer
					$('#photoComments').prepend("<div id='comment"+photoIdVal+"_"+j+"' class='comment'><div id='commentBox"+photoIdVal+"_"+j+"' class='commentBox'><div id='authorDate"+photoIdVal+"_"+j+"' class='authorDate'><div id='author"+photoIdVal+"_"+j+"' class='author' >"+data.author+"</div> <div id='meta-info"+photoIdVal+"_"+j+"' class='meta-info' >"+data.created+"</div> </div> <div id='commentBody"+photoIdVal+"_"+j+"' class='commentBody' commentId='"+data.commentId+"' >"+data.comment+"</div></div><button class='replyBtn btnStyle3' id='replyBtn"+photoIdVal+"_"+j+"' onclick='reply(this)'>Reply Comment</button></div>");
					
					// clear the textarea of the input field for the comment box
					$('#commentTextarea').val("");
				} else {
					if (data.errors && data.message) {
						$('#feedback').append("<p>"+data.message+"</p>");
					} else {
						$('#feedback').text("");
						if (data.validate_errors.comment) {
							$('#feedback').append("<p>"+data.validate_errors.comment+"</p>");
						}
						if (data.validate_errors.comment_err_long) {
							$('#feedback').append("<p>"+data.validate_errors.comment_err_long+"</p>");
						}
						if (data.validate_errors.comment_comment_error) {
							$('#feedback').append("<p>"+data.validate_errors.comment_comment_error+"</p>");
						}
					}
				}
				// Append the total number of comments on the photo ad that was clicked
				$('#adComment'+photoIdVal).html("<i class='fas fa-comment'></i>Comments "+data.totalComments);
			}
		});
	});
});

/* This function will generate a reply div textarea and 
button an inset it after the reply comment button */
function reply(caller) {
	var id = caller.id;
	var numIndex = id.match(/\d+/);
	var uniqueId = id.substring(numIndex['index']);

	var newDivIdName = "replyDiv"+uniqueId;
	var newDivId = document.getElementById(newDivIdName);
	if (!newDivId) {
		var htmlContent = '<div id="replyDiv'+uniqueId+'" class="replyDiv"><textarea id="replyTextarea'+uniqueId+'" class="replyTextarea" name="message_content" rows="1"></textarea><button id="submitReply'+uniqueId+'" class="submitReply btnStyle3" onclick="submitReply(this)">Reply</button><button id="cancelReply'+uniqueId+'" class="submitReply btnStyle3" onclick="cancelReply(this)">Cancel</button></div>';		
		$(htmlContent).insertAfter($(caller));
	} else {
		$('#'+newDivIdName).css('display', 'block');
	}
}

// Hide the reply div container
function cancelReply(caller) {
	var id = caller.id;
	// get the array index of the appearance of the first number
	var numIndex = id.match(/\d+/);
	var uniqueId = id.substring(numIndex['index']);
	
	$('#replyDiv'+uniqueId).css('display', 'none');
}

/* This method will create a new reply div container 
and appends it at the end of the last element in the div */
function reply1(caller) {
	var id = caller.id;
	var numIndex = id.match(/\d+/);
	var uniqueId = id.substring(numIndex['index']);

	var newDivIdName = "replyDiv"+uniqueId;
	var newDivId = document.getElementById(newDivIdName);
	if (!newDivId) {
		var htmlContent = '<textarea id="replyTextarea'+uniqueId+'" class="replyTextarea" name="message_content" rows="1"></textarea><button id="submitReply'+uniqueId+'" class="submitReply btnStyle3" onclick="submitReply(this)">Reply</button><button id="cancelReply'+uniqueId+'" class="submitReply btnStyle3" onclick="cancelReply(this)">Cancel</button>';
		// Create div
		var newDiv = document.createElement('div');
		// add id and class to the new div
		newDiv.id = "replyDiv"+uniqueId;
		newDiv.className = "replyDiv";
		newDiv.innerHTML = htmlContent;
		var parentId = document.getElementById('comment'+uniqueId);
		parentId.appendChild(newDiv);
	}	
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
	// Get the input from the textarea
	var reply = $('#replyTextarea'+idIndex).val();
	
	$.ajax({
		url: "./PHP-JSON/likeComment_JSON.php", 
		type: "POST",
		dataType: 'json',
		data: {commentId: commentId, reply: reply, customerId: customerIdVal, photoId: photoIdVal},
		success: function(data) {
			if (data.reply) {
				var htmlContent = "<div id='replyContainer' class='replycontainer'><div id='replyComment' class='replyComment'><div id='authorDate' class='authorDate'><div id='replyAuthor' class='author'>"+data.author+"</div><div id='meta-info' class='meta-info'>"+data.created+"</div></div><div id='commentBody' class='commentBody' replyId='"+data.replyId+"'>"+data.reply+"</div></div></div>";
				$(htmlContent).insertAfter($("#replyBtn"+idIndex));

				// clear the textarea
				$('#replyTextarea'+idIndex).val("");

				// Hide the reply div after displaying a message
				$('#replyDiv'+idIndex).css('display', 'none');
			} else {
				if (data.errors && data.message) {
					$('#feedback').append("<p>"+data.message+"</p>");
				} else {
					$('#feedback').text("");
					if (data.validate_errors.reply) {
						$('#feedback').append("<p>"+data.validate_errors.reply+"</p>");
					}
					if (data.validate_errors.reply_err_long) {
						$('#feedback').append("<p>"+data.validate_errors.reply_err_long+"</p>");
					}
					if (data.validate_errors.reply_comment_error) {
						$('#feedback').append("<p>"+data.validate_errors.reply_comment_error+"</p>");
					}
				}					
			}
		}
	});	
}