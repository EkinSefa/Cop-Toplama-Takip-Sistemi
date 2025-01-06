<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['ip_address'], $data['device_name'])) {
    $ip = $data['ip_address'];
    $name = $data['device_name'];

    $stmt = $pdo->prepare("INSERT INTO devices (ip_address, device_name) VALUES (?, ?)");
    $stmt->execute([$ip, $name]);

    echo json_encode(['message' => 'Cihaz başarıyla eklendi!']);
} else {
    echo json_encode(['message' => 'Geçersiz veri!']);
}
