<?php

require_once 'xml_utils.php';
require_once 'product.php';

class InvoiceLine
{
    public function initFromDbLine($db, $line)
    {
        $taxId = $line['tax_id'];

        /** @var $db PDO */
        $taxStmt = $db->prepare('SELECT type, percentage FROM tax WHERE id = :id');
        $taxStmt->bindParam(':id', $taxId, PDO::PARAM_INT);
        $taxStmt->execute();

        $taxResult = $taxStmt->fetch();

        if ($taxResult == null)
            return 404;

        $this->_taxType = $taxResult['type'];
        $this->_taxPercentage = (int)$taxResult['percentage'];

        $this->_number      =   (int)$line['line_number'];
        $this->_quantity    =   (int)$line['quantity'];
        $this->_unitPrice   =   (float)$line['unit_price'];

        $this->_creditAmount = $this->_quantity * $this->_unitPrice;

        $this->_product = new Product;
        $error = $this->_product->queryDbById((int)$line['product_id']);
        if ($error)
            return $error;

        return 0;
    }

    public function getTaxPercentage()
    {
        return $this->_taxPercentage;
    }

    public function getCreditAmount()
    {
        return $this->_creditAmount;
    }

    public function toArray()
    {
        $tax = [
            'TaxType' => $this->_taxType,
            'TaxPercentage' => $this->_taxPercentage
        ];

        return [
            'LineNumber'   => $this->_number,
            'ProductCode'  => $this->_product->getCode(),
            'ProductDescription' => $this->_product->getDescription(),
            'Quantity'     => $this->_quantity,
            'UnitOfMeasure' => 'Euro',
            'TaxPointDate' => 'N/A',
            'UnitPrice'    => $this->_unitPrice,
            'CreditAmount' => $this->_creditAmount,
            'Tax'          => $tax
        ];
    }

    private $_number;
    private $_product;
    private $_productDescription;
    private $_quantity;
    private $_unitPrice;
    private $_creditAmount;
    private $_taxType;
    private $_taxPercentage;
}

class Invoice
{
    public function queryDbByNo($invoiceNo)
    {
        if (is_string($invoiceNo) && !is_numeric($invoiceNo))
        {
            $parseError = parseInvoiceNoFromString($invoiceNo, $invoiceNo);
            if ($parseError) return $parseError;
        }

        $db = new PDO('sqlite:../sql/OIS.db');

        $invoiceStmt = $db->prepare('SELECT billing_date, customer_id, user_id, strftime(\'%Y-%m-%dT%H:%M:%S\', entry_date) as entry_datef FROM invoice WHERE id = :id');
        $invoiceStmt->bindParam(':id', $invoiceNo, PDO::PARAM_INT);
        $invoiceStmt->execute();

        $invoiceResult = $invoiceStmt->fetch();

        if ($invoiceResult == null)
        {
            return 404;
        }

        $linesStmt = $db->prepare('SELECT product_id, line_number, quantity, unit_price, tax_id FROM line WHERE invoice_id = :id');
        $linesStmt->bindParam(':id', $invoiceNo, PDO::PARAM_INT);
        $linesStmt->execute();

        $linesResult = $linesStmt->fetchAll();

        $this->_lines = [];

        $this->_taxPayable = 0.0;
        $this->_netTotal = 0.0;

        if ($linesResult != null)
        {
            $i = 0;
            foreach ($linesResult as $line)
            {
                $invoiceLine = new InvoiceLine;
                $error = $invoiceLine->initFromDbLine($db, $line);

                if ($error)
                    return $error;

                $this->_taxPayable += $invoiceLine->getTaxPercentage() / 100.0 * $invoiceLine->getCreditAmount();
                $this->_netTotal += $invoiceLine->getCreditAmount();

                $this->_lines[$i++] = $invoiceLine;
            }
        }

        $this->_no = $invoiceNo;
        $this->_date = $invoiceResult['billing_date'];
        $this->_customerID = (int)$invoiceResult['customer_id'];
        $this->_userID = (int)$invoiceResult['user_id'];
        $this->_entryDate = $invoiceResult['entry_datef'];
        $this->_grossTotal = $this->_taxPayable + $this->_netTotal;

        return 0;
    }

    public function toArray()
    {
        $lines = [];

        $i = 0;
        foreach ($this->_lines as $line)
        {
            $l = $line->toArray();
            $l['TaxPointDate'] = $this->_date;
            $lines[$i++] = l;
        }

        $documentTotals = [
            'TaxPayable' => $this->_taxPayable,
            'NetTotal' => $this->_netTotal,
            'GrossTotal' => $this->_grossTotal
        ];

        $documentStatus = [
            'InvoiceStatus' => 'N',
            'InvoiceStatusDate' => $this->_entryDate,
            'SourceID' => $this->_userID,
            'SourceBilling' => 'P'
        ];

        $specialRegimes = 
            'SelfBillingIndicator' => 0,
            'CashVATSchemeIndicator' => 0,
            'ThirdPartiesBillingIndicator' => 0
        ];

        return [
            'InvoiceNo' => $this->_no,
            'DocumentStatus' => $documentStatus,
            'Hash' => 0,
            'InvoiceDate' => $this->_date,
            'InvoiceType' => 'FT',
            'SpecialRegimes' => $specialRegimes,
            'SourceID' => $this->_userID,
            'SystemEntryDate' => $this->_entryDate,
            'CustomerId' => $this->_customerID,
            'Line' => $lines,
            'DocumentTotals' => $documentTotals
        ];
    }

    public function encode($type)
    {
        if ($type != "xml" && $type != "json")
            return "";

        $array = $this->toArray();

        if ($type == "xml")
            return xml_encode(["Invoice" => $array]);
        else
            return json_encode($array);
    }

    private $_no;
    private $_date;
    private $_customerID;
    private $_userID;
    private $_lines;
    private $_taxPayable;
    private $_netTotal;
    private $_grossTotal;
}

function parseInvoiceNoFromString($invoiceNoStr, &$invoiceNo)
{
    if (empty($invoiceNoStr))
    {
        return 400;
    }

    $regexMatches = preg_match("/^([^ ]+) ([^\/^ ]+)\/([0-9]+)$/", $invoiceNoStr, $inv);

    if ($regexMatches == 0)
    {
        return 400;
    }
    else if ($inv[1] != 'FT' || $inv[2] != 'SEQ')
    {
        return 404;
    }

    $invoiceNo = $inv[3];

    return 0;
}
