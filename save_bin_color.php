<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);

// Çöp kutusu ID'sini ve rengini session'a kaydet
if (isset($data['binId']) && isset($data['color'])) {
    $_SESSION['bin_colors'][$data['binId']] = $data['color'];
    echo json_encode(['message' => 'Renk başarıyla kaydedildi.']);
} else {
    echo json_encode(['message' => 'Geçersiz veri.']);
}
