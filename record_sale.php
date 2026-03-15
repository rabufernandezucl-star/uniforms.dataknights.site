<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

// Fetch all sales for listing
$stmt = $pdo->query("SELECT * FROM sales ORDER BY sale_date DESC");
$sales = $stmt->fetchAll();

// Calculate total revenue
$totalRevenue = $pdo->query("SELECT SUM(total) FROM sales")->fetchColumn();

// Calculate revenue per uniform
$stmt2 = $pdo->query("
    SELECT uniform_name, SUM(quantity) as total_qty, SUM(total) as total_rev
    FROM sales
    GROUP BY uniform_name
    ORDER BY total_rev DESC
");
$uniformRevenue = $stmt2->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Uniform Sales Dashboard</title>
<style>
table { border-collapse: collapse; width: 90%; margin: auto; margin-bottom: 30px; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
th { background-color: #0b4da2; color: white; }
a { text-decoration: none; padding: 4px 8px; background: #0b4da2; color: #fff; border-radius: 4px; }
</style>
</head>
<body>
<h2 style="text-align:center;">Uniform Sales Dashboard</h2>
<p style="text-align:center;">Welcome, <?= $_SESSION['username'] ?> | <a href="logout.php">Logout</a></p>

<p style="text-align:center; font-weight:bold;">Total Revenue: ₱<?= number_format($totalRevenue, 2) ?></p>

<div style="text-align:center;margin-bottom:20px;">
    <a href="add_sale.php">Add New Sale</a>
</div>

<!-- Revenue per Uniform -->
<h3 style="text-align:center;">Revenue per Uniform</h3>
<table>
<tr>
    <th>Uniform Name</th>
    <th>Total Quantity Sold</th>
    <th>Total Revenue</th>
</tr>
<?php foreach($uniformRevenue as $ur): ?>
<tr>
    <td><?= $ur['uniform_name'] ?></td>
    <td><?= $ur['total_qty'] ?></td>
    <td>₱<?= number_format($ur['total_rev'],2) ?></td>
</tr>
<?php endforeach; ?>
</table>

<!-- Detailed Sales Table -->
<h3 style="text-align:center;">Detailed Sales Records</h3>
<table>
<tr>
    <th>ID</th>
    <th>Uniform Name</th>
    <th>Quantity</th>
    <th>Price</th>
    <th>Total</th>
    <th>Date</th>
    <th>Actions</th>
</tr>
<?php foreach($sales as $sale): ?>
<tr>
    <td><?= $sale['id'] ?></td>
    <td><?= $sale['uniform_name'] ?></td>
    <td><?= $sale['quantity'] ?></td>
    <td>₱<?= number_format($sale['price'],2) ?></td>
    <td>₱<?= number_format($sale['total'],2) ?></td>
    <td><?= $sale['sale_date'] ?></td>
    <td>
        <a href="edit_sale.php?id=<?= $sale['id'] ?>">Edit</a> |
        <a href="delete_sale.php?id=<?= $sale['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
