<?php

function is_logged_in()
{
    session_start();
    return array_key_exists("username", $_SESSION);
}

function redirect_if_not_logged_in()
{
    if (!is_logged_in())
    {
        header("Location: " . $_SERVER['REQUEST_URI'] . "authenticate.php");
    }
}

function get_role()
{
    if (!is_logged_in()) return null;

    $username = $_SESSION['username'];

    $db = new PDO('sqlite:../sql/OIS.db');
    $query = "SELECT role.name AS name FROM user JOIN role on user.role_id = role.id WHERE user.username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $role = $stmt->fetch();

    return $role['name'];
}

function encrypt_credentials($username, $password)
{
    return hash("sha512", $username . ":" . $password);
}

function is_admin()
{
    $role = get_role();
    return is_logged_in() && $role == "Administrator";
}

function is_editor()
{
    $role = get_role();
    return is_logged_in() && ($role == "Editor" || $role == "Administrator");
}

function is_reader()
{
    $role = get_role();
    return is_logged_in() && ($role == "Reader" || $role == "Editor" || $role == "Administrator");
}