<?php
require_once 'shared/config/db.php';

/* ================= UPDATE ================= */
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

/* ================= CLAIM ================= */
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

/* ================= LOAD ================= */
$stmt = $pdo->prepare("SELECT * FROM uniforms WHERE uniform_key = 'cap_black'");
$stmt->execute();
$cap = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
<title>Cap – Black</title>

<style>
body { font-family: Arial; }
table { border-collapse: collapse; width: 60%; }
th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
.available { color: green; font-weight: bold; }
.not-available { color: red; font-weight: bold; }
button { padding: 6px 12px; cursor: pointer; }
</style>

</head>
<body>

<div class="section">
<h2>Cap – Black</h2>

<table>
<tr>
<th>Item</th>
<th>Stock</th>
<th>Status</th>
<th>Action</th>
<th>Admin</th>
</tr>

<tr>
<td>Cap – Black</td>

<td><?= $cap['stock'] ?? 0 ?></td>

<td class="<?= ($cap['status'] == 'Available') ? 'available' : 'not-available' ?>">
<?= $cap['status'] ?? 'Not Available' ?>
</td>

<td>
<form method="POST" style="display:inline;">
<input type="hidden" name="uniform_key" value="cap_black">
<button type="submit" name="claim"
<?= (($cap['stock'] ?? 0) <= 0) ? 'disabled' : '' ?>>
Claim
</button>
</form>
</td>

<td>
<form method="POST" style="display:inline;">
<input type="number"
       name="stock"
       value="<?= $cap['stock'] ?? 0 ?>"
       min="0"
       required>

<input type="hidden"
       name="uniform_key"
       value="cap_black">

<button type="submit" name="update">
Update
</button>
</form>
</td>

</tr>

</table>
</div>

</body>
</html>
