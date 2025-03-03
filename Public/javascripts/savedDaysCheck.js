
// The myTimer variable is initialized before it is used in the function
// var myTimer;
function ajax_json_data(){
	var daysSelectMenu = document.getElementById("set_days").value;

	// Create our XMLHttpRequest object
	var hr = new XMLHttpRequest();
	
	// Create some variables we need to send to our PHP file 
	var url = "../customer/savedDaysCheck_JSON.php";
	
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
			// create a variable for the results div that information will be outputted to
			
			if (data.record.exist === 'empty') {
				usernameMessage.innerHTML = '<p style="color:red; margin-top:0px; margin-bottom:0px; ">'+"No username was entered. "+data.record.exist+'</p>';
			} else if (data.record.exist) {
				usernameMessage.innerHTML = '<p style="color:red; margin-top:0px; margin-bottom:0px;">'+"Username already exist, please try another one. "+data.record.exist+'</p>';
			} else {
				usernameMessage.innerHTML = '<p style="color:green; margin-top:0px; margin-bottom:0px;">'+"Username does not exist, it can be used. "+data.record.exist+'</p>';
			}
			
		}
	}
	
	// Send the data to PHP now... and wait for response to update the status div
	// When using post, a dynamic data can be sent through the send method. Here the variable is manually sent in.
	hr.send("send=connect"); // Actually execute the request
	
	// You could use an animated.gif loader here while you wait for data from the server.
	usernameMessage.innerHTML = "requesting...";
	
	// This executes this function after 10 seconds.
	// myTimer = setTimeout('ajax_json_data()',2000);
}