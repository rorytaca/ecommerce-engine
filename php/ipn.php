<!-- Controller for IPNs for PayPal gateway -->

<?php
    // tell PHP to log errors to ipn_errors.log in this directory
    ini_set('log_errors', true);
    ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');

    // intantiate the IPN listener
    include('ipnlistener.php');
    $listener = new IpnListener();

    // tell the IPN listener to use the PayPal test sandbox
    $listener->use_sandbox = true;
    
    // try to process the IPN POST
    try {
        $listener->requirePostMethod();
        $verified = $listener->processIpn();
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit(0);
    }

    // TODO: Handle IPN Response here
    if ($verified) {
    
        $errmsg = '';   // stores errors from fraud checks
        
        // 1. Make sure the payment status is "Completed" 
        if ($_POST['payment_status'] != 'Completed') { 
            // simply ignore any IPN that is not completed
            mail('rorytaca@gmail.com', 'Incomplete IPN', $listener->getTextReport());
            exit(0); 
        }
    
        // 2. Make sure seller email matches your primary account email.
        //if ($_POST['receiver_email'] != 'Godleehcc@gmail.com') {
          //  $errmsg .= "'receiver_email' does not match: ";
            //$errmsg .= $_POST['receiver_email']."\n";
        //}
    
        // 3. Make sure the amount(s) paid match
        //if ($_POST['mc_gross'] != '9.99') {
          //  $errmsg .= "'mc_gross' does not match: ";
            //$errmsg .= $_POST['mc_gross']."\n";
        //}
        
        // 4. Make sure the currency code matches
        if ($_POST['mc_currency'] != 'USD') {
            $errmsg .= "'mc_currency' does not match: ";
            $errmsg .= $_POST['mc_currency']."\n";
        }
    
    
        // 5. Ensure the transaction is not a duplicate.
        include('config.php');
        
        //connect with PDO
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            $errmsg .= 'connetion fail';  
        }
    
        $txn_id = $_POST['txn_id'];
        $sql = "SELECT * FROM orders WHERE txn_id = '$txn_id'";
        $results = $conn->query($sql);
                
        if($results->rowCount()>0) {
            $errmsg .= "'txn_id' has already been processed: ".$_POST['txn_id']."\n";
        }
        
        if (!empty($errmsg)) {
        
            // manually investigate errors from the fraud checking
            $body = "IPN failed fraud checks: \n$errmsg\n\n";
            $body .= $listener->getTextReport();
            mail('rorytaca@gmail.com', 'IPN Fraud Warning', $body);
            
        } else {
            // add this order to a table of completed orders
            $payer_email = $_POST['payer_email'];
            $mc_gross = $_POST['mc_gross'];
            
            $new_sql = "INSERT INTO `orders` (txn_id, payer_email, mc_gross) VALUES ('".$txn_id."','".$payer_email."',$mc_gross)";

            $stmt = $conn->prepare($new_sql);
            $stmt->execute();
        
            // send user an email with a link to their digital download
            $to = filter_var($_POST['payer_email'], FILTER_SANITIZE_EMAIL);
            $subject = "Your order confirmation from Higher Class Clothing";
            $email_body = "Confirming that we received your order. Your order will be processed and shipped immediately! \n Order Information: ";
            
            $num_items = $_POST['num_cart_items'];
            $n = 1;
            
            //For each item in cart
            //while ($n <= $num_items) {
                 
                //Update SQL Inventory: remove each item qty
                $item_name = $_POST['item_name'.$n];
                //$item_size = $_POST['os1_'.$n];
                $item_size = $_POST['item_number'.$n];
                
                //may need to change to amount_x
                $item_price = $_POST['mc_gross'.$n];
                
                $email_body .= " Item: ".$item_name." Price: ".$item_price;
                
                $newest_sql =  "UPDATE product_sizes_inventory
                                INNER JOIN products ON products.product_id = product_sizes_inventory.product_id
                                SET product_sizes_inventory.".$item_size." = product_sizes_inventory.".$item_size." - 1
                                WHERE products.name = '".$item_name."'";
                                
                $stmt = $conn->prepare($newest_sql);
                $stmt->execute();
            
                $n++;
            //}
        
            mail('rorytaca@gmail.com', "Thank you for your order",  $email_body);
            mail('rorytaca@gmail.com', "Order received",  $email_body);

        }
    
    } else {
        // manually investigate the invalid IPN
        mail('rorytaca@gmail.com', 'Invalid IPN', $listener->getTextReport());
    }



?>