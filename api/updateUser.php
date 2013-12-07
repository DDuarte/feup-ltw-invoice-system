<?php

header("Content-type:application/json");
require_once 'details/user_management.php';

if (!is_logged_in())
    exit('{"error":{"code":403,"reason":"Not authenticated"}}');

if (!is_editor())
    exit('{"error":{"code":403,"reason":"No permission"}}');

$error400 = '{"error":{"code":400,"reason":"Bad request"}}';

if (!isset($_POST['user']))
    exit($error400);
else
    $json = $_POST['user'];

$user = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE)
    exit($error400);

if (!isset($user['username']) || !isset($user['password']))
    exit($error400);

$db = new PDO('sqlite:../sql/OIS.db');

if (empty($user['id']))
{
    $invoiceStmt = "INSERT INTO user(username, password, role_id) VALUES (:username, :password, :role_id);";
    $hasId = false;
}
else
{
    $invoiceStmt = "UPDATE OR FAIL user SET username = :username, password = :password, role_id = :role_id WHERE user.id = :userId;";
    $hasId = true;
}
$stmt = $db->prepare($invoiceStmt);

if (!$stmt)
    exit('Sorry, I dont know how to write sql statements');

$stmt->bindParam(':username', $user['username'], PDO::PARAM_STR);
$hashedPassword = encrypt_credentials($user['username'], $user['password']);
$stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
$role_id = !isset($user['role_id']) ? 1 : $user['role_id'];
$stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);

if ($hasId)
    $stmt->bindParam(':userId', $user['id'], PDO::PARAM_INT);

$stmt->execute();

echo($user);