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
$sizes = ['xs','s','m','l','xl','xxl'];

foreach ($sizes as $size):

$key = "shspants-$size";
$stock = $pantsData[$key]['stock'] ?? 0;
$status = $pantsData[$key]['status'] ?? 'Not Available';
?>

<tr>
<td><?= strtoupper($size) ?></td>

<td id="<?= $key ?>-stock"><?= $stock ?></td>

<td id="<?= $key ?>-status" 
    class="<?= $status === 'Available' ? 'available' : 'not-available' ?>">
    <?= $status ?>
</td>

<td class="claim-col">
<button onclick="claim('<?= $key ?>',this)">Claim</button>
</td>

<td class="admin-control">
<input type="number" id="<?= $key ?>-stock-edit" value="<?= $stock ?>">

<select id="<?= $key ?>-status-edit">
<option value="Available" <?= $status === 'Available' ? 'selected' : '' ?>>
Available
</option>

<option value="Not Available" <?= $status === 'Not Available' ? 'selected' : '' ?>>
Not Available
</option>
</select>

<button onclick="updateUniform('<?= $key ?>')">Update</button>
</td>

</tr>

<?php endforeach; ?>

</table>
</div>
