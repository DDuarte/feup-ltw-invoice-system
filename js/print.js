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
            $('#InvoiceNo').append(data.InvoiceNo);

            $('#InvoiceDate').attr("datetime", data.InvoiceDate);
            $('#InvoiceDate').append(data.InvoiceDate);

            $('#CustomerId').append(data.Customer.CustomerId);
            $('#CustomerTaxID').append(data.Customer.CustomerTaxID);
            $('#CompanyName').append(data.Customer.CompanyName);
            $('#AddressDetail').append(data.Customer.BillingAddress.AddressDetail);
            $('#City').append(data.Customer.BillingAddress.City);
            $('#PostalCode').append(data.Customer.BillingAddress.PostalCode);
            $('#Country').append(data.Customer.BillingAddress.Country);
            $('#Email').append(data.Customer.Email);
        });
}