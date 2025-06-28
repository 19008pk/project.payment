<?php
require '../includes/auth.php';
require_login();
require '../includes/db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$txn = $stmt->fetch();

if (!$txn) {
    echo "Transaction not found";
    echo '<br><a href="list.php">Back to list</a>';
    exit;
}

if ($txn['status'] == 'paid') {
    echo "Only pending transactions can be edited. This transaction is <strong>{$txn['status']}</strong>.";
    echo '<br><a href="list.php">Back to list</a>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $txn = $stmt->fetch();

    if ($txn['status'] !== 'paid') {
        echo "Only pending transactions can be edited. This transaction is <strong>{$txn['status']}</strong>.";
        echo '<br><a href="list.php">Back to list</a>';
        exit;
    }

    $stmt = $pdo->prepare("UPDATE transactions SET amount = ?, status = ?, payment_gateway = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$amount, $status, $gateway, $id, $_SESSION['user_id']]);

    header('Location: list.php');
    exit;
}
?>

<h2>Edit Transaction</h2>
<form method="post">
    Amount: <input type="number" step="0.01" name="amount" value="<?= $txn['amount'] ?>" required><br><br>
    <button type="submit">Update</button>
</form>