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

if ($txn['status'] !== 'pending') {
    echo "Only pending transactions can be paid.";
    echo '<br><a href="list.php">Back to list</a>';
    exit;
}
?>

<h2>Pay for Transaction #<?= $txn['id'] ?></h2>
<p>Amount: $<?= $txn['amount'] ?></p>

<form method="post" action="../payments/process.php">
    <input type="hidden" name="transaction_id" value="<?= $txn['id'] ?>">
    <input type="hidden" name="action" value="pay">
    <label>Select Payment Gateway:</label><br>
    <select name="gateway" required>
        <option value="">--Select--</option>
        <?php foreach ($gateways as $key => $label): ?>
            <option value="<?= $key ?>"><?= htmlspecialchars($label) ?></option>
        <?php endforeach; ?>
    </select><br><br>
    <button type="submit">Proceed to Pay</button>
</form>