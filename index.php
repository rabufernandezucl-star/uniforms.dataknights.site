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

<h1>PHINMA UCL Learning Module System</h1>

<!-- ================= ANNOUNCEMENT SECTION ================= -->
<div class="announcement-box">

    <h2></h2>

    <?php if($announcement): ?>
        <p><?= htmlspecialchars($announcement['message']); ?></p>
    <?php else: ?>
        <p>No active announcement.</p>
    <?php endif; ?>

    <?php if($isAdmin == 1): ?>
        <a href="admin_announcement.php">
            <button class="edit-btn">Edit Announcement</button>
        </a>
    <?php endif; ?>

</div>

<div id="inventory">

    <p>
        Welcome,
        <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>
    </p>
    
    <!-- ================= DASHBOARD ================= -->
<div class="dashboard">

    <div class="card">
        <h3>Total Revenue</h3>
        <h1>₱<?php echo number_format($totalRevenue ?? 0,2); ?></h1>
    </div>

    <div class="card">
        <h3>Items Sold</h3>
        <h1><?php echo $totalItems ?? 0; ?></h1>
    </div>

    <div class="card">
        <h3>Today's Sales</h3>
        <h1>₱<?php echo number_format($todayRevenue ?? 0,2); ?></h1>
    </div>

</div>
    
    <!-- ================= BUTTONS ================= -->

    <a href="logout.php">
        <button style="style.css">
            Logout
        </button>
    </a>

    <?php if($isAdmin == 1): ?>
    <!-- ===== RECORDS BUTTON (ADMIN ONLY) ===== -->
    <a href="./records.php">
        <button style="style.css">
            View Records
        </button>
    </a>
<?php endif; ?>

    <?php if($isAdmin == 1): ?>
        <span style="margin-left:10px;font-weight:bold;">
        </span>
    <?php endif; ?>

    <div id="sections"></div>

</div>

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
