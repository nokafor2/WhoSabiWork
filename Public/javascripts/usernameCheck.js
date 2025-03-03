function stopUsernameCheck() {
	clearTimeout(myTimer);
	var usernameMessage = document.getElementById("username_message");
	usernameMessage.style.display = 'none';
}

function closeMessageDiv() {
	var usernameMessage = $('#usernameMessage');
	// usernameMessage.fadeOut(3000);
	usernameMessage.css("display", "none");
}

var myTimer;
function usernameCheck(){
	// the username from the create user account page is received. username_user
	var usernameInput = document.getElementById("username_user").value;
	var usernameMessage = document.getElementById("username_message");

	// Create our XMLHttpRequest object
	var hr = new XMLHttpRequest();
	
	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];
	// console.log("The name of the page is: "+pageName);
	if ( pageName === 'userEditPage.php' ) {
		// Create some variables we need to send to our PHP file 
		var url = "../PHP-JSON/usernameCheck_JSON.php";
	} else {
		// Create some variables we need to send to our PHP file 
		var url = "PHP-JSON/usernameCheck_JSON.php";
	}
	
	hr.open("POST", url, true);
	
	// Set content type header information for sending url encoded variables in the request
	// Since we are using a POST request, the content type is set to: x-www-form-urlencoded
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	// Access the onreadystatechange event for the XMLHttpRequest object
	hr.onreadystatechange = function() {
		if (hr.readyState == 4 && hr.status == 200) {
			// This is information from the server side through the AJAX request. This will give the HttpResponseText
			// JSON.parse() function will be used instead
			// The JSON.parse() function helps get it ready for javascript parsing. 
			var data = JSON.parse(hr.responseText);
			// console.log(data);
			
			if (data.validateErrors) {
				usernameMessage.style.display = 'block';
				// Concatenate the errors
				var error = "";
				for (var obj in data.validateErrors) {
					error += '<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+data.validateErrors[obj]+'</p>';
				}
				usernameMessage.innerHTML = '<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+"No username was entered. "+'</p>';
				usernameMessage.innerHTML = error;
				$('#username_user').css('box-shadow', 'inset 0 0 10px #A51300');
				// closeDiveTimer = setTimeout('closeMessageDiv()', 10000);
			} else if (data.success === false) {
				usernameMessage.style.display = 'block';
				usernameMessage.innerHTML = '<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+"The username already exist, please try another one. "+'</p>';
				$('#username_user').css('box-shadow', 'inset 0 0 10px #A51300');
				// closeDiveTimer = setTimeout('closeMessageDiv()', 10000);
			} else if (data.success === true && data.exist === false) {
				usernameMessage.style.display = 'block';
				usernameMessage.innerHTML = '<p style="color:green; margin-top:0px; margin-bottom:0px; ">'+"The username does not exist, it can be used. "+'</p>';
				$('#username_user').css('box-shadow', 'inset 0 0 10px green');
				// closeDiveTimer = setTimeout('closeMessageDiv()', 10000);
			}			
		}
	}
	
	// Send the data to PHP now... and wait for response to update the status div
	// When using post, a dynamic data can be sent through the send method. Here the variable is manually sent in.
	hr.send("username="+usernameInput); // Actually execute the request
	
	// You could use an animated.gif loader here while you wait for data from the server.
	usernameMessage.innerHTML = "requesting...";
	
	// This executes this function after 2 seconds.
	// myTimer = setTimeout('usernameCheck()',2000);
}



function stopUsernameBussAccCheck() {
	clearTimeout(controlTimer);
	var usernameMessage = document.getElementById("username_message");
	usernameMessage.style.display = 'none';
}

