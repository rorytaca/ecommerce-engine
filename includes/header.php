<?php
    include('php/cart_control.php');    
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title></title>
  
  <meta name="" content="">
  <meta name="" content="">
  
  <script src="http://code.jquery.com/jquery-latest.min.js"></script>
  <script language="JavaScript" type="text/javascript" src="js/view_controller.js"></script>
  <link rel="stylesheet" href="css/style.css">
    
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  
</head>

<body>
    <div id="header_menu">
      <div id="header_menu_inner">
        <div id="little_flag"></div>
        <a href="cart.php" id="my_cart">my cart
          <?php
            if (checkDBForCart() == true) {
              $itemNumb = fetchCartNumberOfItems();
              if ($itemNumb > 1) {
                print '( '.$itemNumb.' items)';
              } else if ($itemNumb == 1) {
                print '(1 item)';
              }
            }
          ?>
        
        </a>
        
      </div>
    </div>
    
    
    
    <div id="header">

    </div>
    <div id="main_wrapper">