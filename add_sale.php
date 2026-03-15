<?php
session_start();
require_once __DIR__ . '/shared/config/db.php';

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $name = $_POST['uniform_name'];
    $qty = $_POST['quantity'];
    $price = $_POST['price'];

    $total = $qty * $price;

    $stmt = $pdo->prepare("INSERT INTO sales(uniform_name, quantity, price, total, sale_date) VALUES(?,?,?,?,NOW())");
    $stmt->execute([$name, $qty, $price, $total]);

    header("Location: record_sale.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Sale</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

form {
    background: #ffffff;
    padding: 30px 40px;
    border-radius: 10px;
    box-shadow: 0px 8px 20px rgba(0,0,0,0.1);
    width: 350px;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-top: 10px;
    color: #555;
    font-weight: bold;
}

input[type="text"], input[type="number"] {
    width: 100%;
    padding: 10px 12px;
    margin-top: 5px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
    box-sizing: border-box;
    transition: border 0.3s;
}

input[type="text"]:focus, input[type="number"]:focus {
    border-color: #007BFF;
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
}

button:hover {
    background-color: #0056b3;
}
</style>
</head>
<body>

<form method="POST">
    <h2>Add Sale</h2>

    <label for="uniform_name">Uniform Name</label>
    <input type="text" id="uniform_name" name="uniform_name" required>

    <label for="quantity">Quantity</label>
    <input type="number" id="quantity" name="quantity" required min="1">

    <label for="price">Price</label>
    <input type="number" id="price" name="price" required min="1" step="0.01">

    <button type="submit">Save</button>
</form>

</body>
</html>
