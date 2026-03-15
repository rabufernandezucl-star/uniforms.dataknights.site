<?php
session_start();
require 'db.php';
if(!isset($_SESSION['username'])){ header('Location: login.php'); exit; }

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['uniform_name'];
    $qty  = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $total = $qty * $price;

    $stmt = $pdo->prepare("INSERT INTO sales (uniform_name, quantity, price, total) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $qty, $price, $total]);

    header('Location: record_sale.php'); exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Add Sale</title></head>
<body>
<h2>Add New Sale</h2>
<form method="post">
    Uniform Name: <input type="text" name="uniform_name" required><br><br>
    Quantity: <input type="number" name="quantity" required><br><br>
    Price: <input type="number" step="0.01" name="price" required><br><br>
    <button type="submit">Add Sale</button>
</form>
<p><a href="record_sale.php">Back to Dashboard</a></p>
</body>
</html>
