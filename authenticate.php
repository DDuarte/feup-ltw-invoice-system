<?php

require 'api/details/user_management.php';

if (!is_logged_in())
{
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


<!--    <div style="display: block; width: 200px;padding: 100px 100px; margin: 0 auto">-->
<!--        <form method="post" action="api/login.php" style="width: 170px; margin: 0 auto;">-->
<!--            <label>Username: </label><br /><input type="text" name="username"/> <br />-->
<!--            <label>Password: </label><br /><input type="text" name="password"/> <br />-->
<!--            <input type="submit" style="margin-top: 20px" value="Login"/>-->
<!--        </form>-->
<!--    </div>-->

    </body>
</html>

<?php
}
else
{
    header("Location: index.php");
}

?>