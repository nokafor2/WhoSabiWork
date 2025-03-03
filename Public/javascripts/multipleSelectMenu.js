function populate(selectMenuVehId, vehicleBrandId, vehicleServiceId, selMenuStateId) {
	var selectMenuVeh = document.getElementById(selectMenuVehId);
	var vehicleBrand = document.getElementById(vehicleBrandId);
	// This works for both vehicle services and spare parts
	var selectVehicleService = document.getElementById(vehicleServiceId);
	var selMenuState = document.getElementById(selMenuStateId);
	// var selMenuTown = document.getElementById(selMenuTownId);
	
	// This will clear out the contents of the second select menu whenever a selection is made.
	vehicleBrand.innerHTML = "";
	selectVehicleService.innerHTML = "";
	selMenuState.innerHTML = "";
	// selMenuTown.innerHTML = "";
	
	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];
	
	// Evaluate the conditions that was selected from the array.
	var optionArray = new Array();
	if (selectMenuVeh.value === "") {
		// Create the HTML dropdown menu option again if no value was selected
		populateSelectOption(vehicleBrand, selectVehicleService, selMenuState);
		
	} else if (selectMenuVeh.value === "car") {
		// Get the vehicle services for the cars
		dataVariable = {vehicleType: "car", webPage: pageName};
		$.ajax ({
			url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
				populateVehicleBrands(data.carBrands, vehicleBrandId);
				populateVehicleServices(data.techServices, vehicleServiceId);
				populateState(data.states, selMenuStateId);
				// populateTown(data.towns, selMenuTownId);
			}
		});
		
	} else if (selectMenuVeh.value === "bus") {
		// Get the vehicle services for the bus
		dataVariable = {vehicleType: "bus", webPage: pageName};
		$.ajax ({
			url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
				populateVehicleBrands(data.busBrands, vehicleBrandId);
				populateVehicleServices(data.techServices, vehicleServiceId);
				populateState(data.states, selMenuStateId);
				// populateTown(data.towns, selMenuTownId);
			}
		});
	} else if (selectMenuVeh.value === "truck") {
		// Get the vehicle services for the truck
		dataVariable = {vehicleType: "truck", webPage: pageName};
		$.ajax ({
			url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
				populateVehicleBrands(data.truckBrands, vehicleBrandId);
				populateVehicleServices(data.techServices, vehicleServiceId);
				populateState(data.states, selMenuStateId);
				// populateTown(data.towns, selMenuTownId);
			}
		});
	}
}

function getVehicleBrands(selectMenuVehId, vehicleBrandId) {
	var selectMenuVeh = document.getElementById(selectMenuVehId);
	var vehicleBrand = document.getElementById(vehicleBrandId);
	// This works for both vehicle services and spare parts
	
	// This will clear out the contents of the second select menu whenever a selection is made.
	vehicleBrand.innerHTML = "";
	
	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];
	
	// Evaluate the conditions that was selected from the array.
	var optionArray = new Array();
	if (selectMenuVeh.value === "") {
		// Create the dummy select option	
		var newOption = document.createElement("option");
		newOption.value = "";
		newOption.innerHTML = "Select";
		vehicleBrand.options.add(newOption);
	} else if (selectMenuVeh.value === "car") {
		// Get the vehicle services for the cars
		dataVariable = {vehicleType: "car", webPage: pageName};
		$.ajax ({
			url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
				populateVehicleBrands(data.carBrands, vehicleBrandId);
				$("#carBrandLabel").fadeIn(2000).css('display', 'inline-block');
				$("#carBrand").fadeIn(2000).css('display', 'inline-block');
			}
		});
		
	} else if (selectMenuVeh.value === "bus") {
		// Get the vehicle services for the bus
		dataVariable = {vehicleType: "bus", webPage: pageName};
		$.ajax ({
			url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
				populateVehicleBrands(data.busBrands, vehicleBrandId);
				$("#carBrandLabel").fadeIn(2000).css('display', 'inline-block');
				$("#carBrand").fadeIn(2000).css('display', 'inline-block');
			}
		});
	} else if (selectMenuVeh.value === "truck") {
		// Get the vehicle services for the truck
		dataVariable = {vehicleType: "truck", webPage: pageName};
		$.ajax ({
			url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
				populateVehicleBrands(data.truckBrands, vehicleBrandId);
				$("#carBrandLabel").fadeIn(2000).css('display', 'inline-block');
				$("#carBrand").fadeIn(2000).css('display', 'inline-block');
			}
		});
	}
}

