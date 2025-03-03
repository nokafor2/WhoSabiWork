// This function will load the jquery behaviors of some radio buttons when they are clicked
$("document").ready(function() {
	// This displays the categories of artisans when the artisan radio button is clicked
	$("#artisan_btn").on("click", function() {
		$("#artisanDiv").show();
		$("#sellerDiv").hide();
		$("#vehCategory").hide();
		$("#technicalServiceDiv").hide();
		$("#sparePartDiv").hide();
		
		$("#carBrandsDiv").hide();
		$("#busBrandsDiv").hide();
		$("#truckBrandsDiv").hide();
	});

	// This displays the categories of sellers when the seller radio button is clicked
	$("#seller_btn").on("click", function() {
		$("#sellerDiv").show();
		$("#artisanDiv").hide();
		$("#vehCategory").hide();
		$("#technicalServiceDiv").hide();
		$("#sparePartDiv").hide();
		
		$("#carBrandsDiv").hide();
		$("#busBrandsDiv").hide();
		$("#truckBrandsDiv").hide();
	});
	
	// This displays the technical services when the technical service radio button is clicked
	$("#technician_btn").on("click", function() {
		$("#technicalServiceDiv").show();
		$("#vehCategory").show();
		$("#artisanDiv").hide();
		$("#sellerDiv").hide();
		$("#sparePartDiv").hide();
	});
	
	// This displays the spare parts categories when the spare part radio button is clicked
	$("#spare_part_btn").on("click", function() {
		$("#sparePartDiv").show();
		$("#vehCategory").show();
		$("#artisanDiv").hide();
		$("#technicalServiceDiv").hide();
		$("#sellerDiv").hide();
	});
	
	// This displays the various car brands when the car brand radio button is clicked
	$("#cars_btn").on("click", function() {
		$("#carBrandsDiv").show();
		$("#busBrandsDiv").hide();
		$("#truckBrandsDiv").hide();
	});
	
	// This displays the various bus brands when the bus brand radio button is clicked
	$("#buses_btn").on("click", function() {
		$("#busBrandsDiv").show();
		$("#carBrandsDiv").hide();
		$("#truckBrandsDiv").hide();
	});
	
	// This displays the various truck brands when the truck brand radio button is clicked
	$("#trucks_btn").on("click", function() {
		$("#truckBrandsDiv").show();
		$("#busBrandsDiv").hide();
		$("#carBrandsDiv").hide();
	});
	
	
	// Get the vehicle services for the cars
	$.ajax ({
		url: 'PHP-JSON/popVehiclesServicesParts_JSON.php', 
		dataType: 'json',
		type: 'POST',
		
		success: function(data) {
			populateCheckboxes(data.artisans, "artisanDiv", "artisans"); // artisans changed from artisans[], used for PHP
			populateCheckboxes(data.sellers, "sellerDiv", "sellers"); // sellers changed from sellers[], used for PHP
			populateCheckboxes(data.techServices, "technicalServiceDiv", "technical_services"); // technical_services changed from technical_services[], used for PHP
			populateCheckboxes(data.spareParts, "sparePartDiv", "spare_parts"); // spare_parts changed from spare_parts[], used for PHP
			populateCheckboxes(data.carBrands, "carBrandsDiv", "car_brands"); // car_brands changed from car_brands[], used for PHP
			populateCheckboxes(data.busBrands, "busBrandsDiv", "bus_brands"); // bus_brands changed from bus_brands[], used for PHP
			populateCheckboxes(data.truckBrands, "truckBrandsDiv", "truck_brands"); // truck_brands changed from truck_brands[], used for PHP
		}
	});
	
});

