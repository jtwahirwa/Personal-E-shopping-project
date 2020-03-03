<?php
// 'product image' object
class ProductImage{
 
    // database connection and table name
    private $conn;
    private $table_name = "product_images";
 
    // object properties
    public $id;
    public $product_id;
    public $name;
    public $timestamp;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // read the first product image related to a product
function readFirst(){
 
    // select query
    $query = "SELECT id, product_id, name
            FROM " . $this->table_name . "
            WHERE product_id = ?
            ORDER BY name DESC
            LIMIT 0, 1";
 
    // prepare query statement
 try {
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind prodcut id variable
    $stmt->bindParam(1, $this->product_id);
    $stmt->execute();
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

        return $result;
      
    }
    //$stmt = null;
  }
  catch (PDOException $e) {
    print $e->getMessage();
  }
}





// used when filling up the update product form
function getById(){
 
    // query to read single record
    $query = "SELECT
                p.name as product_name, i.name,  i.created, i.modified
            FROM
                " . $this->table_name . " i
                LEFT JOIN
                    product p
                        ON i.product_id = i.id
            WHERE
                p.id = ?
            LIMIT
                0,1";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->id);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->product_id = $row['product_id'];
    $this->name = $row['name'];
    $this->timestamp = $row['created'];
    $this->timestamp = $row['modified'];

}



// read all product image related to a product
function readByProductId(){
 
    // select query
    $query = "SELECT id, product_id, name
            FROM " . $this->table_name . "
            WHERE product_id = ?
            ORDER BY name ASC";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->product_id=htmlspecialchars(strip_tags($this->product_id));
 
    // bind prodcut id variable
    $stmt->bindParam(1, $this->product_id);
 
    // execute query
    $stmt->execute();
 
    // return values
    return $stmt;
}
}