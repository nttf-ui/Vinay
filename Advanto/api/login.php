<?php
// File: api/login.php

// Start a session to store user data
session_start();

require 'db_connect.php';
header('Content-Type: application/json');

// --- Input Validation ---
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter both email and password.']);
    exit;
}

// --- Find User and Verify Password ---
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];

        // Send success response with user data
        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful!',
            'user_email' => $user['email']
        ]);
    } else {
        // Invalid credentials
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database query failed: ' . $e->getMessage()]);
}