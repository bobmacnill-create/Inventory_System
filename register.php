<?php
session_start();
require 'db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 4) {
        $error = "Password must be at least 4 characters.";
    } else {
        // Check if username already exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username is already taken.";
        } else {
            // Hash password and insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $success = "Account created successfully! <a href='login.php'>Click here to login</a>.";
            } else {
                $error = "Error creating account. Please try again.";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Inventory System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h2>Create Account</h2>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p style="color: #28a745; font-size: 14px;"><?= $success ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Choose Username" required>
            <input type="password" name="password" placeholder="Choose Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <p style="text-align: center; font-size: 14px; margin-top: 15px;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</body>
</html>