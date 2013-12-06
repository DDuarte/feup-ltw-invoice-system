<?php

header("Content-type:application/json");
require_once 'details/user_management.php';

if (!is_logged_in())
    exit('{"error":{"code":403,"reason":"Not authenticated"}}');

if (!is_editor())
    exit('{"error":{"code":403,"reason":"No permission"}}');

$error400 = '{"error":{"code":400,"reason":"Bad request"}}';

if (!isset($_POST['product']))
    exit($error400);

$product =  json_decode($_POST['product'], true);

if (json_last_error() !== JSON_ERROR_NONE)
    exit($error400);

if (!isset($product['description']) || !isset($product['unit_price']) || !isset($product['ProductCode']))
    exit($error400);

$db = new PDO('sqlite:../sql/OIS.db');

if (empty($product['ProductCode']))
{
    $productStmt = "INSERT INTO product(description, unit_price) VALUES (:_description, :_unitPrice);";
    $hasProductCode = false;
}
else
{
    $productStmt = "UPDATE OR FAIL product SET description = :_description, unit_price = :_unitPrice WHERE product.id = :_productId;";
    $hasProductCode = true;
}

$stmt = $db->prepare($productStmt);

if (!$stmt)
    exit('Sorry, I dont know how to write sql statements');

$stmt->bindParam(':_description', $product['description'], PDO::PARAM_STR);
$stmt->bindParam(':_unitPrice', $product['unit_price'], PDO::PARAM_INT);

if ($hasProductCode)
    $stmt->bindParam(':_productId', $product['ProductCode'], PDO::PARAM_INT);

$stmt->execute();

echo json_encode($db->lastInsertId());

