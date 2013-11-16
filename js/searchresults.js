function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        if (vars[key]) {
            if (vars[key] instanceof Array) {
                vars[key].push(value);
            } else {
                vars[key] = [vars[key], value];
            }
        } else {
            vars[key] = value;
        }
    });
    return vars;
}

function load() {
    
    var arr = {
        'invoice': {
            'api': 'searchInvoicesByField.php',
            'detailsHtml': 'showInvoice.html',
            'printHtml': 'printInvoice.html',
            'tableHeader': ['Number', 'Date', 'Client Code', 'Total Amount'],
            'key': 'InvoiceNo',
            'jsonFields': ['InvoiceNo', 'InvoiceDate', 'CustomerID', { 'DocumentTotals': 'GrossTotal' }],
            'fields': ['InvoiceNo', 'InvoiceDate', 'CompanyName', 'GrossTotal']
        },
        'product': {
            'api': 'searchProductsByField.php',
            'detailsHtml': 'showProduct.html',
            'hasPrint': false,
            'tableHeader': ['Product Code'],
            'key': 'ProductCode',
            'jsonFields': ['ProductCode'],
            'fields': ['ProductCode', 'ProducDescription']
        },
        'customer': {
            'api': 'searchCustomersByField.php',
            'detailsHtml': 'showCustomer.html',
            'hasPrint': false,
            'tableHeader': ['Customer Id', 'Company Name'],
            'key': 'CustomerID',
            'jsonFields': ['CustomerID', 'CompanyName'],
            'fields': ['CustomerID', 'CustomerTaxID', 'CompanyName']
        }
    }

    var ops = ['range', 'equal', 'contains', 'min', 'max'];

    var doc = getUrlVars()['doc'];
    if (arr[doc] === undefined)
    {
        alert('Unknown doc: ' + doc);
        return;
    }
    
    var op = getUrlVars()['op'];
    if ($.inArray(op, ops) === -1)
    {
        alert('Unknown op: ' + op);
        return;
    }
    
    var field = getUrlVars()['field'];
    if ($.inArray(field, arr[doc].fields) === -1)
    {
        alert('Unknown field: ' + field);
        return;
    }
    
    var values = getUrlVars()['value[]'];

    for (var i = 0; i < arr[doc].tableHeader.length; ++i)
    {
        $('#header').append('<th>' + arr[doc].tableHeader[i] + '</th>');   
    }

    $('#header').append('<th>Details</th>');
    if (arr[doc].printHtml)
        $('#header').append('<th>Print</th>');
    
    var getRequest = {};
    if (values.length > 1)
    {
        var properValues = [];
        for (var i = 0; i < values.length; ++i)
            properValues.push(decodeURI(values[i]));
        getRequest = { 'op': op, 'field': field, 'value[]': properValues };
    }
    else
    {
        getRequest = { 'op': op, 'field': field, 'value[]': decodeURI(values) };
    }

    $.getJSON('api/' + arr[doc].api, getRequest)
        .done(function(data) {
            
            for (var i = 0; i < data.length; ++i)
            {
                $('#search_results_table').append('<tr>');
                for (var j = 0; j < arr[doc].jsonFields.length; ++j)
                {
                    if (typeof arr[doc].jsonFields[j] === 'object')
                    {
                        for (var key in arr[doc].jsonFields[j])
                        {
                            $('#search_results_table tbody').append('<td>' + data[i][key][arr[doc].jsonFields[j][key]] + '</td>');
                        }
                    }
                    else
                    {
                        $('#search_results_table tbody').append('<td>' + data[i][arr[doc].jsonFields[j]] + '</td>');
                    }
                }
                
                $('#search_results_table tbody').append('<td><a target="_blank" href="' + arr[doc].detailsHtml + '?' + arr[doc].key + '=' + data[i][arr[doc].key] + '"><img src="images/icon_details.gif" title="more info" width="20" height="20"></a></td>');
                
                if (arr[doc].printHtml)
                {
                    $('#search_results_table tbody').append('<td><a target="_blank" href="' + arr[doc].printHtml + '?' + arr[doc].key + '=' + data[i][arr[doc].key] + '"><img src="images/icon_print.gif" title="print" width="20" height="20"></a></td>');
                }
                
                $('#search_results_table').append('</tr>');
            }
        });
}
