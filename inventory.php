<?php
require_once "shared/config/db.php";

/* GET ALL UNIFORMS */
$stmt = $pdo->prepare("SELECT * FROM uniforms");
$stmt->execute();
$uniforms = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* CONVERT TO KEY ARRAY */
$uniformData = [];
foreach($uniforms as $u){
    $uniformData[$u['uniform_key']] = $u;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Inventory</title>

<style>
.available { color: green; font-weight: bold; }
.not-available { color: red; font-weight: bold; }
.admin-control { display: none; }
.claim-col { display: table-cell; }
table { width:100%; border-collapse: collapse; margin-bottom:20px; }
th, td { border:1px solid #ccc; padding:8px; text-align:center; }
</style>

<script src="script.js"></script>

</head>
<body>

<h1>Uniform Inventory System</h1>

<div class="section">
<h2>NSTP Uniform</h2>

<table>
<tr>
<th>Size</th>
<th>Stock</th>
<th>Status</th>
<th class="claim-col">Action</th>
<th class="admin-control">Admin</th>
</tr>

<?php
$sizes = ['xs','s','m','l','xl','xxl'];

foreach($sizes as $size):

$key = "nstp_" . $size;

$stock = $uniformData[$key]['stock'] ?? 0;
$status = $uniformData[$key]['status'] ?? "Not Available";
$class = ($status == "Available") ? "available" : "not-available";
?>

<tr>
<td><?= strtoupper($size) ?></td>

<td id="<?= $key ?>-stock"><?= $stock ?></td>

<td id="<?= $key ?>-status" class="<?= $class ?>">
<?= $status ?>
</td>

<td class="claim-col">
<button onclick="claim('<?= $key ?>',this)">Claim</button>
</td>

<td class="admin-control">
<input type="number" id="<?= $key ?>-stock-edit">
<select id="<?= $key ?>-status-edit">
<option <?= $status=='Available'?'selected':'' ?>>Available</option>
<option <?= $status=='Not Available'?'selected':'' ?>>Not Available</option>
</select>
<button onclick="updateUniform('<?= $key ?>')">Update</button>
</td>

</tr>

<?php endforeach; ?>

</table>
</div>

</body>
</html>