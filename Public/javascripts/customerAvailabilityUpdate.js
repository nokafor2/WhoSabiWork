
function customer_availability_update(eight_to_nine_am="", nine_to_ten_am="", ten_to_eleven_am="", eleven_to_twelve_pm="", twelve_to_one_pm="", one_to_two_pm="", two_to_three_pm="", three_to_four_pm="", four_to_five_pm="", five_to_six_pm="", six_to_seven_pm="", seven_to_eight_pm="", eight_to_nine_pm="", nine_to_ten_pm="", sltAllChkBox="", timeUL="", errorMessageDiv="") {
	// get all the check boxes ids for the time and determine if it is checked.
	var eight_to_nine_am_val = document.getElementById(eight_to_nine_am).checked;  // pass in
	var nine_to_ten_am_val = document.getElementById(nine_to_ten_am).checked;   // pass in
	var ten_to_eleven_am_val = document.getElementById(ten_to_eleven_am).checked;    // pass in
	var eleven_to_twelve_pm_val = document.getElementById(eleven_to_twelve_pm).checked;    // pass in
	var twelve_to_one_pm_val = document.getElementById(twelve_to_one_pm).checked;    // pass in
	var one_to_two_pm_val = document.getElementById(one_to_two_pm).checked;    // pass in
	var two_to_three_pm_val = document.getElementById(two_to_three_pm).checked;    // pass in
	var three_to_four_pm_val = document.getElementById(three_to_four_pm).checked;    // pass in
	var four_to_five_pm_val = document.getElementById(four_to_five_pm).checked;    // pass in
	var five_to_six_pm_val = document.getElementById(five_to_six_pm).checked;    // pass in
	var six_to_seven_pm_val = document.getElementById(six_to_seven_pm).checked;    // pass in
	var seven_to_eight_pm_val = document.getElementById(seven_to_eight_pm).checked;    // pass in
	var eight_to_nine_pm_val = document.getElementById(eight_to_nine_pm).checked;    // pass in
	var nine_to_ten_pm_val = document.getElementById(nine_to_ten_pm).checked;    // pass in
	
	// get the customer's id
	var customers_id_val = document.getElementById("customers_id").innerHTML;
	// get the date of schedule 
	var date_available_val = document.getElementById("date_available").innerHTML;
	// get the div where message will be outputted
	var errorMessageDiv = document.getElementById(errorMessageDiv);    // pass in
	
	var timeContent = [
		["eight_to_nine_am", eight_to_nine_am_val],
		["nine_to_ten_am", nine_to_ten_am_val],
		["ten_to_eleven_am", ten_to_eleven_am_val],
		["eleven_to_twelve_pm", eleven_to_twelve_pm_val],
		["twelve_to_one_pm", twelve_to_one_pm_val],
		["one_to_two_pm", one_to_two_pm_val],
		["two_to_three_pm", two_to_three_pm_val],
		["three_to_four_pm", three_to_four_pm_val],
		["four_to_five_pm", four_to_five_pm_val],
		["five_to_six_pm", five_to_six_pm_val],
		["six_to_seven_pm", six_to_seven_pm_val],
		["seven_to_eight_pm", seven_to_eight_pm_val],
		["eight_to_nine_pm", eight_to_nine_pm_val],
		["nine_to_ten_pm", nine_to_ten_pm_val]
	]
	
	// get the selected check boxes from the edited by the customer
	var trueTimeArray = [];
	var counter = 0;
	for (i=0; i<timeContent.length; i++) {
		if (timeContent[i][1] == true) {
			// console.log(timeContent[i][0]);
			trueTimeArray[counter] = timeContent[i][0];
			counter++;
		}	
	}
	
	// convert the time format from the variable type to a format that can be displayed to the user
	var displayTimeArray = [];
	for (i=0; i<trueTimeArray.length; i++) {
		// console.log(convertTimeVarToText(trueTimeArray[i]));
		displayTimeArray[i] = convertTimeVarToText(trueTimeArray[i]);
	}
	
	var ulObj = document.getElementById(timeUL);
	var liObj = document.getElementsByClassName('timeList');
	
	var vars = "eight_to_nine_am="+eight_to_nine_am_val+"&nine_to_ten_am="+nine_to_ten_am_val+"&ten_to_eleven_am="+ten_to_eleven_am_val+"&eleven_to_twelve_pm="+eleven_to_twelve_pm_val+"&twelve_to_one_pm="+twelve_to_one_pm_val+"&one_to_two_pm="+one_to_two_pm_val+"&two_to_three_pm="+two_to_three_pm_val+"&three_to_four_pm="+three_to_four_pm_val+"&four_to_five_pm="+four_to_five_pm_val+"&five_to_six_pm="+five_to_six_pm_val+"&six_to_seven_pm="+six_to_seven_pm_val+"&seven_to_eight_pm="+seven_to_eight_pm_val+"&eight_to_nine_pm="+eight_to_nine_pm_val+"&nine_to_ten_pm="+nine_to_ten_pm_val+"&customers_id="+customers_id_val+"&date_available="+date_available_val;
	
	// Create our XMLHttpRequest object
	var hr = new XMLHttpRequest();
	
	// Assign the link to the PHP file 
	var url = "../customer/customerAvailabilityUpdate_JSON.php";
	
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
			
			// console.log(data); // Used to troubleshoot.
			// create a variable for the results div that information will be outputted to
			
			if (data.record.success === 'failedUpdate') {
				errorMessageDiv.innerHTML = '';
				errorMessageDiv.innerHTML = '<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+"Sorry!!! Your schedule update failed. "+'</p>';
			} else if (data.record.success) {
				// clear the contents of the unordered list tag.
				ulObj.innerHTML = " ";
				
				/* var newTimeList = document.createElement('li');
				newTimeList.className = "timeList";
				for (i=0; i<displayTimeArray.length; i++) {
					newTimeList.textContent = displayTimeArray[i];
					ulObj.appendChild(newTimeList);
				} */
				
				// Display the updated schedule
				for (i=0; i<displayTimeArray.length; i++) {
					ulObj.innerHTML += "<li class='timeList' >"+displayTimeArray[i]+"</li>";
				}
				
				// Uncheck all the selected checkboxes after updating the database
				document.getElementById(eight_to_nine_am).checked = false;  // change
				document.getElementById(nine_to_ten_am).checked = false;  // change
				document.getElementById(ten_to_eleven_am).checked = false;  // change
				document.getElementById(eleven_to_twelve_pm).checked = false;  // change
				document.getElementById(twelve_to_one_pm).checked = false;  // change
				document.getElementById(one_to_two_pm).checked = false;  // change
				document.getElementById(two_to_three_pm).checked = false;  // change
				document.getElementById(three_to_four_pm).checked = false;  // change
				document.getElementById(four_to_five_pm).checked = false;  // change
				document.getElementById(five_to_six_pm).checked = false;  // change
				document.getElementById(six_to_seven_pm).checked = false;  // change
				document.getElementById(seven_to_eight_pm).checked = false;  // change
				document.getElementById(eight_to_nine_pm).checked = false;  // change
				document.getElementById(nine_to_ten_pm).checked = false;  // change

				// Uncheck the select all checkbox
				document.getElementById(sltAllChkBox).checked = false;  // change
				
				errorMessageDiv.innerHTML = '';
				errorMessageDiv.innerHTML = '<p style="color:green; margin-top:0px; margin-bottom:0px; ">'+"Your schedule update was successful. "+'</p>';
				
				
			} else {
				errorMessageDiv.innerHTML = '';
				errorMessageDiv.innerHTML = '<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+"No check box was selected. "+'</p>';
			}
		}
	}			
	
	// Send the data to PHP now... and wait for response to update the status div
	// When using post, a dynamic data can be sent through the send method. Here the variable is manually sent in.
	hr.send(vars); // Actually execute the request
	
	
	// You could use an animated.gif loader here while you wait for data from the server.
	errorMessageDiv.innerHTML = "requesting...";
	

}

