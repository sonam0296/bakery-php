<?php
/*
	EXEMPLO PARA FAZER RESIZE A UMA IMAGEM
	require("resize_image.php");
	$img1=new Resize(directoria, nome_ficheiro, nome_ficheiro, max_width, max_height);
	$img1->resize_image();
*/
class Resize {
	private $upload_dir;
	private $large_image_name;
	private $thumb_image_name;
	private $max_width;
	private $max_height;
	
	
	function __construct($upload_dir, $large_image_name, $thumb_image_name, $max_width, $max_height){
			$this->upload_dir=$upload_dir;
			$this->large_image_name=$large_image_name;
			$this->thumb_image_name=$thumb_image_name;
			$this->max_width=$max_width;
			$this->max_height=$max_height;
	}
	
	
	public function resize_image(){
		#########################################################################################################
		# CONSTANTS																								#
		# You can alter the options below																		#
		#########################################################################################################
		$upload_dir = $this->upload_dir; 				// The directory for the images to be saved in
		$upload_path = $upload_dir."/";				// The path to where the image will be saved
		$large_image_prefix = ""; 			// The prefix name to large image
		$thumb_image_prefix = "";			// The prefix name to the thumb image
		$large_image_name = $this->large_image_name;     // New name of the large image (append the timestamp to the filename)
		$thumb_image_name = $this->thumb_image_name;     // New name of the thumbnail image (append the timestamp to the filename)
		$max_file = "3"; 							// Maximum file size in MB
		$max_width = $this->max_width;							// Max width allowed for the large image
		$max_height = $this->max_height;
		// Only one of these image types should be allowed for upload
		$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
		$allowed_image_ext = array_unique($allowed_image_types); // do not change this
		$image_ext = "";	// initialise variable, do not change this.
		foreach ($allowed_image_ext as $mime_type => $ext) {
			$image_ext.= strtoupper($ext)." ";
		}
		
		
		
		
		if(!function_exists('resizeImage2')){
			function resizeImage2($image,$image_thumb, $width,$height,$scale) {
				list($imagewidth, $imageheight, $imageType) = getimagesize($image);
				$imageType = image_type_to_mime_type($imageType);
				$newImageWidth = ceil($width * $scale);
				$newImageHeight = ceil($height * $scale);
				$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);				
				switch($imageType) {
					case "image/gif":
						$source=imagecreatefromgif($image); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						$source=imagecreatefromjpeg($image); 
						break;
					case "image/png":
					case "image/x-png":
						$source=imagecreatefrompng($image); 
						break;
				}
				
				
				
				switch($imageType) {
					case "image/gif":
						imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
						imagegif($newImage,$image_thumb); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
						
						//ADDED SET/2014
						if (imageistruecolor($newImage)) {
							$colorWhite = imagecolorallocate($newImage,255,255,255);
							$processHeight = $newImageHeight;
							$processWidth = $newImageWidth;
							//Travel y axis
							for($y=0; $y<($processHeight); ++$y){
								// Travel x axis
								for($x=0; $x<($processWidth); ++$x){
									// Change pixel color
									$colorat=imagecolorat($newImage, $x, $y);
									$r = ($colorat >> 16) & 0xFF;
									$g = ($colorat >> 8) & 0xFF;
									$b = $colorat & 0xFF;
									if(($r==253 && $g == 253 && $b ==253) || ($r==254 && $g == 254 && $b ==254)) {
										imagesetpixel($newImage, $x, $y, $colorWhite);
									}
								}
							}
						}
						
						imagejpeg($newImage,$image_thumb,100);
						
						break;
					case "image/png":
					case "image/x-png":
						imagealphablending($newImage, false);
						$transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
						imagefill($newImage, 0, 0, $transparent);
						imagesavealpha($newImage,true);
						imagealphablending($newImage, true);
						imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
						imagepng($newImage,$image_thumb);  
						break;
				}
				
				@chmod($image_thumb, 0777);
				return $image_thumb;
			}
		}
		//You do not need to alter these functions
		if(!function_exists('resizeThumbnailImage')){
			function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
				list($imagewidth, $imageheight, $imageType) = getimagesize($image);
				$imageType = image_type_to_mime_type($imageType);
				$imageheight=$height;
				$imagewidth=$width;
				/*$newImageWidth = ceil($width * $scale);
				$newImageHeight = ceil($height * $scale);*/
				
				$newImageWidth = $width;
				$newImageHeight = $height;
				$start_width;
				$start_height;
				
				$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
				switch($imageType) {
					case "image/gif":
						$source=imagecreatefromgif($image); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						$source=imagecreatefromjpeg($image); 
						break;
					case "image/png":
					case "image/x-png":
						$source=imagecreatefrompng($image); 
						break;
				}
				
				switch($imageType) {
					case "image/gif":
						imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$imagewidth,$imageheight);
						imagegif($newImage,$thumb_image_name); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$imagewidth,$imageheight);
						
