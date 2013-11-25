<?php
header("Content-type:application/json");
require 'details/customer.php';
require 'details/utils.php';

$error400 = '{"error":{"code":400,"reason":"Bad request"}}';

function ConvertIfNeeded($fieldInUse, $operation, $value)
{
    if ($operation == "contains")
        return "%" . $value . "%";

    return $value;
}

$param_type = [
    "CustomerId" => PDO::PARAM_INT,
    "CustomerTaxID" => PDO::PARAM_INT,
    "CompanyName" => PDO::PARAM_STR
];

function GetValidParamType($fieldInUse, $operation)
{
    global $param_type;
    return $operation == "contains" ? PDO::PARAM_STR : $param_type[$fieldInUse];
}

$queries = [
    "CustomerId" => [
        "range" => "SELECT id FROM customer WHERE id BETWEEN :min AND :max",
        "equal" => "SELECT id FROM customer WHERE id = :value",
        "contains" => "SELECT id FROM customer WHERE CAST(id AS TEXT) LIKE :value",
        "min" => "SELECT id FROM customer WHERE id >= :value",
        "max" => "SELECT if FROM customer WHERE id <= :value"
        // "min" => "SELECT MIN(id) AS id FROM customer",
        // "max" => "SELECT MAX(id) AS id FROM customer"
    ],
    "CustomerTaxID" => [
        "range" => "SELECT id FROM customer WHERE tax_id BETWEEN :min AND :max",
        "equal" => "SELECT id FROM customer WHERE tax_id = :value",
        "contains" => "SELECT id FROM customer WHERE CAST(tax_id AS TEXT) LIKE :value",
        "min" => "SELECT id FROM customer WHERE tax_id >= :value",
        "max" => "SELECT id FROM customer WHERE tax_id <= :value"
        // "min" => "SELECT id FROM customer WHERE tax_id IN (SELECT MIN(tax_id) FROM customer)",
        // "max" => "SELECT id FROM customer WHERE tax_id IN (SELECT MAX(tax_id) FROM customer)"
    ],
    "CompanyName" => [
        "range" => "SELECT id FROM customer WHERE company_name BETWEEN :min AND :max",
        "equal" => "SELECT id FROM customer WHERE company_name = :value",
        "contains" => "SELECT id FROM customer WHERE company_name LIKE :value",
        "min" => "SELECT id FROM customer WHERE company_name >= :value",
        "max" => "SELECT id FROM customer WHERE company_name <= :value"
        // "min" => "SELECT id FROM customer WHERE company_name IN (SELECT MIN(company_name) FROM customer)",
        // "max" => "SELECT id FROM customer WHERE company_name IN (SELECT MAX(company_name) FROM customer)"
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
else
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
    $customer = new Customer;
    $customer->queryDbById($result['id']);
    $toBeReturned[$i++] = $customer->toArray();
}

echo json_encode($toBeReturned);
