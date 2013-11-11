<?php

require 'xml_utils.php';

class InvoiceLine
{
    public function initFromDbLine($db, $line)
    {
    	$taxId = $line['tax_id'];
		
		$taxStmt = $db->prepare('SELECT type, percentage FROM tax WHERE id = :id');
		$taxStmt->bindParam(':id', $taxId, PDO::PARAM_INT);
		$taxStmt->execute();
        
        $taxResult = $taxStmt->fetch();
		
        if ($taxResult == null)
            return 404;
        
        $this->_taxType = $taxResult['type'];
        $this->_taxPercentage = (int)$taxResult['percentage'];
        
        $this->_number      =   (int)$line['line_number'];
        $this->_productCode =   (int)$line['product_id'];
        $this->_quantity    =   (int)$line['quantity'];
        $this->_unitPrice   =   (float)$line['unit_price'];
        
        $this->_creditAmount = $this->_quantity * $this->_unitPrice;
        
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
        $tax = Array(
			'TaxType' => $this->_taxType,
			'TaxPercentage' => $this->_taxPercentage
		);
        
        return Array(
			'LineNumber' =>     $this->_number,
			'ProductCode' =>    $this->_productCode,
			'Quantity' =>       $this->_quantity,
			'UnitPrice' =>      $this->_unitPrice,
			'CreditAmount' => $this->_creditAmount,
			'Tax' => $tax
		);
    }
    
    private $_number;
    private $_productCode;
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
        if (is_string($invoiceNo))
        {
            $parseError = parseInvoiceNoFromString($invoiceNo, $invoiceNo);
            if ($parseError) return $parseError;
        }
    
        $db = new PDO('sqlite:../sql/OIS.db');
        
        $invoiceStmt = $db->prepare('SELECT billing_date, customer_id FROM invoice WHERE id = :id');
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
        
        if ($linesResult == null)
        {
            return 404;
        }
        
        $this->_lines = Array();
        
        $this->_taxPayable = 0.0;
        $this->_netTotal = 0.0;
        
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
        
        $this->_no = $invoiceNo;
        $this->_date = $invoiceResult['billing_date'];
        $this->_customerID = (int)$invoiceResult['customer_id'];
        $this->_grossTotal = $this->_taxPayable + $this->_netTotal;
    
    }
    
    public function toArray()
    {
        $lines = Array();
        
        $i = 0;
        foreach ($this->_lines as $line)
            $lines[$i++] = $line->toArray();
            
        $documentTotals = Array(
            'TaxPayable' => $this->_taxPayable,
            'NetTotal' => $this->_netTotal,
            'GrossTotal' => $this->_grossTotal
        );

        return Array(
            'InvoiceNo' => $this->_no,
            'InvoiceDate' => $this->_date,
            'CustomerID' => $this->_customerID,
            'Line' => $lines,
            'DocumentTotals' => $documentTotals
        );
    }
    
    public function encode($type)
    {
        if ($type != "xml" && $type != "json")
            return "";
            
        $array = $this->toArray();
        
        if ($type == "xml")
            return xml_encode(Array("Invoice" => $array));
        else
            return json_encode($array);
    }
    
    private $_no;
    private $_date;
    private $_customerID;
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

?>

