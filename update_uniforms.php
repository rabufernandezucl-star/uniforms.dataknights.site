<?php
require_once "shared/config/db.php";

if(isset($_POST['key'], $_POST['stock'], $_POST['status'])){

    $key = $_POST['key'];
    $stock = intval($_POST['stock']);
    $status = $_POST['status'];

    $stmt = $pdo->prepare("
        UPDATE uniforms
        SET stock = ?, status = ?
        WHERE uniform_key = ?
    ");

    if(!$stmt){
        die("Prepare failed: " . implode(" | ", $pdo->errorInfo()));
    }

    if($stmt->execute([$stock, $status, $key])){

        if($stmt->rowCount() > 0){
            echo "success";
        } else {
            echo "No row updated (check uniform_key)";
        }

    } else {
        echo "Execute failed: ";
        print_r($stmt->errorInfo());
    }

} else {
    echo "Invalid POST data";
}
?>
