<?php

$db = new PDO('sqlite:../sql/OIS.db');

$query = "SELECT * FROM product;";

$stmt = $db->prepare($query);

$stmt->execute();

$products = $stmt->fetchAll();

echo json_encode($products);