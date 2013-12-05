function getUrlVars() {
    var vars = {};
    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function addBlankLine() {
    var placeholder = '<div class="_invoice_line _sub_row"> \
                    <div class="_row _line_number"> \
                        <label>Line #</label> \
                        <input type="text" class="LineNumber" value="" required> \
                    </div> \
                    <div class="_row _product_code"> \
                        <label>Product Code</label> \
                        <input type="number" class="ProductCode" value="" required> \
                        <a class="ProductCodeLink" target="_blank"><img title="Product Info" src="images/icon_arrow.gif"></a> \
                    </div> \
                    <div class="_row _product_description _five_hundred"> \
                        <label>Product Description</label> \
                        <input type="text" class="ProductDescription" value="" required> \
                    </div> \
                    <div class="_row _quantity"> \
                        <label>Quantity</label> \
                        <input type="number" class="Quantity" value="" required> \
                    </div> \
                    <div class="_row _unit_price"> \
                        <label>Unit Price</label> \
                        <input type="text" class="UnitPrice" value="" required> \
                    </div> \
                    <div class="_row _credit_amount"> \
                        <label>Credit Amount</label> \
                        <input type="text" class="CreditAmount" value="" required> \
                    </div> \
                    <div class="_row _tax_type"> \
                        <label>Tax Type</label> \
                        <input type="text" class="TaxType" value="" required> \
                    </div> \
                    <div class="_row _tax_percentage"> \
                        <label>Tax Percentage</label> \
                        <input type="text" class="TaxPercentage" value="" required> \
                    </div> \
                </div>';

    $('._line_title').append(placeholder);

            $('#CustomerID').attr('value', data.Customer.CustomerID);
            $('#CustomerIDLink').attr('href', "showCustomer.php?CustomerID=" + data.Customer.CustomerID);
    else
        $('.LineNumber:last').attr('value', 1);

    $('.LineNumber:last').prop('readonly', true);
}

function addLines(data) {
    var lines = data.Line;
    for (var i = 0; i < lines.length; i++) {
        var placeholder = '<div class="_invoice_line _sub_row"> \
                    <div class="_row _line_number"> \
                        <label>Line #</label> \
                        <input type="text" class="LineNumber" value="N/A" readonly> \
                    </div> \
                    <div class="_row _product_code"> \
                        <label>Product Code</label> \
                        <input type="number" class="ProductCode" value="N/A" readonly> \
                        <a class="ProductCodeLink" target="_blank"><img title="Product Info" src="images/icon_arrow.gif"></a> \
                    </div> \
                    <div class="_row _product_description _five_hundred"> \
                        <label>Product Description</label> \
                        <input type="text" class="ProductDescription" value="N/A" readonly> \
                    </div> \
                    <div class="_row _quantity"> \
                        <label>Quantity</label> \
                        <input type="number" class="Quantity" value="N/A" readonly> \
                    </div> \
                    <div class="_row _unit_price"> \
                        <label>Unit Price</label> \
                        <input type="text" class="UnitPrice" value="N/A" readonly> \
                    </div> \
                    <div class="_row _credit_amount"> \
                        <label>Credit Amount</label> \
                        <input type="text" class="CreditAmount" value="N/A" readonly> \
                    </div> \
                    <div class="_row _tax_type"> \
                        <label>Tax Type</label> \
                        <input type="text" class="TaxType" value="N/A" readonly> \
                    </div> \
                    <div class="_row _tax_percentage"> \
                        <label>Tax Percentage</label> \
                        <input type="text" class="TaxPercentage" value="N/A" readonly> \
                    </div> \
                </div>';

        $('._line_title').append(placeholder);
        $('.LineNumber:last').attr('value', lines[i].LineNumber);

        $('.ProductCode:last').attr('value', lines[i].Product.ProductCode);
        $('.ProductCodeLink:last').attr('href', "showProduct.php?ProductCode=" + lines[i].Product.ProductCode);

        $('.ProductDescription:last').attr('value', lines[i].Product.ProductDescription);
        $('.Quantity:last').attr('value', lines[i].Quantity);
        $('.UnitPrice:last').attr('value', lines[i].UnitPrice + " €");
        $('.TaxPercentage:last').attr('value', lines[i].Tax.TaxPercentage + " %");
        $('.TaxType:last').attr('value', lines[i].Tax.TaxType);
        $('.CreditAmount:last').attr('value', lines[i].CreditAmount + " €");
    }
}

function showInvoice(data) {
    $(document).attr('title', 'Show Invoice #' + data.InvoiceNo);

    $('#InvoiceNo').attr('value', "FT SEQ/" + data.InvoiceNo);
    $('#InvoiceDate').attr('value', data.InvoiceDate);

    $('#CustomerId').attr('value', data.Customer.CustomerId);
    $('#CustomerIdLink').attr('href', "showCustomer.php?CustomerId=" + data.Customer.CustomerId);

    $('#CompanyName').attr('value', data.Customer.CompanyName).prop('readonly', true);
    $('#TaxPayable').attr('value', data.DocumentTotals.TaxPayable + " €").prop('readonly', true);
    $('#NetTotal').attr('value', data.DocumentTotals.NetTotal + " €").prop('readonly', true);
    $('#GrossTotal').attr('value', data.DocumentTotals.GrossTotal + " €").prop('readonly', true);

    addLines(data);
}

function showEditableInvoice(data) {
    $(document).attr('title', 'Edit Invoice #' + data.InvoiceNo);

    $('#InvoiceNo').attr('value', "FT SEQ/" + data.InvoiceNo);
    $('#InvoiceDate').attr('value', data.InvoiceDate);

    $('#CustomerId').attr('value', data.Customer.CustomerId);
    $('#CustomerIdLink').attr('href', "showCustomer.php?CustomerId=" + data.Customer.CustomerId);

    $('#CompanyName').attr('value', data.Customer.CompanyName).prop('readonly', true);
    $('#TaxPayable').attr('value', data.DocumentTotals.TaxPayable + " €").prop('readonly', true);
    $('#NetTotal').attr('value', data.DocumentTotals.NetTotal + " €").prop('readonly', true);
    $('#GrossTotal').attr('value', data.DocumentTotals.GrossTotal + " €").prop('readonly', true);

    addLines(data);

    $('input').filter(function (index) {
        return $(this).attr('id') !== 'InvoiceNo' && $(this).attr('class') !== 'LineNumber';
    }).removeAttr('readonly').prop('required', true);
}

function showBlankInvoice(data) {
    $(document).attr('title', 'Create Invoice');

    $('#InvoiceNo').remove();
    $('#InvoiceDate').replaceWith()
}

function loadInvoice() {
    var urlVars = getUrlVars();
    var id = urlVars['InvoiceNo'];
    var action = urlVars['action'];

    if (action !== 'create') {
        if (id == undefined) {
            alert('No Invoice number was detected');
            return;
        }
    }

    var onSuccess;
    switch (action) {
        case 'edit':
        {
            onSuccess = showEditableInvoice;
            break;
        }
        case 'create':
        {
            onSuccess = showBlankInvoice;
            break;
        }
        case undefined:
        {
            onSuccess = showInvoice;
            break;
        }
        default:
            return;
    }
    $.getJSON("api/getFullInvoice.php", {
        InvoiceNo: decodeURI(id)
    }).done(onSuccess);
}
