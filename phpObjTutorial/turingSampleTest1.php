<?php
function charCheck($arrayVal) {
	$output = array();
	foreach ($arrayVal as $key => $value) {
		$lastKey = count($output) - 1;
		$last2ndKey = $lastKey - 1;

		if (is_numeric($value)) {
			$output[] = $value;
		} elseif ($value === '+') {
			$output[] = $output[$lastKey] + $output[$last2ndKey];
		} elseif ($value === 'D') {
			$output[] = 2 * $output[$lastKey];
		} elseif ($value === 'C') {
			$lastValue = array_pop($output);
		}
	}
	print_r($output);
	echo "<br/><br/>";
	return array_sum($output);
}

echo charCheck(['5','2','C','D','+']);
echo "<br/><br/>";
echo charCheck(['5','2','C']);
echo "<br/><br/>";
echo charCheck(['5','2']);
echo "<br/><br/>";
echo charCheck(['5','-2','4','C','D','9','+','+']);
echo "<br/><br/>";echo "<br/><br/>";

// function checkString($strVal) {
// 	$strArray = str_split($strVal);
// 	$preVal = "";
// 	$nextVal = "";
// 	$output = "";
// 	foreach ($strArray as $key => $value) {
// 		$moduloVal = $key % 2;
// 		echo "<br/> modulo calcuation is ".$moduloVal."<br/>";
// 		if ($strArray[$key] === '(' || $strArray[$key] === '{' || $strArray[$key] === '[' ) {

// 			if ($value === '(' && ($moduloVal == 0)) {
// 				$output .= ' invalid';
// 			} else {
// 				$output .= ' valid';
// 			}
// 		} else {
// 			if ($value === ')' && ($moduloVal == 1)) {
// 				// if ($value === ')' && $strArray[$key-1] === "(") {
// 				// 	$output = 'valid';
// 				// } else {
// 				// 	$output = 'invalid';
// 				// }
// 				$output .= ' valid';
// 			} else {
// 				$output .= ' invalid';
// 			}
// 		}

// 		// if ($value === '(' && $preVal === "") {
// 		// 	$preVal = "(";
// 		// 	$nextVal = ")";
// 		// } elseif ($preVal === "(" && $value === ')') {
// 		// 	$preVal = ')';
// 		// 	$output = 'valid';
// 		// } else {
// 		// 	$output = 'invalid';
// 		// }

// 		// if ($value === '(') {
// 		// 	if ($value === '(' && $strArray[$key-1] === "") {
// 		// 		$preVal = ")";
// 		// 	} elseif ($preVal === "(" && $value === ')') {
// 		// 		$output = 'valid';
// 		// 	} else {
// 		// 		$output = 'invalid';
// 		// 	}
// 		// }			

// 		if ($value === '{') {

// 		} 

// 		if ($value === '[') {

// 		}		
// 	}

// 	return $output;
// }

// echo checkString("()())(");
// echo "<br/><br/>";


function checkString($strVal) {
	$strArray = str_split($strVal);
	$preVal = "";
	$nextVal = "";
	$output = "";
	foreach ($strArray as $key => $value) {
		// Calculate modulo
		$newKey = $key + 1;
		$moduloVal = $newKey % 2;

		// Check first character
		// if ($strArray[0] !== '(' || $strArray[0] !== '{' || $strArray[0] !== '[') {
		// 	// $output .= ' first';
		// 	$output .= 'invalid first';
		// 	break;
		// } else {
						
		// }
		// if ($key > 0) {
		// 	$prevKey = $key - 1;
		// 	if ($strArray[$prevKey] === '(' || $strArray[$prevKey] === '{' || $strArray[$prevKey] === '[' && $value === ')' || $value === '}' || $value === ']' && $moduloVal === 0) {
		// 		$output .= ' valid set';	
		// 	} elseif ($moduloVal === 1 && $value === ')' || $value === '}' || $value === ']') {
		// 		$output .= ' invalid';
		// 		break;
		// 	} elseif ($moduloVal === 1 && ($key === count($strArray) - 1) && $value === '(' || $value === '{' || $value === '[') {
		// 		$output .= ' invalid';
		// 		break;
		// 	}
		// }
		

		// Check first character
		if ($strArray[0] !== '{') {
			// $output .= ' first';
			$output .= 'invalid';
			break;
		} else {
			if ($key > 0) {
				$prevKey = $key - 1;
				if ($strArray[$prevKey] === '{' && $value === '}' && $moduloVal === 0) {
					$output .= ' valid set';	
				} elseif ($moduloVal === 1 && $value === '}') {
					$output .= ' invalid';
					break;
				} elseif ($moduloVal === 1 && ($key === count($strArray) - 1) && $value === '{') {
					$output .= ' invalid';
					break;
				}
			}			
		} 

		// Check first character
		if ($strArray[0] !== '[') {
			// $output .= ' first';
			$output .= 'invalid';
			break;
		} else {
			if ($key > 0) {
				$prevKey = $key - 1;
				if ($strArray[$prevKey] === '[' && $value === ']' && $moduloVal === 0) {
					$output .= ' valid set';	
				} elseif ($moduloVal === 1 && $value === ']') {
					$output .= ' invalid';
					break;
				} elseif ($moduloVal === 1 && ($key === count($strArray) - 1) && $value === '[') {
					$output .= ' invalid';
					break;
				}
			}			
		}		
	}

	return $output;
}

echo checkString("{}[]");
echo "<br/><br/>";