function getTechnicalServices(vehicleTypeId, vehicleBrandId, vehicleServiceId) {
	var selectedVehicleType = document.getElementById(vehicleTypeId);
	var selectedVehicleBrand = document.getElementById(vehicleBrandId);
	// This works for both vehicle services and spare parts
	var selectedVehicleService = document.getElementById(vehicleServiceId);

	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];

	// Get the vehicle services for the cars
	dataVariable = {vehicleType: selectedVehicleType.value, vehicleBrand: selectedVehicleBrand.value, webPage: pageName};
	$.ajax ({
		url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
			populateVehicleServices(data.techServices, vehicleServiceId);
			showTechServ();
		}
	});
}

function getSpareParts(vehicleTypeId, vehicleBrandId, sparePartId) {
	var selectedVehicleType = document.getElementById(vehicleTypeId);
	var selectedVehicleBrand = document.getElementById(vehicleBrandId);
	// This works for both vehicle services and spare parts
	var selectedSparePart = document.getElementById(sparePartId);

	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];

	// Get the spare part sellers for the cars
	dataVariable = {vehicleType: selectedVehicleType.value, vehicleBrand: selectedVehicleBrand.value, webPage: pageName};
	$.ajax ({
		url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
			populateSpareParts(data.spareParts, sparePartId);
			showSparePart();
		}
	});
}

function getStates(vehicleTypeId, vehicleBrandId, techServOrSparePartId, stateId) {
	var selectedVehicleType = document.getElementById(vehicleTypeId);
	var selectedVehicleBrand = document.getElementById(vehicleBrandId);
	// This works for both vehicle services and spare parts
	var techServOrSparePartObj = document.getElementById(techServOrSparePartId);

	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];

	// Get the spare part sellers for the cars
	dataVariable = {vehicleType: selectedVehicleType.value, vehicleBrand: selectedVehicleBrand.value, techServOrSparePart: techServOrSparePartObj.value, webPage: pageName};
	$.ajax ({
		url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
			populateState2(data.states, stateId);
			showStateOptions();
		}
	});
}

function getTowns2(vehicleTypeId, vehicleBrandId, techServOrSparePartId, stateId, townId) {
	var selVehicleType = document.getElementById(vehicleTypeId);
	var selVehicleBrand = document.getElementById(vehicleBrandId);
	var selTechServOrSparePart = document.getElementById(techServOrSparePartId);
	var selState = document.getElementById(stateId);
	var selTown = document.getElementById(townId);
	
	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];
	
	// Get the artisan town
	dataVariable = {vehicleType: selVehicleType.value, vehicleBrand: selVehicleBrand.value, techServOrSparePart: selTechServOrSparePart.value, state: selState.value, webPage: pageName};
	$.ajax ({
		url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
			populateTown2(data.towns, townId);
			showTownOptions();
		}
	});
}

function getTowns(selMenuStateId, selectMenuVehId, selMenuTownId) {
	var selMenuState = document.getElementById(selMenuStateId);
	var selectMenuVeh = document.getElementById(selectMenuVehId);
	var selMenuTown = document.getElementById(selMenuTownId);
	// This will clear the contents of the select menu whenever a new state is selected
	// selMenuState.innerHTML = "";
	selMenuTown.innerHTML = "";
	
	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];
	
	// Get the artisan town
	dataVariable = {state: selMenuState.value, webPage: pageName, vehicleType: selectMenuVeh.value};
	$.ajax ({
		url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
			populateTown(data.towns, selMenuTownId);
		}
	});
}

function popArtisanStates(selectMenuArtisanId, selMenuStateId) {
	var selectMenuArtisan = document.getElementById(selectMenuArtisanId);
	var selMenuState = document.getElementById(selMenuStateId);
	// var selMenuTown = document.getElementById(selMenuTownId);
	
	// This will clear out the contents of the second select menu whenever a selection is made.
	selMenuState.innerHTML = "";
	// selMenuTown.innerHTML = "";
	
	// Get the artisan state and town
	dataVariable = {artisanType: selectMenuArtisan.value};
	$.ajax ({
		url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
			populateState2(data.states, selMenuStateId);
			// This is depercated. The towns will now be populated when 
			// a state is selected
			// populateTown(data.towns, selMenuTownId);
			$("#stateLabel").fadeIn(2000);
			$("#state").fadeIn(2000);
		}
	});
}

