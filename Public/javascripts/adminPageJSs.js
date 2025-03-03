$(document).ready(function(){
	$('#searchName').on('click', function(event){
		event.preventDefault();
		var firstName = $.trim($('#firstName').val());
		var lastName = $.trim($('#lastName').val());
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('findUserDiv').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('findUserDiv').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		$.ajax({
			url: '../PHP-JSON/findProfileByName_JSON.php',
			dataType: 'json',
			type: 'post',
			data: {first_name: firstName, last_name: lastName, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime, account: "user"},

			success: function(response) {
				// console.log(response);
				// Update the CSRF token and CSRF time
				csrfTokenObj.value = response.newCSRFtoken;
				csrfTimeObj.value = response.newCSRFtime;
				if (response.success) {
					if (response.foundUsers.length < 1) {
						$('#usersDivContent').empty();
						$('#usersDivContent').append("No customer found.");
						// Open the display div
						$('.foundUsers').slideDown();
					} else {
						// show the found users
						populateFoundUsers(response.foundUsers);
						// Open the display div
						$('.foundUsers').slideDown();
					}
				} else if (response.result) {
					// concatenate the errors
					var error = "";
					for (var obj in response.result) {
						error += "<p style='margin-left: 10px; margin-top: 0px; color: #A51300;'>"+response.result[obj]+"</p>"
					}
					if (error !== "") {
						displayMessage('Error', error);
					}
				} else if (response.csrfFailed) {
					displayMessage('Error', response.csrfFailed);
				} else if (response.noData) {
					displayMessage('Error', response.noData);
				}
			}
		});
	});

	$('#closeFoundUsers').click(function(event) {
		event.preventDefault();
		$('.foundUsers').slideUp();
	});

	$('#searchCusName').on('click', function(event){
		event.preventDefault();
		var firstName = $.trim($('#cusFirstName').val());
		var lastName = $.trim($('#cusLastName').val());
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('findCusDiv').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('findCusDiv').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;

		$.ajax({
			url: '../PHP-JSON/findProfileByName_JSON.php',
			dataType: 'json',
			type: 'post',
			data: {first_name: firstName, last_name: lastName, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime, account: "customer"},

			success: function(response) {
				// console.log(response);
				// Update the CSRF token and CSRF time
				csrfTokenObj.value = response.newCSRFtoken;
				csrfTimeObj.value = response.newCSRFtime;
				if (response.success) {
					if (response.foundCustomers.length < 1) {
						$('#customersDivContent').empty();
						$('#customersDivContent').append("No customer found.");
						// Open the display div
						$('.foundCustomers').slideDown();
					} else {
						// show the found customers
						populateFoundCustomers(response.foundCustomers);
						// Open the display div
						$('.foundCustomers').slideDown();
					}						
				} else if (response.result) {
					// concatenate the errors
					var error = "";
					for (var obj in response.result) {
						error += "<p style='margin-left: 10px; margin-top: 0px; color: #A51300;'>"+response.result[obj]+"</p>"
					}
					if (error !== "") {
						displayMessage('Error', error);
					}
				} else if (response.csrfFailed) {
					displayMessage('Error', response.csrfFailed);
				} else if (response.noData) {
					displayMessage('Error', response.noData);
				}
			}
		});
	});

	$('#closeFoundCustomers').click(function(event) {
		event.preventDefault();
		$('.foundCustomers').slideUp();
	});

	$('#updatePwd').click(function(event) {
		event.preventDefault();
		// Get username and password
		var username = $.trim($('#usernameBox').val());
		var password = $.trim($('#passwordBox').val());
		var confirmPassword = $.trim($('#confirmPasswordBox').val());
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('userPassKeyDiv').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('userPassKeyDiv').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;
		var dataValues = {username: username, password: password, confirm_password: confirmPassword, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime, account: "user"};

		$.ajax({
			url: '../PHP-JSON/updateProfilePwd_JSON.php',
			dataType: 'json',
			type: 'post',
			data: dataValues,

			beforeSend: function() {
				// disable the submit button during form submission 
	    	// to avoid multiple clicks and enable it later
				$('#updatePwd').attr('disabled', true);
				$(".loader").css("display", "flex");
			},

			complete: function() {
				// $(".loader").css("display", "none");
				$(".loader").fadeOut(2000);
			},

			success: function(response) {
				// Update the CSRF token and CSRF time
				csrfTokenObj.value = response.newCSRFtoken;
				csrfTimeObj.value = response.newCSRFtime;
				if (response.success) {
					var message = '<p style="color:green;">'+response.updatedPwd+'<p>';
					message += '<p>'+response.SMSoutcome+'<p>';
					displayMessage('Success', message);
				} else if (response.updateFailed) {
					displayMessage('Error', response.updateFailed);
				} else if (response.usernameError) {
					displayMessage('Error', response.usernameError);
				} else if (response.result) {
					// concatenate the errors
					var error = "";
					for (var obj in response.result) {
						error += "<p style='margin-left: 10px; margin-top: 0px; color: #A51300;'>"+response.result[obj]+"</p>"
					}
					if (error !== "") {
						displayMessage('Error', error);
					}
				} else if (response.csrfFailed) {
					displayMessage('Error', response.csrfFailed);
				} else if (response.noData) {
					displayMessage('Error', response.noData);
				}
			}
		});
		// Enable the update button again after form submission.
		$('#updatePwd').attr('disabled', false);
	});

	$('#updatePwd2').click(function(event) {
		event.preventDefault();
		// Get username and password
		var username = $.trim($('#usernameBox2').val());
		var password = $.trim($('#passwordBox2').val());
		var confirmPassword = $.trim($('#confirmPasswordBox2').val());
		// Get CSRF token objects
		var csrfTokenObj = document.getElementById('customerPassKeyDiv').children[0];
		var csrfTokenName = csrfTokenObj.name;
		var csrfToken = csrfTokenObj.value;
		// Get CSRF time objects
		var csrfTimeObj = document.getElementById('customerPassKeyDiv').children[1];
		var csrfTimeName = csrfTimeObj.name;
		var csrfTime = csrfTimeObj.value;
		var dataValues = {username: username, password: password, confirm_password: confirmPassword, [csrfTokenName]: csrfToken, [csrfTimeName]: csrfTime, account: "customer"};		

		$.ajax({
			url: '../PHP-JSON/updateProfilePwd_JSON.php',
			dataType: 'json',
			type: 'post',
			data: dataValues,

			beforeSend: function() {
				// disable the submit button during form submission 
	    	// to avoid multiple clicks and enable it later
				$('#updatePwd2').attr('disabled', true);
				$(".loader").css("display", "flex");
			},

			complete: function() {
				// $(".loader").css("display", "none");
				$(".loader").fadeOut(2000);
			},

			success: function(response) {
				// Update the CSRF token and CSRF time
				csrfTokenObj.value = response.newCSRFtoken;
				csrfTimeObj.value = response.newCSRFtime;
				if (response.success) {
					var message = '<p style="color:green;">'+response.updatedPwd+'<p>';
					message += '<p>'+response.SMSoutcome+'<p>';
					displayMessage('Success', message);
				} else if (response.updateFailed) {
					displayMessage('Error', response.updateFailed);
				} else if (response.usernameError) {
					displayMessage('Error', response.usernameError);
				} else if (response.result) {
					// concatenate the errors
					var error = "";
					for (var obj in response.result) {
						error += "<p style='margin-left: 10px; margin-top: 0px; color: #A51300;'>"+response.result[obj]+"</p>"
					}
					if (error !== "") {
						displayMessage('Error', error);
					}
				} else if (response.csrfFailed) {
					displayMessage('Error', response.csrfFailed);
				} else if (response.noData) {
					displayMessage('Error', response.noData);
				}
			}
		});
		// Enable the update button again after form submission.
		$('#updatePwd2').attr('disabled', false);
	});
});

