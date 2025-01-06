<?php
// Oturum açılmadan önce oturumun durumunu kontrol et
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Eğer oturum başlatılmamışsa, başlat
}

require 'db.php';

// Kullanıcı giriş kontrolü ve sayfa yönlendirme işlemleri burada devam eder
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ... (Kalan kod burada devam eder)
