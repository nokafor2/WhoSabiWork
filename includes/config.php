<?php
// Check which server is running whether its the local server for developing or production server for publishing.
if ($_SERVER["SERVER_NAME"] === 'localhost') {
	// Database Constants
	defined('DB_SERVER') ? null : define("DB_SERVER", "localhost");
	defined('DB_USER')   ? null : define("DB_USER", "root");
	defined('DB_PASS')   ? null : define("DB_PASS", "root");
	defined('DB_NAME')   ? null : define("DB_NAME", "db_whosabiwork");
	defined('DB_PORT')   ? null : define("DB_PORT", "3306");
} else {
	// Database Constants on live server when in production mode
	defined('DB_SERVER') ? null : define("DB_SERVER", "131.153.147.50");
	defined('DB_USER')   ? null : define("DB_USER", "husabiwo_admin");
	defined('DB_PASS')   ? null : define("DB_PASS", "bHx_BY7^q6G5");
	defined('DB_NAME')   ? null : define("DB_NAME", "husabiwo_db");
}

?>