<?php
require_once("../../includes/initialize.php");

// Initialize the security function
$security = new Security_Functions();
// the request_is_get() fxn will ensure that a get request was sent from the webpage
if(request_is_get()) {	
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
	
	// Get the admin id 
	$adminId = $session->admin_id;

	// Get all the users
	$users = User::find_all();
	// Get all the customers
	$customers = Customer::find_all();
	// Count all the users
	$numOfUsers = User::count_all();
	// Count all the customers
	$numOfCustomers = Customer::count_all();
	// Get all the admins
	$admins = Admin::find_all();
	// Count all the admins
	$numOfAdmins = Admin::count_all();
	
} else {
	// Spurios attempt to get into an admin's account
	$session->message("Improper page request.");
	redirect_to("/index.php");
}
/*
// Initialize the last tab index
if (isset($_SESSION['lastTabIndex'])) {
	$lastTabIndex = json_encode($_SESSION['lastTabIndex']);
	echo "<script> var lastTabIndex = ".$lastTabIndex."; </script>";
} else {
	$lastTabIndex = json_encode(0);
	echo "<script> var lastTabIndex = ".$lastTabIndex."; </script>";
}
*/
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

<div id="container">
  <!-- Begining of Main Section -->
  <div id="mainAdminPage">
		<?php 
			if (isset($message)) { 
				echo $message; 
			}
		?><br>
    <h2>Admin Page</h2>
    
		<p><a class="btnStyle1" href="/Public/admin/siteLogFile.php">Access Log File</a></p>
    <!-- Begining of tabbed panel -->
    <div id="TabbedPanels1" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Users</li>
        <li class="TabbedPanelsTab" tabindex="0">Customers</li>
				<li class="TabbedPanelsTab" tabindex="0">Admins</li>
				<li class="TabbedPanelsTab" tabindex="0">Check Username</li>
				<li class="TabbedPanelsTab" tabindex="0">Tools</li>
				<li class="TabbedPanelsTab" tabindex="0">Feedbacks</li>
      </ul>
      <div class="TabbedPanelsContentGroup">
      	<!-- Begining of Registered Users Tab -->
        <div class="TabbedPanelsContent">
					<h3 style="text-align: center;">Registered Users</h3>
					<p>Total number of users: <?php echo $numOfUsers; ?> </p>
        	<div class="tableDiv">
						<table style="color: gray; font-size: small;">
							<tr>
								<th style="text-align: left; width: 50px;">Id</th>
								<th style="text-align: left; width: 100px;">Firstname</th>
								<th style="text-align: left; width: 100px;">Lastname</th>
								<th style="text-align: left; width: 100px;">Username</th>
								<th style="text-align: left; width: 100px;">Phone number</th>
								<th style="text-align: left; width: 200px;">Email</th>
								<th style="text-align: left; width: 200px;">Date created</th>
								<th colspan="2" style="text-align: left;">Actions</th>
							</tr>
							
							<?php foreach($users as $user) { ?>
							<tr>
								<td><?php echo $user->id; ?></td>
								<td><?php echo $user->first_name; ?></td>
								<td><?php echo $user->last_name; ?></td>
								<td><?php echo $user->username; ?></td>
								<td><?php echo $user->phone_number; ?></td>
								<td><?php echo $user->user_email; ?></td>
								<td><?php echo $user->date_created; ?></td>
								<td><a href="edit_user.php?id=<?php // echo urlencode($admin["id"]); ?>">Edit</a></td>
								<td><a href="delete_user.php?id=<?php // echo urlencode($admin["id"]); ?>" onclick="return confirm('Are you sure?');">Delete</a></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
				<!-- End of Registered Users Tab -->

				<!-- Begining of Registered Customers Tab -->
        <div class="TabbedPanelsContent">
					<h3 style="text-align: center;">Registered Customers</h3>
					<p>Total number of customers: <?php echo $numOfCustomers; ?> </p>
        	<div class="tableDiv">
						<table style="color: gray; font-size: small;">
							<tr>
								<th style="text-align: left; width: 50px;">Id</th>
								<th style="text-align: left; width: 100px;">Firstname</th>
								<th style="text-align: left; width: 100px;">Lastname</th>
								<th style="text-align: left; width: 100px;">Username</th>
								<th style="text-align: left; width: 100px;">Phone number</th>
								<th style="text-align: left; width: 200px;">Email</th>
								<th style="text-align: left; width: 200px;">Business title</th>
								<th style="text-align: left; width: 200px;">Date created</th>
								<th colspan="2" style="text-align: left;">Actions</th>
							</tr>
							
							<?php foreach($customers as $customer) { ?>
							<tr>
								<td><?php echo $customer->id; ?></td>
								<td><?php echo $customer->first_name; ?></td>
								<td><?php echo $customer->last_name; ?></td>
								<td><?php echo $customer->username; ?></td>
								<td><?php echo $customer->phone_number; ?></td>
								<td><?php echo $customer->customer_email; ?></td>
								<td><?php echo $customer->business_title; ?></td>
								<td><?php echo $customer->date_created; ?></td>
								<td><a href="edit_customer.php?id=<?php // echo urlencode($admin["id"]); ?>">Edit</a></td>
								<td><a href="delete_customer.php?id=<?php // echo urlencode($admin["id"]); ?>" onclick="return confirm('Are you sure?');">Delete</a></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
				<!-- End of Registered Customers Tab -->

				<!-- Begining of Registered Admins Tab -->
				<div class="TabbedPanelsContent">	
					<h3 style="text-align: center;">Authorized Admins</h3>
					<p>Total number of admins: <?php echo $numOfAdmins; ?> </p>
					<div class="tableDiv" >
						<table style="color: gray; font-size: small;">
							<tr>
								<th style="text-align: left; width: 50px;">Id</th>
								<th style="text-align: left; width: 100px;">Firstname</th>
								<th style="text-align: left; width: 100px;">Lastname</th>
								<th style="text-align: left; width: 100px;">Username</th>
								<th style="text-align: left; width: 100px;">Phone number</th>
								<th style="text-align: left; width: 200px;">Email</th>
								<th style="text-align: left; width: 200px;">Date created</th>
								<th colspan="2" style="text-align: left;">Actions</th>
							</tr>
							
							<?php foreach($admins as $admin) { ?>
							<tr>
								<td><?php echo $admin->id; ?></td>
								<td><?php echo $admin->first_name; ?></td>
								<td><?php echo $admin->last_name; ?></td>
								<td><?php echo $admin->username; ?></td>
								<td><?php echo $admin->phone_number; ?></td>
								<td><?php echo $admin->admin_email; ?></td>
								<td><?php echo $admin->date_created; ?></td>
								<td><a href="edit_admin.php?id=<?php // echo urlencode($admin["id"]); ?>">Edit</a></td>
								<td><a href="delete_admin.php?id=<?php // echo urlencode($admin["id"]); ?>" onclick="return confirm('Are you sure?');">Delete</a></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
				<!-- End of Registered Admin Tab -->

				<!-- Begining of Check Username Tab -->
				<div class="TabbedPanelsContent">
					<div id="userGroupTools" class="groupTools">
						<div id="findUserDiv" class="divBox">
							<?php echo $security->csrf_token_tag(); ?>
							<div class="divHeading">Find User</div>
							<div class="divContent">
								<input id="firstName" class="cusTextField" type="text" name="firstName" placeholder="Enter First Name" />
								<input id="lastName" class="cusTextField" type="text" name="lastName" placeholder="Enter Last Name" />
				   	  	<button id="searchName" class="btnStyle1" >Search</button>
							</div>
						</div>

						<div class="foundUsers">
							<div class="divHeading">Found Users</div>
							<div id="usersDivContent" class="divContent">
								<!-- Result of searched user to appear here -->
							</div>
							<button id="closeFoundUsers" class="btnStyle1" >Close</button>				
						</div>

						<div id="userPassKeyDiv" class="updateDivBox">
							<?php echo $security->csrf_token_tag(); ?>
							<div class="divHeading">Update User Password</div>
							<div class="divContent">
								<input id="usernameBox" class="cusTextField" type="text" name="usernameBox" placeholder="Enter Username" />
								<input id="passwordBox" class="cusTextField" type="password" name="passwordBox" placeholder="Enter Password" />
								<input id="confirmPasswordBox" class="cusTextField" type="password" name="confirmPasswordBox" placeholder="Enter Confrim Password" />
				   	  	<button id="updatePwd" class="btnStyle1" >Update</button>
							</div>
						</div>
					</div>

					<div id="customerGroupTools" class="groupTools">
						<div id="findCusDiv" class="divBox">
							<?php echo $security->csrf_token_tag(); ?>
							<div class="divHeading">Find Customer</div>
							<div class="divContent">
								<input id="cusFirstName" class="cusTextField" type="text" name="firstName" placeholder="Enter First Name" />
								<input id="cusLastName" class="cusTextField" type="text" name="lastName" placeholder="Enter Last Name" />
				   	  	<button id="searchCusName" class="btnStyle1" >Search</button>
							</div>
						</div>

						<div class="foundCustomers">
							<div class="divHeading">Found Users</div>
							<div id="customersDivContent" class="divContent">
								<!-- Result of searched user to appear here -->
							</div>
							<button id="closeFoundCustomers" class="btnStyle1" >Close</button>		
						</div>

						<div id="customerPassKeyDiv" class="updateDivBox">
							<?php echo $security->csrf_token_tag(); ?>
							<div class="divHeading">Update Customer Password</div>
							<div class="divContent">
								<input id="usernameBox2" class="cusTextField" type="text" name="usernameBox2" placeholder="Enter Username" />
								<input id="passwordBox2" class="cusTextField" type="password" name="passwordBox2" placeholder="Enter Password" />
								<input id="confirmPasswordBox2" class="cusTextField" type="password" name="confirmPasswordBox2" placeholder="Enter Confrim Password" />
				   	  	<button id="updatePwd2" class="btnStyle1" >Update</button>
							</div>
						</div>
					</div>							
				</div>
				<!-- End of Check Username Tab -->

				<!-- Begining of Tools Tab -->
        <div class="TabbedPanelsContent">
        	<div id="checkCreditBalDiv" class="checkCreditBox">
						<div class="divHeading">Check Credit Balance</div>
						<div class="divContent">
							<p id="creditBal" class="cusTextField" style="width: auto; padding-top: 5px; text-align: center; font-size: medium;" name="creditBal" placeholder="Credit Balance" ></p>

			   	  	<button id="checkCreditBal" class="btnStyle1" >Check Credit</button>
						</div>
					</div>

					<div id="genPwdDiv" class="checkCreditBox">
						<div class="divHeading">Generate Password</div>
						<div class="divContent">
							<p id="genPassword" class="cusTextField" style="width: auto; padding-top: 5px; text-align: center; font-size: medium;" name="genPassword" placeholder="New Password" ></p>

			   	  	<button id="genPwdBtn" class="btnStyle1" >Generate Password</button>
						</div>
					</div>
        </div>
        <!-- End of Tools Tab -->

        <!-- Begining of Feedbacks Tab -->
				<div class="TabbedPanelsContent">
					<div id="feedbackContainer">
						<div id="feedbackOption">
							<label for='subject'>Feedback Subject</label>
							<select name='subject' id='subject'>
								<option id="select"> Select </option>
								<option id="complain"> Complain </option>
								<option id="suggestion"> Suggestion </option>
								<option id="request"> Request </option>
								<option id="other"> Other </option>
							</select>
						</div>

						<div id="feedbackHolder">	
						</div>
					</div>
				</div>
				<!-- End of Feedbacks Tab -->
      </div>
    </div>
    <!-- End of tabbed pannel -->    
  </div>

  <!-- Display the footer section -->
  <?php include_layout_template('navigation_footer.php'); ?>
</div>

<!-- Loader -->
<div class="loader" style="background-color: rgba(0, 0, 0, 0.5);">
	<img src="../images/utilityImages/ajax-loader1.gif" alt="Loading..." />
</div>

<!-- Modal for the display of error messages -->
<div class="messageModal">
	<div class="messageContainer">
		<div id="messageHead">Error</div>
		<div id="messageContent">Message to appear here.</div>
		<button id="closeBtn" class="btnStyle1">Close</button>
	</div>
</div>

<?php
	// Initialize the footer of the webpage
	echo getUniquePageFooter($scriptName);
?>
