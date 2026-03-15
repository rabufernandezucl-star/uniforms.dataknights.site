<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

if($_SERVER["REQUEST_METHOD"]=="POST"){

$name=$_POST['uniform_name'];
$qty=$_POST['quantity'];
$price=$_POST['price'];

$total=$qty*$price;

$stmt=$pdo->prepare("INSERT INTO sales(uniform_name,quantity,price,total,sale_date)
VALUES(?,?,?,?,NOW())");

$stmt->execute([$name,$qty,$price,$total]);

header("Location: record_sale.php");
}
?>

<h2>Add Sale</h2>

<form method="POST">

Uniform Name<br>
<input type="text" name="uniform_name"><br><br>

Quantity<br>
<input type="number" name="quantity"><br><br>

Price<br>
<input type="number" name="price"><br><br>

<button type="submit">Save</button>

</form>
