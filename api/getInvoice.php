<?php
    header("Content-type:application/json");
    $db = new PDO('sqlite:../sql/OIS.db');

    $field = 'InvoiceNo';
    $error400 = '{"error":{"code":400,"reason":"Bad request"}}';
    $error404 = '{"error":{"code":404,"reason":"Invoice not found"}}';

    if (!array_key_exists('InvoiceNo', $_GET))
    {
        exit($error400);
    }

    $invoiceNoStr = htmlspecialchars($_GET[$field]);

    if (empty($invoiceNoStr))
    {
        exit($error400);
    }

    $regexMatches = preg_match("/^([^ ]+) ([^\/^ ]+)\/([0-9]+)$/", $invoiceNoStr, $inv);

    if ($regexMatches == 0)
    {
        exit($error400);
    }
    else if ($inv[1] != 'FT' || $inv[2] != 'SEQ')
    {
        exit($error404);
    }

    $invoiceNo = $inv[3];

    $invoiceStmt = $db->prepare('SELECT billing_date, customer_id FROM invoice WHERE id = :id');
    $invoiceStmt->bindParam(':id', $invoiceNo, PDO::PARAM_INT);
    $invoiceStmt->execute();

    $invoiceResult = $invoiceStmt->fetch();

    if ($invoiceResult == null)
    {
        exit($error404);
    }

    $linesStmt = $db->prepare('SELECT product_id, line_number, quantity, unit_price, tax_id FROM line WHERE invoice_id = :id');
    $linesStmt->bindParam(':id', $invoiceNo, PDO::PARAM_INT);
    $linesStmt->execute();

    $linesResult = $linesStmt->fetchAll();

    $lines = Array();

    $taxPayable = 0.0;
    $netTotal = 0.0;

    $i = 0;
    foreach ($linesResult as $line)
    {
        $taxId = $line['tax_id'];

        $taxStmt = $db->prepare('SELECT type, percentage FROM tax WHERE id = :id');
        $taxStmt->bindParam(':id', $taxId, PDO::PARAM_INT);
        $taxStmt->execute();

        $taxResult = $taxStmt->fetch();

        $tax = Array(
            'TaxType' => $taxResult['type'],
            'TaxPercentage' => (int)$taxResult['percentage']
        );

        $creditAmount = $line['quantity'] * $line['unit_price'];

        $lines[$i++] = Array(
            'LineNumber' => (int)$line['line_number'],
            'ProductCode' => (int)$line['product_id'],
            'Quantity' => (int)$line['quantity'],
            'UnitPrice' => (float)$line['unit_price'],
            'CreditAmount' => $creditAmount,
            'Tax' => $tax
        );

        $taxPayable += $taxResult['percentage'] / 100.0 * $creditAmount;
        $netTotal += $creditAmount;
    }

    $documentTotals = Array(
        'TaxPayable' => $taxPayable,
        'NetTotal' => $netTotal,
        'GrossTotal' => $taxPayable + $netTotal
    );

    $invoice = Array(
        'InvoiceNo' => $invoiceNoStr,
        'InvoiceDate' => $invoiceResult['billing_date'],
        'CustomerID' => (int)$invoiceResult['customer_id'],
        'Line' => $lines,
        'DocumentTotals' => $documentTotals
    );

    echo json_encode($invoice);
?>
