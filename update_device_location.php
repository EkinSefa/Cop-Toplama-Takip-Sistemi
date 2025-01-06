<?php
include 'db.php';

$apiKey = 'b1d55e72837a9a9de13ec706384b3311';

// Veritabanından cihazları al
$query = $pdo->query("SELECT id, ip_address FROM devices");
$devices = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($devices as $device) {
    $ip = $device['ip_address'];

    // API çağrısı yap
    $url = "http://api.ipstack.com/{$ip}?access_key={$apiKey}";
    $response = @file_get_contents($url);  // @ ile hataları gizle
    if ($response === FALSE) {
        echo "API çağrısı başarısız: {$ip}\n";
        continue; // Hata durumunda bir sonraki cihaza geç
    }

    $data = json_decode($response, true);

    // API yanıtında latitude ve longitude kontrolü
    if (isset($data['latitude'], $data['longitude'])) {
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];

        // Veritabanını güncelle
        $stmt = $pdo->prepare("UPDATE devices SET latitude = ?, longitude = ?, last_updated = NOW() WHERE id = ?");
        $stmt->execute([$latitude, $longitude, $device['id']]);
        echo "Cihaz ID {$device['id']} konumu güncellendi: {$latitude}, {$longitude}\n";
    } else {
        echo "Geçersiz veri: IP {$ip} için latitude ve longitude bulunamadı.\n";
    }
}

echo "Cihaz konumları güncellendi!";
