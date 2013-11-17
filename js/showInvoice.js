function getUrlVars() {
    var vars = {};
    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function loadInvoice() {
    var id = getUrlVars()['InvoiceNo'];
    if (id == undefined)
        return;

    $.getJSON("api/getFullInvoice.php", {
        InvoiceNo: decodeURI(id)
    })
        .done(function (data) {
            $(document).attr('title', 'Show Invoice #' + data.InvoiceNo);

            $('#InvoiceNo').attr('value', "FT SEQ/" + data.InvoiceNo);
            $('#InvoiceDate').attr('value', data.InvoiceDate);

            $('#CustomerId').attr('value', data.Customer.CustomerId);
            $('#CustomerIdLink').attr('href', "showCustomer.html?CustomerId=" + data.Customer.CustomerId);

            $('#CompanyName').attr('value', data.Customer.CompanyName);
            $('#TaxPayable').attr('value', data.DocumentTotals.TaxPayable + " €");
            $('#NetTotal').attr('value', data.DocumentTotals.NetTotal + " €");
            $('#GrossTotal').attr('value', data.DocumentTotals.GrossTotal + " €");

            var lines = data.Line;
            for (var i = 0; i < lines.length; i++) {
                var placeholder = '<div class="_invoice_line _sub_row"> \
                    <div class="_row _line_number"> \
                        <label>Line #</label> \
                        <input type="text" class="LineNumber" value="N/A" disabled> \
                    </div> \
                    <div class="_row _product_code"> \
                        <label>Product Code</label> \
                        <input type="number" class="ProductCode" value="N/A" disabled> \
                        <a class="ProductCodeLink" target="_blank"><img title="Product Info" src="images/icon_arrow.gif"></a> \
                    </div> \
                    <div class="_row _product_description _five_hundred"> \
                        <label>Product Description</label> \
                        <input type="text" class="ProductDescription" value="N/A" disabled> \
                    </div> \
                    <div class="_row _quantity"> \
                        <label>Quantity</label> \
                        <input type="number" class="Quantity" value="N/A" disabled> \
                    </div> \
                    <div class="_row _unit_price"> \
                        <label>Unit Price</label> \
                        <input type="text" class="UnitPrice" value="N/A" disabled> \
                    </div> \
                    <div class="_row _credit_amount"> \
                        <label>Credit Amount</label> \
                        <input type="text" class="CreditAmount" value="N/A" disabled> \
                    </div> \
                    <div class="_row _tax_type"> \
                        <label>Tax Type</label> \
                        <input type="text" class="TaxType" value="N/A" disabled> \
                    </div> \
                    <div class="_row _tax_percentage"> \
                        <label>Tax Percentage</label> \
                        <input type="text" class="TaxPercentage" value="N/A" disabled> \
                    </div> \
                </div>';

                $('._line_title').append(placeholder);
                $('.LineNumber:last').attr('value', lines[i].LineNumber);

                $('.ProductCode:last').attr('value', lines[i].Product.ProductCode);
                $('.ProductCodeLink:last').attr('href', "showProduct.html?ProductCode=" + lines[i].Product.ProductCode);

                $('.ProductDescription:last').attr('value', lines[i].Product.ProductDescription);
                $('.Quantity:last').attr('value', lines[i].Quantity);
                $('.UnitPrice:last').attr('value', lines[i].UnitPrice + " €");
                $('.TaxPercentage:last').attr('value', lines[i].Tax.TaxPercentage + " %");
                $('.TaxType:last').attr('value', lines[i].Tax.TaxType);
                $('.CreditAmount:last').attr('value', lines[i].CreditAmount + " €");
            }
        });
}
