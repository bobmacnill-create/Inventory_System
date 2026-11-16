<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require 'db.php';

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (isset($_POST['update_item'])) {
    $name = trim($_POST['item_name']);
    $category = trim($_POST['category']);
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];

    $stmt = $conn->prepare("UPDATE items SET item_name=?, category=?, quantity=?, price=? WHERE id=?");
    $stmt->bind_param("ssidi", $name, $category, $quantity, $price, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h2>Edit Inventory Item</h2>
        <form method="POST" action="">
            <label>Item Name</label>
            <input type="text" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required>
            <label>Category</label>
            <input type="text" name="category" value="<?= htmlspecialchars($item['category']) ?>" required>
            <label>Quantity</label>
            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="0" required>
            <label>Price</label>
            <input type="number" step="0.01" name="price" value="<?= $item['price'] ?>" min="0" required>
            <button type="submit" name="update_item">Update Item</button>
            <a href="index.php" class="cancel-link">Cancel</a>
        </form>
    </div>
</body>
</html>