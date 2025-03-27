<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare('DELETE FROM NHANVIEN WHERE Ma_NV = ?');
    $stmt->execute([$id]);
    header('Location: index.php');
    exit;
} else {
    // Nếu truy cập trực tiếp qua GET hoặc không hợp lệ, chuyển về index
    header('Location: index.php');
    exit;
}
