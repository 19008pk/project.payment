<?php

$config = require __DIR__ . '/../config/.env.php';

$dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4";

require_once 'LoggedPDO.php';

try {
    $pdo = new LoggedPDO($dsn, $config['db_user'], $config['db_pass']);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
