<?php
$code="8+2=?";
$im = imagecreatetruecolor(60, 30);
$bg = imagecolorallocate($im, 62, 118, 179); //background
$fg = imagecolorallocate($im, 0, 0, 0);//text
imagefill($im, 0, 0, $bg);
imagestring($im, 5, 5, 5, $code, $fg);
header("Cache-Control: no-cache, must-revalidate");
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
?>