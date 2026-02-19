<?php
require_once 'shared/config/db.php';

session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    /* ===== GET USER ===== */
    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE username = ?
    ");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    /* ===== VERIFY ===== */
    if ($user && password_verify(
            $password,
            $user['password_hash']
        )) {

        /* SAVE SESSION */
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
    <title>Login</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body{
            font-family: Arial;
            background: url('union.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .login-box{
            width: 340px;
            margin: 120px auto;
            padding: 25px;
            background: rgba(255,255,255,0.85);
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

        .forgot{
            margin-top: 12px;
        }

        .forgot a{
            color: #0b4da2;
            font-size: 13px;
            text-decoration: none;
            font-weight: bold;
        }

        .forgot a:hover{
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="login-box">

    <h2>Login</h2>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>

        <p class="forgot">
            <a href="forgot-password.php">Forgot Password?</a>
        </p>
    </form>

</div>

</body>
</html>
