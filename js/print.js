function getUrlVars() {
    var vars = {};
    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

function loadInvoice() {
    var id = getUrlVars()['InvoiceNo'];
    if (id == undefined)
        return;

    $.getJSON( "api/getFullInvoice.php", { InvoiceNo: decodeURI(id) } )
        .done(function( data ) {
            $('#InvoiceNo').text(data.InvoiceNo);
            $('#InvoiceDate').attr("datetime", data.InvoiceDate).text(data.InvoiceDate);

            $('#CustomerId').text(data.Customer.CustomerId);
            $('#CustomerTaxID').text(data.Customer.CustomerTaxID);
            $('#CompanyName').text(data.Customer.CompanyName);
            $('#AddressDetail').text(data.Customer.BillingAddress.AddressDetail);
            $('#City').text(data.Customer.BillingAddress.City);
            $('#PostalCode').text(data.Customer.BillingAddress.PostalCode);
            $('#Country').text(data.Customer.BillingAddress.Country);
            $('#Email').attr("href", "mailto:" + data.Customer.Email).text(data.Customer.Email);

            $('#TaxPlayable').text(data.DocumentTotals.TaxPayable);
            $('#NetTotal').text(data.DocumentTotals.NetTotal);
            $('#GrossTotal').text(data.DocumentTotals.GrossTotal);

            var lines = data.Line;
            for (var i = 0; i < lines.length; i++)
            {
                var a = '<tr>; \
                    <td><span class="LineNumber">' + i + '</span></td> \
                    <td># <span class="ProductCode">NO CODE</span></td> \
                    <td><span class="ProductDescription">NO DESCR</span></td> \
                    <td><span class="Quantity">NO QUANTITY</span></td> \
                    <td><span class="UnitPrice">NO PRICE</span> &euro;</td> \
                    <td><span class="TaxPercentage">NO TAX %</span>% <span class="TaxType">NO TAX TYPE</span></td> \
                    <td colspan="2"><span class="CreditAmount">NO CREDIT AMOUNT</span> &euro;</td> \
                </tr>';

                $(a).insertAfter('#lines tr:not(.totals):last');
                $('.LineNumber:last').text(lines[i].LineNumber);
                $('.ProductCode:last').text(lines[i].Product.ProductCode);
                $('.ProductDescription:last').text(lines[i].Product.ProductDescription);
                $('.Quantity:last').text(lines[i].Quantity);
                $('.UnitPrice:last').text(lines[i].UnitPrice);
                $('.TaxPercentage:last').text(lines[i].Tax.TaxPercentage);
                $('.TaxType:last').text(lines[i].Tax.TaxType);
                $('.CreditAmount:last').text(lines[i].CreditAmount);
            }
        });
}