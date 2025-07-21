<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html'); // Redirect to your main page
    exit();
}

require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
    <a href="logout.php">Logout</a>
</body>
</html>