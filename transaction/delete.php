<?php
require '../includes/auth.php';
require_login();
require '../includes/db.php';

$id = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'];

if (!$id) {
    die('Invalid transaction ID.');
}

// Step 1: Fetch the transaction
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $userId]);
$txn = $stmt->fetch();

if (!$txn) {
    echo "Transaction not found";
    echo '<br><a href="list.php">Back to list</a>';
    exit;
}

// Step 2: Check status
if ($txn['status'] !== 'pending') {
    echo "Only pending transactions can be deleted. This transaction is <strong>{$txn['status']}</strong>.";
    echo '<br><a href="list.php">Back to list</a>';
    exit;
}

// Step 3: Proceed with delete
$del = $pdo->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
$del->execute([$id, $userId]);

header('Location: list.php');
exit;
