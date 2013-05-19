<?php
			/*
				 This Class can be used for various manipulation to be done on image.
						Author: luke@inkoniq                                      
						Date: 23/04/2013  					
													*/		
 class imageManipulator{
	
	var $filename;
	var $maskfile;

	function setImagenames($targetFile, $maskFile) {
        	$this->filename = $targetFile;
		$this->maskfile = $maskFile;
       	}

	function scaleImage($w, $h){
		list($width, $height) = getimagesize($this->filename);
		
		$thumb = imagecreatetruecolor($w,$h);
		$source = imagecreatefromjpeg($this->filename);

		imagecopyresized($thumb, $source, 0, 0, 0, 0, $w, $h, $width, $height);

		imagejpeg($thumb, 'resizedimage.jpg');
	
		imagedestroy($thumb);				
		imagedestroy($source);
	}			
	
	function slicebyMask( &$picture, $mask){              //Note, that manipulation is done at the address of $picture

		// Get sizes and set up new picture
		    $tempmaskimage=$mask;
		    $xSize = imagesx( $picture );
		    $ySize = imagesy( $picture );
		    $newPicture = imagecreatetruecolor( $xSize, $ySize );
		    imagesavealpha( $newPicture, true );
		    imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) );

		    // Perform pixel-based alpha map application
		    for( $x = 0; $x < $xSize; $x++ ) {
			for( $y = 0; $y < $ySize; $y++ ) {
			    $alpha = imagecolorsforindex( $mask, imagecolorat( $mask, $x, $y ) );
			    $alpha = 127 - floor( $alpha[ 'red' ] / 2 );
			   
			    $color = imagecolorsforindex( $picture, imagecolorat( $picture, $x, $y ) );
			    imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, $color[ 'red' ], $color[ 'green' ],
			    $color[ 'blue' ], $alpha ) );
			}
		    }

		    // Copy back to original picture
		    imagedestroy( $picture );
		    $picture = $newPicture;
			
		    imagepng($picture, 'maskedimage.png');
	}

	function imageMoved($left, $top, $w, $h){
		$left = $left+20;
		$destin = imagecreatetruecolor(505,295);			/* The parameters are the size of the container */
		$srcin = imagecreatefromjpeg('resizedimage.jpg');
		imagecopy($destin, $srcin, $left, $top, 0, 0, $w, $h);  /* Third and fourth parameter are the top and left values of moved image */

		// Output and free from memory
		
		imagejpeg($destin, 'movedimage.jpg');

		imagedestroy($destin);
		imagedestroy($srcin);

	}

	function slicetoMasksize(){
		$to = imagecreatetruecolor(208, 295);
		$srcimg = imagecreatefromjpeg('movedimage.jpg');
		imagecopy($to, $srcimg, 0, 0, 178.5, 0, 208, 295);

		// Output and free from memory

		imagejpeg($to, 'slicetomasksizeimage.jpg');

		imagedestroy($to);
		imagedestroy($srcimg);
	}

	function mergeImage($id , $vigimg){
		// Create image instances
		$src2 = imagecreatefrompng('maskedimage.png');
		$dest2 = imagecreatefrompng($vigimg);

		imagesavealpha( $dest2, true );

		// Copy
		imagecopy($dest2, $src2, 0, 0, 0, 0, 208, 295);

		// Output and free from memory
		$returnval= imagepng($dest2, $id.'.png');

		imagedestroy($dest2);
		imagedestroy($src2);

		return $returnval;
	}			
}

?>
