function getUrlVars() {
    var vars = {};
    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function loadProducts(target, toBeSelected) {
    $.getJSON("api/getAllProducts.php", {
    }).done(function (data) {
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement("option");
                option.value = data[i].id;
                option.text = data[i].description;
                target.append(option);

                if (option.value == toBeSelected)
                    option.selected = true;
            }

            target.change(function() {
                $(this).parent().parent().find('.ProductCode').attr('value', $(this).find(':selected').val());
            });

        });
}

function loadCustomers(target, toBeSelected) {
    $.getJSON("api/getAllCustomers.php", {
    }).done(function (data) {
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement("option");
                option.value = data[i].id;
                option.text = data[i].company_name;
                target.append(option);

                if (option.value == toBeSelected)
                    option.selected = true;
            }

            $('#CustomerIDLink').attr('href', "showCustomer.php?CustomerID=" + target.find(':selected').val());
            $('#CustomerID').attr('value', target.find(':selected').val());
            target.change(function() {
                $('#CustomerIDLink').attr('href', "showCustomer.php?CustomerID=" + $(this).find(':selected').val());
                $('#CustomerID').attr('value', $(this).find(':selected').val());
            });

        });
}

function loadTaxes(targetListBox, targetInput, toBeSelected)
{
    $.getJSON("api/getAllTax.php", {
    }).done(function (data) {
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement("option");
                option.value = data[i].id;
                option.text = data[i].type;
                $(option).attr('percentage', data[i].percentage);
                targetListBox.append(option);
                targetInput.value = data.percentage;
                //taxes[data[i].id] = data[i].percentage;
                if (option.value == toBeSelected)
                    option.selected = true;
            }

            targetListBox.change(function() {
                var option = $(this).find(":selected");
                var percentageInput = $(this).parent().parent().find('._tax_percentage .TaxPercentage');
                percentageInput.attr('value', $(option).attr('percentage'));
            });
        });
}

function removeLine(button)
{
    $(button).parent().remove();
    $('._line_title ._invoice_line ._line_number .LineNumber').each(function(index, element) { element.value = index + 1; });
}

function addBlankLine() {
    var placeholder = '<div class="_invoice_line _sub_row"> \
                    <div class="_row _line_number"> \
                        <label>Line #</label> \
                        <input type="text" class="LineNumber" value="" required> \
                    </div> \
                    <div class="_row _product_code" style="display: none;"> \
                        <label>Product Code</label> \
                        <input type="number" class="ProductCode" value=""> \
                        <a class="ProductCodeLink" target="_blank"><img title="Product Info" src="../images/glyphicons_217_circle_arrow_right.png"></a> \
                    </div> \
                    <div class="_row _product_description _five_hundred"> \
                        <label>Product Description</label> \
                        <input type="text" class="ProductDescription" value="" required> \
                    </div> \
                    <div class="_row _quantity"> \
                        <label>Quantity</label> \
                        <input type="number" min="0" class="Quantity" value="" required> \
                    </div> \
                    <div class="_row _unit_price"> \
                        <label>Unit Price</label> \
                        <input type="number" min="0" step="any" class="UnitPrice" value="" required> \
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
                        <input type="number" min="0" class="TaxPercentage" value="" required readonly> \
                    </div> \
                </div>';

    $('._line_title').append(placeholder);


    if ($('.LineNumber').length > 1) {
        var lines = $('.LineNumber');
        lines.last().attr('value', parseInt(lines[lines.length - 2].value) + 1).prop('readonly', true);
    }
    else {
        $('.LineNumber:last').attr('value', 1).prop('readonly', true);
    }

    var offset = $('.LineNumber:last').offset();
    $("html,body").animate({
        scrollTop: offset.top,
        scrollLeft: offset.left
    });

    var listBoxElement = $('._invoice_line:last').children('._product_description').children('.ProductDescription').
        replaceWith('<select class="ProductDescription _my_select" required></select>');

    listBoxElement = $('._invoice_line:last').children('._product_description').children('.ProductDescription');

    loadProducts(listBoxElement, '');
    listBoxElement.focus();

    var button =  '<input class="removeLineButtons" type="button" value="Remove line" onclick="removeLine(this)"> </input>';
    $('._invoice_line:last').append(button);

    $('.TaxType:last').replaceWith('<select class="TaxType"> </select>');
    loadTaxes($('.TaxType:last'), $('.TaxPercentage:last'), '');

    $('.Quantity:last').change(function() {
        var unitPrice = parseFloat($(this).parent().parent().find('.UnitPrice').val());
        var quantity = parseInt($(this).val());

        if (isNaN(unitPrice) || isNaN(quantity))
            return;


        $(this).parent().parent().find('.CreditAmount').attr('value', unitPrice * quantity);
    });

    $('.UnitPrice:last').change(function() {
        var unitPrice = parseFloat($(this).val());
        var quantity = parseInt($(this).parent().parent().find('.Quantity').val());
        $(this).attr('value', unitPrice);

        if (isNaN(unitPrice) || isNaN(quantity))
            return;

        $(this).parent().parent().find('.CreditAmount').attr('value', unitPrice * quantity);
    })

    $('.TaxPercentage:last').attr('value', parseInt($('.TaxType :selected').attr('percentage')));
}

