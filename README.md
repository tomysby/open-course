
# ðŸŽ“ Open Course Platform

**Open Course** adalah platform pembelajaran daring berbasis web yang memungkinkan instruktur membuat kursus dan pengguna (siswa) untuk mengikuti pembelajaran secara gratis atau berbayar. Dibangun menggunakan **Laravel 12**, **Vue 3**, dan **Tailwind CSS**.

---

## ðŸš€ Fitur Utama

- ðŸ” Autentikasi pengguna (Register, Login)
- ðŸ“š Manajemen Kursus (CRUD)
- ðŸ§© Modul & Pelajaran per kursus
- ðŸ“ Sistem Enrolment (pendaftaran siswa)
- ðŸ“ˆ Progress belajar per siswa
- ðŸ“‚ Upload materi (video, PDF, link)
- ðŸŽ“ Sertifikat (opsional)
- ðŸ§‘â€ðŸ« Role: Admin, Instruktur, Siswa

---

## ðŸ“¦ Teknologi yang Digunakan

- **Backend**: Laravel 10
- **Frontend**: Vue 3 + Vite + Tailwind CSS
- **Database**: MySQL / MariaDB
- **Authentication**: Laravel Sanctum
- **Bundler**: Vite
- **Testing**: PHPUnit

---

## âš™ï¸ Instalasi dan Setup

### 1. Clone repository

```bash
git clone https://github.com/tomysby/open-course.git
cd open-course
```

### 2. Install dependencies backend dan frontend

```bash
composer install
npm install
```

### 3. Konfigurasi `.env`

Salin file contoh:

```bash
cp .env.example .env
```

Ubah isi `.env` agar sesuai dengan environment lokal Anda, contoh:

```env
APP_NAME=OpenCourse
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=open_course
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost
```

### 4. Generate key dan migrate database

```bash
php artisan key:generate
php artisan migrate --seed
```

### 5. Jalankan development server

```bash
php artisan serve
npm run dev
```

Frontend akan berjalan di `http://localhost:5173`, dan backend API di `http://localhost:8000`.

---

## ðŸ” Autentikasi

Gunakan Laravel Sanctum:

- Login: `POST /api/login`
- Logout: `POST /api/logout`
- Register: `POST /api/register`
- Cek user: `GET /api/user`

Pastikan client frontend mengirim cookie dan CSRF token sesuai.

---

## ðŸ“˜ Dokumentasi API (Contoh)

| Method | Endpoint                | Keterangan                    |
|--------|-------------------------|-------------------------------|
| GET    | `/api/courses`          | Daftar kursus publik          |
| GET    | `/api/courses/{slug}`   | Detail kursus                 |
| POST   | `/api/enroll/{id}`      | Enroll ke kursus              |
| GET    | `/api/my-courses`       | Kursus yang diikuti user      |
| POST   | `/api/lessons/{id}/done`| Tandai lesson selesai         |

> Gunakan token auth dari Laravel Sanctum di request yang memerlukan autentikasi.

---

## ðŸ§ª Testing

Jalankan semua unit test:

```bash
php artisan test
```

---

## ðŸ“‚ Struktur Folder Utama

```bash
app/
 â”œâ”€â”€ Models/         # Model data
 â”œâ”€â”€ Http/Controllers/Api/  # API logic
resources/
 â”œâ”€â”€ js/             # Frontend Vue components
routes/
 â”œâ”€â”€ api.php         # Endpoint API
 â”œâ”€â”€ web.php         # Route frontend
database/
 â”œâ”€â”€ migrations/     # Struktur tabel
 â”œâ”€â”€ seeders/        # Data awal
```

---

## ðŸ¤ Kontribusi

Pull request dan kontribusi sangat dihargai! Untuk mulai:

1. Fork repositori ini
2. Buat branch baru (`git checkout -b fitur-anda`)
3. Commit perubahan (`git commit -m 'Tambah fitur'`)
4. Push ke branch Anda (`git push origin fitur-anda`)
5. Buka Pull Request

---

## ðŸ“ƒ Lisensi

MIT License Â© 2025 [Tomy SBY](https://github.com/tomysby)

---

## ðŸ“§ Kontak

Untuk saran, bug, atau kolaborasi:
- Email: tomysby@gmail.com *(ganti sesuai kebutuhan)*
- GitHub: [@tomysby](https://github.com/tomysby)