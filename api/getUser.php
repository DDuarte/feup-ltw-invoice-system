<?php
    header("Content-type:application/json");

    require_once 'details/user_management.php';

    if (!is_logged_in())
        exit('{"error":{"code":403,"reason":"Not authenticated"}}');

    require_once 'details/user.php';

    $db = new PDO('sqlite:../sql/OIS.db');

    $field = 'UserId';
    $error400 = '{"error":{"code":400,"reason":"Bad request"}}';
    $error404 = '{"error":{"code":404,"reason":"Invoice not found"}}';

    if (!array_key_exists($field, $_GET))
    {
        exit($error400);
    }

    $userIdStr = htmlspecialchars($_GET[$field]);

    $user = new User;
    $error = $user->queryDbById($userIdStr);
    if ($error)
    {
        $error = "error" . $error;
        exit($$error);
    }

    echo $user->encode("json");
