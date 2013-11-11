<?php
    header("Content-type:application/json");
    require 'details/invoice.php';

    $db = new PDO('sqlite:../sql/OIS.db');
	
	$field = 'InvoiceNo';
	$error400 = '{"error":{"code":400,"reason":"Bad request"}}';
	$error404 = '{"error":{"code":404,"reason":"Invoice not found"}}';

	if (!array_key_exists($field, $_GET))
	{
		exit($error400);
	}

	$invoiceNoStr = htmlspecialchars($_GET[$field]);

    $invoice = new Invoice;
    $error = $invoice->queryDbByNo($invoiceNoStr);
    if ($error)
    {
        $error = "error" . $error;
        exit($$error);
    }

	echo $invoice->encode("json");
?>
