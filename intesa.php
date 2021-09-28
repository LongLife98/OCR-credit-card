<!DOCTYPE html>
<html>
<body>

<?php

require_once "vendor/autoload.php";
use thiagoalessio\TesseractOCR\TesseractOCR;
use Gregwar\Image\Image;

function ocr($fpath){

  //$filepath = "images/test.jpg";
  $filepath   =  $fpath;
  $tesseractInstance = new TesseractOCR($filepath);

  $result = $tesseractInstance->run();
  //echo $result;

  $pattern_card_number = "([0-9]{4,4}\s[0-9]{4,4}\s[0-9]{4,4}\s[0-9]{4,4}\s)";
  $pattern_card_date = "#[0-9]*\.[0-9]+#";
  //$pattern_card_cvv = "#[WV].[0-9]{3}#";
  $pattern_card_cvv = "(\s[0-9]{3}\s)";

  preg_match($pattern_card_number, $result, $number,);
  preg_match($pattern_card_date, $result, $date,);
  preg_match($pattern_card_cvv, $result, $cvv,);

  $card_number = preg_replace('/\s+/', '' ,$number[0]);
  $card_date = preg_replace('/\s+/', '', substr($date[0],0,2).substr($date[0],5,2));                 
  $card_cvv = preg_replace('/\s+/', '', $cvv[0]); 


  $card = "['".$card_number."', '".$card_date."', '".$card_cvv."'],";
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
  //$img = Image::open($temp)->zoomCrop(800, 1200, 'transparent', 'center', 'center');//->save('dkekc.jpg');
  //$img = Image::open($temp)->zoomCrop(576, 650, 'transparent', 'center', 'center');//->save('dkekc.jpg');
  ocr($temp);
}


?>

</body>
</html>