<?php
require_once 'db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($name)) {
        $response['errors']['name'] = 'Full name is required';
    }

    if (empty($email)) {
        $response['errors']['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors']['email'] = 'Invalid email format';
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $response['errors']['email'] = 'Email already registered';
        }
    }

    if (empty($password)) {
        $response['errors']['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $response['errors']['password'] = 'Password must be at least 8 characters';
    }

    if ($password !== $confirmPassword) {
        $response['errors']['confirm_password'] = 'Passwords do not match';
    }

    if (empty($response['errors'])) {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $hashedPassword])) {
            $response['success'] = true;
            $response['message'] = 'Registration successful!';
        } else {
            $response['errors']['general'] = 'Registration failed. Please try again.';
        }
    }
}

echo json_encode($response);
?>