<?php

header("Content-type:application/json");
require_once 'details/user_management.php';

$error400 = '{"error":{"code":400,"reason":"Bad request"}}';
$error403_auth = '{"error":{"code":403,"reason":"Not authenticated"}}';
$error403_perm = '{"error":{"code":403,"reason":"No permission"}}';

if (!is_logged_in())
    exit($error403_auth);

if (!is_editor())
    exit($error403_perm);

if (!isset($_POST['tax']))
    exit($error400);
else
    $json = $_POST['tax'];

$tax = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE)
    exit($error400);

if (!isset($tax['TaxType']) || !isset($tax['TaxCountryRegion']) || !isset($tax['Description']) || !isset($tax['TaxPercentage']))
    exit($error400);

$db = new PDO('sqlite:../sql/OIS.db');

$stmtStr = "SELECT 1 FROM tax WHERE type = :type AND percentage = :percentage";
$stmt = $db->prepare($stmtStr);
$stmt->bindParam(':type',       $tax['TaxType'], PDO::PARAM_STR);
$stmt->bindParam(':percentage', $tax['TaxPercentage'], PDO::PARAM_STR);
$stmt->execute();

$results = $stmt->fetchAll();

if (count($results) == 0) // insert
    $stmtStr = "INSERT INTO tax(type, country_region, description, percentage) VALUES (:type, :country_region, :description, :percentage)";
else // update
    $stmtStr = "UPDATE tax SET country_region = :country_region, description = :description WHERE type = :type AND percentage = :percentage";

$stmt = $db->prepare($stmtStr);

$stmt->bindParam(':type',           $tax['TaxType'], PDO::PARAM_STR);
$stmt->bindParam(':country_region', $tax['TaxCountryRegion'], PDO::PARAM_STR);
$stmt->bindParam(':description',    $tax['Description'], PDO::PARAM_STR);
$stmt->bindParam(':percentage',     $tax['TaxPercentage'], PDO::PARAM_STR);

$stmt->execute();

echo(json_encode($tax));
