<?php
// File: api/db_connect.php

// --- Database Configuration ---
$db_host = 'localhost';   // Or your database host
$db_name = 'advanto_db';  // The database you created
$db_user = 'root';        // Your database username
$db_pass = '';            // Your database password

// --- Establish Connection ---
try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // If connection fails, stop the script and display an error
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit(); // Terminate the script
}