function getUrlVars() {
    var vars = {};
    window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function showUserData(data) {
    $('._header').text('Show User');
    $('#Username').attr('value', data.Username);
    loadRoles($('#role'), data.Role);
    $('#role').attr('disabled', true);
}

function loadRoles(target, toBeSelected) {
    $.getJSON("api/getAllRoles.php", {
    }).done(function (data) {
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement("option");
                option.value = data[i].id;
                option.text = data[i].name;
                target.append(option);

                if (option.text == toBeSelected)
                    option.selected = true;
            }
        });
}

function showAdministratorEditableUserData(data) {
    $('._header').text('Edit User : Administrator');
    $('#Username').attr('value', data.Username);
    loadRoles($('#role'), data.Role);

    var passwordField = '<div id="Password" class="_row" hidden><label>Password:</label><input type="password" id="PasswordField"></input></div>'
    $('form').append(passwordField);

    var checkBox = '<input type="checkbox" id="ChangePassword"><span>Change password<span></input>'
    $('form').append(checkBox);
    $('#ChangePassword').click(function() {
        if ($(this).is(':checked')) {
            $('#Password').show().attr('disabled', false);
            $('#PasswordField').prop('required', true);
        }
        else {
            $('#Password').hide().attr('disabled', true);
            $('#PasswordField').prop('required', false);
        }
    });

    var submitButton = '<input type="submit" id="submit" value="Submit"></input>';
    $('form').append(submitButton).submit(function(event) {
        if (event.preventDefault)
            event.preventDefault();
        else
            event.returnValue = false;

        var jsonRequest = {
            id : data.Id,
            username : $('#Username').val(),
            password : '',
            role_id : $('#role').find(':selected').val()
        };

        if ($('#ChangePassword').prop('checked'))
            jsonRequest.password = $('#PasswordField').val();

        var requestStr = JSON.stringify(jsonRequest);
        //alert(requestStr);

        $.ajax({
            url: "api/updateUser.php",
            type: "POST",
            data: {
                user: requestStr
            },
            dataType: "JSON",
            success: function (jsonObj) {
                //alert(JSON.stringify(jsonObj));
                window.location.replace('showUser.php?UserId=' + jsonObj + '&action=show');
            },
            error: function(jsonObj) {
                alert('Error: ' + JSON.stringify(jsonObj));
            }
        });

    });
}

function showEditableUserData(data) {
    $('._header').text('Edit User');
    $('#Username').attr('value', data.Username);
    loadRoles($('#role'), data.Role);
    $('#role').attr('disabled', true);

    var passwordField = '<div id="Password" class="_row" hidden><label>Password:</label><input type="password" id="PasswordField"></input></div>'
    $('form').append(passwordField);

    var checkBox = '<input type="checkbox" id="ChangePassword"> Change password </input>'
    $('form').append(checkBox);
    $('#ChangePassword').click(function() {
        if ($(this).is(':checked')) {
            $('#Password').show().attr('disabled', false);
            $('#PasswordField').prop('required', true);
        }
        else {
            $('#Password').hide().attr('disabled', true);
            $('#PasswordField').prop('required', false);
        }
    });

    var submitButton = '<input type="submit" id="submit" value="Submit"></input>';
    $('form').append(submitButton).submit(function(event) {
        if (event.preventDefault)
            event.preventDefault();
        else
            event.returnValue = false;

        var jsonRequest = {
            id : data.Id,
            username : $('#Username').val(),
            password : '',
            role_id : ''
        };

        if ($('#ChangePassword').prop('checked'))
            jsonRequest.password = $('#PasswordField').val();

        var requestStr = JSON.stringify(jsonRequest);
        //alert(requestStr);

        $.ajax({
            url: "api/updateUser.php",
            type: "POST",
            data: {
                user: requestStr
            },
            dataType: "JSON",
            success: function (jsonObj) {
                //alert(JSON.stringify(jsonObj));
                window.location.replace('showUser.php?UserId=' + jsonObj + '&action=show');
            },
            error: function(jsonObj) {
                alert('Error: ' + JSON.stringify(jsonObj));
            }
        });

    });
}

function showBlankUserData(data) {
    $('._header').text('Create User');
    $('#Username').attr('required', true).attr('value', '').attr('readonly', false);

    loadRoles($('#role'), '');

    var passwordField = '<div id="Password" class="_row"><label>Password:</label><input type="password" id="PasswordField" required></input></div>'
    //$('form').append(passwordField);
    $('form ._user_role').before(passwordField);

    var submitButton = '<input type="submit" id="submit" value="Submit"></input>';
    $('form').append(submitButton).submit(function(event) {
        if (event.preventDefault)
            event.preventDefault();
        else
            event.returnValue = false;

        var jsonRequest = {
            id : '',
            username : $('#Username').val(),
            password : $('#PasswordField').val(),
            role_id : $('#role').find(':selected').val()
        };

        var requestStr = JSON.stringify(jsonRequest);

        //alert(requestStr);

        $.ajax({
            url: "api/updateUser.php",
            type: "POST",
            data: {
                user: requestStr
            },
            dataType: "JSON",
            success: function (jsonObj) {
                //alert(JSON.stringify(jsonObj));
                window.location.replace('showUser.php?UserId=' + jsonObj + '&action=show');
            },
            error: function(jsonObj) {
                alert('Error: ' + JSON.stringify(jsonObj));
            }
        });
    });

}

function loadUser() {
    var urlVars = getUrlVars();
    var action = urlVars['action'];

    if (action !== 'create') {
        var id = parseInt(urlVars['UserId']);
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

                        if (onSuccess == null) {
                            alert('Error: permission denied');
                            return;
                        }

                        $.getJSON("api/getUser.php", {
                            UserId: decodeURI(id)
                        }).done(onSuccess);
                    }
                });
            } else {
                var onSuccess = null;
                switch(action) {
                    case 'edit': {
                        onSuccess = is_admin ? showAdministratorEditableUserData : null;
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

                if (onSuccess == null) {
                    alert('Error: permission denied');
                    return;
                }

                $.getJSON("api/getUser.php", {
                    UserId: decodeURI(id)
                }).done(onSuccess);
            }
        }
    });
}