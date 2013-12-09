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
        <script src="js/jquery.xml2json.js"></script>
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
                <?php if (is_editor()) { ?>
                <li class="_menu_item" id="documents">
                    <a>
                        <span>Documents</span>
                    </a>
                    <ul class="create_documents">
                        <li class="_menu_item">
                            <a href="showCustomer.php?action=create">
                                <span>Create Customer</span>
                            </a>
                        </li>
                        <li class="_menu_item">
                            <a href="showInvoice.php?action=create">
                                <span>Create Invoice</span>
                            </a>
                        </li>
                        <li class="_menu_item">
                            <a href="showProduct.php?action=create">
                                <span>Create Product</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <?php if (is_admin()) { ?>
                <li class="_menu_item" id="userManagement">
                    <a>
                        <span>Manage Users</span>
                    </a>
                    <ul>
                        <li class="_menu_item">
                            <a href="showUser.php?action=create">
                                <span>Create User</span>
                            </a>
                        </li>
                        <li class="_menu_item">
                            <a href="manageUsers.php">
                                <span>List Users</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <li class="_menu_item" id="import_export">
                    <a href="import_export.php">
                        <span>Import/Export</span>
                    </a>
                </li>
                <li class="_menu_item" id="about">
                    <a href="about.php">
                        <span>About</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="session">
            <div id="_current_user">
                <a id="userEdit" href=<?php echo "\"showUser.php?UserId=".$_SESSION['user_id'].'&action=edit"'; ?>><span id="username"> <?php echo $_SESSION['username']; ?></span></a>
                -
                <span id="role"> <?php require_once 'api/details/user_management.php'; echo get_role(); ?></span>

            </div>
            <a href="api/logout.php">
                <span>Logout</span>
            </a>
        </div>
    </body>
</html>