function addLines(data, edit) {
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
                        <a class="ProductCodeLink" target="_blank"><img title="Product Info" src="../images/glyphicons_217_circle_arrow_right.png"></a> \
                    </div> \
                    <div class="_row _product_description _five_hundred"> \
                        <label>Product Description</label> \
                        <input type="text" class="ProductDescription" value="N/A" readonly> \
                    </div> \
                    <div class="_row _quantity"> \
                        <label>Quantity</label> \
                        <input type="number" min="0" class="Quantity" value="N/A" readonly> \
                    </div> \
                    <div class="_row _unit_price"> \
                        <label>Unit Price</label> \
                        <input type="number" step="any" min="0" class="UnitPrice" value="N/A" readonly> \
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
                        <input type="number" min="0" class="TaxPercentage" value="N/A" readonly> \
                    </div> \
                </div>';

        $('._line_title').append(placeholder);
        $('.LineNumber:last').attr('value', lines[i].LineNumber);

        $('.ProductCode:last').attr('value', lines[i].Product.ProductCode);

        if(!edit)
            $('.ProductCodeLink:last').attr('href', "showProduct.php?ProductCode=" + lines[i].Product.ProductCode);
        else
            $('.ProductCodeLink:last').remove();

        $('.ProductDescription:last').attr('value', lines[i].Product.ProductDescription);
        $('.Quantity:last').attr('value', lines[i].Quantity);
        $('.UnitPrice:last').attr('value', lines[i].UnitPrice);
        $('.TaxPercentage:last').attr('value', lines[i].Tax.TaxPercentage);
        $('.TaxType:last').attr('value', lines[i].Tax.TaxType);
        $('.CreditAmount:last').attr('value', lines[i].CreditAmount);

        if (!edit)
            continue;

        var listBoxElement = $('._invoice_line:last').children('._product_description').children('.ProductDescription').
            replaceWith('<select class="ProductDescription _my_select" required></select>');

        listBoxElement = $('._invoice_line:last').children('._product_description').children('.ProductDescription');

        loadProducts(listBoxElement, lines[i].Product.ProductCode);

        var button =  '<input class="removeLineButtons" type="button" value="Remove line" onclick="removeLine(this)"> </input>';
        $('._invoice_line:last').append(button);

        $('.TaxType:last').replaceWith('<select class="TaxType"> </select>');

        loadTaxes($('.TaxType:last'), $('.TaxPercentage:last'), '');

        $('.Quantity:last').change(function() {
            var unitPrice = parseFloat($(this).parent().parent().find('.UnitPrice').val());
            var quantity = parseInt($(this).val());
            $(this).parent().parent().find('.CreditAmount').attr('value', unitPrice * quantity);
        })

        $('.UnitPrice:last').change(function() {
            var unitPrice = parseFloat($(this).val());
            var quantity = parseInt($(this).parent().parent().find('.Quantity').val());
            $(this).attr('value', unitPrice);
            $(this).parent().parent().find('.CreditAmount').attr('value', unitPrice * quantity);
        })
    }
}

