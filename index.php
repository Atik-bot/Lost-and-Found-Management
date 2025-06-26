<?php
include 'includes/db.php';
// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<?php include 'includes/header.php'; ?>
<div class="auth-wrapper">
    <div class="container">
        <!-- Logo Added Here -->
        <div class="text-center mb-6">
            <img src="images/logo.png" alt="Project Logo" style="max-width: 150px; margin: auto;">
        </div>
        
        <h2>Welcome Back!</h2>
        <p class="text-center" style="margin-top:-20px; margin-bottom:20px;">Please login to your account.</p>
        
        <?php if(isset($_GET['error'])): ?>
            <p style="color:red; text-align:center;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>
         <?php if(isset($_GET['registered'])): ?>
            <p style="color:green; text-align:center;">Registration successful! Please login.</p>
        <?php endif; ?>
        
        <form action="login.php" method="post">
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
        <p class="text-center mt-3">Not registered yet? <a href="register.php">Create an Account</a></p>
        <!-- Admin Login Link -->
        <p class="text-center mt-3" style="font-size: 14px;"><a href="admin/index.php">Administrator Login</a></p>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
