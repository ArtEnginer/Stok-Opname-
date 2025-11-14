# 🚀 Quick Start: Setup Payment Gateway Midtrans

## Step 1: Register Midtrans Sandbox (5 menit)

1. Buka https://dashboard.sandbox.midtrans.com/register
2. Isi form registrasi:
   - Email
   - Password
   - Nama Bisnis
3. Verifikasi email
4. Login ke dashboard

## Step 2: Get API Keys (2 menit)

1. Login ke https://dashboard.sandbox.midtrans.com
2. Klik **Settings** → **Access Keys**
3. Copy keys:
   ```
   Server Key: SB-Mid-server-xxxxxxxxx
   Client Key: SB-Mid-client-xxxxxxxxx
   ```

## Step 3: Update Konfigurasi (1 menit)

1. Buka file: `app/Config/Midtrans.php`
2. Replace placeholder dengan keys Anda:
   ```php
   public string $serverKey = 'SB-Mid-server-xxxxxxxxx'; // Paste Server Key
   public string $clientKey = 'SB-Mid-client-xxxxxxxxx'; // Paste Client Key
   public bool $isProduction = false; // Tetap false untuk testing
   ```
3. Save file

## Step 4: Setup Notification URL (3 menit)

### Untuk Local Development (dengan ngrok):

1. Install ngrok: https://ngrok.com/download
2. Jalankan server:
   ```bash
   php spark serve
   ```
3. Di terminal baru, jalankan ngrok:
   ```bash
   ngrok http 8080
   ```
4. Copy URL ngrok (contoh: https://abc123.ngrok.io)
5. Login ke Midtrans Dashboard
6. Klik **Settings** → **Configuration**
7. Isi form:
   - **Payment Notification URL:** `https://abc123.ngrok.io/payment/notification`
   - **Finish Redirect URL:** `https://abc123.ngrok.io/payment/finish`
   - **Unfinish Redirect URL:** `https://abc123.ngrok.io/payment/unfinish`
   - **Error Redirect URL:** `https://abc123.ngrok.io/payment/error`
8. Klik **Update**

### Untuk Production:

1. Ganti dengan domain Anda:
   - **Payment Notification URL:** `https://yourdomain.com/payment/notification`
   - **Finish Redirect URL:** `https://yourdomain.com/payment/finish`
   - **Unfinish Redirect URL:** `https://yourdomain.com/payment/unfinish`
   - **Error Redirect URL:** `https://yourdomain.com/payment/error`

## Step 5: Test Payment (5 menit)

1. Buka aplikasi: http://localhost:8080
2. Pilih campaign
3. Klik "Donasi Sekarang"
4. Isi form donasi
5. Pilih **"Midtrans Payment"**
6. Klik "Kirim Donasi"
7. Halaman pembayaran akan terbuka
8. Klik "Bayar Sekarang"
9. Pilih metode pembayaran

### Test Credit Card (Sandbox):
- **Card Number:** 4811 1111 1111 1114
- **Exp Date:** 01/30 (any future date)
- **CVV:** 123
- **Result:** SUCCESS ✅

### Test GoPay (Sandbox):
1. Pilih GoPay
2. Scan QR code dengan app simulator
3. Atau klik "Bayar" langsung (sandbox auto-success)

### Test BCA VA (Sandbox):
1. Pilih BCA Virtual Account
2. VA Number akan ditampilkan
3. Di sandbox, langsung click "Continue"
4. Payment auto-success

## Step 6: Verifikasi (2 menit)

1. Setelah pembayaran sukses
2. Cek database:
   ```sql
   SELECT * FROM donations ORDER BY created_at DESC LIMIT 1;
   ```
3. Status harus "verified"
4. collected_amount campaign harus bertambah

## 🎯 Checklist Setup

- [ ] Register Midtrans Sandbox account
- [ ] Get Server Key & Client Key
- [ ] Update app/Config/Midtrans.php
- [ ] Setup notification URL (ngrok untuk local)
- [ ] Test payment dengan kartu test
- [ ] Verifikasi status di database
- [ ] Check Midtrans Dashboard untuk transaction history

## 🐛 Troubleshooting

### Error: "Failed to create payment"
- ✅ Check Server Key sudah benar
- ✅ Check internet connection
- ✅ Lihat logs: `writable/logs/log-[date].log`

### Webhook tidak jalan
- ✅ Pastikan ngrok running (untuk local)
- ✅ Check notification URL di Midtrans Dashboard
- ✅ Test dengan Postman: POST ke `/payment/notification`

### Status tidak update otomatis
- ✅ Check notification URL accessible dari internet
- ✅ Lihat logs di `writable/logs/`
- ✅ Check Midtrans Dashboard → Transaction → Notification History

## 📚 Resources

- **Midtrans Docs:** https://docs.midtrans.com
- **API Reference:** https://api-docs.midtrans.com
- **Test Cards:** https://docs.midtrans.com/en/technical-reference/sandbox-test
- **Ngrok Tutorial:** https://ngrok.com/docs/getting-started

## ⏱️ Total Time: ~20 menit

Selamat! Payment gateway Midtrans sudah siap digunakan! 🎉

---

**Tips Pro:**
- Gunakan Postman untuk test webhook
- Monitor Midtrans Dashboard untuk debugging
- Save ngrok URL untuk development
- Backup Server Key dengan aman
- Never commit API keys to git
