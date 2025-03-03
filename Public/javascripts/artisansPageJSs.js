function viewedCustomer(customerId) {
	var url = "./PHP-JSON/viewedCustomer_JSON.php";
	
	$.post(url, {viewedCusId : customerId}, function(data) {}, 'json');
	return true;
}

/* This function connects to the database and searches for the artisans */
function display_artisan_ads(page) {
	// This will convert the javascript argument variable from object to array
	var args = Array.from(arguments);
	// This will take care of method overloading
	if (arguments.length > 1) {
		// get the passed variables and assign them to a variable
		var artisan = arguments[0];
		var state = arguments[1];
		var town = arguments[2];
		var page = arguments[3];
	} else {
		// get the page variable passed in
		// retrieve properties of artisan, state and town

		// get all the selected options for the artisan, state and town
		var artisan = document.getElementById("artisanType").value;  // pass in
		var state = document.getElementById("state").value;    // pass in 
		var town = document.getElementById("town").value;    // pass in 
	}		
	
	// get page title
	var pageHeading = document.getElementById("pageHeading"); 	
	// get the div where the ad images will be displayed
	var adContainer1 = document.getElementById("adContainer1");    // pass in
	// get the div where the pagination will be set.
	var pagination = document.getElementById("pagination"); 
	// get the div where message will be outputted
	var messageDiv = document.getElementById("messageDiv");    // pass in
	// Set a default error message.
	messageDiv.innerHTML = "Not available";
	
	dataVariable = {artisanType: artisan, state: state, town: town, page: page};
	$.ajax ({
		url: './PHP-JSON/displayArtisanAds_JSON.php', 
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
			// Display a temporarily ad of no artisan found.
			if (data.selectErrors) {
				adContainer1.style.height = "auto";
				pageHeading.innerHTML = 'Welcome! Please select the artisan category you need.';
				adContainer1.innerHTML = '';
				pagination.innerHTML = '';
				messageDiv.innerHTML = "";
				if (data.selectErrors == 'noSelectedArtisan') {
					messageDiv.innerHTML += "<p>Please select an artisan category. </p>";
				}
			} else if (data.available == "noAvailArtisan") {
				adContainer1.style.height = "auto";
				pageHeading.innerHTML = 'Sorry! There are no available '+artisan.replace(/_/g, ' ')+' artisans temporarily.';
				adContainer1.innerHTML = '';
				adContainer1.innerHTML += '<div class="adContainer" ><p class="adContent">There is no available artisan temporarily.</p></div>';
				pagination.innerHTML = '';
				messageDiv.innerHTML = "No artisan available.";
			} else if (data.details){
				// Display the customers ads found
				adContainer1.style.height = "auto";
				adContainer1.innerHTML = ''; // Erase the previous contents contained within the container to display the ads
				pagination.innerHTML = ''; // This will erase any pagination that was previously present. It will also prevent previous pagination to persist.
				pageHeading.innerHTML = 'The available '+artisan.replace(/_/g, ' ')+' artisans are below.';
				// displayAd(data.details);
				
				for (var obj in data.details) {
					if (data.details[obj].phone_validated == 1) {
						// var validatedOuput = "<p class='adContent'>Number validated</p>";
						var validatedOuput = "";
					} else {
						var validatedOuput = "<p class='adContent phoneNotValidated' >Number not validated</p>";
					}
					// This code was used to store the number of views of customers when clicked
					// onclick="return viewedCustomer('+data.details[obj].customerId+');"
					// adContainer1.innerHTML += '<div class="adContainer" ><a class="adLink" href="./customer/customerHomePage.php?id='+data.details[obj].customerId+'"  ><p class="adImage"><img src="'+data.details[obj].image_path+'" alt="This artisan ad image is not available" name="AdImage" width="300" height="300" /></p><h1 class="adTitle max-lines" >'+data.details[obj].business_title+'</h1></a><p class="adContent max-lines" onclick="redirectTo('+data.details[obj].customerId+')"><i class="far fa-user" style="padding-right:10px;"></i>'+data.details[obj].full_name+'</p><p class="adContent max-lines"><i class="far fa-address-card fa-lg" style="padding-right:10px;"></i>'+data.details[obj].address+'</p><p class="adContent telephone"><i class="fas fa-phone" style="padding-right:10px;"></i>'+data.details[obj].phone_number+'</p>'+validatedOuput+'<div class="rating jDisabled" data-average="'+data.details[obj].rateValue+'" data-id="'+data.details[obj].rateCustomerId+'" ></div></div>';
					//adContainer1.innerHTML += '<div class="adContainer" ><p class="adImage" onclick="redirectTo('+data.details[obj].customerId+')"><img src="'+data.details[obj].image_path+'" alt="This artisan ad image is not available" name="AdImage" width="300" height="300" /></p><h1 class="adTitle max-lines" onclick="redirectTo('+data.details[obj].customerId+')">'+data.details[obj].business_title+'</h1><p class="adContent max-lines"><i class="far fa-user" style="padding-right:10px;"></i>'+data.details[obj].full_name+'</p><p class="adContent max-lines"><i class="far fa-address-card fa-lg" style="padding-right:10px;"></i>'+data.details[obj].address+'</p><p class="adContent telephone"><i class="fas fa-phone" style="padding-right:10px;"></i>'+data.details[obj].phone_number+'</p>'+validatedOuput+'<div class="rating jDisabled" data-average="'+data.details[obj].rateValue+'" data-id="'+data.details[obj].rateCustomerId+'" ></div></div>';
					var imgPath = "images/"+data.details[obj].image_path;
					adContainer1.innerHTML += '<div class="adContainer" ><p class="adImage" onclick="redirectTo('+data.details[obj].customerId+')"><div id="AdImage'+obj+'" name="AdImage" style="background: url('+imgPath+'); background-repeat: no-repeat; background-size: cover; background-position: center; width: 300px; height: 300px;" ></div></p><h1 class="adTitle max-lines" onclick="redirectTo('+data.details[obj].customerId+')">'+data.details[obj].business_title+'</h1><p class="adContent max-lines"><i class="far fa-user" style="padding-right:10px;"></i>'+data.details[obj].full_name+'</p><p class="adContent max-lines"><i class="far fa-address-card fa-lg" style="padding-right:10px;"></i>'+data.details[obj].address+'</p><p class="adContent telephone"><i class="fas fa-phone" style="padding-right:10px;"></i>'+data.details[obj].phone_number+'</p>'+validatedOuput+'<div class="rating jDisabled" data-average="'+data.details[obj].rateValue+'" data-id="'+data.details[obj].rateCustomerId+'" ></div></div>';
					// The rating jquery function can also be put here, but its on necessary running multiple times.
				}
				// displayAd(data.details);

				// Display the ratings of the various users 
				// this has to be declared after the rating class has been initialized
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
					// Call a function to know if the pagination has next page.
					if (data.details[obj].has_previous_page == 'true') {
						// pagination.innerHTML += '<a href="#" onclick="display_artisan_ads('+data[obj].previous_page+')" > &laquo; Previous </a> ';
						pagination.innerHTML += '<input class="btnStyle1" type="button" value="&laquo; Previous" onclick="display_artisan_ads('+data.details[obj].previous_page+');"/> '; 
					}
					// Iterate through the list of pages if there is more than one page.
					for(var i=1; i <= data.details[obj].total_pages; i++) {
						// Checks if you are in the current page
						if(i == data.details[obj].page) {
							// For the current page, there will be no link
							pagination.innerHTML += "<span class='selected'> Page "+i+" </span>";
						} else {
							// If there is more than one page, output it by concatenating the location to the link through the $_GET global variable
							// pagination.innerHTML += " <a href='#' onclick='display_artisan_ads("+i+")'> Page "+i+" </a> ";
							pagination.innerHTML += '<input class="btnStyle1" type="button" value="Page '+i+'" onclick="display_artisan_ads('+i+');"/> ';
						}
					}
					// If it has next page, then it should have a previous page.
					if(data.details[obj].has_next_page == 'true') { 
						// Display the link to the next page and set the dynamic link in the $_GET global variable
						// pagination.innerHTML += ' <a href="#" onclick="display_artisan_ads('+data[obj].next_page+')" > Next &raquo; </a>';
						pagination.innerHTML += '<input class="btnStyle1" type="button" value="Next &raquo;" onclick="display_artisan_ads('+data.details[obj].next_page+');"/> '; 
					}
					
				}
				// messageDiv.innerHTML = "Artisan available.";
				messageDiv.innerHTML = "";
			}
		}
	});
	
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
		url: './PHP-JSON/displayArtisanAds_JSON.php', 
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
				messageDiv.innerHTML = "No artisan available.";
			} else {
				// This function will display the artisan ad
				// It is located in the genericJSs.js file
				displayAd(data.customersData);
			}
		}
	});
}

