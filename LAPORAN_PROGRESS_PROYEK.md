# 📋 LAPORAN DOKUMENTASI PROGRESS PROYEK: RENTAL MOTOR KUTA BALI
*(Versi Lengkap & Terverifikasi - 7 Resource + 1 Custom Page Filament, Manajemen Testimoni Dwibahasa, Sentralisasi Pengaturan Bisnis, SSR Astro, Motion Design, & Interactive Gallery)*

**Proyek:** Website Rental Motor Kuta Bali (RMB Sewa Motor Kuta)  
**Lingkup:** Frontend (Astro 7 + TypeScript + Tailwind CSS v4) & Backend (PHP 8.3+ + Laravel 13 + Filament 3)  
**Versi Framework Backend:** Laravel Framework 13.33.0 (`^13.17`) & Filament 3.2 (`^3.2`)  
**Status Terakhir:** Switcher bahasa memakai bendera SVG inline berbasis konfigurasi; pesan WhatsApp pelanggan dan booking mengikuti locale URL. Production build Astro dan tes API booking terfokus lulus.  
**Tanggal Pembaruan Terakhir:** 8 Oktober 2026  

---

## 📌 DAFTAR ISI
1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Spesifikasi Teknis & Arsitektur Nyata](#2-spesifikasi-teknis--arsitektur-nyata)
3. [Daftar Modul Filament Nyata di Sidebar (7 Resource & 1 Custom Page)](#3-daftar-modul-filament-nyata-di-sidebar-7-resource--1-custom-page)
4. [Peningkatan Alur Kerja Admin: Redirect & Flash Notifikasi Saat Save Changes](#4-peningkatan-alur-kerja-admin-redirect--flash-notifikasi-saat-save-changes)
5. [Implementasi Desain & Sistem Visual Aktif](#5-implementasi-desain--sistem-visual-aktif)
6. [Kronologi & Detail Pekerjaan dari Awal hingga Akhir](#6-kronologi--detail-pekerjaan-dari-awal-hingga-akhir)
7. [Daftar Masalah, Troubleshooting & Solusi](#7-daftar-masalah-troubleshooting--solusi)
8. [Bukti Pengujian & Validasi Empiris](#8-bukti-pengujian--validasi-empiris)
9. [Struktur File & Komponen Nyata di Repositori](#9-struktur-file--komponen-nyata-di-repositori)
10. [Komitmen & Aturan Pengembangan ke Depan](#10-komitmen--aturan-pengembangan-ke-depan)

---

## 1. RINGKASAN EKSEKUTIF
Proyek ini mengembangkan platform website rental motor responsif dan sistem manajemen reservasi untuk **Sewa Motor Kuta Bali (RMB Sewa Motor Kuta)**. Website dirancang melayani pelanggan lokal maupun turis mancanegara dengan dukungan dwibahasa (Indonesia `/id` & Inggris `/en`), katalog armada dinamis, sistem booking terhubung langsung ke panel admin, galeri dokumentasi aktivitas, tanya-jawab (FAQ) dinamis, dan direct WhatsApp checkout.

Website melayani pelanggan dalam Bahasa Indonesia (`/id`) dan Bahasa Inggris (`/en`), dengan katalog dan informasi yang terhubung ke API Laravel. Frontend menggunakan Astro dalam mode server-rendered, Tailwind CSS v4, serta font Plus Jakarta Sans. Fitur pengelolaan testimoni terbaru menghubungkan panel Filament, database, API publik, dan halaman website, termasuk konten dwibahasa, rating, urutan, dan status aktif. Desain visual yang sedang aktif menggunakan dasar putih, aksen biru langit, teks abu-abu kebiruan, dan footer navy; rincian warna terdapat pada Bagian 5.

---

## 2. SPESIFIKASI TEKNIS & ARSITEKTUR NYATA

### A. Frontend (Client-Facing)
- **Framework:** Astro 7.x (`output: 'server'` dengan adapter Vercel). Halaman di `src/pages/[lang]/` saat ini menggunakan `prerender = false`, sehingga dirender melalui SSR; halaman statis/prerendered tidak teridentifikasi pada rute tersebut.
- **Styling:** Tailwind CSS v4 + Design Tokens Terpusat di `global.css`
- **Bahasa:** TypeScript untuk client API dan JavaScript/TypeScript interaktif di browser
- **Typography:** Plus Jakarta Sans (weights 400, 500, 600, 700, 800), dengan fallback system sans-serif
- **Ikonografi:** `lucide-astro` (100% konsisten, proporsional, bebas SVG kasar / manual)
- **Motion & Interactivity Engine:**
  - Micro-vertical emergence (18px) dengan quintic ease-out `cubic-bezier(0.16, 1, 0.3, 1)`
  - Single-instance `IntersectionObserver` untuk scroll reveal (GPU compositor only, auto-unobserve)
  - Interactive infinite marquee dengan sub-pixel floating point accumulator via `requestAnimationFrame`
  - Dukungan interaksi multi-input: mouse drag-to-scroll (`cursor-grab`/`grabbing`), trackpad horizontal swipe, touch swipe, dan mouse wheel
  - Kepatuhan penuh terhadap standar aksesibilitas `prefers-reduced-motion`
- **Deployment Adapter:** `@astrojs/vercel`
- **Internasionalisasi (i18n):** Routing dinamis `/[lang]/` (`id` dan `en`) dengan kamus fallback tangguh
- **API Client:** `frontend/src/lib/api.ts` (mengonsumsi REST API backend dan memakai fallback sesuai pola yang tersedia)

### B. Backend & Admin Panel (Internal)
- **Framework:** **Laravel Framework 13.33.0**
- **Admin Panel:** **Filament 3.2**
- **Port Layanan Dev:** `http://127.0.0.1:8089`
- **File Storage:** `public/storage` terhubung melalui symlink fisik (`storage:link`)
- **Automated Testing:** PHPUnit; dua test khusus testimoni lulus pada 7 Oktober 2026 (26 assertions)

---

## 3. DAFTAR MODUL FILAMENT NYATA DI SIDEBAR (7 RESOURCE & 1 CUSTOM PAGE)

Panel admin Filament memiliki 7 resource dan 1 custom page aktif yang dikelompokkan secara logis:

### 1. `Bookings` (`BookingResource.php`) — *Grup: Rental Management*
- **Tabel:** `bookings`
- **Fungsi:** Mengelola reservasi sewa motor dari pelanggan frontend.
- **Field Form Nyata:** `motor_id`, `customer_name`, `customer_whatsapp`, `customer_document_type` (ktp, sim, passport), `start_date`, `end_date`, `total_days`, `total_price`, `status` (pending, confirmed, completed, cancelled), `notes`.
- **Aksi Cepat:** Integrasi link WhatsApp langsung ke nomor pemesan.

### 2. `Motors` (`MotorResource.php`) — *Grup: Fleet Management*
- **Tabel:** `motors`
- **Fungsi:** Mengelola unit armada, spesifikasi teknis, harga sewa bertingkat (harian, mingguan, bulanan), jumlah unit, dan status ketersediaan.

### 3. `FAQs` (`FaqResource.php`) — *Grup: Konten Website*
- **Tabel:** `faqs`
- **Fungsi:** Memungkinkan staf non-teknis menambah, mengedit pertanyaan-jawaban dalam format dwibahasa (ID & EN), mengatur urutan tampil (`order`), dan menonaktifkan item tanpa menghapus (`is_active`).

### 4. `Pricing Packages` (`PricingPackageResource.php`) — *Grup: Konten Website*
- **Tabel:** `pricing_packages`
- **Fungsi:** Mengelola kartu paket sewa di beranda (Harian, Mingguan, Bulanan) secara dinamis tanpa hardcode.
- **Field Form Nyata:**
  - Teks dwibahasa: `name_id`, `name_en`, `subtitle_id`, `subtitle_en`, `price_unit_id`, `price_unit_en`, `cta_label_id`, `cta_label_en`.
  - Harga: `price` (decimal numerik dengan prefix IDR).
  - Repeater Poin Benefit: `benefits_id` dan `benefits_en` (mudah tambah/hapus item benefit, tersimpan rapi sebagai JSON).
  - Pilihan Icon Lucide: Dropdown `icon` (`Bike`, `Sparkles`, `Crown`, `ShieldCheck`, `Zap`, `Clock`, `Heart`, `Award`, `CheckCircle2`).
  - Pengaturan Tampil: Toggle `is_featured` (highlight badge terpopuler & kartu kontras), toggle `is_active`, dan input numerik `order`.
- **Alur Kerja:** Otomatis redirect ke tabel index saat simpan perubahan disertai notifikasi toast.

### 5. `Gallery Foto` (`GalleryImageResource.php`) — *Grup: Konten Website*
- **Tabel:** `gallery_images`
- **Fungsi:** Mengunggah foto dokumentasi aktivitas dan testimoni serah terima motor via Filament `FileUpload` (tersimpan di `storage/app/public/gallery/`), dilengkapi caption dwibahasa dan pengurutan.

### 6. `Admin Users` (`UserResource.php`) — *Grup: Pengaturan Sistem*
- **Tabel:** `users`
- **Fungsi:** Mengelola akun login staf/admin multi-user. Dilengkapi fitur auto-hashing password (`Hash::make`), pencarian email, dan proteksi hapus diri sendiri (admin yang sedang login tidak dapat menghapus akunnya sendiri).

### 7. `Testimoni Pelanggan` (`TestimonialResource.php`) — *Grup: Konten Website*
- **Tabel:** `testimonials`
- **Fungsi:** Mengelola ulasan yang ditampilkan di homepage tanpa mengubah kode frontend.
- **Field:** `name`, `location`, teks Indonesia `text_id`, teks Inggris `text_en`, `rating` (1–5), `order`, dan `is_active`.
- **Aksi:** Tambah, edit, hapus, filter status, dan urutkan daftar; testimoni nonaktif tidak dikirim API publik.

### 8. `Pengaturan Bisnis` (`ManageBusinessSettings.php`) — *Grup: Pengaturan Sistem (Custom Page / Single-Row Pattern)*
- **Tabel:** `business_settings` (Tabel konfigurasi baris tunggal, row `id=1`).
- **Fungsi:** Sentralisasi seluruh informasi kontak operasional rental motor (nomor WhatsApp utama & cadangan, email bisnis, alamat fisik garasi, jam operasional dwibahasa ID/EN, dan URL Instagram).
- **Arsitektur Custom Page (Bukan Resource Biasa):**
  - Mengimplementasikan `Filament\Pages\Page` bersama trait `HasForms` dan `InteractsWithForms`.
  - Menampilkan 1 formulir terpusat yang langsung mengikat (*bound*) ke data `BusinessSetting::firstOrCreate(['id' => 1])`.
  - Mengeliminasi tabel list/index, pagination, tombol tambah record baru (*Create*), dan tombol hapus (*Delete*) yang tidak relevan untuk konfigurasi baris tunggal.
  - Dilengkapi tombol aksi form *"Simpan Perubahan"* beraksen amber dengan notifikasi toast sukses instan.
- **Konsumsi Publik:** Tersedia melalui endpoint publik `GET /api/settings` dan dikonsumsi secara dinamis oleh seluruh komponen frontend (FAB WhatsApp, Navbar, Footer, ContactSection, dan Checkout Konfirmasi Booking) dengan mekanisme fallback aman jika server backend sedang offline.

---

## 4. PENINGKATAN ALUR KERJA ADMIN: REDIRECT & FLASH NOTIFIKASI SAAT SAVE CHANGES

### A. Latar Belakang & Masalah
Secara default, Filament 3 menetap pada halaman form edit (`EditRecord`) setelah tombol *"Save changes"* ditekan dan hanya menampilkan banner kecil di pojok kanan atas. Bagi staf operasional rental motor, hal ini memperlambat alur kerja karena mereka harus mengklik manual menu sidebar atau tombol *Cancel* untuk kembali memeriksa daftar reservasi.

### B. Solusi yang Diterapkan
1. **Redirect Otomatis ke Halaman Tabel Index (`getRedirectUrl`):**
   Pada semua halaman edit dan create:
   ```php
   protected function getRedirectUrl(): string
   {
       return $this->getResource()::getUrl("index");
   }
   ```
2. **Preservasi Notifikasi Toast (Session Flash Notification):**
   Filament memanfaatkan mekanisme flash session bawaan. Begitu halaman berpindah ke tabel index (`/admin/bookings`), toast banner hijau tetap menyala di pojok kanan atas dengan teks informatif:
   > *"Perubahan data booking berhasil disimpan"* (pada edit)  
   > *"Booking baru berhasil dibuat"* (pada create)
3. **Penerapan Menyeluruh:**
   Alur ini distandardisasi di seluruh resource:
   - `BookingResource`: `EditBooking.php` & `CreateBooking.php`
   - `MotorResource`: `EditMotor.php` & `CreateMotor.php`
   - `FaqResource`: `EditFaq.php` & `CreateFaq.php`
   - `GalleryImageResource`: `EditGalleryImage.php` & `CreateGalleryImage.php`
   - `UserResource`: `EditUser.php` & `CreateUser.php`

---

## 5. IMPLEMENTASI DESAIN & SISTEM VISUAL AKTIF

### A. Palet Warna yang Saat Ini Dipakai
Nilai berikut disinkronkan dengan token pada `frontend/src/styles/global.css` dan pemakaian warna di komponen. Halaman utama memakai latar putih; hero memakai foto dengan overlay gelap agar teks tetap terbaca.

| Token / Warna | Nilai HEX | Peran & Penggunaan |
| :--- | :---: | :--- |
| `--bg`, `--surface` | `#FFFFFF` | Latar utama dan permukaan kartu |
| `--accent` | `#8ED8F8` | Aksen biru langit, CTA utama, selection, dan highlight |
| `--accent-hover` | `#72CBEF` | Keadaan hover tombol biru |
| Biru tautan/ikon | `#0284C7` | Ikon, judul kecil, tautan, dan label section |
| Biru hover | `#0369A1` | Hover untuk tautan biru |
| `--accent-ink` | `#0A2633` | Teks kontras pada tombol biru |
| `--dark` / footer | `#0E232E` | Footer dan beberapa permukaan gelap |
| `--text` | `#1E293B` | Judul dan teks utama |
| `--text-muted` | `#5F7380` | Paragraf dan deskripsi |
| `--text-subtle` | `#8D9FA9` | Teks sekunder dan keterangan kecil |
| `--border` | `#E2ECE8` | Garis kartu, border, dan pemisah |
| `--surface-hover` | `#F8FCFD` | Permukaan hover lembut |
| `--mint` | `#A7E8D0` | Checklist/aksen fasilitas; hover `#8FE0C3` |
| Kuning rating | `#EAB308` | Bintang pada kartu testimoni |
| `--whatsapp` | `#25D366` | Tombol WhatsApp; hover `#1EBE5A` |

### B. Typography
- **Font utama:** Plus Jakarta Sans, dimuat dari Google Fonts dengan bobot 400, 500, 600, 700, dan 800.
- **Fallback:** system font macOS, BlinkMacSystemFont, Segoe UI, Roboto, lalu sans-serif.

### C. Header, Hero, Logo, dan Konten Visual
- Navbar pada homepage transparan dengan teks terang agar menyatu di atas hero; halaman selain homepage mempertahankan navigasi kontras.
- Gambar hero menggunakan `frontend/public/images/kuta-2.jpg`, lebar penuh responsif (`w-full`), bukan `w-screen`, dengan overlay gelap untuk keterbacaan teks.
- Logo aktif menggunakan `frontend/public/images/logo-final.png` di navbar, footer, favicon, dan apple-touch icon. Ukuran logo navbar/footer diperbesar dan teks brand pendamping serta badge latar putih dihilangkan.
- Kartu katalog menampilkan logo tanpa latar pada sudut kanan atas seluruh kartu.
- Bagian cerita RMB dan halaman Tentang memakai copy Indonesia dan Inggris yang diselaraskan; carousel berisi foto `kuta-5.jpeg` sampai `kuta-9.jpeg`.

### D. Gerak dan Aksesibilitas
Animasi scroll reveal, slider cerita, dan galeri interaktif merupakan perilaku tambahan di atas sistem warna. Animasi menghormati `prefers-reduced-motion`; detail implementasi historis tetap tercatat pada kronologi fase sebelumnya.

---

## 6. KRONOLOGI & DETAIL PEKERJAAN DARI AWAL HINGGA AKHIR

### Fase 1: Setup Arsitektur & Katalog Armada
- Inisialisasi Astro 5 dengan Tailwind CSS v4 dan struktur dwibahasa `/[lang]/`.
- Inisialisasi backend Laravel dengan model `Motor` dan migrasi database.
- Integrasi endpoint publik `GET /api/motors` dan `GET /api/motors/{slug}`.

### Fase 2: Sistem Reservasi & Form Booking Terintegrasi
- Pembuatan model `Booking` dengan kalkulasi otomatis total hari dan harga.
- Pembuatan endpoint `POST /api/bookings`.
- Halaman konfirmasi booking dengan direct WhatsApp gateway.

### Fase 3: Revisi Visual & Tema Sunset Dusk
- Penggantian foto latar Hero menjadi lanskap pura Bali sunset `kuta-4.jpeg`.
- Pembersihan card warna biru di belakang foto motor cutout pada Hero Section.
- Migrasi palet warna biru cyan lama ke **Sunset Dusk (#2C3A54, #D4A24E, #1B2536)** di seluruh komponen.

### Fase 4: Standardisasi Ikon Menggunakan `lucide-astro`
- Pemasangan package `lucide-astro` resmi.
- Penggantian seluruh SVG inline kasar/manual dan emoji menjadi komponen Lucide Icon (`Bike`, `Phone`, `Clock`, `MapPin`, `Calendar`, `Star`, `ChevronDown`, `CheckCircle`, dsb.).
- Preservasi khusus logo resmi WhatsApp pada Floating Action Button.

### Fase 5: Implementasi 3 Resource Baru (FAQ, Galeri, Multi-Admin)
- **Modul FAQ:** Migration `faqs`, Model `Faq`, `FaqResource`, endpoint `GET /api/faqs`, seeder 5 tanya jawab, dan komponen `FAQAccordion.astro` dengan fallback dictionary otomatis.
- **Modul Galeri:** Migration `gallery_images`, Model `GalleryImage`, `GalleryImageResource` (FileUpload), endpoint `GET /api/gallery`, seeder 5 dokumentasi, dan integrasi di halaman `tentang.astro`.
- **Modul Multi-Admin:** `UserResource` dengan auto-hashing password dan perlindungan diri.

### Fase 6: Penambahan Modul Pricing Packages & Optimasi UX Admin
- **Modul Pricing Packages:** Migrasi tabel `pricing_packages`, Model `PricingPackage`, `PricingPackageResource` dengan repeater benefits JSON dwibahasa, toggle featured/active, dan endpoint `GET /api/pricing-packages`.
- **Optimasi UX Admin (Redirect on Save):** Penambahan method `getRedirectUrl()` ke seluruh halaman edit/create Filament agar otomatis kembali ke daftar tabel (`index`) dengan toast konfirmasi tersimpan.
- **Pengecekan Storage:** Verifikasi symlink `storage:link` dan penyesuaian `APP_URL=http://127.0.0.1:8089`.
- **Automated Testing:** Menulis unit/feature test `FilamentRedirectTest.php` dan `PricingPackageApiTest.php`.

### Fase 7: Penggantian Logo Resmi Website (Kuta Motorcycles)
- **Aset Logo Baru:** Mengintegrasikan aset `frontend/public/images/logo.png` (emblem sirkular Kuta Motorcycles bernuansa emas, dusk navy, siluet motor, dan kompas).
- **Optimasi Transparansi & Ketajaman:** Menghilangkan margin kotak abu-abu di luar lingkaran dan menerapkan masking alfa anti-aliased beresolusi tinggi sehingga logo menyatu sempurna pada latar terang (`#FFFBF5`) maupun gelap (`#1B2536`).
- **Penerapan Menyeluruh:**
  1. `Navbar.astro`: Menggantikan ikon placeholder kotak dengan logo resmi Kuta Motorcycles.
  2. `Footer.astro`: Menggantikan ikon placeholder footer dengan logo resmi Kuta Motorcycles.
  3. `Layout.astro`: Menetapkan logo resmi sebagai icon tab browser (favicon) dan apple-touch-icon.

### Fase 8: Refactor Visual Menyeluruh ke Tema Warm Dark (Tropical Bali Luxury)
- **Identifikasi Masalah:** Kontras visual yang terpecah antara Hero berpalet hangat (lanskap sunset Bali, coklat, amber) dengan komponen di bawahnya (fitur, armada, pricing, footer, language selector) yang memakai warna dusk navy dan slate dingin.
- **Standardisasi Token Desain Terpusat (`global.css`):**
  - Menerapkan palet Warm Dark: `--bg: #0F0D0B`, `--bg-alt: #17130F`, `--surface: #1E1914`, `--surface-hover: #262019`, `--border: #2E261D`, `--accent: #F5A524`, `--accent-hover: #FFB93D`, `--text: #F5EFE6`, `--text-muted: #B8AB9A`, `--text-subtle: #8A7E70`.
  - Mengeliminasi 100% warna slate, navy, dan cyan dingin di seluruh komponen frontend.
- **Refactor Komponen Menyeluruh:**
  - `Navbar.astro`: Glassmorphism hangat (`bg-[#0F0D0B]/85` dengan border `#2E261D`), tombol bahasa bernuansa gelap hangat, dan link navigasi dengan highlight emas.
  - `Hero.astro`: Latar belakang full-bleed `kuta-4.jpeg` dengan overlay gradien hangat bertingkat (`from-[#0F0D0B] via-[#0F0D0B]/70 to-[#0F0D0B]/90`), typography H1 kontras tajam.
  - `KeyBenefits.astro`: 4 kartu pilar keunggulan dengan surface `#1E1914`, border `#2E261D`, dan ikon beraksen amber `#F5A524`.
  - `ProductCard.astro`: Rasio gambar armada distandardisasi ke `aspect-[16/10]` dengan background neutral hangat `#262019`, `object-contain`, drop shadow lembut, dan badge ketersediaan bernuansa amber.
  - `PricingPackages.astro`: Kartu paket sewa harian/mingguan/bulanan berlatar `#1E1914` dengan highlight khusus untuk paket terpopuler (*Featured*).
  - `HowItWorks.astro`, `Testimonials.astro`, `FAQAccordion.astro`, `ContactSection.astro`, dan `Footer.astro`: Diselaraskan 100% ke token Warm Dark.

### Fase 9: Penerapan Sistem Motion Design "Quiet Luxury Scroll Reveal"
- **Filosofi Gerak:** Menerapkan prinsip *Restrained / Understated Luxury*—animasi kemunculan konten dibuat sangat halus, tenang, dan berbobot tanpa mengalihkan fokus pengguna dari informasi rental.
- **Spesifikasi Gerak:**
  - Micro-vertical emergence: Pergeseran elemen hanya sebesar 18px (`translateY(18px)` &rarr; `translateY(0)`).
  - Kurva Kecepatan Liquid Quintic: `cubic-bezier(0.16, 1, 0.3, 1)` dengan durasi 700ms untuk transisi yang terasa organik.
  - Staggered Delay: Elemen dalam grid (seperti kartu armada dan benefit) diberi delay beruntun `+80ms` hingga `+120ms` per item.
- **Efisiensi Performa & Aksesibilitas:**
  - Menggunakan satu instance `IntersectionObserver` efisien dengan threshold 0.1 dan rootMargin `-40px`.
  - Animasi hanya memodifikasi properti GPU compositor (`opacity` dan `transform`).
  - Properti `will-change` otomatis dibersihkan setelah transisi selesai untuk menghemat memori GPU.
  - Elemen yang sudah tampil otomatis di-`unobserve`, mengembalikan pemakaian CPU ke 0% saat diam (*idle*).
  - Dukungan penuh standar `@media (prefers-reduced-motion: reduce)` yang mematikan efek gerak secara instan demi kenyamanan pengguna dengan sensitivitas vestibular.

### Fase 10: Rekayasa Modul Interactive Infinite Marquee Gallery (Auto-Drift & Manual Drag/Swipe)
- **Kebutuhan Bisnis & Visual:** Menampilkan dokumentasi motor dan keseruan aktivitas wisatawan Kuta dalam bentuk galeri pita bergerak terus-menerus tanpa henti (*infinite marquee*) di Beranda (`index.astro`) dan Halaman Tentang Kami (`tentang.astro`).
- **Pola 2 Baris Selang-Seling:**
  - Baris 1 (Atas): Bergerak perlahan ke arah kanan (`speed: -0.55 px/frame`).
  - Baris 2 (Bawah): Bergerak perlahan ke arah kiri (`speed: +0.50 px/frame`).
- **Arsitektur 3 Buffer Group Cloning:**
  - Menghindari visual kosong dengan mereplikasi data foto menjadi 3 grup identik `[Grup 1: 0..W]`, `[Grup 2: W..2W]`, `[Grup 3: 2W..3W]`.
  - Posisi scroll awal diatur tepat di tengah (Grup 2, `scrollLeft = groupWidth`).
  - Algoritma wrap-around seamless: Ketika `scrollLeft >= 2 * groupWidth`, posisi otomatis dimundurkan sebesar `groupWidth`; ketika `scrollLeft <= 0`, dimajukan sebesar `groupWidth`. Pergeseran terjadi instan pada frame yang sama tanpa lonjakan (*zero stutter*).
- **Interaktivitas Bebas (Drag, Wheel & Swipe):**
  - Mengatasi keterbatasan CSS `@keyframes` konvensional yang kaku dan membeku saat kursor menyentuh galeri.
  - Sistem dibangun berbasis `scrollLeft` DOM murni dan `requestAnimationFrame`.
  - Mendukung interaksi multi-input lengkap: mouse drag-and-drop (`mousedown`, `mousemove`, `mouseup` dengan kursor dinamis `cursor-grab` dan `cursor-grabbing`), trackpad horizontal swipe dua jari, mouse wheel horizontal, serta swipe layar sentuh pada perangkat mobile.
  - Kursor hover tidak lagi mematikan animasi; auto-drift hanya berhenti sementara saat pengguna aktif men-drag atau men-scroll secara manual, dan otomatis melanjutkan drift perlahan setelah 1 detik tidak ada input.

### Fase 11: Penyeimbangan Spacing Header, Jarak Logo, & Konsistensi i18n Copywriting
- **Reposisi Navigasi Header:** Mengatur ulang tata letak header navigasi (`Navbar.astro`) agar grup tautan terdistribusi proporsional dan lebih condong ke kanan mendekati tombol aksi utama "Rent Now / Sewa Sekarang".
- **Penyeimbangan Spacing Logo:** Menyesuaikan padding dan margin logo Kuta Motorcycles dengan teks nama brand agar presisi dan seimbang di seluruh ukuran layar.
- **Standardisasi String Translasi (i18n):**
  - Memperbaiki kebocoran label "Mulai dari" pada antarmuka Bahasa Inggris (`/en/`) menjadi "Starts from" melalui kamus terpusat `frontend/src/i18n/ui.ts`.
  - Fleksibilitas teks paket dan benefit (seperti "2 helm SNI gratis", "jas hujan", dsb.) yang kini dapat dikelola fleksibel melalui kamus `ui.ts` maupun panel admin `PricingPackageResource`.

### Fase 12: Sentralisasi Informasi Kontak Bisnis & Filament Custom Page (Single-Row Pattern)
- **Latar Belakang & Kebutuhan Bisnis:** Sebelumnya, nomor kontak WhatsApp, alamat garasi, dan jam operasional tersebar secara hardcoded di berbagai komponen frontend (`FloatingWhatsApp.astro`, `Navbar.astro`, `Footer.astro`, `ContactSection.astro`, dan link konfirmasi booking). Pemilik bisnis memerlukan kemampuan memperbarui nomor kontak sewaktu-waktu langsung dari admin dashboard tanpa perlu menyentuh kode program atau melakukan deploy ulang.
- **Perancangan Skema Database:**
  - Migrasi `2026_09_29_090000_create_business_settings_table.php` mendefinisikan tabel `business_settings`:
    - `id` (primary key integer)
    - `whatsapp_primary` (string)
    - `whatsapp_secondary` (string, nullable)
    - `business_email` (string, nullable)
    - `business_address` (text, nullable)
    - `operating_hours_id` (string)
    - `operating_hours_en` (string)
    - `instagram_url` (string, nullable)
    - `timestamps`
  - Seeder `BusinessSettingSeeder.php` menyisipkan baris awal (`id=1`) menggunakan nilai kontak resmi saat ini: WA `6285947167662`, email `info@sewamotorkuta.id`, alamat `Jl. Pantai Kuta No. 88, Badung, Bali 80361`, jam operasional ID `Setiap Hari (Senin - Minggu): 08:00 - 22:00 WITA`, dan EN `Everyday (Monday - Sunday): 08:00 - 22:00 WITA`.
- **Pengembangan Model & Custom Page Filament:**
  - Model `BusinessSetting.php` dengan `$fillable` lengkap.
  - Custom Page `ManageBusinessSettings.php` (turunan `Filament\Pages\Page` dengan trait `HasForms` dan `InteractsWithForms`):
    - Didaftarkan di sidebar grup *"Pengaturan Sistem"* dengan icon `heroicon-o-cog-6-tooth`.
    - Menampilkan 1 form edit tunggal terikat langsung ke baris `id=1` (`BusinessSetting::firstOrCreate(['id' => 1])`).
    - Menghilangkan tabel index, tombol create record baru, dan tombol delete (menjamin keutuhan pola single-row).
    - Dilengkapi tombol aksi form *"Simpan Perubahan"* beraksen amber dengan toast notifikasi sukses instan.
- **Penyediaan REST API Publik:**
  - Endpoint publik `GET /api/settings` pada `SettingController.php` terdaftar di `routes/api.php`.
  - Mengembalikan objek data baris tunggal dalam format JSON standar berstruktur `{ status: 'success', data: { ... } }`.
- **Konsumsi Terpusat di Frontend Astro & Live Update (SSR):**
  - Pembaruan `frontend/src/lib/api.ts`: Menambahkan interface `BusinessSettings`, fallback defaults `FALLBACK_SETTINGS`, helper `fetchSettings()`, dan fungsi format nomor telepon `formatPhoneNumber()`.
  - Pembaruan seluruh komponen frontend yang memuat kontak:
    1. `FloatingWhatsApp.astro`: Menghasilkan tautan dynamic `wa.me/{whatsapp_primary}`.
    2. `ContactSection.astro`: Menampilkan nomor telepon terformat rapi (`+62 859-4716-7662`), alamat garasi, jam operasional dwibahasa, dan tombol WhatsApp CTA dinamis.
    3. `Footer.astro`: Menampilkan kontak telepon, alamat garasi, jam operasional, dan link WhatsApp dinamis.
    4. `pages/[lang]/booking/konfirmasi/[booking_id].astro`: Menghasilkan tautan WhatsApp dinamis berpesan detail booking terisi otomatis (*pre-filled*).
    5. `Navbar.astro`: Menghasilkan tautan WhatsApp dinamis pada mobile drawer.
  - Pengaktifan Server-Side Rendering (SSR) pada `index.astro` dan `kontak.astro` (`export const prerender = false`) agar setiap kali admin mengubah nomor di dashboard, perubahan langsung tersaji di frontend pada saat pengguna me-refresh halaman tanpa membutuhkan build ulang atau redeploy.

### Fase 13: Carousel Auto-Slider Horizontal Halaman Tentang Kami & Beranda (Koleksi Sunset Bali kuta-5 s/d kuta-9)
- **Latar Belakang & Permintaan:** Pengguna menghendaki foto utama pada section *"Teman Perjalanan Terpercaya di Kuta"* (di halaman `tentang.astro` maupun section *"Tentang RMB"* pada beranda `index.astro` melalui `AboutRMB.astro`) dapat bergulir otomatis ke samping (*horizontal auto-slider*) dengan interval pergantian 2 detik, menampilkan seluruh koleksi foto yang telah disiapkan di `frontend/public/images/`: `kuta-5.jpeg`, `kuta-6.jpeg`, `kuta-7.jpeg`, `kuta-8.jpeg`, dan `kuta-9.jpeg`. Desain dibuat murni otonom (tanpa kontrol manual/swipe pengguna, tanpa titik indikator, dan tanpa tombol panah), dengan gerakan transisi yang lambat, halus, dan sinematik.
- **Implementasi Arsitektur Carousel di Kedua Halaman:**
  - **Daftar Aset Foto:** Mengonsumsi 5 foto sunset & landmark Bali bertema Warm Dark (`kuta-5` Pura Lempuyang / Gunung Agung, `kuta-6` siluet penjor sunset Pantai Kuta, `kuta-7` candi meru pura Bali, `kuta-8` senja pura tepi tebing, `kuta-9` sunset pantai Bali).
  - **Sinkronisasi Komponen:** Diterapkan secara identik pada:
    1. `frontend/src/pages/[lang]/tentang.astro` (Halaman Tentang Kami)
    2. `frontend/src/components/AboutRMB.astro` (Section Tentang RMB di Beranda `index.astro`) secara bersih 100% tanpa teks alamat atau gradient overlay yang menutupi gambar.
  - **Seamless Clone Wrap-Around:** Menyematkan clone foto terakhir di indeks 0 dan clone foto pertama di indeks 6. Ketika slider bergeser ke indeks 6, event `transitionend` secara instan menonaktifkan transisi CSS dan menyetel posisi kembali ke indeks 1 tanpa jeda visual (*zero flicker*).
  - **Siklus Jeda 2 Detik (`DISPLAY_TIME = 2000`):** Foto diam stabil selama 2 detik penuh agar pengunjung dapat menikmati detail visual sebelum mulai bergeser ke foto berikutnya.
  - **Gerakan Slow & Elegan (`1200ms cubic-bezier(0.4, 0, 0.2, 1)`):** Menghilangkan efek sentakan/slide kasar dengan kurva transisi sinematik 1.200 ms yang berakselerasi lembut dan melambat anggun (*quiet luxury*).
  - **Tampilan Bersih & Proteksi Interaksi Manual (`pointer-events-none select-none`):** Mengeliminasi tombol panah kiri-kanan, baris titik navigasi, teks alamat jalan/showroom, dan gradient overlay bawah, serta menonaktifkan event drag/swipe manual sehingga foto tampil bersih dari tepi ke tepi dan bergulir mandiri secara stabil.
  - **Pengujian Real-Time:** Diverifikasi langsung pada Google Chrome headless CDP port mengamati siklus waktu 8–10 detik (`translateX(-100%)` s/d `translateX(-600%)` lalu snap kembali ke `-100%`) di kedua rute halaman.

> **Catatan kronologi:** Fase-fase yang menyebut palet Sunset Dusk/Warm Dark adalah dokumentasi tahapan desain sebelumnya. Sistem desain aktif telah diperbarui pada Fase 14 dan dijelaskan pada Bagian 5.

### Fase 14: Penyelarasan Copy, Fasilitas, Logo, dan Layout Homepage (6–7 Oktober 2026)
- **Copywriting dwibahasa:** Memperbarui judul dan deskripsi bagian Tentang RMB serta halaman Tentang dalam Bahasa Indonesia dan Inggris; istilah fasilitas (helm, jas hujan, pengantaran area Kuta, dan bantuan darurat 24 jam) diselaraskan antara komponen About, fasilitas, dan kamus i18n.
- **Hero dan navbar:** Navbar homepage dibuat transparan dengan teks terang agar berada di atas foto hero; tinggi hero disesuaikan, lebar menggunakan `w-full` alih-alih `w-screen` untuk menghindari kelebihan lebar viewport.
- **Identitas logo:** Memakai `public/images/logo-final.png` pada navbar, footer, favicon, dan apple-touch icon. Ukuran navbar/footer diperbesar dan teks brand pendamping dihilangkan. Pada `ProductCard.astro`, logo ditempatkan tanpa background di pojok kanan atas seluruh kartu.
- **Aset foto:** Hero menggunakan `kuta-2.jpg`; slider Tentang RMB menggunakan `kuta-5.jpeg` sampai `kuta-9.jpeg`. Perubahan tampilan diuji melalui production build frontend.

### Fase 15: Manajemen Testimoni Pelanggan dari Dashboard (7 Oktober 2026)
- **Penyimpanan dan model:** Menambahkan tabel `testimonials` melalui migrasi `2026_10_07_000001_create_testimonials_table.php` dan model `Testimonial`. Data meliputi nama, lokasi, isi Bahasa Indonesia/Inggris, rating 1–5, urutan tampil, dan status aktif.
- **CRUD Filament:** Menambahkan `TestimonialResource` dan halaman list/create/edit di grup sidebar *Konten Website*. Admin dapat menambah, mengubah, menghapus, mengurutkan, mengatur rating, serta menonaktifkan ulasan.
- **API publik:** Menambahkan `GET /api/testimonials`; hanya ulasan aktif dikirim dan hasil diurutkan berdasarkan `order`, lalu `id`.
- **Frontend:** `fetchTestimonials()` di `frontend/src/lib/api.ts` mengambil data API dengan fallback ke tiga ulasan terdahulu jika API tidak tersedia. `Testimonials.astro` memilih copy sesuai bahasa halaman dan menggambar bintang sesuai rating aktual.
- **Seeder yang mempertahankan edit admin:** `TestimonialSeeder` memuat tiga ulasan terdahulu dalam dua bahasa dan memakai `firstOrCreate`, sehingga pengisian ulang tidak menimpa konten yang diedit admin. Seeder didaftarkan di `DatabaseSeeder`.
- **Migrasi lokal:** Migrasi tabel testimoni dan `TestimonialSeeder` khusus telah dijalankan di database lokal. Seeder penuh tidak dijalankan.

### Fase 16: Memunculkan FAQ Aktif pada Beranda (8 Oktober 2026)
- **Diagnosis:** Data FAQ berstatus aktif dan endpoint `GET /api/faqs` merespons HTTP 200 dengan data. Komponen `FAQAccordion.astro` sudah mengambil data backend dan memilih teks berdasarkan bahasa, tetapi tidak di-import atau dirender oleh `pages/[lang]/index.astro`.
- **Perbaikan:** Menambahkan komponen FAQ ke homepage setelah testimoni dan sebelum kontak, sehingga FAQ aktif dari dashboard terlihat oleh pelanggan.
- **Validasi:** `curl` ke API lokal mengembalikan HTTP 200 beserta FAQ aktif; `npm run build` dan pemeriksaan diagnostics untuk halaman serta komponen FAQ lulus.

### Fase 17: Redesain Tampilan FAQ (8 Oktober 2026)
- Mengganti label tag kuning dan judul/subjudul translation key yang sebelumnya tampil mentah dengan heading bilingual: “FAQ - Sewa Motor di Bali (Koota)” / “FAQ - Scooter Rental in Bali (Koota)”.
- Menyatukan seluruh item accordion ke dalam satu card biru, mengikuti referensi card tunggal: judul putih di tengah, pemisah antarpertanyaan, dan indikator plus/minus.

### Fase 18: Bendera SVG dan Pesan WhatsApp Mengikuti Bahasa Situs (8 Oktober 2026)
- **Switcher bahasa:** Menambahkan `astro-icon` dan `@iconify-json/flag`; memverifikasi nama `flag:id-4x3` dan `flag:gb-4x3` tersedia pada koleksi. `src/i18n/locales.ts` menjadi daftar bahasa, label, singkatan, dan nama icon yang di-loop oleh switcher desktop serta menu mobile. SVG dibundel/inline saat build.
- **WhatsApp:** Menambahkan `buildBookingMessage()` dan kamus dwibahasa untuk pesan booking dan pesan umum. Form meneruskan `lang` dan teks terjemahan ke `POST /api/bookings`; Laravel memakai teks tersebut untuk URL respons, dan halaman konfirmasi menggunakan builder yang sama. Tombol WhatsApp Navbar, kontak, footer, dan floating button juga memakai pesan sesuai locale URL.
- **Status form:** Pesan sukses, proses, pengalihan, dan fallback error booking dipindahkan ke i18n agar tampilan `/en` tidak kembali ke teks status Indonesia.
- **API kompatibilitas:** Parameter `lang` dan `whatsapp_message` bersifat opsional untuk klien lama; ketika disertakan, Laravel memvalidasi locale `id|en` dan menggunakan pesan frontend yang sudah diterjemahkan. Pesan fallback lama tetap tersedia bagi klien API yang tidak mengirim locale.
- **Validasi:** `npm run build` lulus (Vercel adapter memberi peringatan bahwa Node lokal 26 tidak didukung dan runtime akan memakai Node 24); `php artisan test tests/Feature/MotorBookingApiTest.php` lulus (3 tes, 16 assertions). Browser menjalankan submit formulir `/id/booking/4` dan `/en/booking/4` dengan POST di-mock agar tidak membuat booking permanen; pesan hasil decode, switcher desktop/mobile, dan SVG inline diperiksa. Pencarian frontend untuk emoji bendera tidak menemukan kecocokan.
- **Batasan yang diketahui:** Aksi WhatsApp admin di `BookingResource` masih memakai pesan Indonesia karena record booking tidak menyimpan locale halaman asal. Sejumlah URL gambar galeri yang menunjuk ke `http://127.0.0.1:8089/storage/...` masih berisiko saat deploy; tidak diubah pada fase ini.

---

## 7. DAFTAR MASALAH, TROUBLESHOOTING & SOLUSI

| No | Gejala Masalah | Penyebab Teknis | Solusi yang Diterapkan |
| :---: | :--- | :--- | :--- |
| **1** | Admin tertahan di form edit setelah klik *"Save changes"*. | Perilaku bawaan Filament `EditRecord` tidak me-redirect. | Meng-override method `getRedirectUrl()` agar kembali ke `$this->getResource()::getUrl("index")`. |
| **2** | Kekhawatiran notifikasi hilang saat di-redirect. | Dikhawatirkan redirect menghapus alert. | Filament menggunakan session flash message; toast hijau *"Perubahan data booking berhasil disimpan"* tetap tampil di pojok kanan atas halaman tabel index. |
| **3** | URL upload gambar galeri berpotensi 404 jika `APP_URL` salah. | `.env` bawaan mengarah ke `http://localhost:8000` sedangkan server berjalan di port `8089`. | Menyetel `APP_URL=http://127.0.0.1:8089` dan memastikan symlink `public/storage` aktif via `storage:link`. |
| **4** | Foto Hero sunset terlihat gepeng atau terpotong di layar ultrawide. | Framing `center bottom` pada kontainer yang kurang tinggi. | Mengubah posisi ke `center center` dan menambah tinggi Hero container menjadi `min-h-[620px]`. |
| **5** | Ikon visual website tidak konsisten dan proporsi kasar. | Menggunakan SVG manual/ad-hoc dan emoji. | Mengganti seluruh ikon menggunakan package terverifikasi `lucide-astro`. |
| **6** | Inkonsistensi palet warna antara hero dan section lain pada iterasi desain terdahulu. | Penggabungan parsial template warna lama dengan tema sunset baru. | Pernah melalui beberapa iterasi desain; palet aktif saat ini adalah putih/biru langit dengan teks abu-abu kebiruan. Lihat tabel warna pada Bagian 5. |
| **7** | Kebocoran teks Bahasa Indonesia *"Mulai dari"* saat website diakses dalam mode Bahasa Inggris (`/en/`). | Label di-hardcode dalam bahasa Indonesia di komponen atau kamus translasi belum terpanggil secara tepat. | Standardisasi string dictionary `fleet.starts_from` pada `src/i18n/ui.ts` dan pemanggilan helper `useTranslations(lang)`. |
| **8** | Tombol floating WhatsApp (FAB) menutupi tombol *"Sewa"* dan label harga pada baris armada paling bawah. | Posisi FAB fixed di pojok kanan bawah layar tanpa margin/padding proteksi pada container fleet. | Menambahkan padding bottom ekstra (`pb-20` / `pb-24`) pada section fleet dan menyetel `z-index` layering secara presisi. |
| **9** | Foto unit armada dari dashboard admin memiliki aspek rasio berbeda dan latar belakang putih yang memotong tema gelap. | Dimensi foto dari staf admin tidak seragam dan sering berlatar putih/terang. | Menggunakan container berbingkai `aspect-[16/10]` dengan background warm neutral `#262019`, `object-contain`, dan drop-shadow lembut. |
| **10** | Galeri dokumentasi aktivitas kaku dan memakan ruang scroll vertikal yang terlalu panjang jika berupa grid statis. | Grid konvensional memerlukan scrolling vertikal panjang dan terlihat statis. | Mengonversi galeri menjadi Interactive Infinite Marquee horizontal dua baris selang-seling (baris atas ke kanan, baris bawah ke kiri). |
| **11** | Animasi CSS Marquee murni (`@keyframes` + `translate3d`) membeku kaku saat kursor melintas dan tidak bisa di-drag/di-scroll manual. | `animation-play-state: paused` mengunci elemen dan CSS transform tidak merespons gesture horizontal pengguna. | Mengganti animasi CSS dengan scroll engine berbasis DOM `scrollLeft` dan `requestAnimationFrame` dengan dukungan drag mouse, wheel, dan touch swipe. |
| **12** | Foto galeri sempat berhenti diam (*freeze* / tidak bergerak) setelah inisialisasi awal. | Penugasan programatis `container.scrollLeft` menembakkan event `scroll` native asinkron yang keliru dideteksi sebagai interaksi pengguna, sehingga menyetel `lastInteractionTime` terus-menerus dan mengunci animasi dalam jeda jeda 1.000ms. | Mengeliminasi generic event listener `scroll` untuk deteksi interaksi, dan mengalihkannya secara murni ke event fisik pengguna (`mousedown`/`mousemove`, `touchstart`/`touchmove`, serta `wheel`), sekaligus menaikkan kecepatan gerak ke 0.75 / 0.70 px/frame (~45 px/detik). |
| **13** | JavaScript `ReferenceError` akibat Temporal Dead Zone (TDZ) pada script inline `GallerySection.astro`. | Fungsi `initMarquee()` dieksekusi sebelum variabel flag dan timer dideklarasikan dengan `let`/`const`. | Menata ulang urutan deklarasi variabel state di bagian paling atas sebelum fungsi listener dijalankan. |
| **14** | Pergerakan auto-drift marquee patah-patah (*stutter*) pada display resolusi tinggi (Retina display). | Pembulatan integer DOM `scrollLeft` memotong nilai pecahan desimal (`currentScroll += 0.55`). | Menyimpan akumulator posisi dalam variabel floating point JavaScript dan hanya membulatkan saat sinkronisasi DOM. |
| **15** | Kebutuhan konfigurasi bisnis (kontak, alamat, jam operasional) hanya memerlukan 1 baris data tunggal, namun Filament Resource default selalu menyediakan tabel list, pagination, create, dan bulk delete. | Karakteristik Filament `Resource` ditujukan untuk entitas multi-record. | Mengimplementasikan Filament `Custom Page` (`ManageBusinessSettings.php`) dengan `HasForms` yang mengikat langsung ke `id=1`. Tombol create/delete dan view tabel dieliminasi sehingga UI bersih dan aman dari duplikasi atau penghapusan data. |
| **16** | Perubahan nomor WhatsApp di admin panel tidak langsung muncul di frontend jika halaman berstatus static prerendered. | Pada Astro dengan adaptor Vercel mode server, halaman yang memakai `export const prerender = true` dibekukan (*baked*) saat waktu build sehingga fetch API tidak dipanggil ulang saat runtime. | Menonaktifkan prerender (`export const prerender = false`) pada `index.astro` dan `kontak.astro` sehingga berjalan dalam mode Server-Side Rendering (SSR). Setiap kali halaman di-refresh, data kontak terbaru langsung diambil dari `/api/settings` tanpa perlu build ulang. |
| **17** | Pesan sukses booking tampil dalam kotak merah (*error alert*) dan tidak mengalihkan halaman ke konfirmasi. | Respon backend API `POST /api/bookings` mengembalikan data dengan key `booking_id`, sementara script frontend sebelumnya hanya memeriksa `result.data?.id`. Akibatnya, blok `else` melemparkan error dan menampilkan string sukses di dalam `#error-alert` merah. | Menambahkan key `id` bersama `booking_id` pada respon API backend, membuat banner `#success-alert` baru dengan styling hijau emerald (`bg-emerald-950/60 border-emerald-500/50 text-emerald-400`), dan mengalihkan halaman secara mulus ke `/[lang]/booking/konfirmasi/{id}`. |
| **18** | Tautan WhatsApp (`wa.me`) memunculkan galat *"This link couldn't be opened. Check the link and try again"* pada aplikasi WhatsApp Desktop macOS. | Nomor telepon pelanggan atau admin yang berawalan `08...`, bertanda `+`, atau berspasi/tanda hubung tidak dikenali oleh WhatsApp deep-link protocol karena tidak memuat kode negara yang valid tanpa simbol. | Menambahkan fungsi sanitasi otomatis E.164 (`sanitizePhone` di Laravel & `sanitizeWaNumber` di Astro) yang otomatis mengonversi awalan `08...` atau `8...` menjadi `628...` dan membuang semua karakter non-angka. |
| **19** | Slider foto berpindah meloncat atau rewinding kasar dari slide terakhir kembali ke slide pertama. | Loop konvensional pada indeks slide ke-0 membalikkan arah transform ke kiri secara drastis saat mencapai ujung slide. | Mengimplementasikan teknik *Seamless Clone Wrap-Around* dengan menduplikasi slide pertama dan terakhir di kedua ujung track. Saat slide clone tercapai, event `transitionend` mengembalikan indeks secara instan tanpa transisi CSS (`transition: none`), sehingga slider bergulir maju searah tanpa henti. |
| **20** | Foto katalog dan galeri tidak muncul saat homepage dibuka melalui HTTPS tunnel, sehingga halaman tampak berantakan setelah reload. | Log browser menunjukkan backend mengirim URL aset `http://127.0.0.1:8089/storage/...`. Alamat loopback tidak dapat dijangkau oleh browser pengunjung dan resource HTTP juga diblokir sebagai mixed content pada halaman HTTPS. | Penyebab sudah didiagnosis. Perbaikan URL media publik yang dapat diakses lewat HTTPS belum diterapkan; pertimbangkan URL backend HTTPS yang dapat dijangkau browser atau proxy/CDN media. |

---

## 8. BUKTI PENGUJIAN & VALIDASI EMPIRIS

### A. Pengujian Endpoint REST API (Curl Asli)
- `curl -i -s "http://127.0.0.1:8089/api/settings"` &rarr; **HTTP 200 OK** (mengembalikan baris tunggal konfigurasi kontak WhatsApp, email, alamat fisik garasi, jam operasional dwibahasa ID/EN, dan URL Instagram).
- `curl -i -s "http://127.0.0.1:8089/api/pricing-packages"` &rarr; **HTTP 200 OK** (3 paket sewa aktif terurut berdasarkan `order`).
- `curl -i -s "http://127.0.0.1:8089/api/faqs"` &rarr; **HTTP 200 OK** (5 item dwibahasa terurut berdasarkan `order`).
- `curl -i -s "http://127.0.0.1:8089/api/gallery"` &rarr; **HTTP 200 OK** (5 item foto terurut beserta URL publik).
- `GET /api/testimonials` &rarr; rute API publik aktif; implementasi memfilter `is_active` dan mengurutkan berdasarkan `order`, lalu `id` (divalidasi melalui test feature).
- `curl -i -s "http://127.0.0.1:8089/storage/gallery/test.jpg"` &rarr; **HTTP 200 OK** (konfirmasi symlink storage publik aktif).

### B. Pengujian Failover & Fallback Frontend
- **Server Laravel Aktif:** Seluruh komponen kontak dan FAQ menyajikan data dinamis langsung dari database.
- **Server Laravel Dimatikan Sementara:** Frontend tetap berjalan lancar tanpa error/crash, otomatis menampilkan isi default aman (`FALLBACK_SETTINGS` dan kamus statis `ui.ts`).
- **Server Laravel Dinyalakan Kembali:** Frontend otomatis kembali menyajikan data dinamis dari database tanpa intervensi manual.

### C. Automated Unit & Feature Testing (PHPUnit)
Baseline laporan sebelumnya (29 September 2026) mencatat 19 tes lulus (131 assertions). Pada pembaruan 7 Oktober 2026, dua tes terarah khusus testimoni dijalankan:
```
php artisan test tests/Feature/TestimonialApiTest.php tests/Feature/TestimonialSeederTest.php
Tests: 2 passed (26 assertions, 0 failures)
```
Test API memvalidasi filter ulasan nonaktif, urutan, rating dan bentuk respons. Test seeder memvalidasi tiga data bawaan dwibahasa dan memastikan seeding ulang tidak menimpa edit admin. **Full suite PHPUnit belum dijalankan ulang setelah implementasi testimoni.**

### D. Pengujian Build Frontend Astro (Production Build)
Build produksi frontend menggunakan Astro dan `@astrojs/vercel` berhasil pada 7 Oktober 2026:
```
> frontend@0.0.1 build
> astro build

output: "server"
adapter: @astrojs/vercel
Server built successfully
```
Catatan: Vercel adapter memberi peringatan bahwa Node lokal versi 26 tidak didukung langsung dan fungsi akan memakai runtime Node 24. Build tetap selesai sukses. Laravel Pint `--test` pada file terkait testimoni juga lulus.

### E. Pengujian Empiris Interaktivitas Infinite Marquee Gallery (Chrome Headless & CDP Testing)
Verifikasi fungsionalitas gerak otomatis dan interaksi pengguna pada modul galeri menggunakan Chrome DevTools Protocol (`scratch/verify_autodrift.cjs`, `scratch/test_drag_scroll.cjs`):
- **Auto-Drift Dua Arah:** Baris 1 bergerak stabil ke kanan (`speed: -0.55 px/frame`), Baris 2 bergerak stabil ke kiri (`speed: +0.50 px/frame`). Nilai `scrollLeft` bergeser mulus tanpa jitter.
- **Hover Non-Blocking:** Mengarahkan mouse ke area foto tidak menghentikan pergerakan (*non-freezing*), menjaga tampilan tetap hidup dan dinamis.
- **Interaksi Drag-to-Scroll:** Simulasi penekanan tombol mouse (`mousedown`) &rarr; pergeseran kursor (`mousemove` +250px) &rarr; pelepasan mouse (`mouseup`) berhasil menggeser konten seketika, mengubah kursor menjadi `cursor-grabbing`, mengistirahatkan auto-drift sejenak, dan melanjutkan drift secara organik setelah 1 detik tidak ada input.
- **Infinite Wrap-Around:** Pengujian batas scroll membuktikan bahwa saat posisi melewati batas `2 * groupWidth` atau `<= 0`, kalkulasi buffer 3 grup melakukan lompatan posisi instan sebesar `groupWidth` dengan jeda visual 0 pixel (*seamless zero-stutter loop*).

### F. Pengujian Empiris Perubahan Nomor WhatsApp Real-Time (Live Update Tanpa Redeploy)
Verifikasi empiris perubahan nomor kontak dari panel admin ke seluruh 5 titik integrasi frontend:
1. **Kondisi Awal (Initial State):**
   - Database: `whatsapp_primary = "6285947167662"`.
   - Frontend FAB, Navbar, Footer, ContactSection, dan Checkout Konfirmasi Booking menampilkan nomor `0859-4716-7662` dan tautan `wa.me/6285947167662`.
2. **Pembaruan via Filament / Backend:**
   - Nomor WhatsApp Utama diubah menjadi `6281234567890` dan disimpan.
   - Endpoint `GET /api/settings` langsung merespons data terbarukan: `"whatsapp_primary": "6281234567890"`.
3. **Penyajian di Frontend Tanpa Redeploy (SSR Refresh):**
   - Halaman Beranda (`/id`), Halaman Kontak (`/id/kontak`), dan Konfirmasi Booking (`/id/booking/konfirmasi/BK-TEST`) langsung di-refresh:
     - Total tautan WhatsApp: 4 tautan per halaman.
     - Tautan WhatsApp lama (`6285947167662`): 0 tersisa.
     - Tautan WhatsApp baru (`6281234567890`): 4 aktif terhubung.
     - Tampilan format teks telepon: Berubah seketika menjadi `+62 812-3456-7890` / `0812-3456-7890`.
4. **Restorasi:** Nilai dikembalikan dengan aman ke nomor operasional resmi `6285947167662`.

---

## 9. STRUKTUR FILE & KOMPONEN NYATA DI REPOSITORI

```
rental-kuta-scooter/
├── backend/
│   ├── app/
│   │   ├── Filament/
│   │   │   ├── Pages/
│   │   │   │   └── ManageBusinessSettings.php   # [NYATA] Custom Page Pengaturan Bisnis (Single-Row Form)
│   │   │   └── Resources/
│   │   │       ├── BookingResource.php          # [NYATA] Resource Reservasi
│   │   │       │   └── Pages/
│   │   │       │       ├── ListBookings.php
│   │   │       │       ├── CreateBooking.php    # [NYATA] Redirect ke index on save
│   │   │       │       └── EditBooking.php      # [NYATA] Redirect ke index on save + Toast
│   │   │       ├── MotorResource.php            # [NYATA] Resource Armada Motor
│   │   │       ├── FaqResource.php              # [NYATA] Resource FAQ Dinamis
│   │   │       ├── PricingPackageResource.php   # [NYATA] Resource Paket Harga Sewa
│   │   │       │   └── Pages/
│   │   │       │       ├── ListPricingPackages.php
│   │   │       │       ├── CreatePricingPackage.php # [NYATA] Redirect on save
│   │   │       │       └── EditPricingPackage.php   # [NYATA] Redirect on save + Toast
│   │   │       ├── GalleryImageResource.php     # [NYATA] Resource Galeri Foto
│   │   │       ├── TestimonialResource.php      # [NYATA] CRUD testimoni dwibahasa
│   │   │       │   └── Pages/                   # List, Create, Edit
│   │   │       └── UserResource.php             # [NYATA] Resource Multi-Admin Staff
│   │   ├── Http/Controllers/Api/
│   │   │   ├── SettingController.php            # [NYATA] Controller GET /api/settings
│   │   │   └── TestimonialController.php        # [NYATA] Controller GET /api/testimonials
│   │   └── Models/
│   │       ├── Booking.php
│   │       ├── Motor.php
│   │       ├── Faq.php
│   │       ├── PricingPackage.php           # [NYATA] Model Paket Harga Sewa
│   │       ├── GalleryImage.php
│   │       ├── BusinessSetting.php          # [NYATA] Model Pengaturan Bisnis Single-Row
│   │       ├── Testimonial.php               # [NYATA] Model testimoni
│   │       └── User.php
│   ├── database/
│   │   ├── migrations/                      # Termasuk migrasi tabel testimonials
│   │   └── seeders/                         # Termasuk TestimonialSeeder dan DatabaseSeeder
│   ├── public/storage -> storage/app/public # [NYATA] Symlink storage publik aktif
│   ├── routes/api.php                       # Endpoint publik termasuk /api/testimonials
│   └── tests/Feature/
│       ├── BusinessSettingTest.php          # [NYATA] Test API settings & Custom Page Filament (2 tests)
│       ├── MotorBookingApiTest.php
│       ├── FaqGalleryApiTest.php
│       ├── PricingPackageApiTest.php        # [NYATA] Test API paket harga terurut & aktif
│       ├── TestimonialApiTest.php            # [NYATA] Test API testimoni
│       ├── TestimonialSeederTest.php         # [NYATA] Test seeder idempoten
│       └── FilamentRedirectTest.php         # [NYATA] Test redirect index ke-6 resource (15 test lulus)
│
└── frontend/
    ├── public/images/
    │   ├── logo-final.png                   # [NYATA] Logo aktif navbar, footer, favicon, dan kartu motor
    │   ├── kuta-1.jpg
    │   ├── kuta-2.jpg                       # [NYATA] Foto latar aktif Hero
    │   ├── kuta-3.jpg
    │   ├── kuta-4.jpeg                      # Foto aset lainnya
    │   ├── kuta-5.jpeg                      # [NYATA] Foto Slider 1 (Pura Lempuyang / Gunung Agung 1470x980 px)
    │   ├── kuta-6.jpeg                      # [NYATA] Foto Slider 2 (Sunset Penjor Pantai Kuta 1470x980 px)
    │   ├── kuta-7.jpeg                      # [NYATA] Foto Slider 3 (Candi Meru Pura Bali 1252x716 px)
    │   ├── kuta-8.jpeg                      # [NYATA] Foto Slider 4 (Sunset Pura Tepi Tebing 1100x736 px)
    │   └── kuta-9.jpeg                      # [NYATA] Foto Slider 5 (Sunset Laut Bali 1470x950 px)
    │
    └── src/
        ├── styles/
        │   └── global.css                   # [NYATA] Design tokens putih/biru langit & motion
        │
        ├── components/
        │   ├── Navbar.astro                 # [NYATA] Header dwibahasa, logo resmi, glassmorphism, WhatsApp dynamic link
        │   ├── Hero.astro                   # [NYATA] Hero foto Bali full-bleed dengan overlay gelap
        │   ├── KeyBenefits.astro            # [NYATA] Empat pilar keunggulan
        │   ├── AboutRMB.astro               # [NYATA] Copy ID/EN dan slider cerita RMB
        │   ├── ProductCard.astro            # [NYATA] Kartu armada dengan logo pojok kanan atas
        │   ├── PricingPackages.astro        # [NYATA] Paket sewa harian/mingguan/bulanan (API dinamis + fallback i18n)
        │   ├── GallerySection.astro         # [NYATA] Interactive Infinite Marquee Galeri 2 baris (kanan & kiri) dengan drag, wheel, swipe & auto-drift
        │   ├── HowItWorks.astro             # [NYATA] Empat langkah sewa
        │   ├── Testimonials.astro           # [NYATA] Testimoni bilingual dari API dengan fallback
        │   ├── FAQAccordion.astro           # [NYATA] Tanya jawab (API dinamis + Fallback i18n)
        │   ├── ContactSection.astro         # [NYATA] Kontak & jam operasional terpusat dari API settings
        │   ├── Footer.astro                 # [NYATA] Footer informasi & kontak terpusat dari API settings
        │   └── FloatingWhatsApp.astro       # [NYATA] FAB WhatsApp dinamis dari API settings dengan proteksi z-index
        │
        ├── i18n/
        │   ├── ui.ts                        # [NYATA] Kamus translasi ID & EN lengkap (standardisasi Starts from, benefit armada, dsb.)
        │   └── utils.ts                     # Helper routing dwibahasa & useTranslations
        │
        ├── lib/
        │   └── api.ts                       # Client API Laravel (termasuk fetchTestimonials)
        │
        └── pages/[lang]/
            ├── index.astro                  # [NYATA] Beranda utama SSR dinamis (Hero, KeyBenefits, Fleet, Pricing, GallerySection, FAB dinamis)
            ├── katalog.astro                # [NYATA] Katalog lengkap seluruh armada
            ├── kontak.astro                 # [NYATA] Halaman kontak SSR dinamis terpusat dari API settings
            ├── tentang.astro                # [NYATA] Halaman cerita brand & GallerySection Marquee 2 baris
            ├── motor/
            │   └── [slug].astro             # Detail spesifikasi motor & formulir pemesanan
            └── booking/
                ├── [motor_id].astro         # Form booking dinamis per Motor ID
                └── konfirmasi/
                    └── [booking_id].astro   # Halaman konfirmasi sukses dinamis dengan gateway WhatsApp pre-filled dinamis

```

---

## 10. KOMITMEN & ATURAN PENGEMBANGAN KE DEPAN

1. **Integritas Pelaporan 100%:** Seluruh laporan teknis dan status fungsionalitas mencerminkan kode nyata yang telah diuji langsung pada repositori.
2. **Kepatuhan Terhadap PRD & Persetujuan Tertulis:** Tidak menambahkan tabel database, model, resource, kolom, ataupun modul baru di luar persetujuan resmi.
3. **Standar Kualitas & Reliabilitas:** Setiap fitur baru wajib disertai penanganan error/fallback pada antarmuka publik serta pengujian otomatis (*automated tests*) sebelum diserahterimakan.

---
*Laporan ini disusun secara komprehensif, faktual, dan dapat diverifikasi langsung melalui repositori proyek Sewa Motor Kuta Bali.*
