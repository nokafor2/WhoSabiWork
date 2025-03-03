<?php
require_once("../includes/initialize.php");
$message = "";
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
  <div id="mainTechEditPage">
  	<?php echo displayMessages(); ?>
  	<!-- All messages have been combined into displayMessages() -->
    <h2 class="pageIntro">Create your business account</h2>
    <form action="" method="post" enctype="multipart/form-data" name="profileEdit" id="profileEdit">
		  <?php 
			echo csrf_token_tag(); 
			/*
			echo "<br/> The generated CSRF token is: ".$_SESSION['csrf_token']." <br/>";
			echo "<br/> The generated CSRF time is: ".$_SESSION['csrf_token_time']."<br/>";
			*/
		  ?>
      <fieldset class="technician_edit">
        <legend>Business Information</legend>

        <!-- First name -->
        <input name="first_name" type="text" id="first_name" size="60" maxlength="60" placeholder="First name" value="" onblur="validateInput(this);" />
        <div id="first_name_message" name="first_name_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
	    	
	    	<!-- Last name -->
	    	<input name="last_name" type="text" id="last_name" size="60" maxlength="60" placeholder="Last name" value="" onblur="validateInput(this);" />
	    	<div id="last_name_message" name="last_name_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

	    	<!-- Gender -->
      	<p style="padding-left: 10px">
      		<label class="genderLabel">Gender:</label>
			    <label>
	          <input name="gender" type="radio" value="male" id="male" onclick="validateInput(this);" />
			    	Male
	        </label>
	        <label>
	          <input name="gender" type="radio" value="female" id="female" onclick="validateInput(this);" />
			    	Female
	        </label>
	        <br />
      	</p>
      	<div id="gender_message" name="gender_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

      	<!-- Username -->
      	<input name="username" type="text" id="username" size="60" maxlength="60" autocomplete="off" autocorrect="off" autocapitalize="none" placeholder="Username" value="" onblur="usernameBussAccCheck();" />
				<div id="username_message" name="username_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

				<!-- Password -->
      	<input name="password" type="password" id="password" size="60" maxlength="60" placeholder="Password" autocomplete="off" autocorrect="off" autocapitalize="none" onblur="validateInput(this);" />
      	<div id="password_message" name="password_user_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

      	<!-- Confirm password -->
				<input name="confirm_password" type="password" id="confirm_password" size="60" maxlength="60" placeholder="Confirm password" autocomplete="off" autocorrect="off" autocapitalize="none" />
				<div id="confirm_password_message" name="confirm_password_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

      	<!-- Business name -->
      	<input name="business_name" type="text" id="business_name" size="60" maxlength="60" placeholder="Business name" value="" onblur="validateInput(this);" />
      	<div id="business_name_message" name="business_name_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

      	<!-- Email address -->
      	<input name="business_email" type="email" id="business_email" size="60" maxlength="60" autocomplete="off" autocorrect="off" autocapitalize="none" placeholder="Business email" value="" onblur="validateInput(this);"/>
      	<div id="business_email_message" name="business_email_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

      	<!-- Phone number -->
      	<p class="smallText2">Enter phone number in this format 08012345690</p>
      	<input name="business_phone_number" type="tel" id="business_phone_number" size="60" maxlength="60" placeholder="Phone number" value="" onblur="validateInput(this);"/>
      	<div id="business_phone_number_message" name="business_phone_number_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
      	
      	<!-- State -->
      	<p class='selectStyle'>Select State
					<select name='state' id='state' class='marginTop' onchange='getTowns(this.id, "town")'>
					<?php echo displayStateOptions(); ?>
					</select>
      	</p>
      	<div id="state_message" name="state_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

      	<!-- Town -->
      	<p class='selectStyle'>Select Town
					<select name='town' id='town' onchange='showTownTextArea(this.id);'>
						<option value=''>Select</option>
					</select>
      	</p>
      	<div id="town_message" name="town_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
      	
      	<!-- Other town -->
      	<p id='otherTown'>
        	<input name="other_town" type="text" id="other_town" size="60" maxlength="20" placeholder="Other town" value="" onblur="validateInput(this);" />
      	</p>
      	<div id="other_town_message" name="other_town_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>

      	<!-- Address line 1 -->
      	<p class="smallText2">* comma (,) already added at the end of line</p>
      	<input name="address_line_1" type="text" id="address_line_1" size="60" maxlength="100" placeholder="Address line 1" value="" onblur="validateInput(this);" />
      	<div id="address_line_1_message" name="address_line_1_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
      	
      	<!-- Address line 2 -->
      	<p class="smallText2">* comma (,) already added at the end of line</p>
      	<input name="address_line_2" type="text" id="address_line_2" size="60" maxlength="100" placeholder="Address line 2" value="" onblur="validateInput(this);" />
      	<div id="address_line_2_message" name="address_line_2_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
      	
      	<!-- Address line 3 -->
      	<p class="smallText2">* comma (,) already added at the end of line</p>
      	<input name="address_line_3" type="text" id="address_line_3" size="60" maxlength="100" placeholder="Address line 3" value="" onblur="validateInput(this);" />
      	<div id="address_line_3_message" name="address_line_3_message" style="color:red; display:none; margin:0px; margin-left:10px;"></div>
      	      	
      	<br/>
      </fieldset> <!-- End of Main Section -->
	  	<br/>
      <fieldset class="technician_edit">
      	<legend>Business Description</legend>

				<!-- Business categories -->	      	
	   		<label class="bussDescrpLabel">Business categories:</label>
        <br/>
        <label>
          <input name="business_category" type="radio" value="mobile_market" id="seller_btn" onclick="validateInput(this);" />
		    	Mobile Market
        </label>
		   	<label>
          <input name="business_category" type="radio" value="artisan" id="artisan_btn" onclick="validateInput(this);" />
		    	Artisan
        </label>
        <label>
          <input name="business_category" type="radio" value="technician" id="technician_btn" onclick="validateInput(this);" />
		    	Mechanic
        </label>
	      <label>
          <input name="business_category" type="radio" value="spare_part_seller" id="spare_part_btn" onclick="validateInput(this);" />
	        Spare part seller
        </label>
        <br />
        <div id="business_category_message" name="business_category_message" style="color:red; display:none; margin:0px; margin-left:10px; clear:both; "></div>

				<!-- Mobile market/Sellers -->
			  <div id="sellerDiv" style="display:none; clear:both">
				  <p class="bussDescrpLabel" >Mobile Market:</p>
			  </div>
			  <div id="sellers_message" name="sellers_message" style="color:red; display:none; margin:0px; margin-left:10px; clear:both; "></div>

			  <!-- Artisans services -->
			  <div id="artisanDiv" style="display:none; clear:both">
				  <p class="bussDescrpLabel" >Artisans:</p>
			  </div>
			  <div id="artisans_message" name="artisans_message" style="color:red; display:none; margin:0px; margin-left:10px; clear:both; "></div>
				
				<!-- Technicians services -->
			  <div id="technicalServiceDiv" style="display:none; clear:both">
				  <p class="bussDescrpLabel">Technical services:</p>
			  </div>
			  <div id="technical_services_message" name="technical_services_message" style="color:red; display:none; margin:0px; margin-left:10px; clear:both; "></div>
			  
			  <!-- Spare parts -->
			  <div id="sparePartDiv" style="display:none; clear:both">
				<p class="bussDescrpLabel">Spare part categories:</p>
			  </div>
			  <div id="spare_parts_message" name="spare_parts_message" style="color:red; display:none; margin:0px; margin-left:10px; clear:both; "></div>
				
			  <!-- Vehicle categories: -->
			  <div id="vehCategory" style="display:none; clear:both" >
					<p class="bussDescrpLabel">Vehicle categories:</p>
				  <label>
					<input type="radio" name="vehicle_category" value="cars" id="cars_btn" onclick="validateInput(this);" />
					Cars
				  </label>
				  
				  <label>
				  <input type="radio" name="vehicle_category" value="buses" id="buses_btn" onclick="validateInput(this);" />
				  Buses</label>
					
				  <label>
				  <input type="radio" name="vehicle_category" value="trucks" id="trucks_btn" onclick="validateInput(this);" />
				  Trucks</label>
			  </div>
			  <div id="vehicle_category_message" name="vehicle_category_message" style="color:red; display:none; margin:0px; margin-left:10px; clear:both; "></div>
			  
			  <!-- Car brands -->
			  <div id="carBrandsDiv" style="display:none; clear:both">
				  <p class="bussDescrpLabel" >Car specialization:</p>
			  </div>
			  <div id="car_brands_message" name="car_brands_message" style="color:red; display:none; margin:0px; margin-left:10px; clear:both; "></div>
				  
			  <!-- Bus brands -->
			  <div id="busBrandsDiv" style="display:none; clear:both">
				  <p class="bussDescrpLabel" >Bus specialization:</p>
			  </div>
			  <div id="bus_brands_message" name="bus_brands_message" style="color:red; display:none; margin:0px; margin-left:10px; clear:both; "></div>
			  
			  <!-- Truck brands -->
			  <div id="truckBrandsDiv" style="display:none; clear:both">
				  <p class="bussDescrpLabel" >Truck specialization:</p>
			  </div>
			  <div id="truck_brands_message" name="truck_brands_message" style="color:red; display:none; margin:0px; margin-left:10px; clear:both; "></div>

			  <p id="smallText1">By Registering, you agree that you've read and accepted our User <a href="/Public/termsOfUse.php" >Terms of Use</a>, you're at least 18 years old, you consent to our <a href="/Public/privacyPolicy.php" >Privacy Policy</a> and you have accepted to receive marketing communications from us.</p>
			  
		    <p id="submitBtnPara">
		      <input type="submit" name="Submit" id="submit" value="Register"/>
		    </p>	  
    	</fieldset>
    </form>
    <p>&nbsp;</p>
  
  <!-- Display the footer section -->
  <?php 
  include_layout_template('navigation_footer.php'); ?>
  </div>
</div>

<!-- Loader -->
<div class="loader" style="background-color: rgba(0, 0, 0, 0.5);">
	<img src="images/utilityImages/ajax-loader1.gif" alt="Loading..." />
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
<?php // if(isset($database)) { $database->close_connection(); } ?>