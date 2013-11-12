<?php
    header("Content-type:application/json");
    require 'details/customer.php';

    $db = new PDO('sqlite:../sql/OIS.db');

    $field = 'CustomerID';
    $error400 = '{"error":{"code":400,"reason":"Bad request"}}';
    $error404 = '{"error":{"code":404,"reason":"Customer not found"}}';

    if (!array_key_exists($field, $_GET))
    {
        exit($error400);
    }

    $customerId = htmlspecialchars($_GET[$field]);

    $customer = new Customer;
    $error = $customer->queryDbById($customerId);
    if ($error)
    {
        $error = "error" . $error;
        exit($$error);
    }

    echo $customer->encode("json");