function populateFoundUsers(details) {
	// Get the element id of the div to attach the checkboxes
	var usersDivContent = document.getElementById("usersDivContent");
	// Clear the previous content for a new search
	usersDivContent.innerHTML = "";

	for (var i = 0; i < details.length; i++) {
		// Create the div to contain all the details of the user
		var userDetailsGroup = document.createElement("div");
		// Set the id for the div tag
		userDetailsGroup.setAttribute("id", "userDetailsGroup"+i);
		// Set the class for the div tag
		userDetailsGroup.setAttribute("class", "userDetailsGroup");

		// Create First name div and contents
		// Create a label tag
		var labelObj = document.createElement("label");
		// Set the class for the label tag
		labelObj.setAttribute("class", "labelContent");
		// Set the value of the label
		labelObj.innerHTML = "First Name:";
		// Create a paragraph tag
		var paraObj = document.createElement("p");
		// Set the class for the paragraph tag
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].first_name;
		// Create a div tag
		var userDetail = document.createElement("div");
		// Set the id for the div tag
		userDetail.setAttribute("id", "firstNameDiv");
		// Set the class for the div tag
		userDetail.setAttribute("class", "userDetail");
		userDetail.appendChild(labelObj);
		userDetail.appendChild(paraObj);
		userDetailsGroup.appendChild(userDetail);

		// Create Last name div and contents
		var labelObj = document.createElement("label");
		labelObj.setAttribute("class", "labelContent");
		labelObj.innerHTML = "Last Name:";
		var paraObj = document.createElement("p");
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].last_name;
		var userDetail = document.createElement("div");
		userDetail.setAttribute("id", "lastNameDiv");
		userDetail.setAttribute("class", "userDetail");
		userDetail.appendChild(labelObj);
		userDetail.appendChild(paraObj);
		userDetailsGroup.appendChild(userDetail);

		// Create Gender div and contents
		var labelObj = document.createElement("label");
		labelObj.setAttribute("class", "labelContent");
		labelObj.innerHTML = "Gender:";
		var paraObj = document.createElement("p");
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].gender;
		var userDetail = document.createElement("div");
		userDetail.setAttribute("id", "genderDiv");
		userDetail.setAttribute("class", "userDetail");
		userDetail.appendChild(labelObj);
		userDetail.appendChild(paraObj);
		userDetailsGroup.appendChild(userDetail);			
		
		// Create Username div and contents
		var labelObj = document.createElement("label");
		labelObj.setAttribute("class", "labelContent");
		labelObj.innerHTML = "Username:";
		var paraObj = document.createElement("p");
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].username;
		var userDetail = document.createElement("div");
		userDetail.setAttribute("id", "usernameDiv");
		userDetail.setAttribute("class", "userDetail");
		userDetail.appendChild(labelObj);
		userDetail.appendChild(paraObj);
		userDetailsGroup.appendChild(userDetail);

		// Create Phone number div and contents
		var labelObj = document.createElement("label");
		labelObj.setAttribute("class", "labelContent");
		labelObj.innerHTML = "Phone Number:";
		var paraObj = document.createElement("p");
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].phone_number;
		var userDetail = document.createElement("div");
		userDetail.setAttribute("id", "phoneNumberDiv");
		userDetail.setAttribute("class", "userDetail");
		userDetail.appendChild(labelObj);
		userDetail.appendChild(paraObj);
		userDetailsGroup.appendChild(userDetail);

		// Create Email div and contents
		var labelObj = document.createElement("label");
		labelObj.setAttribute("class", "labelContent");
		labelObj.innerHTML = "Email:";
		var paraObj = document.createElement("p");
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].email;
		var userDetail = document.createElement("div");
		userDetail.setAttribute("id", "emailDiv");
		userDetail.setAttribute("class", "userDetail");
		userDetail.appendChild(labelObj);
		userDetail.appendChild(paraObj);
		userDetailsGroup.appendChild(userDetail);

		// Make Date Created div and contents
		var labelObj = document.createElement("label");
		labelObj.setAttribute("class", "labelContent");
		labelObj.innerHTML = "Date Created:";
		var paraObj = document.createElement("p");
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].date_created;
		var userDetail = document.createElement("div");
		userDetail.setAttribute("id", "dateCreatedDiv");
		userDetail.setAttribute("class", "userDetail");
		userDetail.appendChild(labelObj);
		userDetail.appendChild(paraObj);
		userDetailsGroup.appendChild(userDetail);

		// Append the user group div to the content div
		usersDivContent.appendChild(userDetailsGroup);
	}
}

