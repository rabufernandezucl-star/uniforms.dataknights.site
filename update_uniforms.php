<?php
require_once 'shared/config/db.php';

if(isset($_POST['key'], $_POST['stock'], $_POST['status'])){

    $key = $_POST['key'];
    $stock = $_POST['stock'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("
        UPDATE uniforms
        SET stock = ?, status = ?
        WHERE uniform_key = ?
    ");

    if($stmt->execute([$stock, $status, $key])){
        echo "Update Successful";
    }else{
        echo "Update Failed";
    }

}else{
    echo "Invalid Request";
}
?>
