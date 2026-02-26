<?php
require_once 'shared/config/db.php';

/* ===== FETCH TVL DATA ===== */
$stmt = $pdo->prepare("SELECT uniform_key, stock, status FROM uniforms WHERE uniform_key LIKE 'tvl-%'");
$stmt->execute();
$uniforms = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];
foreach ($uniforms as $row) {
    $data[$row['uniform_key']] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>SHS TVL Uniform</title>

<style>
body {
    font-family: Arial, sans-serif;
}

.section {
    margin: 40px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
}

.available {
    color: green;
    font-weight: bold;
}

.not-available {
    color: red;
    font-weight: bold;
}

button {
    padding: 5px 10px;
    cursor: pointer;
}

.admin-control input {
    width: 60px;
}
</style>

</head>
<body>

<div class="section">

<h2>SHS Uniform – TVL (Red)</h2>

<table>

<tr>
<th>Size</th>
<th>Stock</th>
<th>Status</th>
<th>Action</th>
<th>Admin</th>
</tr>

<?php
$sizes = ['xs','s','m','l','xl','xxl','xxxl'];

foreach($sizes as $size):

$key = "tvl-" . $size;

$stock = $data[$key]['stock'] ?? 0;
$status = $data[$key]['status'] ?? 'Not Available';

$statusClass = ($status === 'Available') ? 'available' : 'not-available';
?>

<tr>

<td><?= strtoupper($size) ?></td>

<td id="<?= $key ?>-stock">
<?= htmlspecialchars($stock) ?>
</td>

<td id="<?= $key ?>-status" class="<?= $statusClass ?>">
<?= htmlspecialchars($status) ?>
</td>

<td>
<button onclick="claim('<?= $key ?>', this)">
Claim
</button>
</td>

<td class="admin-control">

<input type="number"
       id="<?= $key ?>-stock-edit"
       value="<?= htmlspecialchars($stock) ?>">

<select id="<?= $key ?>-status-edit">

<option value="Available"
<?= ($status === 'Available') ? 'selected' : '' ?>>
Available
</option>

<option value="Not Available"
<?= ($status === 'Not Available') ? 'selected' : '' ?>>
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


<script>

/* ===== CLAIM FUNCTION ===== */
function claim(key, btn) {

    let stockCell = document.getElementById(key + "-stock");
    let statusCell = document.getElementById(key + "-status");

    let stock = parseInt(stockCell.innerText);

    if (stock <= 0) {
        alert("Out of stock!");
        return;
    }

    stock--;
    stockCell.innerText = stock;

    if (stock === 0) {
        statusCell.innerText = "Not Available";
        statusCell.className = "not-available";
    }
}


/* ===== ADMIN UPDATE FUNCTION ===== */
function updateUniform(key) {

    let newStock = document.getElementById(key + "-stock-edit").value;
    let newStatus = document.getElementById(key + "-status-edit").value;

    fetch("update_uniform.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "uniform_key=" + key +
              "&stock=" + newStock +
              "&status=" + newStatus
    })
    .then(res => res.text())
    .then(data => {
        location.reload();
    });

}

</script>

</body>
</html>
