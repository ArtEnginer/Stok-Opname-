# 🚀 PANDUAN SETUP - APLIKASI DONASI ONLINE

## Langkah-langkah Setup

### 1️⃣ Persiapan Database

```sql
-- Login ke MySQL
mysql -u root -p

-- Buat database baru
CREATE DATABASE donasi_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Keluar dari MySQL
EXIT;
```

### 2️⃣ Konfigurasi Environment

```bash
# Copy file env menjadi .env
copy env .env
```

Edit file `.env` dan sesuaikan konfigurasi berikut:

```env
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------

CI_ENVIRONMENT = development

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------

app.baseURL = 'http://localhost:8080/'
# app.forceGlobalSecureRequests = false
# app.CSPEnabled = false

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------

database.default.hostname = localhost
database.default.database = donasi_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

### 3️⃣ Jalankan Migration

```bash
# Jalankan semua migration untuk membuat tabel
php spark migrate

# Output yang diharapkan:
# Running: 2024-01-01-000001_CreateCategoriesTable
# Running: 2024-01-01-000002_CreateCampaignsTable
# Running: 2024-01-01-000003_CreateDonationsTable
```

### 4️⃣ Jalankan Seeder (Data Sample)

```bash
# Isi database dengan data sample
php spark db:seed DatabaseSeeder

# Output yang diharapkan:
# ✅ Database seeded successfully!
#    - Categories: 6 items
#    - Campaigns: 5 items
```

### 5️⃣ Set Permission Folder

```bash
# Windows PowerShell (Run as Administrator)
icacls writable /grant Everyone:F /T
icacls writable\uploads /grant Everyone:F /T

# Linux/Mac
chmod -R 777 writable
chmod -R 777 writable/uploads
```

### 6️⃣ Jalankan Development Server

```bash
# Jalankan server
php spark serve

# Server akan berjalan di:
# http://localhost:8080
```

### 7️⃣ Akses Aplikasi

**Frontend (Public):**
- Homepage: http://localhost:8080/
- Daftar Campaign: http://localhost:8080/campaign
- Tentang: http://localhost:8080/about
- Kontak: http://localhost:8080/contact

**Admin Panel:**
- Dashboard: http://localhost:8080/admin
- Kelola Campaign: http://localhost:8080/admin/campaigns
- Kelola Donasi: http://localhost:8080/admin/donations

---

## 🎯 Data Sample yang Tersedia

Setelah menjalankan seeder, Anda akan memiliki:

### Kategori (6 items):
1. Kesehatan
2. Pendidikan
3. Bencana Alam
4. Kemanusiaan
5. Lingkungan
6. Pemberdayaan

### Campaign Sample (5 items):
1. Bantu Adik Rina Melawan Kanker Darah (Kesehatan - Urgent)
2. Beasiswa untuk 100 Anak Kurang Mampu (Pendidikan)
3. Bantu Korban Banjir Bandang di Sumatra (Bencana - Urgent)
4. Renovasi Panti Asuhan Cahaya Kasih (Kemanusiaan)
5. Program Penanaman 10.000 Pohon (Lingkungan)

---

## 🧪 Testing Fitur

### Test Donasi:
1. Buka campaign: http://localhost:8080/campaign
2. Pilih salah satu campaign
3. Klik "Donasi Sekarang"
4. Isi form donasi dengan data:
   - Nama: Test User
   - Email: test@example.com
   - Phone: 081234567890
   - Amount: 100000
   - Payment Method: Bank Transfer
   - Upload gambar sebagai bukti transfer
5. Submit form
6. Lihat halaman success dengan detail transaksi

### Test Admin Panel:
1. Akses http://localhost:8080/admin
2. Dashboard menampilkan statistik
3. Kelola Campaign:
   - Create: Buat campaign baru
   - Edit: Edit campaign existing
   - Delete: Hapus campaign
4. Kelola Donasi:
   - Lihat daftar donasi
   - Verifikasi donasi pending
   - Export data donasi

---

## 🔧 Troubleshooting

### Error: "Database connection failed"
**Solusi:**
- Pastikan MySQL service running
- Cek kredensial database di file `.env`
- Pastikan database sudah dibuat

### Error: "Unable to locate the model"
**Solusi:**
```bash
composer dump-autoload
```

### Error: "writable is not writable"
**Solusi:**
```bash
# Windows
icacls writable /grant Everyone:F /T

# Linux/Mac
chmod -R 777 writable
```

### Upload gambar tidak berfungsi
**Solusi:**
- Pastikan folder `writable/uploads/campaigns` dan `writable/uploads/payment_proofs` ada
- Set permission yang benar (777)
- Cek file size limit di php.ini:
  ```ini
  upload_max_filesize = 10M
  post_max_size = 10M
  ```

### CSS Tailwind tidak muncul
**Solusi:**
- Pastikan koneksi internet aktif (Tailwind menggunakan CDN)
- Clear browser cache
- Cek browser console untuk error

---

## 📦 Struktur Folder Penting

```
WEB-DONASI/
├── app/
│   ├── Controllers/
│   │   ├── Home.php
│   │   ├── CampaignController.php
│   │   ├── DonationController.php
│   │   └── Admin/
│   ├── Models/
│   │   ├── CampaignModel.php
│   │   ├── DonationModel.php
│   │   └── CategoryModel.php
│   ├── Views/
│   │   ├── layouts/
│   │   │   ├── main.php
│   │   │   └── admin.php
│   │   ├── pages/
│   │   │   ├── home.php
│   │   │   ├── campaigns.php
│   │   │   ├── campaign_detail.php
│   │   │   ├── donation_form.php
│   │   │   └── donation_success.php
│   │   └── admin/
│   ├── Database/
│   │   ├── Migrations/
│   │   └── Seeds/
│   └── Helpers/
│       └── donation_helper.php
├── writable/
│   └── uploads/
│       ├── campaigns/
│       └── payment_proofs/
└── public/
```

---

## 🎨 Customization Tips

### Mengganti Warna Theme:
Edit `app/Views/layouts/main.php`, cari section `tailwind.config` dan ubah color primary.

### Mengganti Logo:
Edit navbar di `app/Views/layouts/main.php`, section dengan class "text-2xl font-bold".

### Menambah Kategori:
1. Insert langsung ke database, atau
2. Edit file `app/Database/Seeds/CategorySeeder.php` dan jalankan ulang seeder

### Menambah Payment Method:
Edit view `app/Views/pages/donation_form.php`, section payment method.

---

## 🚀 Production Deployment

### Checklist:
- [ ] Set `CI_ENVIRONMENT = production` di `.env`
- [ ] Ganti `app.baseURL` dengan domain production
- [ ] Setup SSL certificate (HTTPS)
- [ ] Setup proper file permissions (755 untuk folder, 644 untuk file)
- [ ] Enable database caching
- [ ] Setup payment gateway (jika perlu)
- [ ] Setup email notification
- [ ] Setup backup database otomatis
- [ ] Test semua fitur di production

---

## 📞 Need Help?

Jika mengalami masalah:
1. Cek file log di `writable/logs/`
2. Enable debug mode di `.env`: `CI_ENVIRONMENT = development`
3. Cek error di browser console (F12)
4. Review dokumentasi CodeIgniter 4

---

**Selamat mencoba! 🎉**
