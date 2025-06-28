<?php
require '../includes/auth.php';
require_login();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, amount) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $amount]);

    header('Location: list.php');
    exit;
}
?>

<h2>Add Transaction</h2>
<form method="post">
    Amount: <input type="number" step="0.01" name="amount" required><br><br>
    <button type="submit">Save</button>
</form>