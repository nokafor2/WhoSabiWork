<?php
	require_once(LIB_PATH.DS.'database.php');
	require_once(LIB_PATH.DS.'user.php');
	require_once(LIB_PATH.DS.'customer.php');

	$user = new User();
	$customer = new Customer();

	/**
	 * Make call to facebook endpoint
	 *
	 * @param string $endpoint make call to this enpoint
	 * @param array $params array keys are the variable names required by the endpoint
	 *
	 * @return array $response
	 */
	function makeFacebookApiCall( $endpoint, $params ) {
		// open curl call, set endpoint and other curl params
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $endpoint . '?' . http_build_query( $params ) );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );

		// get curl response, json decode it, and close curl
		$fbResponse = curl_exec( $ch );
		$fbResponse = json_decode( $fbResponse, TRUE );
		curl_close( $ch );

		return array( // return response data
			'endpoint' => $endpoint,
			'params' => $params,
			'has_errors' => isset( $fbResponse['error'] ) ? TRUE : FALSE, // boolean for if an error occured
			'error_message' => isset( $fbResponse['error'] ) ? $fbResponse['error']['message'] : '', // error message
			'fb_response' => $fbResponse // actual response from the call
		);
	}

	/**
	 * Get facebook api login url that will take the user to facebook and present them with login dialog
	 *
	 * Endpoint: https://www.facebook.com/{fb-graph-api-version}/dialog/oauth?client_id={app-id}&redirect_uri={redirect-uri}&state={state}&scope={scope}&auth_type={auth-type}
	 *
	 * @param void
	 *
	 * @return string
	 */
	function getFacebookLoginUrl() {
		// endpoint for facebook login dialog
		$endpoint = 'https://www.facebook.com/' . FB_GRAPH_VERSION . '/dialog/oauth';

		$params = array( // login url params required to direct user to facebook and promt them with a login dialog
			'client_id' => FB_APP_ID,
			'redirect_uri' => FB_REDIRECT_URI,
			'state' => FB_APP_STATE,
			'scope' => 'email',
			'auth_type' => 'rerequest'
		);

		// return login url
		return $endpoint . '?' . http_build_query( $params );
	}

	/**
	 * Get an access token with the code from facebook
	 *
	 * Endpoint https://graph.facebook.com/{fb-graph-version}/oauth/access_token?client_id{app-id}&client_secret={app-secret}&redirect_uri={redirect_uri}&code={code}
	 *
	 * @param string $code
	 *
	 * @return array $response
	 */
	function getAccessTokenWithCode( $code ) {
		// endpoint for getting an access token with code
		$endpoint = FB_GRAPH_DOMAIN . FB_GRAPH_VERSION . '/oauth/access_token';

		$params = array( // params for the endpoint
			'client_id' => FB_APP_ID,
			'client_secret' => FB_APP_SECRET,
			'redirect_uri' => FB_REDIRECT_URI,
			'code' => $code
		);

		// make the api call
		return makeFacebookApiCall( $endpoint, $params );
	}

	/**
	 * Get a users facebook info
	 *
	 * Endpoint https://graph.facebook.com/me?fields={fields}&access_token={access-token}
	 *
	 * @param string $accessToken
	 *
	 * @return array $response
	 */
	function getFacebookUserInfo( $accessToken ) {
		// endpoint for getting a users facebook info
		$endpoint = FB_GRAPH_DOMAIN . 'me';

		$params = array( // params for the endpoint
			'fields' => 'first_name,last_name,email,gender,picture',
			'access_token' => $accessToken
		);

		// make the api call
		return makeFacebookApiCall( $endpoint, $params );
	}

	/**
	 * Try and log a user in with facebook
	 *
	 * @param array $get contains the url $_GET variables from the redirect uri after user authenticates with facebook
	 *
	 * @return array $response
	 */
	function tryAndLoginWithFacebook( $get ) {
		// assume fail
		$status = 'fail';
		$message = '';
		global $user;
		global $customer;

		// reset session vars
		$_SESSION['fb_access_token'] = array();
		$_SESSION['fb_user_info'] = array();
		$_SESSION['eci_login_required_to_connect_facebook'] = false;

		if ( isset( $get['error'] ) ) { // error comming from facebook GET vars
			$message = $get['error_description'];
		} else { // no error in facebook GET vars
			// get an access token with the code facebook sent us
			$accessTokenInfo = getAccessTokenWithCode( $get['code'] );

			if ( $accessTokenInfo['has_errors'] ) { // there was an error getting an access token with the code
				$message = $accessTokenInfo['error_message'];
			} else { // we have access token! :D
				// set access token in the session
				$_SESSION['fb_access_token'] = $accessTokenInfo['fb_response']['access_token'];

				// get facebook user info with the access token
				$fbUserInfo = getFacebookUserInfo( $_SESSION['fb_access_token'] );

			 	// Check there was no errors trying to retrieve data from facebook
				if ( !$fbUserInfo['has_errors'] && !empty( $fbUserInfo['fb_response']['id'] ) && !empty( $fbUserInfo['fb_response']['email'] ) ) { 
					// facebook gave us the users id/email
					// 	all good!
					$status = 'ok';
					// save user info to session
					$_SESSION['fb_user_info'] = $fbUserInfo['fb_response'];

					if ($_SESSION['profilePortalType'] === 'user') {
						// check for user with facebook id
						$userInfoWithId = $user->find_by_column_name('fb_user_id', $fbUserInfo['fb_response']['id']);
						// check for user with email
						$userInfoWithEmail = $user->find_by_column_name('user_email', $fbUserInfo['fb_response']['email'] );
						
						// Now try to sign in the user
						// Check if the user has a facebook id already logged into the website before
						if ( $userInfoWithId || ( $userInfoWithEmail && !$userInfoWithEmail->password ) ) { 
							// user has logged in with facebook before so we found them
							// update user
							loginFBaccount($userInfoWithId, 'user');
						} elseif ( $userInfoWithEmail && !$userInfoWithEmail->fb_user_id ) {
							// existing account with our website exists for the email and has not logged in with facebook before
							$_SESSION['eci_login_required_to_connect_facebook'] = true;
						} else {
							// user not found with id/email sign them up and log them in
							// sign user up
							$status = "fail";
							$message = "Sorry, your facebook email doesn't match any account in our website.";
							// Redirect to customer create account page
							// redirect_to('https://whosabiwork.com/Public/createUserAccount.php');
						}
					} elseif ($_SESSION['profilePortalType'] === 'customer') {
						$userInfoWithId = $customer->find_by_column_name('fb_user_id', $fbUserInfo['fb_response']['id']);
						$userInfoWithEmail = $customer->find_by_column_name('customer_email', $fbUserInfo['fb_response']['email'] );
						
						// Now try to sign in the user
						// Check if the user has a facebook id already logged into the website before
						if ( $userInfoWithId || ( $userInfoWithEmail && !$userInfoWithEmail->password ) ) { 
						    // user has logged in with facebook before so we found them
							// update user
							loginFBaccount($userInfoWithId, 'customer');
						} elseif ( $userInfoWithEmail && !$userInfoWithEmail->fb_user_id ) { 
						    // existing account with our website exists for the email and has not logged in with facebook before
							$_SESSION['eci_login_required_to_connect_facebook'] = true;
						} else { 
						    // user not found with id/email sign them up and log them in
							// sign user up
							$status = "fail";
							$message = "Sorry, your facebook email doesn't match any account in our website.";
							// Redirect to customer create account page
							// redirect_to('https://whosabiwork.com/Public/createBusinessAccount.php');
						}
					}						
				} else {
					$message = 'Invalid facebook credentials';
				}
			}
		}

		return array( 
			// return status and message of login
			'status' => $status,
			'message' => $message,
		);
	}

	/**
	 * Get debug info on an access token
	 *
	 * Endpoint https://graph.facebook.com/debug_token?input_token={access-token}&access_token={access-token}
	 *
	 * @param string $accessToken
	 *
	 * @return array $response
	 */
	function getDebugAccessTokenInfo( $accessToken ) {
		// endpoint for getting debug info
		$endpoint = FB_GRAPH_DOMAIN . 'debug_token';

		$params = array( // params for the endpoint
			'input_token' => $accessToken,
			'access_token' => $accessToken
		);

		// make the api call
		return makeFacebookApiCall( $endpoint, $params );
	}

	// Universal function for signing in users and customers
	function loginFBaccount($foundAccount, $accountType) {
    global $session;
		$foundAccount->fb_access_token = $_SESSION['fb_access_token'];
		$foundAccount->update();
    // Clear Facebook session used
    clearFBsession();
	    
		// Update the facebook access token
		if ($accountType === 'user') {
			$login_check = new User_Failed_Login();
			// Clear any attempt of login trial after authentication is passed
			$login_check->clear_user_failed_logins($foundAccount->user_email);
			// If the user is found, tell session to log them in.
			$session->user_login($foundAccount);
			$session->message("Welcome back {$foundAccount->full_name()}.");
			// Create a log that the user has logged into the system
			user_log_action('Login', "{$foundAccount->username} logged in.");
			// check if the user is already adovacting a user before redirecting to the livePhotosFedd page
			$cusAdvocated = Advocate::find_all_advocated_by_user($foundAccount->id);
			if (isset($_SESSION['returnPageTo']) && !empty($_SESSION['returnPageTo'])) {
				redirect_to($_SESSION['returnPageTo']);
			} else {
				redirect_to("/Public/livePhotosFeed.php");
			} /* elseif (!empty($cusAdvocated)) {
				redirect_to("/Public/livePhotosFeed.php");
			} else {
				redirect_to('/Public/user/userEditPage.php?id='.urlencode($foundAccount->id));
			} */
		} elseif ($accountType === 'customer') {
			$cus_login_check = new Customer_Failed_Login();
			// Clear any attempt of login trial after authentication is passed
			$cus_login_check->clear_customer_failed_logins($foundAccount->customer_email);
			// If the customer is found, tell session to log them in.
			$session->customer_login($foundAccount);
			$session->message("Welcome back {$foundAccount->full_name()}.");
			// Create a log that the customer has logged into the system
			cus_log_action('Login', "{$foundAccount->username} logged in.");
			// check if the customer is already adovacting a customer before redirecting to the livePhotosFedd page
			$cusAdvocated = Advocate::find_all_advocated_by_customer($foundAccount->id);
			if (isset($_SESSION['returnPageTo']) && !empty($_SESSION['returnPageTo'])) {
				redirect_to($_SESSION['returnPageTo']);
			} else {
				redirect_to("/Public/livePhotosFeed.php");
			} /* elseif (!empty($cusAdvocated)) {
				redirect_to("/Public/livePhotosFeed.php");
			} else {
				redirect_to('/Public/customer/customerEditPage2.php?id='.urlencode($foundAccount->id));
			} */
		}		
	}