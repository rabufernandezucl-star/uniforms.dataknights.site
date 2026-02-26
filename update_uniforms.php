<?php
require_once 'shared/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $key = $_POST['uniform_key'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $status = $_POST['status'] ?? 'Not Available';

    if (empty($key)) {
        die("Missing uniform key");
    }

    try {

        $stmt = $pdo->prepare("
            UPDATE uniforms
            SET stock = ?, status = ?
            WHERE uniform_key = ?
        ");

        $stmt->execute([$stock, $status, $key]);

        if ($stmt->rowCount() > 0) {
            echo "SUCCESS";
        } else {
            echo "No rows updated. Check uniform_key.";
        }

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }

} else {
    echo "Invalid request.";
}
?>
