<?php
    include_once('cart_control.php');
    
    //Process which call it was - execute function as such.
    if($_SERVER['REQUEST_METHOD']=="GET") {
        
        $function = $_GET['call'];
        
            if ($function == "addToCart") {
            
                $id = $_GET['id'];
                $qty = $_GET['qty'];
                $size = $_GET['size'];
            
                addItem($id,$qty,$size);
                
            } else if ($function == "setSessionID") {
                
                checkSessionID();

            } else if ($function == "removeCartItem") {
                
                $prod_id = $_GET['prod_id'];
                
                removeItem($prod_id);
                
            } else if ($function == "clearCart") {
                clearCart();
            }
            
    } else {
        echo 'fail';
        break;
    }
    
    

?>