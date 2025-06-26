<?php
include 'includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Fetch notifications for the logged-in user
$sql = "SELECT n.*, i.item_name as matched_item_name FROM notifications n JOIN items i ON n.matched_item_id = i.id WHERE n.user_id = ? ORDER BY n.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Mark notifications as read
$updateStmt = $conn->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ? AND is_read = FALSE");
$updateStmt->bind_param("i", $user_id);
$updateStmt->execute();
$updateStmt->close();
?>

<?php include 'includes/header.php'; ?>
<div class="main-container">
    <div class="sidebar">
        <div class="logo">Lost & Found</div>
        <nav class="sidebar-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="report_item.php">Report an Item</a>
            <a href="view_items.php">View Items</a>
            <a href="notifications.php" class="active">Notifications</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
    <div class="content-area">
        <h2>Your Notifications</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="item-card">
                    <p><?php echo htmlspecialchars($row['message']); ?></p>
                    <p><a href="view_items.php?search=<?php echo urlencode($row['matched_item_name']); ?>">View the matched item: "<?php echo htmlspecialchars($row['matched_item_name']); ?>"</a></p>
                    <small>Received on: <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">You have no notifications.</p>
        <?php endif; ?>
    </div>
</div>
<?php
$stmt->close();
$conn->close();
include 'includes/footer.php';
?>
