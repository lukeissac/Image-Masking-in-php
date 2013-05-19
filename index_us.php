<?php
	include("class_lib.php");					//including the class with image manipulation functions.
	include("LoggingHelper.php");
	$logger = new LoggingHelper();
	$logger->logToFile('log.txt', 'call recieved');

	$gotvalues = $_POST['animationContent'];
	$initialsize = getimagesize($gotvalues[4]);
	$logger->logToFile('log.txt',$initialsize[0]);
	$logger->logToFile('log.txt',$initialsize[1]);

	$logger->logToFile('log.txt',$gotvalues[0]);			//Height of scaled image
	$logger->logToFile('log.txt',$gotvalues[1]);			//Width of scaled image
	$logger->logToFile('log.txt',$gotvalues[2]);			//Top value of moved image
	$logger->logToFile('log.txt',$gotvalues[3]);			//Left value of moved image
	$logger->logToFile('log.txt',$gotvalues[4]);			//Url
	$logger->logToFile('log.txt',$gotvalues[5]);			//Logging the id which we are getting

	$blackwhite = 'm2blkwht.png';					//Name of the black and white image
	$vig = 'm2vig.png';						//Name of the vig image with transparent else all
	
						
	$inst = new imageManipulator();					//instantiating the class.

	$inst -> setImagenames($gotvalues[4], $blackwhite);		//Give the sourceimage and mask image name as parameter.
	$inst -> scaleImage($gotvalues[1], $gotvalues[0]);		//Scaling the image as per the given width and height as parameter.
	$inst -> imageMoved($gotvalues[3], $gotvalues[2], 
		$gotvalues[1], $gotvalues[0]);				//Creates the image as per the given top and left value due to movement.

	$inst -> slicetoMaskSize();					//It will slice the above generated image to the dimensions of the mask.
	
	$source = imagecreatefromjpeg( 'slicetomasksizeimage.jpg' );	//Drawing the above generated image in memory to manipulate its pixels.
	$mask = imagecreatefrompng( $blackwhite );			//Drawing the mask image in memory to use it in manipulation process.
	$inst -> slicebyMask( $source, $mask);				//Function to slice the "slicetomasksizeimage.jpg" as per the mask.
									//To display it by streaming it on browser
	$successflag = $inst -> mergeImage($gotvalues[5], $vig);
	echo $successflag;								
?>
