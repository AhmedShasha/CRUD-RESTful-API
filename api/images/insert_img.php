<?php

//  Require header 
header("Content-Type: application/json");
header("Acess-Control-Allow-Origin: *");
header("Acess-Control-Allow-Methods: POST");
header("Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Methods, Authorization");

// get database connection
include_once '../../config/Database.php';
  
// instantiate images object
include_once '../../Model/Images.php';

// Get DB connection 
$connetion = new Database();
$database = $connetion->getConnection();
// Get Model 

$img = new Images($database);

// Get posted data
$data = json_decode(file_get_contents("php://input"), true); // collect input parameters and convert into readable format

// die(var_dump($_FILES));

// Intialize information of image 

$fileName  =  $_FILES['sendimage']['name'];
$tempPath  =  $_FILES['sendimage']['tmp_name'];
$fileSize  =  $_FILES['sendimage']['size'];

// die(var_dump($fileName));

// Make sure data is not empty
if (
    array_key_exists('sendimage', $_FILES)
){
    // Upload path file 
    $filePath = 'uploads/';

    //   File extentions
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newName = time() .  "." . $fileExt;
    $allFile = $filePath . $newName;
    // Valid image extensions
    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

    // Allow valid image file formats
    if (in_array($fileExt, $valid_extensions)) {
        // Check file not exsit

        // if (!file_exists($allFile)) {

        if ($fileSize < 5000000) {
            // Move Upload file 
            move_uploaded_file($tempPath, $allFile);
            // create the product
            // die(var_dump($img->insert($newName)));
            if ($img->insert($newName)) {
                // set response code - 201 created
                http_response_code(201);
                echo json_encode(array("message" => "Image was inserted successfully."));
            } else {
                // set response code - 400 bad request
                http_response_code(400);
                // tell the user
                echo json_encode(array("message" => "Sorry, Can't story this image", "status" => false));
            }
        } else {
            // set response code - 400 bad request
            http_response_code(400);

            // tell the user
            echo json_encode(array("message" => "Sorry, This file is more than 5MB", "status" => false));
        }
        // } else {
        //     // set response code - 400 bad request
        //     http_response_code(400);

        //     // tell the user
        //     echo json_encode(array("message" => "Sorry, This file is already exsit", "status" => false));
        // }
    } else {
        // set response code - 400 bad request
        http_response_code(400);

        // tell the user
        echo json_encode(array("message" => "Sorry, Only JPG, JPEG, PNG, GIF", "status" => false));
    }
} else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to insert image. Data is incomplete.", "status" => false));
}
