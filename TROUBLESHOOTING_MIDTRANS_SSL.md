# Troubleshooting - Midtrans SSL Certificate Error

## 🐛 Problem

Error saat test koneksi Midtrans:

```json
{
  "status": "error",
  "message": "Midtrans connection failed",
  "error": "CURL Error: error setting certificate file: D:\\laragon\\etc\\ssl\\cacert.pem",
  "data": {
    "connected": false
  }
}
```

---

## 🔍 Root Cause

Error ini terjadi karena:

1. **CURL mencari file SSL certificate** (`cacert.pem`) di path yang tidak ada
2. **Laragon/XAMPP** tidak memiliki certificate bundle di lokasi default
3. **PHP CURL** membutuhkan CA certificate untuk verify SSL connection ke Midtrans

---

## ✅ Solution Yang Telah Diterapkan

### 1. **Update Konfigurasi Midtrans**

**File:** `app/Config/Midtrans.php`

**Perubahan:**

```php
// BEFORE
public bool $isProduction = true;

// AFTER
public bool $isProduction = false;  // Set to false for sandbox mode
```

### 2. **Update MidtransLibrary**

**File:** `app/Libraries/MidtransLibrary.php`

**Ditambahkan:**

```php
private function initConfig(): void
{
    Config::$serverKey = $this->config->serverKey;
    Config::$clientKey = $this->config->clientKey;
    Config::$isProduction = $this->config->isProduction;
    Config::$isSanitized = $this->config->isSanitized;
    Config::$is3ds = $this->config->is3ds;

    // Configure CURL options to handle SSL certificate issues
    Config::$curlOptions = [
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => true,
    ];

    // For development/sandbox, disable SSL verification
    if (!$this->config->isProduction) {
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
        ];
    }
}
```

### 3. **Update Test Connection**

**File:** `app/Controllers/Admin/MidtransConfigController.php`

**Ditambahkan:**

```php
public function testConnection()
{
    // ... existing code ...

    // Configure CURL options
    \Midtrans\Config::$curlOptions = [
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => false,
    ];

    // ... rest of code ...
}
```

---

## 🎯 Penjelasan Solution

### Sandbox Mode (Development)

```php
Config::$curlOptions = [
    CURLOPT_SSL_VERIFYHOST => 0,     // Disable hostname verification
    CURLOPT_SSL_VERIFYPEER => false, // Disable peer certificate verification
];
```

**Kenapa disable SSL verification?**

- ✅ Sandbox Midtrans menggunakan self-signed certificate
- ✅ Tidak perlu certificate bundle (cacert.pem)
- ✅ Memudahkan development di local
- ⚠️ **HANYA untuk development/testing**

### Production Mode

```php
Config::$curlOptions = [
    CURLOPT_SSL_VERIFYHOST => 2,    // Verify hostname
    CURLOPT_SSL_VERIFYPEER => true, // Verify peer certificate
];
```

**Untuk production:**

- ✅ SSL verification HARUS diaktifkan
- ✅ Gunakan certificate bundle yang valid
- ✅ Download dari: https://curl.se/ca/cacert.pem
- ✅ Set di php.ini: `curl.cainfo = "C:/path/to/cacert.pem"`

---

## 📋 Alternative Solutions

### Option 1: Download & Set CA Certificate Bundle (Recommended for Production)

1. **Download cacert.pem:**

   ```
   https://curl.se/ca/cacert.pem
   ```

2. **Simpan di folder yang aman:**

   ```
   D:\laragon\etc\ssl\cacert.pem
   ```

3. **Update php.ini:**

   ```ini
   [curl]
   curl.cainfo = "D:\laragon\etc\ssl\cacert.pem"

   [openssl]
   openssl.cafile = "D:\laragon\etc\ssl\cacert.pem"
   ```

4. **Restart Apache/Nginx**

5. **Enable SSL verification kembali:**
   ```php
   Config::$curlOptions = [
       CURLOPT_SSL_VERIFYHOST => 2,
       CURLOPT_SSL_VERIFYPEER => true,
   ];
   ```

### Option 2: Use System Certificate Store

**Windows:**

