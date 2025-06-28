<?php
require '../includes/auth.php';
require_login();
require '../includes/db.php';

$userId = $_SESSION['user_id'];

// Fetch transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ?");
$stmt->execute([$userId]);
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Your Transactions</title>
</head>

<body>

    <h2>Your Transactions</h2>
    <p><a href="add.php">➕ Add New Transaction</a></p>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Gateway</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($transactions) > 0): ?>
                <?php foreach ($transactions as $txn): ?>
                    <tr>
                        <td><?= htmlspecialchars($txn['id']) ?></td>
                        <td>$<?= htmlspecialchars($txn['amount']) ?></td>
                        <td><?= ucfirst($txn['status']) ?></td>
                        <td><?= htmlspecialchars($txn['payment_gateway']) ?></td>
                        <td>
                            <?php if ($txn['status'] === 'pending'): ?>
                                <a class="action" href="pay.php?id=<?= $txn['id'] ?>">💳 Pay</a>
                            <?php elseif ($txn['status'] === 'paid'): ?>
                                <a class="action" href="refund.php?id=<?= $txn['id'] ?>" onclick="return confirm('Are you sure you want to refund this transaction?')">↩️ Refund</a>
                            <?php endif; ?>
                            <?php if ($txn['status'] === 'pending'): ?>
                                <a class="action" href="edit.php?id=<?= $txn['id'] ?>">✏️ Edit</a>
                            <?php endif; ?>
                            <?php if ($txn['status'] === 'pending'): ?>
                                <a class="action" href="delete.php?id=<?= $txn['id'] ?>" onclick="return confirm('Are you sure you want to delete this transaction?')">🗑️ Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No transactions found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>