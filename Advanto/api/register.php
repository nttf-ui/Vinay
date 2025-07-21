<?php
// File: api/register.php

// Include the database connection
require 'db_connect.php';

// Set response header to JSON
header('Content-Type: application/json');

// --- Input Validation ---
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($phone) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with(strtolower($email), '@gmail.com')) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format or not a @gmail.com address.']);
    exit;
}

if (!preg_match('/^\d{10}$/', $phone)) {
    echo json_encode(['status' => 'error', 'message' => 'Phone number must be exactly 10 digits.']);
    exit;
}

// --- Check for Existing User ---
try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'This email address is already registered.']);
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database query failed: ' . $e->getMessage()]);
    exit;
}

// --- Hash Password & Insert User ---
// Use bcrypt for secure password hashing
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (email, phone, password) VALUES (?, ?, ?)");
    $stmt->execute([$email, $phone, $hashed_password]);

    // Send success response
    echo json_encode(['status' => 'success', 'message' => 'Registration successful! Please sign in.']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Could not register user: ' . $e->getMessage()]);
}