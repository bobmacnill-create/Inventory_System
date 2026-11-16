<?php
session_start();
require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Inventory System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h2>Inventory System Login</h2>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username (admin)" required>
            <input type="password" name="password" placeholder="Password (password123)" required>
            <button type="submit">Login</button>
        </form>

                <p style="text-align: center; font-size: 14px; margin-top: 15px;">
                    Don't have an account? <a href="register.php">Register here</a>
                </p>

    </div>
</body>
</html>