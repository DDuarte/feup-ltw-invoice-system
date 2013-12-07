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
        $stmt = $db->prepare('SELECT id, username, password FROM user WHERE username = :username');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();

        $hashedPassword = encrypt_credentials($username, $password);

        if ($result)
        {
            if ($result['password'] == $hashedPassword)
            {
                if (!isset($_SESSION)) {
                    session_save_path(realpath($_SERVER['DOCUMENT_ROOT']) . '/sessions');
                    session_start();
                }
                $_SESSION['username'] = $result['username'];
                $_SESSION['user_id'] = $result['id'];
            }
        }
    }

    redirect();
