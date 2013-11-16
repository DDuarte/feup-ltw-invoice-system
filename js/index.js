function load() {
    $('.doc_fields').hide();
    $('#op_search_list').hide();
    $('._field_search').hide();
    $('#between_span').hide();
    $('#search_button').hide();

    $('#document_search_select').change(function() {
        $('.doc_fields').hide();
        $('#op_search_list').hide();
        $('._field_search').hide();
        $('#between_span').hide();
        $('#search_button').hide();

        if ($(this).val() != 'none') {
            $('#' + $(this).val()).show();
            $('#op_search_list').show();
            $('#field1_search_list').show();
            
            $('#search_button').show();
        }
    });
    
    $('#op_search_select').change(function() {
        $('#field2_search_list').hide();
        $('#between_span').hide();
        if ($('#op_search_select').val() === 'range')
            {
                $('#field2_search_list').show();
                $('#between_span').show();
            }
    });
}

function search() {
    var op = $('#op_search_select').val();
    
    var field = '';
    var doc = '';
    switch ($('#document_search_select').val())
    {
        case 'customer_field_search_list':
            field = $('#customer_field_search_select').val();
            doc = 'customer';
        case 'product_field_search_list':
            field = $('#product_field_search_select').val();
            doc = 'product';
        case 'invoice_field_search_list':
            field = $('#invoice_field_search_select').val();
            doc = 'invoice';
    }
    
    var value1 = $('#field1').val();
    var value2 = $('#field2').val();
    
    var getRequest = { };
    if (value2 === undefined || value2.length === 0)
        getRequest = { 'doc': doc, 'op': op, 'field': field, 'value[]': value1 };
    else
        getRequest = { 'doc': doc, 'op': op, 'field': field, 'value[]': [value1, value2] };
    
    /* $.ajax({
        url: 'searchResults.html',
        type: 'GET',
        async: false,
        data: $.param(getRequest),
        complete: function(jqXHR, textStatus) {
            alert(jqXHR);
        }
    }); */
    
    
    $('#search_result').load('searchResults.html', decodeURI($.param(getRequest)));
}
