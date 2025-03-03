<?php
function arrayCheck($arrVar) {
	$arrStore = array();
	$strArray = str_split($arrVar);

	// check if array contains characters
	foreach($strArray as $key => $value) {
		// !is_numeric($value)
		if (preg_match("/[a-zA-Z]/", $value)) {
			if (!array_key_exists($value, $arrStore)) {
				$arrStore["{$value}"] = 1;
			} else {
				$arrStore["{$value}"] = $arrStore[$value] + 1;
			}
		}
	}

	$maxVar = max($arrStore);
	// Get key of max value
	foreach ($arrStore as $key => $value) {
		if ($value === $maxVar) {
			return "Max character is: ".$key;
		}
	}
}

print_r(arrayCheck('12cdnd eee1233432mmsdnkdkla&&&&&&&&&'));


function specialInteger($strVar) {
	$arrStore = array();
	// convert string to array
	$strArray = str_split($strVar);

	// Check for occurence
	foreach ($strArray as $key => $value) {
		// code...
		if (!array_key_exists($value, $arrStore)) {
			$arrStore["{$value}"] = 1;
		} else {
			$arrStore["{$value}"] = $arrStore[$value] + 1;
		}
	}

	// Get the max value and elimnate values that are not equal to max
	$maxVar = max($arrStore);
	foreach ($arrStore as $key => $value) {
		// eliminate values not equal to max
		if ($value !== $maxVar) {
			unset($arrStore[$key]);
		}
	}

	// Get the number of occurences
	$specialInt = 0;
	foreach ($arrStore as $value) {
		if ($value === $maxVar) {
			$specialInt += 1;
		}
	}

	return $specialInt;
}

echo "<br/><br/>--------------------------------------<br/>";
// print_r(specialInteger('04140'));
echo "The special integer is: ".specialInteger('04140');
?>