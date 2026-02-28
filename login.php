<?php
require_once __DIR__ . '/shared/config/db.php';
session_start();

$error = "";

/* ===== GET ACTIVE ANNOUNCEMENT ===== */
$today = date('Y-m-d');

/* ===== GET ACTIVE ANNOUNCEMENT ===== */
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
<title>PUCL Module Access System</title>

<style>
body{
    font-family: Arial;
    margin: 0;
    background: url('union.jpg') no-repeat center center fixed;
    background-size: cover;
}

/* MAIN LAYOUT */
.main-container{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    gap: 60px;
    flex-wrap: wrap;
}

/* ANNOUNCEMENT CARD */
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

/* LOGIN CARD */
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

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>

            <div class="forgot">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>
        </form>
    </div>

</div>

</body>
</html>

