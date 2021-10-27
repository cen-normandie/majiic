<?php
session_start();

function generateRandomString($length = 6) {
    $characters = 'ABCDEFGHIJKLNOPQRSTUVXYZ';//abcdefghijklmnopqrstuvwxyz
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}





$_SESSION['n_n'] = generateRandomString();
//mt_rand(100,9999);
$img = imagecreate(200,30);
$font = getcwd().'/../fonts/Multistrokes.ttf';
$bg = imagecolorallocate($img,255,255,255);
$textcolor = imagecolorallocate($img,110,193,77);
$pixel_color = imagecolorallocate($img, 110,193,77);
for($i=0;$i<1500;$i++) {
    imagesetpixel($img,rand()%200,rand()%40,$pixel_color);
}  

imagettftext($img, 30,0,12,32, $textcolor, $font,$_SESSION['n_n']);

header('Content-type:image/jpeg');
imagejpeg($img);
imagedestroy($img);

?>