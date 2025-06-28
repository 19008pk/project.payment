<?php
require '../includes/auth.php';
require_login();
require '../includes/db.php';

$transactionId = $_POST['transaction_id'] ?? null;
$gateway = $_POST['gateway'] ?? null;
$userId = $_SESSION['user_id'];

if (!$transactionId || !$gateway) {
    echo "Transaction not found.";
    echo "<a href='../transaction/list.php'>Back to Transactions</a>";
    exit;
}

// Validate transaction
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$transactionId, $userId]);
$txn = $stmt->fetch();

if (!$txn) {
    echo "Transaction not found";
    echo "<br><a href='../transaction/list.php'>Back to Transactions</a>";
    exit;
}

// Step 2: Check status
if ($txn['status'] == 'paid') {
    echo "Only pending transactions can be paid. This transaction is <strong>{$txn['status']}</strong>.";
    echo "<br><a href='../transaction/list.php'>Back to Transactions</a>";
    exit;
}

// Call payment service

// Simulate payment success
$status = 'paid'; // simulate success

$update = $pdo->prepare("UPDATE transactions SET status = ?, payment_gateway = ? WHERE id = ?");
$update->execute([$status, $gateway, $transactionId]);

echo "Payment successful using <strong>$gateway</strong>!<br>";
echo "<a href='../transaction/list.php'>Back to Transactions</a>";
