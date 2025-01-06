document.addEventListener("DOMContentLoaded", () => {
  const map = L.map("map").setView([39.92077, 32.85411], 13); // Başlangıç noktası: Ankara

  // OpenStreetMap katmanı ekle
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
  }).addTo(map);

  // Kullanıcı rolüne göre özellik kontrolü
  if (userRole === "admin") {
    // Admin kullanıcı için harita düzenlemeleri
    map.on("click", (e) => {
      const lat = e.latlng.lat;
      const lon = e.latlng.lng;

      // Çöp kutusu ekle
      const marker = L.marker([lat, lon], {
        title: "Yeni Çöp Kutusu",
      }).addTo(map);

      marker.bindPopup(`
        <p>Koordinatlar: ${lat}, ${lon}</p>
        <button onclick="deleteBin(${lat}, ${lon})">Sil</button>
      `);

      // API'ye kaydet
      fetch("add_bin.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ latitude: lat, longitude: lon }),
      })
        .then((response) => response.json())
        .then((data) => alert(data.message));
    });
  } else {
    alert("Haritada değişiklik yapma yetkiniz yok.");
  }

  // Çöp Kutularını Harita Üzerinde Göster
  fetch("get_bins.php")
    .then((response) => response.json())
    .then((bins) => {
      bins.forEach((bin) => {
        const marker = L.marker([bin.latitude, bin.longitude], {
          title: `Çöp Kutusu - ID: ${bin.id}`,
        }).addTo(map);

        marker.bindPopup(`
          <p>Çöp Kutusu ID: ${bin.id}</p>
        `);
      });
    });

  // Çöp Kutusu Silme Fonksiyonu (Yalnızca Admin)
  window.deleteBin = function (lat, lon) {
    if (userRole === "admin") {
      fetch("delete_bin.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ latitude: lat, longitude: lon }),
      })
        .then((response) => response.json())
        .then((data) => {
          alert(data.message);
          location.reload(); // Haritayı güncellemek için sayfayı yenile
        });
    } else {
      alert("Bu işlemi yapma yetkiniz yok.");
    }
  };

  // Kullanıcı konumunu ekleme (yalnızca Admin)
  if (userRole === "admin" && navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
      const userLat = position.coords.latitude;
      const userLon = position.coords.longitude;

      const userMarker = L.marker([userLat, userLon], {
        title: "Mevcut Konumunuz",
      }).addTo(map);

      userMarker.bindPopup(`
        <p>Enlem: ${userLat}</p>
        <p>Boylam: ${userLon}</p>
      `);
    });
  }
});
