<?php
$host = 'localhost';
$db = 'payment';
$user = 'root';
$pass = '123456';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
