# Multiple Location Stock Opname - Dokumentasi

## Overview
Sistem stock opname sekarang mendukung penghitungan item yang sama di **multiple locations** dalam satu session. Ini berguna ketika satu produk tersimpan di beberapa lokasi gudang yang berbeda.

## Fitur Utama

### 1. **Item Bisa Dihitung Lebih dari 1 Kali**
- Satu product dapat dihitung di berbagai lokasi dalam session yang sama
- Setiap lokasi memiliki entry tersendiri di database
- Physical stock final = **SUM dari semua lokasi**

### 2. **Status Tracking Per Lokasi**
Saat search item di batch input, sistem akan menampilkan:
- ✅ **"Sudah dihitung di lokasi ini"** - Item sudah counted di location yang sedang diinput (highlight kuning)
- ℹ️ **"Sudah dihitung di N lokasi lain"** - Item sudah counted di lokasi berbeda (badge biru)
- Item tetap bisa ditambahkan meskipun sudah counted di lokasi lain

### 3. **Automatic Handling**
- **Update existing**: Jika item sudah counted di lokasi yang sama, akan di-update
- **Create new entry**: Jika item sudah counted di lokasi lain, akan membuat entry baru

## Cara Penggunaan

### A. Input Stock Opname dengan Multiple Locations

1. **Buka Batch Input** dari halaman detail SO
   ```
   Stock Opname > [Session] > Batch Input
   ```

2. **Pilih Lokasi** dari master locations
   - Pastikan lokasi dipilih sebelum search item
   - Location ID akan digunakan untuk track status counted

3. **Search & Add Items**
   - Ketik code/PLU/name produk
   - Sistem akan show status:
     - Jika belum dihitung: tampil normal
     - Jika sudah dihitung di lokasi ini: background kuning
     - Jika sudah dihitung di lokasi lain: badge biru dengan info lokasi

4. **Input Physical Stock**
   - Masukkan qty yang dihitung di lokasi tersebut
   - Bukan total dari semua lokasi, tapi qty spesifik di lokasi ini

5. **Save**
   - Semua items akan disimpan dengan location_id
   - Jika ada item yang sudah counted di lokasi lain, akan create entry baru

### B. Workflow Multi-Lokasi

#### Contoh: Produk "Susu UHT 1L" ada di 3 lokasi

1. **Lokasi: Gudang Utama**
   ```
   - Search: "Susu UHT"
   - Physical Stock: 100 pcs
   - Save
   ```

2. **Lokasi: Gudang Cabang A**
   ```
   - Search: "Susu UHT"
   - Status: "Sudah dihitung di 1 lokasi lain" (Gudang Utama)
   - Physical Stock: 50 pcs
   - Save → Create new entry
   ```

3. **Lokasi: Display Toko**
   ```
   - Search: "Susu UHT"
   - Status: "Sudah dihitung di 2 lokasi lain"
   - Physical Stock: 20 pcs
   - Save → Create new entry
   ```

**Total Physical Stock = 100 + 50 + 20 = 170 pcs**

### C. View Items di Show Page

Ada 2 mode view:

1. **Detailed View (Default)** - Show semua entries per location
   ```
   Product A | Lokasi: Gudang Utama    | Qty: 100
   Product A | Lokasi: Gudang Cabang A | Qty: 50
   Product A | Lokasi: Display Toko    | Qty: 20
   ```

2. **Grouped View (Optional)** - Group by product dengan sum qty
   ```
   Product A | Total Physical: 170 | Locations: 3
             | Gudang Utama, Gudang Cabang A, Display Toko
   ```

### D. Close Session

Saat close session:
1. Sistem akan **aggregate physical stock** dari semua locations per product
2. **Total Physical Stock** = SUM(physical_stock) WHERE product_id sama
3. Update system stock dengan total tersebut
4. Apply mutation adjustment jika ada

## Database Structure

### Perubahan Schema

```sql
-- Tidak ada unique constraint pada (session_id, product_id)
-- Memungkinkan multiple rows untuk product yang sama dengan location berbeda

stock_opname_items:
  - id (PK)
  - session_id
  - product_id
  - location_id         -- Nullable, bisa berbeda untuk product yang sama
  - physical_stock      -- Qty di lokasi ini saja
  - baseline_stock
  - difference
  - is_counted
  - counted_date
  - counted_by
```

### Query untuk Get Total Physical Stock

```sql
SELECT 
    product_id,
    SUM(physical_stock) as total_physical_stock,
    COUNT(DISTINCT location_id) as counted_locations
FROM stock_opname_items
WHERE session_id = ? 
  AND is_counted = 1
GROUP BY product_id
```

## API Endpoints

### 1. Search Item
```
GET /stock-opname/{sessionId}/search-item
Params:
  - q: keyword (code/PLU/name)
  - location_id: current location being counted

Response:
{
  "success": true,
  "items": [
    {
      "product_id": 123,
      "code": "SKU001",
      "name": "Product A",
      "is_counted_at_current_location": false,
      "counted_locations_count": 2,
      "counted_locations": [
        {"location_id": 1, "nama_lokasi": "Gudang Utama"},
        {"location_id": 2, "nama_lokasi": "Gudang Cabang"}
      ]
    }
  ]
}
```

### 2. Save Batch Input
```
POST /stock-opname/{sessionId}/save-batch
Body:
  - location_id: ID lokasi dari master
  - counted_date: Tanggal hitung
  - counted_by: Nama penghitung
  - items: {item_id: physical_stock}

Logic:
  - Cek existing entry untuk (session_id, product_id, location_id)
  - Jika ada → UPDATE
  - Jika tidak ada:
    - Base item uncounted → UPDATE base item
    - Base item counted di lokasi lain → CREATE new entry
```

## Best Practices

### ✅ DO
- **Pilih lokasi** sebelum mulai input
- **Input qty per lokasi** bukan total keseluruhan
- **Gunakan master location** untuk consistency
- **Review summary** sebelum close session

### ❌ DON'T
- Jangan input total qty di satu lokasi lalu skip lokasi lain
- Jangan lupa pilih lokasi sebelum search
- Jangan close session sebelum semua lokasi dihitung

## Troubleshooting

### Q: Item sudah counted tapi masih muncul di search?
**A:** Ini normal. Item bisa dihitung di multiple locations. Cek badge untuk tahu status.

### Q: Physical stock tidak sesuai setelah close?
**A:** Pastikan semua lokasi sudah diinput. Total = SUM dari semua locations.

### Q: Bagaimana cara edit qty di lokasi tertentu?
**A:** Search item lagi di lokasi yang sama, akan update existing entry.

### Q: Bisa hapus entry per lokasi?
**A:** Saat ini belum support delete per location. Bisa reopen session lalu re-input.

## Migration

Untuk enable fitur ini, jalankan migration:

```bash
php spark migrate
```

Migration akan:
1. Drop unique constraint jika ada
2. Allow multiple entries per product dalam satu session
3. Backward compatible dengan data existing

## Summary

Fitur multiple location stock opname memungkinkan:
- ✅ Akurasi lebih tinggi untuk produk yang tersebar di banyak lokasi
- ✅ Tracking detail qty per lokasi
- ✅ Automatic aggregation saat close session
- ✅ Visibility status counted per lokasi
- ✅ Flexible workflow untuk tim besar

---
**Updated:** 2025-12-03
**Version:** 1.0
