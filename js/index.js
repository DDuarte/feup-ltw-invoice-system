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
