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

function encrypt_credentials($username, $password)
{
    return hash("sha512", $username . ":" . $password);
}