<?php

use thiagoalessio\TesseractOCR\TesseractOCR;

include 'vendor\autoload.php';

if (empty($_FILES)) {
    die('Please upload the file');
}
if (!file_exists("uploads")) {
    mkdir('uploads', 0777);
}
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = false;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if ($check !== false) {
    //echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = true;
}
// Check if file already exists
if (file_exists($target_file)) {
    unlink($target_file);
    $uploadOk = true;
}
// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    echo "Sorry, only JPG, JPEG, PNG files are allowed.";
    $uploadOk = false;
}
// Check if $uploadOk is set to 0 by an error
if (!$uploadOk) {
    echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
if ($uploadOk) {
    $data = (new TesseractOCR($target_file))
            ->lang('eng')
            ->run();
    if ($GLOBALS['generateFile']) {
        file_put_contents('uploads/text.txt', $data);
    } else {
        echo $data;
    }

}
