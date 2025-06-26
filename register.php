<?php
include 'includes/db.php';
$message = '';
// (No changes to the PHP logic)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $city = $_POST['city'];
    $contact_number = $_POST['contact_number'];
    if (empty($name) || empty($email) || empty($password)) {
        $message = "Please fill in all required fields.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, city, contact_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $hashed_password, $city, $contact_number);
        if ($stmt->execute()) {
            header("Location: index.php?registered=true");
            exit();
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<?php include 'includes/header.php'; ?>
<div class="auth-wrapper">
    <div class="container">
        <!-- Logo Added Here -->
        <div class="text-center mb-6">
            <img src="images/logo.png" alt="Project Logo" style="max-width: 150px; margin: auto;">
        </div>
        
        <h2>Create Your Account</h2>
        
        <?php if(!empty($message)): ?>
            <p style="color:red; text-align:center;"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
             <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number">
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city">
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <p class="text-center mt-3">Already a member? <a href="index.php">Log In</a></p>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
