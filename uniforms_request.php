<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$error = "";
$success = "";

// Check if user is logged in AND not admin
if(!isset($_SESSION['username']) || ($_SESSION['is_admin'] ?? 0) == 1){
    header("Location: /");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $student_id = $_POST['student_id'];
    $full_name  = $_POST['full_name'];
    $email      = $_POST['email']; 
    $course     = $_POST['course'];
    $uniform    = $_POST['uniform'];
    $size       = $_POST['size'];

    $stmt = $pdo->prepare("
        INSERT INTO uniforms_request
        (student_id, full_name, email, course, uniform, size, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    if($stmt->execute([$student_id, $full_name, $email, $course, $uniform, $size])){
        $success = "Request submitted successfully!";
    } else {
        $error = "Failed to submit request.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Submit Uniform Request</title>
    <link rel="stylesheet" href="uniforms_request.css">
</head>

<body>

<div class="uniforms-container">
    <h1>Submit Uniform Request</h1>

    <?php if($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <a href="index.php">
        <button type="button" class="back-btn">Back</button>
    </a>

    <form method="POST">
        <input type="text" name="student_id" placeholder="Student ID" required>
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="course" placeholder="Course" required>
        <input type="text" name="uniform" placeholder="Uniform Type" required>
        <input type="text" name="size" placeholder="Size (S, M, L, XL)" required>

        <button type="submit">Submit Request</button>
    </form>
</div>

</body>
</html>
