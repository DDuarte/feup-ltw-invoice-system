<?php

$error400 = '{"error":{"code":400,"reason":"Bad request"}}';

if (!isset($_POST['invoice']))
    exit($error400);
else
    $json = $_POST['invoice'];

$invoice =  json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE)
    exit($error400);

if (!isset($invoice['billing_date']) || !isset($invoice['customer_id']) || !isset($invoice['invoiceNo']))
    exit($error400);

$db = new PDO('sqlite:../sql/OIS.db');

if (empty($invoice['invoiceNo']))
{
    $invoiceStmt = "INSERT INTO invoice(billing_date, customer_id) VALUES (:billingDate, :customerId);";
    $hasInvoiceNo = false;
}
else
{
    $invoiceStmt = "UPDATE OR FAIL invoice SET billing_date = :date, customer_id = :customerId WHERE invoice.id = :invoiceId;";
    $hasInvoiceNo = true;
}
$stmt = $db->prepare($invoiceStmt);

if (!$stmt)
    exit('Sorry, I dont know how to write sql statements');

$stmt->bindParam(':customerId', $invoice['billing_date'], PDO::PARAM_STR);
$stmt->bindParam(':customerId', $invoice['customer_id'], PDO::PARAM_INT);

if ($hasInvoiceNo)
    $stmt->bindParam(':customerId', $invoice['invoiceNo'], PDO::PARAM_INT);

$stmt->execute();

echo($invoice);