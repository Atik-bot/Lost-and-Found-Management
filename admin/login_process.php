<?php
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a statement to select the user and check if they are an admin
    $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $is_admin);
        $stmt->fetch();

        // Verify the password AND check if the user is an admin
        if (password_verify($password, $hashed_password) && $is_admin == 1) {
            // Set admin session variables
            $_SESSION['admin_id'] = $user_id;
            $_SESSION['admin_email'] = $email;
            header("Location: dashboard.php"); // Redirect to admin dashboard
            exit();
        } else {
            // Redirect back with an error for invalid credentials or not being an admin
            header("Location: index.php?error=Access Denied. Invalid credentials or not an administrator.");
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
