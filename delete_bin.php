<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $id = $data['id'];

    $stmt = $pdo->prepare("DELETE FROM bins WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['message' => 'Çöp kutusu başarıyla silindi!']);
} else {
    echo json_encode(['message' => 'Geçersiz veri!']);
}
