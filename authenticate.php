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
        <link rel="stylesheet"  href="css/index.css" type="text/css">
    </head>
    <body>
    <div style="display: table; position: absolute; height: 100%; width: 100%">
        <div style="display: table-cell; vertical-align: middle;">
            <div style="margin-left: auto; margin-right: auto; width: 600px; padding-top: 110px; padding-bottom: 110px">
                <form method="post" action="api/login.php" style="margin: 0 auto; width: 170px">
                    <div style="margin-bottom: 10px"><label>Username: </label><br /><input type="text" name="username"/></div>
                    <div style="margin-bottom: 20px"><label>Password: </label><br /><input type="password" name="password"/></div>
                    <input type="submit" style=" width: 50px; margin-left: 55px;" value="Login"/>
                </form>
            </div>
        </div>
    </div>
    </body>
</html>
