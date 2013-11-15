function getUrlVars() {
    var vars = {};
    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

function loadProduct() {
    var id = getUrlVars()['ProductCode'];
    if (id == undefined)
        return;

    $.getJSON("api/getProduct.php", { ProductCode: decodeURI(id) })
        .done(function(data) {
            $(document).attr('title', 'Show Product #' + data.ProductCode);

            $('#ProductCode').attr('value', data.ProductCode);
            $('#ProductDescription').attr('value', data.ProductDescription);
            $('#UnitPrice').attr('value', data.UnitPrice + " €");

            /*
            $('#InvoiceNo').text("FT SEQ/" + data.InvoiceNo);
            $('#InvoiceDate').attr("datetime", data.InvoiceDate).text(data.InvoiceDate);

            //<a href="showCostumer.html?CustomerID=555560">555560</a>
            $('#CustomerId').html("<a href=\"showCostumer.html?CustomerID=" + data.Customer.CustomerId + "\">" + data.Customer.CustomerId + "</a");
            $('#CompanyName').text(data.Customer.CompanyName);

            $('#TaxPayable').html(data.DocumentTotals.TaxPayable + " &euro;");
            $('#NetTotal').html(data.DocumentTotals.NetTotal + " &euro;");
            $('#GrossTotal').html(data.DocumentTotals.GrossTotal + " &euro;");

            var lines = data.Line;
            for (var i = 0; i < lines.length; i++)
            {
                var placeholder = '<div class="_invoice_line _sub_row"> \
                    <div class="_row _line_number"> \
                        <label>Line #</label> \
                        <label class="LineNumber">N/A</label> \
                    </div> \
                    <div class="_row _product_code"> \
                        <label>Product Code</label> \
                        <label class="ProductCode">N/A</label> \
                    </div> \
                    <div class="_row _product_description"> \
                        <label>Product Description</label> \
                        <label class="_five_hundred ProductDescription">N/A</label> \
                    </div> \
                    <div class="_row _quantity"> \
                        <label>Quantity</label> \
                        <label class="Quantity">N/A</label> \
                    </div> \
                    <div class="_row _unit_price"> \
                        <label>Unit Price</label> \
                        <label class="UnitPrice">N/A</label> \
                    </div> \
                    <div class="_row _credit_amount"> \
                        <label>Credit Amount</label> \
                        <label class="CreditAmount">N/A</label> \
                    </div> \
                    <div class="_row _tax_type"> \
                        <label>Tax Type</label> \
                        <label class="TaxType">N/A</label> \
                    </div> \
                    <div class="_row _tax_percentage"> \
                        <label>Tax Percentage</label> \
                        <label class="TaxPercentage">N/A</label> \
                    </div> \
                </div>';

                $('._line_title').append(placeholder);
                $('.LineNumber:last').text(lines[i].LineNumber);
                $('.ProductCode:last').html("<a href=\"showProduct.html&ProductCode=" + lines[i].Product.ProductCode + "\">"  + lines[i].Product.ProductCode +  "</a>");
                $('.ProductDescription:last').text(lines[i].Product.ProductDescription);
                $('.Quantity:last').text(lines[i].Quantity);
                $('.UnitPrice:last').html(lines[i].UnitPrice + " &euro;");
                $('.TaxPercentage:last').text(lines[i].Tax.TaxPercentage + " %");
                $('.TaxType:last').text(lines[i].Tax.TaxType);
                $('.CreditAmount:last').html(lines[i].CreditAmount + " &euro;");
            }
            */
        });
}
