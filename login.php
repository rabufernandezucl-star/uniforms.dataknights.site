<?php
require_once __DIR__ . '/shared/config/db.php';
session_start();

$error = "";

/* ===== GET ACTIVE ANNOUNCEMENT ===== */
$today = date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT message 
    FROM announcements
    WHERE CURDATE() BETWEEN start_date AND end_date
    ORDER BY id DESC
    LIMIT 1
");
$stmt->execute();
$announcement = $stmt->fetch(PDO::FETCH_ASSOC);

/* ===== LOGIN PROCESS ===== */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {

        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];

        header("Location: index.php");
        exit;

    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>PHINMA UCL Academic Uniform Management System</title>

<style>
/* ================= GLOBAL ================= */
body{
    font-family: Arial;
    margin: 0;
    padding-top: 60px; /* space for fixed topbar */
    background: url('union.jpg') no-repeat center center fixed;
    background-size: cover;
}

/* ================= TOPBAR ================= */
.topbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 60px;
    background: linear-gradient(to right, #2fb36f, #2a8ecb);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 40px;
    box-sizing: border-box;
    z-index: 9999;
}

.topbar-left {
    font-size: 20px;
    font-weight: bold;
}

.topbar-right-links {
    display: flex;
    gap: 20px;
}

.topbar-right-links a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}

.topbar-right-links a:hover {
    text-decoration: underline;
}

/* ================= MAIN LAYOUT ================= */
.main-container{
    display: flex;
    justify-content: center;
    align-items: center;
    height: calc(100vh - 60px); /* adjust for topbar height */
    gap: 60px;
    flex-wrap: wrap;
}

/* ================= ANNOUNCEMENT CARD ================= */
.announcement-card{
    width: 500px;
    padding: 40px;
    background: rgba(255,255,255,0.9);
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
}

.announcement-card h2{
    color: #2fb36f;
}

/* ================= LOGIN CARD ================= */
.login-card{
    width: 350px;
    padding: 30px;
    background: rgba(255,255,255,0.9);
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
    text-align: center;
}

input{
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    box-sizing: border-box;
}

button{
    width: 100%;
    padding: 10px;
    background: linear-gradient(to right, #2fb36f, #2a8ecb);
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 6px;
    font-weight: bold;
}

button:hover{
    opacity: 0.9;
}

.error{
    color: red;
    font-size: 14px;
}

/* FORGOT PASSWORD */
.forgot {
    margin-top: 10px;
    text-align: center;
}

.forgot a {
    font-size: 14px;
    color: #2a8ecb;
    text-decoration: none;
}

.forgot a:hover {
    text-decoration: underline;
}
</style>

</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-left">
      PHINMA UCL Academic Uniform Management System
    </div>

    <div class="topbar-right-links">
        <a href="https://dataknights.site/">Home</a>
        <a href="https://feedback.dataknights.site/">Feedback</a>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-container">

    <!-- ANNOUNCEMENT -->
    <div class="announcement-card">
        <h2>Announcements</h2>

        <?php if($announcement): ?>
            <p><?= htmlspecialchars($announcement['message']) ?></p>
        <?php else: ?>
            <p>No active schedules as of the moment.</p>
        <?php endif; ?>
    </div>

    <!-- LOGIN -->
    <div class="login-card">
        <h2>Login</h2>

        <?php if($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

     <form method="POST" action="/">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

            <div class="forgot">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>
        </form>
    </div>

</div>

</body>
</html>
