<?php require_once("../../includes/initialize.php"); ?>
<?php //if (!$session->is_logged_in()) { redirect_to("loginPage.php"); } ?>
<?php

	// Path to the log file
	$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
  
	if(isset($_GET['clear']) && ($_GET['clear'] == 'true')) {
	    // Using this command will open and clear everything in the log file.
		file_put_contents($logfile, '');
		// Add the first log entry
		log_action('Logs Cleared', "by User ID {$session->user_id}");
		// redirect to this same page so that the URL won't 
		// have "clear=true" anymore this will also prevent vulnerabilities to the website.
		redirect_to('logfile.php');
	}
?>

<?php include_layout_template('admin_header.php'); ?>

<a href="indexPictures.php">&laquo; Back</a><br />
<br />

<h2>Log File</h2>

<!-- The href has '?clear=true' this will set a default value for the global variable $_GET[] to be true when it is clicked -->
<p><a href="logfile.php?clear=true">Clear log file</a></p>

<?php
  // This code will read in the log file
  // Check if the file exists, is readable and it is able to open
  if( file_exists($logfile) && is_readable($logfile) && 
			$handle = fopen($logfile, 'r')) {  // read
	// Create an unordered list for the title of the file contents.
    echo "<ul class=\"log-entries\">";
		// Run a loop to check end of the file
		while(!feof($handle)) {
			// Read one line at a time from the file using fgets()
			$entry = fgets($handle);
			// This will trim away any white space from the entry of the file
			if(trim($entry) != "") {
				// Output the entries of the log file.
				echo "<li>{$entry}</li>";
			}
		}
		echo "</ul>";
    fclose($handle);
  } else {
	// Display an error message if log file could not open
    echo "Could not read from {$logfile}.";
  }

?>

<?php include_layout_template('admin_footer.php'); ?>
