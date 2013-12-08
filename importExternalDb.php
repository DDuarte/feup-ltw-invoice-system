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
    <script src="js/importExternalDb.js"></script>
</head>
<body onload="load()">
<div id="pageHeader"></div>

<div class="_search_box">
    <div class="_search_first_line">
        <div class="_search_input">
            <label for="document_search_select">Search</label>
        </div>
        <form id="search_form">
            <div class="_field_search" id="field2_search_list">
                <input type="url" id="field2" required>
            </div>
            <input type="submit" value="Import" id="search_button">
        </form>
    </div>
</div>
</body>
</html>
