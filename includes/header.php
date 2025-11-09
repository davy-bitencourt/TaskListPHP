<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}
?>

<head>
    </head>
    <header>
    <link rel="stylesheet" href="style.css">
    <div>
        <h2 style="margin: 0;">Olá, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    </div>

    <nav>
        <a href="dashboard.php" style="margin-right: 15px; text-decoration: none;" onmouseover="this.style.color='#000';" onmouseout="this.style.color='#888';" >Dashboard</a>
        <a href="historico.php" style="margin-right: 15px; text-decoration: none;"onmouseover="this.style.color='#000';" onmouseout="this.style.color='#888';" >Histórico</a>
        <a href="logout.php" style="text-decoration: none;" onmouseover="this.style.color='#9c0000ff';" onmouseout="this.style.color='#ff0000ff';">Sair</a>
    </nav>
</header>
