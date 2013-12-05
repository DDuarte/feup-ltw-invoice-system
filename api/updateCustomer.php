<?php

header("Content-type:application/json");
require_once 'details/user_management.php';

if (!is_logged_in())
    exit('{"error":{"code":403,"reason":"Not authenticated"}}');

$error400 = '{"error":{"code":400,"reason":"Bad request"}}';

if (!isset($_POST['customer']))
    exit($error400);

$customer =  json_decode($_POST['customer'], true);

if (json_last_error() !== JSON_ERROR_NONE)
    exit($error400);

if (!isset($customer['CustomerID']) || !isset($customer['tax_id']) || !isset($customer['detail']) || !isset($customer['company_name'])
    || !isset($customer['email']) || !isset($customer['city_id']) || !isset($customer['postal_code'])
    || !isset($customer['country_code']))
    exit($error400);

$db = new PDO('sqlite:../sql/OIS.db');

if (empty($customer['CustomerID']))
{
    $customerStmt = "INSERT INTO customer(tax_id, company_name, email, detail, city, postal_code, country_code) VALUES
    (:_tax_id, :_company_name, :_email, :_detail, :_city_id, :_postal_code, :_country_code);";
    $hasCustomerID = false;
}
else
{
    $customerStmt = "UPDATE OR FAIL customer SET tax_id = :_tax_id, company_name = :_company_name, email = :_email,
    detail = :_detail, city = :_city_id, postal_code = :_postal_code, country_code = :_country_code WHERE customer.id = :_customerID;";
    $hasCustomerID = true;
}

$stmt = $db->prepare($customerStmt);

if (!$stmt)
    exit('Error: database insertion/update');

$stmt->bindParam(':_tax_id', $customer['tax_id'], PDO::PARAM_INT);
$stmt->bindParam(':_company_name', $customer['company_name'], PDO::PARAM_STR);
$stmt->bindParam(':_email', $customer['email'], PDO::PARAM_STR);
$stmt->bindParam(':_detail', $customer['detail'], PDO::PARAM_STR);
$stmt->bindParam(':_city_id', $customer['city_id'], PDO::PARAM_STR);
$stmt->bindParam(':_postal_code', $customer['postal_code'], PDO::PARAM_STR);
$stmt->bindParam(':_country_code', $customer['country_code'], PDO::PARAM_STR);

if ($hasCustomerID)
    $stmt->bindParam(':_customerID', $customer['CustomerID'], PDO::PARAM_INT);

$stmt->execute();

if (!$hasCustomerID)
    echo($db->lastInsertId());
else
    echo($customer['CustomerID']);