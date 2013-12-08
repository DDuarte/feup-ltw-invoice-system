
function loadProduct(jsonObj) {
    $.ajax({
        url: "api/updateProduct.php",
        type: "POST",
        data: jsonObj,
        dataType: "JSON",
        success: function(obj) {
        },
        error: function(obj) {

        }
    });
}

function loadCustomer(jsonObj) {
    $.ajax({
        url: "api/updateCustomer.php",
        type: "POST",
        data: jsonObj,
        dataType: "JSON",
        success: function(obj) {
        },
        error: function(obj) {

        }
    });
}

function loadInvoice(jsonObj) {
    $.ajax({
        url: "api/updateInvoice.php",
        type: "POST",
        data: jsonObj,
        dataType: "JSON",
        success: function(obj) {
        },
        error: function(obj) {

        }
    });
}

function submissionCallback(event) {
    if (event.preventDefault)
        event.preventDefault();
    else
        event.returnValue = false;


    var paramStr = $('#field2').val();

    var addressStr = paramStr.split('?')[0];
    var paramStr = paramStr.split('?')[1];

    alert(paramStr);
    alert(addressStr);

    $.ajax({
        url: addressStr,
        type: "GET",
        data: paramStr,
        dataType: "TEXT",
        success: function (jsonObjArray) {
            if (jsonObjArray.length == 0) {
                alert('No results were found');
                return;
            }

            for (var i = 0; i < jsonObjArray.length; i++) {

                var jsonObj = jsonObjArray[i];

                if (jsonObj.CustomerTaxID != undefined) { // Customer
                    loadCustomer(jsonObj);
                } else if (jsonObj.ProductDescription != undefined) { // product
                    loadProduct(jsonObj);
                } else if (jsonObj.InvoiceNo != undefined) { // invoice
                    loadInvoice(jsonObj);
                }
            }

        },
        error: function (jsonObj) {
            alert('Error');
        }
    });
}

function load() {
    $('form').submit(submissionCallback);
}
