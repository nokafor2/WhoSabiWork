<?php
require_once("../../includes/initialize.php");
// Path to image is: WhoSabiWork/Public/images/OnewuBusiness.jpg
// Ugoeze images: /WhoSabiWork/UgoezeTailoring/ugoezeT1.jpg
// $image_purpose: is the new group/objective the image belongs to
// $dir: is the path to the image
// $MaxWidth: is the maximum dimension for the width of the image
$image_purpose = "adImage";
$dir = SITE_ROOT .DS. 'UgoezeTailoring';
$filename = "ugoezeT1.jpg";
$MaxWidth = "300";
$extension = getImageExt($filename);

function resize_and_store_extra_images($image_purpose,$dir,$filename,$MaxWidth,$extension){
	/*
		Store an optimized copy of an image
	*/
	$old_filename = $filename ;
	// $filename = $dir."/".$filename ;
	// $new_name = "$dir/".join_image_purpose($old_filename,$image_purpose);
	// $filename = "/WhoSabiWork/UgoezeTailoring/ugoezeT1.jpg";
	// $new_name = "/WhoSabiWork/UgoezeTailoring/ugoezeT1_adImage.jpg";
	$filename = "ugoezeT1.jpg";
	$new_name = "ugoezeT1_adImage.jpg";
	
	// Get new dimensions
	list($width, $height) = getimagesize($filename);
	/* if( ($width > $MaxWidth)||($width < 40) ){
		$ThumbWidth = $MaxWidth;
		//CALCULATE THE IMAGE RATIO
		$imgratio=$width/$height;
	}else{
		$ThumbWidth = $MaxWidth;
		//CALCULATE THE IMAGE RATIO
		$imgratio= 1;
	}	
	
		if ($imgratio>1){
			$new_width = $ThumbWidth;
			$new_height = $ThumbWidth/$imgratio;
		}else{
			$new_height = $ThumbWidth;
			$new_width = $ThumbWidth*$imgratio;
		} */
		
		$new_width = 300;
		$new_height = 150;
			// Resample
			$image_p = imagecreatetruecolor($new_width, $new_height);
			$image = NewImageCreate($filename,$extension);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);		
			// Output
			if(ImageOutput($image_p, $new_name, 90, $extension) ){
				return 1;	
			}else{
				return 0;	
			}
}
function NewImageCreate($filename,$extension){
	switch($extension){
		case 'jpg':
			return imagecreatefromjpeg($filename);
			break ;
		case 'png':
			return imagecreatefrompng($filename);
			break ;
		case 'gif':
			return imagecreatefromgif($filename);
			break ;
	}
}
function ImageOutput($image_p, $new_name, $quality, $extension){
	switch($extension){
		case 'jpg':
			return imagejpeg($image_p, $new_name, $quality);
			break ;
		case 'png':
			return imagejpeg($image_p, $new_name, $quality);
			break ;
		case 'gif':
			return imagegif($image_p, $new_name);
			break ;
	}
}	
function join_image_purpose($image_filename,$image_purpose){
	$img_name = explode(".",$image_filename) ;
	echo "supposed image name is: ".$img_name[0]."<br/>";
	echo "supposed image extension is: ".$img_name[1]."<br/>";
	$img_name = $img_name[0]."_"."$image_purpose".".".$img_name[1] ;
	echo "New Image name is: ".$img_name."<br/>";
	return $img_name;
}
function getImageExt($image_filename){
	$img_name = explode(".",$image_filename) ;
	return $img_name[1];
}

resize_and_store_extra_images($image_purpose,$dir,$filename,$MaxWidth,$extension);
// <img src="/WhoSabiWork/Images/WhoSabiWorkL1.jpg" width="200" height="100" alt="WhoSabiWork Logo"  />
?>

