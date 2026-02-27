<?php
require_once "shared/config/db.php";

if(isset($_POST['key'], $_POST['stock'], $_POST['status'])){

    $key = $_POST['key'];
    $stock = intval($_POST['stock']);
    $status = $_POST['status'];

    try {

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

    } catch(PDOException $e){
        echo "SQL ERROR: " . $e->getMessage();
    }

} else {
    echo "invalid_post";
}
?>
