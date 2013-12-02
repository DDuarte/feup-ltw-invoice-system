function getUrlVars() {
    var vars = {};
    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function loadCustomer() {

    var urlVars = getUrlVars();
    var action = urlVars['action'];

    if (action !== 'create')
    {
        var id = getUrlVars()['CustomerId'];
        if (id == undefined)
            return;
    }

    var onSuccess;
    switch(action)
    {
        case 'edit':
        {
            onSuccess = showEditableProductData;
            break;
        }
        case 'create':
        {
            onSuccess = showBlankProductData;
            break;
        }
        case undefined:
        {
            onSuccess = showProductData;
            break;
        }
        default:
            return;
    }

    $.getJSON("api/getCustomer.php", {
        CustomerId: decodeURI(id)
    })
        .done(function (data) {
            $(document).attr('title', 'Show Customer #' + data.CustomerId);

            $('#CustomerId').attr('value', data.CustomerId);
            $('#CustomerTaxId').attr('value', data.CustomerTaxID);
            $('#CompanyName').attr('value', data.CompanyName);
            $('#AddressDetail').attr('value', data.BillingAddress.AddressDetail);
            $('#City').attr('value', data.BillingAddress.City);
            $('#PostalCode').attr('value', data.BillingAddress.PostalCode);
            $('#Country').attr('value', data.BillingAddress.Country);
            $('#Email').attr('value', data.Email);
            $('#SelfBillingIndicator').prop('checked', data.SelfBillingIndicator);
        });
}
