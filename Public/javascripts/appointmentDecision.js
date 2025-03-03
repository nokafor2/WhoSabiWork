
function appointmentAccepted(acceptBtnId) {
	// console.log("The button id is: "+acceptBtnId);
	// Get the index of the id button id which is consistent with other id names.
	index = acceptBtnId.substr(12, 13);
	aptDBId = "aptDBId"+index;
	
	// Get the id of the appointment record saved in the table database
	var aptDBIdValue = document.getElementById(aptDBId).innerHTML;
	
	// Get the id for the div box containing the user details
	userSchDetails = "userSchDetails"+index;
	var userSchDetailsVar = document.getElementById(userSchDetails);
	
	// Get the id of the user full name
	userFullName = "userTitle"+index;
	var userFullNameVar = document.getElementById(userFullName).innerHTML;
	
	// var eventMessage = document.getElementById(eventMessage);
	
	// Concatenate variables to send to the PHP JSON file
	var varToPHP = "id="+aptDBIdValue;

	// Display the loading symbol
	var beforeSend = function() {
		$(".loader").css("display", "flex");
		$(".loader").css("color", "grey");
		$(".loader").append("<p style='margin-left: 10px;'>Hold on! Sending email and text messages.</p>");
	}
	beforeSend();
	
	// Create our XMLHttpRequest object
	var hr = new XMLHttpRequest();
	
	// Assign the link to the PHP file 
	var url = "../customer/customerAcceptAppointment_JSON.php";
	
	hr.open("POST", url, true);
	
	// Set content type header information for sending url encoded variables in the request
	// Since we are using a POST request, the content type is set to: x-www-form-urlencoded
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");			
	
	// Send the data to PHP now... and wait for response to update the status div
	// When using post, a dynamic data can be sent through the send method. Here the variable is manually sent in.
	hr.send(varToPHP); // Actually execute the request
	
	// Access the onreadystatechange event for the XMLHttpRequest object
	hr.onreadystatechange = function() {
		if (hr.readyState == 4 && hr.status == 200) {
			// This is information from the server side through the AJAX request. This will give the HttpResponseText
			// JSON.parse() function will be used instead
			// The JSON.parse() function helps get it ready for javascript parsing. 
			var data = JSON.parse(hr.responseText);
			// console.log(data);
			// Hide the loader after request has been gotten
			$(".loader").fadeOut(2000);
			
			// Hide the div containing the appointment
			if (data.success) {
				eventMessage.innerHTML = "<p style='color:green' >Your appointment with "+userFullNameVar+" has been scheduled.</p>";
				// userSchDetailsVar.innerHTML = " ";
				// userSchDetailsVar.innerHTML = "<p style='color:green' >Your appointment with "+userFullNameVar+" has been scheduled.</p>";
				// Display success message
				$message = "";
				$message += "<p style='color:green' >Your appointment with "+userFullNameVar+" has been scheduled.</p>";
				$message += "<p>"+data.SMSoutcome+"</p>";
				$message += "<p>"+data.emailOutcome+"</p>";
				displayMessage('Success', $message);

				// Delete the div
				userSchDetailsVar.remove();
			} else {
				// eventMessage.innerHTML = "An error occurred while saving.";
				// userSchDetailsVar.innerHTML = "<p style='color:green' >An error occurred while saving.</p>";
				// Display error message
				displayMessage('Error', "<p style='color:red' >An error occurred while saving.</p>");
			}			
		}
	}
}

