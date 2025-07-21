<?php
// File: api/logout.php

session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'You have been successfully logged out.']);