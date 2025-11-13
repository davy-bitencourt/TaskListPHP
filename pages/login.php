<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../src/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $senha = $_POST['senha'];

    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        $erro = "Usuário ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="body2">
    <div class="container">
        <h2>Fazer Login</h2>
        <form method="POST">
            <label>Usuário</label>
            <input type="text" name="username" required>
            <label>Senha</label>
            <input type="password" name="senha" required>
            <button class="button" type="submit">Entrar</button>
            <a href="cadastro.php">Criar uma conta</a>
        </form>
        
        <?php if (isset($erro)): ?>
            <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
            <?php endif; ?> 
    </div>
</body>
</html>