// JavaScript Document

/* $('#openDiv0').click(function() {
	// Display the div containing the form elements when clicked
	$('#openDiv0').css('display','none');
	$('#editTime0').show();
	$('#closeDiv0').show();
}); */
 
// This is just a demo for having a clickable div to open the edit section
$('#scheduleDiv').click(function() {
	// Display the div containing the form elements when clicked
	$('#editTime0').show('blind');
	
	// Hide the edit button
	$('#openDiv0').hide();
	
	// Display the close button
	$('#closeDiv0').show('blind');
}); 
 
 
// 1st day schedule edit
 // Action to perform when the close button is clicked: Hide the form elements.
$('#closeDiv0').click(function() {
	// Hide the div tag containing the form elements
	$('#editTime0').hide('blind');
	
	// Hide the close button
	$('#closeDiv0').hide();
	
	// Display the edit button
	$('#openDiv0').show('blind');
});

// Action to perform when the edit button is clicked: Display the form elements.
$('#openDiv0').click(function() {
	// Display the div containing the form elements when clicked
	$('#editTime0').show('blind');
	
	// Hide the edit button
	$('#openDiv0').hide();
	
	// Display the close button
	$('#closeDiv0').show('blind');
});


// 2nd day schedule edit
$('#closeDiv1').click(function() {
	// Hide the div tag containing the form elements
	$('#editTime1').hide('blind');
	
	// Hide the close button
	$('#closeDiv1').hide();
	
	// Display the edit button
	$('#openDiv1').show('blind');
});

// Action to perform when the edit button is clicked: Display the form elements.
$('#openDiv1').click(function() {
	// Display the div containing the form elements when clicked
	$('#editTime1').show('blind');
	
	// Hide the edit button
	$('#openDiv1').hide();
	
	// Display the close button
	$('#closeDiv1').show('blind');
});


// 3rd day schedule edit
$('#closeDiv2').click(function() {
	// Hide the div tag containing the form elements
	$('#editTime2').hide('blind');
	
	// Hide the close button
	$('#closeDiv2').hide();
	
	// Display the edit button
	$('#openDiv2').show('blind');
});

// Action to perform when the edit button is clicked: Display the form elements.
$('#openDiv2').click(function() {
	// Display the div containing the form elements when clicked
	$('#editTime2').show('blind');
	
	// Hide the edit button
	$('#openDiv2').hide();
	
	// Display the close button
	$('#closeDiv2').show('blind');
});


// 4th day schedule edit
$('#closeDiv3').click(function() {
	// Hide the div tag containing the form elements
	$('#editTime3').hide('blind');
	
	// Hide the close button
	$('#closeDiv3').hide();
	
	// Display the edit button
	$('#openDiv3').show('blind');
});

// Action to perform when the edit button is clicked: Display the form elements.
$('#openDiv3').click(function() {
	// Display the div containing the form elements when clicked
	$('#editTime3').show('blind');
	
	// Hide the edit button
	$('#openDiv3').hide();
	
	// Display the close button
	$('#closeDiv3').show('blind');
});


// 5th day schedule edit
$('#closeDiv4').click(function() {
	// Hide the div tag containing the form elements
	$('#editTime4').hide('blind');
	
	// Hide the close button
	$('#closeDiv4').hide();
	
	// Display the edit button
	$('#openDiv4').show('blind');
});

// Action to perform when the edit button is clicked: Display the form elements.
$('#openDiv4').click(function() {
	// Display the div containing the form elements when clicked
	$('#editTime4').show('blind');
	
	// Hide the edit button
	$('#openDiv4').hide();
	
	// Display the close button
	$('#closeDiv4').show('blind');
});



// 6th day schedule edit
$('#closeDiv5').click(function() {
	// Hide the div tag containing the form elements
	$('#editTime5').hide('blind');
	
	// Hide the close button
	$('#closeDiv5').hide();
	
	// Display the edit button
	$('#openDiv5').show('blind');
});

// Action to perform when the edit button is clicked: Display the form elements.
$('#openDiv5').click(function() {
	// Display the div containing the form elements when clicked
	$('#editTime5').show('blind');
	
	// Hide the edit button
	$('#openDiv5').hide();
	
	// Display the close button
	$('#closeDiv5').show('blind');
});



// 7th day schedule edit
$('#closeDiv6').click(function() {
	// Hide the div tag containing the form elements
	$('#editTime6').hide('blind');
	
	// Hide the close button
	$('#closeDiv6').hide();
	
	// Display the edit button
	$('#openDiv6').show('blind');
});

// Action to perform when the edit button is clicked: Display the form elements.
$('#openDiv6').click(function() {
	// Display the div containing the form elements when clicked
	$('#editTime6').show('blind');
	
	// Hide the edit button
	$('#openDiv6').hide();
	
	// Display the close button
	$('#closeDiv6').show('blind');
});