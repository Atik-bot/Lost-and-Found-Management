<?php
include 'includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// Get unread notification count
$notif_sql = "SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = ? AND is_read = FALSE";
$notif_stmt = $conn->prepare($notif_sql);
$notif_stmt->bind_param("i", $user_id);
$notif_stmt->execute();
$notif_result = $notif_stmt->get_result()->fetch_assoc();
$unread_count = $notif_result['unread_count'];
$notif_stmt->close();
// Get latest notifications
$latest_notif_sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 3";
$latest_stmt = $conn->prepare($latest_notif_sql);
$latest_stmt->bind_param("i", $user_id);
$latest_stmt->execute();
$latest_notifications = $latest_stmt->get_result();
$latest_stmt->close();
?>
<?php include 'includes/header.php'; ?>
<div class="main-container">
    <div class="sidebar">
        <div class="logo">
             <img src="images/logo.png" alt="Project Logo" style="max-width: 120px; margin: auto;">
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-link active">Dashboard</a>
            <a href="report_item.php" class="nav-link">Report an Item</a>
            <a href="view_items.php" class="nav-link">View Items</a>
            <a href="notifications.php" class="nav-link">Notifications <?php if($unread_count > 0) echo "<span style='background-color:red; color:white; border-radius:50%; padding: 2px 6px; font-size:12px;'>$unread_count</span>"; ?></a>
            <a href="tutorial.php" class="nav-link">Tutorial</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </nav>
    </div>
    <div class="content-area">
        <div class="dashboard-header">
            <h2>Dashboard</h2>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>! What would you like to do today?</p>
        </div>
        <div class="dashboard-actions">
            <a href="report_item.php" class="action-card">
                <h3>Report a New Item</h3>
                <p>Submit a report for an item you have either lost or found.</p>
            </a>
            <a href="view_items.php" class="action-card">
                <h3>View All Items</h3>
                <p>Browse and search through all publicly reported lost and found items.</p>
            </a>
            <!-- New Tutorial Card -->
            <a href="tutorial.php" class="action-card" style="grid-column: 1 / -1;">
                <h3>Watch Tutorial</h3>
                <p>Learn how to use the system with our step-by-step video guide.</p>
            </a>
        </div>
        <div style="margin-top: 40px;">
            <h3>Recent Notifications</h3>
            <?php if ($latest_notifications->num_rows > 0): ?>
                <?php while($notification = $latest_notifications->fetch_assoc()): ?>
                    <div class="item-card">
                        <p><?php echo htmlspecialchars($notification['message']); ?> <small style="float:right;"><?php echo date('M j', strtotime($notification['created_at'])); ?></small></p>
                    </div>
                <?php endwhile; ?>
                 <p class="text-center"><a href="notifications.php">View all notifications...</a></p>
            <?php else: ?>
                <p class="text-center">No recent notifications.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $conn->close(); ?>
<?php include 'includes/footer.php'; ?>
