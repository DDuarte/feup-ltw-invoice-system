function loadProducts(baseRequest, requestStr) {
    alert(requestStr);
    $.ajax({
        url: requestStr,
        type: "GET",
        dataType: "JSON",
        success: function (jsonObjArray) {
            for (var i = 0; i < jsonObjArray.length; i++) {

                var jsonObj = jsonObjArray[i];

                jsonObj.ProductCode = '';

                alert(JSON.stringify(jsonObj));

                $.ajax({
                    url: baseRequest + '/api/' + 'updateProduct.php',
                    type: "POST",
                    dataType: "JSON",
                    data: jsonObj,
                    success: function(jsonObj) {
                        alert('Success');
                    },
                    error: function(jsonObj) {
                        alert('Error: ' + JSON.stringify(jsonObj));
                        return;
                    }
                });
            }
        },
        error: function(errorMsg) {
            alert('Error: ' + JSON.stringify(errorMsg));
        }
    });

}

function loadCustomers(baseRequest, requestStr) {
    $.ajax({
        url: requestStr,
        type: "GET",
        dataType: "JSON",
        success: function (jsonObjArray) {
            for (var i = 0; i < jsonObjArray.length; i++) {

                var jsonObj = jsonObjArray[i];

                jsonObj.CustomerID = '';

                $.ajax({
                    url: baseRequest + '/api/' + 'updateCustomer.php',
                    type: "POST",
                    dataType: "JSON",
                    data: jsonObj,
                    success: function(jsonObj) {

                    },
                    error: function(jsonObj) {
                        alert('Error: ' + JSON.stringify(jsonObj));
                        return;
                    }
                });
            }
        },
        error: function(errorMsg) {
            alert('Error: ' + JSON.stringify(errorMsg));
            return;
        }
    });
}

function loadInvoices(baseRequest, requestStr) {
    $.ajax({
        url: requestStr,
        type: "GET",
        dataType: "JSON",
        success: function (jsonObjArray) {
            for (var i = 0; i < jsonObjArray.length; i++) {

                var jsonObj = jsonObjArray[i];

                jsonObj.InvoiceNo = '';

                $.ajax({
                    url: baseRequest + '/api/' + 'updateInvoice.php',
                    type: "POST",
                    dataType: "JSON",
                    data: jsonObj,
                    success: function(jsonObj) {

                    },
                    error: function(jsonObj) {
                        alert('Error: ' + JSON.stringify(jsonObj));
                        return;
                    }
                });
            }
        },
        error: function(errorMsg) {
            alert('Error: ' + JSON.stringify(errorMsg));
            return;
        }
    });
}

function submissionCallback() {
    /* (event.preventDefault)
        event.preventDefault();
    else
        event.returnValue = false;*/

    console.log('Here');

    var paramStr = $('#url_field').val();

    var customerRequestStr = paramStr + '/api/searchCustomersByField.php?doc=customer&op=min&field=CustomerID&value[]=1';
    var invoiceRequestStr = paramStr + '/api/searchInvoicesByField.php?doc=invoice&op=min&field=InvoiceNo&value[]=1';
    var productRequestStr = paramStr + '/api/searchProductsByField.php?doc=product&op=min&field=ProductCode&value[]=1';

    var baseRequest = paramStr;

    alert('All the external database entries will be added');

    $('#url_field').css('color', 'red').val('Importing,please wait...').prop('disabled', true);

    $.ajax({
        url: productRequestStr,
        type: "GET",
        dataType: "JSON",
        success: function (productArray) {

            if (productArray.error != undefined)
                return;

            $.ajax({
                url: customerRequestStr,
                type: "GET",
                dataType: "JSON",
                success: function (costumerArray) {

                    if (costumerArray.error != undefined)
                        return;

                    $.ajax({
                        url: invoiceRequestStr,
                        type: "GET",
                        dataType: "JSON",
                        success: function (invoiceArray) {

                            if (invoiceArray.error != undefined)
                                return;

                            for (var i = 0; i < productArray.length; i++) {

                                var jsonObj = productArray[i];

                                jsonObj.ProductCode = '';

                                $.ajax({
                                    url: baseRequest + '/api/' + 'updateProduct.php',
                                    type: "POST",
                                    dataType: "JSON",
                                    data: {
                                        product: JSON.stringify(jsonObj)
                                    },
                                    async : false,
                                    success: function(retVal) {
                                        console.error(JSON.stringify(retVal));
                                    },
                                    error: function(retVal) {
                                        console.error('Error: ' + JSON.stringify(retVal));
                                        return;
                                    }
                                });
                            }

                            for (var i = 0; i < costumerArray.length; i++) {

                                var jsonObj = costumerArray[i];

                                jsonObj.CustomerID = '';

                                $.ajax({
                                    url: baseRequest + '/api/' + 'updateCustomer.php',
                                    type: "POST",
                                    dataType: "JSON",
                                    data: {
                                        customer: JSON.stringify(jsonObj)
                                    },
                                    async : false,
                                    success: function(retVal) {
                                        console.error(JSON.stringify(retVal));
                                    },
                                    error: function(retVal) {
                                        console.error('Error: ' + JSON.stringify(retVal));
                                        return;
                                    }
                                });
                            }

                            for (var i = 0; i < invoiceArray.length; i++) {

                                var jsonObj = invoiceArray[i];

                                jsonObj.InvoiceNo = '';

                                $.ajax({
                                    url: baseRequest + '/api/' + 'updateInvoice.php',
                                    type: "POST",
                                    dataType: "JSON",
                                    data: {
                                        invoice: JSON.stringify(jsonObj)
                                    },
                                    success: function(retVal) {
                                        console.error(JSON.stringify(retVal));
                                    },
                                    error: function(retVal) {
                                        console.error('Error: ' + JSON.stringify(retVal));
                                        return;
                                    }
                                });
                            }

                            $('#url_field').css('color', 'black').val('Done!').prop('disabled', false);

                        }
                    });
                }
            });
        }
        });

    // loadProducts(paramStr, productRequestStr);
    // loadCustomers(paramStr, customerRequestStr);
    // loadInvoices(paramStr, invoiceRequestStr);
}

function loadExt() {
    $('#import_url_form #input_button').click(function () {
        //$('#url_field').css('color', 'red').val('Importing,please wait...').prop('disabled', true);
        submissionCallback();
    });
}
