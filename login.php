<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Also select the is_active status
    $stmt = $conn->prepare("SELECT id, password, is_active FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $is_active);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            
            // ** NEW: Check if the user is active **
            if ($is_active == 1) {
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;
                header("Location: dashboard.php");
                exit();
            } else {
                // User is deactivated
                header("Location: index.php?error=Your account has been deactivated. Please contact support.");
                exit();
            }

        } else {
            header("Location: index.php?error=Invalid password.");
            exit();
        }
    } else {
        header("Location: index.php?error=No user found with that email.");
        exit();
    }
    $stmt->close();
}
$conn->close();
?>