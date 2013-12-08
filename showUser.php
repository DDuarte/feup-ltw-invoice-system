<?php

require 'api/details/user_management.php';
redirect_if_not_logged_in();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/showDocuments.css" type="text/css">
    <link rel="stylesheet" href="css/common.css" type="text/css">
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/index.js"></script>
    <script src="js/showUser.js"></script>
    <title>User management</title>
    <script type="text/javascript">
        $(document).ready( function() { load('userManagement'); });
    </script>
</head>
<body onload="loadUser()">
<div id="pageHeader"></div>
<form class="_form">
    <div class="_header">
        Show User
    </div>
    <div class="_row _user_username">
        <label for="Username">Username</label>
        <input type="text" id="Username" value="N/A" readonly>
    </div>
    <div class="_row _user_role">
        <label for="Role">Role</label>

        <div class="_my_select">
            <select id="role" required>
            </select>
        </div>
    </div>
</form>
</body>
</html>
