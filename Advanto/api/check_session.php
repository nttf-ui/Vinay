<?php
// File: api/check_session.php

session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_email'])) {
    // User is logged in
    echo json_encode([
        'isLoggedIn' => true,
        'user_email' => $_SESSION['user_email']
    ]);
} else {
    // User is not logged in
    echo json_encode(['isLoggedIn' => false]);
}