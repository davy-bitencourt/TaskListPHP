<?php
session_start();
require_once '../src/crud.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: dashboard.php'); 
    exit;
}

$repo = new CRUD();
$taskId = (int)$_GET['id'];

$task = $repo->getById($taskId);
if ($task->user_id !== $_SESSION['user_id']) {
    die('Acesso negado.');
}

$repo->delete($taskId);
header('Location: ' . $_SERVER['HTTP_REFERER']); 
exit;
?>
