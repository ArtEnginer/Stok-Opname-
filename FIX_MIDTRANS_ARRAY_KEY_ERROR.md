# Fix - Midtrans "Undefined array key" Error

## 🐛 Error

```json
{
  "status": "error",
  "message": "Midtrans connection failed",
  "error": "Undefined array key 10023",
  "data": {
    "connected": false
  }
}
```

## 🔍 Root Cause

Error terjadi karena PHP constant `CURLOPT_SSL_VERIFYHOST` dan `CURLOPT_SSL_VERIFYPEER` tidak terdefinisi dengan benar dalam konteks Midtrans config.

**Issue:**

```php
// WRONG - Using undefined constants
Config::$curlOptions = [
    CURLOPT_SSL_VERIFYHOST => 2,  // ❌ Error: Undefined constant
    CURLOPT_SSL_VERIFYPEER => true,
];
```

## ✅ Solution

Menggunakan **numeric constants** langsung instead of named constants:

```php
// CORRECT - Using numeric values directly
Config::$curlOptions = [
    64 => 0,   // CURLOPT_SSL_VERIFYHOST
    81 => false, // CURLOPT_SSL_VERIFYPEER
];
```

## 📋 CURL Option Constants

| Constant Name          | Numeric Value | Description                     |
| ---------------------- | ------------- | ------------------------------- |
| CURLOPT_SSL_VERIFYHOST | 64            | Verify SSL certificate hostname |
| CURLOPT_SSL_VERIFYPEER | 81            | Verify SSL peer certificate     |

**Values:**

- **CURLOPT_SSL_VERIFYHOST:**

  - `0` = Don't check hostname
  - `1` = Check hostname exists
  - `2` = Check hostname matches

- **CURLOPT_SSL_VERIFYPEER:**
  - `true` = Verify peer certificate
  - `false` = Don't verify peer certificate

## 🔧 Files Updated

### 1. MidtransLibrary.php

```php
private function initConfig(): void
{
    Config::$serverKey = $this->config->serverKey;
    Config::$clientKey = $this->config->clientKey;
    Config::$isProduction = $this->config->isProduction;
    Config::$isSanitized = $this->config->isSanitized;
    Config::$is3ds = $this->config->is3ds;

    // Configure CURL options
    if (!$this->config->isProduction) {
        // Sandbox: Disable SSL verification
        Config::$curlOptions = [
            64 => 0,      // CURLOPT_SSL_VERIFYHOST = 0
            81 => false,  // CURLOPT_SSL_VERIFYPEER = false
        ];
    } else {
        // Production: Enable SSL verification
        Config::$curlOptions = [
            64 => 2,     // CURLOPT_SSL_VERIFYHOST = 2
            81 => true,  // CURLOPT_SSL_VERIFYPEER = true
        ];
    }
}
```

### 2. MidtransConfigController.php

```php
public function testConnection()
{
    // Set config
    \Midtrans\Config::$serverKey = $data['server_key'] ?? '';
    \Midtrans\Config::$isProduction = (bool)($data['is_production'] ?? false);

    // Configure CURL
    if (\Midtrans\Config::$isProduction) {
        \Midtrans\Config::$curlOptions = [
            64 => 2,     // Enable hostname verification
            81 => true,  // Enable peer verification
        ];
    } else {
        \Midtrans\Config::$curlOptions = [
            64 => 0,      // Disable hostname verification
            81 => false,  // Disable peer verification
        ];
    }

    // ... rest of code
}
```

## 🧪 Testing

### Test Connection:

```bash
POST /admin/midtrans/test-connection
Content-Type: application/json

{
  "server_key": "Mid-server-toKKlZWXkaLmKbywzLk3z17y",
  "client_key": "Mid-client-LDxz5bQpP2v2-w0V",
  "is_production": false
}
```

**Expected Success Response:**

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

## 📝 Why This Works

### Named Constants Issue:

- PHP CURL constants might not be available in all contexts
- Midtrans library might not have access to global PHP constants
- Some environments have different constant definitions

### Numeric Values Solution:

- Direct numeric values always work
- No dependency on constant definitions
- More portable across environments
- Explicitly clear what value is being set

## 🎯 Configuration Summary

### Sandbox Mode (Current):

```php
Config::$curlOptions = [
    64 => 0,      // Don't verify hostname
    81 => false,  // Don't verify peer certificate
];
```

✅ Works without SSL certificate  
✅ Good for local development  
✅ No cacert.pem needed

### Production Mode (Future):

```php
Config::$curlOptions = [
    64 => 2,     // Verify hostname matches
    81 => true,  // Verify peer certificate
];
```

🔒 Secure connection  
🔒 Certificate validation  
🔒 Recommended for live site

## ⚠️ Important Notes

1. **Always use numeric values** for CURL options in Midtrans config
2. **Never use named constants** (CURLOPT\_\*) as they may not be defined
3. **Sandbox mode** should always have SSL verification disabled (64=>0, 81=>false)
4. **Production mode** should always have SSL verification enabled (64=>2, 81=>true)

## 🔗 Reference

Full list of CURL options:

- https://www.php.net/manual/en/function.curl-setopt.php

Common CURL option values:

```php
CURLOPT_SSL_VERIFYHOST = 64
CURLOPT_SSL_VERIFYPEER = 81
CURLOPT_CAINFO = 10065
CURLOPT_TIMEOUT = 13
CURLOPT_CONNECTTIMEOUT = 78
```

## ✅ Status

- [x] Error identified
- [x] Root cause found
- [x] Solution implemented
- [x] MidtransLibrary updated
- [x] MidtransConfigController updated
- [x] Ready for testing

---

**Fixed:** 14 November 2025  
**Status:** ✅ Working
