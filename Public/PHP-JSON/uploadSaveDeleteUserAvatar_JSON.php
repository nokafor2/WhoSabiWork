<?php 
	require_once("../../includes/initialize.php");
	
	header('Content-type: text/javascript');
	// This will be set to specify as a default output upon request from the JSON file
	// An array is actually created here
	$json = array(
		'success' => false,
		'result' => false
	);
	
	
	if (request_is_post() && request_is_same_domain()) {
		$post_params = allowed_post_params(['upload', 'path', 'reselectedAvatar', 'changeImage']);

		if (isset($_FILES['photo'])) {
			$userId = $session->user_id;
			$userId = $database->escape_value($userId);
			$photo = new User_Photograph();			
			$attach_photo = $photo->attach_file($_FILES['photo']);
			// If attach photo was successful, continue with saving
			if ($attach_photo) {
				$photo->user_id = $userId;
							
				$savedPhoto = $photo->save();
				if ($savedPhoto === TRUE) {
					// Success
					$json["success"] = true;
					$json["result"] = $_SESSION['img_name'];
				} else {
					$message = join("<br/>", $photo->errors);
					$json["success"] = "image not saved";
					$json["result"] = $message;
				}
			} else {
				$message = join("<br/>", $photo->errors);
				// $session->message($message);
				$json["success"] = "image not saved";
				$json["result"] = $message;
			}							
		} elseif (isset($_FILES["croppedImage"])) {
			/*
			$croppedImage = explode(".", $_FILES['croppedImage']['name']);
			// print_r($croppedImage);
			$to_be_uploaded = $_FILES['croppedImage']['tmp_name'];
			$croppedImageName = $_FILES['croppedImage']['name'];
			$size = $_FILES['croppedImage']['size'];
			$type = $_FILES['croppedImage']['type'];
			$extention = end($croppedImage);
			$new_file = '../images/'.$croppedImageName;
			if (file_exists($new_file)) {
				unlink($new_file);
			}

			move_uploaded_file($to_be_uploaded, $new_file);
			// Unset temporary path after upload of image
			unset($to_be_uploaded);

			// Update the image properties saved in the session with the cropped image.
			$_SESSION['user_photo_filename'] = $croppedImageName;
			$_SESSION['user_photo_type'] = $type;
			$_SESSION['user_photo_size'] = $size; */

			// Save the image properties to the databse
			$userId = $session->user_id;
			$userId = $database->escape_value($userId);
			$photo = new User_Photograph();			
			$attach_photo = $photo->attach_file($_FILES['croppedImage']);
			$photo->user_id = $userId;
			// If attach photo was successful, continue with saving
			if ($attach_photo) {					
				$savedPhoto = $photo->saveCrop();
				if ($savedPhoto === TRUE) {
					$json["success"] = true;
					$json["result"] = $_SESSION['img_name'];
				} else {
					$message = join("<br/>", $photo->errors);
					$json["success"] = "image not saved";
					$json["result"] = $message;
				}
			} else {
				$message = join("<br/>", $photo->errors);
				$json["success"] = "image not saved";
				$json["result"] = $message;
			}
			
			// Append filetime to the image, so that an updated image can always be uploaded.
			$new_file = $_SESSION['target_path_name'];
			$new_file = '../images/'.$new_file;
			$filetime = filemtime($new_file);		
			
			// After moving the cropped image, save to the database
			
			// Send back data to be displayed
			// The 'new_file' path can still be used since the respective codes have to move back one folder and into the images folder
			// Appending the filetime with the '?' helps the browser/cache to use the current image since it has the same filename.
			$json['success'] = true;			
			$json['result'] = '<img id="cropped_avatar" src="'.$new_file.'?'.$filetime.'" />
					<div id="actionBtnsDiv">
					<button type="button" data-path="'.$new_file.'?'.$filetime.'" class="crop_button btnStyle1" id="remove_avatar" > Remove Image </button>			
					<button type="button" data-path="'.$new_file.'?'.$filetime.'" class="crop_button btnStyle1" id="upload_avatar" > Upload Image </button> 
					</div>';
		} elseif (!empty($_POST['upload'])) {
			global $session;
			// Delete the initial uploaded image cropped from
			$initialImage = $_SESSION['img_name'];
			if (file_exists('../images/'.$initialImage)) {
				if (unlink('../images/'.$initialImage)) {
					$json['success'] = true;
					$json['result'] = "Previous image was deleted.";
				}
			}

			$userId = $session->user_id;
			// Check if the customer already has an avatar image
			$photoObj = User_Photograph::find_by_userId($userId);

			if (!empty($photoObj)) {
				// Unset the previous avatar image in the database
				$oldFilename = $photoObj->filename;
				if (file_exists('../images/'.$oldFilename)) {
					unlink('../images/'.$oldFilename);	
				}

				// Update avatar record
				$photoObj->user_id = $_SESSION['user_photo_user_id'];
				$photoObj->filename = $_SESSION['user_photo_filename'];
				$photoObj->type = $_SESSION['user_photo_type'];
				$photoObj->size = $_SESSION['user_photo_size'];
				$photoObj->visible = $_SESSION['user_photo_visible'];
				$photoObj->date_created = $_SESSION['user_photo_date_created'];
				$photoObj->date_edited = current_Date_Time();
				// print_r($_SESSION);
				
				if ($photoObj->update()) {
					$json['success'] = true;
					$json['photoId'] = $photoObj->id;
					$json['result'] = "The photo was saved.";
				} else {
					$json['success'] = false;
					$json['result'] = "The photo was not saved.";
				}
			} else {
				// Make new avatar record
				$photo = new User_Photograph();
				$photo->user_id = $_SESSION['user_photo_user_id'];
				$photo->filename = $_SESSION['user_photo_filename'];
				$photo->type = $_SESSION['user_photo_type'];
				$photo->size = $_SESSION['user_photo_size'];
				$photo->visible = $_SESSION['user_photo_visible'];
				$photo->date_created = $_SESSION['user_photo_date_created'];
				
				if ($photo->create()) {
					$json['success'] = true;
					$json['photoId'] = $database->insert_id();
					$json['result'] = "The photo was saved.";
				} else {
					$json['success'] = false;
					$json['result'] = "The photo was not saved.";
				}
			}
			
			// After successfully saving photo details, unset the photo session variables
			unset($_SESSION['user_photo_user_id']);
			unset($_SESSION['user_photo_filename']);
			unset($_SESSION['user_photo_type']);
			unset($_SESSION['user_photo_size']);
			unset($_SESSION['user_photo_visible']);
			unset($_SESSION['user_photo_date_created']);
			unset($_SESSION['img_name']);
			unset($_SESSION['target_path_name']);
		} elseif (!empty($_POST['path'])) {
			$imgPath = $_POST['path'];
			// Get the position where '?' occurs in the name of the image path
			$strPos = strpos($imgPath, '?');
			// Get the new image path excluding the occurence of ?
			$newImgPath = substr($imgPath, 0, $strPos);
			if (unlink($newImgPath)) {
				$json['success'] = true;
				$json['result'] = "Image has been deleted.";
			} else {
				$json['success'] = false;
				$json['result'] = "Image could not be deleted.";
			}
		} elseif (isset($_POST['changeImage'])) {
			// This is where to unlink the initial uploaded image that was cropped from.
			$initialImage = $_SESSION['img_name'];
			if (file_exists('../images/'.$initialImage)) {
				if (unlink('../images/'.$initialImage)) {
					$json['success'] = true;
					$json['result'] = "Previous image was deleted.";
				} else {
					$json['success'] = true;
					$json['result'] = "Previous image was not deleted.";
				}	
			} else {
				$json['success'] = false;
				$json['result'] = "Previous image does not exist.";
			}

			// Delete cropped image too
			$croppedImage = $_SESSION['user_photo_filename'];
			if (file_exists('../images/'.$croppedImage)) {
				if (unlink('../images/'.$croppedImage)) {
					$json['success2'] = true;
					$json['result2'] = "cropped image deleted";
				} else {
					$json['success2'] = false;
					$json['result2'] = "cropped image not deleted";
				}	
			} else {
				$json['success2'] = false;
				$json['result2'] = "cropped image does not exist";
			}
		} elseif (isset($_POST['cancelUpload'])) {
			// This is where to unlink the initial uploaded image that was cropped from.
			$initialImage = $_SESSION['img_name'];
			if (file_exists('../images/'.$initialImage)) {
				if (unlink('../images/'.$initialImage)) {
					$json['success'] = true;
					$json['result'] = "Previous image was deleted.";
				} else {
					$json['success'] = true;
					$json['result'] = "Previous image was not deleted.";
				}	
			} else {
				$json['success'] = false;
				$json['result'] = "Previous image does not exist.";
			}
		} else {
			$json["success"] = false;
			$json["result"] = "error:'An error occurred sending the data from JSON'";
		}
	} else {
		$json["success"] = false;
		$json["result"] = "error:'Request not from same domain.'";
	}
	echo json_encode($json);
?>