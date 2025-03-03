var ApiKey = "CDqdYvVDF7P7ILTorVo+zKjmwYYvRIs6nMUbJnJ/uDg=";
var ClientId = "7bf515f1-b9a0-43bb-a25d-e1d875def7bf";
$.ajax({
  type: "GET",
  url: "https://secure.xwireless.net/api/v2/Balance?ApiKey={ApiKey}&ClientId={ClientId}",
  contentType: "application/json ",
  dataType: 'json ',
  success: function (response) {
  	console.log(response);
  }
});