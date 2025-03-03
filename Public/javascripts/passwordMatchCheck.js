
/* console.log("Print out");
var fullURL = window.location.search.substring(1);
console.log("The page URL is: "+fullURL);

var pagePath = $(location).attr('pathname');
console.log("The page pathname using jquery is: "+pagePath);

var pathName = window.location.href;
console.log("The page href using javascript is: "+pathName);

var pathArray = window.location.pathname.split( '/' );
console.log("The path arrays is: "+pathArray);
console.log("The last variable is: "+pathArray[pathArray.length-1]) */

function closeMessageDiv() {
	var passwordMessage = $('#passwordMessage');
	passwordMessage.fadeOut(3000);
}

function closePasswordMessageDiv() {
	var passwordMessage = $('#passwordMessage');
	passwordMessage.fadeOut(3000);
}

$('#confirm_password_user').on('blur', function() {
	var firstPassword = $('#password_user'),
		confirmPassword = $(this),
		passwordMessage = $('#confirm_password_message');
	
	var passwordPair = {
		firstPassword: firstPassword.val(),
		confirmPassword: confirmPassword.val()
	};
		
	$.ajax({
		type: 'POST',
		url: 'PHP-JSON/passwordMatchCheck_JSON.php',
		dataType: 'json',
		data: passwordPair,
		
		success: function(data) {
			if (data.result === "There was an error") {
				passwordMessage.show();
				passwordMessage.empty();
				firstPassword.css('box-shadow', 'inset 0 0 10px #A51300');
				confirmPassword.css('box-shadow', 'inset 0 0 10px #A51300');
				$.each(data.errors, function(i, error) {
					passwordMessage.append('<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+ error +'</p>');
				});
				// closeDiveTimer = setTimeout('closeMessageDiv()',5000);
			} else if (data.success === true) {
				passwordMessage.show();
				passwordMessage.empty();
				passwordMessage.append('<p style="margin-top:0px; margin-bottom:0px; ">'+data.result+'</p>');
				passwordMessage.css('color', 'green');
				firstPassword.css('box-shadow', 'inset 0 0 10px green');
				confirmPassword.css('box-shadow', 'inset 0 0 10px green');
				// closeDiveTimer = setTimeout('closeMessageDiv()',5000);
			} else {
				// This block notifies if the password does not match
				passwordMessage.show();
				passwordMessage.empty();
				passwordMessage.append('<p style="margin-top:0px; margin-bottom:0px; ">'+data.result+'</p>');
				passwordMessage.css('color', 'red');
				firstPassword.css('box-shadow', 'inset 0 0 10px #A51300');
				confirmPassword.css('box-shadow', 'inset 0 0 10px #A51300');
				// closeDiveTimer = setTimeout('closeMessageDiv()',5000);
			}
		}
	});
});

// Confirm customer password match during creation of account
$('#confirm_password').on('blur', function() {
	var firstPassword = $('#password'),
		confirmPassword = $(this),
		// passwordMessage = $('#passwordMessage');
		passwordMessage = $('#confirm_password_message');
	
	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var fileName = pathArray[pathArray.length-1];
	
	if (fileName === "loginPage.php") {
		var phpURL = 'PHP-JSON/passwordMatchCheck_JSON.php';
	} else if (fileName === "customerEditPage2.php") {
		var phpURL = '../PHP-JSON/passwordMatchCheck_JSON.php';
		firstPassword = $('#edit_password');
	} else if (fileName === "createBusinessAccount.php") {
		var phpURL = 'PHP-JSON/passwordMatchCheck_JSON.php';
	}
	
	var passwordPair = {
		firstPassword: firstPassword.val(),
		confirmPassword: confirmPassword.val()
	};
		
	$.ajax({
		type: 'POST',
		url: phpURL,
		dataType: 'json',
		data: passwordPair,
		
		success: function(data) {
			if (data.result === "There was an error") {
				passwordMessage.show();
				passwordMessage.empty();
				firstPassword.css('box-shadow', 'inset 0 0 10px #A51300');
				confirmPassword.css('box-shadow', 'inset 0 0 10px #A51300');
				$.each(data.errors, function(i, error) {
					passwordMessage.append('<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+ error +'</p>');
				});
				// closeDiveTimer = setTimeout('closeMessageDiv()',5000);
			} else if (data.success === true) {
				passwordMessage.show();
				passwordMessage.empty();
				passwordMessage.append('<p style="margin-top:0px; margin-bottom:0px; ">'+data.result+'</p>');
				passwordMessage.css('color', 'green');
				firstPassword.css('box-shadow', 'inset 0 0 10px green');
				confirmPassword.css('box-shadow', 'inset 0 0 10px green');
				// closeDiveTimer = setTimeout('closeMessageDiv()',5000);
			} else {
				// This block notifies if the password does not match
				passwordMessage.show();
				passwordMessage.empty();
				passwordMessage.append('<p style="margin-top:0px; margin-bottom:0px; ">'+data.result+'</p>');
				passwordMessage.css('color', 'red');
				firstPassword.css('box-shadow', 'inset 0 0 10px #A51300');
				confirmPassword.css('box-shadow', 'inset 0 0 10px #A51300');
				// closeDiveTimer = setTimeout('closeMessageDiv()',5000);
			}
		}
	});	
});

// Confirm the admin password
$('#confirm_password_admin').on('blur', function() {
	var firstPassword = $('#password_admin'),
		confirmPassword = $(this),
		passwordMessage = $('#confirm_password_message');
	
	var passwordPair = {
		firstPassword: firstPassword.val(),
		confirmPassword: confirmPassword.val()
	};
		
	$.ajax({
		type: 'POST',
		url: '../PHP-JSON/passwordMatchCheck_JSON.php',
		dataType: 'json',
		data: passwordPair,
		
		success: function(data) {
			if (data.result === "There was an error") {
				passwordMessage.show();
				passwordMessage.empty();
				firstPassword.css('box-shadow', 'inset 0 0 10px #A51300');
				confirmPassword.css('box-shadow', 'inset 0 0 10px #A51300');
				$.each(data.errors, function(i, error) {
					passwordMessage.append('<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+ error +'</p>');
				});
				// closeDiveTimer = setTimeout('closeMessageDiv()',5000);
			} else if (data.success === true) {
				passwordMessage.show();
				passwordMessage.empty();
				passwordMessage.append('<p style="margin-top:0px; margin-bottom:0px; ">'+data.result+'</p>');
				passwordMessage.css('color', 'green');
				firstPassword.css('box-shadow', 'inset 0 0 10px green');
				confirmPassword.css('box-shadow', 'inset 0 0 10px green');
				// closeDiveTimer = setTimeout('closeMessageDiv()',5000);
			} else {
				// This block notifies if the password does not match
				passwordMessage.show();
				passwordMessage.empty();
				passwordMessage.append('<p style="margin-top:0px; margin-bottom:0px; ">'+data.result+'</p>');
				passwordMessage.css('color', 'red');
				firstPassword.css('box-shadow', 'inset 0 0 10px #A51300');
				confirmPassword.css('box-shadow', 'inset 0 0 10px #A51300');
				// closeDiveTimer = setTimeout('closeMessageDiv()',5000);
			}
		}
	});
});