<?php
$host = 'localhost';
$dbname = 'garbage_tracking';
$username = 'root'; // phpMyAdmin kullanıcı adı
$password = ''; // phpMyAdmin şifresi

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}
