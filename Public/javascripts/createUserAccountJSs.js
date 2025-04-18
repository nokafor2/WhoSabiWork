/* Validate the web form fields 
	This function listens for an onblur event in the web form for the createUserAccount.js page
*/
function validateInput(inputObj) {
	var inputIdVal = inputObj.id;
	var inputName = inputObj.name;
	var inputValue = $.trim(inputObj.value);
	var dataVariable = {inputName: inputName, value: inputValue};
	
	// send variables through ajax to php functoin
	$.ajax({
		url: 'PHP-JSON/validateInput_JSON.php', 
		dataType: 'json',
		type: 'POST',
		data: dataVariable,

		success: function(response) {
			// console.log(response);
			if (response.success) {
				$('#'+inputIdVal).css('box-shadow', 'inset 0 0 10px green');
				$('#'+inputName+'_message').hide();
			} else if (response.validateError) {
				// Concatenate the error
				var error = "";
				for (var obj in response.validateError) {
					error += "<p style='margin: 0px; color: red;'>"+response.validateError[obj]+"</p>"
				}
				$('#'+inputIdVal).css('box-shadow', 'inset 0 0 10px #A51300');
				// box-shadow: inset 0 0 10px #C0C4C6;
				if (error !== "") {
					// displayMessage('Error', error);
					$('#'+inputName+'_message').show();
					$('#'+inputName+'_message').html(error);
				}				
			}				
		}
	});
}

/* This function will send details of the user creating an account to the database */
$('#user_register').on('click', function(event) {
	event.preventDefault();
	// Get the first name
	var firstName = $('#first_name_user').val();
	// Get the last name
	var lastName = $('#last_name_user').val();
	// Get the gender
	var genders = document.form1.gender;
	for (var i=0; i < genders.length; i++) {
		if (genders[i].checked) {
			gender = genders[i].value;
			break;
		}
	}
	if (typeof gender === "undefined" || gender === null) {
		gender = "";
	}
	// Get the username
	var username = $('#username_user').val();
	// Get the passwords
	var password = $('#password_user').val();
	var confirmPassword = $('#confirm_password_user').val();
	// Get the phone number
	var phoneNumber = $('#phone_number_user').val();
	// Get the email 
	var email = $('#email_user').val();
	// Concatenate the data to be sent to the PHP file for processing
	var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email};

	// Check the session for errors during form filling
	$.ajax({
		url: 'PHP-JSON/checkFormValidation_JSON.php', 
		dataType: 'json',
		type: 'POST',
		data: formData,

		success: function(response) {
			// check if response is successful
			if (response.result) {
				// Display errors to the user
				var errorArray = response.result;
				// checkErrorData(errorArray); // Deprecated code
				emptyErrMsgDiv();
				for (var obj in errorArray) {
					var errorMsgDiv = returnErrorDiv(obj);
					$('#'+errorMsgDiv[0]).css('box-shadow', 'inset 0 0 10px #A51300');
					$('#'+errorMsgDiv[1]).append('<p style="margin:0px;">'+errorArray[obj]+'</p>');
					$('#'+errorMsgDiv[1]).show();
				}
				// console.log(errorArray);
			}	else if (response.validationResult) {
				// save the form data
				saveUserAccountFormData();				
			}	else if (response.noData) {
				displayMessage("Error", "Please complete the form before submitting.");
			}	
		}
	});		
});

/* This function gets the error message div for the respective error message input passed in */
function returnErrorDiv(reference) {
	if (reference.search('first_name') >= 0) {
		return ['first_name_user', 'first_name_message'];
	} else if (reference.search('last_name') >= 0) {
		return ['last_name_user', 'last_name_message'];
	} else if (reference.search('gender') >= 0) {
		return ['gender', 'gender_message'];
	} else if (reference.search('username') >= 0) {
		return ['username_user', 'username_message'];
	} else if (reference.search('confirm_password') >= 0) {
		return ['confirm_password_user', 'confirm_password_message'];	
	} else if (reference.search('password') >= 0) {
		return ['password_user', 'password_message'];
	} else if (reference.search('phone_number') >= 0) {
		return ['phone_number_user', 'phone_number_message'];
	} else if (reference.search('email') >= 0) {
		return ['email_user', 'email_message'];
	}
}

/* This clears all the contents in the error message box for form the input fields */
function emptyErrMsgDiv() {
	$('#first_name_message').empty();
	$('#last_name_message').empty();	
	$('#gender_message').empty();	
	$('#username_message').empty();	
	$('#password_message').empty();	
	$('#confirm_password_message').empty();	
	$('#phone_number_message').empty();
	$('#email_message').empty();
}

/* check for errors in array display the errors */
function checkErrorData(errorArray) {
	var errorArrayLen = Object.entries(errorArray).length;
	
	if (errorArrayLen > 0) {			
		// Report the errors
		var output = "<p style='font-size: medium; font-weight: bold;'>Fix the following errors: </p>";
		for (var obj in errorArray) {
			output += "<p>"+errorArray[obj]+"</p>";
		}
		displayMessage('Error', output);
	}
}

function saveUserAccountFormData() {
	// Get the first name
	var firstName = $('#first_name_user').val();
	// Get the last name
	var lastName = $('#last_name_user').val();
	// Get the gender
	var genders = document.form1.gender;
	for (var i=0; i < genders.length; i++) {
		if (genders[i].checked) {
			gender = genders[i].value;
			break;
		}
	}
	// Get the username
	var username = $('#username_user').val();
	// Get the passwords
	var password = $('#password_user').val();
	var confirmPassword = $('#confirm_password_user').val();
	// Get the phone number
	var phoneNumber = $('#phone_number_user').val();
	// Get the email 
	var email = $('#email_user').val();
	// Get csrf token
	var csrf_token = $('#csrf_token').val();
	// Get csrf time
	var csrf_token_time = $('#csrf_token_time').val();
	// Concatenate the data to be sent to the PHP file for processing
	var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, csrf_token: csrf_token, csrf_token_time: csrf_token_time};
	// Send data through Ajax
	$.ajax({
		url: "PHP-JSON/submitFormData_JSON.php",
		dataType: "json",
		type: "POST",
		data: formData,

		beforeSend: function() {
			// disable the submit button during form submission 
    	// to avoid multiple clicks and enable it later
			$('#user_register').attr('disabled', true);
			$(".loader").css("display", "flex");
		},

		complete: function() {
			// $(".loader").css("display", "none");
			$(".loader").fadeOut(2000);
		},

		success: function(response) {
			if (response.success) {
				// Enable the submit button again after form submission.
				$('#user_register').attr('disabled', false);
				// Redirect to the page of the created account.
				window.location.replace("user/userEditPage.php?id="+response.newUserId);
			} else if (response.savingError) {
				var message = "<p style='color: red;' >"+response.savingError+"</p>";
				displayMessage('Error', message);
			} else if (response.result) {
				var message = "<p style='color: red;' >"+response.result+"</p>";
				displayMessage('Error', message);
			} else if (response.csrf_failure) {
				var message = "<p style='color: red;' >"+response.csrf_failure+"</p>";
				displayMessage('Error', message);
			}
		}
	});
	// Enable the submit button again after form submission.
	$('#user_register').attr('disabled', false);
}