function convertTimeVarToText(timeVar) {
	if (timeVar == 'eight_to_nine_am') {
		return '8:00 AM - 9:00 AM';
	} else if (timeVar == 'nine_to_ten_am') {
		return '9:00 AM - 10:00 AM';
	} else if (timeVar == 'ten_to_eleven_am') {
		return '10:00 AM - 11:00 AM';
	} else if (timeVar == 'eleven_to_twelve_pm') {
		return '11:00 AM - 12:00 PM';
	} else if (timeVar == 'twelve_to_one_pm') {
		return '12:00 PM - 1:00 PM';
	} else if (timeVar == 'one_to_two_pm') {
		return '1:00 PM - 2:00 PM';
	} else if (timeVar == 'two_to_three_pm') {
		return '2:00 PM - 3:00 PM';
	} else if (timeVar == 'three_to_four_pm') {
		return '3:00 PM - 4:00 PM';
	} else if (timeVar == 'four_to_five_pm') {
		return '4:00 PM - 5:00 PM';
	} else if (timeVar == 'five_to_six_pm') {
		return '5:00 PM - 6:00 PM';
	} else if (timeVar == 'six_to_seven_pm') {
		return '6:00 PM - 7:00 PM';
	} else if (timeVar == 'seven_to_eight_pm') {
		return '7:00 PM - 8:00 PM';
	} else if (timeVar == 'eight_to_nine_pm') {
		return '8:00 PM - 9:00 PM';
	} else if (timeVar == 'nine_to_ten_pm') {
		return '9:00 PM - 10:00 PM';
	}	
}