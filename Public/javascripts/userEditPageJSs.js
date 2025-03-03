function reply(caller) {
	var id = caller.id;
	
	$("#replyDiv").insertAfter($(caller));
	$("#replyDiv").show();
}


/* This functions will clear the input fields in the customer profile page */
$(document).ready(function(){
	$('#clear_first_name').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearUserInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'firstName'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#firstNameInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					alert("Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_last_name').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearUserInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'lastName'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#lastNameInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					alert("Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_gender').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearUserInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'gender'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#genderInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					alert("Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_username').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearUserInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'username'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#usernameInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					alert("Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_email').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearUserInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'email'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#emailInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					alert("Unauthorized attempt to make this change.");
				}
			}
		});
	});

	$('#clear_phoneNumber').click(function(event){
		event.preventDefault();
		// Clear the first name input
		$.ajax ({
			url: '../PHP-JSON/clearUserInputs_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {input: 'phoneNumber'},
			
			success: function(data) {
				// console.log(data);
				if (data.result === 'cleared') {
					$('#phoneNumberInput').text(' ');
					$('.CollapsiblePanelTab').css('overflow', 'hidden');
				} else {
					alert("Unauthorized attempt to make this change.");
				}
			}
		});
	});
});


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
					// Display error message if comment was not saved.
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



/* This functions will update the edited input fields in the customer profile page */
$(document).ready(function(){
	// Update first name
	$('#submit_first_name').click(function(event){
		event.preventDefault();

		// Get the first name entered
		var firstName = $('#first_name').val();
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
			url: '../PHP-JSON/submitUserProfileUpdate_JSON.php', 
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
					$('#first_name').val("");
					$('#firstNameInput').empty();
					$('#firstNamePanelContent').slideUp();
					$('#firstNameInput').append(initialCaps(firstName));
					displayMessage('Success', data.inputFieldUpdated);
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
		var lastName = $('#last_name').val();
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
			url: '../PHP-JSON/submitUserProfileUpdate_JSON.php', 
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
					$('#last_name').val("");
					$('#lastNameInput').empty();
					$('#lastNamePanelContent').slideUp();
					$('#lastNameInput').append(initialCaps(lastName));
					displayMessage('Success', data.inputFieldUpdated);
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
			url: '../PHP-JSON/submitUserProfileUpdate_JSON.php', 
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
					displayMessage('Success', data.inputFieldUpdated);
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
		var username = $('#username_user').val();
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
			url: '../PHP-JSON/submitUserProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'username', username: username, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#username').val("");
					$('#usernameInput').empty();
					$('#usernameInput').append(username);
					$('#usernamePanelContent').slideUp();
					displayMessage('Success', data.inputFieldUpdated);
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
		var password = $('#password').val();
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
			url: '../PHP-JSON/submitUserProfileUpdate_JSON.php', 
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
					displayMessage('Success', data.inputFieldUpdated);
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
	$('#submit_email').click(function(event){
		event.preventDefault();

		// Get the business email
		var email = $('#email').val();
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('email_form').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('email_form').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		// Update the business email
		$.ajax ({
			url: '../PHP-JSON/submitUserProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'email', email: email, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('#email').val("");
					$('#emailInput').empty();
					$('#emailInput').append(email);
					$('#emailPanelContent').slideUp();
					displayMessage('Success', data.inputFieldUpdated);
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
		var phone_number = $('#phone_number').val();
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
			url: '../PHP-JSON/submitUserProfileUpdate_JSON.php', 
			dataType: 'json',
			type: 'POST',
			data: {inputField: 'phone_number', phone_number: phone_number, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime},
			
			success: function(data) {
				// Reset the csrf token and time used
        csrfTokenObj.value = data.newCSRFtoken;
				csrfTimeObj.value = data.newCSRFtime;
				if (data.success) {
					$('.accountInfoDetails')[2].innerHTML = phone_number;
					$('#validateNumber').css('display', 'inline');
					$('#phone_number').val("");
					$('#phoneNumPanelContent').slideUp();
					$('#phoneNumberInput').empty();
					$('#phoneNumberInput').append(phone_number);
					displayMessage('Success', data.inputFieldUpdated);
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
			url: '../PHP-JSON/submitUserProfileUpdate_JSON.php', 
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
			url: '../PHP-JSON/submitUserProfileUpdate_JSON.php', 
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

});