<?php
/*
This class contains functions to perform scurity checks in the various webpages.
*/

class Security_Functions {
	
	// Initialize variables
	
	// Class constructor
	function __construct() {
		
	}
	
	// GET requests should not make changes
	// Only POST requests should make changes

	function request_is_get() {
		return $_SERVER['REQUEST_METHOD'] === 'GET';
	}

	function request_is_post() {
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}

	// Must call session_start() before this loads. It is already initialized in the session class; thus, this class has to load after the session class and included after it in the includes.php program.

	// Generate a token for use with CSRF protection.
	// Does not store the token.
	function csrf_token() {
		return md5(uniqid(rand(), TRUE));
	}

	// Generate and store CSRF token in user session.
	// Requires session to have been started already.
	function create_csrf_token() {
		global $session;
		$token = $this->csrf_token();
	    // $_SESSION['csrf_token'] = $token;
		$session->set_csrf_token($token);
		// $_SESSION['csrf_token_time'] = time();
		$session->set_csrf_token_time(time());
		return $token;
	}

	// Destroys a token by removing it from the session.
	function destroy_csrf_token() {
		global $session;
	    // $_SESSION['csrf_token'] = null;
		$session->set_csrf_token(null);
		// $_SESSION['csrf_token_time'] = null;
		$session->set_csrf_token_time(null);
		return true;
	}

	// Return an HTML tag including the CSRF token 
	// for use in a form.
	// Usage: echo csrf_token_tag();
	function csrf_token_tag() {
		$token = $this->create_csrf_token();
		return "<input type=\"hidden\" name=\"csrf_token\" value=\"".$token."\">";
	}

	// Returns true if user-submitted POST token is
	// identical to the previously stored SESSION token.
	// Returns false otherwise.
	function csrf_token_is_valid() {
		global $session;
		if(isset($_POST['csrf_token'])) {
			$user_token = $_POST['csrf_token'];
			// $stored_token = $_SESSION['csrf_token'];
			$stored_token = $session->get_csrf_token();
			return $user_token === $stored_token;
		} else {
			return false;
		}
	}

	// You can simply check the token validity and 
	// handle the failure yourself, or you can use 
	// this "stop-everything-on-failure" function. 
	function die_on_csrf_token_failure() {
		if(!$this->csrf_token_is_valid()) {
			die("CSRF token validation failed.");
		}
	}

	// Optional check to see if token is also recent
	function csrf_token_is_recent() {
		global $session;
		$max_elapsed = 60 * 60 * 24; // 1 day
		
		// isset($_SESSION['csrf_token_time'])
		if($session->get_csrf_token_time() !== null) {
			// $stored_time = $_SESSION['csrf_token_time'];
			$stored_time = $session->get_csrf_token_time();
			return ($stored_time + $max_elapsed) >= time();
		} else {
			// Remove expired token
			$this->destroy_csrf_token();
			return false;
		}
	}	
}

?>