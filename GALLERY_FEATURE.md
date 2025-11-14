# Dokumentasi Fitur Galeri Campaign

## Perubahan yang Dibuat

### 1. Title Campaign Menjadi Clickable di Form Donasi

- Title campaign di halaman form donasi sekarang dapat diklik
- Ketika diklik, akan mengarah ke halaman detail campaign
- Efek hover yang elegan dengan perubahan warna dan ikon external link

### 2. Galeri Foto Campaign

#### A. Di Halaman Form Donasi (donation_form.php)

**Fitur:**

- Tampilan galeri kecil (w-48 x h-32)
- Slider foto dengan tombol navigasi kiri-kanan
- Counter foto (menampilkan foto ke-berapa dari total foto)
- Thumbnail navigation (thumbnail foto di bawah gambar utama)
- Support untuk single image atau multiple images
- Smooth transition saat berpindah foto

**Cara Kerja:**

- Foto utama (dari field `image`) digabung dengan foto tambahan (dari field `images` JSON)
- JavaScript function `changeImageSmall()` untuk navigasi next/prev
- JavaScript function `goToImageSmall()` untuk klik thumbnail langsung

#### B. Di Halaman Detail Campaign (campaign_detail.php)

**Fitur:**

- Tampilan galeri besar (w-full x h-500px)
- Slider foto dengan tombol navigasi yang lebih besar
- Counter foto dengan ikon
- Tombol "Lihat Penuh" untuk fullscreen mode
- Grid thumbnail 5 kolom dengan border highlight pada foto aktif
- Label "Utama" pada foto pertama
- Hover effect pada thumbnail

**Fullscreen Mode:**

- Modal overlay dengan background blur
- Navigasi dengan tombol kiri-kanan atau keyboard arrow keys
- Tombol close (X) atau tekan ESC untuk keluar
- Counter foto di bagian bawah
- Gambar ditampilkan maksimal dengan aspect ratio terjaga
- Animasi smooth saat berpindah foto

**Keyboard Navigation:**

- Arrow Left/Right: Berpindah foto
- Escape: Keluar dari fullscreen mode

### 3. Database Structure

Field `images` di tabel `campaigns`:

```sql
'images' => [
    'type' => 'TEXT',
    'null' => true,
    'comment' => 'JSON array of additional images',
]
```

Format data: `["image1.jpg", "image2.jpg", "image3.jpg"]`

### 4. File CSS Baru

**File:** `public/css/campaign-gallery.css`

- Styling untuk galeri kecil dan besar
- Animasi dan transitions
- Fullscreen modal styling
- Responsive design (mobile-friendly)
- Touch device optimizations
- Accessibility features (focus states)
- Print styles

### 5. JavaScript Functions

#### Donation Form (Small Gallery):

- `changeImageSmall(direction)` - Navigasi foto next/prev
- `goToImageSmall(index)` - Jump ke foto tertentu

#### Campaign Detail (Main Gallery):

- `changeMainImage(direction)` - Navigasi foto next/prev di galeri utama
- `goToMainImage(index)` - Jump ke foto tertentu via thumbnail
- `openFullscreen()` - Buka mode fullscreen
- `closeFullscreen()` - Tutup mode fullscreen
- `changeFullscreenImage(direction)` - Navigasi foto di mode fullscreen
- `updateFullscreenCounter()` - Update counter di fullscreen

### 6. Responsive Design

- Desktop: Grid 5 kolom thumbnail, tinggi 500px
- Tablet: Grid responsif, tinggi 300px
- Mobile: Thumbnail scrollable, tinggi 250px
- Touch device: Optimasi untuk sentuhan, non-hover effects

### 7. Contoh Penggunaan

#### Upload Multiple Images (di Admin)

Ketika membuat/edit campaign, pastikan field `images` diisi dengan JSON array:

```php
$campaign['images'] = json_encode(['photo1.jpg', 'photo2.jpg', 'photo3.jpg']);
```

#### Di View

Galeri akan otomatis detect dan tampilkan semua foto:

```php
$additionalImages = !empty($campaign['images']) ? json_decode($campaign['images'], true) : [];
$allImages = array_merge([$campaign['image']], $additionalImages);
```

### 8. Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- IE11+ (dengan fallback untuk beberapa fitur)
- Mobile browsers (iOS Safari, Chrome Mobile)

### 9. Performance

- Lazy loading untuk thumbnail
- Smooth animations dengan CSS transitions
- Optimized image loading
- Minimal JavaScript overhead

### 10. Accessibility

- Keyboard navigation support
- Focus states untuk semua interactive elements
- Alt text untuk semua gambar
- Screen reader friendly

## Cara Testing

1. **Buka halaman campaign detail**: `/campaign/{slug}`

   - Lihat galeri foto besar dengan thumbnail
   - Klik thumbnail untuk pindah foto
   - Klik "Lihat Penuh" untuk fullscreen
   - Test keyboard navigation (arrow keys, escape)

2. **Buka form donasi**: `/donate/{slug}`

   - Klik title campaign untuk ke detail
   - Lihat galeri foto kecil dengan thumbnail
   - Test navigasi foto dengan arrow buttons

3. **Test Responsive**:
   - Resize browser ke ukuran mobile
   - Test di device mobile sebenarnya
   - Pastikan thumbnail scrollable di mobile

## Notes

- Pastikan folder `uploads/campaigns/` memiliki permission yang benar
- Foto tambahan harus disimpan di folder yang sama dengan foto utama
- Format JSON untuk field `images` harus valid array
- Gunakan cache busting (`?v=<?= time() ?>`) untuk CSS saat development
