# Solusi Alternatif - Midtrans SSL Error

## 🔧 Solusi Paling Simple & Efektif

Karena error terus muncul, kita akan menggunakan pendekatan yang lebih sederhana:

### Disable SSL Verification di php.ini (Untuk Development)

**File:** `php.ini`

Tambahkan atau uncomment:

```ini
[curl]
; Disable SSL verification untuk development
curl.cainfo=""

[openssl]
openssl.cafile=""
```

**Atau set di runtime:**

```php
// Di awal file bootstrap atau index.php
ini_set('curl.cainfo', '');
ini_set('openssl.cafile', '');
```

## 🎯 Update Code dengan Pendekatan Minimal

### File: app/Libraries/MidtransLibrary.php

```php
private function initConfig(): void
{
    Config::$serverKey = $this->config->serverKey;
    Config::$clientKey = $this->config->clientKey;
    Config::$isProduction = $this->config->isProduction;
    Config::$isSanitized = $this->config->isSanitized;
    Config::$is3ds = $this->config->is3ds;

    // Set empty array untuk curlOptions (penting!)
    Config::$curlOptions = [];
}
```

### File: app/Controllers/Admin/MidtransConfigController.php

```php
public function testConnection()
{
    try {
        $data = $this->request->getJSON(true);

        // Set config
        \Midtrans\Config::$serverKey = $data['server_key'] ?? '';
        \Midtrans\Config::$isProduction = (bool)($data['is_production'] ?? false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // IMPORTANT: Set empty array untuk curlOptions
        \Midtrans\Config::$curlOptions = [];

        // Test params...
    }
}
```

## 🚀 Cara Paling Cepat Fix

### Option 1: Update php.ini

1. Cari file `php.ini` Anda (biasanya di `D:\laragon\bin\php\php-x.x.x\php.ini`)

2. Cari section `[curl]` dan set:

   ```ini
   [curl]
   curl.cainfo = ""
   ```

3. Restart Apache/Nginx

4. Test lagi

### Option 2: Set di Bootstrap

**File:** `public/index.php`

Tambahkan setelah `require` bootstrap:

```php
// After: $app = require realpath($bootstrap) ?: $bootstrap;

// Disable SSL verification for Midtrans in development
if (ENVIRONMENT === 'development') {
    ini_set('curl.cainfo', '');
    ini_set('openssl.cafile', '');
}

$app->run();
```

### Option 3: Custom CURL Handler (Advanced)

Buat file baru: `app/Libraries/MidtransCurl.php`

```php
<?php

namespace App\Libraries;

class MidtransCurl
{
    public static function exec($ch)
    {
        // Disable SSL verification for all Midtrans requests
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        return curl_exec($ch);
    }
}
```

## 📝 Quick Test

Setelah update, test dengan:

```bash
php -r "echo ini_get('curl.cainfo');"
```

Jika hasilnya kosong atau path yang tidak ada, bagus!

## ⚡ Immediate Fix

Tambahkan ini di `app/Config/Boot/development.php`:

```php
<?php

/*
 * Environment-specific settings for development
 */

// Disable SSL verification for development (Midtrans sandbox)
ini_set('curl.cainfo', '');
ini_set('openssl.cafile', '');
ini_set('display_errors', '1');
error_reporting(E_ALL);
```

Restart server dan test lagi!

---

**Status:** Testing Required
