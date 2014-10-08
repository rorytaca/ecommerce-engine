<?php

    function checkSessionID() {
        global $session_id;
        
        include('config.php');
        //Check for session id cookie
        if (!isset($_COOKIE['session_id'])) {
            //No cookie. Check set of active session id's and create one.
            $check = false;
            while ($check != true) {
                //Generate ID
                $checkID = rand(1000000,2000000);
                
                //Check if it exists in table
                try {
                    $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch(PDOException $e) {
                    echo 'connection fail';
                }
                
                $query = "SELECT * FROM `active_sessions` WHERE session_id = $checkID";
                $results = $conn->query($query);
                
                
                
                if(!$results->rowCount()>0){
                    $check = true;
                    $new_query = "INSERT INTO `active_sessions` (session_id) VALUES($checkID)";
                    $stmt = $conn->prepare($new_query);
                    $stmt->execute();
                    
                }
                print $checkID;
            }
            
        } else {
            //Cookie already set. Grab cookie from browser.
            $session_id = $_COOKIE['session_id'];
            print $session_id;
        }
        
        
    }
    
    function createCartDB() {
        $session_id = $_COOKIE['session_id'];
        include('config.php');
        //connect with PDO
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'connetion fail';
        }
        
        try {
            //Prepare new table statement and process
            $stmt = "CREATE TABLE IF NOT EXISTS `ecommerce-sql-table`.`shopping_cart_".$session_id."` (
                    `id` INT NOT NULL AUTO_INCREMENT ,
                    `prod_id` INT NOT NULL ,
                    `name` TEXT NOT NULL ,
                    `price` DOUBLE( 6, 2 ) NOT NULL ,
                    `qty` INT NOT NULL ,
                    `size` VARCHAR( 16 ) NOT NULL ,
                    PRIMARY KEY (  `id` )
                    ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_bin";
            $conn->exec($stmt);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
    }
    
    function checkDBForCart() {
        $session_id = $_COOKIE['session_id'];
        include('config.php');
        //connect with PDO
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'connetion fail';
        }
    
        //check if table exists
        $query = "SHOW TABLES LIKE 'shopping_cart_".$session_id."'";
        $results = $conn->query($query);
            
        if($results->rowCount()>0){
            return true;
        } else {
            return false;
        }
        
    }
    
    function addItem($product_id, $qty, $size) {
        $session_id = $_COOKIE['session_id'];
        include('config.php');
        
        //Check if cart exists, call createCart's table function

        createCartDB();
                
        //Connect to database
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'connection fail';
        }
        
        //look up price and item name from prod_id
        $query = "SELECT name,price FROM products WHERE id=$product_id";
        $data = $conn->query($query);
        $array = $data->fetch(PDO::FETCH_ASSOC);
        
        //send insert message  
        $query = "INSERT INTO `shopping_cart_".$session_id."` (prod_id, qty, size, name, price) VALUES ('".$product_id."','".$qty."','".$size."','".$array['name']."','".$array['price']."')";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
    }
    
    function removeItem($product_id) {
        $session_id = $_COOKIE['session_id'];
        include('config.php');

        //Connect to database
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'connection fail';
        }
        
        $query = "DELETE FROM `shopping_cart_".$session_id."` WHERE prod_id=".$product_id;
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
    }
    
    function clearCart() {
        $session_id = $_COOKIE['session_id'];
        include('config.php');

        //Connect to database
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'connection fail';
        }
        
        $query = "TRUNCATE TABLE `shopping_cart_".$session_id."`";
        $stmt = $conn->prepare($query);
        $stmt->execute();
    }
    
    function fetchCartNumberOfItems() {
        $session_id = $_COOKIE['session_id'];
        include('config.php');
        //Connect to database
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'connection fail';
        }
        
        //Fetch Shopping Cart Items
        $query = "SELECT * FROM `shopping_cart_".$session_id."`";
        $data = $conn->query($query);
        
        return $data->rowCount();
    }
    
    function fetchCartHTML() {
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
                foreach ($data as $row) {
                    //Print elements of row: Name, Qty, Price, Remove Option
                    print'
                         <div class="item">
                            <span class="hidden id">'.$row['prod_id'].'</span>
                            <a href="product.php?prod='.$row['prod_id'].'" class="name">'.$row['name'].'</a>
                            <span class="size">Size: '.$row['size'].'</span>
                            <span class="quantity">Quantity: '.$row['qty'].'</span>
                            <span class="price">$'.$row['price'].'</span>
                            
                            <div class="remove">X</div>
                    
                        </div>
                    ';
            
                }
            }
        } else {
            
        }
    }
    
    function getCartTotalPrice() {
        $session_id = $_COOKIE['session_id'];
        include('config.php');
        //Connect to database
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'connection fail';
        }
        
        $query = "SELECT price FROM `shopping_cart_".$session_id."`";
        $data = $conn->query($query);
        
        $price = 0.00;
        if (!count($data)) {

            return $price;
        } else {
            
            foreach($data as $row) {
                
                $price = $price + (double)$row['price'];
            }
            
            return $price;
        }
        
        
    }


?>