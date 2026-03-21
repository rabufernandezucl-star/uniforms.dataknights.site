<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

/* ===== ADMIN SECURITY ===== */
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1){
    header("Location: /"); // clean login redirect
    exit();
}

/* ===== GET MONTHLY CLAIMED DATA ===== */
$stmt = $pdo->query("
    SELECT 
        DATE_FORMAT(claim_date, '%Y-%m') AS year_month,
        uniform_name,
        COUNT(id) AS total_claimed
    FROM claim_records
    GROUP BY year_month, uniform_name
    ORDER BY year_month DESC, uniform_name
");

$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===== CSV DOWNLOAD ===== */
if(isset($_GET['download']) && $_GET['download'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="claimed_records.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Month', 'Uniform', 'Total Claimed']); // CSV header

    foreach($records as $row){
        fputcsv($output, [$row['year_month'], $row['uniform_name'], $row['total_claimed']]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Claimed Uniform Records (Monthly)</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        h1 { color: #2fb36f; }
        table { border-collapse: collapse; width: 100%; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background: #2fb36f; color: white; }
        a button { padding: 10px 15px; margin-right: 10px; border: none; background: #2fb36f; color: #fff; cursor: pointer; border-radius: 5px; }
        a button:hover { opacity: 0.9; }
    </style>
</head>
<body>

<h1>Claimed Uniform Summary (Monthly)</h1>

<!-- Buttons -->
<a href="index"><button>Back to Dashboard</button></a>
<a href="records.php?download=csv"><button>Download CSV</button></a>

<br><br>

<!-- Table -->
<table>
    <tr>
        <th>Month</th>
        <th>Uniform</th>
        <th>Total Claimed</th>
    </tr>

    <?php if(count($records) > 0): ?>
        <?php foreach($records as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['year_month']); ?></td>
                <td><?= htmlspecialchars($row['uniform_name']); ?></td>
                <td><?= $row['total_claimed']; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">No Records Found</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
