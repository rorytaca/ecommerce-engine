<?php   
   
    function generatePayPalForms() {
        $session_id = $_COOKIE['session_id'];
        include('config.php');
        //Connect to database
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'connection fail';
        }
        
        if (checkDBForCart()) {
            //Fetch Shopping Cart Items
            $query = "SELECT * FROM `shopping_cart_".$session_id."`";
            $data = $conn->query($query);
            if ( count($data) ) {
               print    '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                            <input type="hidden" name="cmd" value="_cart">
                            <input type="hidden" name="upload" value="1">
                            <input type="hidden" name="business" value="">';
                $i = 1;
                foreach ($data as $row) {
                    //Print elements of row: Name, Qty, Price, Remove Option
                    print'
                            <input type="hidden" name="item_name_'.$i.'" value="'.$row['name'].'">
                            <input type="hidden" name="on1_'.$i.'" value="size">
                            <input type="hidden" name="os1_'.$i.'" value="'.$row['size'].'">
                            <input type="hidden" name="item_number_'.$i.'" value="'.$row['size'].'">
                            <input type="hidden" name="amount_'.$i.'" value="'.$row['price'].'">
                            <input type="hidden" name="shipping_'.$i.'" value="2.00">


                    ';
                    $i++;
                }
                
                print      '<input type="hidden" name="return" value="http://ecommerce.com/cart.php">
                            <input type="hidden" name="notify_url" value="http://ecommerce.com/php/ipn.php">
                            <input type="submit" value="Proceed to Checkout" class="checkout">
                        </form>';
            }
        } else {
            
        }
    }
    
?>