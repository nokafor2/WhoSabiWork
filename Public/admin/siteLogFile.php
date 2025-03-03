<?php
require_once("../../includes/initialize.php");
if ($session->is_admin_logged_in()) {	 
	if (!$session->is_session_valid()) {
		// logout the user and end the session
		$session->logout();
		if (session_id() == '') {
		  session_start();
		}
		$session->message("Expired session: Please log-in again.");
		redirect_to("/Public/admin/loginAdmin.php"); 
	} 
} else {
	$session->message("Please log-in properlly.");
	redirect_to("/Public/admin/loginAdmin.php"); 
} 

$message = "";

global $session;

$users = User::find_all();
$customers = Customer::find_all();
$numOfUsers = User::count_all();
$numOfCustomers = Customer::count_all();

// Path to the log file
$usersLogfile = SITE_ROOT.DS.'Logs'.DS.'usersLog.txt';
$cusLogfile = SITE_ROOT.DS.'Logs'.DS.'customersLog.txt';
$adminsLogfile = SITE_ROOT.DS.'Logs'.DS.'adminsLog.txt';

if (isset($_GET['clearUserLogs']) && ($_GET['clearUserLogs'] == 'true')) {
	// Using this command will open and clearUserLogs everything in the log file.
	file_put_contents($usersLogfile, '');
	// Add the first log entry
	// Only persons with admin priviledges should be able to clear the log history
	admin_log_action('User Logs Cleared', "by User ID {$session->admin_full_name}");
	// redirect to this same page so that the URL won't 
	// have "clearUserLogs=true" anymore this will also prevent vulnerabilities to the website.
	redirect_to('siteLogFile.php');
}

if (isset($_GET['clearCusLogs']) && ($_GET['clearCusLogs'] == 'true')) {
	// Using this command will open and clearCusLogs everything in the log file.
	file_put_contents($cusLogfile, '');
	// Add the first log entry
	// Only persons with admin priviledges should be able to clear the log history
	admin_log_action('Customer Logs Cleared', "by User ID {$session->admin_full_name}");
	// redirect to this same page so that the URL won't 
	// have "clearCusLogs=true" anymore this will also prevent vulnerabilities to the website.
	redirect_to('siteLogFile.php');
}

if (isset($_GET['clearAdminsLogs']) && ($_GET['clearAdminsLogs'] == 'true')) {
	// Using this command will open and clearAdminsLogs everything in the log file.
	file_put_contents($adminsLogfile, '');
	// Add the first log entry
	// Only persons with admin priviledges should be able to clear the log history
	admin_log_action('Admin Logs Cleared', "by User ID {$session->admin_full_name}");
	// redirect to this same page so that the URL won't 
	// have "clearAdminsLogs=true" anymore this will also prevent vulnerabilities to the website.
	redirect_to('siteLogFile.php');
}

?>


<?php
	$scriptName = scriptPathName();
	// Initialize the header of the webpage
	echo getUniquePageHeader($scriptName);
?>

<div id="topContainer">
	<!-- Include the header and navigation of the website. This will make the header and navigation section consistent -->
	<?php include_layout_template('navigation_header.php'); ?>
</div>

<!-- Begining of Container -->
<div id="container">
  <!-- Begining of Main Section -->
  <div id="mainAdminPage">
	<?php echo $message; ?><br/>
    <h2>Log File</h2>
    
	<p><a class="btnStyle1" href="adminPage.php">&laquo; Back</a></p>
    <!-- Begining of tabbed panel -->
    <div id="TabbedPanels1" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Users Logs</li>
        <li class="TabbedPanelsTab" tabindex="0">Customers Logs</li>
		<li class="TabbedPanelsTab" tabindex="0">Admin Logs</li>
      </ul>
	  
	  <!-- Begining of Tabbed Panels Content Group -->
      <div class="TabbedPanelsContentGroup">
	    <!-- Users Tabbed Panel Content -->
        <div class="TabbedPanelsContent">
			<!-- The href has '?clearUserLogs=true' this will set a default value for the global variable $_GET[] to be true when it is clicked -->
			<p><a href="siteLogFile.php?clearUserLogs=true">Clear log file</a></p>

			<?php
			  // This code will read in the log file
			  // Check if the file exists, is readable and it is able to open
			  if( file_exists($usersLogfile) && is_readable($usersLogfile) && 
						$handle = fopen($usersLogfile, 'r')) {  // read
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
				echo "Could not read from {$usersLogfile}.";
			  }

			?>
		</div>
		<!-- End of Users Tabbed Panel Content -->
		
		<!-- Begining of Customer Log -->
        <div class="TabbedPanelsContent">
			<!-- The href has '?clearCusLogs=true' this will set a default value for the global variable $_GET[] to be true when it is clicked -->
			<p><a href="siteLogFile.php?clearCusLogs=true">Clear log file</a></p>

			<?php
			  // This code will read in the log file
			  // Check if the file exists, is readable and it is able to open
			  if( file_exists($cusLogfile) && is_readable($cusLogfile) && 
						$cusHandle = fopen($cusLogfile, 'r')) {  // read
				// Create an unordered list for the title of the file contents.
				echo "<ul class=\"log-entries\">";
					// Run a loop to check end of the file
					while(!feof($cusHandle)) {
						// Read one line at a time from the file using fgets()
						$cusEntry = fgets($cusHandle);
						// This will trim away any white space from the cusEntry of the file
						if(trim($cusEntry) != "") {
							// Output the entries of the log file.
							echo "<li>{$cusEntry}</li>";
						}
					}
					echo "</ul>";
				fclose($cusHandle);
			  } else {
				// Display an error message if log file could not open
				echo "Could not read from {$cusLogfile}.";
			  }

			?>
		</div>
		<!-- End of Customer Log -->
		
		<!-- Begining of Admin Log -->
		<div class="TabbedPanelsContent">
			<!-- The href has '?clearAdminsLogs=true' this will set a default value for the global variable $_GET[] to be true when it is clicked -->
			<p><a href="siteLogFile.php?clearAdminsLogs=true">Clear log file</a></p>

			<?php
			  // This code will read in the log file
			  // Check if the file exists, is readable and it is able to open
			  if( file_exists($adminsLogfile) && is_readable($adminsLogfile) && 
						$adminsHandle = fopen($adminsLogfile, 'r')) {  // read
				// Create an unordered list for the title of the file contents.
				echo "<ul class=\"log-entries\">";
					// Run a loop to check end of the file
					while(!feof($adminsHandle)) {
						// Read one line at a time from the file using fgets()
						$adminsEntry = fgets($adminsHandle);
						// This will trim away any white space from the adminsEntry of the file
						if(trim($adminsEntry) != "") {
							// Output the entries of the log file.
							echo "<li>{$adminsEntry}</li>";
						}
					}
					echo "</ul>";
				fclose($adminsHandle);
			  } else {
				// Display an error message if log file could not open
				echo "Could not read from {$adminsLogfile}.";
			  }

			?>
			
		</div>
		<!-- End of Admin Log -->
		
      </div>
	  <!-- End of Tabbed Panels Content Group -->
  
	</div>
	<!-- End of tabbed panel -->
  </div>
  <!-- End of Main Section -->

  <!-- Display the footer section -->
  <?php include_layout_template('navigation_footer.php'); ?>
</div>
<!-- End of Container -->

<?php
	// Initialize the footer of the webpage
	echo getUniquePageFooter($scriptName);
?>
