function getUrlVars() {
    var vars = {};
    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function loadCountryCodes(target, toBeSelected) {
    $.getJSON("api/getCountries.php", {
    }).done(function (data) {
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement("option");
                option.value = data[i].code;
                option.text = data[i].name;
                $(target).append(option);

                if (option.text == toBeSelected)
                    option.selected = true;
            }

        });
}

function showCustomerData(data) {
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
}

function editSubmissionCallback(event) {
    // prevent the default form behaviour
    if (event.preventDefault)
        event.preventDefault();
    else
        event.returnValue = false;

    var jsonObj = {
        CustomerID: $('#CustomerId').val(),
        tax_id: $("#CustomerTaxId").val(),
        detail: $("#AddressDetail").val(),
        company_name: $('#CompanyName').val(),
        email: $('#Email').val(),
        city_id: $('#City').val(),
        postal_code: $('#PostalCode').val(),
        country_code: $('#Country').val()
    };

    var requestStr = JSON.stringify(jsonObj);

    $.ajax({
        url: "api/updateCustomer.php",
        type: "POST",
        data: {
            customer: requestStr
        },
        dataType: "JSON",
        success: function (jsonObj) {
            location.href = "showCustomer.php?CustomerId=" + jsonObj
        },
        error: function (jsonObj) {
            alert(JSON.stringify(jsonObj));
        }
});
}

function createSubmissionCallback(event) {

    if (event.preventDefault)
        event.preventDefault();
    else
        event.returnValue = false;

    var jsonObj = {
        CustomerID: '',
        tax_id: $("#CustomerTaxId").val(),
        detail: $("#AddressDetail").val(),
        company_name: $('#CompanyName').val(),
        email: $('#Email').val(),
        city_id: $('#City').val(),
        postal_code: $('#PostalCode').val(),
        country_code: $('#Country').val()
    };

    var requestStr = JSON.stringify(jsonObj);

    $.ajax({
        url: "api/updateCustomer.php",
        type: "POST",
        data: {
            customer: requestStr
        },
        dataType: "JSON",
        success: function (jsonObj) {
            location.href = "showCustomer.php?CustomerId=" + jsonObj
        },
        error: function (jsonObj) {
            alert(JSON.stringify(jsonObj));
        }
});

}

function showEditableCustomerData(data) {
    $(document).attr('title', 'Edit Customer #' + data.CustomerId);

    $('input').filter(function (index) {
        return $(this).attr('id') !== 'CustomerId';
    }).removeAttr('readonly').prop('required', true);

    $('#CustomerId').attr('value', data.CustomerId);
    $('#CustomerTaxId').attr('value', data.CustomerTaxID);
    $('#CompanyName').attr('value', data.CompanyName);
    $('#AddressDetail').attr('value', data.BillingAddress.AddressDetail);
    $('#City').attr('value', data.BillingAddress.City);
    $('#PostalCode').attr('value', data.BillingAddress.PostalCode);
    $('#Email').attr('value', data.Email);
    $('#SelfBillingIndicator').prop('checked', data.SelfBillingIndicator);

    $('input#Country').replaceWith('<select id="Country"></select>').prop('required', true);
    loadCountryCodes('#Country', data.BillingAddress.Country);

    $('form').append('<input id="submit" type="submit" value="Submit">').submit(editSubmissionCallback);
}

function showBlankCustomerData(data) {
    $(document).attr('title', 'Create Customer');

    $('input').filter(function (index) {
        return $(this).attr('id') !== 'CustomerId';
    }).removeAttr('readonly').prop('required', true).val('');

    $('#CustomerId').remove();

    $('input#Country').replaceWith('<select id="Country"></select>').prop('required', true);
    loadCountryCodes('#Country', 'Portugal');

    $('form').append('<input id="submit" type="submit" value="Submit">').submit(createSubmissionCallback);
}

function loadCustomer() {

    var urlVars = getUrlVars();
    var action = urlVars['action'];

    if (action !== 'create') {
        var id = getUrlVars()['CustomerId'];
        if (id == undefined) {
            alert('No customer id was detected');
            return;
        }
    }

    var onSuccess;
    switch (action) {
        case 'edit':
        {
            onSuccess = showEditableCustomerData;
            break;
        }
        case 'create':
        {
            onSuccess = showBlankCustomerData;
            break;
        }
        case undefined:
        {
            onSuccess = showCustomerData;
            break;
        }
        default:
            return;
    }

    $.getJSON("api/getCustomer.php", {
        CustomerId: decodeURI(id)
    }).done(onSuccess);
}
