<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

/* ===== ADMIN SECURITY ===== */
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1){
    header("Location: index.php");
    exit();
}

/* ===== GET CLAIMED DATA ===== */
$stmt = $pdo->query("
    SELECT 
        uniform_key,
        size,
        COUNT(*) as total_claimed
    FROM claimed_uniforms
    GROUP BY uniform_key, size
    ORDER BY uniform_key
");

$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Claimed Records</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Claimed Uniform Summary</h1>

<a href="index.php">
    <button>Back to Dashboard</button>
</a>

<br><br>

<table border="1" cellpadding="10">
<tr>
    <th>Uniform</th>
    <th>Size</th>
    <th>Total Claimed</th>
</tr>

<?php foreach($records as $row): ?>
<tr>
    <td><?= htmlspecialchars($row['uniform_key']); ?></td>
    <td><?= htmlspecialchars($row['size']); ?></td>
    <td><?= $row['total_claimed']; ?></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>