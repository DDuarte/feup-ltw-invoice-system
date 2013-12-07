<?php

require_once 'api/details/user_management.php';
redirect_if_not_logged_in();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Online Invoicing System</title>
        <script src="js/jquery-1.10.2.min.js"></script>
        <script src="js/index.js"></script>
        <link rel="stylesheet"  href="css/common.css" type="text/css">
        <link rel="stylesheet"  href="css/navigatorBar.css" type="text/css">
    </head>
    <body>
        <nav class="_menu_navigator_bar">
            <ul class="_menu_bar">
                <li class="_menu_item selected" id="index">
                    <a href="index.php">
                        <span>Home</span>
                    </a>
                </li>
                <li class="_menu_item" id="search">
                    <a href="search.php">
                        <span>Search</span>
                    </a>
                </li>
                <?php if (is_admin()) { ?>
                <li class="_menu_item" id="userManagement">
                    <a href="manageUsers.php">
                        <span>Manage</span>
                    </a>
                </li>
                <?php } ?>
                <li class="_menu_item" id="about">
                    <a href="about.php">
                        <span>About</span>
                    </a>
                </li>
                <li class="_menu_item" id="logout">
                    <a href="api/logout.php">
                        <span>Logout</span>
                    </a>
                </li>

            </ul>
        </nav>
    </body>
</html>