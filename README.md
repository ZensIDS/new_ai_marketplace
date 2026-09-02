# MarketKu — Website Marketplace (Laravel 9 + MySQL)

Project ini berisi source code custom (Models, Controllers, Views, Routes, Migrations)
untuk website marketplace sesuai spesifikasi:

- **Admin Panel**: Dashboard, CRUD Kategori, CRUD Produk, CRUD User (Admin & Customer)
- **Frontend Guest**: Home, All Product (pagination), tombol **Beli** saja → diarahkan ke Login/Register
- **Frontend Customer (login)**: Home, All Product (pagination), tombol **+Keranjang** & **Beli**
    - Beli → langsung ke WhatsApp (nama produk, deskripsi, harga sudah terisi otomatis di chat)
    - +Keranjang → ke halaman Keranjang (checklist banyak barang, mirip Shopee) → widget total → Checkout ke WhatsApp
- **Desain**: Tailwind CSS (frontend, warna oranye khas marketplace) + Bootstrap 5 (admin panel), fully responsive mobile.

> Catatan: File di sini adalah **source code aplikasi** (bukan skeleton Laravel penuh), karena environment
> pembuatan file ini tidak punya akses ke Packagist/Composer. Ikuti langkah instalasi di bawah untuk
> menggabungkannya ke project Laravel 9 baru di komputer kamu.

---

## 1. Buat project Laravel 9 baru

```bash
composer create-project laravel/laravel:^9.0 marketplace
cd marketplace
```

## 2. Copy/replace file dari folder ini ke project Laravel kamu

Salin & timpa (overwrite) folder/berkas berikut dari zip ini ke root project Laravel:

```
app/Http/Controllers/   -> app/Http/Controllers/
app/Http/Middleware/RoleMiddleware.php -> app/Http/Middleware/
app/Models/              -> app/Models/
database/migrations/     -> database/migrations/   (hapus dulu migration bawaan yg lama)
database/seeders/         -> database/seeders/
resources/views/          -> resources/views/
routes/web.php            -> routes/web.php
.env.example               -> .env.example (opsional, jadi acuan .env kamu)
```

## 3. Daftarkan middleware `role`

Buka `app/Http/Kernel.php`, tambahkan baris berikut di array `$middlewareAliases` (Laravel 9):

```php
protected $middlewareAliases = [
    // ...alias bawaan lainnya...
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
```

## 4. Tambahkan config nomor WhatsApp

Buka `config/services.php`, tambahkan array berikut (contoh isinya ada di file
`config/services_whatsapp_snippet.php` pada zip ini):

```php
'whatsapp' => [
    'number' => env('WHATSAPP_NUMBER', '62895399259868'),
],
```

## 5. Setting `.env`

Sesuaikan `.env` project kamu (contoh ada di `.env.example`):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
WHATSAPP_NUMBER=62895399259868
```

Buat database `marketplace` di MySQL (misal lewat phpMyAdmin/HeidiSQL/CLI).

## 6. Install dependency & jalankan

```bash
composer install
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
php artisan serve
```

Buka `http://localhost:8000`

## 7. Akun default (dari Seeder)

| Role     | Email                     | Password |
| -------- | ------------------------- | -------- |
| Admin    | admin@marketplace.test    | password |
| Customer | customer@marketplace.test | password |

Login admin lalu buka `http://localhost:8000/admin/dashboard` untuk kelola Kategori, Produk & User.

---

## Alur Sesuai Requirement

1. **Guest** buka Home/All Product → tiap produk hanya ada 1 tombol **Beli** → klik akan diarahkan ke halaman **Login**.
2. **Customer (sudah login)** melihat 2 tombol di tiap produk:
    - **+Keranjang** → produk masuk ke session cart → langsung diarahkan ke halaman **/cart**.
    - **Beli** → langsung buka **WhatsApp** (wa.me) dengan pesan otomatis: nama produk, deskripsi, harga, + kalimat "Saya ingin bertanya tentang produk ini."
3. Halaman **/cart** menampilkan semua produk di keranjang dengan **checkbox** (bisa pilih beberapa produk sekaligus, seperti Shopee), ada widget sticky di bawah yang menghitung total otomatis (JS) dari item yang dicentang, lalu tombol **Checkout via WhatsApp** yang akan membuka WhatsApp dengan daftar semua produk terpilih beserta subtotal & total.
4. **Admin Panel** (`/admin`) hanya bisa diakses role `admin`: Dashboard (statistik), CRUD Kategori, CRUD Produk (dengan upload gambar), CRUD User (kelola akun admin & customer).

## Struktur Halaman Penting

- `resources/views/components/product-card.blade.php` — komponen kartu produk yang otomatis berubah
  tampilannya sesuai status login (guest / customer), jadi tidak perlu bikin 2 view terpisah.
- `resources/views/cart/index.blade.php` — halaman keranjang dengan checklist + widget total + checkout WA (pakai JS, tanpa reload).
- `app/Http/Middleware/RoleMiddleware.php` — proteksi role `admin` & `customer` di routes.

## Pengembangan Lanjutan (opsional)

- Tambahkan halaman detail produk (`/product/{slug}`).
- Tambahkan riwayat pesanan (order history) jika nanti mau simpan transaksi di database, bukan hanya via WhatsApp.
- Tambahkan review/rating produk.
- Ganti Tailwind CDN dengan build Vite (`npm install && npm run build`) untuk performa produksi yang lebih optimal.
"# new_ai_marketplace" 
