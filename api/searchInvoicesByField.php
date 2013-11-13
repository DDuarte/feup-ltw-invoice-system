<?php
    header("Content-type:application/json");
    require 'details/invoice.php';
    require 'details/utils.php';

    function ConvertIfNeeded($fieldInUse, $operation, $value)
    {
        if ($fieldInUse == "GrossTotal")
            $value = strval($value);
        
        if ($operation == "contains")
            return "%" . $value . "%";

        return $value;
    }

    function ParseValue($fileInUse, &$value)
    {
        if ($fileInUse == "InvoiceNo")
        {
            $error = parseInvoiceNoFromString($value, $value);
            if ($error) return $error;
        }

        return 0;
    }

    $param_type = [
        "InvoiceNo" => PDO::PARAM_INT,
        "InvoiceDate" => PDO::PARAM_STR,
        "CompanyName" => PDO::PARAM_STR,
        "GrossTotal" => PDO::PARAM_STR
    ];

    $queries = [
        "InvoiceNo" => [
            "range" => "SELECT id FROM invoice WHERE id BETWEEN :min AND :max",
            "equal" => "SELECT id FROM Invoice WHERE id = :value",
            "contains" => "SELECT id FROM Invoice WHERE id LIKE '%:value%'",
            "min" => "SELECT MIN(id) AS id FROM Invoice",
            "max" => "SELECT MAX(id) AS id FROM Invoice"
        ],
        "InvoiceDate" => [
            "range" => "SELECT id FROM invoice WHERE billing_date BETWEEN :min AND :max",
            "equal" => "SELECT id FROM invoice WHERE billing_date = :value",
            "contains" => "SELECT id FROM invoice WHERE billing_date LIKE :value",
            "min" => "SELECT id FROM invoice WHERE billing_date IN (SELECT MIN(billing_date) FROM invoice)",
            "max" => "SELECT id FROM invoice WHERE billing_date IN (SELECT MAX(billing_date) FROM invoice)"
        ],
        "CompanyName" => [
            "range" => "SELECT invoice.id AS id FROM invoice JOIN customer on invoice.customer_id = customer.id WHERE customer.company_name BETWEEN :min AND :max",
            "equal" => "SELECT invoice.id AS id FROM invoice JOIN customer on invoice.customer_id = customer.id WHERE customer.company_name = :value",
            "contains" => "SELECT invoice.id AS id FROM invoice JOIN customer on invoice.customer_id = customer.id WHERE customer.company_name LIKE :value",
            "min" => "SELECT invoice.id AS id FROM invoice JOIN customer on invoice.customer_id = customer.id WHERE customer.company_name IN (SELECT MIN(company_name) FROM customer)",
            "max" => "SELECT invoice.id AS id FROM invoice JOIN customer on invoice.customer_id = customer.id WHERE customer.company_name IN (SELECT MAX(company_name) FROM customer)"
        ],
        "GrossTotal" => [
            "range" => "SELECT invoice.id AS id
                        FROM invoice JOIN line ON invoice.id = line.invoice_id join tax ON line.tax_id = tax.id 
                        GROUP BY invoice.id
                        HAVING SUM((tax.percentage / 100.0 + 1) * line.quantity * line.unit_price) BETWEEN :min AND :max",
            "equal" => "SELECT invoice.id AS id
                        FROM invoice JOIN line ON invoice.id = line.invoice_id join tax ON line.tax_id = tax.id 
                        GROUP BY invoice.id
                        HAVING SUM((tax.percentage / 100.0 + 1) * line.quantity * line.unit_price) = :value",  
            "contains" => "SELECT invoice.id AS id
                        FROM invoice JOIN line ON invoice.id = line.invoice_id join tax ON line.tax_id = tax.id 
                        GROUP BY invoice.id
                        HAVING SUM((tax.percentage / 100.0 + 1) * line.quantity * line.unit_price) LIKE :value", 
            "min" => "SELECT line.invoice_id AS id
                        FROM line JOIN tax ON line.tax_id = tax.id 
                        GROUP BY line.invoice_id
                        HAVING SUM((tax.percentage / 100.0 + 1) * line.quantity * line.unit_price) IN 
                            (   SELECT MIN(grossTotal) FROM (SELECT SUM((tax.percentage / 100.0 + 1) * line.quantity * line.unit_price) AS grossTotal
                                FROM line JOIN tax ON line.tax_id = tax.id 
                                GROUP BY line.invoice_id)
                            )",
            "max" => "SELECT line.invoice_id AS id
                        FROM line JOIN tax ON line.tax_id = tax.id 
                        GROUP BY line.invoice_id
                        HAVING SUM((tax.percentage / 100.0 + 1) * line.quantity * line.unit_price) IN 
                            (   SELECT MAX(grossTotal) FROM (SELECT SUM((tax.percentage / 100.0 + 1) * line.quantity * line.unit_price) AS grossTotal
                                FROM line JOIN tax ON line.tax_id = tax.id 
                                GROUP BY line.invoice_id)
                            )"

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

        ParseValue($field, $value[0]);
        ParseValue($field, $value[1]);

        $minValue = ConvertIfNeeded($field, $op, $value[0]);
        $stmt->bindParam(':min', $minValue, $param_type[$field]);
        $maxValue = ConvertIfNeeded($field, $op, $value[1]);
        $stmt->bindParam(':max', $maxValue, $param_type[$field]);
    }
    else if ($op != "min" && $op != "max")
    {
        ParseValue($field, $value);
        $convertedValue = ConvertIfNeeded($field, $op, $value);
        $stmt->bindParam(':value', $convertedValue, $param_type[$field]);
    }
    


    $stmt->execute();
    $results = $stmt->fetchAll();

    $toBeReturned = [];

    $i = 0;
    foreach($results as $result)
    {
        $invoice = new Invoice;
        $invoice->queryDbByNo($result['id']);
        $toBeReturned[$i++] = $invoice->toArray();
    }

    echo json_encode($toBeReturned);
