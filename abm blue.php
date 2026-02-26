<?php
require_once 'shared/config/db.php';

/* ================= UPDATE STOCK ================= */
if (isset($_POST['update'])) {

    $uniform_key = $_POST['uniform_key'];
    $stock = intval($_POST['stock']);

    $status = ($stock > 0) ? 'Available' : 'Not Available';

    $stmt = $pdo->prepare("
        UPDATE uniforms
        SET stock = ?, status = ?
        WHERE uniform_key = ?
    ");

    $stmt->execute([$stock, $status, $uniform_key]);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* ================= CLAIM SYSTEM ================= */
if (isset($_POST['claim'])) {

    $uniform_key = $_POST['uniform_key'];

    $stmt = $pdo->prepare("SELECT stock FROM uniforms WHERE uniform_key = ?");
    $stmt->execute([$uniform_key]);
    $row = $stmt->fetch();

    if ($row && $row['stock'] > 0) {

        $newStock = $row['stock'] - 1;
        $status = ($newStock > 0) ? 'Available' : 'Not Available';

        $update = $pdo->prepare("
            UPDATE uniforms
            SET stock = ?, status = ?
            WHERE uniform_key = ?
        ");

        $update->execute([$newStock, $status, $uniform_key]);
    }

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* ================= LOAD DATA ================= */
$stmt = $pdo->prepare("SELECT * FROM uniforms");
$stmt->execute();
$uniforms = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];
foreach ($uniforms as $row) {
    $data[$row['uniform_key']] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>SHS ABM Uniform</title>
<style>
table { border-collapse: collapse; width: 70%; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
.available { color: green; font-weight: bold; }
.not-available { color: red; font-weight: bold; }
button { padding: 5px 10px; cursor: pointer; }
</style>
</head>
<body>

<h2>SHS Uniform – ABM (Blue)</h2>

<table>
<tr>
<th>Size</th>
<th>Stock</th>
<th>Status</th>
<th>Claim</th>
<th>Admin</th>
</tr>

<?php
$sizes = ['xs','s','m','l','xl','xxl'];

foreach($sizes as $size):

$key = "abm_" . $size;
$stock = $data[$key]['stock'] ?? 0;
$status = $data[$key]['status'] ?? 'Not Available';
?>

<tr>
<td><?= strtoupper($size) ?></td>

<td><?= $stock ?></td>

<td class="<?= ($status == 'Available') ? 'available' : 'not-available' ?>">
<?= $status ?>
</td>

<td>
<form method="POST" style="display:inline;">
<input type="hidden" name="uniform_key" value="<?= $key ?>">
<button type="submit" name="claim"
<?= ($stock <= 0) ? 'disabled' : '' ?>>
Claim
</button>
</form>
</td>

<td>
<form method="POST" style="display:inline;">
<input type="number" name="stock" value="<?= $stock ?>" required>
<input type="hidden" name="uniform_key" value="<?= $key ?>">
<button type="submit" name="update">Update</button>
</form>
</td>

</tr>

<?php endforeach; ?>

</table>

</body>
</html>