function appointmentDeclined(declineBtnId) {
	// console.log("The button id is: "+declineBtnId);
	// Get the index of the button id which is consistent with other id names.
	index = declineBtnId.substr(16, 17); 
	aptDBId = "aptDBId"+index;   // aptDBId is abbreviated for appointment database Id
	
	// Get the id of the appointment record saved in the table database
	var aptDBIdValue = document.getElementById(aptDBId).innerHTML;
	
	// Get the id for the div box containing the user details
	userSchDetails = "userSchDetails"+index;
	var userSchDetailsVar = document.getElementById(userSchDetails);
	
	// Get the id of the user full name
	userFullName = "userTitle"+index;
	var userFullNameVar = document.getElementById(userFullName).innerHTML;
	
	// Get the id of the message left by the user for canceling in the textarea
	reasonForDeclineId = "decliningReason"+index;
	var reasonForDeclineVar = document.getElementById(reasonForDeclineId).value;
	
	// Get the div to output the error message
	errorReportDivId = "errorDeliverDiv"+index;
	var errorReportDivVar = document.getElementById(errorReportDivId);
	
	// Specify what type of user is canceling the appointment
	// This is just used to determine the behavior of the PHP function.
	var userType = "customer";
	
	// Concatenate variables to send to the PHP JSON file
	var varToPHP = "id="+aptDBIdValue+"&decliningReason="+reasonForDeclineVar;
	
	// Display the loading symbol
	var beforeSend = function() {
		$(".loader").css("display", "flex");
		$(".loader").css("color", "grey");
		$(".loader").append("<p style='margin-left: 10px;'>Hold on! Sending email and text messages.</p>");
	}
	beforeSend();

	// Create our XMLHttpRequest object
	var hr = new XMLHttpRequest();
	
	// Assign the link to the PHP file 
	var url = "../customer/customerDeclineAppointment_JSON.php";
	
	hr.open("POST", url, true);
	
	// Set content type header information for sending url encoded variables in the request
	// Since we are using a POST request, the content type is set to: x-www-form-urlencoded
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");			
	
	// Send the data to PHP now... and wait for response to update the status div
	// When using post, a dynamic data can be sent through the send method. Here the variable is manually sent in.
	hr.send(varToPHP); // Actually execute the request
	
	// Access the onreadystatechange event for the XMLHttpRequest object
	hr.onreadystatechange = function() {
		if (hr.readyState == 4 && hr.status == 200) {
			// This is information from the server side through the AJAX request. This will give the HttpResponseText
			// JSON.parse() function will be used instead
			// The JSON.parse() function helps get it ready for javascript parsing. 
			var data = JSON.parse(hr.responseText);
			// Hide the loader after request has been gotten
			$(".loader").fadeOut(2000);
			
			// Check if the appointment cancel was successfully updated in the database
			if (data.success) {
				// userSchDetailsVar.innerHTML = " ";
				// userSchDetailsVar.innerHTML = "<p style='color:red' >Your appointment with "+userFullNameVar+" has been canceled.</p>";
				
				eventMessage.innerHTML = "<p style='color:red' >Your appointment with "+userFullNameVar+" has been canceled.</p>";
				// Display success message
				$message = "";
				$message += "<p style='color:red' >Your appointment with "+userFullNameVar+" has been canceled.</p>";
				$message += "<p>"+data.SMSoutcome+"</p>";
				$message += "<p>"+data.emailOutcome+"</p>";
				displayMessage('Success', $message);

				// Delete the div
				userSchDetailsVar.remove();
			} else if (data.failed) {
				// userSchDetailsVar.innerHTML = " ";
				// userSchDetailsVar.innerHTML = "<p style='color:green' >An error occurred while saving.</p>";
				
				// Display error message
				eventMessage.innerHTML = "<p style='color:red' >An error occurred while deleting.</p>";
				displayMessage('Error', "<p style='color:red' >An error occurred while deleting.</p>");
			}
			
			// Check for error validation
			errorReportDivVar.innerHTML = " ";
			if (data.presence_error) {
				errorReportDivVar.innerHTML += "<p style='color:red; margin:0px;' >"+data.presence_error+"</p>";
				// Display error message
				displayMessage('Error', "<p style='color:red; margin:0px;' >"+data.presence_error+"</p>");
			}
			if (data.invalid_input) {
				errorReportDivVar.innerHTML += "<p style='color:red; margin:0px;' >"+data.invalid_input+"</p>";
				// Display error message
				displayMessage('Error', "<p style='color:red; margin:0px;' >"+data.invalid_input+"</p>");				
			} 
		}
	}
}

