<?php

$db = new PDO('sqlite:../sql/OIS.db');

$query = "SELECT id, company_name FROM customer;";

$stmt = $db->prepare($query);

$stmt->execute();

$customers = $stmt->fetchAll();

echo json_encode($customers);
