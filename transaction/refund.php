<?php
require '../includes/auth.php';
require_login();
require '../includes/db.php';

$gateways = require '../config/gateways.php';

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

if ($txn['status'] !== 'paid') {
    echo "Only paid transactions can be refunded.";
    echo '<br><a href="list.php">Back to list</a>';
    exit;
}
?>

<h2>Refund for Transaction #<?= $txn['id'] ?></h2>
<p>Amount: $<?= $txn['amount'] ?></p>
<p>Gateway: $<?= $txn['payment_gateway'] ?></p>

<form method="post" action="../payments/process.php">
    <input type="hidden" name="transaction_id" value="<?= $txn['id'] ?>">
    <input type="hidden" name="action" value="refund">
    <label>Select Payment Gateway:</label><br>
    <select name="gateway" disabled>
        <option value="">--Select--</option>
        <?php foreach ($gateways as $key => $label): ?>
            <option value="<?= $key ?>" <?= $key === $txn['payment_gateway'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($label) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>
    <button type="submit">Proceed to Refund</button>
</form>