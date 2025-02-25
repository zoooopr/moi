<?php
$host = 'localhost';
$dbname = 'nrotobei';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Kết nối thành công!";
} catch (PDOException $e) {
    die("❌ Lỗi kết nối: " . $e->getMessage());
}
