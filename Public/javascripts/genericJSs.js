// This function controls the buttons on the home page for busines
// and user account creation
$(document).ready(function(){
	$(".btnStyle4").click(function(event){
		window.location.href = '/Public/createUserAccount.php';
	});

	$(".btnStyle5").click(function(event){
		window.location.href = '/Public/createBusinessAccount.php';
	});
});


/* // Old function that controlled the closing of hamburger menu
$(document).ready(function(){
	// var arrow = $(".arrow-up");
	var accountInfo = $(".accountInfo");
	var status = false;
	$("#accountInfoMenu").click(function(event){
		event.preventDefault();
		/* if (status == false) {
			// arrow.fadeIn();
			accountInfo.fadeIn();
			status = true;
		} else {
			// arrow.fadeOut();
			accountInfo.fadeOut();
			status = false;
		} */ /*
		accountInfo.fadeIn();
	});

	var navBar = $("#MenuBar1");
	var status1 = false;

	$(".menuNavBtn").click(function(event){
		event.preventDefault();
		// console.log("Button clicked");
		var screenWidth = $(window).width();
		// For mobile and tablet response
		if (screenWidth <= 480) {
			// console.log("screen width is: "+screenWidth);
			if (status1 == false) {
				navBar.fadeIn(); // fadeIn or slideDown
				$("#container").css("top", "215px");
				status1 = true;
			} else {
				navBar.fadeOut(); // fadeOut or slideUp
				$("#container").css("top", "80px");
				status1 = false;
			}
		} else if (screenWidth <= 600) {
			// console.log("screen width is: "+screenWidth);
			if (status1 == false) {
				navBar.fadeIn(); // fadeIn or slideDown
				$("#container").css("top", "147px");
				status1 = true;
			} else {
				navBar.fadeOut(); // fadeOut or slideUp
				$("#container").css("top", "80px");
				status1 = false;
			}
		} else if (screenWidth <= 800) {
			// console.log("screen width is: "+screenWidth);
			if (status1 == false) {
				// opens the navBar
				navBar.fadeIn("slow"); // fadeIn or slideDown
				$("#container").css("top", "113px");
				status1 = true;
			} else {
				// closes the navBar
				navBar.fadeOut("slow"); // fadeOut or slideUp
				$("#container").css("top", "80px");
				status1 = false;
			}			
		} else {
			// console.log("screen width is: "+screenWidth);
			if (status1 == false) {
				navBar.fadeIn(); // fadeIn or slideDown
				$("#container").css("top", "138px");
				status1 = true;
			} else {
				navBar.fadeOut(); // fadeOut or slideUp
				$("#container").css("top", "105px");
				status1 = false;
			}
		}
	});

	/* Get an array / list of div within the div you want to click out that is not
	to be closed when clicked upon */ /*
	var boxArray = [navBar, accountInfo];
	window.addEventListener('mouseup', function(event) {
		for (var i=0; i < boxArray.length; i++) {
			if ((event.target != boxArray[i]) && (event.target.parentNode != boxArray[i])) {
				if (status == false) {
					// arrow.fadeOut();
					accountInfo.fadeOut();
					status = false;
				} else if (status1 == true) {
					var screenWidth = $(window).width();
					// For mobile and tablet response 
					if (screenWidth <= 480) {
						navBar.fadeOut(); // fadeOut or slideUp
						$("#container").css("top", "80px");
						status1 = false;
					} else if (screenWidth <= 600) {
						navBar.fadeOut(); // fadeOut or slideUp
						$("#container").css("top", "80px");
						status1 = false;
					} else if (screenWidth <= 800) {
						// closes the navBar
						navBar.fadeOut(); // fadeOut or slideUp
						$("#container").css("top", "80px");	
						status1 = false;		
					} else {
						navBar.fadeOut(); // fadeOut or slideUp
						$("#container").css("top", "105px");
						status1 = false;
					}	
					// status1 = true;
				}	
			}
		}
	});
}); */

// This controls the navigation bar
// This is for when no user is logged in
$('#menuBtn').click(function(event){
  $('#navUl').slideDown(2000);
  $('#menuBtn').fadeOut();
  $('#cancel').fadeIn();
});

// This is for when no user is logged in
$('#cancel').click(function(event){
	$('#navUl').slideUp(2000);
  $('#menuBtn').fadeIn();	  
  $('#cancel').fadeOut();
});