function showInvoice(data) {
    $(document).attr('title', 'Show Invoice #' + data.InvoiceNo);

    $('#InvoiceNo').attr('value', "FT SEQ/" + data.InvoiceNo);
    $('#InvoiceDate').attr('value', data.InvoiceDate);

    $('#CustomerID').attr('value', data.Customer.CustomerID);
    $('#CustomerIDLink').attr('href', "showCustomer.php?CustomerID=" + data.Customer.CustomerID);

    $('#CompanyName').attr('value', data.Customer.CompanyName).prop('readonly', true);
    $('#TaxPayable').attr('value', data.DocumentTotals.TaxPayable + " €").prop('readonly', true);
    $('#NetTotal').attr('value', data.DocumentTotals.NetTotal + " €").prop('readonly', true);
    $('#GrossTotal').attr('value', data.DocumentTotals.GrossTotal + " €").prop('readonly', true);
    $('#OwnerDiv').prop('hidden', false);
    $('#OwnerName').prop('hidden', false).attr('value', data.Source.Username);

    addLines(data, false);
    $.ajax({
        url: "api/user_is_editor.php",
        success: function (is_editor) {
            if (JSON.parse(is_editor)) {
                $('._header').append('<a id="editLink" href="showInvoice.php?InvoiceNo=' + data.InvoiceNo + '&action=edit"><img src="images/glyphicons_150_edit.png" title="edit" width="20" height="20"></a>');
            }
        }});
}

function showEditableInvoice(data) {
    $(document).children('title').text('Edit invoice');

    $('._header').text("Edit Invoice");

    $('#InvoiceNo').attr('value', "FT SEQ/" + data.InvoiceNo);
    $('#InvoiceDate').attr('value', data.InvoiceDate);

    $('#CustomerIDLink').attr('href', "showCustomer.php?CustomerID=" + data.Customer.CustomerID);

    addLines(data, true);

    $('input').filter(function (index) {
        return $(this).attr('id') !== 'InvoiceNo' && $(this).attr('class') !== 'LineNumber' &&
            $(this).attr('class') !== 'ProductCode' && $(this).attr('class') !== 'CreditAmount' &&
            $(this).attr('class') !== 'TaxPercentage';
    }).removeAttr('readonly').prop('required', true);

    $('#CustomerID').attr('value', data.Customer.CustomerID).prop('readonly', true);
    $('#CompanyName').attr('value', data.Customer.CompanyName).prop('readonly', true);
    $('.totals').remove();

    $('<input id="addLineButton" type="button" onclick="addBlankLine()" value="Add line"> </input>').
        insertAfter($("#CompanyName"));

    $('form').append('<input type="submit" id="submit">').submit(submissionCallback);
}

function showBlankInvoice(data) {
    $(document).attr('title', 'Create Invoice');
    $('._header').text("Create Invoice");
    $('._invoice_number').remove();

    $('input').removeAttr('readonly').prop('required', true);

    $('.totals').remove();
    $('#CompanyName').replaceWith('<select id="CompanyName"> </select>');
    $('#CustomerID').prop('readonly', true);
    loadCustomers($('#CompanyName'), '');

    $('<input id="addLineButton" type="button" onclick="addBlankLine()" value="Add line"> </input>').
        insertAfter($("#CompanyName"));

    $('form').append('<input type="submit" id="submit">').submit(createSubmissionCallback);

    $('#InvoiceDate').val(new Date().toJSON().slice(0,10));

    var maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 7);
    maxDate = maxDate.toJSON().slice(0,10);

    var minDate = new Date();
    minDate.setDate(minDate.getDate() - 7);
    minDate = minDate.toJSON().slice(0,10);

    $('#InvoiceDate').attr('max', maxDate);
    $('#InvoiceDate').attr('min', minDate);
}

