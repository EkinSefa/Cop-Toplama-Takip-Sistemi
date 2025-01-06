<?php include 'auth.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Görünümü</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1F3B4D, #85A3B2);
            color: white;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        #map {
            height: 600px;
            width: 75%;
            margin: 20px auto;
        }

        .logout-button-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .logout-button {
            display: inline-block;
            padding: 8px 15px;
            background-color: #e74c3c;
            color: white;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-align: center;
        }

        .logout-button:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="logout-button-container">
        <form action="logout.php" method="POST">
            <button type="submit" class="logout-button">Çıkış Yap</button>
        </form>
    </div>

    <h1>Çöp Toplama Takip Sistemi - Kullanıcı Görünümü</h1>
    <div id="map"></div>

    <script>
        // Haritayı başlat
        const map = L.map('map').setView([39.92077, 32.85411], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 20,
        }).addTo(map);

        // Çöp kutularını getir ve haritada göster
        const markers = {}; // Markerları ID'leri ile tutmak için obje
        function fetchBins() {
            fetch('get_bins.php')
                .then(response => response.json())
                .then(bins => {
                    bins.forEach(bin => {
                        // Marker var mı kontrol et
                        if (!markers[bin.id]) {
                            const iconUrl = bin.color === 'green' ?
                                'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png' :
                                bin.color === 'red' ?
                                'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png' :
                                'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png';

                            const customIcon = L.icon({
                                iconUrl: iconUrl,
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34]
                            });

                            const marker = L.marker([bin.latitude, bin.longitude], {
                                title: `Çöp Kutusu - ID: ${bin.id}`,
                                icon: customIcon
                            }).addTo(map);

                            marker.bindPopup(`<p>Çöp Kutusu ID: ${bin.id}</p>`);
                            markers[bin.id] = marker; // Markerı kaydet
                        } else {
                            // Marker varsa sadece rengini güncelle
                            const marker = markers[bin.id];
                            const iconUrl = bin.color === 'green' ?
                                'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png' :
                                bin.color === 'red' ?
                                'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png' :
                                'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png';

                            const customIcon = L.icon({
                                iconUrl: iconUrl,
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34]
                            });

                            marker.setIcon(customIcon); // Marker rengini güncelle
                        }
                    });
                })
                .catch(error => console.error('Hata:', error));
        }

        // Haritayı düzenli olarak güncelle
        setInterval(fetchBins, 1000); // Her saniyede bir güncelle

        // İlk başta çöp kutularını yükle
        fetchBins();
    </script>
</body>

</html>