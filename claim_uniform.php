<?php
require_once "shared/config/db.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $key = $_POST['key'] ?? '';

    if(empty($key)){
        echo "error";
        exit;
    }

    // Kunin muna current stock
    $stmt = $pdo->prepare("SELECT stock, status FROM uniforms WHERE uniform_key = ?");
    $stmt->execute([$key]);
    $uniform = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$uniform){
        echo "error";
        exit;
    }

    $currentStock = intval($uniform['stock']);

    if($currentStock <= 0){
        echo "out";
        exit;
    }

    $newStock = $currentStock - 1;

    // Optional: automatic status kapag 0
    $newStatus = ($newStock == 0) ? "Not Available" : $uniform['status'];

    $update = $pdo->prepare("
        UPDATE uniforms
        SET stock = ?, status = ?
        WHERE uniform_key = ?
    ");

    $update->execute([$newStock, $newStatus, $key]);

    echo json_encode([
        "stock" => $newStock,
        "status" => $newStatus
    ]);
}