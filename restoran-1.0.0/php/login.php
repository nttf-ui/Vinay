<?php
require_once 'db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($email)) {
        $response['errors']['email'] = 'Email is required';
    }

    if (empty($password)) {
        $response['errors']['password'] = 'Password is required';
    }

    if (empty($response['errors'])) {
        // Check user
        $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Start session
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            $response['success'] = true;
            $response['message'] = 'Login successful!';
            $response['redirect'] = 'dashboard.php'; // Change to your dashboard page
        } else {
            $response['errors']['general'] = 'Invalid email or password';
        }
    }
}

echo json_encode($response);
?>