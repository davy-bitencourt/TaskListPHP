<?php
require_once 'database.php';
require_once 'Task.php';

class CRUD {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(Task $task): bool {
        $stmt = $this->db->prepare("INSERT INTO task (titulo, status, type, data_limite, user_id) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$task->titulo,$task->status ? 1 : 0, $task->type,$task->data_limite, $task->user_id]);
    }

    public function getByUser(int $user_id): array {
        $stmt = $this->db->prepare("SELECT * FROM task WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tasks = [];
        foreach ($rows as $r) {
            $tasks[] = new Task($r['id'], $r['titulo'],(bool)$r['status'], $r['type'], $r['data_limite'], $r['user_id']);
        }

        return $tasks;
    }

    public function getById(int $id): ?Task {
        $stmt = $this->db->prepare("SELECT * FROM task WHERE id = ?");
        $stmt->execute([$id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$r) return null;

        return new Task($r['id'], $r['titulo'], (bool)$r['status'], $r['type'], $r['data_limite'], $r['user_id']);
    }

    public function update(Task $task): bool {
        $stmt = $this->db->prepare("UPDATE task SET titulo = ?, status = ?, type = ?, data_limite = ? WHERE id = ?");
        return $stmt->execute([$task->titulo, $task->status ? 1 : 0, $task->type, $task->data_limite, $task->id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM task WHERE id = ?");
        return $stmt->execute([$id]);
    }


    public function getConcluidasByUser(int $user_id): array {
    $stmt = $this->db->prepare("SELECT * FROM task WHERE user_id = ? AND status = 1 ORDER BY data_limite DESC");
    $stmt->execute([$user_id]);
    $tasks = [];

    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tasks[] = new Task($r['id'], $r['titulo'], (bool)$r['status'], $r['type'], $r['data_limite'], $r['user_id']);
    }
    return $tasks;
    }
}
?>