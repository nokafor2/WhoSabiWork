<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isset($_FILES['image']) && $_FILES['image']['type'] == "image/jpeg"){
		move_uploaded_file($_FILES['image']['tmp_name'], $_FILES['image']['name']);
		
		$file = $_FILES['image']['name'];
		
		// parse in the file location and the file resolution.
		$max_resolution = 480;
		resize_image($file, $max_resolution);
		echo "<img src='$file' />";
	} else {
		echo "file not supported";
	}
}

function resize_image($file, $max_resolution) {
	if (file_exists($file)) {
		$original_image = imagecreatefromjpeg($file);
		
		// resolution
		$original_width = imagesx($original_image);
		$original_height = imagesy($original_image);
		
		/* // try max width first... if the image is landscape
		$ratio = $max_resolution / $original_width;
		$new_width = $max_resolution;
		$new_height = $original_height * $ratio;
		
		// check if the resolution is alright
		// Set max resolution with height if it is a portrait image
		if ($new_height > $max_resolution) {
			$ratio = $max_resolution / $original_height;
			$new_height = $max_resolution;
			$new_width = $original_width * $ratio;
		} */
		
		// Determine if the image is portrait or landscape
		// make the max resolution to be equal to the smaller height, so that 
		// the image will fit into the square box which will be used for cropping
		if ($original_width > $original_height) {
			// Image is landscape
			// Make the max resoultion to be equal to the height
			$ratio = $max_resolution / $original_height;
			$new_height = $max_resolution;
			$new_width = $original_width * $ratio;
		} else {
			// Image is portrait
			// Make the max resoultion to be equal to the width
			$ratio = $max_resolution / $original_width;
			$new_width = $max_resolution;
			$new_height = $original_height * $ratio;
		}
		
		if ($original_image) {
			// This is the resized version of the original image
			$new_image = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
			// This will create a new image with a quality of 90
			imagejpeg($new_image, $file, 100);
		}
	}
}

?>

<form method="post" enctype="multipart/form-data">

<input type="file" name="image" /><br/>
<input type='submit' value='post' />
</form>