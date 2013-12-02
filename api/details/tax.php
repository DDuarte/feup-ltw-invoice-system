<?php

require_once 'xml_utils.php';

class Tax
{
    public function queryDbById($taxId, $db = null)
    {
        if (empty($taxId) || !is_numeric($taxId))
        {
            return 400;
        }

        if (!$db)
            $db = new PDO('sqlite:../sql/OIS.db');

        $taxStmt = $db->prepare('SELECT type, country_region, description, percentage FROM tax WHERE id = :id');
        $taxStmt->bindParam(':id', $taxId, PDO::PARAM_INT);
        $taxStmt->execute();

        $taxResult = $taxStmt->fetch();

        if ($taxResult == null)
        {
            return 404;
        }

        $this->_id = $taxId;
        $this->_type = $taxResult['type'];
        $this->_countryRegion = $taxResult['country_region'];
        $this->_description = $taxResult['description'];
        $this->_percentage = (int)$taxResult['percentage'];

        return 0;
    }

    public function toArray()
    {
        return [
            'TaxType' => $this->_type,
            'TaxCountryRegion' => $this->_countryRegion,
            'TaxCode' => 'NOR',
            'Description' => $this->_description,
            'TaxPercentage' => $this->_percentage
        ];
    }

    public function encode($type)
    {
        if ($type != "xml" && $type != "json")
            return "";

        $array = $this->toArray();

        if ($type == "xml")
            return xml_encode(["TaxTableEntry" => $array]);
        else
            return json_encode($array);
    }

    public function getId() { return $this->_id; }
    public function getType() { return $this->_type; }
    public function getPercentage() { return $this->_percentage; }

    private $_id;
    private $_type;
    private $_countryRegion;
    private $_description;
    private $_percentage;
}
