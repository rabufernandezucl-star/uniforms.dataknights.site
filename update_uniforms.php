<?php
require_once 'shared/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $key = $_POST['uniform_key'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $status = $_POST['status'] ?? 'Not Available';

    $stmt = $pdo->prepare("
        UPDATE uniforms
        SET stock = ?, status = ?
        WHERE uniform_key = ?
    ");

    $stmt->execute([$stock, $status, $key]);

    echo "Updated";
}
?>
