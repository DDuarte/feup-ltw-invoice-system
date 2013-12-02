<?php

require 'api/details/user_management.php';
redirect_if_not_logged_in();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet"  href="css/style.css" type="text/css">
        <script src="js/jquery-1.10.2.min.js"></script>
        <script src="js/showProduct.js"></script>
        <title>Show Product</title>
    </head>
    <body onload="loadProduct()">
        <form class="_form">
            <div class="_header">
                Show Product
            </div>
            <div class="_row _product_code">
                <label for="productCode">Product Code</label>
                <input name="product_code" type="text" id="ProductCode" value="N/A" readonly>
            </div>

            <div class="_row _product_description _five_hundred">
                <label for="productDescription">Product Description</label>
                <input name="product_description" type="text" id="ProductDescription" value="N/A" readonly>
            </div>
            <div class="_row _unit_price _seventy">
                <label for="unitPrice">Unit Price </label>
                <input name="unit_price" type="number" id="UnitPrice" value="N/A" readonly>
            </div>
        </form>
    </body>
</html>