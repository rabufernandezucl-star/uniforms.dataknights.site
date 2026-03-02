<?php
require_once "shared/config/db.php";
session_start();

/* USER ONLY CAN CLAIM */
if(!isset($_SESSION['username']) || $_SESSION['is_admin']){
    echo "unauthorized";
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $key = $_POST['key'] ?? '';

    if(empty($key)){
        echo "error";
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT stock, status 
        FROM uniforms 
        WHERE uniform_key = ?
    ");
    $stmt->execute([$key]);
    $uniform = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$uniform){
        echo "error";
        exit;
    }

    /* CHECK STATUS */
    if($uniform['status'] !== 'Available'){
        echo "not_available";
        exit;
    }

    $currentStock = intval($uniform['stock']);

    if($currentStock <= 0){
        echo "out";
        exit;
    }

    $newStock = $currentStock - 1;
    $newStatus = ($newStock == 0) ? "Not Available" : "Available";

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
?>
