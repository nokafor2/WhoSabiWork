
$(document).ready(function(){
	// var arrow = $(".arrow-up");
	var accountInfo = $(".accountInfo");
	var status = false;
	$("#accountInfoMenu").click(function(event){
		event.preventDefault();
		if (status == false) {
			// arrow.fadeIn();
			accountInfo.fadeIn();
			status = true;
		} else {
			// arrow.fadeOut();
			accountInfo.fadeOut();
			status = false;
		}
	});
});

$(document).ready(function(){
	var navBar = $("#MenuBar1");
	var status1 = false;
	$(".menuNavBtn").click(function(event){
		event.preventDefault();
		console.log("Button clicked");
		if (status1 == false) {
			navBar.fadeIn();
			status1 = true;
		} else {
			navBar.fadeOut();
			status1 = false;
		}
	});
});

$(document).ready(function(){
	setTimeout(function(){
		$('.message').fadeOut('slow');
	}, 10000);
});