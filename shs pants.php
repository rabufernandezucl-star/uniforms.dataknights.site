<?php
require_once 'shared/config/db.php';

$keys = [
    'shs_pants_xs',
    'shs_pants_s',
    'shs_pants_m',
    'shs_pants_l',
    'shs_pants_xl',
    'shs_pants_xxl'
];

$pantsData = [];

$stmt = $pdo->prepare("SELECT uniform_key, stock, status FROM uniforms WHERE uniform_key = ?");

foreach ($keys as $key) {
    $stmt->execute([$key]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $pantsData[$key] = $result;
    } else {
        $pantsData[$key] = [
            'stock' => 0,
            'status' => 'Not Available'
        ];
    }
}
?>

<div class="section">

<h2>SHS Pants</h2>

<table>

<tr>
<th>Size</th>
<th>Stock</th>
<th>Status</th>
<th class="claim-col">Action</th>
<th class="admin-control">Admin</th>
</tr>

<?php
$sizes = ['XS','S','M','L','XL','XXL'];

foreach ($sizes as $size):

$key = 'shs_pants_' . strtolower($size);
$stock = $pantsData[$key]['stock'];
$status = $pantsData[$key]['status'];
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
<option value="Available" <?= $status=='Available'?'selected':'' ?>>
Available
</option>
<option value="Not Available" <?= $status=='Not Available'?'selected':'' ?>>
Not Available
</option>
</select>

<button onclick="updateUniform('<?= $key ?>')">Update</button>
</td>

</tr>

<?php endforeach; ?>

</table>
</div>
