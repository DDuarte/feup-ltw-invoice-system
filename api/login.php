<?php
    require_once 'details/user_management.php';

    if (!array_key_exists("username", $_POST))
        redirect();

    $username = $_POST['username'];
    $username = htmlspecialchars($username);

    if (!array_key_exists("password", $_POST))
        redirect();

    $password = $_POST['password'];
    $password = htmlspecialchars($password);

    if ($username != "" && $password != "")
    {
        $db = new PDO('sqlite:../sql/OIS.db');
        $stmt = $db->prepare('SELECT username, password, role.name AS role FROM user JOIN role on user.role_id = role.id WHERE username = :username');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();

        $hashedPassword = encrypt_credentials($username, $password);

        if ($result)
        {
            if ($result['password'] == $hashedPassword)
            {
                if (!isset($_SESSION))
                    session_start();
                $_SESSION['username'] = $result['username'];
            }
        }
    }

    redirect();
