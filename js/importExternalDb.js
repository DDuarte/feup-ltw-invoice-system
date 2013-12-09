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
    alert('Im here');
    /* (event.preventDefault)
        event.preventDefault();
    else
        event.returnValue = false;*/

    var paramStr = $('#url_field').val();

    var customerRequestStr = paramStr + '/api/searchCustomersByField.php?doc=customer&op=min&field=CustomerID&value[]=1';
    var invoiceRequestStr = paramStr + '/api/searchInvoicesByField.php?doc=invoice&op=min&field=InvoiceNo&value[]=1';
    var productRequestStr = paramStr + '/api/searchProductsByField.php?doc=product&op=min&field=ProductCode&value[]=1';

    alert(paramStr);

    loadProducts(paramStr, productRequestStr);
    loadCustomers(paramStr, customerRequestStr);
    loadInvoices(paramStr, invoiceRequestStr);
}

function loadExt() {
    alert('loadExt');
    $('#import_url_form #input_button').click(function () {
        submissionCallback();
    });
}
