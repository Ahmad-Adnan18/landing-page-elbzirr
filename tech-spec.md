Berikut adalah **Dokumen Spesifikasi Teknis (Technical Specification)** lengkap dan final untuk proyek website **Elbzirr Dimsum**.

Dokumen ini disusun sebagai panduan mutlak bagi *developer* (kamu) untuk membangun sistem, mulai dari struktur database hingga logika mata uang ganda.

-----

# TECHNICAL SPECIFICATION DOCUMENT

**Project Name:** Elbzirr Hybrid Web Catalog
**Client:** Muhajir Adi (Elbzirr)
**Version:** 1.0 Final
**Date:** 01 December 2025

-----

## 1\. PROJECT OVERVIEW

Membangun aplikasi web berbasis katalog digital untuk usaha kuliner "Elbzirr". Website ini memiliki fitur unik **Hybrid Currency** (Mata Uang Ganda), di mana produk dapat dijual menggunakan Turkish Lira (₺) atau Rupiah (Rp) dalam satu keranjang belanja. Transaksi akhir dilakukan melalui *redirect* pesan otomatis ke WhatsApp Admin.

## 2\. FUNCTIONAL REQUIREMENTS

### A. Frontend (Halaman Pelanggan)

1.  **Homepage & Katalog:**
      * Menampilkan Banner Promo.
      * List Produk berdasarkan Kategori (Frozen / Ready to Eat).
      * Pencarian Produk (*Search Bar*).
      * **Label Harga Cerdas:** Menampilkan `₺` atau `Rp` sesuai settingan produk.
2.  **Product Detail & Configurator:**
      * User memilih **Varian Utama** (Misal: Isi 6 - Mentai vs Isi 6 - Polos). Harga berubah otomatis saat varian diklik.
      * User memilih **Opsi Tambahan** (Misal: Goreng / Kukus). Tidak mengubah harga.
3.  **Smart Cart (Keranjang Hybrid):**
      * Menampung item dengan mata uang berbeda.
      * **Split Calculation:** Tidak menjumlahkan Lira dengan Rupiah. Total ditampilkan terpisah:
          * *Total Tagihan (Lira): ₺ 145*
          * *Total Tagihan (Rupiah): Rp 24.000*
4.  **Checkout Logic:**
      * Form Data Diri: Nama, No WA, Alamat, Catatan.
      * **Timezone Logic:** Cek waktu server/toko. Jika di luar jam operasional, status order diubah otomatis menjadi **PRE-ORDER (PO)**.
5.  **WhatsApp Generator:**
      * Merakit seluruh data (Item, Varian, Total Terpisah, Data Diri) menjadi satu teks pesan yang rapi.

### B. Backend (Admin Dashboard)

1.  **Product Management (CRUD):**
      * Input Nama, Deskripsi, Foto.
      * **Variant Repeater:** Admin bisa input banyak varian dalam satu produk.
      * **Currency Selector:** Di setiap varian, Admin wajib memilih mata uang (`TRY` atau `IDR`).
2.  **Store Control:**
      * **Toggle Open/Close:** Tombol manual untuk memaksa toko tutup (Mode PO).
      * **WhatsApp Number:** Input nomor HP Admin tujuan.
      * **Stock Toggle:** Switch On/Off ketersediaan varian per item.

-----

## 3\. DATABASE SCHEMA (ERD)

Struktur tabel dirancang untuk efisiensi Laravel + Filament.

### Table: `categories`

| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BigInt | PK |
| `name` | String | "Frozen", "Ready to Eat" |
| `slug` | String | URL friendly name |

### Table: `products`

| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BigInt | PK |
| `category_id` | BigInt | FK to categories |
| `name` | String | e.g., "Dimsum Premium", "Paket Hemat" |
| `description` | Text | Penjelasan isi paket/rasa |
| `image_path` | String | Lokasi file gambar |
| `is_active` | Boolean | Default: True |

### Table: `product_variants` (CORE LOGIC)

