<?php
include 'db.php';

// Çöp kutularını ve renklerini veritabanından çek
$query = $pdo->query("SELECT * FROM bins");
$bins = $query->fetchAll(PDO::FETCH_ASSOC);

// JSON formatında veriyi döndür
header('Content-Type: application/json');
echo json_encode($bins);
