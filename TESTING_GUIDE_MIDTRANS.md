# 🚀 Quick Guide - Testing Midtrans Integration

## ✅ Pre-requisites

1. ✅ Midtrans account (sandbox)
2. ✅ Server Key & Client Key dari dashboard
3. ✅ `isProduction = false` di Config
4. ✅ SSL verification disabled untuk sandbox

---

## 🔧 Step-by-Step Testing

### Step 1: Verify Configuration

**File:** `app/Config/Midtrans.php`

```php
public string $serverKey = 'Mid-server-YOUR_SANDBOX_KEY';
public string $clientKey = 'Mid-client-YOUR_SANDBOX_KEY';
public bool $isProduction = false;  // ✅ MUST be false for sandbox
```

### Step 2: Test Connection

**Via Admin Panel:**

1. Login ke admin
2. Go to Settings > Midtrans Configuration
3. Click "Test Connection"
4. Should show: ✅ "Midtrans connection successful"

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

### Step 3: Test Donation Flow

1. **Go to Campaign Page**

   ```
   http://localhost:8080/campaign/your-campaign-slug
   ```

2. **Click "Donasi Sekarang"**

3. **Fill Donation Form:**

   - Amount: Rp 50.000 (or any amount ≥ 10.000)
   - Name: Your Name
   - Email: your@email.com
   - Phone: 08123456789 (optional)
   - Message: Optional

4. **Click "Kirim Donasi"**

5. **Verify Redirect:**

   - Should redirect to `/payment/{transaction_id}`
   - URL should be: `http://localhost:8080/payment/DON-xxxxxx`

6. **Check Payment Page:**
   - ✅ Campaign info displayed
   - ✅ Transaction ID shown
   - ✅ Amount correct
   - ✅ "Bayar Sekarang" button visible

### Step 4: Test Snap Popup

1. **Click "Bayar Sekarang" button**

2. **Verify Snap Popup:**

   - ✅ Popup should appear (not redirect to new page)
   - ✅ Should show payment methods
   - ✅ Should show transaction amount

3. **If Popup DOESN'T appear:**
   - Open browser console (F12)
   - Check for errors
   - Verify snap token in console log

### Step 5: Test Payment Methods

**Test with Virtual Account (BCA):**

1. Select "Bank Transfer"
2. Choose "BCA Virtual Account"
3. Click "Continue"
4. Copy VA number (for sandbox, any VA will work)
5. In sandbox, you can use simulator

**Test with GoPay:**

1. Select "GoPay"
2. Scan QR code (in sandbox, will simulate success)

**Test with Credit Card:**

1. Select "Credit Card"
2. Use test card numbers from Midtrans docs:
   ```
   Card Number: 4811 1111 1111 1114
   CVV: 123
   Exp: 01/25
   ```

### Step 6: Complete Payment (Sandbox)

**Using Midtrans Sandbox Simulator:**

1. Go to Midtrans Dashboard
2. Click on transaction
3. Click "Action" > "Set Status"
4. Choose status:
   - **Settlement** = Payment success
   - **Pending** = Waiting payment
   - **Deny** = Payment rejected

**Or use callback simulator:**

```bash
curl -X POST http://localhost:8080/payment/notification \
-H "Content-Type: application/json" \
-d '{
  "transaction_status": "settlement",
  "order_id": "DON-xxxxxx",
  "gross_amount": "50000.00",
  "payment_type": "bank_transfer",
  "transaction_time": "2025-11-14 10:00:00",
  "transaction_id": "xxx-xxx-xxx",
  "status_code": "200",
  "signature_key": "generated_signature"
}'
```

---

## 🎯 Expected Results

### After Successful Payment:

1. **Donation Status:**

   - Status changes from `pending` to `verified`
   - Check di database: `donations` table

2. **Campaign Updated:**

   - `collected_amount` increased
   - `donor_count` increased

