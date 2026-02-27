<?php
require_once 'shared/config/db.php';

$keys = [
    'shs_abm_xs',
    'shs_abm_s',
    'shs_abm_m',
    'shs_abm_l',
    'shs_abm_xl',
    'shs_abm_xxl'
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

<h2>SHS Uniform – ABM (Blue)</h2>

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

$key = 'shs_abm_' . strtolower($size);
$stock = $uniformData[$key]['stock'];
$status = $uniformData[$key]['status'];
$statusClass = ($status == 'Available') ? 'available' : 'not-available';
?>

<tr>

<td><?= $size ?></td>

<td id="<?= $key ?>-stock">
<?= htmlspecialchars($stock) ?>
</td>

<td id="<?= $key ?>-status" class="<?= $statusClass ?>">
<?= htmlspecialchars($status) ?>
</td>

<td class="claim-col">
<button onclick="claim('<?= $key ?>', this)">
Claim
</button>
</td>

<td class="admin-control">

<input type="number"
       id="<?= $key ?>-stock-edit"
       value="<?= htmlspecialchars($stock) ?>"
       min="0">

<select id="<?= $key ?>-status-edit">
<option value="Available" <?= $status=='Available'?'selected':'' ?>>
Available
</option>
<option value="Not Available" <?= $status=='Not Available'?'selected':'' ?>>
Not Available
</option>
</select>

<button onclick="updateUniform('<?= $key ?>')">
Update
</button>

</td>

</tr>

<?php endforeach; ?>

</table>
</div>
