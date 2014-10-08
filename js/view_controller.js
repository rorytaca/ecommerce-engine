/* View_controller.js
    Watches for User inputs on the view level, executes corresponding procedures.
*/
var session_id;

$(document).ready(function() {
    //Set cookie or fetch 
    setSessionID();

    
    //Common view layer objects

    //AJAX specific triggered events
    
    /* Add to Cart */
    $("#add_to_cart_button").click(function() {
        
        //Fetch vars
        var _id =  $("#prod_id").text();
        var _qty = 1;
        
        var sel = document.getElementById("size_select");
        var _size = sel.options[sel.selectedIndex].value;
        
        if (_size == "blank") {
            $("#product_warning").html("Please select a size!");
            
            return;
        }
        
        $.ajax({
            type: "GET",
            url: "/php/ajax_controller.php",
            data: {call: "addToCart", id: _id, qty: _qty, size: _size},
            success: function(){
                //do something - update cart
                
                window.location = "cart.php";
            }
        
       });
    });
    
    $(".remove").click(function() {
        var product_id = $(this).siblings('.id').text();
        $.ajax({
            type: "GET",
            url: "php/ajax_controller.php",
            data: {call: "removeCartItem", prod_id: product_id},
            success: function(){
                
                window.location = "cart.php";
            }
        
        });
    });
    
    $(".clear").click(function() {
        console.log('test');
        $.ajax({
            type: "GET",
            url: "php/ajax_controller.php",
            data: {call: "clearCart"},
            success: function(){
                
                window.location = "cart.php";
            }
        
        });
    });
});

function setCookie(_sid) {
                    var cookieName = "session_id";
                    var cookieValue = new String(_sid);
                    newValue = cookieValue.substring(1,8);
                    
                    console.log(newValue);
                    
                    var myDate = new Date();
                    myDate.setDate(myDate.getDate()+2);
                    console.log(cookieName+ "=" + newValue + ";expires=" + myDate 
                        + ";domain=higherclassclothing.com;path=/");
                    
                    var cookieString = cookieName+ "=" + newValue + ";expires=" + myDate 
                        + ";domain=higherclassclothing.com;path=/";
                        
                    document.cookie = cookieString;
}

function setSessionID() {
    $.ajax({
            type: "GET",
            url: "/php/ajax_controller.php",
            data: {call: "setSessionID"},
            success: function(sid){
                setCookie(sid);
            }
        
       });
}