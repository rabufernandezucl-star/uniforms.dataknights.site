<?php
require_once 'shared/config/db.php';

$key = $_POST['key'];
$stock = $_POST['stock'];
$status = $_POST['status'];

$stmt = $pdo->prepare("
    UPDATE uniforms
    SET stock = ?, status = ?
    WHERE uniform_key = ?
");

$stmt->execute([$stock, $status, $key]);

echo "Rows affected: " . $stmt->rowCount();
?>
