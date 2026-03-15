<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

$id=$_GET['id'];

$stmt=$pdo->prepare("DELETE FROM sales WHERE id=?");
$stmt->execute([$id]);

header("Location: record_sale.php");
