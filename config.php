<?php
$dsn = 'mysql:host=localhost;dbname=ql_nhansu';
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Kết nối thất bại: ' . $e->getMessage());
}
