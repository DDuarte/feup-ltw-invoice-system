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
        <link rel="stylesheet"  href="css/index.css" type="text/css">
    </head>
    <body onload="load('index')">
        <div id="pageHeader"></div>

        <div class="_info_block">
            <h1>OIS</h1>
            <pre><h3> Online Invoicing System </h3></pre>
            <div class="homeTextBox">
                <p> OIS is a free, open source, web based invoicing system that you can install on your server/pc. </p>
                
                <p> This system provides a feature rich search API, allowing you to acess all the necessary information with ease. </p>
            </div>
        </div>
    </body>
</html>