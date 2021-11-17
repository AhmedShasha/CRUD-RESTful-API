<?php
class Images
{
    // DB Connection and table name 
    private $conn;
    private $table_name = "`images`";


    // All Objects 
    public $id;
    // public $name;
    public $image;
    public $checkIdQuery;
    public $checkId;

    // Constractor DB connection here 
    function __construct($db)
    {
        $this->conn = $db;
    }

    function readAll()
    {
        // Query 
        $query = "SELECT * FROM " . $this->table_name . "";
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function insert($newName)
    {
        // die(var_dump($newName));
        // Insert Query

        $this->image = $newName;

        $query = "INSERT INTO " . $this->table_name . " (image) VALUES ('" . $this->image . "')";
        // Prepare query
        $stmt = $this->conn->prepare($query);

        // Sanitize
        // $this->name = htmlspecialchars(strip_tags($this->name));
        $this->image = htmlspecialchars(strip_tags($this->image));

        // Bind Value 
        // $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image", $this->image);

        // execute query
        if ($stmt->execute()) {
            // $stmt->fetch();
            return true;
        }

        return false;
    }

    function read_img($id)
    {
        // query to read single record
        $query = "SELECT * FROM " . $this->table_name . " WHERE id=" . $id . "";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of image to get
        // $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // die(var_dump($row));

        // set values to object properties
        $this->id = $row['id'];
        $this->image = $row['image'];
    }

    function delete($id)
    {
        // Delete query 
        $query = "DELETE FROM " . $this->table_name . " WHERE id = " . $id . "";

        // prepare query 
        $stmt = $this->conn->prepare($query);

        // $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind id of record to delete it 
        // $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function update($newName)
    {

        $this->image = $newName;

        /* 
            $this->checkIdQuery = "SELECT id FROM " . $this->table_name . "WHERE id = " . $_REQUEST['id'] . "";

            $stmtCheck = $this->conn->prepare($this->checkIdQuery);

            $stmtCheck->execute();

            $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            $this->checkId = $row['id'];

            if ($this->checkId == $_REQUEST['id']) {
        */
    
        // Update Query 
        $query = "UPDATE " . $this->table_name . "SET image=:image WHERE id=:id";

        //prepare statment 
        $stmt = $this->conn->prepare($query);

        //Sanitize
        $this->id = htmlspecialchars(strip_tags($_REQUEST['id']));
        $this->image = htmlspecialchars(strip_tags($this->image));
        // Bind new value 
        $stmt->bindParam(':id', $_REQUEST['id']);
        $stmt->bindParam(':image', $this->image);

        if ($stmt->execute()) {
            return true;
        }
        return false;
       
        /* 
            }else{
            return false;
            }
        */
    }
}
