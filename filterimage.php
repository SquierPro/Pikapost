<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('error_reporting',  E_ALL);


function filter($uploadfile, $out, $type, $value=false){

  $img = imagecreatefrompng($uploadfile);
  if($type=='gray') {
        imagefilter($img, IMG_FILTER_GRAYSCALE);
  }elseif($type=='negative') {
        imagefilter($img, IMG_FILTER_NEGATE);
  }elseif($type=='color') {
        if(strlen($value) == 3) {
           $r = hexdec(substr($value,0,1).substr($value,0,1));
           $g = hexdec(substr($value,1,1).substr($value,1,1));
           $b = hexdec(substr($value,2,1).substr($value,2,1));
        } else {
           $r = hexdec(substr($value,0,2));
           $g = hexdec(substr($value,2,2));
           $b = hexdec(substr($value,4,2));
        }
        imagefilter($img, IMG_FILTER_COLORIZE, $r, $g, $b);
  }elseif($type=='bright') {
	$x=imagesx($img);
	$y=imagesy($img);
	$imageOut = imagecreatetruecolor($x, $y);
	if($value>0) $bg = imagecolorallocatealpha($imageOut, 255, 255, 255, 127-$value);
		else $bg = imagecolorallocatealpha($imageOut, 0, 0, 0, 127+$value);
	imagefill($imageOut, 0, 0, $bg);
	imagecopyresampled($img, $imageOut, 0, 0, 0, 0, $x, $y, $x, $y);
        imagedestroy($imageOut);

  }elseif($type=='blur') {
	imagefilter($img, IMG_FILTER_SELECTIVE_BLUR);
	imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);
	imagefilter($img, IMG_FILTER_CONTRAST, -15);
	imagefilter($img, IMG_FILTER_SMOOTH, -2);
  }elseif($type=='antique') {
	imagefilter($img, IMG_FILTER_BRIGHTNESS, 0);
	imagefilter($img, IMG_FILTER_CONTRAST, -30);
	imagefilter($img, IMG_FILTER_COLORIZE, 75, 50, 25);
  }elseif($type=='blackwhite') {
	imagefilter($img, IMG_FILTER_GRAYSCALE);
	imagefilter($img, IMG_FILTER_BRIGHTNESS, 10);
	imagefilter($img, IMG_FILTER_CONTRAST, -20);
  }elseif($type=='boost') {
	imagefilter($img, IMG_FILTER_CONTRAST, -35);
	imagefilter($img, IMG_FILTER_COLORIZE, 25, 25, 25);
  }elseif($type=='sepia') {
	imagefilter($img, IMG_FILTER_GRAYSCALE);
	imagefilter($img, IMG_FILTER_BRIGHTNESS, -10);
	imagefilter($img, IMG_FILTER_CONTRAST, -20);
	imagefilter($img, IMG_FILTER_COLORIZE, 60, 30, -15);
  }elseif($type=='dreamy') {
	imagefilter($img, IMG_FILTER_BRIGHTNESS, 20);
	imagefilter($img, IMG_FILTER_CONTRAST, -35);
	imagefilter($img, IMG_FILTER_COLORIZE, 60, -10, 35);
	imagefilter($img, IMG_FILTER_SMOOTH, 7);
  }elseif($type=='velvet') {
	imagefilter($img, IMG_FILTER_BRIGHTNESS, 5);
	imagefilter($img, IMG_FILTER_CONTRAST, -25);
	imagefilter($img, IMG_FILTER_COLORIZE, -10, 45, 65);
  }elseif($type=='chrome') {
	imagefilter($img, IMG_FILTER_BRIGHTNESS, 15);
	imagefilter($img, IMG_FILTER_CONTRAST, -15);
	imagefilter($img, IMG_FILTER_COLORIZE, -5, -10, -15);
  }elseif($type=='lift') {
	imagefilter($img, IMG_FILTER_BRIGHTNESS, 50);
	imagefilter($img, IMG_FILTER_CONTRAST, -25);
	imagefilter($img, IMG_FILTER_COLORIZE, 75, 0, 25);
  }elseif($type=='canvas') {
	imagefilter($img, IMG_FILTER_BRIGHTNESS, 25);
	imagefilter($img, IMG_FILTER_CONTRAST, -25);
	imagefilter($img, IMG_FILTER_COLORIZE, 50, 25, -35);
  }elseif($type=='vintage') {
	imagefilter($img, IMG_FILTER_BRIGHTNESS, 15);
	imagefilter($img, IMG_FILTER_CONTRAST, -25);
	imagefilter($img, IMG_FILTER_COLORIZE, -10, -5, -15);
	imagefilter($img, IMG_FILTER_SMOOTH, 7);
  }elseif($type=='monopin') {
	imagefilter($img, IMG_FILTER_GRAYSCALE);
	imagefilter($img, IMG_FILTER_BRIGHTNESS, -15);
	imagefilter($img, IMG_FILTER_CONTRAST, -15);
  }elseif($type=='edge') {
	imagefilter($img, IMG_FILTER_EDGEDETECT);
  }elseif($type=='emboss') {
	imagefilter($img, IMG_FILTER_EMBOSS);
  }elseif($type=='removal') {
	imagefilter($img, IMG_FILTER_MEAN_REMOVAL);
  }elseif($type=='pixel') {
	imagefilter($img, IMG_FILTER_PIXELATE, 5, true);
  }elseif($type=='shakal') {
	imagefilter($img, IMG_FILTER_MEAN_REMOVAL);
        imagejpeg($img,$out, 0);
        imagedestroy($img);
        $img = imagecreatefromjpeg($out);
  }
        imagepng($img,$out, 0);
        imagedestroy($img);
}



$source='./img/filter.png';

$type='gray';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='negative';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='color';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type, 'ff0000');
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='bright';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type, -100);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='blur';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='antique';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='blackwhite';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='boost';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='sepia';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='dreamy';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='velvet';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='chrome';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='lift';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='canvas';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='vintage';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='monopin';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='edge';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='emboss';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='removal';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='pixel';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';

$type='shakal';
$output='./img/filter_'.$type.'.png';
filter($source,$output,$type);
echo $type.'<br>';
echo '<img src="'.$output.'?rand='.rand(0,1000).'"/>';
?>