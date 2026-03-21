<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

// Admin-only access
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1){
    header("Location: /");
    exit();
}

try {
    $stmt = $pdo->query("
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') AS `month`,
            student_id,
            full_name,
            email,
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
    fputcsv($output, ['Month', 'Student ID', 'Full Name','email', 'Course', 'Uniform', 'Size', 'Created At', 'Claimed At']);

    foreach($requests as $row){
        fputcsv($output, [
            $row['month'],
            $row['student_id'],
            $row['full_name'],
             $row['email'],
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
<title>Uniform Requests Admin</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1>All Uniform Requests (Admin)</h1>
<a href="index"><button>Back to Dashboard</button></a>
<a href="uniforms_request_admin.php?download=csv"><button>Download CSV</button></a>

<table border="1" cellpadding="8">
<tr>
    <th>Month</th>
    <th>Student ID</th>
    <th>Full Name</th>
    <th>email</th>
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
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= htmlspecialchars($row['course']); ?></td>
            <td><?= htmlspecialchars($row['uniform']); ?></td>
            <td><?= htmlspecialchars($row['size']); ?></td>
            <td><?= htmlspecialchars($row['created_at']); ?></td>
            <td><?= htmlspecialchars($row['claimed_at']); ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="8">No Requests Found</td></tr>
<?php endif; ?>
</table>
</body>
</html>
