
$("document").ready(function() {
	$(document).ajaxStart(function() {
		console.log("AJAX starting");
	});
	
	$(document).ajaxStop(function() {
		console.log("AJAX request ended.");
	});
	
	$(document).ajaxSend(function(evt, jqXHR, options) {
		console.log("About to request data...");
	});
	
	$(document).ajaxComplete(function(evt, jqXHR, options) {
		console.log("Everything's finished!");
	});
	
	$(document).ajaxError(function(evt, jqXHR, settings, err) {
		console.log("Hmmm. Seems like there was a problem: " + err);
	});
	
	$(document).ajaxSuccess(function(evt, jqXHR, options) {
		console.log("Looks like everything worked!");
	});
	
});

var randomString;
var getSMSCodeTimer;
$('#verifyNumber').on('click', function() {
	// Get the phone number to verify
	if ($('#phone_number_user').val() !== undefined) {
		var phoneNumber = $('#phone_number_user').val();
	} else {
		var phoneNumber = $('#business_phone_number').val();
	}
	// console.log("phone number object is: "+phoneNumber);
	var confirmationCodeDiv = $('#confirmationCodeDiv');
	// validate the phone number is acceptable
	var messageUpdate = $('#messageUpdate');
	// messageUpdate.append(validateNumber(phoneNumber));
	// console.log("The length of the phone number is: "+phoneNumber.length);
	var errors = validateNumber(phoneNumber);
	if (errors.length > 0) {
		messageUpdate.empty();
		$.each(errors, function(i, error){
			messageUpdate.append('<li>'+error+'</li>');
		});
	} else {
		messageUpdate.empty();
		messageUpdate.append('<p style="color:green; margin:0px;">The phone number is valid.</p>');
		// Generate six alpha numeric characters
		randomString = makeRandomString(6);
		// console.log("The generated random string is: "+randomString);
		
		var dataVariables = {
			phoneNumber: phoneNumber,
			smsCode: randomString
		};
		
		// Jquery ajax method will be used to parse the contents.
		$.ajax({
			url: 'PHP-JSON/sendSMSCode_JSON.php', 
			dataType: 'json', // specify the data type, however the function is smart enough to figure that out
			type: 'POST',    // the type of request that will be made to the PHP file
			data: dataVariables,    // You can parse a data here or a serialized content here
			
			// This is a success call back function, data from xwireless.net API will be returned here
			success: function(data) {
				// console.log(data.msg_id);
				if ((data !== null) && (data.msg_id)) {
					messageUpdate.empty();
					messageUpdate.append('<p style="color:green; margin:0px;">Please use the confrimation code sent to your phone number to complete your verification.</p>');
					
					// getSMSCodeTimer = setInterval('getSMSVerificationCode()', 2000);
					// console.log("The set interval is: "+getSMSCodeTimer);
					getSMSVerificationCode(35);
				} else {
					// Check for the condition if data is not retrieved or is null
					messageUpdate.empty();
					messageUpdate.append('<p style="color:red; margin:0px;">An error occured. The SMS verification code could not be sent, please check your internet.</p>');
					// Send an error message to the PHP session through Ajax, to notify validation
					failedValidation();
					
					confirmationCodeDiv.fadeOut(15000);
				}
				
			}
			// Check for the condition if data is not retrieved or is null
			// .error(function(){})
		});
	}
	
	$('#confirmationCodeDiv').show();
	// return false so that the function will not submit be itself
	return false;
});

/* $('#enterCode').on('click', function() {
	// Perform the verification of the 
	// $('#confirmationCodeDiv').fadeOut(5000);
	closeDivTimer = setTimeout('closeConfirmationDiv()', 5000);
}); */

function closeConfirmationDiv() {
	var confirmationCodeDiv = $('#confirmationCodeDiv');
	var verifyNumber = $('#verifyNumber');
	// verifyNumber.fadeOut(3000);
	confirmationCodeDiv.fadeOut(3000);
}