// This is for when a user is logged in
$('#mobileAccountPreview').click(function(event){
  $('#navUl').slideDown(2000);
  $('#mobileAccountPreview').fadeOut();
  $('#cancel2').fadeIn();
});

// This is for when a user is logged in
$('#cancel2').click(function(event){
	$('#navUl').slideUp(2000);
  $('#mobileAccountPreview').fadeIn();	  
  $('#cancel2').fadeOut();
});

// Open the account preview div with a this function 
function accountPreview() {
	// document.getElementById('accountInfo').style.display = 'block';
	// This can also work in getting the function active
	$('#accountInfo').toggle();
}

/* // Controls the closing of the account preview div when opened
// Get id of the parent div containing the account preview btn
var accountInfoDiv = document.getElementById('accountInfo');
// Get the preview image id
var previewImgId = document.getElementById('avatarPreview2');
// Get the fullname paragraph id
var accountFullnameId = document.getElementById('accountFullname');
// Get the account number paragraph id
var accountNumberId = document.getElementById('accountNumber');
// Get the profile button id
var profileBtnId = document.getElementById('profileBtn');
// Get the logout button id
var logoutBtnId = document.getElementById('logoutBtn');
// Check for a clicked event on the document out side of the account preview div
document.onmouseup = function(div){
	// Check that the allowed ids clicked are the account preview div container and its children divs.
	// Also check that the allowed ids clicked are the image, paragraphs and buttons within the account preview div
	if ((div.target !== accountInfoDiv) && (event.target.parentNode !== accountInfoDiv) && (div.target !== previewImgId)  && (div.target !== accountFullnameId)  && (div.target !== accountNumberId)  && (div.target !== profileBtnId)  && (div.target !== logoutBtnId)) {
		// accountInfoDiv.style.display = 'none';
		// Check if the accountInfo div is displayed
		if (accountInfoDiv.style.display === 'block') {
			$('#accountInfo').css('display', 'none');
		}		
	}
}; */


// This controls the event of the view profile button
function goToProfile(event) {
	event.preventDefault();

	$.ajax({
		// Make the URL global so it can be accessed from all pages
		url: "/Public/PHP-JSON/globalNav_JSON.php",
		dataType: 'json',
		type: 'post',
		data: {action : "profileBtn"},

		success: function(response) {
			if (response.success === true) {
				window.location.href = response.redirectPath;
			}
		}
	});
}

// This controls the event of the logout button
function logoutBtn(event) {
	event.preventDefault();

	$.ajax({
		// Make the URL global so it can be accessed from all pages
		url: "/Public/PHP-JSON/globalNav_JSON.php",
		dataType: 'json',
		type: 'post',
		data: {action : "logoutBtn"},

		success: function(response) {
			if (response.success === true) {
				window.location.href = response.redirectPath;
			}
		}
	});
}



/*
document.getElementById("topContainer").addEventListener('resize', function() {
	alert("topcontainer div was resized");
});
*/

// This function will move the web page down to where photos can be uploaded 
// when clicked
$(document).ready(function(){
	var scrollLink = $('.scroll');
	
	// Perform scrolling
	scrollLink.click(function(e){
		// This will prevent the default behaviour of the website jumping to the div
		e.preventDefault();
		// The idea of animation is to animate a css property
		// specify a css property and the time to animate
		$('body, html').animate({
			// scrollTop looks for the top location of the scroll bar
			// find out how far you are to the location that contains the 
			// 'scroll class tag'
			// 'this.hash' is refering to the id where the scrollbar will me moving to
			// with the offset, we calculate the distance to the div
			scrollTop: $(this.hash).offset().top
		}, 1000);
		
	});
});

/* This is the function scrolls up to the page 
when the button is clicked */
$(document).ready(function(){
	var offset = 250;
	var duration = 500;
	
	// this checks if the scroll bar is not present and hides the button to go to // top of the page
	$(window).scroll(function(){
		// console.log($(this).scrollTop());
		if ($(this).scrollTop() > offset) {
			$('.to-top-div').fadeIn(duration);
		} else {
			$('.to-top-div').fadeOut(duration);
		}
	});
	
	/* $(window).scroll(function(){
		if ($(document).height() > $(window).height()) {
			// has scroll bar
			$('.to-top-div').fadeIn(duration);
		} else {
			// scroll bar not present
			$('.to-top-div').fadeOut(duration);
		}
	}); */
	
	
	$('.to-top').click(function(e){
		e.preventDefault();
		$('body, html').animate({scrollTop: 0}, 1000);
	});
}); 

