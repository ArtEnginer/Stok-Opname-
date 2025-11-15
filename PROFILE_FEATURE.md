# Fitur Edit Profile User

## Fitur yang Sudah Dibuat

### 1. **ProfileController** (`app/Controllers/Admin/ProfileController.php`)
Controller untuk mengelola profile user dengan 4 method utama:

#### Methods:
- **`index()`** - Menampilkan halaman profile
- **`update()`** - Update username dan nama lengkap
- **`updateEmail()`** - Update email user
- **`updatePassword()`** - Update password dengan validasi password lama

#### Keamanan:
✅ Validasi password lama sebelum update password baru
✅ Validasi unique untuk username dan email
✅ Menggunakan Shield's password hashing
✅ Check password current menggunakan `service('passwords')->verify()`

### 2. **View Profile** (`app/Views/pages/admin/profile/index.php`)
Halaman profile dengan 3 form terpisah:

#### Sections:
1. **Profile Overview Card** (Kiri)
   - Avatar dengan initial username
   - Username & email display
   - Status aktif/non-aktif
   - Role user
   - Tanggal bergabung

2. **Update Profile Form**
   - Username (wajib, unique)
   - Nama Lengkap (opsional)

3. **Update Email Form**
   - Email baru (wajib, unique, valid email)

4. **Update Password Form**
   - Password saat ini (wajib)
   - Password baru (minimal 4 karakter)
   - Konfirmasi password baru

### 3. **Routes** (`app/Config/Routes.php`)
Routes yang ditambahkan:

```php
$routes->group('admin/profile', function($routes) {
    $routes->get('', 'ProfileController::index');
    $routes->post('update', 'ProfileController::update');
    $routes->post('update-email', 'ProfileController::updateEmail');
    $routes->post('update-password', 'ProfileController::updatePassword');
});
```

### 4. **Sidebar Menu** (`app/Views/layouts/admin.php`)
Ditambahkan menu:
- **Profile Saya** - Link ke halaman profile
- **Logout** - Link logout dengan style merah
- Nama aplikasi dinamis dari database

## Cara Menggunakan

### Akses Halaman Profile:
1. Login sebagai admin
2. Klik menu **"Profile Saya"** di sidebar
3. Atau akses langsung: `http://localhost:8080/admin/profile`

### Update Profile:
1. Ubah **Username** atau **Nama Lengkap**
2. Klik tombol **"Simpan Perubahan"**
3. Success message akan muncul jika berhasil

### Update Email:
1. Masukkan **Email Baru**
2. Klik tombol **"Ubah Email"**
3. Email akan divalidasi (format & unique)

### Update Password:
1. Masukkan **Password Saat Ini**
2. Masukkan **Password Baru** (min 4 karakter)
3. Masukkan **Konfirmasi Password Baru**
4. Klik tombol **"Ubah Password"**
5. Password lama akan diverifikasi terlebih dahulu

## Validasi

### Username:
- ✅ Required
- ✅ Min 3 karakter, max 30 karakter
- ✅ Hanya huruf, angka, dan titik
- ✅ Unique (kecuali user sendiri)

### Email:
- ✅ Required
- ✅ Format email valid
- ✅ Unique (kecuali user sendiri)

### Password:
- ✅ Password lama harus benar
- ✅ Password baru min 4 karakter
- ✅ Konfirmasi password harus cocok
- ✅ Auto-hashed menggunakan Shield

## Integrasi dengan Shield

### User Provider:
```php
$this->userProvider = auth()->getProvider();
$user = $this->userProvider->findById($userId);
```

### Email Identity:
```php
$identity = $user->getEmailIdentity();
$identity->secret = $newEmail; // Update email
$identity->save();
```

### Password Update:
```php
$user->password = $newPassword; // Auto hashed by Shield
$this->userProvider->save($user);
```

### Password Verification:
```php
service('passwords')->verify($currentPassword, $identity->secret2);
```

## Error Handling

- ✅ User tidak ditemukan
- ✅ Password lama salah
- ✅ Validasi field gagal
- ✅ Username/Email sudah digunakan
- ✅ Password confirmation tidak cocok

## UI/UX Features

- ✅ Responsive design (mobile & desktop)
- ✅ Avatar dengan initial username
- ✅ Color-coded status badge
- ✅ Form terpisah untuk setiap fungsi
- ✅ Icons untuk setiap field
- ✅ Success/Error messages
- ✅ Validation error messages per field
- ✅ Tailwind CSS styling

## Testing

Server sudah running di: `http://localhost:8080`

### Test Cases:
1. ✅ Akses halaman profile
2. ✅ Update username
3. ✅ Update nama lengkap
4. ✅ Update email
5. ✅ Update password dengan password lama benar
6. ✅ Coba update password dengan password lama salah
7. ✅ Validasi unique username & email
8. ✅ Validasi password confirmation

## Notes

⚠️ **Penting:**
- Password minimal 4 karakter (bisa diubah di `app/Config/Auth.php`)
- Email harus unique di seluruh sistem
- Username hanya boleh huruf, angka, dan titik
- Password lama WAJIB benar untuk update password
- Semua form menggunakan CSRF protection

🎉 **Fitur Siap Digunakan!**
