<?php

require_once 'api/details/user_management.php';
redirect_if_not_logged_in();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/common.css" type="text/css">
        <link rel="stylesheet" href="css/showDocuments.css" type="text/css">
        <script src="js/jquery-1.10.2.min.js"></script>
        <script src="js/index.js"></script>
        <script src="js/showInvoice.js"></script>
        <title>Show Invoice</title>
        <script type="text/javascript">
            $(document).ready(function() {
                load('documents');
            } );
        </script>
    </head>
    <body onload="loadInvoice()">
        <div id="pageHeader"></div>
        <form class="_form">
            <div class="_header">
                Show Invoice
            </div>
            <div class="_row _invoice_number">
                <label for="InvoiceNo">Invoice Number</label>
                <input type="text" id="InvoiceNo" value="N/A" readonly>
            </div>
            <div class="_row _invoice_date">
                <label for="InvoiceDate">Invoice Date</label>
                <input type="date" id="InvoiceDate" value="N/A" readonly>
            </div>
            <div class="_row">
                <label>Customer Id</label>
                <input type="number" id="CustomerID" value="N/A" readonly>
                <a id="CustomerIDLink" target="_blank"><img title="Customer Info" src="images/glyphicons_217_circle_arrow_right.png"></a>
            </div>
            <div class="_row">
                <label for="CompanyName">Company Name</label>
                <input type="text" id="CompanyName" value="N/A" readonly>
            </div>
            <div class="_row" id="OwnerDiv" hidden>
                <label for="AccountName">Owner</label>
                <input type="text" id="OwnerName" value="N/A" readonly>
            </div>
            <div class="_row _line_title">
            </div>
            <div class="_row totals">
                <label for="TaxPayable">Tax Payable</label>
                <input type="text" id="TaxPayable" value="N/A" readonly>
            </div>
            <div class="_row totals">
                <label for="NetTotal">Net Total</label>
                <input type="text" id="NetTotal" value="N/A" readonly>
            </div>
            <div class="_row totals">
                <label for="GrossTotal">Gross Total</label>
                <input type="text" id="GrossTotal" value="N/A" readonly>
            </div>
        </form>
    </body>
</html>
