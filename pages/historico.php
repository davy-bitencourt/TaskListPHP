<?php
session_start();
require_once '../src/crud.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$repo = new CRUD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? null;

    if ($acao === 'status') {
        $task = $repo->getById((int)$_POST['id']);
        if ($task && $task->user_id === $_SESSION['user_id']) {
            $task->status = $_POST['status'] == '1';
            $repo->update($task);
        }
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
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$tasksConcluidas = $repo->getConcluidasByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <h2>Tarefas concluídas</h2>

    <table>
        <thead>
            <tr>
                <th> </th>
                <th>Título</th>
                <th>Tipo</th>
                <th>Prazo</th>
                <th> </th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tasksConcluidas)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Nenhuma tarefa concluída ainda</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tasksConcluidas as $t): ?>
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
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div id="modalEditarTarefa" class="modal">
        <div class="modal-content">
            <span class="special" id="fecharModal">&times;</span>

            <?php if (isset($_GET['edit'])):
                $editTask = $repo->getById((int)$_GET['edit']);
            ?>
                <h3>Editar tarefa concluída</h3>
                <form method="POST">
                    <input type="hidden" name="acao" value="editar">
                    <input type="hidden" name="id" value="<?= $editTask->id ?>">
                    <input type="hidden" name="status" value="1">

                    <label>Título:</label>
                    <input type="text" name="titulo" value="<?= htmlspecialchars($editTask->titulo) ?>" required>

                    <label>Tipo:</label>
                    <input type="text" name="tipo" value="<?= htmlspecialchars($editTask->type) ?>">

                    <label>Prazo:</label>
                    <input type="date" name="data_limite" value="<?= htmlspecialchars($editTask->data_limite) ?>">

                    <input type="submit" value="Salvar alterações">
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalEditarTarefa');
        const close = document.getElementById('fecharModal');

        if (close) {
            close.onclick = () => modal.style.display = 'none';
            window.onclick = e => { if (e.target == modal) modal.style.display = 'none'; };
        }
    </script>

    <?php if (isset($_GET['edit'])): ?>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            document.getElementById('modalEditarTarefa').style.display = 'block';
        });
    </script>
    <?php endif; ?>

</body>
</html>
