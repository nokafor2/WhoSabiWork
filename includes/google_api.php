<?php
	// Import google functions
	require_once('google-login/vendor/autoload.php');
	// create client to access google api
	$google_client = new Google_Client();
	// global $google_client;

	// Set client ID
	$google_client->setClientID('705286549634-ojc97l32j19c4ab6fnan1jqfquv933mv.apps.googleusercontent.com');

	// Set client secret key
	$google_client->setClientSecret('v18nN6g4_d-Rt99DAZjvu5Lg');

	// define redirect URL (this will be done on the login page, so the website can easily know if its a user or customer login used)

	// Add scope to access google API
	// GEt scope for email and user profile
	$google_client->addScope('email');
	$google_client->addScope('profile');

	function tryAndLoginWithGoogle($get) {
		// assume fail
		$status = 'fail';
		$message = '';
		global $user;
		global $customer;
		global $google_client;

		// reset session vars
		$_SESSION['google_access_token'] = array();
		$_SESSION['google_user_info'] = array();
		$_SESSION['request_to_connect_google'] = false;

		/* echo "<pre>";
			echo "Google GET contents are: <br/>";
			print_r($get);
		echo "</pre>"; */

		// get an access token with the code facebook sent us
		$accessTokenInfo = $google_client->fetchAccessTokenWithAuthCode( $get['code'] );
		/* echo "<pre>";
			echo "Google token contents are: <br/>";
			print_r($accessTokenInfo);
		echo "</pre>";	 */

		if ( isset( $accessTokenInfo['error'] ) ) { // error comming from facebook GET vars
			$message = $accessTokenInfo['error_description'];			
		} else { 
			// no error in facebook GET vars
			// Set the google access token received
			$google_client->setAccessToken($accessTokenInfo['access_token']);

			// set access token in the session
			$_SESSION['google_access_token'] = $accessTokenInfo['access_token'];

			// Create object for user profile data
			$google_service = new Google_Service_Oauth2($google_client);

			// Return user profile data
			$userData = $google_service->userinfo->get();
			/* echo "<pre>";
				echo "returned user data are: <br/>";
				print_r($userData);
			echo "</pre>"; */

			// Check data was retrieved from Google
			if (!empty($userData['id']) && !empty($userData['email'])) {
				$status = 'ok';
				//save user info to session
				// $_SESSION['google_user_info'] = $userData;
				$_SESSION['google_user_info']['id'] = $userData['id'];
				$_SESSION['google_user_info']['email'] = $userData['email'];

				if ($_SESSION['profilePortalType'] === 'user') {
					// check for user with google id
					$userInfoWithId = $user->find_by_column_name('google_user_id', $userData['id']);
					// check for user with google email
					$userInfoWithEmail = $user->find_by_column_name('user_email', $userData['email'] );

					// Now try to sign in the user
					// Check if the user has a facebook id already logged into the website before
					if ( $userInfoWithId || ( $userInfoWithEmail && !$userInfoWithEmail->password ) ) { 
						// user has logged in with facebook before so we found them
						// update user
						loginGoogleAccount($userInfoWithId, 'user');
					} elseif ( $userInfoWithEmail && !$userInfoWithEmail->google_user_id ) {
						// existing account with our website exists for the email and has not logged in with facebook before
						$_SESSION['request_to_connect_google'] = true;
					} else {
						// user not found with id/email sign them up and log them in
						// sign user up
						$status = "fail";
						$message = "Sorry, your Google email doesn't match any account in our website.";
						// Redirect to customer create account page
						// redirect_to('https://whosabiwork.com/Public/createUserAccount.php');
					}
				} elseif ($_SESSION['profilePortalType'] === 'customer') {
					// check for user with google id
					$userInfoWithId = $customer->find_by_column_name('google_user_id', $userData['id']);
					$userInfoWithEmail = $customer->find_by_column_name('customer_email', $userData['email'] );

					// Now try to sign in the user
					// Check if the user has a google id already logged into the website before
					if ( $userInfoWithId || ( $userInfoWithEmail && !$userInfoWithEmail->password ) ) { 
					  // user has logged in with facebook before so we found them
						// update user
						loginGoogleAccount($userInfoWithId, 'customer');
					} elseif ( $userInfoWithEmail && !$userInfoWithEmail->google_user_id ) { 
					  // existing account with our website exists for the email and has not logged in with facebook before
						$_SESSION['request_to_connect_google'] = true;
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

		return array( 
			// return status and message of login
			'status' => $status,
			'message' => $message,
		);
	}


	// Universal function for signing in users and customers
	function loginGoogleAccount($foundAccount, $accountType) {
    global $session;
		$foundAccount->google_access_token = $_SESSION['google_access_token'];
		$foundAccount->update();
    // Clear Facebook session used
    clearGoogleSession();
	    
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
?>
