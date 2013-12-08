function loadUsers(){

    var arr = {
        'user': {
            'api': 'listUsers.php',
            'deleteUser': 'deleteUser.php',
            'tableHeader': ['Username', 'Role'],
            'key': 'Username',
            'jsonFields': ['Username', 'Role'],
            'fields': ['Username', 'Role']
        }
    }

    $.getJSON('api/' + arr['user'].api)
        .done(function (data) {

            $('.search_results').empty();
            $('.search_results').append('<table id="search_results_table"><tr id="header"></tr></table>');

            for (var i = 0; i < arr['user'].tableHeader.length; ++i) {
                $('#header').append('<th>' + arr['user'].tableHeader[i] + '</th>');
            }

            $('#header').append('<th>Edit User</th>');
            $('#header').append('<th>Delete User</th>');

            for (var i = 0; i < data.length; ++i) {
                $('#search_results_table').append('<tr>');
                for (var j = 0; j < arr['user'].jsonFields.length; ++j) {
                    if (typeof arr['user'].jsonFields[j] === 'object') {
                        for (var key in arr['user'].jsonFields[j]) {
                            $('#search_results_table tbody').append('<td>' + data[i][key][arr['user'].jsonFields[j][key]] + '</td>');
                        }
                    } else {
                        $('#search_results_table tbody').append('<td>' + data[i][arr['user'].jsonFields[j]] + '</td>');
                    }
                }

                $('#search_results_table tbody').append('<td><a target="_blank" href=""><img src="images/glyphicons_150_edit.png" title="edit" width="20" height="20"></a></td>');
                $('#search_results_table tbody').append('<td><a target="_blank" href=""><img src="images/glyphicons_256_delete.png" title="delete" width="20" height="12"></a></td>');

                $('#search_results_table').append('</tr>');
            }
        });
}
