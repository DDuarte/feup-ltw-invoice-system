<?php
header("Content-type:application/json");
require 'details/product.php';
require 'details/utils.php';

$error400 = '{"error":{"code":400,"reason":"Bad request"}}';

function ConvertIfNeeded($fieldInUse, $operation, $value)
{
    if ($operation == "contains")
        return "%" . $value . "%";

    return $value;
}

$param_type = [
    "ProductCode" => PDO::PARAM_INT,
    "ProductDescription" => PDO::PARAM_STR
];

function GetValidParamType($fieldInUse, $operation)
{
    global $param_type;
    return $operation == "contains" ? PDO::PARAM_STR : $param_type[$fieldInUse];
}

$queries = [
    "ProductCode" => [
        "range" => "SELECT id FROM product WHERE id BETWEEN :min AND :max",
        "equal" => "SELECT id FROM product WHERE id = :value",
        "contains" => "SELECT id FROM product WHERE CAST(id AS TEXT) LIKE :value",
        "min" => "SELECT MIN(id) AS id FROM product",
        "max" => "SELECT MAX(id) AS id FROM product"
    ],
    "ProductDescription" => [
        "range" => "SELECT id FROM product WHERE description BETWEEN :min AND :max",
        "equal" => "SELECT id FROM product WHERE description = :value",
        "contains" => "SELECT id FROM product WHERE description LIKE :value",
        "min" => "SELECT id FROM product WHERE description IN (SELECT MIN(description) FROM invoice)",
        "max" => "SELECT id FROM product WHERE description IN (SELECT MAX(description) FROM invoice)"
    ],
];

$fieldError = getParameter("field", $field);
if ($fieldError)
{
    $error =  "error" . $fieldError;
    exit($$error);
}

$opError = getParameter("op", $op);
if ($opError)
{
    $error =  "error" . $opError;
    exit($$error);
}

$valueError = getParameter("value", $value);
if ($valueError && $op != "min" && $op != "max")
{
    $error =  "error" . $valueError;
    exit($$error);
}

if (!(array_key_exists($field, $queries) && array_key_exists($op, $queries[$field])))
{
    exit($error400);
}

$query = $queries[$field][$op];

$db = new PDO('sqlite:../sql/OIS.db');

$stmt = $db->prepare($query);

if ($op == "range")
{
    if (!is_array($value) || !count($value) == 2)
        exit($error400);

    $minValue = ConvertIfNeeded($field, $op, $value[0]);
    $stmt->bindParam(':min', $minValue, GetValidParamType($field, $op));
    $maxValue = ConvertIfNeeded($field, $op, $value[1]);
    $stmt->bindParam(':max', $maxValue, GetValidParamType($field, $op));
}
else if ($op != "min" && $op != "max")
{
    $convertedValue = ConvertIfNeeded($field, $op, $value[0]);
    $stmt->bindParam(':value', $convertedValue, GetValidParamType($field, $op));
}

$stmt->execute();
$results = $stmt->fetchAll();

$toBeReturned = [];

$i = 0;
foreach ($results as $result)
{
    $product = new Product;
    $product->queryDbById($result['id']);
    $toBeReturned[$i++] = $product->toArray();
}

echo json_encode($toBeReturned);
