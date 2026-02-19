<?php
require_once 'shared/config/db.php';

$error = '';
$success = '';

/* ================= GET TOKEN ================= */
$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Invalid request.");
}

/* ================= CHECK TOKEN ================= */
$stmt = $pdo->prepare("
    SELECT *
    FROM password_resets
    WHERE token = ?
");
$stmt->execute([$token]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reset) {
    die("This password link is invalid.");
}

/* ================= CHECK EXPIRATION ================= */
if (strtotime($reset['expires_at']) < time()) {
    die("This password link has expired.");
}

/* ================= FORM SUBMIT ================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($password !== $confirm) {

        $error = "Passwords do not match.";

    } else {

        $password_hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        /* UPDATE PASSWORD */
        $stmt = $pdo->prepare("
            UPDATE users
            SET password_hash = ?
            WHERE username = ?
        ");
        $stmt->execute([
            $password_hash,
            $reset['username']
        ]);

        /* DELETE TOKEN */
        $stmt = $pdo->prepare("
            DELETE FROM password_resets
            WHERE token = ?
        ");
        $stmt->execute([$token]);

        $success = "Password successfully changed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Set Password</title>

<link rel="stylesheet" href="style.css">

<style>
body{
    font-family: Arial;
    background: url('union.jpg') no-repeat center center fixed;
    background-size: cover;
}

.box{
    width: 380px;
    margin: 120px auto;
    padding: 25px;
    background: rgba(255,255,255,0.9);
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
    text-align: center;
}

input{
    width: 100%;
    padding: 10px;
    margin: 8px 0;
}

button{
    width: 100%;
    padding: 10px;
    background: #0b4da2;
    color: white;
    border: none;
    cursor: pointer;
}

.error{
    color: red;
    margin-bottom: 10px;
}

.success{
    color: green;
    margin-bottom: 10px;
}
</style>

</head>

<body>

<div class="box">

<h2>Set New Password</h2>

<?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>

<?php elseif ($success): ?>
    <div class="success">
        <?php echo $success; ?><br><br>
        <a href="login.php">Return to Login</a>
    </div>

<?php else: ?>

<form method="post">

    <input type="password"
           name="password"
           placeholder="New Password"
           required>

    <input type="password"
           name="confirm_password"
           placeholder="Confirm Password"
           required>

    <button type="submit">
        Set Password
    </button>

</form>

<?php endif; ?>

</div>

</body>
</html>
