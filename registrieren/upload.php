<?php

	function imageCreateFromAny($filepath) { 
    $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize() 
    $allowedTypes = array( 
        1,  // [] gif 
        2,  // [] jpg 
        3,  // [] png 
        6   // [] bmp 
    ); 
    if (!in_array($type, $allowedTypes)) { 
        return false; 
    } 
    switch ($type) { 
			case 1 : 
				$im = imageCreateFromGif($filepath); 
			break; 
			case 2 : 
				$im = imageCreateFromJpeg($filepath); 
			break; 
			case 3 : 
				$im = imageCreateFromPng($filepath); 
			break; 
			case 6 : 
				$im = imageCreateFromBmp($filepath); 
			break; 
    }    
    return $im;  
	} 

	include_once 'db_connect.php';
	include_once 'functions.php';
	if(isset($_POST['uid'], $_FILES['profile_picture'], $_POST['password'])){
		if(isValidInvite($_POST['uid'], $_POST['password'], $mysqli)){
			if( 0 < $_FILES['profile_picture']['error']){
				echo error('internalError', 500, 'File corrupted'); exit;
			} else {
				$username = $_POST['uid'];
				$originalFilename = basename($_FILES['profile_picture']['name']);
				$dir = "../users/$username/";
				$target = "";
				if(move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target . $originalFilename)){
					$image = imageCreateFromAny($target . $originalFilename);
					$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
					imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
					imagealphablending($bg, TRUE);
					imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
					imagedestroy($image);
					imagejpeg($bg, $dir . "avatar.jpg", 100);
					imagedestroy($bg);
					unlink($target . $originalFilename);
					echo json_encode([
						"success" => true,
						"href" => $dir . "avatar.jpg";
					], JSON_PRETTY_PRINT);
				} else {
					echo error('internalError', 500, 'Storage failed');
				}
			}
		} else {
			echo error('clientError', 403, 'Forbidden'); exit;
		}
	} else {
		echo error('clientError', 400, 'Bad Request'); exit;
	}