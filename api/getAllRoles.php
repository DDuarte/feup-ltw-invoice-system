<?php

$db = new PDO('sqlite:../sql/OIS.db');

$query = "SELECT * FROM role;";

$stmt = $db->prepare($query);

$stmt->execute();

$roles = $stmt->fetchAll();

echo json_encode($roles);