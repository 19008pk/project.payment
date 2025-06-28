<?php

// Log all errors, notices, and warnings
error_reporting(E_ALL);

// Don't display errors to the browser (production-safe)
ini_set('display_errors', 0);

// Log all errors to a file inside the project
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error.log');

session_start();

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
