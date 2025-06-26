<?php
include 'includes/db.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// --- Search Logic ---
// Initialize the search query part of the SQL statement
$search_query = "";
$search_term = '';

// Check if a search term was submitted via GET request
if (isset($_GET['search']) && !empty($_GET['search'])) {
    // Sanitize the search term to prevent SQL injection
    $search_term = $conn->real_escape_string($_GET['search']);
    // Build the WHERE clause for the SQL query to search across multiple fields
    $search_query = " WHERE item_name LIKE '%$search_term%' OR category LIKE '%$search_term%' OR location LIKE '%$search_term%' OR description LIKE '%$search_term%'";
}

// --- SQL Query to Fetch Items ---
// Base SQL query to join items and users tables
$sql = "SELECT items.*, users.name as user_name, users.email as user_email FROM items JOIN users ON items.user_id = users.id" . $search_query . " ORDER BY report_date DESC";
$result = $conn->query($sql);
?>

<?php include 'includes/header.php'; // Include the standard header ?>

<div class="main-container">
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="logo">Lost & Found</div>
        <nav class="sidebar-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="report_item.php">Report an Item</a>
            <a href="view_items.php" class="active">View Items</a>
            <a href="notifications.php">Notifications</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>

    <!-- Main Content Area -->
    <div class="content-area">
        <h2>Browse Reported Items</h2>

        <!-- Search Form -->
        <form action="view_items.php" method="get" class="form-group" style="display: flex; gap: 10px; align-items:center;">
            <input type="text" name="search" placeholder="Search by keyword, category, location..." value="<?php echo htmlspecialchars($search_term); ?>" style="flex-grow:1;">
            <button type="submit" class="btn" style="width: auto;">Search</button>
        </form>

        <!-- Display Items -->
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="item-card">
                    <h3>
                        <?php echo htmlspecialchars($row['item_name']); ?> 
                        <span style="font-size: 14px; font-weight:500; color: #888;">(Status: <?php echo htmlspecialchars($row['status']); ?>)</span>
                    </h3>
                    
                    <?php if ($row['item_image']): // Display image if it exists ?>
                        <img src="<?php echo htmlspecialchars($row['item_image']); ?>" alt="Item Image" style="max-width: 150px; border-radius: 8px; margin-bottom: 15px;">
                    <?php endif; ?>

                    <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                    
                    <hr style="border: 0; border-top: 1px solid #f0f0f0; margin: 15px 0;">
                    
                    <p>
                        <small>
                            Reported by: <?php echo htmlspecialchars($row['user_name']); ?> | Contact: <?php echo htmlspecialchars($row['user_email']); ?> <br> 
                            Date: <?php echo date('F j, Y, g:i a', strtotime($row['report_date'])); ?>
                        </small>
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No items found matching your search criteria.</p>
        <?php endif; ?>
    </div>
</div>

<?php
// Close the database connection and include the footer
$conn->close();
include 'includes/footer.php';
?>