function populateFoundCustomers(details) {
	// Get the element id of the div to attach the checkboxes
	var customersDivContent = document.getElementById("customersDivContent");
	// Clear the previous content for a new search
	customersDivContent.innerHTML = "";

	for (var i = 0; i < details.length; i++) {
		// Create the div to contain all the details of the customer
		var customerDetailsGroup = document.createElement("div");
		// Set the id for the div tag
		customerDetailsGroup.setAttribute("id", "customerDetailsGroup"+i);
		// Set the class for the div tag
		customerDetailsGroup.setAttribute("class", "customerDetailsGroup");

		// Create First name div and contents
		// Create a label tag
		var labelObj = document.createElement("label");
		// Set the class for the label tag
		labelObj.setAttribute("class", "labelContent");
		// Set the value of the label
		labelObj.innerHTML = "First Name:";
		// Create a paragraph tag
		var paraObj = document.createElement("p");
		// Set the class for the paragraph tag
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].first_name;
		// Create a div tag
		var customerDetail = document.createElement("div");
		// Set the id for the div tag
		customerDetail.setAttribute("id", "firstNameDiv");
		// Set the class for the div tag
		customerDetail.setAttribute("class", "customerDetail");
		customerDetail.appendChild(labelObj);
		customerDetail.appendChild(paraObj);
		customerDetailsGroup.appendChild(customerDetail);

		// Create Last name div and contents
		var labelObj = document.createElement("label");
		labelObj.setAttribute("class", "labelContent");
		labelObj.innerHTML = "Last Name:";
		var paraObj = document.createElement("p");
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].last_name;
		var customerDetail = document.createElement("div");
		customerDetail.setAttribute("id", "lastNameDiv");
		customerDetail.setAttribute("class", "customerDetail");
		customerDetail.appendChild(labelObj);
		customerDetail.appendChild(paraObj);
		customerDetailsGroup.appendChild(customerDetail);

		// Create Gender div and contents
		var labelObj = document.createElement("label");
		labelObj.setAttribute("class", "labelContent");
		labelObj.innerHTML = "Gender:";
		var paraObj = document.createElement("p");
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].gender;
		var customerDetail = document.createElement("div");
		customerDetail.setAttribute("id", "genderDiv");
		customerDetail.setAttribute("class", "customerDetail");
		customerDetail.appendChild(labelObj);
		customerDetail.appendChild(paraObj);
		customerDetailsGroup.appendChild(customerDetail);			
		
		// Create Username div and contents
		var labelObj = document.createElement("label");
		labelObj.setAttribute("class", "labelContent");
		labelObj.innerHTML = "Username:";
		var paraObj = document.createElement("p");
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].username;
		var customerDetail = document.createElement("div");
		customerDetail.setAttribute("id", "usernameDiv");
		customerDetail.setAttribute("class", "customerDetail");
		customerDetail.appendChild(labelObj);
		customerDetail.appendChild(paraObj);
		customerDetailsGroup.appendChild(customerDetail);

		// Create Phone number div and contents
		var labelObj = document.createElement("label");
		labelObj.setAttribute("class", "labelContent");
		labelObj.innerHTML = "Phone Number:";
		var paraObj = document.createElement("p");
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].phone_number;
		var customerDetail = document.createElement("div");
		customerDetail.setAttribute("id", "phoneNumberDiv");
		customerDetail.setAttribute("class", "customerDetail");
		customerDetail.appendChild(labelObj);
		customerDetail.appendChild(paraObj);
		customerDetailsGroup.appendChild(customerDetail);

		// Create Email div and contents
		var labelObj = document.createElement("label");
		labelObj.setAttribute("class", "labelContent");
		labelObj.innerHTML = "Email:";
		var paraObj = document.createElement("p");
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].email;
		var customerDetail = document.createElement("div");
		customerDetail.setAttribute("id", "emailDiv");
		customerDetail.setAttribute("class", "customerDetail");
		customerDetail.appendChild(labelObj);
		customerDetail.appendChild(paraObj);
		customerDetailsGroup.appendChild(customerDetail);

		// Make Date Created div and contents
		var labelObj = document.createElement("label");
		labelObj.setAttribute("class", "labelContent");
		labelObj.innerHTML = "Date Created:";
		var paraObj = document.createElement("p");
		paraObj.setAttribute("class", "detailsContent");
		paraObj.innerHTML = details[i].date_created;
		var customerDetail = document.createElement("div");
		customerDetail.setAttribute("id", "dateCreatedDiv");
		customerDetail.setAttribute("class", "customerDetail");
		customerDetail.appendChild(labelObj);
		customerDetail.appendChild(paraObj);
		customerDetailsGroup.appendChild(customerDetail);

		// Append the customer group div to the content div
		customersDivContent.appendChild(customerDetailsGroup);
	}
}


