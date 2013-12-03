<?php
header("Content-type:application/json");

require 'details/user_management.php';

if (!is_logged_in())
    exit('{"error":{"code":403,"reason":"Not authenticated"}}');

require 'details/user.php';

$db = new PDO('sqlite:../sql/OIS.db');

$stmt = $db->prepare('SELECT id FROM user;');
$stmt->execute();

if (!$stmt)
    echo "error in select all users.";

$results = $stmt->fetchAll();

$toBeReturned = [];

$i = 0;

foreach ($results as $result) {
    $user = new User;
    $user->queryDbById($result['id']);
    $toBeReturned[$i++] = $user->toArray();
}

echo json_encode($toBeReturned);