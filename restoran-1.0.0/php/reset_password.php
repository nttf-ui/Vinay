<?php
require_once 'db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $confirmNewPassword = $_POST['confirm_new_password'] ?? '';

    // Validation
    if (empty($email)) {
        $response['errors']['email'] = 'Email is required';
    }

    if (empty($newPassword)) {
        $response['errors']['new_password'] = 'New password is required';
    } elseif (strlen($newPassword) < 8) {
        $response['errors']['new_password'] = 'Password must be at least 8 characters';
    }

    if ($newPassword !== $confirmNewPassword) {
        $response['errors']['confirm_new_password'] = 'Passwords do not match';
    }

    if (empty($response['errors'])) {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            
            if ($updateStmt->execute([$hashedPassword, $email])) {
                $response['success'] = true;
                $response['message'] = 'Password updated successfully!';
            } else {
                $response['errors']['general'] = 'Failed to update password. Please try again.';
            }
        } else {
            $response['errors']['email'] = 'Email not found';
        }
    }
}

echo json_encode($response);
?>