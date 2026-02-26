<?php
require_once 'shared/config/db.php';

$keys = ['cap_green'];

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

$stock = $uniformData['cap_green']['stock'];
$status = $uniformData['cap_green']['status'];
$statusClass = ($status == 'Available') ? 'available' : 'not-available';
?>

<div class="section">

<h2>Cap – Green</h2>

<table>

<tr>
<th>Item</th>
<th>Stock</th>
<th>Status</th>
<th class="claim-col">Action</th>
<th class="admin-control">Admin</th>
</tr>

<tr>

<td>Cap – Green</td>

<td id="cap_green-stock">
<?= htmlspecialchars($stock) ?>
</td>

<td id="cap_green-status" class="<?= $statusClass ?>">
<?= htmlspecialchars($status) ?>
</td>

<td class="claim-col">
<button onclick="claim('cap_green', this)">
Claim
</button>
</td>

<td class="admin-control">

<input type="number"
       id="cap_green-stock-edit"
       value="<?= htmlspecialchars($stock) ?>"
       min="0">

<select id="cap_green-status-edit">
<option value="Available" <?= $status=='Available'?'selected':'' ?>>
Available
</option>
<option value="Not Available" <?= $status=='Not Available'?'selected':'' ?>>
Not Available
</option>
</select>

<button onclick="updateUniform('cap_green')">
Update
</button>

</td>

</tr>

</table>
</div>
