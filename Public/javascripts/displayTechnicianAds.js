function viewedCustomer(customerId) {
	var url = "./PHP-JSON/viewedCustomer_JSON.php";
	
	$.post(url, {viewedCusId : customerId}, function(data) {}, 'json');
	return true;
}

/* This function connects to the database and searches for the mechanics and spare part sellers */
function display_Technician_Ads(page) {
	// This will convert the javascript argument variable from object to array
	var args = Array.from(arguments);
	// This will take care of method overloading
	// console.log(arguments);
	if (arguments.length > 1) {
		// get the passed variables and assign them to a variable
		// requestPage, techServOrSparePart, carBrand, vehicleType, state, town, page
		var requestPage = arguments[0];
		var techServOrSparePart = arguments[1];
		var carBrand = arguments[2];
		var vehicleType = arguments[3];
		var state = arguments[4];
		var town = arguments[5];
		var page = arguments[6];

		if (requestPage == 'servicePage') {
			var technicalService = techServOrSparePart;
			var vars = "carBrand="+carBrand+"&technicalService="+technicalService+"&vehicleType="+vehicleType+"&state="+state+"&town="+town+"&page="+page;
		} else if (requestPage == 'sparePartPage') {
			var sparePart = techServOrSparePart;
			var vars = "carBrand="+carBrand+"&sparePart="+sparePart+"&vehicleType="+vehicleType+"&state="+state+"&town="+town+"&page="+page;
		}

		// Determine the URL for the php page
		var pathArray = window.location.pathname.split( '/' );
		// Get the filename of the page from the array.
		var pageName = pathArray[pathArray.length-1];
	} else {
		// get the page variable passed in
		// retrieve properties of artisan, state and town

		// get all the selected options for the car brands, vehicle and service type from the user.
		var carBrand = document.getElementById("carBrand").value;  // pass in
		var vehicleType = document.getElementById("vehicleType").value;    // pass in 
		var state = document.getElementById("state").value;    // pass in 
		var town = document.getElementById("town").value;    // pass in

		// Determine the URL for the php page
		var pathArray = window.location.pathname.split( '/' );
		// Get the filename of the page from the array.
		var pageName = pathArray[pathArray.length-1];
		if ( pageName === 'servicePage.php' ) {
			var technicalService = document.getElementById("technicalService").value;   // pass in   
			var vars = "carBrand="+carBrand+"&technicalService="+technicalService+"&vehicleType="+vehicleType+"&state="+state+"&town="+town+"&page="+page;
		} else if ( pageName === 'sparePartPage.php' ) {
			var sparePart = document.getElementById("sparePart").value;   // pass in 
			var vars = "carBrand="+carBrand+"&sparePart="+sparePart+"&vehicleType="+vehicleType+"&state="+state+"&town="+town+"&page="+page;
		}
	}
	
	// Get the page title
	var pageHeading = document.getElementById("pageHeading");	
	// get the div where the ad images will be displayed
	var adContainer1 = document.getElementById("adContainer1");    // pass in
	// get the div where the pagination will be set.
	var pagination = document.getElementById("pagination"); 
	// get the div where message will be outputted
	var messageDiv = document.getElementById("messageDiv");    // pass in
	/*
	if ( pageName === 'servicePage.php' ) {
		var vars = "carBrand="+carBrand+"&technicalService="+technicalService+"&vehicleType="+vehicleType+"&state="+state+"&town="+town+"&page="+page;
	} else if ( pageName === 'sparePartPage.php' ) {
		var vars = "carBrand="+carBrand+"&sparePart="+sparePart+"&vehicleType="+vehicleType+"&state="+state+"&town="+town+"&page="+page;
	}*/

	var beforeSend = function() {
		$(".loader").css("display", "flex");
	}
	beforeSend();
	// Create our XMLHttpRequest object
	var hr = new XMLHttpRequest();
	
	// Assign the link to the PHP file 
	var url = "./PHP-JSON/displayTechnicianAds_JSON.php";
	/*
	hr.addEventListener('load', function() {
		$(".loader").css("display", "flex");
	}, false);
	*/
	hr.open("POST", url, true);
	
	// Set content type header information for sending url encoded variables in the request
	// Since we are using a POST request, the content type is set to: x-www-form-urlencoded
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	// Send the data to PHP now... and wait for response to update the status div
	// When using post, a dynamic data can be sent through the send method. Here the variable is manually sent in.
	hr.send(vars); // Actually execute the request

	// Access the onreadystatechange event for the XMLHttpRequest object
	hr.onreadystatechange = function() {
		if (hr.readyState == 4 && hr.status == 200) {
			// This is information from the server side through the AJAX request. This will give the HttpResponseText
			// JSON.parse() function will be used instead
			// The JSON.parse() function helps get it ready for javascript parsing. 
			var data = JSON.parse(hr.responseText);
			// Hide the loader after request has been gotten
			$(".loader").fadeOut(2000);
			
			// Display a temporarily ad of no technician found.
			if (data.selectErrors) {
				adContainer1.style.height = "auto";
				pageHeading.innerHTML = 'Welcome! Please select the technician category you need.';
				adContainer1.innerHTML = '';
				pagination.innerHTML = '';
				messageDiv.innerHTML = "";
				if (data.selectErrors.vehicleTypeError == 'noVehicleType') {
					messageDiv.innerHTML += "<p>Please select vehicle type. </p>";
				}
				if (data.selectErrors.carBrandError == 'noCarBrand') {
					messageDiv.innerHTML += "<p>Please select vehicle brand. </p>";
				}
				if (data.selectErrors.technicalServiceError == 'noTechnicalService') {
					messageDiv.innerHTML += "<p>Please select vehicle technical service. </p>";
				}
				if (data.selectErrors.sparePartError == 'noSparePart') {
					messageDiv.innerHTML += "<p>Please select vehicle spare part. </p>";
				}
			} else if (data.available == "noTechnician") {
				adContainer1.style.height = "auto";
				if ( pageName === 'servicePage.php' ) {
					pageHeading.innerHTML = 'Sorry! There are no available '+carBrand.replace(/_/g, ' ')+' '+vehicleType.replace(/_/g, ' ')+' technicians for '+technicalService.replace(/_/g, ' ')+' temporarily.';
					adContainer1.innerHTML = '';
					adContainer1.innerHTML += '<div class="adContainer"><p class="adContent">There is no available technician temporarily.</p></div>';
					pagination.innerHTML = '';
					messageDiv.innerHTML = "No technician available.";
				} else {
					pageHeading.innerHTML = 'Sorry! There are no available '+carBrand.replace(/_/g, ' ')+' '+vehicleType.replace(/_/g, ' ')+' spare part sellers of '+sparePart.replace(/_/g, ' ')+' temporarily.';
					adContainer1.innerHTML = '';
					adContainer1.innerHTML += '<div class="adContainer"><p class="adContent">There is no available spare part seller temporarily.</p></div>';
					pagination.innerHTML = '';
					messageDiv.innerHTML = "No spare part seller available.";
				}
			} else if (data.details){
				// Display the customers ads found
				adContainer1.style.height = "auto";
				adContainer1.innerHTML = ''; // Erase the previous contents contained within the container to display the ads
				pagination.innerHTML = ''; // This will erase any pagination that was previously present. It will also prevent previous pagination to persist.
				if ( pageName === 'servicePage.php' ) {
					pageHeading.innerHTML = 'The available '+vehicleType.replace(/_/g, ' ')+' technicians for '+carBrand.replace(/_/g, ' ')+' '+technicalService.replace(/_/g, ' ')+' are below.';
				} else {
					pageHeading.innerHTML = 'The available '+vehicleType.replace(/_/g, ' ')+' spare part sellers for '+carBrand.replace(/_/g, ' ')+' '+sparePart.replace(/_/g, ' ')+' are below.';
				}
				for (var obj in data.details) {					
					if (data.details[obj].phone_validated == 1) {
						// var validatedOuput = "<p class='adContent'>Number validated</p>";
						var validatedOuput = "";
					} else {
						var validatedOuput = "<p class='adContent phoneNotValidated' >Number not validated</p>";
					}
					// onclick="return viewedCustomer('+data.details[obj].customerId+');"
					// adContainer1.innerHTML += '<div class="adContainer" ><a class="adLink" href="./customer/customerHomePage.php?id='+data.details[obj].customerId+'" ><p class="adImage"><img src="'+data.details[obj].image_path+'" alt="This technician ad image is not available" name="AdImage" width="300" height="300" /></p><h1 class="adTitle">'+data.details[obj].business_title+'</h1></a><p class="adContent"><i class="far fa-user" style="padding-right:10px;"></i>'+data.details[obj].full_name+'</p><p class="adContent"><i class="far fa-address-card fa-lg" style="padding-right:10px;"></i>'+data.details[obj].address+'</p><p class="adContent telephone"><i class="fas fa-phone" style="padding-right:10px;"></i>'+data.details[obj].phone_number+'</p>'+validatedOuput+'<div class="rating jDisabled" data-average="'+data.details[obj].rateValue+'" data-id="'+data.details[obj].rateCustomerId+'" ></div></div>';
					// adContainer1.innerHTML += '<div class="adContainer" ><p class="adImage" onclick="redirectTo('+data.details[obj].customerId+')"><img src="'+data.details[obj].image_path+'" alt="This technician ad image is not available" name="AdImage" width="300" height="300" /></p><h1 class="adTitle" onclick="redirectTo('+data.details[obj].customerId+')">'+data.details[obj].business_title+'</h1><p class="adContent"><i class="far fa-user" style="padding-right:10px;"></i>'+data.details[obj].full_name+'</p><p class="adContent"><i class="far fa-address-card fa-lg" style="padding-right:10px;"></i>'+data.details[obj].address+'</p><p class="adContent telephone"><i class="fas fa-phone" style="padding-right:10px;"></i>'+data.details[obj].phone_number+'</p>'+validatedOuput+'<div class="rating jDisabled" data-average="'+data.details[obj].rateValue+'" data-id="'+data.details[obj].rateCustomerId+'" ></div></div>';
					var imgPath = "images/"+data.details[obj].image_path;
					// var imgPath = data.details[obj].image_path;
					adContainer1.innerHTML += '<div class="adContainer" ><p class="adImage" onclick="redirectTo('+data.details[obj].customerId+')"><div id="AdImage'+obj+'" name="AdImage" style="background: url('+imgPath+'); background-repeat: no-repeat; background-size: cover; background-position: center; width:300px; height:300px;" ></div></p><h1 class="adTitle" onclick="redirectTo('+data.details[obj].customerId+')">'+data.details[obj].business_title+'</h1><p class="adContent"><i class="far fa-user" style="padding-right:10px;"></i>'+data.details[obj].full_name+'</p><p class="adContent"><i class="far fa-address-card fa-lg" style="padding-right:10px;"></i>'+data.details[obj].address+'</p><p class="adContent telephone"><i class="fas fa-phone" style="padding-right:10px;"></i>'+data.details[obj].phone_number+'</p>'+validatedOuput+'<div class="rating jDisabled" data-average="'+data.details[obj].rateValue+'" data-id="'+data.details[obj].rateCustomerId+'" ></div></div>';					
					// The rating jquery function can also be put here, but its on necessary running multiple times.
				}
				// Display the ratings of the various users
				$(document).ready(function(){
					$(".rating").jRating({
						// decimalLength : 1, // number of decimal in the rate
						rateMax : 5, // maximal rate - integer from 0 to 9999 (or more)
						// phpPath: 'libs/rating.php',
						bigStarsPath : 'javascripts/jRating/jquery/icons/stars.png', // path of the icon stars.png
						smallStarsPath : 'javascripts/jRating/jquery/icons/small.png', // path of the icon small.png
						
						// canRateAgain : true,
						// nbRates : 3
					});
				});
				// Check for pagination
				if (data.details[obj].total_pages > 1) {
					pagination.innerHTML = '';
					// Call a function to know if the pagination has previous page.
					if (data.details[obj].has_previous_page == 'true') {
						// pagination.innerHTML += '<a href="#" onclick="display_Technician_Ads('+data[obj].previous_page+')" > &laquo; Previous </a> ';
						pagination.innerHTML += '<input class="btnStyle1" type="button" value="&laquo; Previous" onclick="display_Technician_Ads('+data.details[obj].previous_page+');"/> '; 
					}
					// Iterate through the list of pages if there is more than one page.
					for(var i=1; i <= data.details[obj].total_pages; i++) {
						// Checks if you are in the current page
						if(i == data.details[obj].page) {
							// For the current page, there will be no link
							pagination.innerHTML += "<span class='selected'> Page "+i+" </span>";
						} else {
							// If there is more than one page, output it by concatenating the location to the link through the $_GET global variable
							// pagination.innerHTML += " <a href='#' onclick='display_Technician_Ads("+i+")'> Page "+i+" </a> ";
							pagination.innerHTML += '<input class="btnStyle1" type="button" value="Page '+i+'" onclick="display_Technician_Ads('+i+');"/> ';
						}
					}
					// If it has next page, then it should have a previous page.
					if(data.details[obj].has_next_page == 'true') { 
						// Display the link to the next page and set the dynamic link in the $_GET global variable
						// pagination.innerHTML += ' <a href="#" onclick="display_Technician_Ads('+data[obj].next_page+')" > Next &raquo; </a>';
						pagination.innerHTML += '<input class="btnStyle1" type="button" value="Next &raquo;" onclick="display_Technician_Ads('+data.details[obj].next_page+');"/> '; 
					}
					
				}
				// messageDiv.innerHTML = "Technician available.";
				messageDiv.innerHTML = "";
			}
			
		}
	}
	
	
	// You could use an animated.gif loader here while you wait for data from the server.
	// messageDiv.innerHTML = "Not available";
	// $(".loader").css("display", "none");
	/* $(".loader").fadeOut(); */
}



$("#searchBtn").click(function(event) {
	event.preventDefault();
	var searchval = $("#searchInput").val();
	searchArtisan(searchval);
});

function searchArtisan(searchVal) {
	// get the div where the ad images will be displayed
	var adContainer1 = document.getElementById("adContainer1");
	// Get the div to output error message
	var messageDiv = document.getElementById("messageDiv");	
	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];

	dataVariable = {searchVal: searchVal, searchOrigin: "button", searchPage: pageName};
	
	$.ajax ({
		url: './PHP-JSON/displayTechnicianAds_JSON.php', 
		dataType: 'json',
		type: 'POST',
		data: dataVariable,

		beforeSend: function() {
			$(".loader").css("display", "flex");
		},

		complete: function() {
			// $(".loader").css("display", "none");
			$(".loader").fadeOut(2000);
		},
		
		success: function(data) {
			if (data.customersData === "No result") {
				adContainer1.innerHTML = "";
				messageDiv.innerHTML = "Not available.";
			} else {
				// This function will display the artisan ad
				// It is located in the genericJSs.js file
				displayAd(data.customersData);
			}
		}
	});
}