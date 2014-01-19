<?php

$string = $title;
$im    = imagecreatefromjpeg("images/swamp_photo.jpg");
$bg = imagecolorallocate($im, 255, 255, 255);
$yellow = imagecolorallocate($im, 255, 255, 102);
$px    = (imagesx($im) - 12 * strlen($string));
$py = (imagesy($im)/2);
// Set the enviroment variable for GD
imagestring($im, 5, $px, $py, $string, $yellow);

header("Content-type: image/png");
imagejpeg($im);
imagedestroy($im);

?> 
