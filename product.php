<!-- product.php - Displays a individual products page, prod_id passed in url var -->

<?php

    include('php/product_control.php');
    include('includes/header.php');
    
    
    $prod_id = $_GET['prod'];
    
?>

    <div id="content_wrapper">
        
        <div id="product_wrapper">
            
            <?php fetch_product_html($prod_id) ?>
            <div id="add_to_cart_button">BUY</div>
            <div id="product_warning"></div>  
        </div>
        

    </div>
    
<?php include('includes/footer.html'); ?>