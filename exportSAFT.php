<?php
require_once 'api/details/user_management.php';
require_once 'api/details/xml_utils.php';
require_once 'api/details/invoice.php';
require_once 'api/details/customer.php';
require_once 'api/details/product.php';
redirect_if_not_logged_in();

header("Content-type:application/xml");

try
{
    date_default_timezone_set('GMT');

    if (!array_key_exists('startDate', $_GET) && !array_key_exists('endDate', $_GET)) {
        $startDate = new DateTime('01/01/2000');
        $endDate = new DateTime();
    }
    else
    {
        if (array_key_exists('endDate', $_GET))
            $endDate = new DateTime($_GET['endDate']);
        else
            $endDate = new DateTime();

        if (array_key_exists('startDate', $_GET))
            $startDate = new DateTime($_GET['startDate']);
        else
        {
            $startDate = clone $endDate;
            $startDate->sub(new DateInterval('P1M')); //
        }

    }
} catch (Exception $e)
{
    $xml = [
        'Error' => $e
    ];
    echo xml_encode($xml);
    exit(0);
}

$startDateStr = $startDate->format('Y-m-d');
$endDateStr = $endDate->format('Y-m-d');
$nowStr = (new DateTime())->format('Y-m-d');
$currentYear =  $startDate->format('Y');

$db = new PDO('sqlite:sql/OIS.db');

$invoicesStmt = $db->prepare('SELECT id FROM invoice WHERE billing_date BETWEEN :min AND :max');
$invoicesStmt->bindParam(':min', $startDateStr, PDO::PARAM_STR);
$invoicesStmt->bindParam(':max', $endDateStr, PDO::PARAM_STR);
$invoicesStmt->execute();

$invoiceIds = $invoicesStmt->fetchAll(PDO::FETCH_COLUMN);
$customerIds = [];
$taxIds = [];
$productIds = [];

$invoices = [];
$customers = [];
$taxes = [];
$products = [];

$totalCredit = 0.0;

foreach ($invoiceIds as $id)
{
    $inv = new Invoice();
    $inv->queryDbByNo($id, $db);
    $invoice = $inv->toArray();

    $invoice['InvoiceNo'] = 'FT SEQ/' . $invoice['InvoiceNo'];

    foreach ($invoice['Line'] as &$line)
    {
        $totalCredit += $line['CreditAmount'];
        array_push($productIds, $line['ProductCode']);
        array_push($taxIds, $line['Tax']['TaxId']);
        unset($line['Tax']['TaxId']);
        unset($line['Tax']['Description']);
    }

    array_push($invoices, $invoice);
    array_push($customerIds, $invoice['CustomerID']);
}

$customerIds = array_unique($customerIds, SORT_NUMERIC);
$taxIds = array_unique($taxIds, SORT_NUMERIC);
$productIds = array_unique($productIds, SORT_NUMERIC);

foreach ($customerIds as $id)
{
    $cust = new Customer();
    $cust->queryDbById($id, $db);
    $customer = $cust->toArray();

    array_push($customers, $customer);
}

foreach ($productIds as $id)
{
    $prod = new Product();
    $prod->queryDbById($id, $db);
    $product = $prod->toArray();
    unset($product['UnitPrice']);

    array_push($products, $product);
}

foreach ($taxIds as $id)
{
    $t = new Tax();
    $t->queryDbById($id, $db);
    $tax = $t->toArray();

    unset($tax['TaxId']);

    array_push($taxes, $tax);
}

$taxes = count($taxes) > 0 ? ['TaxTableEntry' => $taxes] : [];

$xml = [
    'AuditFile' => [
        'Header' => [
            'AuditFileVersion' => '1.03_01',
            'CompanyID' => 'OIS 12345',
            'TaxRegistrationNumber' => '999999999',
            'TaxAccountingBasis' => 'F',
            'CompanyName' => 'OIS, Lda.',
            'CompanyAddress' => [
                'AddressDetail' => 'Rua Dr. Roberto Frias, s/n',
                'City' => 'Porto',
                'PostalCode' => '4200-465',
                'Country' => 'PT'
            ],
            'FiscalYear' => $currentYear,
            'StartDate' => $startDateStr,
            'EndDate' => $endDateStr,
            'CurrencyCode' => 'EUR',
            'DateCreated' => $nowStr,
            'TaxEntity' => 'Global',
            'ProductCompanyTaxID' => '999999999',
            'SoftwareCertificateNumber' => '0',
            'ProductID' => 'OIS/OIS',
            'ProductVersion' => '1.2.0'
        ],
        'MasterFiles' => [
            'Customer' => $customers,
            'Product' => $products,
            'TaxTable' => $taxes
        ],
        'SourceDocuments' => [
            'SalesInvoices' => [
                'NumberOfEntries' => count($invoices),
                'TotalDebit' => 0.00,
                'TotalCredit' => $totalCredit,
                'Invoice' => $invoices
            ]
        ]
    ]
];

if (count($customers) == 0)
    unset($xml["AuditFile"]["MasterFiles"]["Customer"]);
if (count($products) == 0)
    unset($xml["AuditFile"]["MasterFiles"]["Product"]);
if (count($taxes) == 0)
    unset($xml["AuditFile"]["MasterFiles"]["TaxTable"]);
if (count($invoices) == 0)
    unset($xml["AuditFile"]["SourceDocuments"]);

$auditFileAttributes = [
    'xmlns' => 'urn:OECD:StandardAuditFile-Tax:PT_1.03_01',
    'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
    // 'xmlns:spi' => 'http://Empresa.pt/invoice1',
    'xmlns:saf' => 'urn:OECD:StandardAuditFile-Tax:PT_1.03_01',
    'xsi:schemaLocation' => 'urn:OECD:StandardAuditFile-Tax:PT_1.03_01 http://serprest.pt/tmp/SAFTPT-1.03_01.xsd'
];

echo xml_encode($xml, $auditFileAttributes);
