<?php
/*
This class contains functions to perform scurity checks in the various webpages.
*/
require_once(LIB_PATH.DS.'session.php');

class Security_Functions {
	
	// Initialize variables
	public $index = 0;
	
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
		$counter = $this->index;
		$this->index++;
		$token = $this->csrf_token();
		// echo "created token is: ".$token."<br/>";
		$time = time();
		$session->set_csrf_tokens($counter, $token);
		$session->set_csrf_tokens_time($counter, $time);
		return array($counter, $token, $time);
	}

	public function session_token_search($find_str = '') {
		// Search for all the tokens in the session global that begins with 'csrf_token_'
		// return all the SESSION global array keys:
		$session_params_keys = array_keys($_SESSION);
		// $find_str = 'csrf_token_';
		$session_csrf_tokens_keys = array();
		// Check for 'csrf_token_' in the SESSION global keys.
		foreach ($session_params_keys as $param) {
			if (strpos($param, $find_str) === 0) {
				$session_csrf_tokens_keys[] = $param;
			}
		}
		$session_tokens = array();
		// copies the retrieved csrf_tokens in an array from the $_SESSION global array
		foreach ($session_csrf_tokens_keys as $value) {
			if (array_key_exists($value, $_SESSION)) {
				$session_tokens[] = $_SESSION[$value];
			}	
		}
		
		return array($session_csrf_tokens_keys, $session_tokens);
	}
	
	// Destroys a token by removing it from the session.
	function destroy_csrf_tokens() {
		global $session;
		
		list($session_keys, $session_tokens) = $this->session_token_search('csrf_token_');
		foreach ($session_tokens as $key => $token) {
			$token_key = $session_keys[$key];
			unset($_SESSION[$token_key]);
			$session->csrf_tokens[$token_key] = null;
		}
		
		list($session_keys, $session_times) = $this->session_token_search('csrf_time_');
		foreach ($session_times as $key => $time) {
			$time_key = $session_keys[$key];
			unset($_SESSION[$time_key]);
			$session->csrf_tokens_time[$time_key] = null;
		}
		
		return true;
	}

	// Return an HTML tag including the CSRF token 
	// for use in a form.
	// Usage: echo csrf_token_tag();
	function csrf_token_tag() {
		// $index is a local variable in this function
		list($index, $token, $time) = $this->create_csrf_token();
		return "<input id='csrf_token_".$index."' type='hidden' name='csrf_token_".$index."' value='".$token."'> <input id='csrf_time_".$index."' type='hidden' name='csrf_time_".$index."' value='".$time."'>";
	}

	// Returns true if user-submitted POST token is
	// identical to the previously stored SESSION token.
	// Returns false otherwise.
	function csrf_token_is_valid() {
		global $session;
		
		// Search for all the tokens in the post global that begins with 'csrf_token_'
		// return all the POST global array keys:
		$post_params_keys = array_keys($_POST);
		/* echo "<br/> CSRF Valid Test, Array key values: </br>";
		print_r($post_params_keys);
		echo "<br/>"; */
		$find_str = 'csrf_token_';
		// $post_csrf_tokens_keys = array();
		$post_csrf_token_key = "";
		// Check for 'csrf_token_' in the POST global keys.
		foreach ($post_params_keys as $param) {
			if (strpos($param, $find_str) === 0) {
				// $post_csrf_tokens_keys[] = $param;
				// echo "The post global varibles are: ".$param."<br/>";
				$post_csrf_token_key = $param;
			}
		}
		// echo "<br/> Found token key in POST Global: <br/>";
		// print_r($post_csrf_tokens_keys);
		// echo $post_csrf_token_key;
		// echo "<br/>";
		// $user_tokens = array();
		$post_csrf_token = "";
		// copies the retrieved csrf_tokens in an array from the $_POST global array
		/* foreach ($post_csrf_tokens_keys as $value) {
			if (array_key_exists($value, $_POST)) {
				$user_tokens[] = $_POST[$value];
			}	
		} */
		$post_csrf_token = $_POST[$post_csrf_token_key];
		// echo "Token found with key is: ".$post_csrf_token."<br/>";
		if (isset($post_csrf_token)) {
			$stored_tokens = $session->get_csrf_tokens();
			/* echo "<br/> Stored token in array is: <br/>";
			print_r($stored_tokens);
			echo "<br/>";
			echo "<br/> Stored token in the session is : <br/>";
			print_r($_SESSION);
			echo "<br/>"; */
			
			// compares the two arrays, $user_tokens to $stored_tokens and returns the elements that are not matching.
			// $result = array_diff($user_tokens, $stored_tokens); // Using an array
			if (in_array($post_csrf_token, $stored_tokens)) {
				/* echo "Token is in stored array <br/>"; */
				return true;
			} else {
				// echo "Token is not in stored array <br/>";
				return false;
			}
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
		$max_elapsed = 60 * 60 * 1; // 1 hour
		
		// isset($_SESSION['csrf_token_time'])
		if(count($session->get_csrf_tokens_time()) !== 0) {
			// $stored_time = $_SESSION['csrf_token_time'];
			$stored_times = $session->get_csrf_tokens_time();
			// The time for the forms created are always the same, so any form time can be used. In this case we search through all, but the first is returned.
			foreach ($stored_times as $token_time) {
				return ($token_time + $max_elapsed) >= time();
			}
		} else {
			// Remove expired token
			$this->destroy_csrf_tokens();
			return false;
		}
	}	
}

?>