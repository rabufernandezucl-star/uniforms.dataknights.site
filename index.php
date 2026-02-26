<?php
session_start();

/* ================= LOGIN REQUIRED ================= */
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = $_SESSION['is_admin'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Uniform Inventory</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h1>Uniform Inventory System</h1>

<div id="inventory">

    <p>
        Welcome,
        <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>
    </p>

    <a href="logout.php">
        <button>Logout</button>
    </a>

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

    /* ================= LOAD ALL FILES ================= */
    Promise.all([

        loadHTML("sections","college-polo.php"),
        loadHTML("sections","humss.php"),
        loadHTML("sections","cass ivory.php"),
        loadHTML("sections","peshirts.php"),
        loadHTML("sections","karate.php"),
        loadHTML("sections","cap black.php"),
        loadHTML("sections","cap green.php"),
        loadHTML("sections","pe joggingpants.php"),
        loadHTML("sections","ispa.php"),
        loadHTML("sections","nstp.php"),
        loadHTML("sections","stem.php"),
        loadHTML("sections","cass hm.php"),
        loadHTML("sections","cass yellow-shirt.php"),
        loadHTML("sections","cass shirt-yellow.php"),
        loadHTML("sections","cass blue-shirt.php"),
        loadHTML("sections","cass shirts.php"),
        loadHTML("sections","abm blue.php"),
        loadHTML("sections","college pants.php"),
        loadHTML("sections","college blouse.php"),
        loadHTML("sections","college skirt.php"),
        loadHTML("sections","shs polo.php"),
        loadHTML("sections","shs pants.php"),
        loadHTML("sections","shs blouse.php"),
        loadHTML("sections","shs skirt.php"),
        loadHTML("sections","tvl-red.php")

    ]).then(() => {

        /* ================= ADMIN CHECK ================= */
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