// This function will move the web page down to where photos can be uploaded 
// when clicked
$(document).ready(function(){
	var scrollLink = $('.scroll');
	
	// Perform scrolling
	scrollLink.click(function(e){
		// This will prevent the default behaviour of the website jumping to the div
		e.preventDefault();
		// The idea of animation is to animate a css property
		// specify a css property and the time to animate
		$('body, html').animate({
			// scrollTop looks for the top location of the scroll bar
			// find out how far you are to the location that contains the 
			// 'scroll class tag'
			// 'this.hash' is refering to the id where the scrollbar will me moving to
			// with the offset, we calculate the distance to the div
			scrollTop: $(this.hash).offset().top
		}, 1000);
		
	});
});

// This function sets a timeout when the message div displays a message on the page
$(document).ready(function(){
	$('.message').fadeIn('slow');
	
	setTimeout(function() {
		$('.message').fadeOut('slow');
		$('#pageTitle').css('margin-top', '0px').delay(5000);
		$('#pageTitle').css('padding-top', '10px').delay(5000);
	}, 10000);
});

// This function sets a timeout when the error div or feedback div displays a message on the page
$(document).ready(function(){
	$('.error, #feedback #feedback2').fadeIn('slow');
	
	setTimeout(function(){
		$('.error, #feedback #feedback2').fadeOut('slow');
		
	}, 15000);
});

/* This function will trigger the default browser button when clicked
virtually by the button holder */
$(document).ready(function(){
	// Without internet exploret support you can use
	// document.querySelectorAll('.fileUploadBtn').forEach()
	// This code is suitable for internet explorer support
	Array.prototype.forEach.call(document.querySelectorAll('.fileUploadBtn'), function(button) {
		// This will get the parent element of the div and then select hidden browser input
		const hiddenInput = button.parentElement.querySelector('.fileUpload');
		const label = button.parentElement.querySelector('.fileUploadLabel');
		const defaultLabelText = "No file(s) selected.";

		// Set defualt text for label
		label.textContent = defaultLabelText;
		label.title = defaultLabelText;

		/* When the button holder is clicked, it will trigger the browser
		file selection window */
		button.addEventListener('click', function() {
			hiddenInput.click();
		});

		hiddenInput.addEventListener('change', function() {
			// console.log(hiddenInput.files);
			// This will be able to get the list of files selected
			// Get an array of list of files and check for the names one after the other
			/* This will work without considering internet explorer
				Array.from(hiddenInput.files).map(function(file) {});
			*/
			const filenameList = Array.prototype.map.call(hiddenInput.files, function(file) {
				return file.name;
			});

			/* This will set the list of files selected seperated by a comma, but it 
			 the file isn't selected or canceled, the default message will be outputted. */
			label.textContent = filenameList.join(', ') || defaultLabelText;
			label.title = label.textContent;
		});
	});
});

// Function for scrolling to the bottom of page
$(document).ready(function() {
	$('#buttonId').click(function() {
		$('html.body').animate({scrollTop: $(document).height()}, 'slow');
		return false;
	});
});

// Function to count the number of text in a comment box
/*
function updateCount1(idVar) {
  var commentText = document.getElementById(idVar).value;
  var charCount = countCharacters(commentText);
  var wordCountBox = document.getElementById(idVar).nextElementSibling.childNodes[1];
  if (wordCountBox.value == "wordCount") {
  	wordCountBox.value	= charCount+"/250";
  }
  
  // wordCountBox.value = charCount+"/250";
  if (charCount > 250) {
    wordCountBox.style.color = "white";
    wordCountBox.style.backgroundColor = "red";
  } else {
    wordCountBox.style.color = "black";
    wordCountBox.style.backgroundColor = "white";
  }
}


function updateCount2() {
	var commentTextArray = [];
	// append objects of ids needed
	commentTextArray.push(document.getElementById("message_content").value);
	commentTextArray.push(document.getElementById("appointment_message").value);
  for (var i = 0; i < commentTextArray.length; i++) {
  	// check if the value in the array is null
  	if (commentTextArray[i] != null) {
  		var charCount = countCharacters(commentText);
		  var wordCountBox = document.getElementById("wordCount");
		  wordCountBox.value = charCount+"/250";		
  	}
  }
  
  if (charCount > 250) {
    wordCountBox.style.color = "white";
    wordCountBox.style.backgroundColor = "red";
  } else {
    wordCountBox.style.color = "black";
    wordCountBox.style.backgroundColor = "white";
  }
}
*/

