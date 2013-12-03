<?php

$db = new PDO('sqlite:../sql/OIS.db');

$query = "SELECT code, name FROM country;";

$stmt = $db->prepare($query);

if (!$stmt)
    exit('Sorry, I dont know how to write sql statements');

$stmt->execute();

$countries = $stmt->fetchAll();

echo json_encode($countries);