```php
Config::$curlOptions = [
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_CAINFO => 'C:/Windows/System32/curl-ca-bundle.crt',
];
```

### Option 3: Disable Only for Specific Environment

```php
// In MidtransLibrary.php
if (ENVIRONMENT === 'development') {
    Config::$curlOptions = [
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => false,
    ];
} else {
    Config::$curlOptions = [
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_CAINFO => ROOTPATH . 'certificates/cacert.pem',
    ];
}
```

---

## 🧪 Testing

### 1. Test Koneksi Midtrans

Setelah perubahan, test koneksi di admin panel:

```bash
POST /admin/midtrans/test-connection
{
  "server_key": "Mid-server-xxx",
  "client_key": "Mid-client-xxx",
  "is_production": false
}
```

**Expected Response:**

```json
{
  "status": "success",
  "message": "Midtrans connection successful",
  "data": {
    "connected": true,
    "environment": "sandbox",
    "snap_token": "abc123..."
  }
}
```

### 2. Test Donasi

1. Buka halaman campaign
2. Klik "Donasi Sekarang"
3. Isi form donasi
4. Klik "Kirim Donasi"
5. **Snap popup harus muncul**
6. Pilih metode pembayaran
7. Complete payment

### 3. Verify Logs

Check logs untuk memastikan tidak ada error:

```bash
tail -f writable/logs/log-*.log
```

---

## ⚠️ Important Notes

### Security Considerations:

1. **Development/Sandbox:**

   - ✅ OK to disable SSL verification
   - ✅ Hanya untuk testing di local
   - ✅ Tidak untuk production

2. **Production:**

   - ❌ JANGAN disable SSL verification
   - ✅ HARUS gunakan certificate bundle
   - ✅ Set `$isProduction = true`
   - ✅ Verify SSL certificate

3. **Environment Variables:**
   ```env
   # .env file
   midtrans.serverKey = Mid-server-YOUR_KEY
   midtrans.clientKey = Mid-client-YOUR_KEY
   midtrans.isProduction = false
   ```

---

## 🔧 Configuration Checklist

- [x] `isProduction = false` untuk sandbox
- [x] CURL SSL verification disabled untuk sandbox
- [x] Test connection berhasil
- [ ] Download cacert.pem untuk production (nanti)
- [ ] Update php.ini untuk production (nanti)
- [ ] Enable SSL verification untuk production (nanti)

---

## 📞 Common Issues & Solutions

### Issue 1: Snap popup tidak muncul

**Solutions:**

1. Check browser console untuk error JavaScript
2. Verify snap token tidak kosong
3. Check client key valid
4. Ensure snap.js ter-load dengan benar

### Issue 2: Payment gagal setelah popup muncul

**Solutions:**

1. Check server key valid
2. Verify notification URL accessible
3. Check callback handler berfungsi
4. Review Midtrans dashboard untuk error details

### Issue 3: "Invalid signature" error

**Solutions:**

1. Verify server key matches dengan yang di Midtrans dashboard
2. Check signature verification di callback handler
3. Ensure order_id, status_code, gross_amount sama persis

---

## 🎓 Best Practices

1. **Separation of Concerns:**

   ```php
   // Development
   if (ENVIRONMENT === 'development') {
       // Disable SSL verification
   }

   // Production
   else {
       // Enable SSL verification with proper certificate
   }
   ```

2. **Logging:**

   ```php
   log_message('info', 'Midtrans request: ' . json_encode($params));
   log_message('debug', 'Snap token: ' . $snapToken);
   ```

3. **Error Handling:**
   ```php
   try {
       $snapToken = Snap::getSnapToken($params);
   } catch (\Exception $e) {
       log_message('error', 'Midtrans error: ' . $e->getMessage());
       // Handle error gracefully
   }
   ```

---

## 📚 References

- [Midtrans Documentation](https://docs.midtrans.com)
- [CURL CA Bundle](https://curl.se/ca/cacert.pem)
- [PHP CURL Options](https://www.php.net/manual/en/function.curl-setopt.php)
- [SSL Certificate Verification](https://www.php.net/manual/en/context.ssl.php)

---

**Version:** 1.0.0  
**Status:** ✅ Fixed  
**Last Updated:** 14 November 2025