// Check credit balance
$(document).ready(function(){
	$('#checkCreditBal').on('click', function(event){
		event.preventDefault();

		var ApiKey = "CDqdYvVDF7P7lLTorVo%2BzKjmwYYvRls6nMUbJnJ%2FuDg%3D";
		var ClientId = "7bf515f1-b9a0-43bb-a25d-e1d875def7bf";
		
		/* $.ajax({
		  type: "GET",
		  // url for local host connection (developer mode)
		  // url: "http://45.77.146.255:6005/api/v2/Balance?ApiKey="+ApiKey+"&ClientId="+ClientId,

		  // url for live server internet connection (production mode)
		  url: "https://secure.xwireless.net/api/v2/Balance?ApiKey="+ApiKey+"&ClientId="+ClientId,
		  
		  contentType: "application/x-www-form-urlencoded; charset=UTF-8",
		  dataType: 'json',

		  complete: function (data, status) {
		  	if (status === 'success') {
		  		$('#creditBal').empty();
					$('#creditBal').append(data.responseJSON.Data[0].Credits);	
		  	} else if (status === 'error') {
		  		displayMessage('Error', "Error communicating with xwireless server.");
		  	}
		  }
		}); */
		
		$.ajax({
			url: '../PHP-JSON/checkCreditBalance_JSON.php',
			dataType: 'json',
			type: 'post', 

			success: function(response) {
				if (response.success) {
					$('#creditBal').empty();
					$('#creditBal').append(response.creditBalance);	
				} else if (response.error) {
					displayMessage('Error', response.error);
				}	else if (response.sameDomainError) {
					displayMessage('Error', response.sameDomainError);
				}
			}
		});
	});
});


