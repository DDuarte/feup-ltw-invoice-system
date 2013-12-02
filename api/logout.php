<?php
    require_once 'details\user_management.php';

    session_start();
    session_destroy();

    redirect();
