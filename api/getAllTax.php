<?php

$db = new PDO('sqlite:../sql/OIS.db');

$query = "SELECT id, type, percentage FROM tax;";

$stmt = $db->prepare($query);

$stmt->execute();

$taxes = $stmt->fetchAll();

echo json_encode($taxes);