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

    $stmt->execute([$stock, $status, $key]);

    if($stmt->rowCount() > 0){
        echo "success";
    } else {
        echo "no_row_updated";
    }
}
?>
