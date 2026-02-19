<?php
$host = "localhost";

$username = "knights2025_admin";

$password = "M84Q.z]M~FjVO((!";

$dbname = "knights2025_uniforms_system";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
