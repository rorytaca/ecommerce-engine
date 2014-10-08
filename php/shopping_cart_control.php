<?php
    
    
    function check_cart() {
        $session_id = $_COOKIE['session_id'];
        
        if ($session_id) {
            return true;
        } else {
            return false;
        }
        
    }
    
    function fetch_cart_number() {
        
    }

    function add_item_to_cart($pid,$qty,$size) {
        
        //Check if cart exists by checking cookies. If session id exists, fetch id and add. Else create new table and add
        if (!check_cart()) {
            $session_id = create_new_cart();
        } else {
        
            $session_id = $_COOKIE['session_id'];
        }
        
        //$stmt =
            //'INSERT INTO `shopping_cart_'.$session_id.'` ()
            //';
    
    
    }
  
    function create_new_cart() {
        //Randomly generate new session id, check if a table exists
        $new_id = rand(1000000,2000000);
        
        //Set cookie
        setcookie('session_id',$new_id,time()+3600*24, '/', 'ecommerce.com', 0, false);
        
        include('config.php');
        //connect with PDO
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'connetion fail';
        }
    
        //check if random number table exists, reassign number and try again if it does.
        $check = false;
        while (!$check) {
            //$query = "SELECT * FROM `shopping_cart_".$new_id."`";
            $query = "SHOW TABLES LIKE 'shopping_cart_".$new_id."'";
            $results = $conn->query($query);
            //$result = $data->fetch(PDO::FETCH_ASSOC);
            
            if(!$results->rowCount()>0){$check = true;}
        }
        
        

        
        try {
            //Prepare new table statement and process
            $stmt = "CREATE TABLE  `ecommerce-sql-table`.`shopping_cart_".$new_id."` (
                    `id` INT NOT NULL ,
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

?>