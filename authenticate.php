<?php

require_once 'api/details/user_management.php';

if (is_logged_in())
{
    header("Location: index.php");
    exit(0);
}

?>

<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8" />
        <title>Online Invoicing System</title>
        <script src="js/jquery-1.10.2.min.js"></script>
        <link rel="stylesheet"  href="css/common.css" type="text/css">
        <link rel="stylesheet"  href="css/authenticate.css" type="text/css">
    </head>
    <body>
    <div class="_login_box">
            <div class="_login_form">
                <div class="_form_title">
                    <h1>OIS</h1>
                </div>
                <form method="post" action="api/login.php">
                    <div><label>Username</label><br /><input type="text" name="username"/></div>
                    <div><label>Password</label><br /><input type="password" name="password"/></div>
                    <input type="submit" style="width: 100px; text-align: center" value="Login"/>
                </form>
                <div class="_help">
                    <ul>
                        <li>
                            <a href="http://google.pt" style="text-decoration: none">Can't log in?</a>
                        </li>
                    </ul>
                </div>
        </div>
    </div>
    </body>
</html>
