<?php
require_once __DIR__ . '/shared/config/db.php';

$uniform = $_POST['uniform'];
$price = $_POST['price'];
$qty = $_POST['qty'];

$total = $price * $qty;

$stmt = $pdo->prepare("
INSERT INTO sales
(uniform_name,price,quantity,total,sale_date)
VALUES (?,?,?,?,CURDATE())
");

$stmt->execute([
$uniform,
$price,
$qty,
$total
]);

echo json_encode([
"success"=>true
]);