// Check credit balance
$(document).ready(function(){
	$('#genPwdBtn').on('click', function(event){
		event.preventDefault();
		
		var pwdLength = 8;
		var newPassword = passwordGen(pwdLength);
		// Append the password to the input field
		$('#genPassword').empty();
		$('#genPassword').append(newPassword);
	});
});


// This function returns the feedback messages of a certian subject
$(document).ready(function(){
	$('#subject').change(function() {
		var subject = $('#subject').val();
		
		$.ajax({
			url: '../PHP-JSON/getFeedbackBySubject_JSON.php',
			dataType: 'json',
			type: 'post',
			data: {subject: subject},

			success: function(response) {
				displayFeedback(response.foundFeedbacks, subject);
			}
		});
	});
});


// This function displays the selected feedback
function displayFeedback(feedbacks, subject) {
	// check if the feedbacks array exist
	var feedbackHolder = document.getElementById('feedbackHolder');
	if (feedbacks.length > 0) {
		// Make feedback holder visible
		feedbackHolder.innerHTML = "";
		// Create the div number of resolved and unresolved feedbacks
		var statisticsDiv = document.createElement('div');
		statisticsDiv.setAttribute('id', 'statisticsDiv');
		// Define number of resolve and unresovled
		var numResoved = 0;
		var numUnresoved = 0;

		for (var i = 0; i < feedbacks.length; i++) {
			// Create the feedback div
			var feedbackDiv = document.createElement('div');
			feedbackDiv.setAttribute('id', 'feedback'+i);
			feedbackDiv.setAttribute('class', 'feedbackDiv');
			if (parseInt(feedbacks[i].resolved) === 1) {
				numResoved = parseInt(numResoved) + 1;
				feedbackDiv.style.border = "thin solid greenyellow";
			} else {
				numUnresoved = parseInt(numUnresoved) + 1;
				feedbackDiv.style.border = "thin solid red";
			}

			// Create a hidden paragraph to contain the id from the table
			var feedbackId = document.createElement('p');
			feedbackId.setAttribute('id', 'tableId'+i);
			feedbackId.setAttribute('class', 'hidden');
			feedbackId.innerHTML = feedbacks[i].id;

			// Create the div for date
			var dateDiv = document.createElement('div');
			dateDiv.setAttribute('id', 'dateCreatedDiv');
			dateDiv.setAttribute('class', 'userDetail');

			// Create the date label
			var dateLabel = document.createElement('label');
			dateLabel.setAttribute('class', 'labelContent');
			dateLabel.innerHTML = 'Date Created:';

			// Create the date paragraph
			var datePara = document.createElement('p');
			datePara.setAttribute('class', 'detailsContent');
			datePara.innerHTML = feedbacks[i].date_created.toString();
			
			// Append to div
			dateDiv.appendChild(dateLabel);
			dateDiv.appendChild(datePara);
			
			// Create the div for fullname
			var fullnameDiv = document.createElement('div');
			fullnameDiv.setAttribute('id', 'fullnameDiv');
			fullnameDiv.setAttribute('class', 'userDetail');

			// Create the fullname label
			var fullnameLabel = document.createElement('label');
			fullnameLabel.setAttribute('class', 'labelContent');
			fullnameLabel.innerHTML = 'Fullname:';

			// Create the fullname paragraph
			var fullnamePara = document.createElement('p');
			fullnamePara.setAttribute('class', 'detailsContent');
			fullnamePara.innerHTML = feedbacks[i].first_name.toString()+' '+feedbacks[i].last_name.toString();

			// Append to div
			fullnameDiv.appendChild(fullnameLabel);
			fullnameDiv.appendChild(fullnamePara);

			// Create the div for phone number
			var phoneDiv = document.createElement('div');
			phoneDiv.setAttribute('id', 'phoneNumberDiv');
			phoneDiv.setAttribute('class', 'userDetail');

			// Create the date label
			var phoneLabel = document.createElement('label');
			phoneLabel.setAttribute('class', 'labelContent');
			phoneLabel.innerHTML = 'Phone Number:';

			// Create the date paragraph
			var phonePara = document.createElement('p');
			phonePara.setAttribute('class', 'detailsContent');
			phonePara.innerHTML = feedbacks[i].phone_number.toString();

			// Append to div
			phoneDiv.appendChild(phoneLabel);
			phoneDiv.appendChild(phonePara);

			// Create the div for email
			var emailDiv = document.createElement('div');
			emailDiv.setAttribute('id', 'emailDiv');
			emailDiv.setAttribute('class', 'userDetail');

			// Create the date label
			var emailLabel = document.createElement('label');
			emailLabel.setAttribute('class', 'labelContent');
			emailLabel.innerHTML = 'Email:';

			// Create the date paragraph
			var emailPara = document.createElement('p');
			emailPara.setAttribute('class', 'detailsContent');
			emailPara.innerHTML = feedbacks[i].email_address.toString();

			// Append to div
			emailDiv.appendChild(emailLabel);
			emailDiv.appendChild(emailPara);

			// Create the feedback Message div for textarea			
			var textareaDiv = document.createElement('div');
			textareaDiv.setAttribute('id', 'feedbackMsg');
			textareaDiv.setAttribute('class', 'userDetail');

			// Create the div for textarea label
			var textareaDivLbl = document.createElement('div');

			// Create the textarea label
			var textareaLabel = document.createElement('label');
			textareaLabel.setAttribute('class', 'labelContent');
			textareaLabel.innerHTML = 'Feedback Subject:';

			// Create the textarea paragraph
			var textareaPara = document.createElement('p');
			textareaPara.setAttribute('class', 'detailsContent');
			textareaPara.innerHTML = feedbacks[i].message_subject.toString();

			// Create the textarea for displaying message
			var textarea = document.createElement('div');
			textarea.setAttribute('class', 'feedbackMsgBox');
			textarea.innerHTML = feedbacks[i].message_content.toString();

			// Append to div
			textareaDivLbl.appendChild(textareaLabel);
			textareaDivLbl.appendChild(textareaPara);

			textareaDiv.appendChild(textareaDivLbl);
			textareaDiv.appendChild(textarea);

			// Create button div
			var btnDiv = document.createElement('div');
			btnDiv.setAttribute('id', 'buttonDiv');
			btnDiv.setAttribute('class', 'userDetail');			

			// Create button for resolved and unresolved
			var button = document.createElement('button');
			if (parseInt(feedbacks[i].resolved) === 1) {
				// Set the unresolved button for changes
				button.setAttribute('id', 'unresolved'+i);
				button.setAttribute('class', 'unresolved');
				button.setAttribute('onclick', 'unresolved(this)');
				button.innerHTML = "Unresolved";
			} else {
				// Set the resolved button for changes
				button.setAttribute('id', 'resolved'+i);
				button.setAttribute('class', 'resolved');
				button.setAttribute('onclick', 'resolved(this)');
				button.innerHTML = "Resolved";
			}

			// Create delete button
			var deleteBtn = document.createElement('button');
			deleteBtn.setAttribute('id', 'deleteFeedback'+i);
			deleteBtn.setAttribute('class', 'deleteBtn');
			deleteBtn.setAttribute('onclick', 'deleteFeedback(this)');
			deleteBtn.innerHTML = "Delete";

			// Append buttons to div
			btnDiv.appendChild(button);
			btnDiv.appendChild(deleteBtn);

			// Append divs category to the feedback div
			feedbackDiv.appendChild(feedbackId);
			feedbackDiv.appendChild(dateDiv);
			feedbackDiv.appendChild(fullnameDiv);
			feedbackDiv.appendChild(phoneDiv);
			feedbackDiv.appendChild(emailDiv);
			feedbackDiv.appendChild(textareaDiv);
			feedbackDiv.appendChild(btnDiv);

			// Append the feedback div to the holder div for all feedbacks
			feedbackHolder.appendChild(feedbackDiv);
		}
		statisticsDiv.innerHTML = "<p id='resolvedPara'>Number of Resoved feedbacks are: "+numResoved+"</p>";
		statisticsDiv.innerHTML += "<p id='unresolvedPara'>Number of Unresoved feedbacks are: "+numUnresoved+"</p>";
		
		feedbackHolder.insertBefore(statisticsDiv, feedbackHolder.children[0]);
		// feedbackHolder.style.display = 'none';
		$('#feedbackHolder').slideDown();
	} else {
		feedbackHolder.innerHTML = "";
		feedbackHolder.innerHTML = "<p style='margin-left: 10px;'>There is no "+subject+" feedback available.</p>";
	}
}

