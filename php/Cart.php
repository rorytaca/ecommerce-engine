<?php

class Cart {
    var $cart_name;         //Name will be session variable based.
    var $items = array();
    
    function __construct($name) {
        $this->cart_name = $name;
        $this->items = $_COOKIE[$this->cart_name];

    }
    
    function setItemQuantity($product_id, $qty) {
        $this->items[$product_id] = $qty;
    }
    
    function getItemPrice() {
        
    }
    
}

?>