function searchArtisan1(searchVal) {
	dataVariable = {searchVal: searchVal};

	if (searchVal !== '') {
		$.ajax ({
			url: './PHP-JSON/displayArtisanAds_JSON.php', 
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
				console.log(data.customersData);
				/*
				var searchResult = document.getElementById("searchResult");
				var searchList = document.getElementById("searchList");				

				// check that the array for data is not empty				
				// Array.isArray(data.customersData) || data.customersData.length
				if (data.customersData !== 'No result') {
					// Clear all the contents of the list
					searchList.innerHTML = "";
					// Create a list for the search results
					for (let i = 0; i < data.customersData.length; i++) {
						var li = document.createElement("li");
						// li.innerHTML = data.customersData[i]['firstName']+" "+data[i]['lastName'];
						li.innerHTML = data.customersData[i].firstName+" "+data.customersData[i].lastName;
						searchList.appendChild(li);
						// console.log(data.customersData[i].firstName);
					}
				} else {
					searchList.innerHTML = "";
				}*/
			}
		});
	}
	
}

function searchArtisan2(searchVal) {
	dataVariable = {searchVal: searchVal};

	if (searchVal !== '') {
		var searchedVals = $.ajax ({
			url: './PHP-JSON/displayArtisanAds_JSON.php', 
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
				data.customersData;
			}
		});
	}
	return searchedVals;
}

/*
var searchval = $('#searchInput').val();
$('#searchInput').typeahead({
  hint: true,
  highlight: true,
  minLength: 1
},
{
  name: 'customers',
  source: searchArtisan2(searchval);
}); */

/*
var searchval = $('#searchInput').val();
var customersDetails = searchArtisan2("okafor");
console.log(customersDetails);
console.log(customersDetails.customersData); */
/*
$('#searchInput').typeahead(null, {
	name: 'customers',
  source: customersDetails.customersData
}); */

// console.log(searchArtisan2('okafor'));



