<?php
require_once 'shared/config/db.php';

$keys = [
    'college_pants_xs',
    'college_pants_s',
    'college_pants_m',
    'college_pants_l',
    'college_pants_xl',
    'college_pants_xxl',
    'college_pants_xxxl',
    'college_pants_xxxxl',
    'college_pants_xxxxxl'
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

<h2>College Pants</h2>

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

$key = 'college_pants_' . strtolower($size);
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
<button onclick="claim('<?= $key ?>', this)">Claim</button>
</td>

<td class="admin-control">

<input type="number"
       id="<?= $key ?>-stock-edit"
       value="<?= htmlspecialchars($stock) ?>">

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