/* This function takes the contents of the data received from 
the Ajax, the div name where the contents will be outputted, 
the array name of the checkboxes that will collect the selected 
options in the attribute of input tag. */
function populateCheckboxes(contents, divNameId="", arrayName="") {
	// Get the element id of the div to attach the checkboxes
	var divName = document.getElementById(divNameId);
		
	for (var content in contents) {
		// Create a label tag
		var labelOption = document.createElement("label");
		// Set the class for the input tag
		labelOption.setAttribute("class", "checkboxStyle");
		// Create an input tag
		var inputOption = document.createElement("input");
		// Set the type of the input tag
		inputOption.setAttribute("type", "checkbox");
		// Set the value of the input tag
		inputOption.setAttribute("id", ""+content);
		// Set the value of the input tag
		inputOption.setAttribute("value", content);
		// Set the validation function
		inputOption.setAttribute("onclick", "validateInput(this);");		
		// set the name of the input tag
		inputOption.name = arrayName;
		// Attach the input tag into the label tag
		labelOption.appendChild(inputOption);
		// Capitalize the first letter in the string
		content = content.charAt(0).toUpperCase()+content.slice(1);
		// Remove the underscores in the string
		content = content.replace(/_/g, ' ');
		// Create a text node for the label tag
		var labelText = document.createTextNode(content);
		// Attach the label text in the label tag
		labelOption.appendChild(labelText);
		// Append the label tag after the last element in the div for the checkboxes
		divName.appendChild(labelOption);
	}
}

function getTowns(state, town) {
	// Get the state id
	var stateId = document.getElementById(state);
	var townId = document.getElementById(town);
	var stateVal = stateId.value;

	var towns = [];
	var phpURL = 'PHP-JSON/getTowns_JSON.php';
	var stateKey = {state: stateVal};
	$.ajax({
		type: 'POST',
		url: phpURL,
		dataType: 'json',
		data: stateKey,
		
		success: function(data) {
			if (data.success == true) {
				// This converts the object values to array values
				// const towns = Object.values(data.result);
				// This converts the object values to array values
				// This is a well supported method
				const towns = Object.keys(data.result).map(i => data.result[i]);
				
				// populate the multiple select menu of the town
				populateTown(towns, townId);
			}
		}
	});
}

// This function will populate the towns gotten in the array
// in the multiple select menu of town on the webpage
function populateTown(towns, selMenuTown) {
	// Hide the other town textarea if it is open
	$('#otherTown').css('display', 'none');
	// clear the values of the options before appending new enstries upon change
	selMenuTown.innerHTML = "";
	// sort the towns array alphabetically
	towns.sort();

	// Create the dummy select option
	var newOption = document.createElement("option");
	newOption.value = "";
	newOption.innerHTML = "Select";
	selMenuTown.options.add(newOption);
	
	// Use a loop to populate the options for the select menu
	for (var town in towns) {
		// create an 'option' tag object to be used to populate the select contents
		var newOption = document.createElement("option");
		// Use this to access the value of the array
		// replace the spaces with hyphen for the values of the option
		newOption.value = towns[town].replace(' ', '_');
		// Capitalize the first letter in the string
		towns[town] = towns[town].charAt(0).toUpperCase()+towns[town].slice(1);
		// Remove the underscores in the string
		towns[town] = towns[town].replace(/_/g, ' ');
		// Use this to access the label of the array
		newOption.innerHTML = towns[town].replace('_', ' ');
		selMenuTown.options.add(newOption);
	}

	// Create the other select option
	var newOption = document.createElement("option");
	newOption.value = "other";
	newOption.innerHTML = "Other";
	selMenuTown.options.add(newOption);
}

function showTownTextArea(selMenuTownId) {
	var townId = document.getElementById(selMenuTownId);
	town = townId.value;

	if (town === 'other') {
		$('#otherTown').css('display', 'block');
		$('#townContent').css('height', '110px');
	} else {
		$('#otherTown').css('display', 'none');
	}
}

// This function sets a timeout when the message div displays a message on the page
$(document).ready(function(){
	$('.message').fadeIn('slow');
	
	setTimeout(function() {
		$('.message').fadeOut('slow');
		$('#pageTitle').css('margin-top', '0px').delay(5000);
		$('#pageTitle').css('padding-top', '10px').delay(5000);
	}, 10000);
});

// This function sets a timeout when the error div or feedback div displays a message on the page
$(document).ready(function(){
	$('.error, #feedback, #feedback2').fadeIn('slow');
	
	setTimeout(function(){
		$('.error, #feedback, #feedback2').fadeOut('slow');
		
	}, 15000);
});


