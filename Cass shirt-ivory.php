<?php
require_once 'shared/config/db.php';

/* ================= UPDATE ================= */
if(isset($_POST['update_uniform'])){
    $key = $_POST['uniform_key'];
    $stock = $_POST['stock'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE uniforms SET stock=?, status=? WHERE uniform_key=?");
    $stmt->execute([$stock,$status,$key]);

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

/* ================= CLAIM ================= */
if(isset($_POST['claim_uniform'])){
    $key = $_POST['uniform_key'];

    $stmt = $pdo->prepare("SELECT stock FROM uniforms WHERE uniform_key=?");
    $stmt->execute([$key]);
    $row = $stmt->fetch();

    if($row && $row['stock'] > 0){
        $newStock = $row['stock'] - 1;
        $newStatus = ($newStock > 0) ? "Available" : "Not Available";

        $update = $pdo->prepare("UPDATE uniforms SET stock=?, status=? WHERE uniform_key=?");
        $update->execute([$newStock,$newStatus,$key]);
    }

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

/* ================= FETCH ================= */
$stmt = $pdo->prepare("SELECT * FROM uniforms");
$stmt->execute();
$uniforms = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];
foreach($uniforms as $u){
    $data[$u['uniform_key']] = $u;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>CASS PSY</title>
<style>
table { border-collapse: collapse; width:100%; }
th,td { border:1px solid #ccc; padding:8px; text-align:center; }
.available { color:green; font-weight:bold; }
.not-available { color:red; font-weight:bold; }
</style>
</head>
<body>

<div class="section">
<h2>CASS Shirt Ivory (PSY)</h2>

<table>
<tr>
<th>Size</th>
<th>Stock</th>
<th>Status</th>
<th>Action</th>
<th>Admin</th>
</tr>

<?php
$sizes = ['xs','s','m','l','xl','xxl','xxxl','xxxxl'];

foreach($sizes as $size):

$key = "cass-psy-$size";
$stock = $data[$key]['stock'] ?? 0;
$status = $data[$key]['status'] ?? "Not Available";
$statusClass = ($status=="Available") ? "available" : "not-available";
?>

<tr>
<td><?= strtoupper($size) ?></td>

<td><?= $stock ?></td>

<td class="<?= $statusClass ?>">
<?= $status ?>
</td>

<td>
<form method="POST">
<input type="hidden" name="uniform_key" value="<?= $key ?>">
<button type="submit" name="claim_uniform">Claim</button>
</form>
</td>

<td>
<form method="POST">
<input type="hidden" name="uniform_key" value="<?= $key ?>">
<input type="number" name="stock" value="<?= $stock ?>" required>

<select name="status">
<option value="Available" <?= ($status=="Available")?'selected':'' ?>>Available</option>
<option value="Not Available" <?= ($status=="Not Available")?'selected':'' ?>>Not Available</option>
</select>

<button type="submit" name="update_uniform">Update</button>
</form>
</td>

</tr>

<?php endforeach; ?>

</table>
</div>

</body>
</html>
