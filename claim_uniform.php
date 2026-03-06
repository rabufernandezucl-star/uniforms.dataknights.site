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
    $size = $_POST['size'] ?? '';

    if(empty($key) || empty($size)){
        echo "error";
        exit;
    }

    /* CHECK SESSION DATA */
    if(
        !isset($_SESSION['student_db_id']) ||
        !isset($_SESSION['student_id']) ||
        !isset($_SESSION['student_name'])
    ){
        echo "session_error";
        exit;
    }

    $student_db_id = $_SESSION['student_db_id'];
    $student_id = $_SESSION['student_id'];
    $student_name = $_SESSION['student_name'];

    /* GET UNIFORM */
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

    if($uniform['status'] !== 'Available'){
        echo "not_available";
        exit;
    }

    $currentStock = intval($uniform['stock']);

    if($currentStock <= 0){
        echo "out";
        exit;
    }

    /* INSERT CLAIM RECORD */
    $insert = $pdo->prepare("
        INSERT INTO claimed_uniforms 
        (student_db_id, student_id, student_name, uniform_key, size, quantity)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $insert->execute([
        $student_db_id,
        $student_id,
        $student_name,
        $key,
        $size,
        1
    ]);

    /* UPDATE STOCK */
    $newStock = $currentStock - 1;
    $newStatus = ($newStock == 0) ? "Not Available" : "Available";

    $update = $pdo->prepare("
        UPDATE uniforms
        SET stock = ?, status = ?
        WHERE uniform_key = ?
    ");

    $update->execute([
        $newStock,
        $newStatus,
        $key
    ]);

    echo json_encode([
        "stock" => $newStock,
        "status" => $newStatus
    ]);
}
?>
