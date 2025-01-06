<?php
// auth.php dosyasını dahil et (kullanıcı oturumunu doğrulama)
include 'auth.php';

// Kullanıcı oturumu yoksa login sayfasına yönlendirme
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); // Kodun yönlendirme sonrası devam etmesini engellemek için
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çöp Toplama Takip Sistemi</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1F3B4D, #85A3B2);
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: white;
        }

        /* Harita alanı */
        #map {
            height: 600px;
            width: 75%;
            margin: 20px auto;
            border: 2px solid #3498db;
            border-radius: 8px;
        }

        /* Form düzeni */
        form {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form h2 {
            margin-bottom: 20px;
            color: #1F3B4D;
            font-size: 20px;
            text-align: center;
        }

        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        form input {
            width: calc(100% - 20px);
            padding: 8px 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            width: 100%;
            padding: 10px;
            background-color: #1F3B4D;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        form button:hover {
            background-color: rgb(67, 83, 92);
        }

        /* Çıkış butonu stili */
        .logout-button-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .logout-button {
            display: inline-block;
            padding: 4px 10px;
            background-color: #e74c3c;
            color: white;
            font-size: 10px;
            font-weight: bold;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-align: center;
        }

        .logout-button:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .logout-button:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }


        /* Mobil uyumluluk */
        @media (max-width: 768px) {
            #map {
                height: 300px;
            }

            form {
                width: 90%;
            }

            form h2 {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

    <div class="logout-button-container">
        <form action="logout.php" method="POST">
            <button type="submit" class="logout-button">Çıkış Yap</button>
        </form>
    </div>

    <h1>Çöp Toplama Takip Sistemi</h1>

    <!-- Harita -->
    <div id="map"></div>
    <!-- Çöp Kutusu Ekleme Formu -->
    <form id="addBinForm">
        <h2>Manuel Çöp Kutusu Ekle</h2>
        <label for="latitude">Enlem:</label>
        <input type="text" id="latitude" name="latitude" required>
        <label for="longitude">Boylam:</label>
        <input type="text" id="longitude" name="longitude" required>
        <button type="submit">Ekle</button>
    </form>

    <form id="addDeviceForm">
        <h2>Cihaz Ekle</h2>
        <label for="deviceName">Cihaz Adı:</label>
        <input type="text" id="deviceName" name="deviceName" placeholder="Cihaz adı girin" required>
        <label for="ipAddress">IP Adresi:</label>
        <input type="text" id="ipAddress" name="ipAddress" placeholder="IP adresi girin" required>
        <button type="submit">Ekle</button>
    </form>

    <form id="deleteDeviceForm">
        <h2>Cihaz Sil</h2>
        <label for="deviceId">Cihaz ID:</label>
        <input type="number" id="deviceId" name="deviceId" placeholder="Cihaz ID girin" required>
        <button type="submit">Sil</button>
    </form>

    <script>
        // Cihaz Ekleme Formu
        document.getElementById('addDeviceForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const deviceName = document.getElementById('deviceName').value;
            const ipAddress = document.getElementById('ipAddress').value;

            fetch('add_device.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        device_name: deviceName,
                        ip_address: ipAddress
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload(); // Haritayı güncellemek için sayfayı yenile
                });
        });

        // Cihaz Silme Formu
        document.getElementById('deleteDeviceForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const deviceId = document.getElementById('deviceId').value;

            fetch('delete_device.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: deviceId
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload(); // Haritayı güncellemek için sayfayı yenile
                });
        });
    </script>


    <script>
        // Haritayı başlat
        const map = L.map('map').setView([39.92077, 32.85411], 13); // Varsayılan Ankara koordinatları
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        let polygon; // Poligonu global bir değişkende saklayalım

        fetch('read_json.php')
            .then(response => response.json())
            .then(data => {
                const coordinates = data.geometry.coordinates[0];

                // Leaflet için koordinatları dönüştür
                const latLngs = coordinates.map(coord => [coord[1], coord[0]]);

                // Poligon oluştur ve haritaya ekle
                polygon = L.polygon(latLngs, {
                    color: 'blue',
                    fillColor: 'lightblue',
                    fillOpacity: 0.2
                }).addTo(map);

                // Poligon popup
                polygon.bindPopup(data.properties.name);

                // Harita görünümünü poligonun kapsadığı alana sığdır
                map.fitBounds(polygon.getBounds());

                // Poligon içine tıklama olayı: Marker ekleme
                polygon.on('click', function(e) {
                    const {
                        lat,
                        lng
                    } = e.latlng;
                    if (confirm(`Bu noktaya çöp kutusu eklemek istiyor musunuz? (Enlem: ${lat}, Boylam: ${lng})`)) {
                        addBinToDatabase(lat, lng);
                    }
                });
            })
            .catch(error => console.error('Hata:', error));

        // Kullanıcının konumunu al ve haritada göster
        let userLat, userLon;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                userLat = position.coords.latitude;
                userLon = position.coords.longitude;

                // Kullanıcı konumunu haritada göster
                const userMarker = L.marker([userLat, userLon], {
                    title: "Mevcut Konumunuz",
                    icon: L.icon({
                        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34]
                    })
                }).addTo(map);

                // Kullanıcının etrafında 50m çapında bir daire oluştur
                const userCircle = L.circle([userLat, userLon], {
                    color: 'blue', // Dairenin rengi
                    fillColor: 'blue', // Dairenin iç rengini ayarlıyoruz
                    fillOpacity: 0.2, // Dairenin iç opaklık değeri
                    radius: 50 // Dairenin çapı (50 metre)
                }).addTo(map);

                // Popup mesajı ekle
                userMarker.bindPopup("Mevcut Konumunuz").openPopup();

                // Harita görünümünü kullanıcı konumuna odakla
                map.setView([userLat, userLon], 15);
            }, error => {
                alert("Konum alınamadı! Lütfen tarayıcınızın konum erişimine izin verdiğinden emin olun.");
            });
        } else {
            alert("Tarayıcınız konum servisini desteklemiyor!");
        }


        let markers = [];

        // Çöp kutularını getir ve haritada göster
        fetch('get_bins.php')
            .then(response => response.json())
            .then(bins => {
                bins.forEach(bin => {
                    const binLat = bin.latitude;
                    const binLon = bin.longitude;
                    const binId = bin.id;

                    const marker = L.marker([binLat, binLon], {
                        title: `Çöp Kutusu - ID: ${binId}`,
                    }).addTo(map);

                    // sessionStorage'daki rengi kontrol et
                    const savedColor = sessionStorage.getItem(`marker-${binId}`);
                    if (savedColor === "green") {
                        const greenIcon = L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34]
                        });
                        marker.setIcon(greenIcon);
                    }

                    marker.bindPopup(`
                <p>Çöp Kutusu ID: ${binId}</p>
                <button onclick="deleteBin(${binId})">Sil</button>
            `);

                    markers.push({
                        marker,
                        id: binId
                    });
                });
            });



        // Çöp kutusu ekleme işlemi
        document.getElementById("addBinForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const latitude = document.getElementById("latitude").value;
            const longitude = document.getElementById("longitude").value;

            addBinToDatabase(latitude, longitude);
        });

        // Haritaya tıklama ile çöp kutusu ekleme
        map.on('click', function(e) {
            const {
                lat,
                lng
            } = e.latlng;

            // Çöp kutusu eklemek için onay iste
            if (confirm(`Bu noktaya çöp kutusu eklemek istiyor musunuz? (Enlem: ${lat}, Boylam: ${lng})`)) {
                addBinToDatabase(lat, lng);
            }
        });

        // Çöp kutusu ekleme işlevi
        function addBinToDatabase(latitude, longitude) {
            fetch('add_bin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        latitude,
                        longitude
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload(); // Sayfayı yenileyerek yeni kutuyu göster
                });
        }

        // Çöp kutusu silme işlemi
        function deleteBin(binId) {
            fetch('delete_bin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: binId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);

                    // sessionStorage'dan sil
                    sessionStorage.removeItem(`marker-${binId}`);
                    location.reload(); // Haritayı güncellemek için sayfayı yenile
                });
        }


        // 24 saatte bir markerların rengini eski rengine döndür
        setInterval(() => {
            markers.forEach(item => {
                // Markerın rengini eski rengine döndür
                item.marker.setIcon(L.icon({
                    iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34]
                }));
                item.currentColor = item.defaultColor; // Eski renk olarak ayarla
            });
        }, 24 * 60 * 60 * 1000); // 24 saat (24 saat * 60 dakika * 60 saniye * 1000 milisaniye)

        let lastLocationUpdate = {}; // Kullanıcı konumunun son güncellenme zamanı (her çöp kutusu için)
        let distanceCheckInterval = null; // Mesafe kontrolü için interval

        // Mesafeye göre renk değiştirme işlevi
        function checkDistanceAndChangeColor(binLat, binLon, marker, binId) {
            const binPosition = L.latLng(binLat, binLon);
            const userPosition = L.latLng(userLat, userLon);
            const distance = userPosition.distanceTo(binPosition);

            if (distance <= 50) {
                if (!lastLocationUpdate[binId]) {
                    lastLocationUpdate[binId] = Date.now();
                } else {
                    const timeElapsed = (Date.now() - lastLocationUpdate[binId]) / 1000;
                    if (timeElapsed >= 10) {
                        const greenIcon = L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34]
                        });
                        marker.setIcon(greenIcon);
                        lastLocationUpdate[binId] = null;

                        // sessionStorage'a kaydet
                        sessionStorage.setItem(`marker-${binId}`, "green");
                    }
                }
            } else {
                lastLocationUpdate[binId] = null;
            }
        }


        // Kullanıcı konumunu sürekli kontrol et ve mesafeyi yeniden hesapla
        setInterval(() => {
            markers.forEach(item => {
                checkDistanceAndChangeColor(item.marker.getLatLng().lat, item.marker.getLatLng().lng, item.marker, item.id);
            });
        }, 1000); // Her saniye kontrol et


        // Kullanıcı konumunu sürekli kontrol et ve mesafeyi yeniden hesapla
        setInterval(() => {
            markers.forEach(item => {
                checkDistanceAndChangeColor(item.marker.getLatLng().lat, item.marker.getLatLng().lng, item.marker);
            });
        }, 1000); // Her saniye kontrol et






        // Kullanıcının konumunu takip etmek için polyline ve pozisyon kontrolü
        let userPath = [];
        let userPolyline;

        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(position => {
                const {
                    latitude,
                    longitude
                } = position.coords;

                // Kullanıcının yeni konumunu ekle
                userPath.push([latitude, longitude]);

                // Eğer polyline yoksa yeni oluştur
                if (!userPolyline) {
                    userPolyline = L.polyline(userPath, {
                        color: 'red', // Çizginin rengi
                        weight: 4, // Çizginin kalınlığı
                    }).addTo(map);

                    // Harita görünümünü kullanıcı yolunu gösterecek şekilde ayarla
                    map.setView([latitude, longitude], 15);
                } else {
                    // Polyline'ı yeni konumla güncelle
                    userPolyline.setLatLngs(userPath);
                }
            }, error => {
                console.error('Konum alınamadı:', error.message);
            }, {
                enableHighAccuracy: true, // Daha doğru konum bilgisi için
                maximumAge: 0, // Konum verilerini her zaman güncel tut
            });
        } else {
            alert("Tarayıcınız konum servisini desteklemiyor!");
        }
        fetch('get_devices.php')
            .then(response => response.json())
            .then(devices => {
                devices.forEach(device => {
                    if (device.latitude && device.longitude) {
                        L.marker([device.latitude, device.longitude])
                            .addTo(map)
                            .bindPopup(`<b>${device.device_name}</b>`);
                    }
                });
            });

        // Kullanıcı yolunu sıfırlamak için buton ekleme
        const resetButton = document.createElement('button');
        resetButton.textContent = "Yolu Sıfırla";
        resetButton.style.position = "absolute";
        resetButton.style.top = "20px";
        resetButton.style.left = "20px";
        resetButton.style.zIndex = "1000";
        resetButton.style.padding = "10px";
        resetButton.style.backgroundColor = "#e74c3c";
        resetButton.style.color = "white";
        resetButton.style.border = "none";
        resetButton.style.borderRadius = "5px";
        resetButton.style.cursor = "pointer";

        resetButton.addEventListener('click', () => {
            if (userPolyline) {
                userPath = [];
                userPolyline.setLatLngs(userPath);
                alert("Kullanıcı yolu sıfırlandı.");
            }
        });

        document.body.appendChild(resetButton);
    </script>
</body>

</html>