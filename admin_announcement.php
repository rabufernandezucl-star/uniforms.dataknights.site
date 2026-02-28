<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

/* ================= ADMIN SECURITY ================= */
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1){
    header("Location: index.php");
    exit();
}

$message = "";
$success = "";

/* ================= SAVE ANNOUNCEMENT ================= */
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $announcement = $_POST['message'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    if(!empty($announcement) && !empty($start) && !empty($end)){

        $stmt = $pdo->prepare("
            INSERT INTO announcements (message, start_date, end_date)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([$announcement, $start, $end]);

        $success = "Announcement successfully updated!";
    } else {
        $message = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Announcement</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="admin-box">

    <h2>Edit Announcement</h2>

    <?php if($message): ?>
        <p class="error"><?= $message ?></p>
    <?php endif; ?>

    <?php if($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST">
        <textarea name="message" placeholder="Enter announcement..." required></textarea>

        <label>Start Date:</label>
        <input type="date" name="start_date" required>

        <label>End Date:</label>
        <input type="date" name="end_date" required>

        <button type="submit">Save Announcement</button>
    </form>

    <br>
    <a href="index.php">
        <button>Back</button>
    </a>

</div>

</body>
</html>