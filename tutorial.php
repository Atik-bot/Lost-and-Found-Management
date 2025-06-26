<?php
include 'includes/db.php';
// Secure this page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<?php include 'includes/header.php'; ?>
<div class="main-container">
    <div class="sidebar">
        <div class="logo">
            <img src="images/logo.png" alt="Project Logo" style="max-width: 120px; margin: auto;">
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="report_item.php" class="nav-link">Report an Item</a>
            <a href="view_items.php" class="nav-link">View Items</a>
            <a href="notifications.php" class="nav-link">Notifications</a>
            <a href="tutorial.php" class="nav-link active">Tutorial</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </nav>
    </div>
    <div class="content-area">
        <div class="dashboard-header">
            <h2>User Tutorial</h2>
            <p>This video will guide you on how to use the Lost & Found system effectively.</p>
        </div>
        
        <!-- Embedded Video with Updated Link -->
        <div class="video-container">
            <iframe 
                src="https://www.youtube.com/embed/cZhjLtbEiyc" 
                title="YouTube video player" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
