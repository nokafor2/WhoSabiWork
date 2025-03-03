<?php

// function send_sms($phone, $msg, $sender){
function send_sms(){
	// Eliminate the first zero in front of the telephone number if it exists
	/* if(substr($phone,0,1)=="0"){
		$phone	=	"234".substr($phone,1);
	} */
	// $msg	=	substr($msg,0,158);
	// $phone	=	urlencode($phone);
	// $msg	=	urlencode($msg);
	// $sender	=	urlencode($sender);

	// $send_on_date = str_replace('+00:00', '', gmdate('c', strtotime(date('Y-m-d H:i:s')) ) );
	$send_on_date = str_replace('+00:00', '', gmdate('c', strtotime(date('d-m-Y H:i:s')) ) );
	echo "The time for sending the message is: ".$send_on_date;
	echo "<br/>";

	// $url	= "http://panel.xwireless.net/API/WebSMS/Http/v1.0a/index.php?username=jedhppc@gmail.com&password=pass1234&sender=".$sender."&to=".$phone."&message=".$msg."&reqid=1&format=json";
	
	$url = "http://smsc.xwireless.net/API/WebSMS/Http/v3.1/index.php?method=credit_check&username=nokafor&password=6QpL4X96&format=json";
	
	 // http://panel.xwireless.net/API/WebSMS/Http/v1.0a/index.php?username=nokafor&password=6QpL4X96&sender=Ayuanorama&to=2348057368560&message=Hello+Testing+Ayuanorama&reqid=1&format=json&route_id=2&callback=?&unique=1&sendondate=19-07-2018T13:30:15 
	 // http://smsc.xwireless.net/API/WebSMS/Http/v3.1/index.php?username=nokafor&password=6QpL4X96&sender=Ayuanorama&to=2348057368560&message=Hello+Testing+Ayuanorama&reqid=1&format=json
	 
	 // To check credit:
	 // http://smsc.xwireless.net/API/WebSMS/Http/v3.1/index.php?method=credit_check&username=nokafor&password=6QpL4X96&format=json
	 // Without using time:
	 // http://panel.xwireless.net/API/WebSMS/Http/v1.0a/index.php?username=nokafor&password=6QpL4X96&sender=Ayuanorama&to=2348057368560&message=Hello+Testing+Ayuanorama&reqid=1&format=json&route_id=3&unique=1
	$res	=	"";
	$res	=	@file_get_contents($url);
	echo $res;
	return true;
}

send_sms();

?>