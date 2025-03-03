<?php
require_once("initialize.php");
// require_once("included_functions.php");
// require_once("validation_functions.php");

// $errors = array();
$message = "";

if (isset($_POST['submit'])){
	// for was submitted
	$username = trim($_POST["username"]);
	$password = trim($_POST["password"]);
	
	// Validations: try to make sure the data is checked and valid
	/* $fields_required = array("username", "password");
	foreach($fields_required as $field){
		$value = trim($_POST[$field]);
		if (!has_presence($value)) {
			$errors[$field] = ucfirst($field)." can't be blank";
		}
	} */
	
	$fields_required = array("username", "password");
	foreach($fields_required as $field){
		$message = "scanning through ".$field;
		$value = trim($_POST[$field]);
		if (!($validate->has_presence($value))) {
			$validate->errors[$field] = ucfirst($field)." can't be blank";
		}
	}
	
	// Using an assoc. array
	/* $fields_with_max_lengths = array("username" => 30, "password" => 8);
	validate_max_lengths($fields_with_max_lengths); */
	
	if (empty($validate->errors)) {
		// try to login 
		if ($username == "nokafor" && $password == "secret"){
			// successful login
			// redirect_to("basic.html");
			$message = "Logged in sucessfully.";
		} else {
			$message = "Username/password do not match.";
		}
	}
} else {
	$username = "";
	$message = "Please log in.";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">

<html lang = "en">
	<head>
		<title>Form</title>
	</head>
	<body>
		<!--
		
		-->
		
		<?php echo $message; ?><br/>
		<?php echo $validate->form_errors($validate->errors); ?>
		<form action="form_with_validation.php" method="post">
			Username: <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" /><br/>
			Password: <input type="password" name="password" value="" /><br/>
			<br/>
			<input type="submit" name="submit" value="Submit" />
		</form>
		
	</body>
</html>