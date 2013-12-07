<?php

require_once 'api/details/user_management.php';
redirect_if_not_logged_in();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet"  href="css/showDocuments.css" type="text/css">
        <script src="js/jquery-1.10.2.min.js"></script>
        <script src="js/showCustomer.js"></script>
        <title>Create Customer</title>
    </head>
    <body>
        <div class="_form">
            <div class="_header">
                Create Customer
            </div>
            <div class="_row _customer_id _hundred">
                <label for="CustomerID">Customer Id</label>
                <input type="number" id="CustomerID" value="N/A" readonly>
            </div>
            <div class="_row _customer_tax_id _hundred">
                <label for="CustomerTaxId">Customer Tax Id</label>
                <input type="number" id="CustomerTaxId" value="N/A" readonly>
            </div>
            <div class="_row _company_name _five_hundred">
                <label for="CompanyName">Company Name</label>
                <input type="text" id="CompanyName">
            </div>
            <div class="_row">
                <label>Billing Address</label>
                <div class="_sub_row">
                    <div class="_row _address_detail _five_hundred">
                        <label for="AddressDetail">Address Detail</label>
                        <input type="text" id="AddressDetail">
                    </div>
                    <div class="_row _city _hundred">
                        <label for="City">City</label>
                        <input type="text" id="City">
                    </div>
                    <div class="_row _postal_code _seventy">
                        <label for="PostalCode">Postal Code</label>
                        <input type="text" id="PostalCode">
                    </div>
                    <div class="_row _country _hundred">
                        <label for="Country">Country</label>
                        <div class="_my_select _search_list" id="document_search_list">
                        <select id="document_search_select">
                            <option value="none">Select your country</option>
                            <option>Portugal</option>
                        </select>
                    </div>
                    </div>
                </div>
            </div>
            <div class="_row _email _five_hundred">
                <label for="Email">Email</label>
                <input type="email" id="Email">
            </div>
            <div class="_row _self_billing_indicator">
                <label for="SelfBillingIndicator">Self Billing</label>
                <input type="checkbox" id="SelfBillingIndicator" readonly>
            </div>
            <form method="post" action="" style="postion: relative; display: inline-flex; float:right">
                    <input type="submit" style="width: 50px;" value="Create"/>
                </form>
        </div>
    </body>
</html>
