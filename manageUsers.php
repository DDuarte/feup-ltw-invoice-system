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
    <script src="js/manageUsers.js"></script>
    <link rel="stylesheet"  href="css/index.css" type="text/css">
    <script type="text/javascript">
        $(document).ready( function() {
            load('userManagement');
            loadUsers();
        });
    </script>
</head>
<body>
<div id="pageHeader"></div>

<div class="search_results">
    <table id="search_results_table">
        <tr id="header">
        </tr>
    </table>
</div>
</body>
</html>