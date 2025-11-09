<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $senha = $_POST['senha'];

    if (empty($username) || empty($senha)) {
        $erro = "Preencha todos os campos.";

    } else {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id FROM user WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $erro = "O nome de usuário já está em uso.";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $db->prepare("INSERT INTO user (username, senha) VALUES (?, ?)");
            if ($stmt->execute([$username, $hash])) {
                header('Location: login.php');
                exit;
            } else {
                $erro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="body2">
    
    <div class="container">
        <h2>Criar Conta</h2>
        <form method="POST">
            <label>Usuário</label>
            <input type="text" name="username" required>
            <label>Senha</label>
            <input type="password" name="senha" required>
            <button class="button" type="submit">Cadastrar</button>
            <a href="login.php">Já tenho uma conta</a>
        </form>
        
        <?php if (isset($erro)): ?>
            <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>
    </div>        
            

</body>
</html>