function countCharacters(textStr) {
   var commentregx = /\s/g;
   var chars = textStr.replace(commentregx, "");
   return chars.length;
}


// This function will create the customers ad to be displayed
function displayAd(customersDetails) {
	// get the div where the ad images will be displayed
	var adContainer1 = document.getElementById("adContainer1");
	// Clear the contents of the previous ads
	adContainer1.innerHTML = "";
	// Set the height of the adContainer to auto
	adContainer1.style.height = "auto";
	// Get the div to output error message
	var messageDiv = document.getElementById("messageDiv");	
	// Clear the contents of the previous message
	messageDiv.innerHTML = "";

	// Use a loop to populate the options for the select menu
	for (var i = 0; i < customersDetails.length; i++) {		
		// Create div element
		var divObj = document.createElement("div");
		// Set div class
		divObj.setAttribute("class", "adContainer");

		// Creat paragraph tag that holds image
		var paraObj = document.createElement("p");
		// Set paragraph class
		paraObj.setAttribute("class", "adImage");
		paraObj.setAttribute("onclick", "redirectTo("+customersDetails[i].customerId+")");

		// Create Div element for image
		var divImgObj = document.createElement("div");
		// Set div image name attribute
		divImgObj.setAttribute("id", "AdImage"+i);
		// Set div image name attribute
		divImgObj.setAttribute("name", "AdImage");
		// Set div image width attribute
		divImgObj.setAttribute("class", "adImgStyle");
		// Set div image background style
		var imgPath = "images/"+customersDetails[i].image_path;
		divImgObj.setAttribute("style", "background: url("+imgPath+"); background-repeat: no-repeat; background-size: cover; background-position: center;");
		// Append div image to paragraph 
		paraObj.appendChild(divImgObj);

		// Append image paragraph to div
		divObj.appendChild(paraObj);

		// Create h1 element object
		var h1Obj = document.createElement("h1");
		// Set h1 class
		h1Obj.setAttribute("class", "adTitle max-lines");
		h1Obj.setAttribute("onclick", "redirectTo("+customersDetails[i].customerId+")");
		h1Obj.innerHTML = customersDetails[i].business_title;
		// Append h1 to anchor 
		divObj.appendChild(h1Obj);

		// Creat paragraph tag for full name
		var para1Obj = document.createElement("p");
		// Set paragraph class
		para1Obj.setAttribute("class", "adContent max-lines");
		// Create i tag 
		var iTagObj = document.createElement("i");
		// Set paragraph class
		iTagObj.setAttribute("class", "far fa-user");
		iTagObj.setAttribute("style", "padding-right:10px;");
		// Append i tag to paragraph
		para1Obj.appendChild(iTagObj);
		para1Obj.append(customersDetails[i].full_name);
		divObj.appendChild(para1Obj);

		// Create address paragraph tag
		var para2Obj = document.createElement("p");
		// Set paragraph class
		para2Obj.setAttribute("class", "adContent max-lines");
		// Create i tag 
		var iTag1Obj = document.createElement("i");
		// Set paragraph class
		iTag1Obj.setAttribute("class", "far fa-address-card fa-lg");
		iTag1Obj.setAttribute("style", "padding-right:10px;");
		// Append i tag to paragraph
		para2Obj.appendChild(iTag1Obj);
		para2Obj.append(customersDetails[i].address);
		divObj.appendChild(para2Obj);

		// Create phone number paragraph tag
		var para3Obj = document.createElement("p");
		// Set paragraph class
		para3Obj.setAttribute("class", "adContent telephone");
		// Create i tag
		var iTag2Obj = document.createElement("i");
		// Set paragraph class
		iTag2Obj.setAttribute("class", "fas fa-phone");
		iTag2Obj.setAttribute("style", "padding-right:10px;");
		// Append i tag to paragraph
		para3Obj.appendChild(iTag2Obj);
		para3Obj.append(customersDetails[i].phone_number);
		divObj.appendChild(para3Obj);

		// Determine if the phone number is validated
		if (parseInt(customersDetails[i].phone_validated) !== 1) {
			// Create validated paragraph tag
			var para4Obj = document.createElement("p");
			// Set paragraph class
			para4Obj.setAttribute("class", "adContent phoneNotValidated");
			para4Obj.innerHTML = "Number not validated";
			divObj.appendChild(para4Obj);
			// var validatedOuput = "<p class='adContent phoneNotValidated' >Number not validated</p>";
		}

		// Create rating div tag
		var ratingDiv = document.createElement("div");
		// Set div class
		ratingDiv.setAttribute("class", "rating jDisabled");
		ratingDiv.setAttribute("data-average", ""+customersDetails[i].rateValue);
		ratingDiv.setAttribute("data-id", ""+customersDetails[i].rateCustomerId);
		divObj.appendChild(ratingDiv);

		adContainer1.appendChild(divObj);
	}

	// Display the ratings of the various users
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


/* Adjust the height of the main div to fit the screen */
window.onload = getMainDivHeight;

function getMainDivHeight() {
	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];
	// console.log("pageName is: "+pageName);

	// Get the various div and window height fro computing
	var windowHeight = window.innerHeight;
	var topContHeight = document.getElementById('topContainer').offsetHeight;
	var contPosition = document.getElementById('container').offsetTop;
	var footerHeight = document.getElementsByClassName('footerStyle')[0].offsetHeight;	
	// Calculate main div height
	var mainDivHeight = windowHeight - (contPosition + footerHeight);
	// Determine the page to execute
	if (pageName === 'loginPage.php') {
		var mainDiv = document.getElementsByClassName('mainLogin')[0];
		// Calculate main div height
		mainDivHeight = mainDivHeight - 42;
	} else if (pageName === 'selectProfileType.php') {
		var mainDiv = document.getElementsByClassName('mainLogin')[0];
	} else if (pageName === 'mobileMarketPage.php') {
		var mainDiv = document.getElementById('mainServicePage');
	} else if (pageName === 'artisanPage.php') {
		var mainDiv = document.getElementById('mainServicePage');
	} else if (pageName === 'servicePage.php') {
		var mainDiv = document.getElementById('mainServicePage');
	} else if (pageName === 'sparePartPage.php') {
		var mainDiv = document.getElementById('mainServicePage');
	} else if (pageName === 'livePhotosFeed.php') {
		var mainDiv = document.getElementById('mainLiveFeed');
	} else if (pageName === 'adminPage.php') {
		var mainDiv = document.getElementById('mainAdminPage');
	} else if (pageName === 'forgotPassword.php') {
		var mainDiv = document.getElementsByClassName('mainLogin')[0];
	} else if (pageName === 'resetPassword.php') {
		var mainDiv = document.getElementsByClassName('mainLogin')[0];
	} else if (pageName === 'loginAdmin.php') {
		var mainDiv = document.getElementsByClassName('mainLogin')[0];
	} else if (pageName === 'userEditPage.php') {
		var mainDiv = document.getElementById('mainUserEditPage');
	} else {
		return false;
	}

	// Set main div height
	mainDiv.style.minHeight = mainDivHeight+"px";	
}

