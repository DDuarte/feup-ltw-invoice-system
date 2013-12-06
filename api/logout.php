<?php
    require_once 'details\user_management.php';

    if (!isset($_SESSION))
        session_start();
    session_destroy();

    redirect();
