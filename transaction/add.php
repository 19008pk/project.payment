<?php
require '../includes/auth.php';
require_login();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $gateway = $_POST['payment_gateway'];

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, amount, status, payment_gateway) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $amount, $status, $gateway]);

    header('Location: list.php');
    exit;
}
?>

<h2>Add Transaction</h2>
<form method="post">
    Amount: <input type="number" step="0.01" name="amount" required><br><br>
    Status:
    <select name="status">
        <option>pending</option>
        <option>paid</option>
        <option>failed</option>
    </select><br><br>
    <button type="submit">Save</button>
</form>