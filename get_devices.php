<?php
header('Content-Type: application/json');

// Veritabanı bağlantısını yapın
include('db_connection.php');

// Cihazları veritabanından çekin
$query = "SELECT * FROM devices"; // Burada 'devices' tablonuzdan cihaz bilgilerini çekiyorsunuz
$result = mysqli_query($conn, $query);

$devices = [];

// Cihazları JSON formatında döndür
while ($row = mysqli_fetch_assoc($result)) {
    $devices[] = [
        'device_name' => $row['device_name'],
        'latitude' => $row['latitude'],
        'longitude' => $row['longitude']
    ];
}

// JSON formatında cihaz verilerini döndürün
echo json_encode($devices);
