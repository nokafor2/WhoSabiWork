<?php
// Adjust the PHP.ini variables
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// changed from 2M in php.ini
ini_set('upload_max_filesize', '100M');

// changed from 8M in php.ini
ini_set('post_max_size', '100M'); 

// changed from 128M to 256M
ini_set('memory_limit', '256M');

// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

// Check which server is running whether its the local servr for developing or production server for publishing.
if ($_SERVER["SERVER_NAME"] === 'localhost') {
	// this defines the file system path to the root folder containing the website pages
	defined('SITE_ROOT') ? null : 
		define('SITE_ROOT', DS.'MAMP'.DS.'htdocs');
} else {
	// this defines the file system path to the root folder containing the website pages when on production mode in the live server
	defined('SITE_ROOT') ? null : 
		define('SITE_ROOT', DS.'home2'.DS.'husabiwo'.DS.'public_html');	
}

// This is a defined path for the includes directory because it is called often
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// load config file first
require_once(LIB_PATH.DS.'config.php');

// load basic functions next so that everything after can use them
require_once(LIB_PATH.DS.'session.php');
require_once(LIB_PATH.DS.'functions.php');

// load core objects
require_once(LIB_PATH.DS.'database.php');
require_once(LIB_PATH.DS.'database_object.php');
require_once(LIB_PATH.DS.'pagination.php');
require_once(LIB_PATH.DS.'validation.php');
require_once(LIB_PATH.DS.'public_image_display.php');
require_once(LIB_PATH.DS.'public_ad_display.php');
require_once(LIB_PATH.DS.'security_functions.php');
require_once(LIB_PATH.DS.'encryption.php');
// Import PHPMailer classes into the global namespace through this file
require_once(LIB_PATH.DS.'phpmailer/vendor/autoload.php');
require_once(LIB_PATH.DS.'phpmailer/sendMail.php');

// load database-related classes
require_once(LIB_PATH.DS.'db_table_class.php');
require_once(LIB_PATH.DS.'blacklisted_ip.php');
require_once(LIB_PATH.DS.'artisan.php');
require_once(LIB_PATH.DS.'seller.php');
require_once(LIB_PATH.DS.'user.php');
require_once(LIB_PATH.DS.'admin.php');
require_once(LIB_PATH.DS.'address.php');
require_once(LIB_PATH.DS.'customer.php');
require_once(LIB_PATH.DS.'photograph.php');
require_once(LIB_PATH.DS.'vehicle_category.php');
require_once(LIB_PATH.DS.'business_category.php');
require_once(LIB_PATH.DS.'car_brand.php');
require_once(LIB_PATH.DS.'bus_brand.php');
require_once(LIB_PATH.DS.'truck_brand.php');
require_once(LIB_PATH.DS.'technical_service.php');
require_once(LIB_PATH.DS.'user_comment.php');
require_once(LIB_PATH.DS.'user_reply.php');
require_once(LIB_PATH.DS.'customer_rating.php');
require_once(LIB_PATH.DS.'customers_availability.php');
require_once(LIB_PATH.DS.'customers_appointment.php');
require_once(LIB_PATH.DS.'spare_part.php');
require_once(LIB_PATH.DS.'comment.php');
require_once(LIB_PATH.DS.'user_failed_login.php');
require_once(LIB_PATH.DS.'users_feedback.php');
require_once(LIB_PATH.DS.'customer_failed_login.php');
require_once(LIB_PATH.DS.'admin_failed_login.php');
require_once(LIB_PATH.DS.'state_town.php');
require_once(LIB_PATH.DS.'user_photograph.php');
require_once(LIB_PATH.DS.'advocate.php');
require_once(LIB_PATH.DS.'photograph_like.php');
require_once(LIB_PATH.DS.'photograph_comment.php');
require_once(LIB_PATH.DS.'photograph_reply.php');
require_once(LIB_PATH.DS.'csrf_token.php');
require_once(LIB_PATH.DS.'exception_class.php');
require_once(SITE_ROOT.DS.'Public'.DS.'layouts'.DS.'header.php');
require_once(SITE_ROOT.DS.'Public'.DS.'layouts'.DS.'footer.php');


// Initialize facebook credentials
// The facebook FB_REQUEST_URI constant will be defined in the loginPage.php to detect if its a user or customer sign-in portal that is used
defined('FB_APP_ID') ? null : define( 'FB_APP_ID', '2599070017069589' );
defined('FB_APP_SECRET') ? null : define( 'FB_APP_SECRET', '0303a781809d4b6938f2b93c8d66f639' );
defined('FB_GRAPH_VERSION') ? null : define( 'FB_GRAPH_VERSION', 'v11.0' ); // facebook graph version
defined('FB_GRAPH_DOMAIN') ? null : define( 'FB_GRAPH_DOMAIN', 'https://graph.facebook.com/' ); // base domain for api
defined('FB_APP_STATE') ? null : define( 'FB_APP_STATE', 'eciphp' ); // verify state

require_once(LIB_PATH.DS.'facebook_api.php');


// Initialize google credentials
require_once(LIB_PATH.DS.'google_api.php');

// Check for blacklisted ips before getting into the website.
$blacklist = new Blacklisted_Ip();
$blacklist->block_blacklisted_ips();

?>