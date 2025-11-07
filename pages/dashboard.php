<?php
session_start();
// echo var_dump($_SESSION);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task</title>
</head>
<body>
    <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <a href="login.php">Sair</a>
</body>
</html>