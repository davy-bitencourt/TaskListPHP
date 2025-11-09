<?php
session_start();
require_once '../crud.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$repo = new CRUD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? 'criar';

    if ($acao === 'criar') {
        $task = new Task(
            id: 0,
            titulo: $_POST['titulo'],
            status: false,
            type: $_POST['tipo'] ?? '',
            data_limite: $_POST['data_limite'] ?? '',
            user_id: $_SESSION['user_id']
        );
        $repo->create($task);
    } elseif ($acao === 'editar') {
        $task = new Task(
            id: (int)$_POST['id'],
            titulo: $_POST['titulo'],
            status: $_POST['status'] == '1',
            type: $_POST['tipo'] ?? '',
            data_limite: $_POST['data_limite'] ?? '',
            user_id: $_SESSION['user_id']
        );
        $repo->update($task);
    } elseif ($acao === 'status') {
        $task = $repo->getById((int)$_POST['id']);
        if ($task && $task->user_id === $_SESSION['user_id']) {
            $task->status = $_POST['status'] == '1';
            $repo->update($task);
        }
    }

    header("Location: dashboard.php");
    exit;
}

$tasks = $repo->getByUser($_SESSION['user_id']);

$allCompleted = false;
if (!empty($tasks)) {
    $allCompleted = array_reduce($tasks, function($carry, $task) {
        return $carry && $task->status;
    }, true);
}
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

    <?php include '../includes/header.php'; ?>

    <h2>Suas tarefas</h2>
    <table>
        <thead>
            <tr>
                <th> </th>
                <th>Título</th>
                <th>Tipo</th>
                <th>Prazo</th>
                <th>
                    <a href="?add=1" class="th" id="abrirModal">+ Nova Tarefa</a>
                </th>
            </tr>
        </thead>
            <tbody>
                <?php if (empty($tasks)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">Nenhuma tarefa encontrada</td>
                    </tr>
                <?php elseif ($allCompleted): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">Tarefas concluídas!</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tasks as $t): ?>
                        <?php if (!$t->status): ?>
                            <tr>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="acao" value="status">
                                        <input type="hidden" name="id" value="<?= $t->id ?>">
                                        <input type="hidden" name="status" value="<?= $t->status ? '0' : '1' ?>">
                                        <input type="checkbox" onchange="this.form.submit()" <?= $t->status ? 'checked' : '' ?>>
                                    </form>
                                </td>
                                <td><?= htmlspecialchars($t->titulo) ?></td>
                                <td><?= htmlspecialchars($t->type) ?></td>
                                <td><?= htmlspecialchars($t->data_limite) ?></td>
                                <td>
                                    <a class="a2" href="?edit=<?= $t->id ?>">Editar</a>
                                    <a class="a2" href="excluir.php?id=<?= $t->id ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
    </table>

<div id="modalNovaTarefa" class="modal">
    <div class="modal-content">
        <span class="special" id="fecharModal">&times;</span>

        <?php if (isset($_GET['edit'])): ?>
            <?php $editTask = $repo->getById((int)$_GET['edit']); ?>
            <h3>Editar tarefa</h3>
            <form method="POST">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" value="<?= $editTask->id ?>">

                <label>Título:</label>
                <input type="text" name="titulo" value="<?= htmlspecialchars($editTask->titulo) ?>" required>

                <label>Tipo:</label>
                <input type="text" name="tipo" value="<?= htmlspecialchars($editTask->type) ?>">

                <label>Prazo:</label>
                <input type="date" name="data_limite" value="<?= htmlspecialchars($editTask->data_limite) ?>">

                <input type="submit" value="Salvar alterações">
            </form>

        <?php elseif (isset($_GET['add'])): ?>
            <h3>Criar nova tarefa</h3>
            <form method="POST">
                <input type="hidden" name="acao" value="criar">

                <label>Título:</label>
                <input type="text" name="titulo" required>

                <label>Tipo:</label>
                <input type="text" name="tipo">

                <label>Prazo:</label>
                <input type="date" name="data_limite">

                <input type="submit" value="Salvar">
            </form>
        <?php endif; ?>
    </div>
</div>


    <script>
        const modal = document.getElementById('modalNovaTarefa');
        const btn = document.getElementById('abrirModal');
        const close = document.getElementById('fecharModal');

        btn.onclick = (e) => { modal.style.display = 'block'; };
        close.onclick = () => { modal.style.display = 'none'; };
    </script>

    <?php if (isset($_GET['add']) || isset($_GET['edit'])): ?>
    <script>
        window.addEventListener('DOMContentLoaded', () => {document.getElementById('modalNovaTarefa').style.display = 'block';});
    </script>
    <?php endif; ?>

</body>
</html>
