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
        u.uniform_key,
        u.size,
        COUNT(c.id) AS total_claimed
    FROM uniforms u
    LEFT JOIN claimed_uniforms c 
        ON u.uniform_key = c.uniform_key 
        AND u.size = c.size
    GROUP BY u.uniform_key, u.size
    ORDER BY u.uniform_key
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
