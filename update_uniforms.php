<?php
require_once "shared/config/db.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $key = $_POST['key'] ?? '';
    $stock = intval($_POST['stock'] ?? 0);
    $status = $_POST['status'] ?? '';

    if(empty($key)){
        echo "error";
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE uniforms
        SET stock = ?, status = ?
        WHERE uniform_key = ?
    ");

    if($stmt->execute([$stock, $status, $key])){
        echo "Update Successful";
    } else {
        echo "error";
    }
}
?>