function appointmentCanceled(submitBtnId) {
	// console.log("The button id is: "+submitBtnId);
	// Get the index of the id button id which is consistent with other id names.
	// console.log(submitBtnId.substr(15, 16));
	index = submitBtnId.substr(15, 16);
	// Concatenate the index gotten with the prefix of the name of the id field of the database to form the id attribute name of the tag element containing the value retrieved from the database.
	aptTableDBId = "aptTableDBId"+index;
	// console.log(aptTableDBId);
	
	// Get the id of the appointment record saved in the table database
	var aptDBIdValue = document.getElementById(aptTableDBId).innerHTML;
	// console.log("The database Id is: "+aptDBIdValue);
	
	// Get the id for the div box containing the user details
	userSchCardId = "userSchCard"+index;
	var userSchCardVar = document.getElementById(userSchCardId);
	
	// Get the id of the user full name
	userFullNameId = "userName"+index;
	var userFullNameVar = document.getElementById(userFullNameId).innerHTML;
	
	// Get the id of the message left by the user for canceling
	reasonForCancelId = "reasonForCancel"+index;
	var reasonForCancelVar = document.getElementById(reasonForCancelId).value;
	// console.log("The reason for canceling is: "+reasonForCancelVar);
	
	// Get the div to output the error message
	errorReportDivId = "errorReportDiv"+index;
	var errorReportDivVar = document.getElementById(errorReportDivId);
	
	// Specify what type of user is canceling the appointment
	var userType = "customer";
	
	// Concatenate variables to send to the PHP JSON file
	var varToPHP = "id="+aptDBIdValue+"&cancelingReason="+reasonForCancelVar+"&userType="+userType;
	
	// Display the loading symbol
	var beforeSend = function() {
		$(".loader").css("display", "flex");
		$(".loader").css("color", "grey");
		$(".loader").append("<p style='margin-left: 10px;'>Hold on! Sending email and text messages.</p>");
	}
	beforeSend();

	// Create our XMLHttpRequest object
	var hr = new XMLHttpRequest();
	
	// Assign the link to the PHP file 
	var url = "../customer/customerCancelAppointment_JSON.php";
	
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
			// Hide the loader after request has been gotten
			$(".loader").fadeOut(2000);
			
			// Check if the appointment cancel was successfully updated in the database
			if (data.success) {
				// userSchCardVar.innerHTML = " ";
				// userSchCardVar.innerHTML = "<p style='color:red' >Your appointment with "+userFullNameVar+" has been canceled.</p>";
			
				$('#appointmentInfo').html("<p style='color:red' >Your appointment with "+userFullNameVar+" has been canceled.</p>");
				// Display success message
				$message = "";
				$message += "<p style='color:red' >Your appointment with "+userFullNameVar+" has been canceled.</p>";
				$message += "<p>"+data.SMSoutcome+"</p>";
				$message += "<p>"+data.emailOutcome+"</p>";
				displayMessage('Success', $message);

				// Delete the div
				userSchCardVar.remove();
			} else if (data.failed) {
				// userSchCardVar.innerHTML = " ";
				// userSchCardVar.innerHTML = "<p style='color:green' >An error occurred while saving.</p>";
			
				// Display error message
				$('#appointmentInfo').html("<p style='color:green' >An error occurred while deleting.</p>");
				displayMessage('Error', "<p style='color:red' >An error occurred while deleting.</p>");
			}
			
			// Check for error validation
			errorReportDivVar.innerHTML = " ";
			if (data.presence_error) {
				errorReportDivVar.innerHTML += "<p style='color:red; margin:0px;' >"+data.presence_error+"</p>";
				// Display error message
				displayMessage('Error', "<p style='color:red; margin:0px;' >"+data.presence_error+"</p>");
			}
			if (data.invalid_input) {
				errorReportDivVar.innerHTML += "<p style='color:red; margin:0px;' >"+data.invalid_input+"</p>";
				// Display error message
				displayMessage('Error', "<p style='color:red; margin:0px;' >"+data.invalid_input+"</p>");
			}			
		}
	}			
	
	// Send the data to PHP now... and wait for response to update the status div
	// When using post, a dynamic data can be sent through the send method. Here the variable is manually sent in.
	hr.send(varToPHP); // Actually execute the request
	
	// You could use an animated.gif loader here while you wait for data from the server.
	// checkboxOfDays.innerHTML = "requesting...";
}

