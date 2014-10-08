<!-- cart.php - Displays the shopping cart page. Shopping cart held as a table on MySQL -->


<?php
    include('includes/header.php');
    include('php/paypal_controller.php');

?>


    <div id="content_wrapper">
        <div id="cart">
            <h4>Shopping Cart</h4>
            
            <?php
            
                if (checkDBForCart() == true && fetchCartNumberOfItems() != 0) {
                    fetchCartHTML();
                } else {
                    print '
                        <span id="cart_warning">
                            <p>You have not added anything to your cart yet! Start <a href="category.php?cat=all" class="accented">shopping</a> now.</p>
                        </span>';
                }
                
            ?>
            
            <div id="cart_footer">
                <span id="cart_price" class="price_tag">Cart:</span><span class="price_number">$<?php $price = getCartTotalPrice(); echo number_format((float)$price, 2, '.', ''); ?></span>
                <span id="shipping_price" class="price_tag">Shipping:</span><span class="price_number">$2.00</span>
                <span id="total_price" class="price_tag">Total Price:</span><span class="price_number">$<?php $price = getCartTotalPrice() + 7.00; echo number_format((float)$price, 2, '.', ''); ?></span>
            </div>
            
            <?php generatePayPalForms(); ?>
                      
            <a href="#" class="clear">Clear Cart</a>
        </div>
    </div>
    
<?php include('includes/footer.html'); ?>

<!--<a href="checkout.php" class="checkout">Proceed to Checkout</a> -->