function endTimer() {
	clearTimeout(getSMSCodeTimer);		
}

var failedAttempts = 0;
// takes in the number of seconds to get wait for validation.
function getSMSVerificationCode(timer) {
	var messageUpdate = $('#messageUpdate');
	console.log("The count down seconds now is: "+timer);
	
	// Check if the timer count down has elapsed
	if (timer < 0) {
		getSMSCodeTimer = clearTimeout(getSMSCodeTimer);
		console.log("The clear timeout is: "+getSMSCodeTimer);
		messageUpdate.empty();
		messageUpdate.append('<p style="color:red; margin:0px;">You are out of time to continue verification. Pleas try again later.</p>');
		// endTimer();
		//messageUpdate.delay(3000);
		closeDivTimer = setTimeout('closeConfirmationDiv()', 5000);
		return false;      // End the function like this so that the function will not continue running.
	} else {
		$('#enterCode').on('click', function() {
			var submittedCode = $('#verifyCode').val();
			// Later you can validate a code was entered
			if (submittedCode !== '') {
				if (submittedCode === randomString) {
					// Display the submit button
					/* 
					if ($('#phone_number_user').val() !== undefined) {
						$('#user_register').show(); // Display the user register button
					} else {
						$('#Submit').show(); // Display the business register button
					} */
					messageUpdate.empty();
					messageUpdate.append('<p style="color:green; margin:0px;">Hurray! Your phone number has been verified.</p>');
					// clearInterval(getSMSCodeTimer);
					// console.log("The cleared interval is: "+getSMSCodeTimer);
					getSMSCodeTimer = clearTimeout(getSMSCodeTimer);
					// console.log("The cleared timeout is: "+getSMSCodeTimer);
					closeDivTimer = setTimeout('closeConfirmationDiv()', 2000);
					return false;
				} else {
					// Include an error in the PHP validation.
					
					failedAttempts++;
					messageUpdate.empty();
					messageUpdate.append('<p style="color:red; margin:0px;">There was no match.</p>');
					/* if (failedAttempts > 3) {
						// Include an error in the PHP validation.
						
						messageUpdate.empty();
						messageUpdate.append('<p style="color:red; margin:0px;">You have elapsed your trial. Try again later.</p>');	
						// messageUpdate.delay(3000);
						// closeDivTimer = setTimeout('closeConfirmationDiv()', 2000);
					} 
					console.log("The number of failed attempts is: "+failedAttempts */
				}
			}
		});
	}
	
	timer--;
	getSMSCodeTimer = setTimeout('getSMSVerificationCode('+timer+')', 1000);
	// console.log("The set timeout is: "+getSMSCodeTimer);
}

function failedValidation() {
	var dataVariable = {phoneValidation: "Phone verification failed."};
	
	$.ajax({
		url: 'PHP-JSON/phoneValidation_JSON.php', 
		dataType: 'json', // specify the data type, however the function is smart enough to figure that out
		type: 'POST',    // the type of request that will be made to the PHP file
		data: dataVariable,    // You can parse a data here or a serialized content here
		
		success: function(data) { 
			console.log(data);
		}
	});
}

function validateNumber(phoneNumber) {
	// var errors = new Array();
	var errors = [];
	
	var i = 0;
	if (phoneNumber == "") {
		errors[i] = "Please enter a phone number.";
		i++;
		// return errors;
		// return "Please enter a phone number";
	} 
	if (phoneNumber.length < 11) {
		errors[i] = "The phone number is not complete.";
		i++;
		// return errors;
	}
	if (phoneNumber.length > 11) {
		errors[i] = "The phone number is too long.";
		i++;
		// return errors;
	}
	if (isNaN(phoneNumber)) {
		errors[i] = "This is an invalid number.";
		i++;
	}
	return errors;
}

function makeRandomString(stringSize) {
	var randomString = "";
	var randomeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	for (var i=0; i < stringSize; i++) {
		randomString += randomeChars.charAt(Math.floor(Math.random() * randomeChars.length));
	}
	return randomString;
}