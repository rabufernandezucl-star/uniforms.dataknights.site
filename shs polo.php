<?php
require_once 'shared/config/db.php';

$keys = [
    'shs_polo_xs',
    'shs_polo_s',
    'shs_polo_m',
    'shs_polo_l',
    'shs_polo_xl',
    'shs_polo_xxl',
    'shs_polo_xxxl',
    'shs_polo_xxxxl',
    'shs_polo_xxxxxl'
];

$uniformData = [];

$stmt = $pdo->prepare("SELECT uniform_key, stock, status FROM uniforms WHERE uniform_key = ?");

foreach ($keys as $key) {
    $stmt->execute([$key]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $uniformData[$key] = $result;
    } else {
        $uniformData[$key] = [
            'stock' => 0,
            'status' => 'Not Available'
        ];
    }
}
?>

<div class="section">

<h2>SHS Polo</h2>

<table>

<tr>
<th>Size</th>
<th>Stock</th>
<th>Status</th>
<th class="claim-col">Action</th>
<th class="admin-control">Admin</th>
</tr>

<?php
$sizes = ['XS','S','M','L','XL','XXL','XXXL','XXXXL','XXXXXL'];

foreach ($sizes as $size):

$key = 'shs_polo_' . strtolower($size);
$stock = $uniformData[$key]['stock'];
$status = $uniformData[$key]['status'];
$statusClass = ($status == 'Available') ? 'available' : 'not-available';
?>

<tr>
<td><?= $size ?></td>

<td id="<?= $key ?>-stock"><?= $stock ?></td>

<td id="<?= $key ?>-status" class="<?= $statusClass ?>">
<?= $status ?>
</td>

<td class="claim-col">
<button onclick="claim('<?= $key ?>',this)">Claim</button>
</td>

<td class="admin-control">
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
</div>
