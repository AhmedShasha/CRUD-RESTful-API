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

$all = $img->readAll(); // select data from DB
$count = $all->rowCount();

$filePath = 'uploads/';

// check if more than 0 record found
if ($count > 0) {

    // images array
    $images_arr = array();
    $images_arr["records"] = array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $all->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['image'] to
        // just $image only
        extract($row);

        $images_item = array(
            "id" => $id,
            "image" => $filePath.$image,
            
        );

        array_push($images_arr["records"], $images_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show images data in json format
    echo json_encode($images_arr);
}else{
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no images found
    echo json_encode(
        array("message" => "No images found.")
    );
}
  
// no images found will be here