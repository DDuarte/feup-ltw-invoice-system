<?php

require_once 'xml_utils.php';

class Customer
{
    public function queryDbById($customerId, $db = null)
    {
        if (empty($customerId) || !is_numeric($customerId))
        {
            return 400;
        }

        if (!$db)
            $db = new PDO('sqlite:../sql/OIS.db');

        $customerStmt = $db->prepare('SELECT tax_id, company_name, email, detail, city, postal_code, country_code FROM customer WHERE id = :id');
        $customerStmt->bindParam(':id', $customerId, PDO::PARAM_INT);
        $customerStmt->execute();

        $customerResult = $customerStmt->fetch();

        if ($customerResult == null)
        {
            return 404;
        }

        $this->_customerId = $customerId;
        $this->_accountId = 'Desconhecido';
        $this->_customerTaxID = $customerResult['tax_id'];
        $this->_companyName = $customerResult['company_name'];
        $this->_addressDetail = $customerResult['detail'];
        $this->_city = $customerResult['city'];
        $this->_postalCode = $customerResult['postal_code'];
        $this->_country = $customerResult['country_code'];
        $this->_email = $customerResult['email'];
        $this->_selfBillingIndicator = 0;

        return 0;
    }

    public function toArray()
    {
        $billingAddress = [
            'AddressDetail' => $this->_addressDetail,
            'City' => $this->_city,
            'PostalCode' => $this->_postalCode,
            'Country' => $this->_country
        ];

        return [
            'CustomerID'     => $this->_customerId,
            'AccountID'      => $this->_accountId,
            'CustomerTaxID'  => $this->_customerTaxID,
            'CompanyName'    => $this->_companyName,
            'BillingAddress' => $billingAddress,
            'Email'          => $this->_email,
            'SelfBillingIndicator' => $this->_selfBillingIndicator
        ];
    }

    public function encode($type)
    {
        if ($type != "xml" && $type != "json")
            return "";

        $array = $this->toArray();

        if ($type == "xml")
            return xml_encode(["Customer" => $array]);
        else
            return json_encode($array);
    }

    private $_customerId;
    private $_accountId;
    private $_customerTaxID;
    private $_companyName;
    private $_addressDetail;
    private $_city;
    private $_postalCode;
    private $_country;
    private $_email;
    private $_selfBillingIndicator;
}
