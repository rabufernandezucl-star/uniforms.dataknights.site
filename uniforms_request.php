<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

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
    $full_name  = $_POST['email'];
    $course     = $_POST['course'];
    $uniform    = $_POST['uniform'];
    $size       = $_POST['size'];

    // Insert request
    $stmt = $pdo->prepare("
        INSERT INTO uniforms_request
        (student_id, full_name, email ,course, uniform, size, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Submit Uniform Request</h1>

<?php if($error): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if($success): ?>
    <p style="color:green"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<!-- Back button -->
<a href="index"><button type="button">Back</button></a>

<form method="POST">
    <input type="text" name="student_id" placeholder="Student ID" required><br>
    <input type="text" name="full_name" placeholder="Full Name" required><br>
    <input type="text" name="email" placeholder="email" required><br>
    <input type="text" name="course" placeholder="Course" required><br>
    <input type="text" name="uniform" placeholder="Uniform Type" required><br>
    <input type="text" name="size" placeholder="Size" required><br>
    <button type="submit">Submit Request</button>
</form>

</body>
</html>
