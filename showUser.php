<?php

require 'api/details/user_management.php';
redirect_if_not_logged_in();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet"  href="css/style.css" type="text/css">
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/showUser.js"></script>
    <title>Show Customer</title>
</head>
<body onload="loadUser()">
<form class="_form">
    <div class="_header">
        Show User
    </div>
    <div class="_row _user_username _hundred">
        <label for="Username">Username</label>
        <input type="number" id="Username" value="N/A" readonly>
    </div>
    <div class="_row _user_role _five_hundred">
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
    <div class="_row _self_billing_indicator">
        <label for="SelfBillingIndicator">Self Billing</label>
        <input type="checkbox" id="SelfBillingIndicator" readonly>
    </div>
</form>
</body>
</html>