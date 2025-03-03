
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