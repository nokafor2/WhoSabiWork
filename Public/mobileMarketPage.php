<?php require_once("../includes/initialize.php");

$mobileMarketObj = new Seller();

// Check if the request is get
if (request_is_get()) {
	// Check that only allowed parameters is passed into the form
	$get_params = allowed_get_params(['sellerType', 'state', 'town', 'page']);

	// Receive variables from $_GET Global
	$searchedSeller = $get_params['sellerType'];
	$searchedState = $get_params['state'];
	$searchedTown = $get_params['town'];
	$searchedPage = $get_params['page'];
}

function searchByGetRequest() {
	global $searchedSeller, $searchedState, $searchedTown, $searchedPage;
	$args = '"'.$searchedSeller.'", "'.$searchedState.'", "'.$searchedTown.'", "'.$searchedPage.'"';

	$output = "";
	$output .= "
		<script type='text/javascript'>
			$('#sellerType').val('".$searchedSeller."').prop('selected', true);  	
			display_seller_ads(".$args.");
		</script>
	";

	return $output;
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
<div id="container">
  <!-- Begining of Main Section  -->
  <div id="mainServicePage">
    <div class="pageIntroDiv" >
    	<h2 class="pageIntro" id="pageHeading">Welcome! Please select the seller category you need</h2>
    </div>    
    <!-- <div class="selectionPanel pageIntroDiv adContainer1"> -->
		<div class="selectionPanel">
   	  <form id="form1" name="form1" method="post" action="">
   	  	<div id="searchBox" class="searchDiv">
	   	  	<input id="searchInput" class="searchField" type="text" name="searchBox" placeholder="Search an entrepreneur" />
	   	  	<!-- oninput="searchArtisan(this.value);" -->
	   	  	<button id="searchBtn" class="searchButton btnStyle1" ><i class="fas fa-search"></i></button>
   	  	</div>
		    <label for="sellerType">Select a seller type</label>
	      <select name="sellerType" id="sellerType" class="selectStyle" onchange="popSellerStates(this.id, 'state', 'town')" > <!-- popSellerStates(selectMenuSellerId, selMenuStateId)-->
          	    <?php echo $mobileMarketObj->sellersTypeOptions(); ?>
        </select>
		    <br class="breaker" />

		    <label id="stateLabel" for="state" class="hideElement" >Select state</label>
				<select id="state" class="selectStyle hideElement" name="state" onchange="getSellerTowns(this.id, 'sellerType', 'town')" >
					<option value="select">Select</option>
				</select>
				<br class="breaker" />
			
				<label id="townLabel" for="town" class="hideElement">Select town</label>
				<select id="town" class="selectStyle hideElement" name="town" onchange="showSubmitBtn();">
					<option value="select">Select</option>
				</select>
				<br class="breaker" />
		  
        <input type="button" value="Submit" name="submitBtn" id="submitBtn" class="btnStyle1 hideElement" onclick="display_seller_ads(pageNumber=1);"  />
        <br class="breaker" />
      </form>
    </div>
		<!-- <div id="page" style="display:none;">1</div> class="adContainer1" -->
		<div id="adContainer1"></div>
		<!-- Div for pagination -->
		<div id="pagination"></div>
		<!-- Div for error message and progress message -->
    <div id='messageDiv'></div>
  </div> <!-- End of Main Section  -->
  
    <!-- Scroll to top widget -->
	<div class="to-top-div" style="display: block;">
		<a class="to-top" href="#mainServicePage"> ^ </a>
	</div>
  
  <!-- Display the footer section -->
  <?php include_layout_template('navigation_footer.php'); ?>
  
  <!-- This is used to search for sellers when a get request is performed. It will call a javascript function -->
  <?php 
  	if (!empty($get_params['sellerType'])) {
   		echo searchByGetRequest();
   	}
  ?>  
  
</div>
<div class="loader">
	<img src="images/utilityImages/ajax-loader1.gif" alt="Loading..." />
</div>

<?php
	// Initialize the footer of the webpage
	echo getUniquePageFooter($scriptName);
?>
