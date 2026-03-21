<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/shared/config/db.php';

/* ================= LOGIN REQUIRED ================= */
if (!isset($_SESSION['username'])) {
    header("Location: /"); // <--- clean URL login
    exit();
}

$isAdmin = $_SESSION['is_admin'] ?? 0;

/* ================= DASHBOARD DATA ================= */
try {
    $totalItems = $pdo->query("SELECT SUM(quantity) FROM sales")->fetchColumn();
} catch (Exception $e) {
    $totalItems = 0;
}

/* ================= GET ACTIVE ANNOUNCEMENT ================= */
$today = date('Y-m-d');

try {
    $stmt = $pdo->prepare("
        SELECT * FROM announcements
        WHERE start_date <= ? AND end_date >= ?
        ORDER BY id DESC
        LIMIT 1
    ");
    $stmt->execute([$today, $today]);
    $announcement = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $announcement = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title> PHINMA UCL Academic Uniform Management System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ================= TOPBAR ================= -->
<div class="topbar">
    <div class="topbar-left">  PHINMA UCL Academic Uniform Management System</div>
    <div class="topbar-right-links">
        <span>Welcome, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></span>
    </div>
</div>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
    <div class="sidebar-title">Admin Panel</div>

    <?php if($isAdmin == 1): ?>
        <a href="records.php"><button class="sidebar-btn">View Records</button></a>
        <a href="admin_announcement.php"><button class="sidebar-btn">Edit Announcement</button></a>
        <a href="uniforms_request_admin.php"><button class="sidebar-btn">request</button></a>
    <?php endif; ?>

    <a href="logout.php"><button class="sidebar-btn">Logout</button></a>
    <a href="uniforms_request.php"><button class="sidebar-btn">request</button></a>
</div>

<!-- ================= MAIN CONTENT ================= -->
<div class="main-content">

    <!-- ================= ANNOUNCEMENT ================= -->
    <div class="announcement-box">
        <h2>Announcement</h2>
        <?php if($announcement && isset($announcement['message'])): ?>
            <p><?= htmlspecialchars($announcement['message']); ?></p>
        <?php else: ?>
            <p>No active announcement.</p>
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

<!-- ================= SCRIPT ================= -->
<script src="script.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function(){

    function loadHTML(id, file){
        return fetch(file) // HINDI natin binago
            .then(res => {
                if(!res.ok) throw new Error(file + " not found");
                return res.text();
            })
            .then(data => {
                console.log("Loaded:", file); // debug
                document.getElementById(id).innerHTML += data;
            })
            .catch(error => console.error("Error loading:", file, error));
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
            document.querySelectorAll(".claim-btn").forEach(btn => btn.style.display = "none");
            document.querySelectorAll(".claim-col").forEach(col => col.style.display = "none");
            document.querySelectorAll(".admin-control").forEach(ctrl => ctrl.style.display = "table-cell");
        } else {
            document.querySelectorAll(".admin-control").forEach(ctrl => ctrl.style.display = "none");
        }

    });

});
</script>

</body>
</html>
