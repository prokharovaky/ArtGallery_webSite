<?php
session_start();
$letters = 'abcdefghijklmnopqrstuvwxyz';
$digits = '123456789';
$caplen = 6;
$width = 300;
$height = 60;
$font = "../fonts/Black Angel Demo.ttf";
$fontsize = 47;
header('Content-type: image/png');
$im = imagecreatetruecolor($width, $height);
imagesavealpha($im, true);
$bg = imagecolorallocatealpha($im, 0, 0, 0, 127);
imagefill($im, 0, 0, $bg);
$img_arr = array("../img/back.png","../img/back.png","../img/back.png");
// Генерируем (выбираем) фон для капчи случайным образом
$img_fn = $img_arr[rand(0, sizeof($img_arr)-1)];
$im = imagescale(imagecreatefrompng($img_fn), 200, 60);
// Случайные линии под текстом
$linenum = rand(3, 7);
for ($i=0; $i<$linenum; $i++)
{
$color = imagecolorallocate($im, rand(0, 255), rand(0, 200), rand(0, 200));
imageline($im, rand(0, 10), rand(1, 60), rand(160, 200), rand(1, 60), $color);
}
//формирование текста капчи

$letter1 = $letters[rand(0, strlen($letters) - 1)];
$digit1 = $digits[rand(0, strlen($digits) - 1)];
$letter2 = $letters[rand(0, strlen($letters) - 1)];
$digit2 = $digits[rand(0, strlen($digits) - 1)];
$captcha_text = "{$letter1}{$digit1}+{$letter2}{$digit2}";
$captcha_answer = str_repeat($letter1, $digit1) . str_repeat($letter2, $digit2);
$_SESSION['captcha'] = $captcha_answer;

$x = 50;
$y = (int)($height - ($height - $fontsize) / 2);

$curcolor = imagecolorallocate($im, rand(0, 100), rand(0, 100), rand(0, 100));
imagettftext($im, $fontsize, rand(-10, 10), $x, $y, $curcolor, $font, $letter1);

$x += 20;
$curcolor = imagecolorallocate($im, rand(0, 100), rand(0, 100), rand(0, 100));
imagettftext($im, $fontsize-5, rand(-10, 10), $x, $y, $curcolor, $font, $digit1);

$x += 20;
$curcolor = imagecolorallocate($im, rand(0, 100), rand(0, 100), rand(0, 100));
imagettftext($im, $fontsize, rand(-10, 10), $x, $y, $curcolor, $font, '+');

$x += 20;
$curcolor = imagecolorallocate($im, rand(0, 100), rand(0, 100), rand(0, 100));
imagettftext($im, $fontsize, rand(-10, 10), $x, $y, $curcolor, $font, $letter2);

$x += 20;
$curcolor = imagecolorallocate($im, rand(0, 100), rand(0, 100), rand(0, 100));
imagettftext($im, $fontsize-5, rand(-10, 10), $x, $y, $curcolor, $font, $digit2);


// Опять линии, уже сверху текста
$linenum = rand(3, 7);
for ($i=0; $i<$linenum; $i++)
{
$color = imagecolorallocate($im, rand(0, 255), rand(0, 200), rand(0, 255));
imageline($im, rand(0, 10), rand(1, 60), rand(160, 200), rand(1, 60), $color);
}

imagepng($im);
imagedestroy($im);
?>