<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/shared/config/db.php';

/* ===== ADMIN SECURITY ===== */
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1){
    header("Location: index.php");
    exit();
}

try {

    /* ===== GET DETAILED CLAIM DATA ===== */
    $stmt = $pdo->query("
        SELECT 
            student_name,
            student_id,
            uniform_key,
            size,
            claimed_at
        FROM claimed_uniforms
        ORDER BY claimed_at DESC
    ");

    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Claimed Records</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Claimed Uniform History</h1>

<a href="index.php">
    <button>Back to Dashboard</button>
</a>

<br><br>

<?php if(empty($records)): ?>
    <p>No claimed records yet.</p>
<?php else: ?>

<table border="1" cellpadding="10">
<tr>
    <th>Student Name</th>
    <th>Student ID</th>
    <th>Uniform</th>
    <th>Size</th>
    <th>Date Claimed</th>
</tr>

<?php foreach($records as $row): ?>
<tr>
    <td><?= htmlspecialchars($row['student_name']); ?></td>
    <td><?= htmlspecialchars($row['student_id']); ?></td>
    <td><?= htmlspecialchars($row['uniform_key']); ?></td>
    <td><?= htmlspecialchars($row['size']); ?></td>
    <td><?= htmlspecialchars($row['claimed_at']); ?></td>
</tr>
<?php endforeach; ?>

</table>

<?php endif; ?>

</body>
</html>
