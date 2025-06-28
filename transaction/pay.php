<?php
require '../includes/auth.php';
require_login();
require '../includes/db.php';

$id = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $userId]);
$txn = $stmt->fetch();

if (!$txn) {
    echo "Transaction not found or access denied.";
    echo '<br><a href="list.php">Back to list</a>';
    exit;
}

if ($txn['status'] !== 'pending') {
    echo "Only pending transactions can be paid.";
    echo '<br><a href="list.php">Back to list</a>';
    exit;
}
?>

<h2>Pay for Transaction #<?= $txn['id'] ?></h2>
<p>Amount: $<?= $txn['amount'] ?></p>

<form method="post" action="">
    <input type="hidden" name="transaction_id" value="<?= $txn['id'] ?>">
    <label>Select Payment Gateway:</label><br>
    <select name="gateway" required>
        <option value="">--Select--</option>
        <option value="paypal">PayPal</option>
        <option value="stripe">Stripe</option>
        <option value="razorpay">Razorpay</option>
    </select><br><br>
    <button type="submit">Proceed to Pay</button>
</form>