<?php
/*
the flow:

on start
	php 
	reload_files( the chosen set )
		files	( array of files )
		first_files  ( html img tag with filename hard cored )
		
	display first_files	
	
	js
	swap img from arr at interval: setInterval(swap_images, interval);
*/


//load new set if 
if ($_GET != array() ){
	$set_name = $_GET["set"];
}else{
	$set_name = "default";
}
//echo $set_name; exit;

$js_arr = reload_files($set_name)["files"];
$first_files = reload_files($set_name)["first_files"];
$aspects = reload_files($set_name)["aspects"];


//print_r($js_arr); exit();
//print_r($first_files); exit();


function reload_files($set_name){
	$currDir=dirname(__FILE__)."/";
	
	$files = Listfiles( $currDir . "img/$set_name/" , array(".jpg",".JPG"), "is_file");
	shuffle( $files); 
	
	$ran_files = array();
	$aspect_info = array();
	
	foreach($files as $f){
		//to do: decide if it's landscape or portrait
		$s = getimagesize($f);
		
		
/*
		echo $f; 
		echo "<br />";
		print_r($s); exit;
*/
		if ( $s[0]>$s[1] ) {
			//landscape
			$aspect_info[] = "H";
		}else{
			//portrait
			$aspect_info[] = "V";
		}

		$ran_files[] = basename($f);
	}
	
	//newly added
	$js__aspect_arr= '["'. implode( '","', $aspect_info) . '"]';
	
	
//print_r($js__aspect_arr); exit();	
	$js_arr= '["'. implode( '","', $ran_files) . '"]';
	
	//prepare the first 2 loaded files
	$bottom_file = "img/$set_name/" . $ran_files[1];
	$top_file = "img/$set_name/" . $ran_files[0];
	
	$first_files ="<img id='bottom' src='$bottom_file' ><img id='top' src='$top_file' >";
	  
	//echo $first_files; exit;
	return array( "files" => $js_arr, "first_files"=> $first_files, "aspects"=>$js__aspect_arr);
}
	
function Listfiles($dir,$ext_arr, $filter){
	if ( substr( $dir , -1 ) != "/" ){
		$dir .= "/";
	}
	$output_arr=array();
	foreach( $ext_arr as $ext){
		$files = array_filter(glob($dir."*$ext"), $filter);
		$output_arr=array_merge( $output_arr, $files);
	}
	return $output_arr;
}


//this be run by a cronjob
function get_files_set( $number_of_files, $set = null, $width, $height ){
	//db access
	
	
	//copy n resize new files to tmp
		//$width, $height
	
	//move existing to dir sets
	
	
	//move tmp files to image/
	
	
	//redirect to current page, so $files reload n updated
	
}

?>

<!DOCTYPE html>
<html>
<title>slide show</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css.css">
<body>
 	<?php echo $first_files; ?>
	  <!-- 
<img id="bottom" src="img/P5182654.JPG" >
	  <img id="top" src="img/P5182659.JPG" >
 -->
	  
 	<div class="caption">Album: <?php echo $set_name ?></div>
<script>
//http://css3.bradshawenterprises.com/cfimg/

var step=100;
var img_max_index; // = imgs.length-1; //-1 makes compatible with array
var imgs = get_images();
//var imgs_data = get_images_data();

var img_index = 0;
//var set_name = "'" + "'" ;
var tp = document.getElementById("top");
interval = 10000;

var screen_size = {
  width: window.innerWidth || document.body.clientWidth,
  height: window.innerHeight || document.body.clientHeight
}
//alert(screen_size['width']);
//alert(screen_size['height']);
window.image_aspects = <?php echo $aspects; ?>;

swap_images();
setInterval(swap_images, interval);

function carousel() {
	if ( step != 0 ){
		op = step/100;
		tp.style.opacity=op;
		step=step-5;
		setTimeout(carousel, 50);
	}else{
		tp.style.opacity=0;
		step=100;
	}
}

function get_images() {
	arr=  <?php echo $js_arr; ?> ;
	
	arr.push( arr[0]);  //add the tail to end
	img_max_index=arr.length-1;   //-1 makes compatible with array
	//console.log(arr);
	return arr;
}


function get_images_data() {
	arr = window.image_aspects;
	
	arr.push( arr[0]);  //add the tail to end
	img_max_index=arr.length-1;   //-1 makes compatible with array
	//console.log(arr);
	return arr;
}



function swap_images(){
	 //swaps bottom to top and load bottom with the next
	 //call caroousel , which will do the fade by itself in a second
	 
	var bm = document.getElementById("bottom");
	
	
	if ( img_index == img_max_index){ 
		next_index = 0; 
	}else{
		next_index = img_index+1;
	}
	
	//new image is under, at bottom. then the top fade out
	bm.src = "img/<?php echo $set_name; ?>/" + imgs[next_index];
	tp.src = "img/<?php echo $set_name; ?>/" + imgs[img_index];
	
	//to do: how to get their aspect ratio and display properly?
	bm_height = bm.naturalHeight;
	bm_width = bm.naturalWidth;
	
	
	//alert(bm_height);
	
	//console.log(  next_index  );
	
	img_index++;
	
	if ( img_index == img_max_index ){
		//correct?
		get_images();
		//imgs_data = get_images_data();
		img_index = 0;
	}
	
	carousel();
	//setTimeout(carousel, 1500);
}

</script>

</body>
</html>

