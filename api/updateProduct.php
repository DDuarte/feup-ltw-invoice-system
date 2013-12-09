<?php

header("Content-type:application/json");
require_once 'details/user_management.php';

if (!is_logged_in())
    exit('{"error":{"code":403,"reason":"Not authenticated"}}');

if (!is_editor())
    exit('{"error":{"code":403,"reason":"No permission"}}');

$error400 = '{"error":{"code":400,"reason":"Bad request"}}';
$jsonError = '{"error":{"code":400,"reason":"Json decode Error"}}';

if (!isset($_POST['product']))
    exit($error400);

$product =  json_decode($_POST['product'], true);

if (json_last_error() !== JSON_ERROR_NONE)
    exit($jsonError);

if (!isset($product['ProductDescription']) || !isset($product['ProductCode']))
    exit($error400);

$db = new PDO('sqlite:../sql/OIS.db');

if (empty($product['ProductCode']))
{
    $productStmt = "INSERT INTO product(description, unit_price) VALUES (:_description, :_unitPrice)";
    $hasProductCode = false;
}
else
{
    $productStmt = "UPDATE OR FAIL product SET description = :_description, unit_price = :_unitPrice WHERE id = :_productId";
    $hasProductCode = true;
}

$stmt = $db->prepare($productStmt);

$stmt->bindParam(':_description', $product['ProductDescription'], PDO::PARAM_STR);

if (isset($product['UnitPrice']))
    $stmt->bindParam(':_unitPrice', $product['UnitPrice'], PDO::PARAM_INT);
else
{
    $null = "NULL"; // bind needs ref
    $stmt->bindParam(':_unitPrice', $null, PDO::PARAM_INT);
}

if ($hasProductCode)
    $stmt->bindParam(':_productId', $product['ProductCode'], PDO::PARAM_INT);

$stmt->execute();

if ($hasProductCode)
    echo $product['ProductCode'];
else
    echo $db->lastInsertId();
