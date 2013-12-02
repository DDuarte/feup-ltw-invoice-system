function getUrlVars() {
    var vars = {};
    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function showProductData(data) {
    $(document).attr('title', 'Show Product #' + data.ProductCode);

    $('#ProductCode').attr('value', data.ProductCode);
    $('#ProductDescription').attr('value', data.ProductDescription);
    $('#UnitPrice').attr('value', data.UnitPrice + " €");
}

function showEditableProductData(data) {

    $('input').filter(function (index) {
        return $(this).attr('id') !== 'ProductCode';
    }).removeAttr('readonly').attr('method', 'post').prop('required', true);

    $('form').attr('method', 'post').attr('action', 'api/updateProduct.php')
        .append('<input type="button" id="submit" value="Submit">');

    $('div._header').text('Edit product');

    showProductData(data);

    $("#submit").click(function () {

        var jsonObj = {
            ProductCode: $('#ProductCode').val(),
            description: $("#ProductDescription").val(),
            unit_price: $("#UnitPrice").val()
        }

        var requestStr = JSON.stringify(jsonObj);

        $.ajax({
            url: "api/updateProduct.php",
            type: "POST",
            data: {
                product: requestStr
            },
            dataType: "JSON",
            success: function (jsonStr) {
                alert(JSON.stringify(jsonStr));
            }
        });
    });
}

function showBlankProductData(data) {

    $('div._product_code').remove();

    $('input').removeAttr('readonly').prop('required', true);

    $('form')
        .append('<input id="submit" type="button" value="Submit">');

    $('div._header').text('Create new product');

    $("#submit").click(function () {

    var jsonObj = {
        ProductCode: "",
        description: $("#ProductDescription").val(),
        unit_price: $("#UnitPrice").val()
    }

        var requestStr = JSON.stringify(jsonObj);

    $.ajax({
            url: "api/updateProduct.php",
            type: "POST",
            data: {
                product: requestStr
            },
            dataType: "JSON",
            success: function (jsonStr) {
                alert(JSON.stringify(jsonStr));
            }
        });
    });
}

function loadProduct() {

    var urlVars = getUrlVars();
    var action = urlVars['action'];

    if (action !== 'create')
    {
        var id = urlVars['ProductCode'];
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

    $.getJSON("api/getProduct.php", {
        ProductCode: decodeURI(id)
    }).done(onSuccess);
}