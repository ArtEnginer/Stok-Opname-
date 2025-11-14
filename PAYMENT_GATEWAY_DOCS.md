# Integrasi Payment Gateway Midtrans

## 📋 Fitur yang Sudah Diimplementasikan

### ✅ 1. Midtrans PHP Library
- ✅ Terinstall via Composer (`midtrans/midtrans-php`)
- ✅ Auto-loaded dalam aplikasi

### ✅ 2. Konfigurasi Midtrans
**File:** `app/Config/Midtrans.php`
- Server Key & Client Key configuration
- Sandbox/Production mode toggle
- Payment notification URLs
- Allowed payment methods (Credit Card, GoPay, ShopeePay, QRIS, VA)
- 3D Secure & Sanitization settings

### ✅ 3. Midtrans Library Helper
**File:** `app/Libraries/MidtransLibrary.php`
**Methods:**
- `createSnapToken()` - Generate payment token
- `buildTransactionParams()` - Build transaction data
- `getStatus()` - Check payment status
- `handleNotification()` - Process webhook
- `verifySignature()` - Validate notification
- `cancel()` - Cancel transaction
- `getPaymentMethodName()` - Format payment method name

### ✅ 4. Payment Controller
**File:** `app/Controllers/PaymentController.php`
**Routes:**
- `/payment/{transaction_id}` - Payment page with Snap UI
- `/payment/notification` - Webhook endpoint (POST)
- `/payment/finish` - Success/pending redirect
- `/payment/unfinish` - User closed payment
- `/payment/error` - Payment failed
- `/payment/check/{transaction_id}` - Check status (AJAX)

### ✅ 5. Donation Controller Updates
**File:** `app/Controllers/DonationController.php`
- Support untuk 2 metode pembayaran:
  - **Midtrans** - Automatic payment gateway
  - **Manual** - Upload bukti transfer
- Validasi berbeda per metode
- Generate Snap Token untuk Midtrans
- Redirect ke halaman pembayaran

### ✅ 6. Database Enhancement
**Tabel:** `donations`
- ✅ Kolom `snap_token` ditambahkan (VARCHAR 255)
- Menyimpan token pembayaran Midtrans

### ✅ 7. Payment Views
**Files Created:**
1. `app/Views/pages/payment.php`
   - Halaman pembayaran dengan Midtrans Snap
   - Loading Snap.js dari CDN
   - Payment popup integration
   
2. `app/Views/pages/payment_finish.php`
   - Success page dengan detail transaksi
   - Display VA number atau bill key
   - Pending payment instructions
   - Share buttons
   
3. `app/Views/pages/payment_unfinish.php`
   - User closed payment popup
   - Continue payment option
   - Transaction still valid for 24h
   
4. `app/Views/pages/payment_error.php`
   - Error handling page
   - Troubleshooting suggestions
   - Retry payment option

### ✅ 8. Donation Form Updates
**File:** `app/Views/pages/donation_form.php`
- Modern payment method cards
- Toggle payment proof untuk manual transfer
- Visual feedback untuk selected method
- Dynamic form validation
- Enhanced UI dengan icons dan styling

## 🔄 Flow Pembayaran

### A. Midtrans Payment Flow
```
1. User isi form donasi → pilih "Midtrans Payment"
2. Submit form → DonationController::process()
3. Generate Snap Token via MidtransLibrary
4. Save donation dengan status "pending"
5. Redirect ke /payment/{transaction_id}
6. Show Snap payment popup
7. User pilih metode & bayar
8. Midtrans kirim notification → /payment/notification
9. Update status donation (verified/rejected)
10. User redirect ke /payment/finish
11. Show success page dengan detail
```

### B. Manual Payment Flow
```
1. User isi form donasi → pilih "Transfer Manual"
2. Upload bukti pembayaran
3. Submit form → DonationController::process()
4. Save donation dengan status "pending"
5. Redirect ke /donate/success/{transaction_id}
6. Admin verifikasi manual di admin panel
7. Status updated ke "verified"
```

## 🎨 Payment Methods Tersedia (Midtrans)

1. **Credit Card** 💳
   - Visa, Mastercard, JCB
   - 3D Secure enabled
   - Installment options

2. **E-Wallet** 📱
   - GoPay
   - ShopeePay
   - Link Aja
   - DANA

3. **QRIS** 🔲
   - Scan & Pay
   - All e-wallets supported

4. **Bank Transfer** 🏦
   - BCA Virtual Account
   - Mandiri Bill Payment
   - BNI Virtual Account
   - BRI Virtual Account
   - Permata Virtual Account
   - Other Banks VA

5. **Convenience Store** 🏪
   - Alfamart
   - Indomaret

## 🔐 Security Features

