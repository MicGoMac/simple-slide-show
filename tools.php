<?php


//e,g, resizeto($src, "/Users/michael/test.jpg", 300) ;

function resizeto ( $src, $desDir, $desired_width){
 
	/* read the source image */
	$source_image = imagecreatefromjpeg($src);
	//$source_image = imagecreatefrompng($src);
	
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	
	/* create the physical thumbnail image to its destination */
	$dest=$desDir. basename($src);
	//echo $dest."\n";
	echo ".";
	imagejpeg($virtual_image, $dest);
}
?>