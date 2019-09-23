<?php

function saveBase64ImagePng($base64Image, $imageDir) {
    //set name of the image file

    $fileName =  'test.png';

    $base64Image = trim($base64Image);
    $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
    $base64Image = str_replace('data:image/jpg;base64,', '', $base64Image);
    $base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
    $base64Image = str_replace('data:image/gif;base64,', '', $base64Image);
    $base64Image = str_replace(' ', '+', $base64Image);

    $imageData = base64_decode($base64Image);
    //Set image whole path here 
    $filePath = $imageDir . $fileName;


    file_put_contents($filePath, $imageData);

}