<?php
require_once 'shared/config/db.php';

$message = '';
$submitted = false;

/* ================= FORM SUBMIT ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $identifier = $_POST['identifier'] ?? '';

    if (!empty($identifier)) {

        /* ================= FIND USER ================= */
        $stmt = $pdo->prepare("
            SELECT username
            FROM users
            WHERE username = ?
            LIMIT 1
        ");
        $stmt->execute([$identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        /* Always show success (security) */
        $submitted = true;

        if ($user) {

            /* ================= GENERATE TOKEN ================= */
            $token = bin2hex(random_bytes(32));
            $expires = date(
                'Y-m-d H:i:s',
                strtotime('+1 hour')
            );

            /* ================= SAVE TOKEN ================= */
            $stmt = $pdo->prepare("
                INSERT INTO password_resets
                (username, token, expires_at)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([
                $user['username'],
                $token,
                $expires
            ]);

            /* ================= RESET LINK ================= */
            $link = "http://localhost/uniform/set-password.php?token=$token";

            $message = "
                <div style='padding:10px;background:#e7f3ff;margin-top:10px;border-radius:5px;'>
                    <b>Reset Link:</b><br>
                    <a href='$link'>$link</a>
                </div>
            ";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>

<style>
body{
    font-family: Arial;
    background:#f4f6f8;
}
.box{
    width:400px;
    margin:80px auto;
    padding:20px;
    background:#fff;
    border-radius:6px;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}
input{
    width:100%;
    padding:10px;
    margin:8px 0;
}
button{
    width:100%;
    padding:10px;
    background:#0066cc;
    color:#fff;
    border:none;
}
</style>
</head>

<body>

<div class="box">

<h2>Forgot Password</h2>

<?php if ($submitted): ?>

    <p>
        If the account exists, a reset link has been generated.
    </p>

    <?php echo $message; ?>

    <p>
        <a href="login.php">Return to Login</a>
    </p>

<?php else: ?>

<form method="POST">

    <input
        type="text"
        name="identifier"
        placeholder="Enter Username"
        required
    >

    <button type="submit">
        Generate Reset Link
    </button>

</form>

<?php endif; ?>

</div>

</body>
</html>
