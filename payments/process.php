<?php
require '../includes/auth.php';
require_login();
require '../includes/db.php';
require '../services/PaymentService.php';
require '../services/TransactionService.php';

$transactionId = $_POST['transaction_id'];
$gateway = $_POST['gateway'] ?? null;
$action = $_POST['action'];
$userId = $_SESSION['user_id'];

if (!$transactionId || !$action) {
    echo "❌ Invalid request: missing transaction.";
    echo "<br><a href='../transaction/list.php'>Back to Transactions</a>";
    exit;
}

// Fetch transaction
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$transactionId, $userId]);
$txn = $stmt->fetch();

if (!$txn) {
    echo "❌ Transaction not found.";
    echo "<br><a href='../transaction/list.php'>Back to Transactions</a><br>";
    exit;
}

$transactionService = new TransactionService($pdo);

if ($action === 'refund') {
    if ($txn['status'] !== 'paid') {
        echo "❌ Refund not allowed. Only paid transactions can be refunded.<br>";
    } else {
        $response = PaymentService::refund($txn);

        if ($response['success']) {
            echo "✅ Refund successful via <strong>" . $txn['payment_gateway'] . "</strong>!<br>";
            $transactionService->updateTransactionStatus($txn['id'], $response);
        } else {
            echo "❌ Refund failed.<br>";
        }
    }
} else { // action is 'pay' (default)
    if ($txn['status'] !== 'pending') {
        echo "❌ Payment not allowed. Only pending transactions can be paid.<br>";
    } else {
        $response = PaymentService::pay($gateway, $txn);

        if ($response['success']) {
            $transactionService = new TransactionService($pdo);
            $transactionService->updateTransactionStatus($txn['id'], $response, $gateway);
            echo "✅ Payment successful via <strong>$gateway</strong>!<br>";
        } else {
            echo "❌ Payment failed via <strong>$gateway</strong>.<br>";
        }
    }
}

echo "Message: " . $response['message'] . "<br>";
echo "Reference: " . $response['reference_id'] . "<br>";
echo "Status: " . $response['status'] . "<br>";

echo "<br><a href='../transaction/list.php'>Back to Transactions</a>";
