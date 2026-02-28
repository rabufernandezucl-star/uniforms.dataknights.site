<?php
require_once __DIR__ . '/shared/config/db.php';
session_start();

$error = "";

/* ===== GET ACTIVE ANNOUNCEMENT ===== */
$today = date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT message 
    FROM announcements
    WHERE start_date <= ? AND end_date >= ?
    ORDER BY id DESC
    LIMIT 1
");
$stmt->execute([$today, $today]);
$announcement = $stmt->fetch(PDO::FETCH_ASSOC);


/* ===== LOGIN PROCESS ===== */
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
    <title>PUCL Module Access System</title>
    <link rel="stylesheet" href="style.css">
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
    <div class="login-box">

        <h2>Login</h2>

        <?php if($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>

            <p class="forgot">
                <a href="forgot-password.php">Forgot Password?</a>
            </p>
        </form>

    </div>

</div>

</body>
</html>
