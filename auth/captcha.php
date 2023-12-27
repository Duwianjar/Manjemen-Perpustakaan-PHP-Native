<?php
session_start();
$random_alpha = md5(rand());

$captcha_code = substr($random_alpha, 0, 10);
$_SESSION["captcha_code"] = $captcha_code;

$target_layer = imagecreatetruecolor(100,30);

$captcha_background = imagecolorallocate($target_layer, rand(0,255), rand(0,255), rand(0,255));
imagefill($target_layer,25,25,$captcha_background);

$captcha_text_color = imagecolorallocate($target_layer,rand(0,255), rand(0,255), rand(0,255));

for($i=0; $i < 5; $i++ ) {
    $line_color = imagecolorallocate($target_layer,rand(0,255), rand(0,255), rand(0,255));
    imageline($target_layer, rand(0,70), rand(0,30), rand(0,70), rand(0,30), $line_color);
}

for($i=0; $i < 5; $i++ ) {
    $dot_color = imagecolorallocate($target_layer,rand(0,255), rand(0,255), rand(0,255));
    imagesetpixel($target_layer, rand(0,70), rand(0,30), $dot_color);
}

imagestring($target_layer, 5, 5, 5, $captcha_code, $captcha_text_color);
header("Content-type: image/jpeg");
imagejpeg($target_layer);