// This will listen for the click of a resoleve button
function resolved(btnObj) {
	// Get the number in the id
	var id = btnObj.id;
	var subject = $('#subject').val();
	
	// Get the id for the feedback table
	var mainDiv = document.getElementById(id).parentElement.parentElement;
	var feedbackId = mainDiv.firstChild.innerHTML;

	$.ajax({
		url: '../PHP-JSON/updateFeedback_JSON.php',
		dataType: 'json',
		type: 'post',
		data: {id: feedbackId, subject: subject, action: 'resolved'},

		success: function(response) {
			if (response.success) {
				// Set the settings of unresolved
				// Chage the parent div border style
				mainDiv.style.border = "thin solid greenyellow";
				// Change the button class
				btnObj.setAttribute('class', 'unresolved');
				// Chage the button text
				btnObj.innerHTML = 'Unresolved';
				// Chage the button function
				btnObj.setAttribute('onclick', 'unresolved(this)');
				// Update the statistics
				$('#resolvedPara').html("Number of Resoved feedbacks are: "+response.numResolved);
				$('#unresolvedPara').html("Number of Unresoved feedbacks are: "+response.numUnesolved);
			} else if (response.saveError) {
				displayMessage('Error', response.saveError);
			} else if (response.deleteError) {
				displayMessage('Error', response.deleteError);
			} else if (response.postDataError) {
				displayMessage('Error', response.postDataError);
			} else if (response.sameDomainError) {
				displayMessage('Error', response.sameDomainError);
			}
		}
	});
}