function popSellerStates(selectMenuSellerId, selMenuStateId) {
	var selectMenuSeller = document.getElementById(selectMenuSellerId);
	var selMenuState = document.getElementById(selMenuStateId);
	// var selMenuTown = document.getElementById(selMenuTownId);
	
	// This will clear out the contents of the second select menu whenever a selection is made.
	selMenuState.innerHTML = "";
	// selMenuTown.innerHTML = "";
	
	// Get the seller state and town
	dataVariable = {sellerType: selectMenuSeller.value};
	$.ajax ({
		url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
			populateState2(data.states, selMenuStateId);
			// populateTown(data.towns, selMenuTownId);
			$("#stateLabel").fadeIn(2000).css('display', 'inline-block');
			$("#state").fadeIn(2000).css('display', 'inline-block');			
		}
	});
}

function populateSelectOption(selectVehicleBrand, selectVehicleService, selMenuState) {
	// Create the dummy select option
	var newOption = document.createElement("option");
	newOption.value = "";
	newOption.innerHTML = "Select";
	selectVehicleService.options.add(newOption);
	
	// Create the dummy select option	
	var newOption = document.createElement("option");
	newOption.value = "";
	newOption.innerHTML = "Select";
	selectVehicleBrand.options.add(newOption);
	
	// Create the dummy select option
	var newOption = document.createElement("option");
	newOption.value = "";
	newOption.innerHTML = "Select";
	selMenuState.options.add(newOption);
	
	// Create the dummy select option
	/* var newOption = document.createElement("option");
	newOption.value = "";
	newOption.innerHTML = "Select";
	selMenuTown.options.add(newOption); */
}

// This function will also work for services if selected
function populateVehicleServices(services, vehicleServiceId) {
	var selectVehicleService = document.getElementById(vehicleServiceId);
	
	// clear the contents of the previous search
	selectVehicleService.innerHTML = "";
	// Create the dummy select option
	var newOption = document.createElement("option");
	newOption.value = "";
	newOption.innerHTML = "Select";
	selectVehicleService.options.add(newOption);
	
	/* // Use a loop to populate the options for the select menu
	for (var service in services) {
		// create an 'option' tag object to be used to populate the select contents
		var newOption = document.createElement("option");
		// Use this to access the value of the array
		newOption.value = service;
		// Capitalize the first letter in the string
		service = service.charAt(0).toUpperCase()+service.slice(1);
		// Remove the underscores in the string
		service = service.replace(/_/g, ' ');
		// Use this to access the label of the array
		newOption.innerHTML = service;
		selectVehicleService.options.add(newOption);
	} */

	// Use a loop to populate the options for the select menu
	if (services.length > 0) {
		for (var i = 0; i < services.length; i++) {
			// create an 'option' tag object to be used to populate the select contents
			var newOption = document.createElement("option");
			// Use this to access the value of the array
			newOption.value = services[i];
			// Set a function for the option
			newOption.setAttribute("onclick", "showStateOptions()");
			// Capitalize the first letter in the string
			services[i] = services[i].charAt(0).toUpperCase()+services[i].slice(1);
			// Remove the underscores in the string
			services[i] = services[i].replace(/_/g, ' ');
			// Use this to access the label of the array
			newOption.innerHTML = services[i];
			selectVehicleService.options.add(newOption);
		}		
	} else {
		// Create the dummy select option
		var newOption = document.createElement("option");
		newOption.value = "";
		newOption.innerHTML = "Not available";
		selectVehicleService.options.add(newOption);

		// Hide the other select options if they are open
		$('#stateLabel').fadeOut();
		$('#state').fadeOut();
		$('#town').fadeOut();
		$('#townLabel').fadeOut();
		$('#submitBtn').fadeOut();
	}
}

