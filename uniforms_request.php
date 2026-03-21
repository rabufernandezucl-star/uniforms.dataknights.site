<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

/* ===== ADMIN ACCESS ===== */
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1){
    header("Location: index");
    exit();
}

/* ===== FETCH UNIFORMS REQUESTS ===== */
try {
    $stmt = $pdo->query("
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') AS `month`,
            student_id,
            full_name,
            course,
            uniform,
            size,
            created_at,
            claimed_at
        FROM uniforms_request
        ORDER BY created_at DESC
    ");
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

/* ===== CSV DOWNLOAD ===== */
if(isset($_GET['download']) && $_GET['download'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="uniforms_request.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Month', 'Student ID', 'Full Name', 'Course', 'Uniform', 'Size', 'Created At', 'Claimed At']);

    foreach($requests as $row){
        fputcsv($output, [
            $row['month'],
            $row['student_id'],
            $row['full_name'],
            $row['course'],
            $row['uniform'],
            $row['size'],
            $row['created_at'],
            $row['claimed_at']
        ]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Uniforms Requests</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        h1 { color: #2fb36f; }
        table { border-collapse: collapse; width: 100%; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background: #2fb36f; color: white; }
        a button { padding: 8px 12px; margin-right: 10px; border: none; background: #2fb36f; color: #fff; cursor: pointer; border-radius: 5px; }
        a button:hover { opacity: 0.9; }
    </style>
</head>
<body>

<h1>Uniforms Requests</h1>

<!-- Buttons -->
<a href="index"><button>Back to Dashboard</button></a>
<a href="uniforms_request.php?download=csv"><button>Download CSV</button></a>

<br><br>

<!-- Table -->
<table>
    <tr>
        <th>Month</th>
        <th>Student ID</th>
        <th>Full Name</th>
        <th>Course</th>
        <th>Uniform</th>
        <th>Size</th>
        <th>Created At</th>
        <th>Claimed At</th>
    </tr>

    <?php if(!empty($requests)): ?>
        <?php foreach($requests as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['month']); ?></td>
                <td><?= htmlspecialchars($row['student_id']); ?></td>
                <td><?= htmlspecialchars($row['full_name']); ?></td>
                <td><?= htmlspecialchars($row['course']); ?></td>
                <td><?= htmlspecialchars($row['uniform']); ?></td>
                <td><?= htmlspecialchars($row['size']); ?></td>
                <td><?= htmlspecialchars($row['created_at']); ?></td>
                <td><?= htmlspecialchars($row['claimed_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8">No Requests Found</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