1. **SSL/TLS Encryption**
   - All data encrypted in transit

2. **PCI-DSS Level 1 Compliance**
   - Midtrans certified payment gateway

3. **3D Secure**
   - Enabled untuk kartu kredit

4. **Signature Verification**
   - Webhook validation
   - Prevent fake notifications

5. **Fraud Detection**
   - Automatic by Midtrans
   - Challenge transactions flagged

## 📊 Notification Handling

**Webhook:** `/payment/notification`

**Transaction Status:**
- `capture` → Payment sukses (credit card)
- `settlement` → Payment completed
- `pending` → Waiting payment (VA/ewallet)
- `deny` → Payment rejected
- `cancel` → Payment cancelled
- `expire` → Payment expired

**Auto Updates:**
- Status donation updated otomatis
- Collected amount updated
- Verification timestamp recorded
- Email notification (coming soon)

## ⚙️ Konfigurasi Midtrans

### Development (Sandbox)
1. Register di https://dashboard.sandbox.midtrans.com
2. Get Server Key & Client Key
3. Update `app/Config/Midtrans.php`:
   ```php
   public string $serverKey = 'SB-Mid-server-xxxxx';
   public string $clientKey = 'SB-Mid-client-xxxxx';
   public bool $isProduction = false;
   ```

### Production (Live)
1. Complete Midtrans business verification
2. Get Production keys dari https://dashboard.midtrans.com
3. Update config:
   ```php
   public string $serverKey = 'Mid-server-xxxxx';
   public string $clientKey = 'Mid-client-xxxxx';
   public bool $isProduction = true;
   ```
4. Setup notification URL di Midtrans Dashboard:
   - `https://yourdomain.com/payment/notification`

### Test Cards (Sandbox)
- **Success:** 4811 1111 1111 1114
- **Challenge:** 4011 1111 1111 1112 (need manual verification)
- **Deny:** 4911 1111 1111 1113
- CVV: 123
- Exp: Any future date

## 🧪 Testing Checklist

### Midtrans Payment
- [ ] Credit card success
- [ ] Credit card challenge
- [ ] Credit card deny
- [ ] GoPay payment
- [ ] ShopeePay payment
- [ ] QRIS payment
- [ ] BCA VA
- [ ] Mandiri Bill
- [ ] BNI VA
- [ ] Payment timeout/expire
- [ ] Close payment popup
- [ ] Notification webhook
- [ ] Status updates

### Manual Payment
- [ ] Upload bukti transfer
- [ ] File validation
- [ ] Pending status
- [ ] Admin verification
- [ ] Status update

## 📱 Mobile Responsive
- ✅ Payment form responsive
- ✅ Snap popup mobile-friendly
- ✅ Status pages optimized
- ✅ Touch-friendly buttons

## 🚀 Next Steps / Enhancement Ideas

### 1. Email Notifications
- [ ] Send receipt after payment
- [ ] Payment reminder (pending)
- [ ] Verification confirmation

### 2. SMS Notifications
- [ ] Payment confirmation via SMS
- [ ] VA number via SMS

### 3. Admin Dashboard
- [ ] Payment statistics
- [ ] Real-time payment monitoring
- [ ] Revenue charts
- [ ] Payment method breakdown

### 4. Donor Features
- [ ] Payment history page
- [ ] Download receipt PDF
- [ ] Recurring donations
- [ ] Donation certificates

### 5. Advanced Features
- [ ] Partial payments / installments
- [ ] Donation goals per payment method
- [ ] Payment method recommendations
- [ ] Auto-refund for failed campaigns

## 📞 Support

**Midtrans:**
- Docs: https://docs.midtrans.com
- API: https://api-docs.midtrans.com
- Support: support@midtrans.com

**Application:**
- Technical issues: Check logs di `writable/logs/`
- Payment errors: Check Midtrans Dashboard
- Webhook issues: Test dengan ngrok untuk local development

## 🎉 Keunggulan Implementasi

1. **Dual Payment Options**
   - Otomatis (Midtrans) untuk kemudahan
   - Manual untuk fleksibilitas

2. **User Experience**
   - Modern UI dengan Tailwind CSS
   - Visual payment method selection
   - Real-time validation
   - Clear status pages

3. **Admin Friendly**
   - Easy verification workflow
   - Automatic updates dari Midtrans
   - Manual override options

4. **Secure & Reliable**
   - Industry-standard security
   - Webhook verification
   - Error handling
   - Transaction logging

5. **Scalable**
   - Support multiple payment methods
   - Easy to add new features
   - Production-ready code
   - Well-documented

---

**Status:** ✅ Ready for Testing
**Version:** 1.0.0
**Last Updated:** November 14, 2025
