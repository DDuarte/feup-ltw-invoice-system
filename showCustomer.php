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
        <script src="js/showCustomer.js"></script>
        <title>Show Customer</title>
    </head>
    <body onload="loadCustomer()">
        <form class="_form">
            <div class="_header">
                Show Customer
            </div>
            <div class="_row _customer_id _hundred">
                <label for="CustomerID">Customer Id</label>
                <input type="number" id="CustomerID" value="N/A" readonly>
            </div>
            <div class="_row _customer_tax_id _hundred">
                <label for="CustomerTaxId">Customer Tax Id</label>
                <input type="text" id="CustomerTaxId" pattern="[1-9]{1}[0-9]{8}" value="N/A" readonly>
            </div>
            <div class="_row _company_name _five_hundred">
                <label for="CompanyName">Company Name</label>
                <input type="text" id="CompanyName" value="N/A" readonly>
            </div>
            <div class="_row">
                <label>Billing Address</label>
                <div class="_sub_row">
                    <div class="_row _address_detail _five_hundred">
                        <label for="AddressDetail">Address Detail</label>
                        <input type="text" id="AddressDetail" value="N/A" readonly>
                    </div>
                    <div class="_row _city _hundred">
                        <label for="City">City</label>
                        <input type="text" id="City" value="N/A" readonly>
                    </div>
                    <div class="_row _postal_code _seventy">
                        <label for="PostalCode">Postal Code</label>
                        <input type="text" id="PostalCode" value="N/A" readonly pattern="\d{4}-\d{3}">
                    </div>
                    <div class="_row _country _hundred">
                        <label for="Country">Country</label>
                        <input type="text" id="Country" value="N/A" readonly>
                    </div>
                </div>
            </div>
            <div class="_row _email _five_hundred">
                <label for="Email">Email</label>
                <input type="email" id="Email" value="N/A" readonly>
            </div>
        </form>
    </body>
</html>