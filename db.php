<?php
$host = "localhost";
$username = "knights2025_knights_admin";
$password = "M84Q.z]M~FjVO((!";
$dbname = "knights_uniforms_system";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    echo "Connected successfully!";
} catch (PDOException $e) {
    die("Connection failed!");
}
?>
