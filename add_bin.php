<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['latitude'], $data['longitude'])) {
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];

    $stmt = $pdo->prepare("INSERT INTO bins (latitude, longitude) VALUES (?, ?)");
    $stmt->execute([$latitude, $longitude]);

    echo json_encode(['message' => 'Çöp kutusu başarıyla eklendi!']);
} else {
    echo json_encode(['message' => 'Geçersiz veri!']);
}
