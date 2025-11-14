# Perbaikan Dialog Midtrans Payment

## Perubahan yang Dilakukan:

### 1. **app/Views/pages/payment.php**
✅ Menambahkan error handling dan debugging
✅ Hardcode URL snap.js ke sandbox
✅ Menambahkan validasi snap token sebelum memanggil snap.pay()
✅ Menambahkan console.log untuk debugging
✅ Menambahkan try-catch untuk menangkap error
✅ Menambahkan debug info box (development mode)
✅ Menonaktifkan auto-redirect saat close popup

### 2. **app/Controllers/PaymentController.php**
✅ Menambahkan logging untuk debugging
✅ Validasi snap_token sebelum render view
✅ Validasi campaign exists
✅ Error message yang lebih informatif

### 3. **app/Controllers/DonationController.php**
✅ Menambahkan extensive logging
✅ Validasi snap token tidak kosong
✅ Verify snap token tersimpan di database
✅ Error handling yang lebih baik
✅ donor_phone dibuat optional

## Cara Testing:

1. **Buka Browser Console** (F12)
2. **Akses Homepage**: http://localhost:8080
3. **Pilih Campaign** dan klik "Donasi Sekarang"
4. **Isi Form Donasi**:
   - Nominal: 50000 (minimal Rp 10.000)
   - Nama: Test User
   - Email: test@example.com
   - Phone: (kosongkan atau isi)
   - Pilih "Midtrans Payment"
5. **Klik "Kirim Donasi"**
6. **Cek Console untuk melihat**:
   - Snap Token logged
   - Client Key logged
   - Error messages (jika ada)

## Kemungkinan Masalah:

### Jika Snap Token NULL/Empty:
- Cek Midtrans credentials di `app/Config/Midtrans.php`
- Server Key dan Client Key harus valid
- Cek log di `writable/logs/log-YYYY-MM-DD.log`

### Jika Snap Library tidak load:
- Cek koneksi internet
- URL snap.js harus accessible
- Browser console akan show error

### Jika Dialog tidak muncul tapi tidak ada error:
- Cek browser popup blocker
- Cek browser console untuk JavaScript errors
- Snap token harus valid dari Midtrans

## Debug Commands:

```powershell
# Cek log terbaru
Get-Content writable/logs/log-*.log | Select-Object -Last 100

# Cek database donations
# Di MySQL/PHPMyAdmin:
SELECT id, transaction_id, donor_name, amount, payment_method, snap_token, status 
FROM donations 
ORDER BY created_at DESC 
LIMIT 5;

# Cek apakah snap_token field ada
DESCRIBE donations;
```

## Midtrans Test Credentials (Sandbox):

**Credit Card:**
- Card: 4811 1111 1111 1114
- CVV: 123
- Exp: 01/25
- OTP: 112233

**GoPay:**
- No perlu credential, langsung success di sandbox

**Virtual Account:**
- Generate otomatis, langsung success setelah beberapa detik

## Next Steps:

Jika masih tidak muncul, cek:
1. Browser console untuk error JavaScript
2. Network tab untuk request ke Midtrans
3. Log files untuk error dari backend
4. Database untuk memastikan snap_token tersimpan
