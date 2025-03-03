<?php
// This function will return the generic Javascript Footer
function genericJavascriptFooter() {
	$output = '
		<script type="text/javascript">
			var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"/Public/stylesheets/SpryAssets/SpryMenuBarDownHover.gif", imgRight:"/Public/stylesheets/SpryAssets/SpryMenuBarRightHover.gif"});
		</script>
		<script type="text/javascript" src="/Public/javascripts/genericJSs.js"></script>
	';

	return $output;
}

// Funtion to get the unique Javascript of a webpage
function getUniqueJavascriptFooter($scriptName) {
	global $counterForJS; 
	global $busCategory;
	$output = '';

	if ($scriptName === 'homePage.php') {
		$output .= '<script type="text/javascript" src="/Public/javascripts/homePageJSs.js"></script>';
	} elseif ($scriptName === 'selectProfileType.php') {
		$output .= '';
	} elseif ($scriptName === 'loginPage.php') {
		$output .= '
			<script type="text/javascript">				
				var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
			</script>	
		';
	} elseif ($scriptName === 'createUserAccount.php') {
		$output .= '
			<script type="text/javascript">
				var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
			</script>
			<script type="text/javascript" src="/Public/javascripts/createUserAccountJSs.js"></script>
			<script type="text/javascript" src="/Public/javascripts/usernameCheck.js"></script>
			<script type="text/javascript" src="/Public/javascripts/passwordMatchCheck.js"></script>
		';
	} elseif ($scriptName === 'createBusinessAccount.php') {
		$output .= '
			<script type="text/javascript" src="/Public/javascripts/usernameCheck.js"></script>
			<script type="text/javascript" src="/Public/javascripts/passwordMatchCheck.js"></script>
			<script type="text/javascript" src="/Public/javascripts/createBusAccJavascripts.js"></script>
		';
	} elseif ($scriptName === 'livePhotosFeed.php') {
		$output .= '<script type="text/javascript" src="./javascripts/livePhotosFeedJSs.js"></script>';
	} elseif ($scriptName === 'mobileMarketPage.php') {
		$output .= '<script type="text/javascript" src="./javascripts/mobileMarketPage.js"></script>';
	} elseif ($scriptName === 'artisanPage.php') {
		$output .= '<script type="text/javascript" src="./javascripts/artisansPageJSs.js"></script>';
	} elseif ($scriptName === 'servicePage.php') {
		$output .= '<script type="text/javascript" src="./javascripts/displayTechnicianAds.js"></script>';
	} elseif ($scriptName === 'sparePartPage.php') {
		$output .= '<script type="text/javascript" src="./javascripts/displayTechnicianAds.js"></script>';
	} elseif ($scriptName === 'contactUs.php') {
		$output .= '';
	} elseif ($scriptName === 'privacyPolicy.php') {
		$output .= '';
	} elseif ($scriptName === 'termsOfUse.php') {
		$output .= '';
	} elseif ($scriptName === 'forgotPassword.php') {
		$output .= '
			<script type="text/javascript">
				var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
			</script>
		';
	} elseif ($scriptName === 'resetPassword.php') {
		$output .= '
			<script type="text/javascript">
				var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
			</script>
		';
	} elseif ($scriptName === 'userEditPage.php') {
		$output .= '
			<script type="text/javascript">
				var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen:false});
				var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2", {contentIsOpen:false});
				var CollapsiblePanel3 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel3", {contentIsOpen:false});
				var CollapsiblePanel4 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel4", {contentIsOpen:false});
				var CollapsiblePanel5 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel5", {contentIsOpen:false});
				var CollapsiblePanel6 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel6", {contentIsOpen:false});
				var CollapsiblePanel7 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel7", {contentIsOpen:false});
				var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
			</script>
			<script type="text/javascript" src="../javascripts/appointmentDecision.js"></script>
			<script type="text/javascript" src="../javascripts/usernameCheck.js"></script>				
			<script type="text/javascript" src="../javascripts/cancelAppointment.js"></script>
			<script type="text/javascript" src="../javascripts/userEditPageJSs.js"></script>
			<script type="text/javascript" src="../javascripts/cropImage.js">
			</script>';
	} elseif ($scriptName === 'customerEditPage2.php') {
		$output .= '
			<script type="text/javascript">
				var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen:false});
				var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2", {contentIsOpen:false});
				var CollapsiblePanel3 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel3", {contentIsOpen:false});
				var CollapsiblePanel4 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel4", {contentIsOpen:false});
				var CollapsiblePanel5 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel5", {contentIsOpen:false});
				var CollapsiblePanel6 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel6", {contentIsOpen:false});
				var CollapsiblePanel7 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel7", {contentIsOpen:false});
				var CollapsiblePanel8 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel8", {contentIsOpen:false});
				var CollapsiblePanel9 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel9", {contentIsOpen:false});
				var CollapsiblePanel10 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel10", {contentIsOpen:false});
				var CollapsiblePanel11 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel11", {contentIsOpen:false});
				var CollapsiblePanel12 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel12", {contentIsOpen:false});
				var CollapsiblePanel13 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel13", {contentIsOpen:false});
				var CollapsiblePanel14 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel14", {contentIsOpen:false});
				var CollapsiblePanel15 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel15", {contentIsOpen:false});
		';

		if ($busCategory === "Technician" || $busCategory === "Spare part seller") {
			$output .= 'var CollapsiblePanel16 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel16", {contentIsOpen:false});
			var CollapsiblePanel17 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel17", {contentIsOpen:false});';
		}

		$output .= 'var CollapsiblePanel18 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel18", {contentIsOpen:false});
			
			var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
			</script>';

		for ($i=0; $i < $counterForJS; $i++) {
			$output .= '<script type="text/javascript" src="/Public/javascripts/editDayTime.js"></script>
				<script type="text/javascript" src="/Public/javascripts/customerAvailabilityUpdate.js"></script>';
		}

		$output .= '
			<script type="text/javascript" src="/Public/javascripts/appointmentDecision.js"></script>
			<script type="text/javascript" src="/Public/javascripts/cancelAppointment.js"></script>
			<script type="text/javascript" src="/Public/javascripts/usernameCheck.js"></script>
			<script type="text/javascript" src="/Public/javascripts/customerEditPage2JSScripts.js"></script>
			<script type="text/javascript" src="/Public/javascripts/cropImage.js"></script>
		';
	} elseif ($scriptName === 'customerHomePage.php') {
		$output .= '<script type="text/javascript">
		var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1"); </script>';
	} elseif ($scriptName === 'loginAdmin.php') {
		$output .= '
			<script type="text/javascript">
				var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
				var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
			</script>
			<script type="text/javascript" src="/Public/javascripts/loginAdminJSs.js"></script>
			<script type="text/javascript" src="/Public/javascripts/usernameCheck.js"></script>
			<script type="text/javascript" src="/Public/javascripts/passwordMatchCheck.js"></script>
		';
	} elseif ($scriptName === 'adminPage.php') {
		$output .= '
			<script type="text/javascript">
				var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
			</script>
		';
	} elseif ($scriptName === 'siteLogFile.php') {
		$output .= '
			<script type="text/javascript">
				var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
			</script>
		';
	}

	return $output;
}

// Get the compiled Footer for the given page
function getUniquePageFooter($scriptName) {
	$output = '';

	$output .= genericJavascriptFooter().getUniqueJavascriptFooter($scriptName).'
		</body>
	</html>';

	return $output;
}

?>