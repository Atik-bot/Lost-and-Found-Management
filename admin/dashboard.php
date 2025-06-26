<?php
include '../includes/db.php';

// Secure this page
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
$admin_email = $_SESSION['admin_email'];

// Fetch Statistics
$total_members_result = $conn->query("SELECT COUNT(*) as count FROM users WHERE is_admin = FALSE");
$total_members = $total_members_result->fetch_assoc()['count'];

$total_lost_result = $conn->query("SELECT COUNT(*) as count FROM items WHERE status = 'lost'");
$total_lost = $total_lost_result->fetch_assoc()['count'];

$total_found_result = $conn->query("SELECT COUNT(*) as count FROM items WHERE status = 'found'");
$total_found = $total_found_result->fetch_assoc()['count'];

// Fetch all items for the list below
$items_sql = "SELECT items.*, users.name as user_name FROM items JOIN users ON items.user_id = users.id ORDER BY report_date DESC";
$items_result = $conn->query($items_sql);
?>

<?php include 'admin_header.php'; // Use the admin header ?>

<aside class="main-sidebar">
    <div class="sidebar-header">
        <a href="dashboard.php">Lost and Found</a>
    </div>
    <div class="admin-user-panel">
        <img src="https://placehold.co/128x128/EFEFEF/AAAAAA&text=A" alt="Admin Image">
        <div class="info">
            <a href="#"><?php echo htmlspecialchars($admin_email); ?></a>
        </div>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="user_management.php" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i> User Management
        </a>
        <a href="#items-list" class="nav-link">
             <i class="nav-icon fas fa-list"></i> Lost and Found Items
        </a>
        <a href="../logout.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</aside>

<main class="content-wrapper">
    <section class="content-header">
        <h1>Dashboard</h1>
        <ol class="breadcrumb">
            <li><a href="#">Home</a> / Dashboard</li>
        </ol>
    </section>

    <section class="content">
        <div class="stats-container">
            <div class="stat-card bg-info">
                <div class="inner">
                    <h3><?php echo $total_members; ?></h3>
                    <p>Total Members</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="user_management.php" class="more-info">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
            <div class="stat-card bg-primary">
                <div class="inner">
                    <h3><?php echo $total_lost; ?></h3>
                    <p>Total Lost Items</p>
                </div>
                <div class="icon"><i class="fas fa-search"></i></div>
                 <a href="#items-list" class="more-info">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
            <div class="stat-card bg-success">
                <div class="inner">
                    <h3><?php echo $total_found; ?></h3>
                    <p>Total Found Items</p>
                </div>
                <div class="icon"><i class="fas fa-briefcase"></i></div>
                 <a href="#items-list" class="more-info">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="items-list-container" id="items-list">
            <h3>All Reported Items</h3>
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="padding: 10px; text-align: left;">Item Name</th>
                        <th style="padding: 10px; text-align: left;">Status</th>
                        <th style="padding: 10px; text-align: left;">Reported By</th>
                        <th style="padding: 10px; text-align: left;">Date</th>
                        <th style="padding: 10px; text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($items_result && $items_result->num_rows > 0): ?>
                        <?php while($row = $items_result->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;"><?php echo htmlspecialchars($row['item_name']); ?></td>
                                <td style="padding: 10px;"><?php echo htmlspecialchars($row['status']); ?></td>
                                <td style="padding: 10px;"><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td style="padding: 10px;"><?php echo date('Y-m-d', strtotime($row['report_date'])); ?></td>
                                <td style="padding: 10px;">
                                    <a href="delete_item.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');" style="color: #dc3545; text-decoration:none;">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="padding: 10px; text-align: center;">No items reported.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

</div> </body>
</html>
<?php $conn->close(); ?>