<?php
include '../includes/db.php';

// Secure this page: check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Check if an item ID is provided in the URL
if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']);

    // Prepare a statement to delete the item
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        // Redirect back to the dashboard with a success message
        header("Location: dashboard.php?deleted=true");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
} else {
    // Redirect if no ID is provided
    header("Location: dashboard.php");
    exit();
}

$conn->close();
?>