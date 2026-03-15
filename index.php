<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = $_SESSION['is_admin'] ?? 0;

$today = date('Y-m-d');

$stmt = $pdo->prepare("
SELECT * FROM announcements
WHERE start_date <= ? AND end_date >= ?
ORDER BY id DESC
LIMIT 1
");
$stmt->execute([$today,$today]);
$announcement = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>PHINMA UCL Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <h2 class="logo">PHINMA UCL</h2>

    <a href="#">Dashboard Overview</a>

    <?php if($isAdmin == 1): ?>
        <a class="green-btn" href="admin_announcement.php">+ Add Announcement</a>
        <a class="green-btn" href="#">Monthly Stats</a>
        <a class="green-btn" href="#">Module Requests</a>
    <?php endif; ?>

</div>


<!-- MAIN CONTENT -->
<div class="main-content">

    <!-- TOP BAR -->
    <div class="topbar">

        <div>
            Welcome,
            <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>
        </div>

        <div>

            <?php if($isAdmin == 1): ?>
            <a href="records.php">
                <button class="green-button">View Records</button>
            </a>
            <?php endif; ?>

            <?php if($isAdmin == 1): ?>
            <a href="record_sale.php">
                <button class="green-button">Revenue</button>
            </a>
            <?php endif; ?>

            <a href="logout.php">
                <button class="green-button">Logout</button>
            </a>

        </div>

    </div>


    <!-- ANNOUNCEMENT -->
    <div class="announcement-box">

        <h3>Announcement</h3>

        <?php if($announcement): ?>
            <p><?= htmlspecialchars($announcement['message']); ?></p>
        <?php else: ?>
            <p>No active announcement.</p>
        <?php endif; ?>

        <?php if($isAdmin == 1): ?>
        <a href="admin_announcement.php">
            <button class="green-button">Edit Announcement</button>
        </a>
        <?php endif; ?>

    </div>


    <!-- PRODUCTS / UNIFORMS -->
    <div id="sections"></div>

</div>


<script>

document.addEventListener("DOMContentLoaded", function(){

    function loadHTML(id,file){

        return fetch(file)
        .then(res => res.text())
        .then(data => {
            document.getElementById(id).innerHTML += data;
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

        } else {

            document.querySelectorAll(".admin-control").forEach(ctrl=>{
                ctrl.style.display = "none";
            });

        }

    });

});
</script>

</body>
</html>
