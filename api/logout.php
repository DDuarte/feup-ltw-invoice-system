<?php
    require_once 'details\user_management.php';

    if (!isset($_SESSION)) {
        session_save_path(realpath($_SERVER['DOCUMENT_ROOT']) . '/sessions');
        session_start();
    }
    session_destroy();

    redirect();
