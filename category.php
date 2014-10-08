<!-- category.php - Displays a page of all items of particular category passed in Url -->

<?php
    include('php/product_control.php');
    include('includes/header.php');

    $cat = $_GET['cat'];
?>


    <div id="content_wrapper">
        <div id="products">
            
            <!-- Fetch products -->
            <?php fetch_products_html($cat) ?>
            
        </div>
    </div>
    
<?php include('includes/footer.html'); ?>