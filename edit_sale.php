<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

$id=$_GET['id'];

$stmt=$pdo->prepare("SELECT * FROM sales WHERE id=?");
$stmt->execute([$id]);
$sale=$stmt->fetch();

if($_SERVER["REQUEST_METHOD"]=="POST"){

$name=$_POST['uniform_name'];
$qty=$_POST['quantity'];
$price=$_POST['price'];

$total=$qty*$price;

$stmt=$pdo->prepare("UPDATE sales SET uniform_name=?,quantity=?,price=?,total=? WHERE id=?");
$stmt->execute([$name,$qty,$price,$total,$id]);

header("Location: record_sale.php");
}
?>

<h2>Edit Sale</h2>

<form method="POST">

<input type="text" name="uniform_name" value="<?= $sale['uniform_name'] ?>"><br><br>

<input type="number" name="quantity" value="<?= $sale['quantity'] ?>"><br><br>

<input type="number" name="price" value="<?= $sale['price'] ?>"><br><br>

<button type="submit">Update</button>

</form>
