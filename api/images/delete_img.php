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

// die(var_dump($_GET['id']));

// Set image id to delete it 
$img->id = $_GET['id'];
$filePath = 'uploads/';

// delete the product

$img->read_img($_GET['id']);

$fileName = $img->image;
$fileId = $img->id;
if ($img->id != null) {
    $img_arr = array(
        'id' => $img->id,
        'image' => $img->image,
    );
    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($img_arr);
} else {
    // set response code -404 Not Found
    http_response_code(404);

    // tell the user product does not exist
    echo json_encode(array("message" => "Image not found"));
}
if($img->delete($_GET['id'])){
  
    unlink($filePath.$fileName);
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Image was deleted successfully."));
}
  
// if unable to delete the product
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to delete image."));
}
