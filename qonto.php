<!DOCTYPE html>
<html>
<body>

<?php

ini_set('max_execution_time', 123456);
require_once "vendor/autoload.php";
use thiagoalessio\TesseractOCR\TesseractOCR;
use Gregwar\Image\Image;

function ocr($fpath){

  //$filepath = "images/test.jpg";
  $filepath   =  $fpath;
  $tesseractInstance = new TesseractOCR($filepath);

  $result = $tesseractInstance->run();

  //echo $result;

  $pattern_card_number = "([0-9]{4,4}\s[0-9]{4,4}\s[0-9]{4,4}\s[0-9]{4,4})";
  $pattern_card_date = "#[0-9]*\/[0-9]+#";
  $pattern_card_cvv = "(\s[0-9]{3}\s)";
  
  preg_match($pattern_card_number, $result, $number,);
  preg_match($pattern_card_date, $result, $date,);
  preg_match($pattern_card_cvv, $result, $cvv,);

  //echo $cvv[0];

  $card_number = str_replace(" ","",$number[0]);
  $card_date = str_replace("/","",$date[0]);             
  $card_cvv = $cvv[0]; 


  $card = "['".$card_number."','".$card_date."','".$card_cvv."'],";
  echo $card;
  echo ("<br>");
  return $card;
}

$dir = 'images';
$files = scandir($dir);

echo('<br>');

if (($key = array_search('.', $files)) !== false) {
  unset($files[$key]);
}
if (($key = array_search('..', $files)) !== false) {
  unset($files[$key]);
}

print_r($files);
echo('<br><br><br>');
$limit = count($files);

for($x = 2; $x < $limit+2; $x++) {
  $temp =  "images/".$files[$x];
  //$img = Image::open($temp)->resize(576, 1280, 'transparent')->zoomCrop(576, 400, 'transparent', 'center', 'center')->contrast(-100);//->save('dkekc.jpg');
  $img = Image::open($temp)->resize(576, 1280, 'transparent')->zoomCrop(576, 400, 'transparent', 'center', 'center')->contrast(-100);//->save('dkekc.jpg');
  ocr($img);
}


?>

</body>
</html>