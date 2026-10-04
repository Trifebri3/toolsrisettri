# Research OS — Research Repository & Paper Builder

> **From Ideas to Evidence, From Evidence to Knowledge**  
> Aplikasi Web Personal Research Management & Scientific Paper Builder bertenaga AI untuk peneliti, akademisi, dosen, dan mahasiswa.

---

## Ringkasan Fitur Utama

- **PWA & Offline-First Field Mode**: Berfungsi sebagai aplikasi terpasang di smartphone/laptop, mencatat ide di lab/lahan tanpa koneksi internet dan sinkron otomatis saat online.
- **Dynamic Research Checklist**: Checklist dinamis yang beradaptasi dengan tahapan penelitian (Pondasi → Metodologi → Pengumpulan Data → Analisis → Penulisan Paper).
- **Research Questions & Hypotheses Tracker**: Menghubungkan setiap RQ dengan objektif, hipotesis, dan temuan analisis.
- **Traceability Matrix & Anti-Hallucination Guardrail**: Setiap klaim ilmiah (*scientific claim*) wajib ditautkan ke bukti empiris (*evidence backlink*). Klaim tanpa bukti dicegah masuk ke naskah final.
- **Dokumen Proyek & Multi-File Upload**: Manajemen proposal, ethical clearance, instrumen survei, dataset spreadsheet, hingga foto dokumentasi lapangan.
- **AI Research Assistant**:
  - *Research Mapper*: Sintesis problem statement, RQ, novelty, dan kontribusi.
  - *Literature Synthesizer*: Matriks perbandingan literatur dan gap analysis.
  - *Methodology Consultant*: Checklist validitas desain, variabel, dan instrumen.
  - *Data & Findings Explorer*: Rekomendasi analisis statistik dan korelasi.
  - *Reviewer Mode*: Audit anti-halusinasi klaim ilmiah.
- **Multi-Output Publication Tracker**: Mengonversi satu proyek riset menjadi jurnal Scopus/Sinta, paper konferensi, buku monograf, laporan PKM, atau open dataset (Zenodo/Kaggle).
- **Paper Builder & Markdown Exporter**: Penyusunan draft IMRaD dan ekspor langsung dalam format Markdown (`.md`).

---

## Kredensial Default

Setelah migrasi dan seeder dijalankan, gunakan akun berikut untuk masuk:

- **Email**: `trifebriansah321@gmail.com`
- **Password**: `12344321`

---

## Persyaratan Sistem

- PHP `>= 8.2` (Disarankan PHP 8.4) dengan ekstensi `pdo_sqlite` atau `pdo_mysql`, `mbstring`, `fileinfo`, `curl`
- Composer `>= 2.2`
- Node.js `>= 18.x` & NPM
- Database: SQLite (default dev/local) atau MySQL/PostgreSQL/MariaDB

---

## Langkah Instalasi Cepat (Local Development)

```bash
# 1. Clone repositori
git clone <repository-url>
cd risettools

# 2. Install dependensi PHP & JavaScript
composer install
npm install

# 3. Setup file konfigurasi .env
cp .env.example .env
php artisan key:generate

# 4. Buat symbolic link storage berkas dokumen
php artisan storage:link

# 5. Jalankan migrasi dan seeder database
php artisan migrate:fresh --seed

# 6. Kompilasi asset frontend
npm run build
# Atau untuk hot-reload dev:
npm run dev

# 7. Jalankan server lokal
php artisan serve
```

Akses aplikasi di browser: [http://localhost:8000](http://localhost:8000)

---

## Panduan Siap Deploy (Production Checklist)

### 1. Konfigurasi Environment (`.env`)
Pastikan parameter berikut diset untuk mode produksi:
```ini
APP_NAME="Research OS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

# Database (Gunakan MySQL / PostgreSQL di production)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=user_database
DB_PASSWORD=password_database

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 2. Build & Optimasi Produksi
Jalankan perintah berikut di server produksi:

```bash
# 1. Install dependensi produksi
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 2. Migrasi database
php artisan migrate --force

# 3. Buat symbolic link folder uploads
php artisan storage:link

# 4. Cache konfigurasi, rute, dan blade view
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Pengujian Kualitas & Format Kode
Proyek ini dilengkapi dengan suite pengujian Pest otomatis:
```bash
# Menjalankan seluruh test suite (37 tests)
php artisan test --compact

# Memastikan standar format kode Laravel Pint
vendor/bin/pint --format agent
```

---

## Struktur Folder Kunci

- `app/Models/` — Model Eloquent: `ResearchProject`, `ResearchIdea`, `LiteratureReference`, `ScientificClaim`, `ProjectDocument`, dll.
- `app/Services/AiResearchService.php` — Mesin sintesis AI, deteksi celah riset, dan audit anti-halusinasi.
- `resources/views/` — Blade templates responsif mobile, dashboard, workspace proyek, dan stepper.
- `public/sw.js` & `resources/js/offline-manager.js` — Service Worker dan IndexedDB sync offline mode.
