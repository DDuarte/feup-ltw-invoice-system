<?php

header("Content-type:application/json");
require_once 'details/user_management.php';

if (!is_logged_in())
    exit('{"error":{"code":403,"reason":"Not authenticated"}}');

if (!is_editor())
    exit('{"error":{"code":550,"reason":"Permission denied"}}');

$error400 = '{"error":{"code":400,"reason":"Bad request"}}';

if (!isset($_POST['user']))
    exit($error400);
else
    $json = $_POST['user'];

$user = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE)
    exit($error400);

if (!isset($user['username']) || !isset($user['password']) || !isset($user['id']) || !isset($user['role_id']))
    exit($error400);

$db = new PDO('sqlite:../sql/OIS.db');

if (empty($user['id'])) {
    $invoiceStmt = "INSERT INTO user(username, password, role_id) VALUES (:username, :password, :role_id);";
    $hasId = false;
    $hasPassword = true;
    $hasRole = true;
} else {
    if (!empty($user['password'])) {
        if (!empty($user['role_id'])) {
            $invoiceStmt = "UPDATE OR FAIL user SET username = :username, password = :password, role_id = :role_id WHERE user.id = :userId;";
            $hasRole = true;
        }
        else {
            $invoiceStmt = "UPDATE OR FAIL user SET username = :username, password = :password WHERE user.id = :userId;";
            $hasRole = false;
        }
        $hasPassword = true;
    } else {
        if (!empty($user['role_id'])) {
            $invoiceStmt = "UPDATE OR FAIL user SET username = :username, role_id = :role_id WHERE user.id = :userId;";
            $hasRole = true;
        }
        else {
            $invoiceStmt = "UPDATE OR FAIL user SET username = :username WHERE user.id = :userId;";
            $hasRole = false;
        }

        $hasPassword = false;
    }

    $hasId = true;
}
$stmt = $db->prepare($invoiceStmt);

if (!$stmt)
    exit($error400);

$stmt->bindParam(':username', $user['username'], PDO::PARAM_STR);

if ($hasPassword) {
    $hashedPassword = encrypt_credentials($user['username'], $user['password']);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
}

if ($hasRole)
    $stmt->bindParam(':role_id', $user['role_id'], PDO::PARAM_INT);

if ($hasId)
    $stmt->bindParam(':userId', $user['id'], PDO::PARAM_INT);

$stmt->execute();

if ($hasId)
    echo $user['id'];
else
    echo $db->lastInsertId();