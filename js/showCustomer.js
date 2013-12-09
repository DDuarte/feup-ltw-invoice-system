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

                if (option.value == toBeSelected)
                    option.selected = true;
            }

        });
}

function showCustomerData(data) {
    $(document).attr('title', 'Show Customer #' + data.CustomerID);

    $('#CustomerID').attr('value', data.CustomerID);
    $('#CustomerTaxId').attr('value', data.CustomerTaxID);
    $('#CompanyName').attr('value', data.CompanyName);
    $('#AddressDetail').attr('value', data.BillingAddress.AddressDetail);
    $('#City').attr('value', data.BillingAddress.City);
    $('#PostalCode').attr('value', data.BillingAddress.PostalCode);
    $('#Country').attr('value', data.BillingAddress.Country);
    $('#Email').attr('value', data.Email);

    $.ajax({
        url: "api/user_is_editor.php",
        success: function (is_editor) {
            if (JSON.parse(is_editor)) {
                $('._header').append('<a id="editLink" href="showCustomer.php?CustomerID=' + data.CustomerID + '&action=edit"><img src="images/glyphicons_150_edit.png" title="edit" width="20" height="20"></a>');
            }
        }});
}

function editSubmissionCallback(event) {
    // prevent the default form behaviour
    if (event.preventDefault)
        event.preventDefault();
    else
        event.returnValue = false;

    var jsonObj = {
        CustomerID: $('#CustomerID').val(),
        CustomerTaxID: $("#CustomerTaxId").val(),
        CompanyName: $('#CompanyName').val(),
        Email: $('#Email').val(),
        BillingAddress : {
            AddressDetail: $("#AddressDetail").val(),
            City: $('#City').val(),
            PostalCode: $('#PostalCode').val(),
            Country: $('#Country').val()
        }
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
            location.href = "showCustomer.php?CustomerID=" + jsonObj
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
        CustomerTaxID: $("#CustomerTaxId").val(),
        CompanyName: $('#CompanyName').val(),
        Email: $('#Email').val(),
        BillingAddress : {
        AddressDetail: $("#AddressDetail").val(),
        City: $('#City').val(),
        PostalCode: $('#PostalCode').val(),
        Country: $('#Country').val()
        }
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
            location.href = "showCustomer.php?CustomerID=" + jsonObj
        },
        error: function (jsonObj) {
            alert(JSON.stringify(jsonObj));
        }
});

}

function showEditableCustomerData(data) {
    $(document).attr('title', 'Edit Customer #' + data.CustomerID);
    $('._header').text("Edit Customer");

    $('input').filter(function (index) {
        return $(this).attr('id') !== 'CustomerID';
    }).removeAttr('readonly').prop('required', true);

    $('#CustomerID').attr('value', data.CustomerID);
    $('#CustomerTaxId').attr('value', data.CustomerTaxID);
    $('#CompanyName').attr('value', data.CompanyName);
    $('#AddressDetail').attr('value', data.BillingAddress.AddressDetail);
    $('#City').attr('value', data.BillingAddress.City);
    $('#PostalCode').attr('value', data.BillingAddress.PostalCode);
    $('#Email').attr('value', data.Email);

    $('input#Country').replaceWith('<select id="Country"></select>').prop('required', true);
    loadCountryCodes('#Country', data.BillingAddress.Country);

    $('form').append('<input id="submit" type="submit" value="Submit">').submit(editSubmissionCallback);
}

function showBlankCustomerData(data) {
    $(document).attr('title', 'Create Customer');
    $('._header').text("Create Customer");

    $('input').filter(function (index) {
        return $(this).attr('id') !== 'CustomerID';
    }).removeAttr('readonly').prop('required', true).val('');

    $('#CustomerID').remove();

    $('input#Country').replaceWith('<select id="Country"></select>').prop('required', true);
    loadCountryCodes('#Country', 'Portugal');

    $('form').append('<input id="submit" type="submit" value="Submit">').submit(createSubmissionCallback);
}

function loadCustomer() {

    var urlVars = getUrlVars();
    var action = urlVars['action'];

    if (action !== 'create') {
        var id = urlVars['CustomerID'];
        if (id == undefined) {
            alert('No customer id was detected');
            return;
        }
    }

    $.ajax({
        url: "api/user_is_editor.php",
        success: function (is_editor) {
            is_editor = JSON.parse(is_editor);
            var onSuccess = null;
            if(is_editor) {
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
                    case 'show':
                    case undefined:
                    {
                        onSuccess = showCustomerData;
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
                    onSuccess = showCustomerData;
            }

            if (onSuccess === null)
                return;

            $.getJSON("api/getCustomer.php", {
                CustomerID: decodeURI(id)
            }).done(onSuccess);

        }
    });
}
