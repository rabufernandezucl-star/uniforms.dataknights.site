<?php
require_once __DIR__ . '/shared/config/db.php';
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE username = ?
    ");
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
    <title>PHINMA UCL Learning Module System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- TOP BAR -->
<div class="top-bar">
    <div>PHINMA UCL Learning Module System</div>

    <div class="top-links">
        <a href="#">Home</a>
        <a href="#">Feedback</a>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-container">

    <!-- LEFT ANNOUNCEMENT -->
    <div class="announcement-card">
        <h2>Announcements</h2>
        <p>No active schedules as of the moment.</p>
    </div>

    <!-- RIGHT LOGIN -->
    <div class="login-card">

        <h2>PUCL Module Access System</h2>

        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
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

<!-- FOOTER -->
<div class="footer">
    © <?php echo date("Y"); ?> PHINMA UCL Learning Module System
</div>

</body>
</html>
