<?php
require_once 'shared/config/db.php';

$keys = [
    'college_cass_coed_xs',
    'college_cass_coed_s',
    'college_cass_coed_m',
    'college_cass_coed_l',
    'college_cass_coed_xl',
    'college_cass_coed_xxl',
    'college_cass_coed_xxxl',
    'college_cass_coed_xxxxl'
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

<h2>CASS Shirt Blue (COED)</h2>

<table>

<tr>
<th>Size</th>
<th>Stock</th>
<th>Status</th>
<th class="claim-col">Action</th>
<th class="admin-control">Admin</th>
</tr>

<?php
$sizes = ['XS','S','M','L','XL','XXL','XXXL','XXXXL'];

foreach ($sizes as $size):

$key = 'college_cass_coed_' . strtolower($size);
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