						//ADDED SET/2014
						if (imageistruecolor($newImage)) {
							$colorWhite = imagecolorallocate($newImage,255,255,255);
							$processHeight = $newImageHeight;
							$processWidth = $newImageWidth;
							//Travel y axis
							for($y=0; $y<($processHeight); ++$y){
								// Travel x axis
								for($x=0; $x<($processWidth); ++$x){
									// Change pixel color
									$colorat=imagecolorat($newImage, $x, $y);
									$r = ($colorat >> 16) & 0xFF;
									$g = ($colorat >> 8) & 0xFF;
									$b = $colorat & 0xFF;
									if(($r==253 && $g == 253 && $b ==253) || ($r==254 && $g == 254 && $b ==254)) {
										imagesetpixel($newImage, $x, $y, $colorWhite);
									}
								}
							}
						}
						
						imagejpeg($newImage,$thumb_image_name,100); 
						break;
					case "image/png":
					case "image/x-png":
						imagealphablending($newImage, false);
						$transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
						imagefill($newImage, 0, 0, $transparent);
						imagesavealpha($newImage,true);
						imagealphablending($newImage, true);
						imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$imagewidth,$imageheight);
						imagepng($newImage,$thumb_image_name);  
						break;
				}
				
				
				@chmod($thumb_image_name, 0777);
				return $thumb_image_name;
			}
		}
		
		
		//You do not need to alter these functions
		if(!function_exists('getHeight')){
			function getHeight($image) {
				$size = getimagesize($image);
				$height = $size[1];
				return $height;
			}
		}
		//You do not need to alter these functions
		if(!function_exists('getWidth')){
			function getWidth($image) {
				$size = getimagesize($image);
				$width = $size[0];
				return $width;
			}
		}
		
		
		//AQUI COMEÇA!!!!////
		$large_image_location = $upload_path.$large_image_name;
		$thumb_image_name = $upload_path.$thumb_image_name;
		
		@chmod($large_image_location, 0777);
		
		$width = getWidth($large_image_location);
		$height = getHeight($large_image_location);
		//Scale the image if it is greater than the width set above
		$cenas=$max_width/$width;
		$cenas1=$max_height/$height;
		
		if ($width > $max_width && $cenas < $cenas1){
			$scale = $max_width/$width;
			$uploaded = resizeImage2($large_image_location,$thumb_image_name,$width,$height,$scale);
			
		}elseif($height > $max_height && $cenas1 <= $cenas){
			$scale = $max_height/$height;
			$uploaded = resizeImage2($large_image_location,$thumb_image_name,$width,$height,$scale);
			
		}else{
			$scale = 1;
			$uploaded = resizeImage2($large_image_location,$thumb_image_name,$width,$height,$scale);
		}
		
		
			$thumb_image = $uploaded;	
		
		
		move_uploaded_file($thumb_image, $large_image_location);
		@chmod($large_image_location, 0777);
		
	} 
	
	
}




