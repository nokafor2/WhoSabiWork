<?php
// A class to help work with Sessions
// In our case, primarily to manage logging users in and out

// Keep in mind when working with sessions that it is generally 
// inadvisable to store DB-related objects in sessions

// Useful php.ini file settings:
// session.cookie_lifetime = 0
// session.cookie_secure = 1
// session.cookie_httponly = 1
// session.use_only_cookies = 1
// session.entropy_file = "/dev/urandom"

class Session {
	
	// user_logged_in is made private, so that only the session is able to know if a user has been logged in or not.
	// User variables
	private $user_logged_in=false;
	public $user_id;
	public $user_username;
	public $user_full_name;
	// Customer variables
	private $customer_logged_in = false;
	public $customer_id; 
	public $customer_username;
	public $customer_full_name;
	// Admin variables
	private $admin_logged_in = false;
	public $admin_id; 
	public $admin_username;
	public $admin_full_name;
	// Other variables
	public $message;
	public $link_id;
	public $csrf_tokens = array();
	public $csrf_tokens_time = array();
	
	// The session construct is used to start the session when an instance of the session class is created.
	function __construct() {
		// check if a session has already started
		if (session_id() == '') {
		   session_start();
		}
		// once the session starts, the login variables for the user, customer, admin and message will be checked.
		$this->check_user_login();
		$this->check_customer_login();
		$this->check_admin_login();
		$this->check_message();
		$this->check_csrf_tokens();
		$this->check_csrf_tokens_time();
		// $this->link_id();
		
		/* if($this->user_logged_in) {
		  // actions to take right away if user is logged in
		} else {
		  // actions to take right away if user is not logged in
		} */
	}
	
	// is used to get the boolean value of user_logged_in
	public function is_user_logged_in() {
		return $this->user_logged_in;
	}
	
	// is used to get the boolean value of customer_logged_in
	public function is_customer_logged_in() {
		return $this->customer_logged_in;
	}
	
	// is used to get the boolean value of admin_logged_in
	public function is_admin_logged_in() {
		return $this->admin_logged_in;
	}

	// this function marks a user as logged in after verifying its details
	public function user_login($user) {
		// database should find user based on username/password
		if($user){
		  $this->after_successful_login();
		  
		  $this->user_id = $_SESSION['user_id'] = $user->id;
		  $this->user_username = $_SESSION['user_username'] = $user->username;
		  $this->user_full_name = $_SESSION['user_full_name'] = $user->full_name();
		  $this->user_logged_in = $_SESSION['user_logged_in'] = true;
		}
  }
	
	// this function marks a customer as logged in after verifying its details
	public function customer_login($customer) {
		// database should find user based on username/password
		if($customer){
		  $this->after_successful_login();
		  
		  $this->customer_id = $_SESSION['customer_id'] = $customer->id;
		  $this->customer_username = $_SESSION['customer_username'] = $customer->username;
		  $this->customer_full_name = $_SESSION['customer_full_name'] = $customer->full_name();
		  $this->customer_logged_in = $_SESSION['customer_logged_in'] = true;
		}
  }
	
	// this function marks a admin as logged in after verifying its details
	public function admin_login($admin) {
		// database should find admin based on username/password
		if($admin){
		  $this->after_successful_login();
		  
		  $this->admin_id = $_SESSION['admin_id'] = $admin->id;
		  $this->admin_username = $_SESSION['admin_username'] = $admin->username;
		  $this->admin_full_name = $_SESSION['admin_full_name'] = $admin->full_name();
		  $this->admin_logged_in = $_SESSION['admin_logged_in'] = true;
		}
  }

