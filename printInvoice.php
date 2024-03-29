<?php

require_once 'api/details/user_management.php';
redirect_if_not_logged_in();

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet"  href="css/printstyle.css" type="text/css">
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/print.js"></script>
    <title>Invoice Information</title>
</head>
<body onload="loadInvoice()">
    <div class="_header_title">
        Online Invoice System
    </div>
<div>
    <div id="invoice_info">
        <p class="invoiceInfo" style="margin-top:0">
            <b>Invoice Information: </b><br>
            <span id="InvoiceNo">N/A</span><br>
            <time id="InvoiceDate" datetime="N/A">N/A</time>
        </p>
    </div>
    <div id="customer_info">
       <p class="customerInfo">
           <b>Customer Information: </b><br>
           #<span id="CustomerID">N/A</span> - NIF: <span id="CustomerTaxID">N/A</span><br>
           <b><span id="CompanyName">N/A</span></b><br>
           <span id="AddressDetail">N/A</span><br> <span id="City">N/A</span> <span id="PostalCode">N/A</span><br> <span id="Country">N/A</span><br>
           <a id="Email" href="mailto:N/A">N/A</a>
       </p>
    </div>
</div>
<br>
<div>
    <div class="_table" id="lines">
        <div class="_row _header">
            <div class="_header">#              </div>
            <div class="_header">Code           </div>
            <div class="_header">Description    </div>
            <div class="_header">Quantity       </div>
            <div class="_header">Unit Price     </div>
            <div class="_header">Tax            </div>
            <div class="_header">Credit Amount  </div>
        </div>
    </div>

    <div class="_table totals_div">
        <div class="_row totals">
            <div class="_cell title" id="tax_payable_title">Tax Payable</div>
            <div class="_cell total_value"><span id="TaxPayable">N/A</span> &euro;</div>
        </div>
        <div class="_row totals">
            <div class="_cell title" id="net_total_tile">Net Total</div>
            <div class="_cell total_value"><span id="NetTotal">N/A</span> &euro;</div>
        </div>
        <div class="_row totals">
            <div class="_cell title" id="gross_total_title"><b>Gross Total</b></div>
            <div class="_cell total_value"><span id="GrossTotal">N/A</span> &euro;</div>
        </div>
    </div>
</div>
</body>
</html>