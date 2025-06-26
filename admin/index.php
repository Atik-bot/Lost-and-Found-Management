<?php
// We include the db.php file from the parent directory
include '../includes/db.php'; 

// If admin is already logged in, redirect to the admin dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found Network</title>
    <!-- Corrected CSS Path for Admin Directory -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="container">
            <!-- Logo -->
            <div class="text-center mb-6">
                <img src="../images/logo.png" alt="Project Logo" style="max-width: 150px; margin: auto;">
            </div>
            
            <!-- Headings -->
            <h2>Welcome Back!</h2>
            <p class="text-center" style="margin-top:-20px; margin-bottom:20px;">Please login to your account.</p>
            
            <?php if(isset($_GET['error'])): ?>
                <p style="color:red; text-align:center;"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>

            <!-- Login form pointing to admin processor -->
            <form action="login_process.php" method="post">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
             
             <!-- Links now match the user login page -->
             <p class="text-center mt-3">Not registered yet? <a href="../register.php">Create an Account</a></p>
             <p class="text-center mt-3" style="font-size: 14px;"><a href="../index.php">User Login</a></p>
        </div>
    </div>
</body>
</html>