/* Validate the web form fields */
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
			if (response.success) {
				$('#'+inputIdVal).css('box-shadow', 'inset 0 0 10px green');
				$('#'+inputName+'_message').hide();
			} else if (response.validateError) {
				// Concatenate the error
				var error = "";
				for (var obj in response.validateError) {
					error += "<p style='margin-top: 0px; color: red;'>"+response.validateError[obj]+"</p>"
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

// This function will send details of the customer creating an account to the database
// The form input will be validated first
$('#submit').on('click', function(event) {
	event.preventDefault();
	// Get the first name
	var firstName = $('#first_name').val();
	// Get the last name
	var lastName = $('#last_name').val();
	// Get the gender
	var genders = document.profileEdit.gender;
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
	var username = $('#username').val();
	// Get the passwords
	var password = $('#password').val();
	var confirmPassword = $('#confirm_password').val();
	// Get the business name
	var businessName = $('#business_name').val();
	// Get the phone number
	var phoneNumber = $('#business_phone_number').val();
	// Get the email 
	var email = $('#business_email').val();
	// Get the state 
	var state = $('#state').val();
	// Get the town 
	var town = $('#town').val();
	// Check if other town was selected
	if (town === 'other') {
		var town = $('#other_town').val();
	}
	// Get the address line 1 
	var address_line_1 = $('#address_line_1').val();
	// Get the address line 2 
	var address_line_2 = $('#address_line_2').val();
	// Get the address line 3 
	var address_line_3 = $('#address_line_3').val();
	// Get the business category
	var business_category = getSelectedBusinessType();
	// Form data for the available inputs
	var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category};	
	// Get the sellected check box options of the business category
	if (business_category === 'mobile_market') {
		var selected_inventories = getSelectedInventories();
		
		// Concatenate the data to be sent to the PHP file for processing
		var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, sellers: selected_inventories};
	} else if (business_category === 'artisan') {
		var selected_artisans = getSelectedArtisanSkills();

		// Concatenate the data to be sent to the PHP file for processing
		var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, artisans: selected_artisans};
	} else if (business_category === 'technician') {
		var selected_tech_serv = getSelectedTechnicalSkills();		

		// Get the selected automobile
		var selectedVehicle = getSelectedVehicle();

		// Concatenate the data to be sent to the PHP file for processing
		var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, technical_services: selected_tech_serv, vehicle_category: selectedVehicle};
		if (selectedVehicle === 'cars') {
			// Get selected car brands
			var selectedCars = getSelectedCarBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, technical_services: selected_tech_serv, vehicle_category: selectedVehicle, car_brands: selectedCars};
		} else if (selectedVehicle === 'buses') {
			// Get selected car brands
			var selectedBuses =  getSelectedBusBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, technical_services: selected_tech_serv, vehicle_category: selectedVehicle, bus_brands: selectedBuses};
		} else if (selectedVehicle === 'trucks') {
			// Get selected car brands
			var selectedTrucks =  getSelectedTruckBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, technical_services: selected_tech_serv, vehicle_category: selectedVehicle, truck_brands: selectedTrucks}; 
		}
	} else if (business_category === 'spare_part_seller') {
		var selected_spare_parts = getSelectedSpareParts();
		
		// Get the selected automobile
		var selectedVehicle = getSelectedVehicle();

		// Concatenate the data to be sent to the PHP file for processing
		var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, spare_parts: selected_spare_parts, vehicle_category: selectedVehicle};
		if (selectedVehicle === 'cars') {
			// Get selected car brands
			var selectedCars = getSelectedCarBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, spare_parts: selected_spare_parts, vehicle_category: selectedVehicle, car_brands: selectedCars};
		} else if (selectedVehicle === 'buses') {
			// Get selected car brands
			var selectedBuses =  getSelectedBusBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, spare_parts: selected_spare_parts, vehicle_category: selectedVehicle, bus_brands: selectedBuses};
		} else if (selectedVehicle === 'trucks') {
			// Get selected car brands
			var selectedTrucks =  getSelectedTruckBrands(); 
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, spare_parts: selected_spare_parts, vehicle_category: selectedVehicle, truck_brands: selectedTrucks};
		}
	}

	// Check the session for errors during form filling
	$.ajax({
		url: 'PHP-JSON/checkFormValidation_JSON.php', 
		dataType: 'json',
		type: 'POST',
		data: formData,

		success: function(response) {
			// check if there are errors in the validation
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
				console.log(errorArray);
			}	else if (response.validationResult) {
				// save the form data
				saveBussAccountFormData();				
			}	else if (response.noData) {
				displayMessage("Error", "Please complete the form before submitting.");
			}
		}
	});	
});