function submissionCallback(event) {

    // prevent form default behaviour
    if (event.preventDefault)
        event.preventDefault();
    else
        event.returnValue = false;

    var jsonObject = new Object();
    jsonObject.InvoiceNo = $('#InvoiceNo').val().replace( /^\D+/g, '');
    jsonObject.CustomerID = $('#CustomerID').val();
    jsonObject.InvoiceDate = $('#InvoiceDate').val();
    jsonObject.DocumentStatus  = {
        SourceID: 1
    };
    jsonObject.notUpdatePrice = false;

    jsonObject.Line = [];
    $('._line_title').children('._invoice_line').each(function() {
        var line =  {
            LineNumber : $(this).find('.LineNumber').val(),
            ProductCode : $(this).find('.ProductDescription').find(':selected').val(),
            Quantity : $(this).find('.Quantity').val(),
            UnitPrice : $(this).find('.UnitPrice').val(),
            Tax: {
                TaxType : $(this).find('.TaxType').find(':selected').text(),
                TaxCountryRegion : "",
                TaxCode : "",
                TaxPercentage : $(this).find('.TaxType').find(':selected').attr('percentage')
            }
//            TaxID : $(this).find('.TaxType').find(':selected').val()
        };

        jsonObject.Line.push(line);
    });

    var requestStr = JSON.stringify(jsonObject);

   $.ajax({
        url: "api/updateInvoice.php",
        type: "POST",
        data: {
            invoice: requestStr
        },
        dataType: "JSON",
        success: function (jsonObj) {
            var invoiceId = parseInt(jsonObj.InvoiceNo);
            window.location.replace('showInvoice.php?InvoiceNo=' + invoiceId +'&action=show');
        },
        error: function(jsonObj) {

        }
    });
}

function createSubmissionCallback(event) {
    // prevent form default behaviour
    if (event.preventDefault)
        event.preventDefault();
    else
        event.returnValue = false;

    var jsonObject = new Object();
    jsonObject.InvoiceNo = '';
    jsonObject.CustomerID = $('#CustomerID').val();
    jsonObject.InvoiceDate = $('#InvoiceDate').val();

    $.ajax({
        url: "api/get_logged_id.php",
        success: function(logged_id) {
            logged_id = JSON.parse(logged_id);
            jsonObject.DocumentStatus  = {
                SourceID: logged_id
            };

            jsonObject.notUpdatePrice = false;

            jsonObject.Line = [];
            $('._line_title').children('._invoice_line').each(function() {
                var line =  {
                    LineNumber : $(this).find('.LineNumber').val(),
                    ProductCode : $(this).find('.ProductDescription').find(':selected').val(),
                    Quantity : $(this).find('.Quantity').val(),
                    UnitPrice : $(this).find('.UnitPrice').val(),
                    Tax: {
                        TaxType : $(this).find('.TaxType').find(':selected').text(),
                        TaxCountryRegion : "",
                        TaxCode : "",
                        TaxPercentage : $(this).find('.TaxType').find(':selected').attr('percentage')
                    }
                };

                jsonObject.Line.push(line);
            });

            var requestStr = JSON.stringify(jsonObject);

            $.ajax({
                url: "api/updateInvoice.php",
                type: "POST",
                data: {
                    invoice: requestStr
                },
                dataType: "JSON",
                success: function (jsonObj) {
                    var invoiceId = parseInt(jsonObj.InvoiceNo);
                    window.location.replace('showInvoice.php?InvoiceNo=' + invoiceId +'&action=show');
                },
                error: function(jsonObj) {
                    alert('Error:' + JSON.stringify(jsonObj));
                }
            });
        },
        error: function(jsonObj) {
            alert('Error: ' + JSON.stringify(jsonObj));
            return;
        }
    });
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

    $.ajax({
        url: "api/user_is_editor.php",
        success: function (is_editor) {

            is_editor = JSON.parse(is_editor);

            var onSuccess = null;
            if (is_editor)
            {
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
                    case 'show':
                    case undefined:
                    {
                        onSuccess = showInvoice;
                        break;
                    }
                    default:
                        return;
                }
            }
            else {
                if (action != 'show' && action !== undefined) {
                    alert('Error: permission denied');
                    window.location.replace('index.php');
                }
                else
                    onSuccess = showInvoice;
            }
            if (onSuccess == null)
                return;

            $.getJSON("api/getFullInvoice.php", {
                InvoiceNo: decodeURI(id)
            }).done(onSuccess);
        }

    });
}
