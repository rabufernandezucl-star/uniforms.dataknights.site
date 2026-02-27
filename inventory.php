<?php
require_once "shared/config/db.php";

$stmt = $pdo->prepare("SELECT * FROM uniforms");
$stmt->execute();
$uniforms = $stmt->fetchAll(PDO::FETCH_ASSOC);

$uniformData = [];
foreach($uniforms as $u){
    $uniformData[$u['uniform_key']] = $u;
}

/* WITH SIZES */
$sizedCategories = [

    "shs_tvl_red",
    "shs_skirt",
    "shs_blouse",
    "shs_pants",
    "shs_polo",

    "shs_abm_blue",
    "shs_stem_green",
    "shs_humss_black",

    "college_skirt",
    "college_blouse",
    "college_pants",
    "college_polo",

    "cass_shirt_yellow_cma",
    "cass_shirt_blue_coed",
    "cass_shirt_yellow_bsba",
    "cass_shirt_yellow_it",
    "cass_shirt_yellow_hm",
    "cass_ivory",

    "nstp_uniform",
    "rotc_tshirt",
    "res_ipsa",

    "pe_jogging_pants",
    "pe_uniform_tshirt",

    "karate_arnis"
];

/* NO SIZE */
$noSizeCategories = [
    "cap_black",
    "cap_green"
];

$sizes = ['xs','s','m','l','xl','xxl'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Uniform Inventory</title>

<style>
.available { color: green; font-weight: bold; }
.not-available { color: red; font-weight: bold; }
table { width:100%; border-collapse: collapse; margin-bottom:40px; }
th, td { border:1px solid #ccc; padding:8px; text-align:center; }
</style>

<script src="script.js"></script>

</head>
<body>

<h1>Uniform Inventory System</h1>

<!-- CAPS (NO SIZE) -->
<h2>CAPS</h2>
<table>
<tr>
<th>Item</th>
<th>Stock</th>
<th>Status</th>
<th>Claim</th>
<th>Admin</th>
</tr>

<?php foreach($noSizeCategories as $category):

$key = $category;
$stock = $uniformData[$key]['stock'] ?? 0;
$status = $uniformData[$key]['status'] ?? "Not Available";
$class = ($status == "Available") ? "available" : "not-available";
?>

<tr>
<td><?= strtoupper(str_replace("_"," ",$category)) ?></td>

<td id="<?= $key ?>-stock"><?= $stock ?></td>

<td id="<?= $key ?>-status" class="<?= $class ?>">
<?= $status ?>
</td>

<td>
<button onclick="claim('<?= $key ?>')">Claim</button>
</td>

<td>
<input type="number" id="<?= $key ?>-stock-edit" value="<?= $stock ?>">
<select id="<?= $key ?>-status-edit">
<option <?= $status=='Available'?'selected':'' ?>>Available</option>
<option <?= $status=='Not Available'?'selected':'' ?>>Not Available</option>
</select>
<button onclick="updateUniform('<?= $key ?>')">Update</button>
</td>

</tr>
<?php endforeach; ?>
</table>


<!-- SIZED UNIFORMS -->
<?php foreach($sizedCategories as $category): ?>
<h2><?= strtoupper(str_replace("_"," ",$category)) ?></h2>

<table>
<tr>
<th>Size</th>
<th>Stock</th>
<th>Status</th>
<th>Claim</th>
<th>Admin</th>
</tr>

<?php foreach($sizes as $size):

$key = $category . "_" . $size;
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

<td>
<button onclick="claim('<?= $key ?>')">Claim</button>
</td>

<td>
<input type="number" id="<?= $key ?>-stock-edit" value="<?= $stock ?>">
<select id="<?= $key ?>-status-edit">
<option <?= $status=='Available'?'selected':'' ?>>Available</option>
<option <?= $status=='Not Available'?'selected':'' ?>>Not Available</option>
</select>
<button onclick="updateUniform('<?= $key ?>')">Update</button>
</td>

</tr>

<?php endforeach; ?>
</table>
<?php endforeach; ?>

</body>
</html>
