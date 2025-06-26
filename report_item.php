<?php
include 'includes/db.php';
include 'includes/matching_engine.php'; // Include the matching engine

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $item_image = null;
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . uniqid() . '_' . basename($_FILES["item_image"]["name"]);
        if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
            $item_image = $target_file;
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
    if(empty($message)) {
        $stmt = $conn->prepare("INSERT INTO items (user_id, item_name, category, location, description, item_image, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $user_id, $item_name, $category, $location, $description, $item_image, $status);
        if ($stmt->execute()) {
            $newItemId = $stmt->insert_id;
            $message = "Item reported successfully!";

            // *** NEW: Trigger the matching algorithm ***
            $newItem = [
                'id' => $newItemId,
                'status' => $status,
                'category' => $category,
                'location' => $location
            ];
            findAndNotifyMatches($conn, $newItem);
            // ****************************************

        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
// Note: We are not closing the connection here because findAndNotifyMatches needs it.
// It will be closed at the end of the script.
?>

<?php include 'includes/header.php'; ?>
<div class="main-container">
    <div class="sidebar">
        <div class="logo">Lost & Found</div>
        <nav class="sidebar-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="report_item.php" class="active">Report an Item</a>
            <a href="view_items.php">View Items</a>
            <a href="notifications.php">Notifications</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
    <div class="content-area">
        <h2>Report a Lost or Found Item</h2>
        <?php if(!empty($message)): ?>
            <p style="color:green; text-align:center;"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="report_item.php" method="post" enctype="multipart/form-data">
             <div class="form-group">
                <label for="status">What are you reporting?</label>
                <select id="status" name="status" required>
                    <option value="lost">I Lost an Item</option>
                    <option value="found">I Found an Item</option>
                </select>
            </div>
            <div class="form-group">
                <label for="item_name">Item Name</label>
                <input type="text" id="item_name" name="item_name" required>
            </div>
            <div class="form-group">
                <label for="category">Category (e.g., Electronics, Wallet, Keys)</label>
                <input type="text" id="category" name="category" required>
            </div>
            <div class="form-group">
                <label for="location">Location Lost/Found</label>
                <input type="text" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="item_image">Upload Image (Optional)</label>
                <input type="file" id="item_image" name="item_image">
            </div>
            <button type="submit" class="btn">Submit Report</button>
        </form>
    </div>
</div>
<?php
$conn->close();
include 'includes/footer.php';
?>
