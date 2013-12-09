<?php

header("Content-type:application/json");
require_once 'details/user_management.php';

if (!is_logged_in())
    exit('{"error":{"code":403,"reason":"Not authenticated"}}');

$error400 = '{"error":{"code":400,"reason":"Bad request"}}';
$jsonError = '{"error":{"code":400,"reason":"Json decode Error"}}';

if (!isset($_POST['customer']))
    exit($error400);

$customer =  json_decode($_POST['customer'], true);

if (json_last_error() !== JSON_ERROR_NONE)
    exit($jsonError);

if (!isset($customer['CustomerID']) || !isset($customer['CustomerTaxID']) || !isset($customer['BillingAddress']['AddressDetail']) || !isset($customer['CompanyName'])
    || !isset($customer['Email']) || !isset($customer['BillingAddress']['City']) || !isset($customer['BillingAddress']['PostalCode'])
    || !isset($customer['BillingAddress']['Country']))
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

$stmt->bindParam(':_tax_id', $customer['CustomerTaxID'], PDO::PARAM_INT);
$stmt->bindParam(':_company_name', $customer['CompanyName'], PDO::PARAM_STR);
$stmt->bindParam(':_email', $customer['Email'], PDO::PARAM_STR);
$stmt->bindParam(':_detail', $customer['BillingAddress']['AddressDetail'], PDO::PARAM_STR);
$stmt->bindParam(':_city_id', $customer['BillingAddress']['City'], PDO::PARAM_STR);
$stmt->bindParam(':_postal_code', $customer['BillingAddress']['PostalCode'], PDO::PARAM_STR);
$stmt->bindParam(':_country_code', $customer['BillingAddress']['Country'], PDO::PARAM_STR);

if ($hasCustomerID)
    $stmt->bindParam(':_customerID', $customer['CustomerID'], PDO::PARAM_INT);

$stmt->execute();

if (!$hasCustomerID)
    echo($db->lastInsertId());
else
    echo($customer['CustomerID']);
