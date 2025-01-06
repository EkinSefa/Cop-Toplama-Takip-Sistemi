<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yetkisiz Erişim</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="unauthorized">
        <h1>Yetkisiz Erişim!</h1>
        <p>Bu sayfaya erişim yetkiniz bulunmamaktadır.</p>
        <a href="map.php">Ana sayfaya dön</a>
    </div>
</body>

</html>