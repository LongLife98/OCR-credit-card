<!DOCTYPE html>
<html>
<body>

<?php

require_once "vendor/autoload.php";
use thiagoalessio\TesseractOCR\TesseractOCR;

function ocr($fpath){

  //$filepath = "images/test.jpg";
  $filepath   =  $fpath;
  $tesseractInstance = new TesseractOCR($filepath);

  $result = $tesseractInstance->run();


  $pattern_card_number = "([0-9]+( [0-9]+)+)";
  $pattern_card_date = "#[0-9]*\.[0-9]+#";
  $pattern_card_cvv = "#[WV].[0-9]{3}#";

  preg_match($pattern_card_number, $result, $number,);
  preg_match($pattern_card_date, $result, $date,);
  preg_match($pattern_card_cvv, $result, $cvv,);

  $card_number = str_replace(" ","",$number[0]);
  $card_date = substr($date[0],0,2).substr($date[0],5,2);                
  $card_cvv = substr($cvv[0],2,3);   


  $card = "['".$card_number."','".$card_date."','".$card_cvv."']";
  //echo $card;
  //echo ("<br>");
  return $card;
}


    $total = count($_FILES['fileToUpload']['name']);
    $target_dir = "images/";
    $results = array();

    for( $i=0 ; $i < $total ; $i++ ) {

        $uploadOk = 1;

        if(isset($_POST["submit"])) {
          $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][$i]);
          if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
          } else {
            echo "File is not an image.";
            $uploadOk = 0;
          }
        }

        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$i]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


        // Check if file already exists
      if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
      }

      // Check file size
      if ($_FILES["fileToUpload"]["size"][$i] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
      }

      // Allow certain file formats
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
      }

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
      // if everything is ok, try to upload file
      } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file)) {
          echo $i.") ". "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"][$i])). " has been uploaded. <br> ";
          $results[$i] = ocr($target_file);
          unlink($target_file);

        } else {
          echo "Sorry, there was an error uploading your file.";
        }

      }
    }
    echo "<br>";
    echo "cards:";
    echo "<br>";
    $arrlength = count($results);
    for($x = 0; $x < $arrlength; $x++) {
      echo $results[$x];
      echo "<br>";
    }

?>

</body>
</html>