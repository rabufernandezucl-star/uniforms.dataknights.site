<?php
session_start();

/* ✅ LOGIN REQUIRED */
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
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
function loadHTML(id, file){
    return fetch(file)
    .then(res => res.text())
    .then(data => {
        document.getElementById(id).innerHTML += data;
    });
}

/* LOAD FILES */
Promise.all([

    loadHTML("sections","college-polo.html"),
    loadHTML("sections","humss.html"),
    loadHTML("sections","Cass shirt-ivory.html"),
    loadHTML("sections","peshirts.html"),
    loadHTML("sections","karate.html"),
    loadHTML("sections","cap black.html"),
    loadHTML("sections","cap green.html"),
    loadHTML("sections","pe joggingpants.html"),
    loadHTML("sections","ispa.html"),
    loadHTML("sections","nstp.html"),
    loadHTML("sections","stem.html"),
    loadHTML("sections","cass hm.html"),
    loadHTML("sections","cass yellow-shirt.html"),
    loadHTML("sections","cass shirt-yellow.html"),
    loadHTML("sections","cass blue-shirt.html"),
    loadHTML("sections","cass-shirts.html"),
    loadHTML("sections","abm blue.html"),
    loadHTML("sections","college pants.html"),
    loadHTML("sections","college blouse.html"),
    loadHTML("sections","college skirt.html"),
    loadHTML("sections","shs polo.html"),
    loadHTML("sections","shs pants.html"),
    loadHTML("sections","shs blouse.html"),
    loadHTML("sections","shs skirt.html"),
    loadHTML("sections","tvl-red.html")

]).then(()=>{

    /* ================= ADMIN CHECK ================= */
    const isAdmin = <?php echo ($_SESSION['is_admin'] ?? 0); ?>;

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
</script>

</body>
</html>

