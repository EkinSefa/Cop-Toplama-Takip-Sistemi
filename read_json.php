<?php
// JSON dosyasını oku
$jsonData = file_get_contents('geo_location.json');

// JSON çıktısını tarayıcıya gönder
header('Content-Type: application/json');
echo $jsonData;
