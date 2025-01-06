<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'], $data['color'])) {
    $id = $data['id'];
    $color = $data['color'];

    $stmt = $pdo->prepare("UPDATE bins SET color = ? WHERE id = ?");
    $stmt->execute([$color, $id]);

    echo json_encode(['message' => 'Çöp kutusu rengi güncellendi!']);
} else {
    echo json_encode(['message' => 'Geçersiz veri!']);
}
