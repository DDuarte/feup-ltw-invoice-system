<?php

header("Content-type:application/json");
require_once 'details/user_management.php';

$error403_auth = '{"error":{"code":403,"reason":"Not authenticated"}}';
$error403_perm = '{"error":{"code":403,"reason":"No permission"}}';

if (!is_logged_in())
    exit($error403_auth);

if (!is_admin())
    exit($error403_perm);

$db = new PDO('sqlite:../sql/OIS.db');

$nCust = $db->exec('DELETE FROM customer');
$nInv =  $db->exec('DELETE FROM invoice');
$nLine = $db->exec('DELETE FROM line');
$nTax =  $db->exec('DELETE FROM tax');
$nProd = $db->exec('DELETE FROM product');

$result = [
    'CustomersDeleted' => $nCust,
    'InvoicesDeleted'  => $nInv,
    'LinesDeleted'     => $nLine,
    'TaxesDeleted'     => $nTax,
    'ProductsDeleted'  => $nProd
];

echo json_encode($result);