3. **Email Notification:**

   - Donor receives confirmation email
   - Admin receives notification (optional)

4. **Redirect:**
   - User redirected to `/payment/finish`
   - Shows success message

---

## 🐛 Debugging Guide

### Issue 1: Snap Popup Tidak Muncul

**Check Console Logs:**

```javascript
// Should see in console:
"Snap Token: abc123...";
"Client Key: Mid-client-xxx";
```

**Common Causes:**

- ❌ Snap token empty/null
- ❌ Client key salah
- ❌ snap.js tidak ter-load
- ❌ JavaScript error

**Fix:**

```php
// Check in PaymentController
log_message('info', 'Snap Token: ' . $donation['snap_token']);
log_message('info', 'Client Key: ' . $clientKey);
```

### Issue 2: Connection Failed

**Check:**

1. SSL verification settings
2. Server key valid
3. Internet connection
4. Firewall/antivirus blocking

**Verify in logs:**

```bash
tail -f writable/logs/log-*.log | grep Midtrans
```

### Issue 3: Payment Not Processing

**Check:**

1. Notification URL accessible
2. Callback handler working
3. Signature verification correct

**Test notification endpoint:**

```bash
curl http://localhost:8080/payment/notification
```

---

## 📊 Testing Checklist

### Frontend Testing:

- [ ] Campaign page loads
- [ ] Donation form submits
- [ ] Redirect to payment page
- [ ] Snap popup appears
- [ ] Payment methods displayed
- [ ] Success page shows

### Backend Testing:

- [ ] Donation record created
- [ ] Snap token generated
- [ ] Callback received
- [ ] Status updated
- [ ] Campaign amount updated
- [ ] Email sent

### Security Testing:

- [ ] Signature verified
- [ ] SQL injection protected
- [ ] XSS protected
- [ ] CSRF token validated

---

## 🎓 Test Cases

### Test Case 1: Successful Donation

```
Input:
- Amount: 50000
- Payment: Virtual Account BCA
- Status: Settlement

Expected:
- Donation status: verified
- Campaign amount: +50000
- Email: sent
```

### Test Case 2: Pending Payment

```
Input:
- Amount: 100000
- Payment: Virtual Account BNI
- Status: Pending

Expected:
- Donation status: pending
- Campaign amount: unchanged
- Email: not sent yet
```

### Test Case 3: Failed Payment

```
Input:
- Amount: 75000
- Payment: Credit Card
- Status: Deny

Expected:
- Donation status: failed
- Campaign amount: unchanged
- Email: failure notification
```

---

## 📞 Useful Commands

### Check Logs:

```bash
# All logs
tail -f writable/logs/log-*.log

# Only Midtrans
tail -f writable/logs/log-*.log | grep Midtrans

# Only errors
tail -f writable/logs/log-*.log | grep ERROR
```

### Database Queries:

```sql
-- Check last donation
SELECT * FROM donations
ORDER BY created_at DESC
LIMIT 1;

-- Check donation by transaction ID
SELECT * FROM donations
WHERE transaction_id = 'DON-xxxxxx';

-- Check campaign collected amount
SELECT id, title, collected_amount, donor_count
FROM campaigns
WHERE id = 1;
```

### Clear Cache:

```bash
php spark cache:clear
```

---

## 🎉 Success Indicators

✅ **Everything Working:**

1. Test connection: ✅ Success
2. Donation form: ✅ Submits
3. Payment page: ✅ Loads
4. Snap popup: ✅ Appears
5. Payment: ✅ Processed
6. Status: ✅ Updated
7. Email: ✅ Sent

---

## 📝 Notes

- **Sandbox Mode**: Use test credentials, tidak pakai uang asli
- **Production**: Ganti ke production keys & enable SSL verification
- **Testing**: Gunakan Midtrans simulator untuk simulate berbagai scenario
- **Logs**: Always check logs untuk debug

---

**Happy Testing! 🚀**
