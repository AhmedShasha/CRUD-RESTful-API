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

// set ID property of Image to be edited

// set image name values
$fileid  =  $_REQUEST['id'];
$fileName  =  $_FILES['sendimage']['name'];
$tempPath  =  $_FILES['sendimage']['tmp_name'];
$fileSize  =  $_FILES['sendimage']['size'];
// update the Image
if (array_key_exists('sendimage', $_FILES) && array_key_exists('id', $_REQUEST)) {
    $filePath = 'uploads/';

    $img->read_img($_REQUEST['id']);
    $oldName = $img->image;
    // die($filePath.$oldName);

    //   File extentions
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newName = time() .  "." . $fileExt;
    $allFile = $filePath . $newName;
    // Valid image extensions
    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
    if (in_array($fileExt, $valid_extensions)) {
        // Check file not exsit

        if ($fileSize < 5000000) {
            // Move Upload file 
            unlink($filePath . $oldName);
            move_uploaded_file($tempPath, $allFile);
            // create the product

            if ($img->update($newName)) {

                // set response code - 200 ok
                http_response_code(200);

                // tell the user
                echo json_encode(array("message" => "Image was updated."));
            }

            // if unable to update the Image, tell the user
            else {

                // set response code - 503 service unavailable
                http_response_code(503);

                // tell the user
                echo json_encode(array("message" => "Image not found."));
            }
        } else {
            // set response code - 400 bad request
            http_response_code(400);

            // tell the user
            echo json_encode(array("message" => "Sorry, This file is more than 5MB", "status" => false));
        }
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
    echo json_encode(array("message" => "Unable to update image. Data is incomplete.", "status" => false));
}
