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
    <script src="js/showCustomer.js"></script>
    <title>Create User</title>
</head>

<body>
<form class="_form">
    <div class="_header">
        Create User
    </div>
    <div class="_row _username _hundred">
        <label for="CustomerID">Username</label>
        <input type="text" id="UsernameId">
    </div>
    <div class="_row _password _hundred">
        <label for="CustomerTaxId">Password</label>
        <input type="password" id="PasswordId">
    </div>
    <input type="submit" value="Create" id="search_button">
</form>
</body>
</html>