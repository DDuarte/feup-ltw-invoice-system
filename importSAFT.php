<?php

require 'api/details/xml_utils.php';

$str = '<?xml version="1.0"?>
<AuditFile xmlns="urn:OECD:StandardAuditFile-Tax:PT_1.03_01" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:saf="urn:OECD:StandardAuditFile-Tax:PT_1.03_01" xsi:schemaLocation="urn:OECD:StandardAuditFile-Tax:PT_1.03_01 http://serprest.pt/tmp/SAFTPT-1.03_01.xsd">
    <Header>
        <AuditFileVersion>1.03_01</AuditFileVersion>
        <CompanyID>OIS 12345</CompanyID>
        <TaxRegistrationNumber>999999999</TaxRegistrationNumber>
        <TaxAccountingBasis>F</TaxAccountingBasis>
        <CompanyName>OIS, Lda.</CompanyName>
        <CompanyAddress>
            <AddressDetail>Rua Dr. Roberto Frias, s/n</AddressDetail>
            <City>Porto</City>
            <PostalCode>4200-465</PostalCode>
            <Country>PT</Country>
        </CompanyAddress>
        <FiscalYear>2013</FiscalYear>
        <StartDate>2013-12-03</StartDate>
        <EndDate>2014-01-03</EndDate>
        <CurrencyCode>EUR</CurrencyCode>
        <DateCreated>2013-12-03</DateCreated>
        <TaxEntity>Global</TaxEntity>
        <ProductCompanyTaxID>999999999</ProductCompanyTaxID>
        <SoftwareCertificateNumber>0</SoftwareCertificateNumber>
        <ProductID>OIS/OIS</ProductID>
        <ProductVersion>1.2.0</ProductVersion>
    </Header>
    <MasterFiles>
        <Customer/>
        <Product/>
        <TaxTable>
            <TaxTableEntry/>
        </TaxTable>
    </MasterFiles>
    <SourceDocuments>
        <SalesInvoices>
            <NumberOfEntries>0</NumberOfEntries>
            <TotalDebit>0</TotalDebit>
            <TotalCredit>0</TotalCredit>
            <Invoice/>
        </SalesInvoices>
    </SourceDocuments>
</AuditFile>
';

$result = xml_decode($str);

echo json_encode($result);