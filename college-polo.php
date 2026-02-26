<?php
require_once 'shared/config/db.php';

$keys = [
    'polo_xs',
    'polo_s',
    'polo_m',
    'polo_l',
    'polo_xl',
    'polo_xxl',
    'polo_xxxl'
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

<h2>College Polo</h2>

<table>

<tr>
<th>Size</th>
<th>Stock</th>
<th>Status</th>
<th class="claim-col">Action</th>
<th class="admin-control">Admin</th>
</tr>

<?php
$sizes = ['XS','S','M','L','XL','XXL','XXXL'];

foreach ($sizes as $size):

$key = 'polo-' . strtolower($size);
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

<button onclick="updateUniform('<?= $key ?>')">
Update
</button>
</td>

</tr>

<?php endforeach; ?>

</table>
</div>

