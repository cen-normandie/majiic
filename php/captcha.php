<?php
session_start();


function generateRandomString($length = 6) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';//abcdefghijklmnopqrstuvwxyz
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}




$_SESSION['n_n'] = generateRandomString();
//mt_rand(100,9999);
$img = imagecreate(240,80);
$font = getcwd().'/../fonts/fimc.ttf';
$bg = imagecolorallocate($img,255,255,255);
$textcolor = imagecolorallocate($img,110,193,77);

imagettftext($img, 40,0,20,52, $textcolor, $font,$_SESSION['n_n']);

header('Content-type:image/jpeg');
imagejpeg($img);
imagedestroy($img);

?>