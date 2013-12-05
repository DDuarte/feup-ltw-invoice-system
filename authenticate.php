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
        <div style="position: relative; margin: 0 auto; top: 300px; height: 60%">
            <div style="margin-left: auto; margin-right: auto; width: 300px; height:170px">
                <div style="margin: 0 auto; width: 90%; height:70px">
                    <h1 style="font-size: 35px; margin-bottom: 20px">OIS</h1>
                </div>
                <form method="post" action="api/login.php" style="margin: 0 auto; width: 90%; height:100%">
                    <div style="margin-bottom: 10px"><label>Username</label><br /><input type="text" name="username" style="width: 250px"/></div>
                    <div style="margin-bottom: 20px"><label>Password</label><br /><input type="password" name="password" style="width: 250px"/></div>
                    <input type="submit" style="width: 100px; text-align: center" value="Login"/>
                </form>
                <div style="width: 90%; margin: 15px auto;">
                    <ul>
                        <li style="list-style-type: none">
                            <a href="http://google.pt" style="text-decoration: none">Can't log in?</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>
