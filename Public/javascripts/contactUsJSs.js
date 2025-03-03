if (document.getElementById("message_content") != null) {
  document.getElementById("message_content").addEventListener("keyup", updateCount);
}

function updateCount() {
  var commentText = document.getElementById("message_content").value;
  var charCount = countCharacters(commentText);
  var wordCountBox = document.getElementById("wordCount");
  wordCountBox.value = charCount+"/250";
  if (charCount > 250) {
    wordCountBox.style.color = "white";
    wordCountBox.style.backgroundColor = "red";
  } else {
    wordCountBox.style.color = "black";
    wordCountBox.style.backgroundColor = "white";
  }
}


// Submit user feedback
$(document).ready(function(){
  $('#submit_complain').click(function(event){
    event.preventDefault();

    var firstName = $('#first_name').val();
    var lastName = $('#last_name').val();
    var phoneNumber = $('#phone_number').val();
    var emailAddress = $('#email_address').val();
    var messageSubject = $('#message_subject').val();
    var messageContent = $('#message_content').val();
    var csrfToken = $('#csrf_token').val();
    var csrfTokenTime = $('#csrf_token_time').val();
    var dataToSend = {first_name: firstName, last_name: lastName, phone_number: phoneNumber, email: emailAddress, message_subject: messageSubject, message_content: messageContent, csrf_token: csrfToken, csrf_token_time: csrfTokenTime};

    $.ajax({
      url: "PHP-JSON/saveFeedback_JSON.php",
      type: "POST",
      dataType: "json",
      data: dataToSend,

      beforeSend: function() {
        $(".loader").css("display", "flex");
      },

      complete: function() {
        $(".loader").fadeOut(2000);
      },    

      success: function(response) {
        // Reset the csrf token and time used
        $('#csrf_token').attr('value', response.newCSRFtoken);
        $('#csrf_token_time').attr('value', response.newCSRFtime);

        if (response.success) {
          // Clear the input box after saving message
          $('#first_name').val("");
          $('#last_name').val("");
          $('#phone_number').val("");
          $('#email_address').val("");
          $('#message_subject').val('Select').prop('selected', true);
          $('#message_content').val("");

          // Concatenate message
          var message = '<p>'+response.feedbackSaved+'</p>';
          message += '<p>'+response.userEmailOutcome+'</p>';
          displayMessage('Success', message);
        } else if (response.csrfFailure) {
          var message = "<p style='color: red;' >"+response.csrfFailure+"</p>";
          displayMessage('Error', message);
        } else if (response.savingError) {
          var message = "<p style='color: red;' >"+response.savingError+"</p>";
          displayMessage('Error', message);
        } else if (response.validationError) {
          // concatenate errors
          var message = "";
          $.each(response.validationError, function(i, error) {
            message += '<p style="color:red; ">'+ error +'</p>';
          });
          displayMessage('Error', message);
        } else if (response.postError) {
          var message = "<p style='color: red;' >"+response.postError+"</p>";
          displayMessage('Error', message);
        } else if (response.sameDomainError) {
          var message = "<p style='color: red;' >"+response.sameDomainError+"</p>";
          displayMessage('Error', message);
        }
      }
    });
  });
});