// This function will also work for spare parts if selected
function populateSpareParts(spareParts, sparePartId) {
	var selectSparePart = document.getElementById(sparePartId);
	
	// clear the contents of the previous search
	selectSparePart.innerHTML = "";
	// Create the dummy select option
	var newOption = document.createElement("option");
	newOption.value = "";
	newOption.innerHTML = "Select";
	selectSparePart.options.add(newOption);

	// Use a loop to populate the options for the select menu
	if (spareParts.length > 0) {
		for (var i = 0; i < spareParts.length; i++) {
			// create an 'option' tag object to be used to populate the select contents
			var newOption = document.createElement("option");
			// Use this to access the value of the array
			newOption.value = spareParts[i];
			// Set a function for the option
			newOption.setAttribute("onclick", "showStateOptions()");
			// Capitalize the first letter in the string
			spareParts[i] = spareParts[i].charAt(0).toUpperCase()+spareParts[i].slice(1);
			// Remove the underscores in the string
			spareParts[i] = spareParts[i].replace(/_/g, ' ');
			// Use this to access the label of the array
			newOption.innerHTML = spareParts[i];
			selectSparePart.options.add(newOption);
		}		
	} else {
		// Create the dummy select option
		var newOption = document.createElement("option");
		newOption.value = "";
		newOption.innerHTML = "Not available";
		selectSparePart.options.add(newOption);

		// Hide the other select options if they are open
		$('#stateLabel').fadeOut();
		$('#state').fadeOut();
		$('#town').fadeOut();
		$('#townLabel').fadeOut();
		$('#submitBtn').fadeOut();
	}
}

function populateVehicleBrands(brands, vehicleBrandId) {
	var selectVehicleBrand = document.getElementById(vehicleBrandId);
	
	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];

	// clear the contents of the previous search
	selectVehicleBrand.innerHTML = "";
	// Create the dummy select option
	var newOption = document.createElement("option");
	newOption.value = "";
	newOption.innerHTML = "Select";
	selectVehicleBrand.options.add(newOption);
	
	/* // Use a loop to populate the options for the select menu
	for (var brand in brands) {
		// create an 'option' tag object to be used to populate the select contents
		var newOption = document.createElement("option");
		// Use this to access the value of the array
		newOption.value = brand;
		// Capitalize the first letter in the string
		brand = brand.charAt(0).toUpperCase()+brand.slice(1);
		// Remove the underscores in the string
		brand = brand.replace(/_/g, ' ');
		// Use this to access the label of the array
		newOption.innerHTML = brand.replace('_', ' ');
		selectVehicleBrand.options.add(newOption);
	} */

	if (brands.length > 0) {
		for (var i = 0; i < brands.length; i++) {
			// create an 'option' tag object to be used to populate the select contents
			var newOption = document.createElement("option");
			// Use this to access the value of the array
			newOption.value = brands[i];
			// add a function to the option
			if (pageName == 'servicePage.php') {
				newOption.setAttribute("onclick", "showTechServ()");
			} else if (pageName == 'sparePartPage.php') {
				newOption.setAttribute("onclick", "showSparePart()");
			}			
			// Capitalize the first letter in the string
			brands[i] = brands[i].charAt(0).toUpperCase()+brands[i].slice(1);
			// Remove the underscores in the string
			brands[i] = brands[i].replace(/_/g, ' ');
			// Use this to access the label of the array
			newOption.innerHTML = brands[i].replace('_', ' ');
			selectVehicleBrand.options.add(newOption);
		}
	} else {
		// Create the dummy select option
		var newOption = document.createElement("option");
		newOption.value = "";
		newOption.innerHTML = "Not available";
		selectVehicleBrand.options.add(newOption);

		// Hide the other select options if they are open
		$('#technicalService').fadeOut();
		$('#techServLabel').fadeOut();
		$('#sparePartLabel').fadeOut();
		$('#sparePart').fadeOut();
		$('#stateLabel').fadeOut();
		$('#state').fadeOut();
		$('#town').fadeOut();
		$('#townLabel').fadeOut();
		$('#submitBtn').fadeOut();
	}
	
}

