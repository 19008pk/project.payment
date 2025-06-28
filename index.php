<?php
require 'includes/auth.php';
require_login();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>
    <h1>Welcome, <?php echo $_SESSION['email']; ?>!</h1>
    <p><a href="transaction/list.php">Go to Transaction List</a></p>

    <p><a href="logout.php">Logout</a></p>
</body>

</html>