/* This function gets the error message div for the respective error message input passed in */
function returnErrorDiv(reference) {
	if (reference.search('first_name') >= 0) {
		return ['first_name', 'first_name_message'];
	} else if (reference.search('last_name') >= 0) {
		return ['last_name', 'last_name_message'];
	} else if (reference.search('gender') >= 0) {
		return ['gender', 'gender_message'];
	} else if (reference.search('username') >= 0) {
		return ['username', 'username_message'];
	} else if (reference.search('confirm_password') >= 0) {
		return ['confirm_password', 'confirm_password_message'];	
	} else if (reference.search('password') >= 0) {
		return ['password', 'password_message'];
	} else if (reference.search('phone_number') >= 0) {
		return ['business_phone_number', 'business_phone_number_message'];
	} else if (reference.search('email') >= 0) {
		return ['business_email', 'business_email_message'];
	} else if (reference.search('business_name') >= 0) {
		return ['business_name', 'business_name_message'];
	} else if (reference.search('state') >= 0) {
		return ['state', 'state_message'];
	} else if (reference.search('town') >= 0) {
		return ['town', 'town_message'];
	} else if (reference.search('other_town') >= 0) {
		return ['other_town', 'other_town_message'];
	} else if (reference.search('address_line_1') >= 0) {
		return ['address_line_1', 'address_line_1_message'];
	} else if (reference.search('address_line_2') >= 0) {
		return ['address_line_2', 'address_line_2_message'];
	} else if (reference.search('address_line_3') >= 0) {
		return ['address_line_3', 'address_line_3_message'];
	} else if (reference.search('business_category') >= 0) {
		return ['business_category', 'business_category_message'];
	} else if (reference.search('artisans') >= 0) {
		return ['artisans', 'artisans_message'];
	} else if (reference.search('technical_services') >= 0) {
		return ['technical_services', 'technical_services_message'];
	} else if (reference.search('spare_parts') >= 0) {
		return ['spare_parts', 'spare_parts_message'];
	} else if (reference.search('sellers') >= 0) {
		return ['sellers', 'sellers_message'];
	} else if (reference.search('vehicle_category') >= 0) {
		return ['vehicle_category', 'vehicle_category_message'];
	} else if (reference.search('car_brands') >= 0) {
		return ['car_brands', 'car_brands_message'];
	} else if (reference.search('bus_brands') >= 0) {
		return ['bus_brands', 'bus_brands_message'];
	} else if (reference.search('truck_brands') >= 0) {
		return ['truck_brands', 'truck_brands_message'];
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
	$('#business_phone_number_message').empty();
	$('#business_name_message').empty();
	$('#business_email_message').empty();
	$('#state_message').empty();
	$('#town_message').empty();
	$('#other_town_message').empty();
	$('#address_line_1_message').empty();
	$('#address_line_2_message').empty();
	$('#address_line_3_message').empty();
	$('#business_category_message').empty();
	$('#sellers_message').empty();
	$('#artisans_message').empty();
	$('#technical_services_message').empty();
	$('#spare_parts_message').empty();
	$('#vehicle_category_message').empty();
	$('#car_brands_message').empty();
	$('#bus_brands_message').empty();
	$('#truck_brands_message').empty();
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

function getSelectedBusinessType() {
	var business_category = "";
	var business_categories = document.profileEdit.business_category;
	for (var i=0; i < business_categories.length; i++) {
		if (business_categories[i].checked) {
			business_category = business_categories[i].value;
			break;
		}
	}

	return business_category;
}

function getSelectedInventories() {
	// Get the selected inventories
	var inventories = document.profileEdit.sellers;
	var selected_inventories = [];
	for (var i=0; i < inventories.length; i++) {
		if (inventories[i].checked) {
			selected_inventories.push(inventories[i].value);				
		}
	}
	
	if (selected_inventories.length < 1) {
		return selected_inventories = "";
	} else {
		return selected_inventories;
	}
}

function getSelectedArtisanSkills() {
	// Get the selected artisan skills
	var artisans = document.profileEdit.artisans;
	var selected_artisans = [];
	for (var i=0; i < artisans.length; i++) {
		if (artisans[i].checked) {
			selected_artisans.push(artisans[i].value);				
		}
	}

	if (selected_artisans.length < 1) {
		return selected_artisans = "";
	} else {
		return selected_artisans;
	}
}

function getSelectedTechnicalSkills() {
	// Get the selected technical skills
	var technical_services = document.profileEdit.technical_services;
	var selected_technical_services = [];
	for (var i=0; i < technical_services.length; i++) {
		if (technical_services[i].checked) {
			selected_technical_services.push(technical_services[i].value);				
		}
	}

	if (selected_technical_services.length < 1) {
		return selected_technical_services = "";
	} else {
		return selected_technical_services;
	}
}

function getSelectedSpareParts() {
	// Get the selected spare parts
	var spare_parts = document.profileEdit.spare_parts;
	var selected_spare_parts = [];
	for (var i=0; i < spare_parts.length; i++) {
		if (spare_parts[i].checked) {
			selected_spare_parts.push(spare_parts[i].value);				
		}
	}

	if (selected_spare_parts.length < 1) {
		return selected_spare_parts = "";
	} else {
		return selected_spare_parts;
	}
}

function getSelectedVehicle() {
	var vehicle_category = "";
	var vehicle_categories = document.profileEdit.vehicle_category;
	for (var i=0; i < vehicle_categories.length; i++) {
		if (vehicle_categories[i].checked) {
			vehicle_category = vehicle_categories[i].value;
			break;
		}
	}

	return vehicle_category;
}

function getSelectedCarBrands() {
	// Get the selected spare parts
	var car_brands = document.profileEdit.car_brands;
	var selected_car_brands = [];
	for (var i=0; i < car_brands.length; i++) {
		if (car_brands[i].checked) {
			selected_car_brands.push(car_brands[i].value);				
		}
	}

	if (selected_car_brands.length < 1) {
		return selected_car_brands = "";
	} else {
		return selected_car_brands;
	}
}

function getSelectedBusBrands() {
	// Get the selected spare parts
	var bus_brands = document.profileEdit.bus_brands;
	var selected_bus_brands = [];
	for (var i=0; i < bus_brands.length; i++) {
		if (bus_brands[i].checked) {
			selected_bus_brands.push(bus_brands[i].value);				
		}
	}

	if (selected_bus_brands.length < 1) {
		return selected_bus_brands = "";
	} else {
		return selected_bus_brands;
	}
}

function getSelectedTruckBrands() {
	// Get the selected spare parts
	var truck_brands = document.profileEdit.truck_brands;
	var selected_truck_brands = [];
	for (var i=0; i < truck_brands.length; i++) {
		if (truck_brands[i].checked) {
			selected_truck_brands.push(truck_brands[i].value);				
		}
	}

	if (selected_truck_brands.length < 1) {
		return selected_truck_brands = "";
	} else {
		return selected_truck_brands;
	}
}

function saveBussAccountFormData() {
	// Get the first name
	var firstName = $('#first_name').val();
	// Get the last name
	var lastName = $('#last_name').val();
	// Get the gender
	var genders = document.profileEdit.gender;
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
	var username = $('#username').val();
	// Get the passwords
	var password = $('#password').val();
	var confirmPassword = $('#confirm_password').val();
	// Get the business name
	var businessName = $('#business_name').val();
	// Get the phone number
	var phoneNumber = $('#business_phone_number').val();
	// Get the email 
	var email = $('#business_email').val();
	// Get the state 
	var state = $('#state').val();
	// Get the town 
	var town = $('#town').val();
	// Check if other town was selected
	var other_town = "";
	if (town === 'other') {
		var other_town = $('#other_town').val();
	}
	// Get the address line 1 
	var address_line_1 = $('#address_line_1').val();
	// Get the address line 2 
	var address_line_2 = $('#address_line_2').val();
	// Get the address line 3 
	var address_line_3 = $('#address_line_3').val();
	// Get csrf token
	var csrf_token = $('#csrf_token').val();
	// Get csrf time
	var csrf_token_time = $('#csrf_token_time').val();
	// Get the business category
	var business_category = getSelectedBusinessType();
	// Form data for the available inputs
	var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, other_town: other_town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category};	
	// Get the sellected check box options of the business category
	if (business_category === 'mobile_market') {
		var selected_inventories = getSelectedInventories();
		// Concatenate the data to be sent to the PHP file for processing
		var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, other_town: other_town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, sellers: selected_inventories, csrf_token: csrf_token, csrf_token_time: csrf_token_time};
	} else if (business_category === 'artisan') {
		var selected_artisans = getSelectedArtisanSkills();

		// Concatenate the data to be sent to the PHP file for processing
		var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, other_town: other_town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, artisans: selected_artisans, csrf_token: csrf_token, csrf_token_time: csrf_token_time};
	} else if (business_category === 'technician') {
		var selected_tech_serv = getSelectedTechnicalSkills();		

		// Get the selected automobile
		var selectedVehicle = getSelectedVehicle();
		if (selectedVehicle === 'cars') {
			// Get selected car brands
			var selectedCars = getSelectedCarBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, other_town: other_town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, technical_services: selected_tech_serv, vehicle_category: selectedVehicle, car_brands: selectedCars, csrf_token: csrf_token, csrf_token_time: csrf_token_time};
		} else if (selectedVehicle === 'buses') {
			// Get selected car brands
			var selectedBuses =  getSelectedBusBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, other_town: other_town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, technical_services: selected_tech_serv, vehicle_category: selectedVehicle, bus_brands: selectedBuses, csrf_token: csrf_token, csrf_token_time: csrf_token_time};
		} else if (selectedVehicle === 'trucks') {
			// Get selected car brands
			var selectedTrucks =  getSelectedTruckBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, other_town: other_town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, technical_services: selected_tech_serv, vehicle_category: selectedVehicle, truck_brands: selectedTrucks, csrf_token: csrf_token, csrf_token_time: csrf_token_time}; 
		}
	} else if (business_category === 'spare_part_seller') {
		var selected_spare_parts = getSelectedSpareParts();
		
		// Get the selected automobile
		var selectedVehicle = getSelectedVehicle();
		if (selectedVehicle === 'cars') {
			// Get selected car brands
			var selectedCars = getSelectedCarBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, other_town: other_town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, spare_parts: selected_spare_parts, vehicle_category: selectedVehicle, car_brands: selectedCars, csrf_token: csrf_token, csrf_token_time: csrf_token_time};
		} else if (selectedVehicle === 'buses') {
			// Get selected car brands
			var selectedBuses =  getSelectedBusBrands();
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, other_town: other_town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, spare_parts: selected_spare_parts, vehicle_category: selectedVehicle, bus_brands: selectedBuses, csrf_token: csrf_token, csrf_token_time: csrf_token_time};
		} else if (selectedVehicle === 'trucks') {
			// Get selected car brands
			var selectedTrucks =  getSelectedTruckBrands(); 
			// Concatenate the data to be sent to the PHP file for processing
			var formData = {first_name: firstName, last_name: lastName, gender: gender, username: username, password: password, confirm_password: confirmPassword, phone_number: phoneNumber, email: email, business_name: businessName, state: state, town: town, other_town: other_town, address_line_1: address_line_1, address_line_2: address_line_2, address_line_3: address_line_3, business_category: business_category, spare_parts: selected_spare_parts, vehicle_category: selectedVehicle, truck_brands: selectedTrucks, csrf_token: csrf_token, csrf_token_time: csrf_token_time};
		}
	}

	// Send data through Ajax
	$.ajax({
		url: "PHP-JSON/submitBussFormData_JSON.php",
		dataType: "json",
		type: "POST",
		data: formData,

		beforeSend: function() {
			// disable the submit button during form submission 
    	// to avoid multiple clicks and enable it later
			$('#submit').attr('disabled', true);
			$(".loader").css("display", "flex");
		},

		complete: function() {
			// $(".loader").css("display", "none");
			$(".loader").fadeOut(2000);
		},

		success: function(response) {		 
			if (response.success) {
				// Enable the submit button again after form submission.
				$('#submit').attr('disabled', false);
				// Redirect to the page of the created account.
				window.location.replace("customer/customerEditPage2.php?id="+response.newCustomerId);
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
	$('#submit').attr('disabled', false);
}