/*
	EXEMPLO PARA CRIAR MINIATURA DE UMA IMAGEM
	require("resize_image.php");
	$img1=new Thumb(directoria, nome_ficheiro, nome_ficheiro, thumb_width, thumb_height);
	$img1->thumb_image();
*/
class Thumb {
	private $upload_dir;
	private $large_image_name;
	private $thumb_image_name;
	private $max_width;
	private $max_height;
	private $thumb_width;
	private $thumb_height;
	
	
	function __construct($upload_dir, $large_image_name, $thumb_image_name, $thumb_width, $thumb_height){
			$this->upload_dir=$upload_dir;
			$this->large_image_name=$large_image_name;
			$this->thumb_image_name=$thumb_image_name;
			$this->max_width=$thumb_width;
			$this->max_height=$thumb_height;
			$this->thumb_width=$thumb_width;
			$this->thumb_height=$thumb_height;
	}
	
	
	public function thumb_image(){
		#########################################################################################################
		# CONSTANTS																								#
		# You can alter the options below																		#
		#########################################################################################################
		$upload_dir = $this->upload_dir; 				// The directory for the images to be saved in
		$upload_path = $upload_dir."/";				// The path to where the image will be saved
		$large_image_prefix = ""; 			// The prefix name to large image
		$thumb_image_prefix = "";			// The prefix name to the thumb image
		$large_image_name = $this->large_image_name;     // New name of the large image (append the timestamp to the filename)
		$thumb_image_name = $this->thumb_image_name;     // New name of the thumbnail image (append the timestamp to the filename)
		$max_file = "3"; 							// Maximum file size in MB
		$max_width = $this->max_width;							// Max width allowed for the large image
		$max_height = $this->max_height;
		$thumb_width = $this->thumb_width;						// Width of thumbnail image
		$thumb_height = $this->thumb_height;						// Height of thumbnail image
		// Only one of these image types should be allowed for upload
		$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
		$allowed_image_ext = array_unique($allowed_image_types); // do not change this
		$image_ext = "";	// initialise variable, do not change this.
		foreach ($allowed_image_ext as $mime_type => $ext) {
			$image_ext.= strtoupper($ext)." ";
		}
		
		
		
		if(!function_exists('resizeImage2')){
			function resizeImage2($image,$image_thumb, $width,$height,$scale) {
				list($imagewidth, $imageheight, $imageType) = getimagesize($image);
				$imageType = image_type_to_mime_type($imageType);
				$newImageWidth = ceil($width * $scale);
				$newImageHeight = ceil($height * $scale);
				$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
				
				switch($imageType) {
					case "image/gif":
						$source=imagecreatefromgif($image); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						$source=imagecreatefromjpeg($image); 
						break;
					case "image/png":
					case "image/x-png":
						$source=imagecreatefrompng($image); 
						break;
				}
				
				
				switch($imageType) {
					case "image/gif":
						imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
						imagegif($newImage,$image_thumb); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
						
						//ADDED SET/2014
						if (imageistruecolor($newImage)) {
							$colorWhite = imagecolorallocate($newImage,255,255,255);
							$processHeight = $newImageHeight;
							$processWidth = $newImageWidth;
							//Travel y axis
							for($y=0; $y<($processHeight); ++$y){
								// Travel x axis
								for($x=0; $x<($processWidth); ++$x){
									// Change pixel color
									$colorat=imagecolorat($newImage, $x, $y);
									$r = ($colorat >> 16) & 0xFF;
									$g = ($colorat >> 8) & 0xFF;
									$b = $colorat & 0xFF;
									if(($r==253 && $g == 253 && $b ==253) || ($r==254 && $g == 254 && $b ==254)) {
										imagesetpixel($newImage, $x, $y, $colorWhite);
									}
								}
							}
						}
						
						imagejpeg($newImage,$image_thumb,100); 
						break;
					case "image/png":
					case "image/x-png":
						imagealphablending($newImage, false);
						$transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
						imagefill($newImage, 0, 0, $transparent);
						imagesavealpha($newImage,true);
						imagealphablending($newImage, true);
						imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
						imagepng($newImage,$image_thumb);  
						break;
				}
				
				@chmod($image_thumb, 0777);
				return $image_thumb;
			}
		}
		//You do not need to alter these functions
		if(!function_exists('resizeThumbnailImage')){
			function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
				list($imagewidth, $imageheight, $imageType) = getimagesize($image);
				$imageType = image_type_to_mime_type($imageType);
				$imageheight=$height;
				$imagewidth=$width;
				/*$newImageWidth = ceil($width * $scale);
				$newImageHeight = ceil($height * $scale);*/
				
				$newImageWidth = $width;
				$newImageHeight = $height;
				$start_width;
				$start_height;
				
				$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
				switch($imageType) {
					case "image/gif":
						$source=imagecreatefromgif($image); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						$source=imagecreatefromjpeg($image); 
						break;
					case "image/png":
					case "image/x-png":
						$source=imagecreatefrompng($image); 
						break;
				}
				
				switch($imageType) {
					case "image/gif":
						imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$imagewidth,$imageheight);
						imagegif($newImage,$thumb_image_name); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$imagewidth,$imageheight);
						
						//ADDED SET/2014
						if (imageistruecolor($newImage)) {
							$colorWhite = imagecolorallocate($newImage,255,255,255);
							$processHeight = $newImageHeight;
							$processWidth = $newImageWidth;
							//Travel y axis
							for($y=0; $y<($processHeight); ++$y){
								// Travel x axis
								for($x=0; $x<($processWidth); ++$x){
									// Change pixel color
									$colorat=imagecolorat($newImage, $x, $y);
									$r = ($colorat >> 16) & 0xFF;
									$g = ($colorat >> 8) & 0xFF;
									$b = $colorat & 0xFF;
									if(($r==253 && $g == 253 && $b ==253) || ($r==254 && $g == 254 && $b ==254)) {
										imagesetpixel($newImage, $x, $y, $colorWhite);
									}
								}
							}
						}
						
						imagejpeg($newImage,$thumb_image_name,100); 
						break;
					case "image/png":
					case "image/x-png":
						imagealphablending($newImage, false);
						$transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
						imagefill($newImage, 0, 0, $transparent);
						imagesavealpha($newImage,true);
						imagealphablending($newImage, true);
						imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$imagewidth,$imageheight);
						imagepng($newImage,$thumb_image_name);  
						break;
				}
				
				
				@chmod($thumb_image_name, 0777);
				return $thumb_image_name;
			}
		}
		
		
		//You do not need to alter these functions
		if(!function_exists('getHeight')){
			function getHeight($image) {
				$size = getimagesize($image);
				$height = $size[1];
				return $height;
			}
		}
		//You do not need to alter these functions
		if(!function_exists('getWidth')){
			function getWidth($image) {
				$size = getimagesize($image);
				$width = $size[0];
				return $width;
			}
		}
		
		
		//AQUI COMEÇA!!!!////
		$large_image_location = $upload_path.$large_image_name;
		$thumb_image_name = $upload_path.$thumb_image_name;
		
		@chmod($large_image_location, 0777);
		
		$width = getWidth($large_image_location);
		$height = getHeight($large_image_location);
		//Scale the image if it is greater than the width set above
		$cenas=$max_width/$width;
		$cenas1=$max_height/$height;
		
		if ($width > $max_width && $cenas > $cenas1){
			$scale = $max_width/$width;
			$uploaded = resizeImage2($large_image_location,$thumb_image_name,$width,$height,$scale);
			
		}elseif($height > $max_height && $cenas1 >= $cenas){
			$scale = $max_height/$height;
			$uploaded = resizeImage2($large_image_location,$thumb_image_name,$width,$height,$scale);
			
		}else{
			$scale = 1;
			$uploaded = resizeImage2($large_image_location,$thumb_image_name,$width,$height,$scale);
		}
		
		
		list($imagewidth, $imageheight, $imageType) = getimagesize($uploaded);


		if($imageheight>$thumb_height){
			$start_width=0;
			$start_height=ceil(($imageheight-$thumb_height)/2);
			$scale = 1;
			$thumb_image = resizeThumbnailImage($thumb_image_name, $uploaded, $thumb_width, $thumb_height, $start_width, $start_height, $scale);
		
		}elseif($imagewidth>$thumb_width){
			$start_height=0;
			$start_width=ceil(($imagewidth-$thumb_width)/2);
			$scale = 1;
			$thumb_image = resizeThumbnailImage($thumb_image_name, $uploaded, $thumb_width, $thumb_height, $start_width, $start_height, $scale);
		
		}else{
			$thumb_image = $uploaded;	
		}
		
		move_uploaded_file($thumb_image, $large_image_location);
		@chmod($large_image_location, 0777);
		
	} 
	
	
}
?>