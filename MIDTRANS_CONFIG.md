# Midtrans Configuration Guide
# =============================
# 
# 1. Register/Login to Midtrans:
#    - Sandbox: https://dashboard.sandbox.midtrans.com
#    - Production: https://dashboard.midtrans.com
#
# 2. Get Your Credentials:
#    - Go to Settings > Access Keys
#    - Copy Server Key and Client Key
#
# 3. Update Configuration:
#    - Open app/Config/Midtrans.php
#    - Replace YOUR-SERVER-KEY with your Midtrans Server Key
#    - Replace YOUR-CLIENT-KEY with your Midtrans Client Key
#    - Set isProduction = true when going live
#
# 4. Configure Notification URL in Midtrans Dashboard:
#    - Go to Settings > Configuration
#    - Payment Notification URL: https://yourdomain.com/payment/notification
#    - Finish Redirect URL: https://yourdomain.com/payment/finish
#    - Unfinish Redirect URL: https://yourdomain.com/payment/unfinish
#    - Error Redirect URL: https://yourdomain.com/payment/error
#
# 5. Test Payment (Sandbox):
#    Use these test cards:
#    - Success: 4811 1111 1111 1114
#    - Challenge: 4011 1111 1111 1112
#    - Deny: 4911 1111 1111 1113
#    CVV: 123, Exp: any future date
#
# Important Notes:
# ================
# - NEVER commit real credentials to git
# - Always use Sandbox for development
# - Test all payment methods before going live
# - Enable 3D Secure for credit card payments
# - Monitor transactions in Midtrans Dashboard
# - Setup proper notification handling
#
# Support:
# ========
# Midtrans Documentation: https://docs.midtrans.com
# API Reference: https://api-docs.midtrans.com
