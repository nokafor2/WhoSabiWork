<?php 

	function GetUserIP() {
		$ip;
		if (getenv("HTTP_CLIENT_IP")) {
			$ip = getenv("HTTP_CLIENT_IP");
		} elseif (getenv("HTTP_X_FORWARDED_FOR")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		} elseif (getenv("REMOtE_ADDR")) {
			$ip = getenv("REMOtE_ADDR");
		} else {
			$ip = "UNKNOWN";
		}
		
		return $ip;
	}

?>