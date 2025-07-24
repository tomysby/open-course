```markdown
# 🎓 Open Course Platform

**Open Course** adalah platform pembelajaran daring (e-learning) berbasis web yang memungkinkan instruktur membuat kursus, serta pengguna (siswa) untuk mengikuti pembelajaran secara gratis atau berbayar.  

Dibangun dengan teknologi modern:
- **Backend**: Laravel 10
- **Frontend**: Vue 3 + Vite
- **Styling**: Tailwind CSS

---

## 🚀 Fitur Utama

- 🔐 Autentikasi pengguna (Register, Login, Logout)
- 📚 Manajemen Kursus (CRUD: Create, Read, Update, Delete)
- 🧩 Modul & Pelajaran per kursus
- 📝 Sistem Pendaftaran (Enrolment) siswa
- 📈 Pelacakan progres belajar per siswa
- 📂 Upload materi (video, PDF, link eksternal)
- 🎓 Generasi sertifikat (opsional)
- 👨‍🏫 Role pengguna: Admin, Instruktur, Siswa

---

## 🛠️ Teknologi yang Digunakan

| Komponen       | Teknologi                     |
|----------------|-------------------------------|
| Backend        | Laravel 10                    |
| Frontend       | Vue 3 + Vite + Tailwind CSS   |
| Database       | MySQL / MariaDB               |
| Autentikasi    | Laravel Sanctum (SPA)         |
| Bundler        | Vite                          |
| Testing        | PHPUnit                       |
| Server         | Apache / Nginx / PHP Built-in |

---

## ⚙️ Instalasi & Setup

### 1. Clone Repository

```bash
git clone https://github.com/tomysby/open-course.git
cd open-course
```

### 2. Install Dependencies

```bash
# Backend (Laravel)
composer install

# Frontend (Vue + Vite)
npm install
```

### 3. Konfigurasi Environment

Salin file environment dan sesuaikan konfigurasinya:

```bash
cp .env.example .env
```

Edit file `.env` sesuai kebutuhan lokal Anda:

```env
APP_NAME=OpenCourse
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=open_course
DB_USERNAME=root
DB_PASSWORD=

# Sanctum & CORS
SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost
```

### 4. Generate Key & Migrasi Database

```bash
php artisan key:generate
php artisan migrate --seed
```

> ⚠️ Pastikan database `open_course` sudah dibuat di MySQL/MariaDB.

### 5. Jalankan Server

- **Backend (API)**:
  ```bash
  php artisan serve
  ```
  Akan berjalan di `http://localhost:8000`

- **Frontend (Vue)**:
  ```bash
  npm run dev
  ```
  Akan berjalan di `http://localhost:5173`

> 🔌 Pastikan kedua server berjalan bersamaan. Frontend akan mengakses API dari `localhost:8000`.

---

## 🔐 Autentikasi

Proyek ini menggunakan **Laravel Sanctum** untuk autentikasi SPA (Single Page Application). Endpoint utama:

| Method | Endpoint             | Deskripsi                     |
|--------|----------------------|-------------------------------|
| POST   | `/api/register`      | Registrasi pengguna baru      |
| POST   | `/api/login`         | Login pengguna                |
| GET    | `/api/user`          | Dapatkan data pengguna saat ini |
| POST   | `/api/logout`        | Logout                        |

> 📌 Sanctum menggunakan cookie session, jadi pastikan frontend mengirimkan CSRF token dan mengizinkan kirim cookie (credentials: 'include').

---

## 📄 Dokumentasi API (Contoh)

| Method | Endpoint                     | Deskripsi                              |
|--------|------------------------------|----------------------------------------|
| GET    | `/api/courses`               | Daftar semua kursus publik             |
| GET    | `/api/courses/{slug}`        | Detail kursus berdasarkan slug         |
| POST   | `/api/enroll/{course_id}`    | Mendaftar ke kursus                    |
| GET    | `/api/my-courses`            | Kursus yang sedang diikuti pengguna    |
| POST   | `/api/lessons/{id}/done`     | Tandai pelajaran sebagai selesai       |
| GET    | `/api/user/progress/{course_id}` | Dapatkan progres belajar pengguna |

> 🔐 Gunakan autentikasi Sanctum (cookie session) untuk endpoint yang memerlukan login.

---

## 🧪 Testing

Jalankan semua unit test Laravel:

```bash
php artisan test
```

---

## 📁 Struktur Folder Utama

```
app/
 ├── Models/               # Model Eloquent
 └── Http/Controllers/Api/ # Controller API

resources/
 └── js/                   # Komponen Vue, router, store

routes/
 ├── api.php               # Endpoint API
 └── web.php               # Route frontend (untuk inertia)

database/
 ├── migrations/           # Skema database
 └── seeders/              # Data awal (user dummy, kursus contoh, dll)

public/
 └── storage/              # Link storage (php artisan storage:link)
```

---

## 🌐 CORS & Sanctum Setup

Pastikan `sanctum` di Laravel diatur dengan benar untuk SPA:

1. Tambahkan `localhost:5173` ke `config/sanctum.php`:
   ```php
   'stateful' => ['localhost:5173'],
   ```

2. Di `.env`, pastikan:
   ```env
   SANCTUM_STATEFUL_DOMAINS=localhost:5173
   SESSION_DOMAIN=localhost
   ```

3. Di frontend (Vue), gunakan `axios` dengan opsi:
   ```js
   axios.defaults.withCredentials = true;
   ```

---

## 💡 Kontribusi

Kontribusi sangat diterima! Berikut langkahnya:

1. Fork repositori ini
2. Buat branch baru: `git checkout -b fitur/atau-fix`
3. Lakukan perubahan dan commit: `git commit -m 'Tambah fitur X'`
4. Push ke branch: `git push origin fitur/atau-fix`
5. Buat **Pull Request** ke `main`

---

## 📜 Lisensi

Proyek ini dilisensikan di bawah **MIT License**.  
© 2025 [Tomy SBY](https://github.com/tomysby)

---

## 📞 Kontak

Jika ada pertanyaan, bug, atau ingin berkolaborasi:

- 📧 Email: tomysby@gmail.com  
- 💼 GitHub: [@tomysby](https://github.com/tomysby)

Terima kasih telah menggunakan **Open Course**! 🙏
```

---

### ✅ Cara Menyimpan sebagai `README.md`:

1. Buka **Notepad** (Windows), **TextEdit** (Mac), atau editor kode (VS Code, dll).
2. Tempel (paste) seluruh teks di atas.
3. Simpan dengan nama: `README.md`
4. Pilih **Save as type: All Files** (jika di Notepad)
5. Ekstensi harus `.md`, bukan `.txt`

Contoh nama file:  
✅ `README.md`  
❌ `README.md.txt`

---

Jika kamu ingin saya bantu mengunduhnya sebagai file, kamu bisa salin isi ini ke proyekmu, atau gunakan tools seperti [GitHub Gist](https://gist.github.com) untuk menyimpannya.

Ingin saya buatkan versi **Bahasa Inggris** juga? 😊