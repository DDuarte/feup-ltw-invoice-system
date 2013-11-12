<?php

require 'xml_utils.php';

class Product
{
    public function queryDbById($productId)
    {
        if (empty($productId) || !is_numeric($productId))
        {
            return 400;
        }

        $db = new PDO('sqlite:../sql/OIS.db');

        $productStmt = $db->prepare('SELECT description, unit_price FROM product WHERE id = :id');
        $productStmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $productStmt->execute();

        $productResult = $productStmt->fetch();

        if ($productResult == null)
        {
            return 404;
        }

        $this->_type = 'P';
        $this->_code = $productId;
        $this->_description = $productResult['description'];
        $this->_numberCode = $this->_code;
        $this->_unitPrice = (float)$productResult['unit_price'];

        return 0;
    }

    public function toArray()
    {
        return Array(
            'ProductType' => $this->_type,
            'ProductCode' => $this->_code,
            'ProductDescription' => $this->_description,
            'ProductNumberCode' => $this->_numberCode,
            'UnitPrice' => $this->_unitPrice
        );
    }

    public function encode($type)
    {
        if ($type != "xml" && $type != "json")
            return "";

        $array = $this->toArray();

        if ($type == "xml")
        {
            unset($array['UnitPrice']); // unit price is not present in SAFT
            return xml_encode(Array("Product" => $array));
        }
        else
            return json_encode($array);
    }
    
    private $_type;
    private $_code;
    private $_description;
    private $_numberCode;
    private $_unitPrice;
}
