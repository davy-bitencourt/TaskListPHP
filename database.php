<?php
require_once 'config.php';

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        try {
            $this->pdo = new PDO(DB_DSN);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->createTable();
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function createTable()
    {
        $stmt = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='user'");
        $tableExists = $stmt->fetch() !== false;
        if (!$tableExists) {
            $sql = "CREATE TABLE user ( 
                    id INTEGER PRIMARY KEY AUTOINCREMENT, 
                    username TEXT UNIQUE NOT NULL, 
                    senha TEXT NOT NULL
                )";
            $this->pdo->exec($sql);

        } 

        $stmt = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='task'");
        $taskExists = $stmt->fetch() !== false;
        if (!$taskExists) {
            $sql = "CREATE TABLE task (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    titulo TEXT NOT NULL,
                    status INTEGER DEFAULT 0,
                    type TEXT,
                    data_limite TEXT,
                    user_id INTEGER NOT NULL,
                    FOREIGN KEY (user_id) REFERENCES user(id)
                )";
            $this->pdo->exec($sql);
        }
    }
}

$db = Database::getInstance();