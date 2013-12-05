function getUrlVars() {
    var vars = {};
    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}
function showUserData(data) {

    alert("showUserData"); return;

    $(document).attr('title', 'Show Product #' + data.ProductCode);

    $('#ProductCode').attr('value', data.ProductCode);
    $('#ProductDescription').attr('value', data.ProductDescription);
    $('#UnitPrice').attr('value', data.UnitPrice);
}

function showEditableUserData(data) {

    alert("showEditableUserData"); return;

    $('input').filter(function (index) {
        return $(this).attr('id') !== 'ProductCode';
    }).removeAttr('readonly').attr('method', 'post').prop('required', true);

    $('div._header').text('Edit product');

    var submissionCallback = function (event) {
        if (event.preventDefault)
            event.preventDefault();
        else
            event.returnValue = false;

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
    };

    showProductData(data);

    $('form').attr('method', 'post').attr('action', 'api/updateProduct.php')
        .append('<input type="submit" id="submit" value="Submit">').submit(submissionCallback);

    $('#ProductDescription').focus();
}

function showBlankUserData(data) {

    alert("showBlankUserData"); return;

    $('div._product_code').remove();

    $('input').removeAttr('readonly').prop('required', true).val('');

    $('#ProductDescription').focus();

    var submissionCallback = function (event) {
        // prevent the default form behaviour
        if (event.preventDefault)
            event.preventDefault();
        else
            event.returnValue = false;

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
            success: function (jsonObj) {
                $.ajax({
                    url: "showProduct.php",
                    type: "GET",
                    data: {
                        ProductId: jsonObj
                    },
                    dataType: "text",
                    success: function (jsonObj) {
                    }
                });
            }
        });
    };

    $('form')
        .append('<input id="submit" type="submit" value="Submit">').submit(submissionCallback);

    $('div._header').text('Create new product');
}

function loadUser() {
    var urlVars = getUrlVars();
    var action = urlVars['action'];

    if (action !== 'create') {
        var id = urlVars['UserId'];
        if (id == undefined) {
            alert('No user id was detected');
            return;
        }
    }

    if (action == undefined)
        action = 'show';
    else if (action != 'show' && action != 'create' && action != 'edit')
        return;

    $.ajax({
        url: "api/user_is_admin.php",
        success: function (is_admin) {
            is_admin = JSON.parse(is_admin);

            if (!is_admin) {
                $.ajax({
                    url: "api/get_logged_id.php",
                    success: function(logged_id) {
                        logged_id = JSON.parse(logged_id);

                        var onSuccess = null;

                        if (id === logged_id) {
                            switch(action) {
                                case 'edit': {
                                    onSuccess = showEditableUserData;
                                    break;
                                }
                                case 'show' : {
                                    onSuccess = showUserData;
                                }
                            }
                        }

                        if (onSuccess == null)
                            return;

                        $.getJSON("api/getUser.php", {
                            UserId: decodeURI(id)
                        }).done(onSuccess);
                    }
                });
            } else {
                var onSuccess = null;
                switch(action) {
                    case 'edit': {
                        onSuccess = is_admin ? showEditableUserData : null;
                        break;
                    }
                    case 'create': {
                        onSuccess = is_admin ? showBlankUserData : null;
                        break;
                    }
                    case 'show' : {
                        onSuccess = showUserData;
                        break;
                    }
                }

                if (onSuccess == null)
                    return;

                $.getJSON("api/getUser.php", {
                    UserId: decodeURI(id)
                }).done(onSuccess);
            }
        }
    })
}