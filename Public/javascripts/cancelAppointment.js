
function openTextarea(cancelBtnId) {
	index = cancelBtnId.substr(12, 13);
	// console.log(cancelBtnId.substr(12, 13));
	// concatenate the id of the div to make visible
	reasonForCancelDiv = "reasonForCancelDiv"+index;
	hideButton = "hideButton"+index;
	// console.log(reasonForCancelDiv);
	
	reasonForCancelDiv = document.getElementById(reasonForCancelDiv);
	reasonForCancelDiv.style.display = 'block';
	
	hideButton = document.getElementById(hideButton);
	hideButton.style.display = 'block';
	// reasonForCancelDiv.hide('blind');
}

function hideTextarea(hideBtnId) {
	index = hideBtnId.substr(10, 11);
	// console.log(hideBtnId.substr(12, 13));
	// concatenate the id of the div to make visible
	reasonForCancelDiv = "reasonForCancelDiv"+index;
	hideButton = "hideButton"+index;
	// console.log(reasonForCancelDiv);
	
	// Clear the text in the textarea if the hide box is selected
	reasonForCancelId = "reasonForCancel"+index;
	reasonForCancel = document.getElementById(reasonForCancelId);
	reasonForCancel.value = "";
	
	// Clear the error message in the div that shows an error if the hide box is checked.
	errorReportDivId = "errorReportDiv"+index;
	errorReportDiv = document.getElementById(errorReportDivId);
	errorReportDiv.innerHTML = "";
	
	reasonForCancelDiv = document.getElementById(reasonForCancelDiv);
	reasonForCancelDiv.style.display = 'none';
	
	hideButton = document.getElementById(hideButton);
	hideButton.style.display = 'none';
}

function openTextareaUser(cancelBtnId) {
	index = cancelBtnId.substr(9, 10);
	// console.log(cancelBtnId.substr(9, 10));
	// concatenate the id of the div to make visible
	reasonForCancelDiv = "reasonForCancelDiv"+index;
	hideBtn = "hideBtn"+index;
	// console.log(reasonForCancelDiv);
	
	reasonForCancelDiv = document.getElementById(reasonForCancelDiv);
	reasonForCancelDiv.style.display = 'block';
	
	hideBtn = document.getElementById(hideBtn);
	hideBtn.style.display = 'block';
	// reasonForCancelDiv.hide('blind');
}

function hideTextareaUser(hideBtnId) {
	index = hideBtnId.substr(7, 8);
	// console.log(hideBtnId.substr(7, 8));
	// concatenate the id of the div to make visible
	reasonForCancelDiv = "reasonForCancelDiv"+index;
	hideBtn = "hideBtn"+index;
	// console.log(reasonForCancelDiv);
	
	// Clear the text in the textarea if the hide box is selected
	reasonForCancelId = "reasonForCancel"+index;
	reasonForCancel = document.getElementById(reasonForCancelId);
	reasonForCancel.value = "";
	
	// Clear the error message in the div that shows an error if the hide box is checked.
	errorReportDivId = "errorReportDiv"+index;
	errorReportDiv = document.getElementById(errorReportDivId);
	errorReportDiv.innerHTML = "";
	
	reasonForCancelDiv = document.getElementById(reasonForCancelDiv);
	reasonForCancelDiv.style.display = 'none';
	
	hideBtn = document.getElementById(hideBtn);
	hideBtn.style.display = 'none';
}

function openTextareaCustomer(cancelBtnId) {
	index = cancelBtnId.substr(9, 10);
	// console.log(cancelBtnId.substr(9, 10));
	// concatenate the id of the div to make visible
	cancelingReasonDiv = "cancelingReasonDiv"+index;
	hideBtn = "hideBtn"+index;
	// console.log(cancelingReasonDiv);
	
	cancelingReasonDiv = document.getElementById(cancelingReasonDiv);
	cancelingReasonDiv.style.display = 'block';
	
	hideBtn = document.getElementById(hideBtn);
	hideBtn.style.display = 'block';
	// cancelingReasonDiv.hide('blind');
}

function hideTextareaCustomer(hideBtnId) {
	index = hideBtnId.substr(7, 8);
	// console.log(hideBtnId.substr(7, 8));
	// concatenate the id of the div to make visible
	cancelingReasonDiv = "cancelingReasonDiv"+index;
	hideBtn = "hideBtn"+index;
	// console.log(cancelingReasonDiv);
	
	// Clear the text in the textarea if the hide box is selected
	reasonForCancelId = "cancelingReason"+index;
	reasonForCancel = document.getElementById(reasonForCancelId);
	reasonForCancel.value = "";
	
	// Clear the error message in the div that shows an error if the hide box is checked.
	errorReportDivId = "errorInformDiv"+index;
	errorReportDiv = document.getElementById(errorReportDivId);
	errorReportDiv.innerHTML = "";
	
	cancelingReasonDiv = document.getElementById(cancelingReasonDiv);
	cancelingReasonDiv.style.display = 'none';
	
	hideBtn = document.getElementById(hideBtn);
	hideBtn.style.display = 'none';
}

function openTextareaCusDecline(declineBtnId) {
	index = declineBtnId.substr(10, 11);
	// console.log(declineBtnId.substr(9, 10));
	// concatenate the id of the div to make visible
	decliningReasonDiv = "decliningReasonDiv"+index;
	hideBtn = "hideDeclineBtn"+index;
	declineBtnId = "declineBtn"+index;
	// console.log(decliningReasonDiv);
	
	decliningReasonDiv = document.getElementById(decliningReasonDiv);
	decliningReasonDiv.style.display = 'block';
	
	hideBtn = document.getElementById(hideBtn);
	hideBtn.style.display = 'block';
	// decliningReasonDiv.hide('blind');
	
	// Dont show the decline button
	declineBtn = document.getElementById(declineBtnId);
	declineBtn.style.display = 'none';
}

function hideTextareaCusDecline(hideDeclineBtnId) {
	index = hideDeclineBtnId.substr(14, 15);
	// console.log(hideBtnId.substr(7, 8));
	// concatenate the id of the div to make visible
	decliningReasonDiv = "decliningReasonDiv"+index;
	hideBtn = "hideDeclineBtn"+index;
	declineBtnId = "declineBtn"+index;
	// console.log(decliningReasonDiv);
	
	// Make the decline button visible
	declineBtn = document.getElementById(declineBtnId);
	declineBtn.style.display = 'block';
	
	// Clear the text in the textarea if the hide box is selected
	reasonForDeclineId = "decliningReason"+index;
	reasonForDecline = document.getElementById(reasonForDeclineId);
	reasonForDecline.value = "";
	
	// Clear the error message in the div that shows an error if the hide box is checked.
	errorReportDivId = "errorDeliverDiv"+index;
	errorReportDiv = document.getElementById(errorReportDivId);
	errorReportDiv.innerHTML = "";
	
	decliningReasonDiv = document.getElementById(decliningReasonDiv);
	decliningReasonDiv.style.display = 'none';
	
	hideBtn = document.getElementById(hideBtn);
	hideBtn.style.display = 'none';
}