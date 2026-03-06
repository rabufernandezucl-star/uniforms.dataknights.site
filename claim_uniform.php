<?php
require_once "shared/config/db.php";
session_start();

/* ================= USER ONLY CAN CLAIM ================= */
if (!isset($_SESSION['username']) || $_SESSION['is_admin'] == 1) {
    echo "unauthorized";
    exit;
}

/* ================= CHECK REQUEST ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $key  = $_POST['key'] ?? '';
    $size = $_POST['size'] ?? '';

    if (empty($key) || empty($size)) {
        echo "error";
        exit;
    }

    /* ================= CHECK SESSION DATA ================= */
    if (
        !isset($_SESSION['username']) ||
        !isset($_SESSION['student_id'])
    ) {
        echo "session_error";
        exit;
    }

    $username  = $_SESSION['username'];
    $studentId = $_SESSION['student_id'];

    /* ================= GET UNIFORM ================= */
    $stmt = $pdo->prepare("
        SELECT stock, status 
        FROM uniforms 
        WHERE uniform_key = ?
    ");
    $stmt->execute([$key]);
    $uniform = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$uniform) {
        echo "error";
        exit;
    }

    if ($uniform['status'] !== 'Available') {
        echo "not_available";
        exit;
    }

    $currentStock = intval($uniform['stock']);

    if ($currentStock <= 0) {
        echo "out";
        exit;
    }

    /* ================= INSERT CLAIM RECORD ================= */
    $insert = $pdo->prepare("
        INSERT INTO claim_records 
        (username, uniform_name, size)
        VALUES (?, ?, ?)
    ");

    $insert->execute([
        $username,
        $key,
        $size
    ]);

    /* ================= UPDATE STOCK ================= */
    $newStock = $currentStock - 1;
    $newStatus = ($newStock <= 0) ? "Not Available" : "Available";

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

    /* ================= RETURN RESPONSE ================= */
    echo json_encode([
        "stock"  => $newStock,
        "status" => $newStatus
    ]);
}
?>
