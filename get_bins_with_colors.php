<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "garbage_collection";

$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Çöp kutularını ve renklerini al
$sql = "SELECT id, latitude, longitude, color FROM bins";
$result = $conn->query($sql);

$bins = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bins[] = $row; // Çöp kutusu bilgilerini diziye ekle
    }
}

// JSON olarak çıktı ver
echo json_encode($bins);

$conn->close();
