<?php
session_start();
require 'db.php';
if(!isset($_SESSION['username'])){ header('Location: login.php'); exit; }

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM sales WHERE id=?");
$stmt->execute([$id]);
$sale = $stmt->fetch();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['uniform_name'];
    $qty  = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $total = $qty * $price;

    $stmt = $pdo->prepare("UPDATE sales SET uniform_name=?, quantity=?, price=?, total=? WHERE id=?");
    $stmt->execute([$name, $qty, $price, $total, $id]);

    header('Location: dashboard.php'); exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Edit Sale</title></head>
<body>
<h2>Edit Sale</h2>
<form method="post">
    Uniform Name: <input type="text" name="uniform_name" value="<?= $sale['uniform_name'] ?>" required><br><br>
    Quantity: <input type="number" name="quantity" value="<?= $sale['quantity'] ?>" required><br><br>
    Price: <input type="number" step="0.01" name="price" value="<?= $sale['price'] ?>" required><br><br>
    <button type="submit">Update Sale</button>
</form>
<p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>