function appointmentUserCanceled(submitBtnId) {
	// console.log("The button id is: "+submitBtnId);
	// Get the index of the id button id which is consistent with other id names.
	// console.log(submitBtnId.substr(15, 16));
	index = submitBtnId.substr(15, 16);
	// Concatenate the index gotten with the prefix of the name of the id field of the database to form the id attribute name of the tag element containing the value retrieved from the database.
	aptDBTableId = "aptDBTableId"+index;
	// console.log(aptDBTableId);
	
	// Get the id of the appointment record saved in the table database
	var aptDBIdValue = document.getElementById(aptDBTableId).innerHTML;
	// console.log("The database Id is: "+aptDBIdValue);
	
	// Get the id for the div box containing the user details
	userAptInfoId = "userAptInfo"+index; 
	var userSchCardVar = document.getElementById(userAptInfoId);
	
	// Get the id of the user full name
	technicianNameId = "technicianName"+index;
	var technicianNameVar = document.getElementById(technicianNameId).innerHTML;
	
	// Get the id of the message left by the user for canceling
	reasonForCancelId = "reasonForCancel"+index;
	var reasonForCancelVar = document.getElementById(reasonForCancelId).value;
	// console.log("The reason for canceling is: "+reasonForCancelVar);
	
	// Get the div to output the error message
	errorReportDivId = "errorReportDiv"+index;
	var errorReportDivVar = document.getElementById(errorReportDivId);
	
	// Specify what type of user is canceling the appointment
	var userType = "appointer";
	
	// Concatenate variables to send to the PHP JSON file
	var varToPHP = "id="+aptDBIdValue+"&cancelingReason="+reasonForCancelVar+"&userType="+userType;
	
	// Display the loading symbol
	var beforeSend = function() {
		$(".loader").css("display", "flex");
		$(".loader").css("color", "grey");
		$(".loader").append("<p style='margin-left: 10px;'>Hold on! Sending email and text messages.</p>");
	}
	beforeSend();

	// Create our XMLHttpRequest object
	var hr = new XMLHttpRequest();
	
	// Assign the link to the PHP file 
	// var url = "../customer/customerCancelAppointment_JSON.php";
	var url = "../PHP-JSON/userCancelAppointment_JSON.php";
	
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
			// Hide the loader after request has been gotten
			$(".loader").fadeOut(2000);
			
			// Check if the appointment cancel was successfully updated in the database
			if (data.success) {
				// userSchCardVar.innerHTML = " ";
				// userSchCardVar.innerHTML = "<p style='color:red' >Your appointment with "+technicianNameVar+" has been canceled.</p>";

				$('#appointmentInfo').html("<p style='color:red' >Your appointment with "+technicianNameVar+" has been canceled.</p>");
				// Display success message
				$message = "";
				$message += "<p style='color:red' >Your appointment with "+technicianNameVar+" has been canceled.</p>";
				$message += "<p>"+data.SMSoutcome+"</p>";
				$message += "<p>"+data.emailOutcome+"</p>";
				displayMessage('Success', $message);

				// Delete the div
				userSchCardVar.remove();
			} else if (data.failed) {
				// userSchCardVar.innerHTML = " ";
				// userSchCardVar.innerHTML = "<p style='color:green' >An error occurred while saving.</p>";

				// Display error message
				$('#appointmentInfo').html("<p style='color:red' >An error occurred while saving.</p>");
				displayMessage('Error', "<p style='color:red' >An error occurred while saving.</p>");
			}
			
			// Check for error validation
			errorReportDivVar.innerHTML = " ";
			if (data.presence_error) {
				errorReportDivVar.innerHTML += "<p style='color:red; margin:0px;' >"+data.presence_error+"</p>";
				// Display error message
				displayMessage('Error', "<p style='color:red; margin:0px;' >"+data.presence_error+"</p>");
			}
			if (data.invalid_input) {
				errorReportDivVar.innerHTML += "<p style='color:red; margin:0px;' >"+data.invalid_input+"</p>";
				// Display error message
				displayMessage('Error', "<p style='color:red; margin:0px;' >"+data.invalid_input+"</p>");
			} 
			
		}
	}			
	
	// Send the data to PHP now... and wait for response to update the status div
	// When using post, a dynamic data can be sent through the send method. Here the variable is manually sent in.
	hr.send(varToPHP); // Actually execute the request
	
	// You could use an animated.gif loader here while you wait for data from the server.
	// checkboxOfDays.innerHTML = "requesting...";
}

