<?php
    header("Content-type:application/json");

    require 'details/user_management.php';

    if (!is_logged_in())
        exit('{"error":{"code":403,"reason":"Not authenticated"}}');

    require 'details/product.php';

    $db = new PDO('sqlite:../sql/OIS.db');

    $field = 'ProductCode';
    $error400 = '{"error":{"code":400,"reason":"Bad request"}}';
    $error404 = '{"error":{"code":404,"reason":"Product not found"}}';

    if (!array_key_exists($field, $_GET))
    {
        exit($error400);
    }

    $productCode = htmlspecialchars($_GET[$field]);

    $product = new Product;
    $error = $product->queryDbById($productCode);
    if ($error)
    {
        $error = "error" . $error;
        exit($$error);
    }

    echo $product->encode("json");
