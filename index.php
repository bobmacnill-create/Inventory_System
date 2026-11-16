<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require 'db.php';

// CREATE: Add new item
if (isset($_POST['add_item'])) {
    $name = trim($_POST['item_name']);
    $category = trim($_POST['category']);
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];

    $stmt = $conn->prepare("INSERT INTO items (item_name, category, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssid", $name, $category, $quantity, $price);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}

// DELETE: Remove item
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}

// READ: Fetch all items
$result = $conn->query("SELECT * FROM items ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h2>Inventory Dashboard</h2>
            <div>
                <span>Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span> |
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </header>

        <!-- Form to Add Item -->
        <form method="POST" action="" id="itemForm" class="add-form">
            <input type="text" name="item_name" id="item_name" placeholder="Item Name" required>
            <input type="text" name="category" id="category" placeholder="Category" required>
            <input type="number" name="quantity" id="quantity" placeholder="Quantity" min="0" required>
            <input type="number" step="0.01" name="price" id="price" placeholder="Unit Price" min="0" required>
            <button type="submit" name="add_item">Add Item</button>
        </form>

        <!-- Table to Display Items -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= number_format($row['price'], 2) ?></td>
                    <td><?= number_format($row['quantity'] * $row['price'], 2) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                        <a href="index.php?delete=<?= $row['id'] ?>" class="btn-delete" onclick="return confirmDelete();">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="script.js"></script>
</body>
</html>