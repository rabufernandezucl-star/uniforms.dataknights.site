<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

/* ================= LOGIN REQUIRED ================= */
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = $_SESSION['is_admin'] ?? 0;

/* ================= DASHBOARD DATA ================= */

$totalRevenue = $pdo->query("
SELECT SUM(total) FROM sales
")->fetchColumn();

$totalItems = $pdo->query("
SELECT SUM(quantity) FROM sales
")->fetchColumn();

$todayRevenue = $pdo->query("
SELECT SUM(total)
FROM sales
WHERE sale_date = CURDATE()
")->fetchColumn();

/* ================= GET ACTIVE ANNOUNCEMENT ================= */

$today = date('Y-m-d');

$stmt = $pdo->prepare("
SELECT * FROM announcements
WHERE start_date <= ? AND end_date >= ?
ORDER BY id DESC
LIMIT 1
");

$stmt->execute([$today, $today]);
$announcement = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
<title>PHINMA UCL Learning Module System</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<!-- ================= TOPBAR ================= -->

<div class="topbar">

    <div class="topbar-left">
        PHINMA UCL
    </div>

    <div class="topbar-right-links">

        <span>
            Welcome,
            <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>
        </span>

    </div>

</div>

<!-- ================= MAIN CONTENT ================= -->

<div class="main-content">

<!-- ================= ANNOUNCEMENT ================= -->

<div class="announcement-box">

<h2>Announcement</h2>

<?php if($announcement): ?>

<p>
<?= htmlspecialchars($announcement['message']); ?>
</p>

<?php else: ?>

<p>No active announcement.</p>

<?php endif; ?>

<?php if($isAdmin == 1): ?>

<a href="admin_announcement.php">
<button class="records-btn">
Edit Announcement
</button>
</a>

<?php endif; ?>

</div>

<!-- ================= BUTTON GROUP ================= -->

<div class="button-group">

<a href="logout.php">
<button class="records-btn">
Logout
</button>
</a>

<?php if($isAdmin == 1): ?>

<a href="records.php">
<button class="records-btn">
View Records
</button>
</a>

<a href="record_sale.php">
<button class="records-btn">
Revenue
</button>
</a>

<?php endif; ?>

</div>

<!-- ================= INVENTORY ================= -->

<div id="inventory">

<div id="sections"></div>

</div>

</div>

<!-- ================= THANK YOU ================= -->

<div id="thankYou" style="display:none;">

<h2>Thank you for claiming!</h2>

</div>

<script src="script.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function(){

function loadHTML(id, file){

return fetch(file)

.then(res => {

if(!res.ok){

throw new Error(file + " not found");

}

return res.text();

})

.then(data => {

document.getElementById(id).innerHTML += data;

})

.catch(error => {

console.error("Error loading:", error);

});

}

Promise.all([

loadHTML("sections","college-polo.php"),
loadHTML("sections","cass ivory.php"),
loadHTML("sections","peshirts.php"),
loadHTML("sections","karate.php"),
loadHTML("sections","cap black.php"),
loadHTML("sections","cap green.php"),
loadHTML("sections","pe joggingpants.php"),
loadHTML("sections","ispa.php"),
loadHTML("sections","nstp.php"),
loadHTML("sections","cass yellow-shirt.php"),
loadHTML("sections","cass shirt-yellow.php"),
loadHTML("sections","cass blue-shirt.php"),
loadHTML("sections","cass-shirts.php"),
loadHTML("sections","college pants.php"),
loadHTML("sections","college blouse.php"),
loadHTML("sections","college skirt.php"),
loadHTML("sections","rotc.php"),
loadHTML("sections","bsa.php")

]).then(() => {

const isAdmin = <?php echo $isAdmin; ?>;

if(isAdmin == 1){

document.querySelectorAll(".claim-btn").forEach(btn=>{
btn.style.display = "none";
});

document.querySelectorAll(".claim-col").forEach(col=>{
col.style.display = "none";
});

document.querySelectorAll(".admin-control").forEach(ctrl=>{
ctrl.style.display = "table-cell";
});

}else{

document.querySelectorAll(".admin-control").forEach(ctrl=>{
ctrl.style.display = "none";
});

}

});

});

</script>

</body>
</html>