function populateState2(states, stateId) {
	var selectState = document.getElementById(stateId);
	
	// clear the contents of the previous search
	selectState.innerHTML = "";
	// Create the dummy select option
	var newOption = document.createElement("option");
	newOption.value = "";
	newOption.innerHTML = "Select";
	selectState.options.add(newOption);

	// Use a loop to populate the options for the select menu
	if (states.length > 0) {
		for (var i = 0; i < states.length; i++) {
			// create an 'option' tag object to be used to populate the select contents
			var newOption = document.createElement("option");
			// Use this to access the value of the array
			newOption.value = states[i];
			// Set a function for the option
			// newOption.setAttribute("onclick", "showTownOptions()");
			// Capitalize the first letter in the string
			states[i] = states[i].charAt(0).toUpperCase()+states[i].slice(1);
			// Remove the underscores in the string
			states[i] = states[i].replace(/_/g, ' ');
			// Use this to access the label of the array
			newOption.innerHTML = states[i];
			selectState.options.add(newOption);
		}		
	} else {
		// Create the dummy select option
		var newOption = document.createElement("option");
		newOption.value = "";
		newOption.innerHTML = "Not available";
		selectState.options.add(newOption);

		// Hide the other select options if they are open
		$('#town').fadeOut();
		$('#townLabel').fadeOut();
		$('#submitBtn').fadeOut();
	}
}

function populateState(states, selMenuStateId) {
	var selMenuState = document.getElementById(selMenuStateId);
	
	if (states === "Not available") {
		// Create the dummy select option
		var newOption = document.createElement("option");
		newOption.value = "";
		newOption.innerHTML = "Select";
		selMenuState.options.add(newOption);
		
		// Output an not available in the list
		var newOption = document.createElement("option");
		newOption.value = "";
		// This will output not available coming from the server
		newOption.innerHTML = states;
		selMenuState.options.add(newOption);
	} else {
		// Create the dummy select option
		var newOption = document.createElement("option");
		newOption.value = "";
		// newOption.className = "stateOptions";
		newOption.innerHTML = "Select";
		selMenuState.options.add(newOption);
		
		// Use a loop to populate the options for the select menu
		for (var state in states) {
			// create an 'option' tag object to be used to populate the select contents
			var newOption = document.createElement("option");
			// Use this to access the value of the array
			newOption.value = state;
			// Create a class name for the option
			newOption.className = "stateOptions";
			// Create the onclick javascript function for the option
			newOption.setAttribute("onclick", "showTownOptions()");
			// Capitalize the first letter in the string
			state = state.charAt(0).toUpperCase()+state.slice(1);
			// Remove the underscores in the string
			state = state.replace(/_/g, ' ');
			// Use this to access the label of the array
			newOption.innerHTML = state.replace('_', ' ');
			selMenuState.options.add(newOption);
		}
	}
}

function getArtisanTowns(selMenuStateId, selectMenuArtisanId, selMenuTownId) {
	var selMenuState = document.getElementById(selMenuStateId);
	var selectMenuArtisan = document.getElementById(selectMenuArtisanId);
	var selMenuTown = document.getElementById(selMenuTownId);
	// This will clear the contents of the select menu whenever a new state is selected
	// selMenuState.innerHTML = "";
	selMenuTown.innerHTML = "";
	
	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];
	
	// Get the artisan town
	dataVariable = {state: selMenuState.value, webPage: pageName, artisanType: selectMenuArtisan.value};
	$.ajax ({
		url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
			populateTown2(data.towns, selMenuTownId);
			// $("#townLabel").fadeIn(2000);
			// $("#town").fadeIn(2000);
			$("#townLabel").fadeIn(2000).css('display', 'inline-block');
			$("#town").fadeIn(2000).css('display', 'inline-block');
		}
	});
}

function getSellerTowns(selMenuStateId, selectMenuSellerId, selMenuTownId) {
	var selMenuState = document.getElementById(selMenuStateId);
	var selectMenuSeller = document.getElementById(selectMenuSellerId);
	var selMenuTown = document.getElementById(selMenuTownId);
	// This will clear the contents of the select menu whenever a new state is selected
	// selMenuState.innerHTML = "";
	selMenuTown.innerHTML = "";
	
	// Determine the URL for the php page
	var pathArray = window.location.pathname.split( '/' );
	// Get the filename of the page from the array.
	var pageName = pathArray[pathArray.length-1];
	
	// Get the seller town
	dataVariable = {state: selMenuState.value, webPage: pageName, sellerType: selectMenuSeller.value};
	$.ajax ({
		url: 'PHP-JSON/multipleSelectMenu_JSON.php', 
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
			populateTown2(data.towns, selMenuTownId);
			showTownOptions();
		}
	});
}

