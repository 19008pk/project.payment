<?php
require '../includes/auth.php';
require_login();
require '../includes/db.php';
require '../services/PaymentService.php';
require '../services/TransactionService.php';

$transactionId = $_POST['transaction_id'] ?? null;
$gateway = $_POST['gateway'] ?? null;
$userId = $_SESSION['user_id'];

if (!$transactionId || !$gateway) {
    echo "Transaction not found.";
    echo "<a href='../transaction/list.php'>Back to Transactions</a>";
    exit;
}

// Fetch transaction
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$transactionId, $userId]);
$txn = $stmt->fetch();

if (!$txn || $txn['status'] !== 'pending') {
    echo "Transaction not found";
    echo "<br><a href='../transaction/list.php'>Back to Transactions</a>";
    exit;
}

// 1. Process payment using PaymentService
$paymentResult = PaymentService::process($gateway, $txn);

// 2. Handle response using TransactionService
$transactionService = new TransactionService($pdo);

// 3. Show result
if ($paymentResult['success']) {

    echo "✅ Payment successful using <strong>$gateway</strong>!<br>";
    echo "Transaction Ref: " . $paymentResult['reference_id'] . "<br>";
    echo "Message: " . $paymentResult['message'] . "<br>";
    echo "Status Ref: " . $paymentResult['status'] . "<br>";
    $transactionService->updateTransactionStatus($txn['id'], $paymentResult, $gateway);
} else {
    echo "❌ Payment failed using <strong>$gateway</strong>.<br>";
    echo "Transaction Ref: " . $paymentResult['reference_id'] . "<br>";
    echo "Message: " . $paymentResult['message'] . "<br>";
    echo "Status Ref: " . $paymentResult['status'] . "<br>";
}

echo "<a href='../transaction/list.php'>Back to Transactions</a>";
