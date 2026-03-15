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

    $key = $_POST['key'] ?? '';

    if (empty($key)) {
        echo "error";
        exit;
    }

    if (!isset($_SESSION['username'])) {
        echo "session_error";
        exit;
    }

    $username = $_SESSION['username'];

    /* ================= GET UNIFORM ================= */
    $stmt = $pdo->prepare("
        SELECT stock, status, uniform_name 
        FROM uniforms 
        WHERE uniform_key = ?
    ");
    $stmt->execute([$key]);
    $uniform = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$uniform) {
        echo "uniform_not_found";
        exit;
    }

    if ($uniform['status'] !== 'Available') {
        echo "not_available";
        exit;
    }

    $currentStock = intval($uniform['stock']);

    if ($currentStock <= 0) {
        echo "out_of_stock";
        exit;
    }

    try {

        /* ================= INSERT INTO CLAIM_RECORDS ================= */

        $insert = $pdo->prepare("
            INSERT INTO claim_records 
            (uniform_key, uniform_name)
            VALUES (?, ?)
        ");

        $insert->execute([
            $key,
            $uniform['uniform_name']
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

        echo json_encode([
            "success" => true,
            "stock" => $newStock,
            "status" => $newStatus
        ]);

    } catch(PDOException $e) {

        echo "DB_ERROR: " . $e->getMessage();
        exit;

    }
}
?>
