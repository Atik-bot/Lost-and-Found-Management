<?php
include '../includes/db.php';

// Secure this page
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
$admin_email = $_SESSION['admin_email'];

// Fetch all non-admin users
$users_sql = "SELECT id, name, email, is_active FROM users WHERE is_admin = FALSE ORDER BY name";
$users_result = $conn->query($users_sql);
?>
<?php include 'admin_header.php'; ?>

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
        <a href="dashboard.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="user_management.php" class="nav-link active">
            <i class="nav-icon fas fa-users-cog"></i> User Management
        </a>
        <a href="dashboard.php#items-list" class="nav-link">
             <i class="nav-icon fas fa-list"></i> Lost and Found Items
        </a>
        <a href="../logout.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</aside>

<main class="content-wrapper">
    <section class="content-header">
        <h1>User Management</h1>
        <ol class="breadcrumb">
            <li><a href="dashboard.php">Home</a> / User Management</li>
        </ol>
    </section>

    <section class="content">
        <div class="items-list-container">
            <h3>Manage User Accounts</h3>
             <?php if(isset($_GET['status_changed'])): ?>
                <p style="color:green; text-align:center;">User status has been successfully updated.</p>
            <?php endif; ?>
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="padding: 10px; text-align: left;">Name</th>
                        <th style="padding: 10px; text-align: left;">Email</th>
                        <th style="padding: 10px; text-align: center;">Status</th>
                        <th style="padding: 10px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users_result && $users_result->num_rows > 0): ?>
                        <?php while($user = $users_result->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 10px;"><?php echo htmlspecialchars($user['name']); ?></td>
                                <td style="padding: 10px;"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td style="padding: 10px; text-align: center;">
                                    <?php if ($user['is_active']): ?>
                                        <span style="color: green; font-weight: bold;">Active</span>
                                    <?php else: ?>
                                        <span style="color: red; font-weight: bold;">Deactivated</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 10px; text-align: center;">
                                    <?php if ($user['is_active']): ?>
                                        <a href="toggle_user_status.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to deactivate this user?');" style="color: #dc3545; text-decoration:none;">Deactivate</a>
                                    <?php else: ?>
                                         <a href="toggle_user_status.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to reactivate this user?');" style="color: #28a745; text-decoration:none;">Reactivate</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="padding: 10px; text-align: center;">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

</div> </body>
</html>
<?php $conn->close(); ?>