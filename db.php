<?php
$host = "localhost";
$username = "knights2025_knights_admin";  // ← tamang user
$password = "M84Q.z]M~FjVO((!";
$dbname = "knights2025_uniforms_system";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "Connected successfully!";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
