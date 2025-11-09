<?php
session_start();
require_once '../crud.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$repo = new CRUD();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
    $task = new Task(
        id: 0, 
        titulo: $_POST['titulo'],
        status: false,
        type: $_POST['tipo'] ?? '',
        data_limite: $_POST['data_limite'] ?? '',
        user_id: $_SESSION['user_id']
    );

    $repo->create($task);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


$tasks = $repo->getByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <a href="logout.php">Sair</a>

    <h2>Suas tarefas</h2>
    
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Status</th>
                <th>Tipo</th>
                <th>Prazo</th>
                <th>
                    <button id="abrirModal">+ Nova Tarefa</button>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tasks)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Nenhuma tarefa encontrada</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tasks as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t->titulo) ?></td>
                        <td><?= $t->status ? "Concluída" : "Pendente" ?></td>
                        <td><?= htmlspecialchars($t->type) ?></td>
                        <td><?= htmlspecialchars($t->data_limite) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $t->id ?>">Editar</a> |
                            <a href="excluir.php?id=<?= $t->id ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div id="modalNovaTarefa" class="modal">
        <div class="modal-content">
            <span class="close" id="fecharModal">&times;</span>
            <h3>Criar nova tarefa</h3>
            <form method="POST">
                <label>Título:</label>
                <input type="text" name="titulo" required>

                <label>Tipo:</label>
                <input type="text" name="tipo">

                <label>Prazo:</label>
                <input type="date" name="data_limite">

                <input type="submit" value="Salvar">
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalNovaTarefa');
        const btn = document.getElementById('abrirModal');
        const close = document.getElementById('fecharModal');

        btn.onclick = () => modal.style.display = 'block';
        close.onclick = () => modal.style.display = 'none';
        window.onclick = e => { if (e.target == modal) modal.style.display = 'none'; };
    </script>

</body>
</html>