var controlTimer;
function usernameBussAccCheck(){
	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];
	// console.log("The name of the page is: "+pageName);

	if ( pageName === 'customerEditPage2.php' ) {
		// the username from the create user account page is received. username_user
		var usernameInput = document.getElementById("edit_username").value;
	} else {
		// the username from the create user account page is received. username_user
		var usernameInput = document.getElementById("username").value;
	}
	
	var usernameMessage = document.getElementById("username_message");

	// Create our XMLHttpRequest object
	var hr = new XMLHttpRequest();
	
	if ( pageName === 'customerEditPage2.php' ) {
		// Create some variables we need to send to our PHP file 
		var url = "../PHP-JSON/usernameCheck_JSON.php";
	} else {
		// Create some variables we need to send to our PHP file 
		var url = "PHP-JSON/usernameCheck_JSON.php";
	}
	
	hr.open("POST", url, true);
	
	// Set content type header information for sending url encoded variables in the request
	// Since we are using a POST request, the content type is set to: x-www-form-urlencoded
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	// Access the onreadystatechange event for the XMLHttpRequest object
	hr.onreadystatechange = function() {
		if (hr.readyState == 4 && hr.status == 200) {
			// This is information from the server side through the AJAX request. This will give the HttpResponseText
			// JSON.parse() function will be used instead
			// The JSON.parse() function helps get it ready for javascript parsing. 
			var data = JSON.parse(hr.responseText);

			if (data.validateErrors) {
				usernameMessage.style.display = 'block';
				// Concatenate the errors
				var error = "";
				for (var obj in data.validateErrors) {
					error += '<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+data.validateErrors[obj]+'</p>';
				}
				// usernameMessage.innerHTML = '<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+"No username was entered. "+'</p>';
				usernameMessage.innerHTML = error;
				$('#username').css('box-shadow', 'inset 0 0 10px #A51300');
				// Let the correction persist for the customerEditPage
				if ( pageName !== 'customerEditPage2.php' ) {
					// closeDiveTimer = setTimeout('closeMessageDiv()', 10000);
				}
			} else if (data.success === false) {
				usernameMessage.style.display = 'block';
				usernameMessage.innerHTML = '<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+"The username already exist, please try another one. "+'</p>';
				$('#username').css('box-shadow', 'inset 0 0 10px #A51300');
				// Let the correction persist for the customerEditPage
				if ( pageName !== 'customerEditPage2.php' ) {
					// closeDiveTimer = setTimeout('closeMessageDiv()', 10000);
				}
			} else if (data.success === true && data.exist === false) {
				usernameMessage.style.display = 'block';
				usernameMessage.innerHTML = '<p style="color:green; margin-top:0px; margin-bottom:0px; ">'+"The username does not exist, it can be used. "+'</p>';
				$('#username').css('box-shadow', 'inset 0 0 10px green');
				// Let the correction persist for the customerEditPage
				if ( pageName !== 'customerEditPage2.php' ) {
					// closeDiveTimer = setTimeout('closeMessageDiv()', 10000);
				}
			}			
		}
	}
	
	// Send the data to PHP now... and wait for response to update the status div
	// When using post, a dynamic data can be sent through the send method. Here the variable is manually sent in.
	hr.send("username="+usernameInput); // Actually execute the request
	
	// You could use an animated.gif loader here while you wait for data from the server.
	usernameMessage.innerHTML = "requesting...";
	
	// This function is not used because the mode of username check has been changed.
	// This executes this function after 2 seconds.
	// controlTimer = setTimeout('usernameBussAccCheck()',2000);
}


// Check for admin username
function adminUsernameCheck(){
	// the username from the loginAdmin page is received.
	var usernameInput = document.getElementById("username_admin").value;
	var usernameMessage = document.getElementById("username_message");

	$.ajax({
		url: "../PHP-JSON/usernameCheck_JSON.php",
		type: 'POST',
		dataType: 'json',
		data: {username: usernameInput, account: "admin"},

		success: function(data) {
			if (data.validateErrors) {
				usernameMessage.style.display = 'block';
				// Concatenate the errors
				var error = "";
				for (var obj in data.validateErrors) {
					error += '<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+data.validateErrors[obj]+'</p>';
				}
				usernameMessage.innerHTML = '<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+"No username was entered. "+'</p>';
				usernameMessage.innerHTML = error;
				$('#username_admin').css('box-shadow', 'inset 0 0 10px #A51300');
				closeDiveTimer = setTimeout('closeMessageDiv()', 10000);
			} else if (data.success === false) {
				usernameMessage.style.display = 'block';
				usernameMessage.innerHTML = '<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+"The username already exist, please try another one. "+'</p>';
				$('#username_admin').css('box-shadow', 'inset 0 0 10px #A51300');
				// closeDiveTimer = setTimeout('closeMessageDiv()', 10000);
			} else if (data.success === true && data.exist === false) {
				usernameMessage.style.display = 'block';
				usernameMessage.innerHTML = '<p style="color:green; margin-top:0px; margin-bottom:0px; ">'+"The username does not exist, it can be used. "+'</p>';
				$('#username_admin').css('box-shadow', 'inset 0 0 10px green');
				closeDiveTimer = setTimeout('closeMessageDiv()', 10000);
			}
		}
	});
}