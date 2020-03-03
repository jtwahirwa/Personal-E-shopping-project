<?php
// start session
session_start();
 
// connect to database
include 'config/database.php';
 
// include objects
include_once "objects/product.php";
include_once "objects/product_images.php";
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize objects
$product = new Product($db);
$product_image = new ProductImage($db);
 
// set page title
$page_title="Detail";
 
// include page header html
include 'layout_header.php';
 
 $action = isset($_GET['action']) ? $_GET['action'] : "";
 
echo "<div class='col-md-12'>";
    if($action=='removed'){
        echo "<div class='alert alert-info'>";
            echo "Le produit a été retiré de votre panier!";
        echo "</div>";
    }
 
    else if($action=='quantity_updated'){
        echo "<div class='alert alert-info'>";
            echo "La quantité du produit a été mise à jou!";
        echo "</div>";
    }
echo "</div>";


if(count($_SESSION['cart'])>0){
 
    // get the product ids
    $ids = array();
    foreach($_SESSION['cart'] as $id=>$value){
        array_push($ids, $id);
    }
 
    $stmt=$product->readByIds($ids);
 
 	
    $total=0;
    $item_count=0;
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $quantity=$_SESSION['cart'][$id]['quantity'];
        $sub_total=$price*$quantity;
 
        // =================

        $product_image->product_id=$id;
        $stmt_product_image=$product_image->readFirst();


            //echo '<pre>';
            //print_r($stmt_product_image);
            
 
            


                echo "<div class='cart-row'>";
                    echo "<div class='col-md-2'><img src='uploads/images/{$stmt_product_image['name']}' class='w-100-pct' />";
                echo "</div>";
            


            echo "<div class='col-md-5'>";
 
                echo "<div class='product-name m-b-10px'>{$name}</div>";
 
                // update quantity
                echo "<form class='update-quantity-form'>";
                    echo "<div class='product-id' style='display:none;'>{$id}</div>";
                    echo "<div class='input-group'>";
                        echo "<input type='number' name='quantity' value='{$quantity}' class='form-control cart-quantity' min='1' />";
                            echo "<span class='input-group-btn'>"; ?>
                                 "<button class='btn btn-labeled btn-info update-quantity' type='submit'>
                                <span class="btn-label"><i class="glyphicon glyphicon-refresh"></i></span></button>"
                           <?php echo "</span>";
                    echo "</div>";
                echo "</form>";
 
                // delete from cart
                echo "<a href='remove_from_cart.php?id={$id}' class='btn btn-default'>";

                echo '
                <i class="glyphicon glyphicon-trash"></i>';


                    //echo "Delete";
                echo "</a>";
            echo "</div>";
 
            echo "<div class='col-md-3'>";
                echo "&#36;" . number_format($sub_total, 2, '.', ',') ;
            echo "</div>";
        echo "</div>";
        // =================
 
        $item_count += $quantity;
        $total+=$sub_total;

    
}
 
    echo "<div class='col-md-6'></div>";
    echo "<div class='col-md-4'>";
        echo "<div class='cart-row'>";
            echo "<h4 class='m-b-10px'>Total ({$item_count} items)</h4>";
            echo "<h4>&#36;" . number_format($total, 2, '.', ',') . "</h4>";
            echo "<a href='checkout.php' class='btn btn-success m-b-10px'>";
                echo "<span class='glyphicon glyphicon-shopping-cart'></span> Passer au payement";
            echo "</a>";
        echo "</div>";
    echo "</div>";
 
}



 
// no products were added to cart
else{
    echo "<div class='col-md-12'>";
        echo "<div class='alert alert-danger'>";
            echo "Panier vide !";
        echo "</div>";
    echo "</div>";
}
 
// layout footer 
include 'layout_footer.php';
?>