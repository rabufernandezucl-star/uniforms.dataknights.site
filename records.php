<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

/* ===== ADMIN SECURITY ===== */
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1){
    header("Location: /");
    exit();
}

/* ===== GET CLAIMED DATA ===== */
$stmt = $pdo->query("
    SELECT 
        uniform_name,
        COUNT(id) AS total_claimed
    FROM claim_records
    GROUP BY uniform_name
    ORDER BY uniform_name
");

$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===== CSV DOWNLOAD ===== */
if(isset($_GET['download']) && $_GET['download'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="claimed_records.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Uniform', 'Total Claimed']); // CSV header

    foreach($records as $row){
        fputcsv($output, [$row['uniform_name'], $row['total_claimed']]);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Claimed Records</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Claimed Uniform Summary</h1>

<!-- Buttons -->
<a href="index.php"><button>Back to Dashboard</button></a>
<a href="records.php?download=csv"><button>Download CSV</button></a>

<br><br>

<!-- Table -->
<table border="1" cellpadding="10">
<tr>
    <th>Uniform</th>
    <th>Total Claimed</th>
</tr>

<?php if(count($records) > 0): ?>
    <?php foreach($records as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['uniform_name']); ?></td>
            <td><?= $row['total_claimed']; ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="2">No Records Found</td>
    </tr>
<?php endif; ?>

</table>

</body>
</html>
