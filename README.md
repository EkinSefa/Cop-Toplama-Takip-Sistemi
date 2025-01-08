# Çöp Toplama Takip Sistemi 🌍🗑️

Modern bir belediye yönetim sistemi için tasarlanmış, kullanımı kolay ve interaktif bir harita tabanlı çöp toplama takip uygulaması. Admin ve User rollerine göre özelleştirilmiş özelliklerle şehir temizliğini etkin bir şekilde yönetmeye olanak sağlar.

## Özellikler 🌟

### 👨‍💼 Admin için:
 
🗺️ Haritaya tıklayarak yeni çöp kutuları (marker) ekleme.

🎨 Çöp kutusu durumuna göre marker renk değişikliği (örneğin, dolu veya boş).

📊 Çöp kutusu konumlarını ve bilgilerini veri tabanında saklama ve yönetme.

📍 GPS kullanarak canlı konum takibi.

### 👤 User için:

👀 Admin tarafından eklenen haritayı yalnızca izleme yetkisi.

🗺️ Çöp kutularını, durum renklerini ve çevre alanlarını görüntüleme.

🔒 Haritada değişiklik yapamama (sadece görüntüleme yetkisi).

## Proje Görselleri

![TakipSistemiGörselleri1](https://github.com/user-attachments/assets/811d29f4-9c99-4d33-abfa-3265f013a990)


![TakipSistemiGörselleri2](https://github.com/user-attachments/assets/6dbd9fbf-1e74-43cd-9098-12b59d504dee)

## Kullanılan Teknolojiler 🛠️

Backend: PHP 

Frontend: HTML, CSS, JavaScript (Leaflet.js harita entegrasyonu)

Database: PhpMyAdmin (Veri saklama ve yönetim için)

### Kurulum ve Kullanım 🚀

Depoyu klonlayın:

```bash
git clone https://github.com/EkinSefa/Cop-Toplama-Takip-Sistemi.git
```
**PHP ve MySQL destekli bir yerel sunucuda çalıştırın (örn: XAMPP veya MAMP).**

**Veri tabanını yapılandırmak için db.sql dosyasını yükleyin.**

**auth.php dosyasındaki bağlantı ayarlarını kendi sisteminize göre düzenleyin.**

**Uygulamayı kullanmak için localhost/index.php adresini ziyaret edin.**