/* function to display error message to the user on the screen */
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

/* function to close the error message modal on the screen */
$('#closeBtn').on('click', function(event) {
	event.preventDefault();
	// close the modal
	$('.messageModal').fadeOut();
	// clear the message content
	$('#messageContent').empty();
});

function closeMsg() {
	// close the modal
	$('.messageModal').fadeOut();
	// clear the message content
	$('#messageContent').empty();
}

// The function generate a random password of specified length
function passwordGen(length) {
	var result = "";
	// These chars contains both numbers from 0 - 9, small letters a - z and capital letters A - Z
	var chars = "Aa0Bb1Cc9Dd2Ee8Ff3Gg7Hh4Ii6Jj5Kk5Ll6Mm4Nn7Oo3Pp8Qq2Rr9Ss1Tt0UuVvWwXxYyZz";
	// This will split the characters into an array
	charArray = chars.split("");
	for (var i = 0; i < length; i++) {
		// This will randomly select the key from the array "charArray" created.
		var rand = charArray[(Math.random() * charArray.length) | 0];
		// This will concatenate the random string using the random character selected.
		result += rand;
	}
	return result;	
}

function initialCaps(string) { 
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function getNameNumber(string) {
	return string.match(/(\d+)/);
}

function redirectTo(customerId) {
	$.ajax({
		url: "./PHP-JSON/viewedCustomer_JSON.php",
		dataType: 'json',
		type: 'post',
		data: {viewedCusId : customerId},

		success: function(response) {
			if (response.success) {
				window.location.href = "./customer/customerHomePage.php?id="+customerId;
			}
		}
	});
}


// Determine the image orientation with the url
function getImgOrientation(url, callback) {
	var img = new Image();
	img.src = url;
	img.onload = function() { 
		callback(this.width, this.height);		
	}		
}