function populateTown2(towns, townId) {
	var selectTown = document.getElementById(townId);
	
	// clear the contents of the previous search
	selectTown.innerHTML = "";
	// Create the dummy select option
	var newOption = document.createElement("option");
	newOption.value = "";
	newOption.innerHTML = "Select";
	selectTown.options.add(newOption);

	// Use a loop to populate the options for the select menu
	if (towns.length > 0) {
		var newOption = document.createElement("option");
		newOption.value = "";
		// newOption.className = "townOptions";
		// newOption.setAttribute("onclick", "showSubmitBtn()");
		newOption.innerHTML = "All"; // changed from Select
		selectTown.options.add(newOption);

		for (var i = 0; i < towns.length; i++) {
			// create an 'option' tag object to be used to populate the select contents
			var newOption = document.createElement("option");
			// Use this to access the value of the array
			newOption.value = towns[i];
			// Set a function for the option
			// This function is not used as its not supported in chrome and explorer
			// newOption.setAttribute("onclick", "showSubmitBtn()");
			// Capitalize the first letter in the string
			towns[i] = towns[i].charAt(0).toUpperCase()+towns[i].slice(1);
			// Remove the underscores in the string
			towns[i] = towns[i].replace(/_/g, ' ');
			// Use this to access the label of the array
			newOption.innerHTML = towns[i];
			selectTown.options.add(newOption);
		}		
	} else {
		// Create the dummy select option
		var newOption = document.createElement("option");
		newOption.value = "";
		newOption.innerHTML = "Not available";
		selectTown.options.add(newOption);

		// hide the submit btn
		$('#submitBtn').fadeOut();
	}
}

function populateTown(towns, selMenuTownId) {
	var selMenuTown = document.getElementById(selMenuTownId);
	
	if (towns === "Not available") {
		// Create the dummy select option
		var newOption = document.createElement("option");
		newOption.value = "";
		newOption.innerHTML = "All"; // chnaged from Select
		selMenuTown.options.add(newOption);
		
		// Output not available in the list
		var newOption = document.createElement("option");
		newOption.value = "";
		newOption.innerHTML = towns;
		selMenuTown.options.add(newOption);
	} else {
		// populate the towns in the array
		var newOption = document.createElement("option");
		newOption.value = "";
		newOption.className = "townOptions";
		newOption.setAttribute("onclick", "showSubmitBtn()");
		newOption.innerHTML = "All"; // changed from Select
		selMenuTown.options.add(newOption);
		
		// Use a loop to populate the options for the select menu
		for (var town in towns) {
			// create an 'option' tag object to be used to populate the select contents
			var newOption = document.createElement("option");
			// Use this to access the value of the array
			newOption.value = town;
			// Add a class for the options
			newOption.className = "townOptions";
			// Create a javascript function attribute for the option
			newOption.setAttribute("onclick", "showSubmitBtn()");
			// Capitalize the first letter in the string
			town = town.charAt(0).toUpperCase()+town.slice(1);
			// Remove the underscores in the string
			town = town.replace(/_/g, ' ');
			// Use this to access the label of the array
			newOption.innerHTML = town.replace('_', ' ');
			selMenuTown.options.add(newOption);
		}
	}
}


function showTownOptions() {
	$("#townLabel").fadeIn(2000).css("display", "inline-block");
	$("#town").fadeIn(2000).css("display", "inline-block");
}

function showSubmitBtn() {
	$("#submitBtn").fadeIn(2000).css("display", "inline-block");
}

function showTechServ() {
	$("#techServLabel").fadeIn(2000).css("display", "inline-block");
	$("#technicalService").fadeIn(2000).css("display", "inline-block");	
}

function showStateOptions() {
	$("#stateLabel").fadeIn(2000).css("display", "inline-block");
	$("#state").fadeIn(2000).css("display", "inline-block");
}

function showSparePart() {
	$("#sparePartLabel").fadeIn(2000).css("display", "inline-block");
	$("#sparePart").fadeIn(2000).css("display", "inline-block");
}