// This will listen for the click of a unresoleve button
function unresolved(btnObj) {
	// Get the number in the id
	var id = btnObj.id;
	var subject = $('#subject').val();
	
	// Get the id for the feedback table	
	var mainDiv = document.getElementById(id).parentElement.parentElement;
	var feedbackId = mainDiv.firstChild.innerHTML;

	$.ajax({
		url: '../PHP-JSON/updateFeedback_JSON.php',
		dataType: 'json',
		type: 'post',
		data: {id: feedbackId, subject: subject, action: 'unresolved'},

		success: function(response) {
			if (response.success) {
				// Set the settings of resolved
				// Chage the parent div border style
				mainDiv.style.border = "thin solid red";
				// Change the button class
				btnObj.setAttribute('class', 'resolved');
				// Chage the button text
				btnObj.innerHTML = 'Resolved';
				// Chage the button function
				btnObj.setAttribute('onclick', 'resolved(this)');
				// Update the statistics
				$('#resolvedPara').html("Number of Resoved feedbacks are: "+response.numResolved);
				$('#unresolvedPara').html("Number of Unresoved feedbacks are: "+response.numUnesolved);
			} else if (response.saveError) {
				displayMessage('Error', response.saveError);
			} else if (response.deleteError) {
				displayMessage('Error', response.deleteError);
			} else if (response.postDataError) {
				displayMessage('Error', response.postDataError);
			} else if (response.sameDomainError) {
				displayMessage('Error', response.sameDomainError);
			}
		}
	});
}

// Delete the feedback
function deleteFeedback(btnObj) {
	// Get the number in the id
	var id = btnObj.id;
	var subject = $('#subject').val();
	
	// Get the id for the feedback table	
	var mainDiv = document.getElementById(id).parentElement.parentElement;
	var feedbackId = mainDiv.firstChild.innerHTML;

	$.ajax({
		url: '../PHP-JSON/updateFeedback_JSON.php',
		dataType: 'json',
		type: 'post',
		data: {id: feedbackId, subject: subject, action: 'delete'},

		success: function(response) {
			if (response.success) {
				// Remove the button
				displayMessage('Success', response.feedbackDelete);
				$('#'+mainDiv.id).remove();
				// Update the statistics
				$('#resolvedPara').html("Number of Resoved feedbacks are: "+response.numResolved);
				$('#unresolvedPara').html("Number of Unresoved feedbacks are: "+response.numUnesolved);
			} else if (response.saveError) {
				displayMessage('Error', response.saveError);
			} else if (response.deleteError) {
				displayMessage('Error', response.deleteError);
			} else if (response.postDataError) {
				displayMessage('Error', response.postDataError);
			} else if (response.sameDomainError) {
				displayMessage('Error', response.sameDomainError);
			}
		}
	});
}