function appointmentCustomerCanceled(submitBtnId) {
	// console.log("The button id is: "+submitBtnId);
	// Get the index of the id button id which is consistent with other id names.
	// console.log(submitBtnId.substr(16, 17));
	index = submitBtnId.substr(16, 17);
	// Concatenate the index gotten with the prefix of the name of the id field of the database to form the id attribute name of the tag element containing the value retrieved from the database.
	aptDBTableId = "aptDBTableId"+index;
	// console.log(aptDBTableId);
	
	// Get the id of the appointment record saved in the table database
	var aptDBIdValue = document.getElementById(aptDBTableId).innerHTML;
	// console.log("The database Id is: "+aptDBIdValue);
	
	// Get the id for the div box containing the user details
	userAptInfoId = "userAptInfo"+index; 
	var userSchCardVar = document.getElementById(userAptInfoId);
	
	// Get the id of the user full name
	technicianNameId = "technicianName"+index;
	var technicianNameVar = document.getElementById(technicianNameId).innerHTML;
	
	// Get the id of the message left by the user for canceling in the textarea
	reasonForCancelId = "cancelingReason"+index;
	var reasonForCancelVar = document.getElementById(reasonForCancelId).value;
	// console.log("The reason for canceling is: "+reasonForCancelVar);
	
	// Get the div to output the error message
	errorReportDivId = "errorInformDiv"+index;
	var errorReportDivVar = document.getElementById(errorReportDivId);
	
	// Specify what type of user is canceling the appointment
	// This is just used to determine the behavior of the PHP function.
	var userType = "customer";
	
	// Concatenate variables to send to the PHP JSON file
	var varToPHP = "id="+aptDBIdValue+"&cancelingReason="+reasonForCancelVar+"&userType="+userType;
	
	// Display the loading symbol
	var beforeSend = function() {
		$(".loader").css("display", "flex");
		$(".loader").css("color", "grey");
		$(".loader").append("<p style='margin-left: 10px;'>Hold on! Sending email and text messages.</p>");
	}
	beforeSend();

	// Create our XMLHttpRequest object
	var hr = new XMLHttpRequest();
	
	// Assign the link to the PHP file 
	// var url = "../customer/customerCancelAppointment_JSON.php";
	var url = "../PHP-JSON/userCancelAppointment_JSON.php";
	
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
			// Hide the loader after request has been gotten
			$(".loader").fadeOut(2000);
			
			// Check if the appointment cancel was successfully updated in the database
			if (data.success) {
				// userSchCardVar.innerHTML = " ";
				// userSchCardVar.innerHTML = "<p style='color:red' >Your appointment with "+technicianNameVar+" has been canceled.</p>";

				$('#appointmentInfo').html("<p style='color:red' >Your appointment with "+technicianNameVar+" has been canceled.</p>");
				// Display success message
				$message = "";
				$message += "<p style='color:red' >Your appointment with "+technicianNameVar+" has been canceled.</p>";
				$message += "<p>"+data.SMSoutcome+"</p>";
				$message += "<p>"+data.emailOutcome+"</p>";
				displayMessage('Success', $message);

				// Delete the div
				userSchCardVar.remove();
			} else if (data.failed) {
				// userSchCardVar.innerHTML = " ";
				// userSchCardVar.innerHTML = "<p style='color:green' >An error occurred while saving.</p>";

				// Display error message
				$('#appointmentInfo').html("<p style='color:red' >An error occurred while saving.</p>");
				displayMessage('Error', "<p style='color:red' >An error occurred while saving.</p>");
			}
			
			// Check for error validation
			errorReportDivVar.innerHTML = " ";
			if (data.presence_error) {
				errorReportDivVar.innerHTML += "<p style='color:red; margin:0px;' >"+data.presence_error+"</p>";				
				// Display error message
				displayMessage('Error', "<p style='color:red; margin:0px;' >"+data.presence_error+"</p>");
			}
			if (data.invalid_input) {
				errorReportDivVar.innerHTML += "<p style='color:red; margin:0px;' >"+data.invalid_input+"</p>";
				// Display error message
				displayMessage('Error', "<p style='color:red; margin:0px;' >"+data.invalid_input+"</p>");
			} 
			
		}
	}			
	
	// Send the data to PHP now... and wait for response to update the status div
	// When using post, a dynamic data can be sent through the send method. Here the variable is manually sent in.
	hr.send(varToPHP); // Actually execute the request
	
	// You could use an animated.gif loader here while you wait for data from the server.
	// checkboxOfDays.innerHTML = "requesting...";
}