<?php
include '../includes/db.php';

// Secure this page
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Check if a user ID is provided
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Get the current status of the user
    $stmt = $conn->prepare("SELECT is_active FROM users WHERE id = ? AND is_admin = FALSE");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Flip the status (if it's 1, make it 0; if it's 0, make it 1)
        $new_status = !$user['is_active'];

        // Update the user's status in the database
        $update_stmt = $conn->prepare("UPDATE users SET is_active = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $new_status, $user_id);
        
        if ($update_stmt->execute()) {
            header("Location: user_management.php?status_changed=true");
            exit();
        } else {
            echo "Error updating user status.";
        }
        $update_stmt->close();
    } else {
        echo "User not found or you cannot change the status of an admin.";
    }
    $stmt->close();
} else {
    header("Location: user_management.php");
    exit();
}

$conn->close();
?>
