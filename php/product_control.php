<!-- Product Object and Functions -->
<?php
    
    /* fetch_product_html():
     *
     * Returns single product's html content. Called on product.php page.
     */

    function fetch_product_html($pid) {
        //include project variables
        include('config.php');
    
        //connect with PDO
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'connetion fail';
        }
    
        //prepare and send query
        $data = $conn->query("SELECT * FROM $product_database WHERE id=$pid");
        $array = $data->fetch(PDO::FETCH_ASSOC);

        echo'
            <img src="/images/product_images/'.$array['image'].'" alt="" id="product_image" />
            <div id="product_info">
                <h4>'.$array['name'].'</h4>
                <span id="product_price">$'.$array['price'].'</span>
                <p>'.$array['description'].'</p>
                <p class="hidden" id="prod_id">'.$array['product_id'].'</p>
            </div>
            
        ';
        
        $pid = $array['product_id']; 
        
        echo    '<span class="size_span">Size:</span><select id="size_select">';
            echo    '<option value="blank">-</option>';

        //Fetch sizes available for DB and create options
        $data2 = $conn->query("SELECT XS,S,M,L,XL FROM `product_sizes_inventory` WHERE product_id=".$pid);
        $array = $data2->fetch(PDO::FETCH_ASSOC);
        
        if ((int)$array['XS'] > 0) {
            echo    '<option value="XS">XS</option>';
        }
        
        if ((int)$array['S'] > 0) {
            echo    '<option value="S">S</option>';
        }
        
        if ((int)$array['M'] > 0) {
            echo    '<option value="M">M</option>';
        }
        
        if ((int)$array['L'] > 0) {
            echo    '<option value="L">L</option>';
        }

        if ((int)$array['XL'] > 0) {
            echo    '<option value="XL">XL</option>';
        }
        echo'
                </select>';
    }
    
    /* fetch_products_html():
     *
     * Returns set of product's html content. Called on categories.php page.
     * Passed a category in a string, results are filtered by this parameter in SQL.
     */
    function fetch_products_html($cat) {
        //include project variables
        include('config.php');
        
        //connect with PDO
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'connetion fail';  
        }
    
        //prepare and send query
        if ($cat == "all") {
            $data = $conn->query("SELECT * FROM $product_database");
        } else {
            $data = $conn->query("SELECT * FROM $product_database WHERE category='".$cat."'");
        }
        
        //process return data into html
        $n = 1;
        if ( count($data) ) {
            foreach ($data as $row) {                
                //print PHP data into HTML   
                echo '
                <a href="product.php?prod='.$row['id'].'" class="product">
                    
                    <img src="/images/product_images/'.$row['image'].'" alt="" class="product_image" />
                    <div class="product_info">
                        <span class="title">'.$row['name'].'</span>
                        <span class="price">$'.$row['price'].'</span>
                    </div>
                </a>
                ';
                $n++;
            }
        } else {
            echo 'no rows found';
        }
    }
    
    //Create Product
    function create_product() {
        //Will be fed a form
    }
    
    
    //Update product
    function update_product() {
        
    }
    
    //Delete Product
    function delete_product() {
        
    }
    


?>