<?php
/**
 *  An example CORS-compliant method.  It will allow any GET, POST, or OPTIONS requests from any
 *  origin.
 *
 *  In a production environment, you probably want to be more restrictive, but this gives you
 *  the general idea of what is involved.  For the nitty-gritty low-down, read:
 *
 *  - https://developer.mozilla.org/en/HTTP_access_control
 *  - https://fetch.spec.whatwg.org/#http-cors-protocol
 *
 */

/*
function cors() {
    
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
    
    echo "You have CORS!";
}

cors();
*/

/*
// Allow from any origin
if(isset($_SERVER["HTTP_ORIGIN"]))
{
    // You can decide if the origin in $_SERVER['HTTP_ORIGIN'] is something you want to allow, or as we do here, just allow all
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
}
else
{
    //No HTTP_ORIGIN set, so we allow any. You can disallow if needed here
    header("Access-Control-Allow-Origin: *");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 600");    // cache for 10 minutes

if($_SERVER["REQUEST_METHOD"] == "OPTIONS")
{
    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); //Make sure you remove those you do not want to support

    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    //Just exit with 200 OK with the above headers for OPTIONS method
    exit(0);
}
//From here, handle the request as it is ok
*/

/*
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
*/

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="../javascripts/jquery.js"></script>
	<!--
	<script type="text/javascript">
		var phoneNumber = '%2B2348057368560';
		var message = 'Message testing from WhoSabiWork';
		// var ApiKey = "CDqdYvVDF7P7ILTorVo+zKjmwYYvRIs6nMUbJnJ/uDg=";
		var ApiKey = "CDqdYvVDF7P7ILTorVo%2BzKjmwYYvRIs6nMUbJnJ%2FuDg%3D";
		var ClientId = "7bf515f1-b9a0-43bb-a25d-e1d875def7bf";
		// var ApiKey = "v7ykf288yK*";
		// var ClientId = "nokafor";
		var senderId = "IMPORTANT";
		
		
		$.ajax({
		  type: "GET",
		  // url: "https://secure.xwireless.net/api/v2/Balance?ApiKey={ApiKey}&ClientId={ClientId}",
		  // url: "https://secure.xwireless.net/api/v2/Balance?ApiKey="+ApiKey+"&ClientId="+ClientId,
		  // url: "https://secure.xwireless.net/api/v2/Balance?ApiKey=CDqdYvVDF7P7ILTorVo%2BzKjmwYYvRIs6nMUbJnJ%2FuDg%3D&ClientId=7bf515f1-b9a0-43bb-a25d-e1d875def7bf",
		  // +"&callback=?"

		  // world bank API
		  // $url: "http://api.worldbank.org/countries?per_page=10&incomeLevel=LIC",

		  // google test API
		  // url: "https://searchconsole.googleapis.com/$discovery/rest?version=v1",
		  
		  // url: "https://secure.xwireless.net/api/v2/SendSMS?ApiKey={ApiKey}&ClientId={ClientId}&SenderId={senderId}&Message={message}&MobileNumbers={phoneNumber}",
		  // url: "http://45.77.146.255:6005/api/v2/SendSMS?SenderId=whoSabiWork&Is_Unicode=false&Is_Flash=false&Message=test%20api&MobileNumbers=2348057368560&ApiKey=CDqdYvVDF7P7lLTorVo%2BzKjmwYYvRls6nMUbJnJ%2FuDg%3D&ClientId=7bf515f1-b9a0-43bb-a25d-e1d875def7bf",
		  url: "https://secure.xwireless.net/api/v2/SendSMS?SenderId=whoSabiWork&Message=Testing%20xwireless%20API&MobileNumbers=2348057368560&ApiKey=CDqdYvVDF7P7ILTorVo%2BzKjmwYYvRls6nMUbJnJ%2FuDg%3D&ClientId=7bf515f1-b9a0-43bb-a25d-e1d875def7bf",
		  contentType: "application/x-www-form-urlencoded; charset=UTF-8",
		  // contentType: "application/json",
		  dataType: 'json',
		  success: function (response) {
		  	console.log(response);
		  }
		});
		
		

		/*
		var requestURL = "https://secure.xwireless.net/api/v2/Balance?callback=?";
		$.getJSON(requestURL, {
			'ApiKey' : ApiKey, 'ClientId' : ClientId,
		}, function(data) {
			console.log(data);
		});
		*/

		/*
		var requestURL = "http://api.worldbank.org/countries?per_page=10&incomeLevel=LIC";
		$.getJSON(requestURL, function(data) {
			console.log(data);
		});
		*/
		/*
		$(function () {
	       var Jsondata = {
	            'SenderId': 'IMPORTANT',
	            'ApiKey': 'CDqdYvVDF7P7ILTorVo%2BzKjmwYYvRIs6nMUbJnJ%2FuDg%3D',
	            'ClientId': '7bf515f1-b9a0-43bb-a25d-e1d875def7bf',
	            'Message': 'Message testing from WhoSabiWork',
	            'MobileNumbers': '8057368560'
	        };
	        $.ajax({
	            type: "GET",
	            url: "https://secure.xwireless.net/api/v2/SendSMS",
	            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
	            dataType: 'json',
	            data: JSON.stringify(Jsondata),
	            success: function (response) {  
	            	console.log(response);
	            }
	        });
	    });*/
	</script>
	-->
	
	<script type="text/javascript">		
		
		function sendSMS() {
			var phoneNumber = '2348057368560';
			var message = encodeURI('Message testing from WhoSabiWork');
			// var ApiKey = "CDqdYvVDF7P7ILTorVo+zKjmwYYvRls6nMUbJnJ/uDg=";
			var ApiKey = "CDqdYvVDF7P7lLTorVo%2BzKjmwYYvRls6nMUbJnJ%2FuDg%3D";
			var ClientId = "7bf515f1-b9a0-43bb-a25d-e1d875def7bf";
			var senderId = "whoSabiWork";
			var creditBal = "error";			
			
			creditBal = $.ajax({
			  type: "GET",
			  // Send message code
			  // url: "http://45.77.146.255:6005/api/v2/SendSMS?SenderId="+senderId+"&Is_Unicode=false&Is_Flash=false&Message="+message+"&MobileNumbers="+phoneNumber+"&ApiKey="+ApiKey+"&ClientId="+ClientId,
			  
			  // Check credit code
			  url: "http://45.77.146.255:6005/api/v2/Balance?ApiKey="+ApiKey+"&ClientId="+ClientId,
			  contentType: "application/x-www-form-urlencoded; charset=UTF-8",
			  dataType: 'json',
			  /*
			  success: function (response, status) {
			  	console.log(response);
			  	console.log(status);
			  	// console.log(response.Data[0].MessageErrorDescription);
			  	// console.log(response.Data[0].Credits);
			  	if (response.Data[0].Credits) {
			  		creditBal = response.Data[0].Credits;
			  	}
			  	// console.log(response.Data[0].Credits);
			  }*/

			  complete: function (data, status) {
			  	console.log(data);
			  	console.log(status);
			  	console.log(data.responseJSON.Data[0].Credits);
			  	creditBal = data.responseJSON.Data[0].Credits;
			  	
			  	// console.log(response.Data[0].MessageErrorDescription);
			  	// console.log(response.Data[0].Credits);
			  	/*
			  	if (response.Data[0].Credits) {
			  		creditBal = response.Data[0].Credits;
			  	}
			  	*/
			  	// console.log(response.Data[0].Credits);
			  	// document.writeln(creditBal);
			  	// return creditBal;
			  }

			});
			return creditBal;
			
		}
		/*
		var phoneNumber = '2348057368560';
		var message = encodeURI('Message testing from WhoSabiWork');
		// var ApiKey = "CDqdYvVDF7P7ILTorVo+zKjmwYYvRls6nMUbJnJ/uDg=";
		var ApiKey = "CDqdYvVDF7P7lLTorVo%2BzKjmwYYvRls6nMUbJnJ%2FuDg%3D";
		var ClientId = "7bf515f1-b9a0-43bb-a25d-e1d875def7bf";
		var senderId = "whoSabiWork";
		
		$.ajax({
		  type: "GET",
		  // url: "http://45.77.146.255:6005/api/v2/SendSMS?SenderId=whoSabiWork&Is_Unicode=false&Is_Flash=false&Message=test%20api&MobileNumbers=2348057368560&ApiKey=CDqdYvVDF7P7lLTorVo%2BzKjmwYYvRls6nMUbJnJ%2FuDg%3D&ClientId=7bf515f1-b9a0-43bb-a25d-e1d875def7bf",
		  // url: "http://45.77.146.255:6005/api/v2/SendSMS?SenderId="+senderId+"&Is_Unicode=false&Is_Flash=false&Message="+message+"&MobileNumbers="+phoneNumber+"&ApiKey="+ApiKey+"&ClientId="+ClientId,
		  url: "http://45.77.146.255:6005/api/v2/Balance?ApiKey="+ApiKey+"&ClientId="+ClientId,
		  contentType: "application/x-www-form-urlencoded; charset=UTF-8",
		  dataType: 'json',
		  success: function (response) {
		  	console.log(response);
		  	console.log(response.Data[0].Credits);
		  }		  
		});
		*/
	</script>
	
</head>
<body>
	<?php
		echo "<script type='text/javascript'> var value = sendSMS(); document.writeln(value.responseJSON.Data);</script>";
		// echo "<script type='text/javascript'>// document.writeln(value.responseJSON.Data[0].Credits); console.log(value.responseJSON.Data[0].Credits);</script>";
	?>
</body>
</html>