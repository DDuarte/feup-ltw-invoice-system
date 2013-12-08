<?php

require_once 'details/user_management.php';

if (!is_logged_in())
    exit('{"error":{"code":403,"reason":"Not authenticated"}}');

$error400 = '{"error":{"code":400,"reason":"Bad request"}}';

if (!isset($_POST['invoice']))
    exit($error400);
else
    $json = $_POST['invoice'];

$invoice =  json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE)
    exit('BAD DECODE');

// InvoiceDate

if (!isset($invoice['InvoiceDate']) || !isset($invoice['CustomerID']) || !isset($invoice['InvoiceNo']) || !isset($invoice['DocumentStatus']['SourceID']))
    exit($error400);

if (!isset($invoice['notUpdatePrice']))
    $updatePrice = true;
else
    $updatePrice = false;

$db = new PDO('sqlite:../sql/OIS.db');

// update or insert new invoice information
if (empty($invoice['InvoiceNo']))
{
    if (!isset($invoice['SystemEntryDate']))
        $invoiceStmt = "INSERT OR FAIL INTO invoice(billing_date, customer_id, user_id) VALUES (:_billingDate, :_customerId, :_user_id);";
    else
        $invoiceStmt = "INSERT OR FAIL INTO invoice(billing_date, customer_id, user_id, entry_date) VALUES (:_billingDate, :_customerId, :_user_id, :_entry_date);";

    $stmt = $db->prepare($invoiceStmt);

    if (!$stmt)
        exit($error400);

    $stmt->bindParam(':_billingDate', $invoice['InvoiceDate'], PDO::PARAM_STR);
    $stmt->bindParam(':_customerId', $invoice['CustomerID'], PDO::PARAM_INT);
    $stmt->bindParam(':_user_id', $invoice['DocumentStatus']['SourceID'], PDO::PARAM_INT);

    if (isset($invoice['SystemEntryDate']))
        $stmt->bindParam(':_entry_date', $invoice['SystemEntryDate'], PDO::PARAM_STR);

    $stmt->execute();

    $invoice['InvoiceNo'] = $db->lastInsertId();
}
else
{
    $invoiceStmt = "UPDATE OR FAIL invoice SET billing_date = :_billingDate WHERE invoice.id = :_invoiceId;";
    $stmt = $db->prepare($invoiceStmt);

    if (!$stmt)
        exit($error400);

    $stmt->bindParam(':_billingDate', $invoice['InvoiceDate'], PDO::PARAM_STR);
    $stmt->bindParam(':_invoiceId', $invoice['InvoiceNo'], PDO::PARAM_INT);

    $stmt->execute();
}

$lines = $invoice['Line'];

if (!isset($lines) || !is_array($lines))
    exit($error400);

// insert each line of the invoice
foreach($lines as $line)
{
    $lineStmt = "INSERT OR REPLACE INTO line (product_id, line_number, invoice_id, quantity, unit_price, tax_id) VALUES
    (:_product_id, :_line_number, :_invoice_id, :_quantity, :_unit_price, :_tax_id);";

    $stmt = $db->prepare($lineStmt);

    if(!$stmt)
        exit($error400);

    $stmt->bindParam(':_product_id', $line['ProductCode'], PDO::PARAM_INT);
    $stmt->bindParam(':_line_number', $line['LineNumber'], PDO::PARAM_INT);
    $stmt->bindParam(':_invoice_id', $invoice['InvoiceNo'], PDO::PARAM_INT);
    $stmt->bindParam(':_quantity', $line['Quantity'], PDO::PARAM_INT);

    if ($updatePrice) {
        if (!empty($line['UnitPrice'])) {
            $stmt->bindParam(':_unit_price', $line['UnitPrice'], PDO::PARAM_STR);

            $productStmt = "UPDATE product SET unit_price = :_product_unit_price WHERE id = :_product_code";
            $productUpdate = $db->prepare($productStmt);

            if (!$productUpdate)
                exit($error400);

            $productUpdate->bindParam(':_product_unit_price', $line['UnitPrice'], PDO::PARAM_STR);
            $productUpdate->bindParam(':_product_code', $line['ProductCode'], PDO::PARAM_INT);

            $productUpdate->execute();
        } else {
            $null = "NULL";
            $stmt->bindParam(':_unit_price', $null, PDO::PARAM_STR);
        }

    } else
        $stmt->bindParam(':_unit_price', $line['UnitPrice'], PDO::PARAM_STR);

    $taxStmt = "SELECT id FROM tax WHERE type = :_type AND percentage = :_percentage;";
    $newTaxStmt = $db->prepare($taxStmt);

    $newTaxStmt->bindParam(':_type', $line['Tax']['TaxType'], PDO::PARAM_STR);
    $newTaxStmt->bindParam(':_percentage', $line['Tax']['TaxPercentage'], PDO::PARAM_INT);

    $newTaxStmt->execute();
    $taxResults = $newTaxStmt->fetchAll();


    $stmt->bindParam(':_tax_id', $taxResults[0]['id'], PDO::PARAM_INT);
    $stmt->execute();
}

$maxLineNumber = end($lines)['LineNumber'];
reset($lines);

// delete remaining lines that may have been stored in the database

$remainingLinesStmt = "DELETE FROM line WHERE line.invoice_id = :_invoice_id AND line.line_number > :_line_number";

$stmt = $db->prepare($remainingLinesStmt);

if (!$stmt)
    exit($error400);

$stmt->bindParam(':_line_number', $maxLineNumber, PDO::PARAM_INT);
$stmt->bindParam(':_invoice_id', $invoice['InvoiceNo'], PDO::PARAM_INT);

$stmt->execute();

if (!$stmt)
    exit($error400);

echo(json_encode($invoice));