*Tabel ini menangani perbedaan harga Mentai vs Polos dan Mata Uang.*
| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BigInt | PK |
| `product_id` | BigInt | FK to products |
| `name` | String | e.g., "Isi 6 - Mentai", "Satuan Pcs" |
| `price` | Decimal | e.g., 145.00 atau 8000.00 |
| `currency` | Enum | Values: **['TRY', 'IDR']** |
| `is_available` | Boolean | Stock status (Ready/Empty) |

### Table: `store_settings`

| Column | Type | Description |
| :--- | :--- | :--- |
| `key` | String | "wa\_number", "is\_store\_open" |
| `value` | String | Value setting |

-----

## 4\. BUSINESS LOGIC & ALGORITHMS

### A. Algoritma Kalkulasi Keranjang (Cart Service)

```php
// Pseudocode Logic
Function CalculateCart(CartItems) {
    let total_TRY = 0;
    let total_IDR = 0;

    foreach (item in CartItems) {
        if (item.variant.currency == 'TRY') {
            total_TRY += (item.variant.price * item.quantity);
        } else {
            total_IDR += (item.variant.price * item.quantity);
        }
    }

    return [
        'total_try' => total_TRY,
        'total_idr' => total_IDR
    ];
}
```

### B. Format Pesan WhatsApp

Sistem akan menghasilkan *String URL Encoded* dengan format:

```text
Halo Admin Elbzirr,
Saya mau pesan via Website:

DATA PEMESAN
Nama: [Input User]
Alamat: [Input User]
Status: [OPEN / PO]

DETAIL PESANAN
------------------
1. [Nama Produk]
   Varian: [Nama Varian] (Rp/₺ [Harga])
   Qty: [Qty]

2. [Nama Produk Lain]
   ...

------------------
REKAP TAGIHAN:
💰 Total Lira: ₺ [Total TRY]
💰 Total Rupiah: Rp [Total IDR]

Mohon info pembayaran.
```

-----

## 5\. TECH STACK RECOMMENDATION

  * **Framework:** Laravel 11.
  * **Admin Panel:** FilamentPHP v3 (Wajib, untuk kemudahan input Repeater Varian).
  * **Frontend Logic:** Vue 3 + Inertia.js (Single Page Application feel).
  * **Styling:** Tailwind CSS.
  * **Database:** MySQL / MariaDB.
  * **Server:** Shared Hosting (Apache/Nginx/LiteSpeed) dengan PHP 8.2+.

## 6\. UI/UX GUIDELINES

  * **Color Palette:**
      * Primary: `#F97316` (Orange - Identitas Makanan/Saus).
      * Secondary: `#EAB308` (Yellow - Aksen).
      * Text: `#1F2937` (Dark Gray - Keterbacaan tinggi).
  * **Typography:** *Inter* atau *Poppins* (Google Fonts).
  * **Currency Formatting:**
      * Jika IDR: Gunakan `number_format` ribuan (Rp 8.000).
      * Jika TRY: Gunakan simbol ₺ di depan/belakang sesuai preferensi (₺ 145).

## 7\. TIMELINE & DELIVERABLES (Estimasi)

1.  **Minggu 1: Backend & Database**
      * Setup Laravel + Filament.
      * Implementasi Multi-Currency Logic di Database.
      * Pembuatan Admin Dashboard Custom.
2.  **Minggu 2: Frontend & Integration**
      * Slicing UI Katalog & Keranjang.
      * Implementasi Logic "Cart Separation" (Pisah Lira & Rupiah).
      * Testing Logic WhatsApp Generator.
      * Deployment ke Server & Domain.

-----

### NEXT STEP (Action Item)

**Langkah pertama untuk kamu (Developer):**
Jalankan perintah instalasi Laravel dan Filament sekarang juga untuk memulai *skeleton* proyek.
`composer create-project laravel/laravel elbzirr-web`
`composer require filament/filament`
`composer require inertiajs/inertia-laravel`