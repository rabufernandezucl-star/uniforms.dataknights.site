<?php
require_once 'shared/config/db.php';

$keys = ['cap_black'];

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

$stock = $uniformData['cap_black']['stock'];
$status = $uniformData['cap_black']['status'];
$statusClass = ($status == 'Available') ? 'available' : 'not-available';
?>

<div class="section">

<h2>Cap – Black</h2>

<table>

<tr>
<th>Item</th>
<th>Stock</th>
<th>Status</th>
<th class="claim-col">Action</th>
<th class="admin-control">Admin</th>
</tr>

<tr>

<td>Cap – Black</td>

<td id="cap_black-stock">
<?= htmlspecialchars($stock) ?>
</td>

<td id="cap_black-status" class="<?= $statusClass ?>">
<?= htmlspecialchars($status) ?>
</td>

<td class="claim-col">
<button onclick="claim('cap_black', this)">
Claim
</button>
</td>

<td class="admin-control">

<input type="number"
       id="cap_black-stock-edit"
       value="<?= htmlspecialchars($stock) ?>"
       min="0">

<select id="cap_black-status-edit">
<option value="Available" <?= $status=='Available'?'selected':'' ?>>
Available
</option>
<option value="Not Available" <?= $status=='Not Available'?'selected':'' ?>>
Not Available
</option>
</select>

<button onclick="updateUniform('cap_black')">
Update
</button>

</td>

</tr>

</table>
</div>