	// This function is used to find out if the user id is set and sets the value of user_logged_in to be either true of false.
	private function check_user_login() {
		if(isset($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
			$this->user_username = $_SESSION['user_username'];
			$this->user_full_name = $_SESSION['user_full_name'];
			$this->user_logged_in = true;
			return true;
		} else {
		/*	unset($this->user_id);
			unset($this->user_username);
			unset($this->user_full_name);
			$this->user_logged_in = false;	*/
			return false;
		}
	}
	
	// This function is used to find out if the customer id is set and sets the value of customer_logged_in to be either true of false.
	private function check_customer_login() {
		if(isset($_SESSION['customer_id'])) {
			$this->customer_id = $_SESSION['customer_id'];
			$this->customer_username = $_SESSION['customer_username'];
			$this->customer_full_name = $_SESSION['customer_full_name'];
			$this->customer_logged_in = true;
			return true;
		} else {
		/*	unset($this->customer_id);
			unset($this->customer_username);
			unset($this->customer_full_name);
			$this->customer_logged_in = false;	*/
			return false;
		}
	}
	
	// This function is used to find out if the admin id is set and sets the value of admin_logged_in to be either true of false.
	private function check_admin_login() {
		if(isset($_SESSION['admin_id'])) {
			$this->admin_id = $_SESSION['admin_id'];
			$this->admin_username = $_SESSION['admin_username'];
			$this->admin_full_name = $_SESSION['admin_full_name'];
			$this->admin_logged_in = true;
			return true;
		} else {
/*			unset($this->admin_id);
			unset($this->admin_username);
			unset($this->admin_full_name);
			$this->admin_logged_in = false;	*/
			
			return false;
		}
	}
	
	// This function logs a user out
    public function user_logout() {
		unset($_SESSION['user_id']);
		unset($this->user_id);
		unset($_SESSION['user_username']);
		unset($this->user_username);
		unset($_SESSION['user_full_name']);
		unset($this->user_full_name);
		// $this->user_logged_in = false;
		unset($this->user_logged_in);
		unset($_SESSION['user_logged_in']);
    }
	
	// This function logs a customer out
    public function customer_logout() {
		unset($_SESSION['customer_id']);
		unset($this->customer_id);
		unset($_SESSION['customer_username']);
		unset($this->customer_username);
		unset($_SESSION['customer_full_name']);
		unset($this->customer_full_name);
		// $this->customer_logged_in = false;
		unset($this->customer_logged_in);
		unset($_SESSION['customer_logged_in']);
    }
	
	// This function logs a admin out
    public function admin_logout() {
		unset($_SESSION['admin_id']);
		unset($this->admin_id);
		unset($_SESSION['admin_username']);
		unset($this->admin_username);
		unset($_SESSION['admin_full_name']);
		unset($this->admin_full_name);
		// $this->admin_logged_in = false;
		unset($this->admin_logged_in);
		unset($_SESSION['admin_logged_in']);
    }
	
	public function logout() {
		if ($this->is_user_logged_in()) {
			$this->user_logout();
		} elseif ($this->is_customer_logged_in()) {
			$this->customer_logout();
		} else {
			$this->admin_logout();
		}
		$this->end_session();
    }
	
	public function set_link_id($id){
		$this->link_id = $_SESSION['link_id'] = $id;
	}
	
	public function link_id(){
		return $this->link_id;
		// return $_SESSION['link_id'];
	}
	
	// This function will perform a dual duty of setting a message value, and get a message value
	public function message($msg="") {
	  // Check if the message variable '$msg' is empty or not empty
	  if(!empty($msg)) {
	    // then this is "set message"
	    // make sure you understand why $this->message=$msg wouldn't work
		// We do it this way to put the message in the $_SESSION global variable so that it will always be available from any class. But if you use the instance, it will not have the free access to it.
	    $_SESSION['message'] = $msg;
	  } else {
	    // then this is "get message"
			return $this->message;
	  }
	}
  
	// This will check to know if we have a message stored. We prefer to put all the messages in the session, so when a web page is reloaded, the messages will not be lost instead they will be saved in the session so the user can see them when the page is redirected.
	private function check_message() {
		// Is there a message stored in the session? This are the messages saved into the $_SESSION global from other wep pages.
		if(isset($_SESSION['message'])) {
			// Add it as an attribute and erase the stored version
			$this->message = $_SESSION['message'];
			// unset($_SESSION['message']);
		} else {
			$this->message = "";
		}
	}
	
	/* CSRF Functions */
	public function set_csrf_tokens($index="", $token="") {
		// changed index of csrf_tokens to 'csrf_token_'.$index
		$this->csrf_tokens['csrf_token_'.$index] = $_SESSION['csrf_token_'.$index] = $token;
	}
	
	public function set_csrf_tokens_time($index="", $time="") {
		// changed index of csrf_time to 'csrf_time_'.$index
		$this->csrf_tokens_time['csrf_time_'.$index] = $_SESSION['csrf_time_'.$index] = $time;
	}
	
	public function get_csrf_tokens() {
		return $this->csrf_tokens;
	}
	
	public function get_csrf_tokens_time() {
		return $this->csrf_tokens_time;
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
	
	// This will check to know if we have a token/token time stored. We prefer to put all the tokens in the session, so when a web page is reloaded, the token will not be lost instead they will be saved in the session so the user can see them when the page is redirected.
	private function check_csrf_tokens() {
		// Is there a token stored in the session? This is the token saved into the $_SESSION global from other web pages.
		list($session_keys, $session_tokens) = $this->session_token_search('csrf_token_');
		foreach ($session_tokens as $key => $token) {
			
			if (isset($token)) {
				// Add it as an attribute and erase the stored version
				$token_key = $session_keys[$key];
				$this->csrf_tokens[$token_key] = $token;
				// Unsetting the session token will remove duplicate of the token key in the session.
				// unset($_SESSION[$token_key]);
			} else {
				$this->csrf_tokens[] = "";
			}
		}
	}
	
	private function check_csrf_tokens_time() {
		// Is there a time stored in the session? This is the time saved into the $_SESSION global from other wep pages.
		list($session_keys, $session_times) = $this->session_token_search('csrf_time_');
		foreach ($session_times as $key => $time) {
			
			if (isset($time)) {
				// Add it as an attribute and erase the stored version
				$time_key = $session_keys[$key];
				$this->csrf_tokens_time[$time_key] = $time;
				// Unsetting the session token time will remove duplicate of the token time in the session.
				// unset($_SESSION[$time_key]);
			} else {
				$this->csrf_tokens_time[] = "";
			}
		}
	}
	/* End of CSRF Functions */
	
	/* Begining of Security Functions */
	
	// Function to forcibly end the session
	private function end_session() {
		// Use both for compatibility with all browsers
		// and all versions of PHP.
		session_unset();
	    session_destroy();
	}

	// Does the request IP match the stored value?
	private function request_ip_matches_session() {
		// return false if either value is not set
		if(!isset($_SESSION['ip']) || !isset($_SERVER['REMOTE_ADDR'])) {
			return false;
		}
		if($_SESSION['ip'] === $_SERVER['REMOTE_ADDR']) {
			return true;
		} else {
			return false;
		}
	}

	// Does the request user agent match the stored value?
	private function request_user_agent_matches_session() {
		// return false if either value is not set
		if(!isset($_SESSION['user_agent']) || !isset($_SERVER['HTTP_USER_AGENT'])) {
			return false;
		}
		if($_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']) {
			return true;
		} else {
			return false;
		}
	}

	// Has too much time passed since the last login?
	private function last_login_is_recent() {
		$max_elapsed = 60 * 60 * 24; // 1 day
		// return false if value is not set
		if(!isset($_SESSION['last_login'])) {
			return false;
		}
		if(($_SESSION['last_login'] + $max_elapsed) >= time()) {
			return true;
		} else {
			return false;
		}
	}

	// Should the session be considered valid?
	public function is_session_valid() {
		$check_ip = true;
		$check_user_agent = true;
		$check_last_login = true;

		if($check_ip && !$this->request_ip_matches_session()) {
			return false;
		}
		if($check_user_agent && !$this->request_user_agent_matches_session()) {
			return false;
		}
		if($check_last_login && !$this->last_login_is_recent()) {
			return false;
		}
		return true;
	}

	// If session is not valid, end and redirect to login page.
	public function confirm_session_is_valid() {
		if(!is_session_valid()) {
			$this->end_session();
			// Note that header redirection requires output buffering 
			// to be turned on or requires nothing has been output 
			// (not even whitespace).
			header("Location: /Public/loginPage.php"); // change login path
			exit;
		}
	}


	// Is user logged in already?
	// This function is similar to is_user_logged_in() 
	/* public function is_logged_in() {
		return (isset($_SESSION['logged_in']) && $_SESSION['logged_in']);
	} */

	// If user is not logged in, end and redirect to login page.
	// This is similar is_user_logged_in() function
	/* private function confirm_user_logged_in() {
		if(!is_logged_in()) {
			$this->end_session();
			// Note that header redirection requires output buffering 
			// to be turned on or requires nothing has been output 
			// (not even whitespace).
			header("Location: /Public/loginPage.php"); // change login path
			exit;
		}
	} */


	// Actions to preform after every successful login
	// Call this function inside the user_login(), customer_login() and admin_login() functions
	private function after_successful_login() {
		// Regenerate session ID to invalidate the old one.
		// Super important to prevent session hijacking/fixation.
		session_regenerate_id();
		
		// $_SESSION['logged_in'] = true; called in the user_login(), customer_login() and admin_login() function

		// Save these values in the session, even when checks aren't enabled 
	    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$_SESSION['last_login'] = time();
		
	}

	// Actions to preform after every successful logout
	// Modify for user, customer and admin
	// Similar to logout function
	/* public function after_successful_logout() {
		$_SESSION['logged_in'] = false;
		$this->end_session();
	} */

	// Actions to preform before giving access to any 
	// access-restricted page. 
	// This is similar to is_user_logged_in() function
	// Modify for user, customer and admin
	/* public function before_every_protected_page() {
		$this->confirm_user_logged_in();
		$this->confirm_session_is_valid();
	} */
	
	/* End of Security Functions */
  
}

// Create an instance of the session class
$session = new Session();
// We would use the message function to update the message variable.
// $message = $session->message();
$sessionMessage = $session->message();

?>