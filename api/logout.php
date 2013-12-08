<?php
    require_once 'details/user_management.php';

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_save_path(realpath($_SERVER['DOCUMENT_ROOT']) . '/sessions');
        session_start();
    }
    session_destroy();

    redirect();
