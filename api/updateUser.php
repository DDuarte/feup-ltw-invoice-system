<?php

require_once 'details/user_management.php';

if (!is_logged_in())
    exit('{"error":{"code":403,"reason":"Not authenticated"}}');

$error400 = '{"error":{"code":400,"reason":"Bad request"}}';

if (!isset($_POST['user']))
    exit($error400);
else
    $json = $_POST['user'];

$user = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE)
    exit($error400);

if (!isset($user['username']) || !isset($user['password']) || !isset($user['role_id']))
    exit($error400);

$db = new PDO('sqlite:../sql/OIS.db');

if (empty($user['id']))
{
    $invoiceStmt = "INSERT INTO user(username, password, role_id) VALUES (:username, :password, :role_id);";
    $hasInvoiceNo = false;
}
else
{
    $invoiceStmt = "UPDATE OR FAIL user SET username = :username, password = :password, role_id = :role_id WHERE user.id = :userId;";
    $hasInvoiceNo = true;
}
$stmt = $db->prepare($invoiceStmt);

if (!$stmt)
    exit('Sorry, I dont know how to write sql statements');

$stmt->bindParam(':username', $user['username'], PDO::PARAM_STR);
$hashedPassword = encrypt_credentials($user['username'], $user['password']);
$stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
$stmt->bindParam(':role_id', $user['role_id'], PDO::PARAM_INT);

if ($hasInvoiceNo)
    $stmt->bindParam(':userId', $user['id'], PDO::PARAM_INT);

$stmt->execute();

echo($user);