function getUrlVars() {
    var vars = {};
    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function loadProduct() {
    var id = getUrlVars()['ProductCode'];
    if (id == undefined)
        return;

    $.getJSON("api/getProduct.php", {
        ProductCode: decodeURI(id)
    })
        .done(function (data) {
            $(document).attr('title', 'Show Product #' + data.ProductCode);

            $('#ProductCode').attr('value', data.ProductCode);
            $('#ProductDescription').attr('value', data.ProductDescription);
            $('#UnitPrice').attr('value', data.UnitPrice + " €");
        });
}
