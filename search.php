<?php

require 'api/details/user_management.php';
redirect_if_not_logged_in();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Online Invoicing System</title>
        <script src="js/jquery-1.10.2.min.js"></script>
        <script src="js/index.js"></script>
        <link rel="stylesheet"  href="css/index.css" type="text/css">
        <script type="text/javascript">
            $(document).ready( function() {
                load('search');
                $('#search_form').on( 'submit', function(e) {
                    e.preventDefault();
                    search();
                });
            });
        </script>
    </head>
    <body>
        <div id="pageHeader"></div>
        <div class="_search_box">
            <div class="_search_first_line">
                <div class="_search_input">
                    <label for="document_search_select">Search</label>
                </div>
                <form id="search_form">
                    <div class="_my_select _search_list" id="document_search_list">
                        <select id="document_search_select">
                            <option value="none">Select document</option>
                            <option value="customer_field_search_list">Customer</option>
                            <option value="invoice_field_search_list">Invoice</option>
                            <option value="product_field_search_list">Product</option>
                        </select>
                    </div>
                    <div class="_my_select _search_list doc_fields" id="customer_field_search_list">
                        <select id="customer_field_search_select">
                            <option value="CustomerId">Customer Id</option>
                            <option value="CustomerTaxID">Customer Tax Id</option>
                            <option value="CompanyName">Company Name</option>
                        </select>
                    </div>
                    <div class="_my_select _search_list doc_fields" id="invoice_field_search_list">
                        <select id="invoice_field_search_select">
                            <option value="InvoiceNo">Invoice Number</option>
                            <option value="InvoiceDate">Invoice Date</option>
                            <option value="CompanyName">Company Name</option>
                            <option value="GrossTotal">Gross Total</option>
                        </select>
                    </div>
                    <div class="_my_select _search_list doc_fields" id="product_field_search_list">
                        <select id="product_field_search_select">
                            <option value="ProductCode">Product Code</option>
                            <option value="ProductDescription">Product Description</option>
                        </select>
                    </div>
                    <div class="_my_select _search_list" id="op_search_list">
                        <select id="op_search_select">
                            <option value="equal">equal to</option>
                            <option value="contains">with</option>
                            <option value="range">between</option>
                            <option value="min">minimum</option>
                            <option value="max">maximum</option>
                            <option value="minvalue">minimum value</option>
                            <option value="maxvalue">maximum value</option>
                        </select>
                    </div>
                    <div class="_field_search" id="field1_search_list">
                        <input required type="text" id="field1">
                    </div>
                    <span id="between_span">and</span>
                    <div class="_field_search" id="field2_search_list">
                        <input type="text" id="field2">
                    </div>
                    <input type="submit" value="Search" id="search_button">
                </form>
            </div>
        </div>
        <div class="search_results">
            <table id="search_results_table">
                <tr id="header">
                </tr>
            </table>
        </div>
    </body>
</html>