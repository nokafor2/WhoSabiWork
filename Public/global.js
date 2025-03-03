$('#add').on("submit", function() {
	var that = $(this),
		returnedResult = $('#returnedResult'),
		// This will take the contents of the form and put it in a string that will be submitted via a request to a page in HTTP to a page 
		contents = that.serialize();
	
	// Use this to check/troubleshoot that data was passed.
	// console.log(contents);
	
	// Jquery ajax method will be used to parse the contents.
	$.ajax({
		url: 'add.php', // specify the url of the PHP file where the data will be sent
		dataType: 'json', // specify the data type, however the function is smart enough to figure that out
		type: 'post',    // the type of request that will be made to the PHP file
		data: contents,    // You can parse a data here or a serialized content here
		
		// This is a success call back function, the data variable in the function is used to grab the data from the call back function usually data coming back from the PHP file or data from an API 
		success: function(data) {
			// console.log(1);
			returnedResult.append("The result is: "+data.result);
		}
	});
	
	// This will prevent the form from submitting.
	return false;
});