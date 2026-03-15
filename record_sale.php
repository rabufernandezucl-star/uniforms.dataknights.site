<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

$stmt=$pdo->query("SELECT * FROM sales ORDER BY sale_date DESC");
$sales=$stmt->fetchAll();

$totalRevenue=$pdo->query("SELECT SUM(total) FROM sales")->fetchColumn();
?>

<h2>Sales Dashboard</h2>

<p>Total Revenue: ₱<?= number_format($totalRevenue,2) ?></p>

<a href="add_sale.php">Add Sale</a>

<table border="1">
<tr>
<th>ID</th>
<th>Uniform</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php foreach($sales as $sale): ?>

<tr>
<td><?= $sale['id'] ?></td>
<td><?= $sale['uniform_name'] ?></td>
<td><?= $sale['quantity'] ?></td>
<td><?= $sale['price'] ?></td>
<td><?= $sale['total'] ?></td>
<td><?= $sale['sale_date'] ?></td>

<td>
<a href="edit_sale.php?id=<?= $sale['id'] ?>">Edit</a>
<a href="delete_sale.php?id=<?= $sale['id'] ?>">Delete</a>
</td>

</tr>

<?php endforeach; ?>
</table>
