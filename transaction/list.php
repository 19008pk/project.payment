<?php
require '../includes/auth.php';
require_login();
require '../includes/db.php';

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ?");
$stmt->execute([$userId]);
$transactions = $stmt->fetchAll();
?>

<h2>Your Transactions</h2>
<a href="add.php">Add Transaction</a><br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Gateway</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($transactions as $txn): ?>
        <tr>
            <td><?= $txn['id'] ?></td>
            <td><?= $txn['amount'] ?></td>
            <td><?= $txn['status'] ?></td>
            <td>
                <?php if ($txn['status'] === 'pending'): ?>
                    <a href="pay.php?id=<?= $txn['id'] ?>">Pay</a>
                <?php endif; ?>
                <?= $txn['payment_gateway'] ?>
            </td>
            <td>
                <a href="edit.php?id=<?= $txn['id'] ?>">Edit</a>
            </td>
            <td>
                <a href="delete.php?id=<?= $txn['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>