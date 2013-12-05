<?php

    header("Content-type:application/json");
    require 'details/user_management.php';

    if (!is_logged_in())
        exit('{"error":{"code":403,"reason":"Not authenticated"}}');
    else
        echo json_encode($_SESSION['user_id']);
