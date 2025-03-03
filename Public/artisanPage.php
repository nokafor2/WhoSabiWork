<?php require_once("../includes/initialize.php");

$artisanObj = new Artisan();

// Check if the request is get
if (request_is_get()) {
	// Check that only allowed parameters is passed into the form
	$get_params = allowed_get_params(['artisanType', 'state', 'town', 'page']);

	// Receive variables from $_GET Global
	$searchedArtisan = $get_params['artisanType'];
	$searchedState = $get_params['state'];
	$searchedTown = $get_params['town'];
	$searchedPage = $get_params['page'];
}

function searchByGetRequest() {
	global $searchedArtisan, $searchedState, $searchedTown, $searchedPage;
	$args = '"'.$searchedArtisan.'", "'.$searchedState.'", "'.$searchedTown.'", "'.$searchedPage.'"';

	$output = "";
	$output .= "
		<script type='text/javascript'>			
			// This can as well work: $('#id').val('value')
			$('#artisanType').val('".$searchedArtisan."').prop('selected', true);
			/*
			popArtisanStates('artisanType', 'state');			
			$('#state').val('".$searchedState."');
			// $('#state option[value=".$searchedState."]').attr('selected', true);
			$('#stateLabel').fadeIn();
			$('#state').fadeIn();			
			
			$('#townLabel').fadeIn();
			$('#town').fadeIn();
			$('#town').val('".$searchedTown."').prop('selected', true);
			$('#submitBtn').fadeIn();
			*/
			display_artisan_ads(".$args.");
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
    	<h2 class="pageIntro" id="pageHeading">Welcome! Please select the artisan category you need</h2>
    </div>    
    <!-- <div class="selectionPanel pageIntroDiv adContainer1"> -->
		<div class="selectionPanel">
   	  <form id="form1" name="form1" method="post" action="">
   	  	<div id="searchBox" class="searchDiv" >
	   	  	<input id="searchInput" class="searchField" type="text" name="searchBox" placeholder="Search an artisan" />
	   	  	<!-- oninput="searchArtisan(this.value);" -->
	   	  	<button id="searchBtn" class="searchButton btnStyle1" ><i class="fas fa-search"></i></button>
   	  	</div>
   	  	<!-- 
   	  	<div id="searchResult" style="width: 50%; display:block; margin-left: auto; margin-right: auto; border: solid thin #CCC; max-height: 300px; overflow-y:scroll; transition: visibility 0.1s, opacity 0.1s;">
   	  		<ul id="searchList" style="list-style: none; padding-left: 15px;">
   	  			<li class="searchValue" style="padding: 5px 0; border-left: 1px solid #CCC; padding-left: 15px; cursor: pointer;">Result 1</li>
   	  			<li style="padding: 5px 0; border-left: 1px solid #CCC; padding-left: 15px; cursor: pointer;">Result 2</li>
   	  		</ul>
   	  	</div>
   	  	-->
		    <label for="artisanType">Select an artisan type</label >
	      <select name="artisanType" id="artisanType" class="selectStyle" onchange="popArtisanStates(this.id, 'state')" >
	        <!-- popArtisanStates(selectMenuArtisanId, selMenuStateId)-->
    	    <?php echo $artisanObj->artisansTypeOptions(); ?>
        </select>
		    <br class="breaker" />

		    <label id="stateLabel" for="state" class="hideElement">Select state</label>
				<select id="state" class="selectStyle hideElement" name="state" onchange="getArtisanTowns(this.id, 'artisanType', 'town')" >
					<option value="select">Select</option>
				</select>
				<br class="breaker" />
			
				<label id="townLabel" for="town" class="hideElement">Select town</label>
				<select id="town" class="selectStyle hideElement" name="town" onchange="showSubmitBtn()">
					<option value="select">Select</option>
				</select>
				<br class="breaker" />
		  
        <input type="button" value="Submit" name="submitBtn" id="submitBtn" class="btnStyle1 hideElement" onclick="display_artisan_ads(pageNumber=1);" />
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
  
  <!-- This is used to search for artisans when a get request is performed. It will call a javascript function -->
  <?php 
  	if (!empty($get_params['artisanType'])) {
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