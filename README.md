# Praktikum Pemrograman Web 2

## Deskripsi

Project ini dibuat untuk memenuhi praktikum mata kuliah Pemrograman Web 2.

Aplikasi menggunakan Laravel sebagai backend/API dan Vue.js dengan Vite sebagai frontend.

## Teknologi

- PHP
- Laravel
- Composer
- Vue.js
- Vite
- Node.js
- NPM
- Git
- Database

## Struktur Project

```text
praktikum-web2_bayunandapamungkas/
├── backend/        # Laravel Backend
├── frontend/       # Vue + Vite Frontend
├── .gitignore
└── README.md



# Helpdesk API v1

REST API untuk pengelolaan tiket helpdesk dengan autentikasi Laravel Sanctum, pembatasan akses berdasarkan kepemilikan tiket, role admin, validasi request, pagination, rate limiting, CORS, serta pengelolaan token.

## 1. Teknologi dan Versi

Project dijalankan menggunakan:

* PHP **8.3.32**
* Laravel **13.32.0**
* Laravel Sanctum **4.3.3**
* Composer **2.10.3**
* Database **MySQL**
* API version **v1**
* Authentication **Laravel Sanctum Personal Access Token**

Namespace controller API:

```text
App\Http\Controllers\Api\V1
```

Base URL:

```text
http://127.0.0.1:8000/api/v1
```

---

## 2. Instalasi Project

Clone atau salin project kemudian masuk ke folder backend.

Install dependency Composer:

```bash
composer install
```

Salin konfigurasi environment:

```bash
cp .env.example .env
```

Pada Windows PowerShell dapat menggunakan:

```powershell
Copy-Item .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Konfigurasikan database pada `.env`.

Contoh:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

> Nilai password database yang sebenarnya tidak boleh dimasukkan ke repository Git atau dokumentasi publik.

---

## 3. Migration dan Seeder

Jalankan migration:

```bash
php artisan migrate
```

Untuk database latihan/demo, jalankan seeder:

```bash
php artisan db:seed --class=ApiDemoSeeder
```

Seeder digunakan untuk menyediakan data demo seperti user, kategori, dan tiket yang diperlukan untuk pengujian API.

Jangan menjalankan seeder pada database produksi tanpa memastikan isi data yang akan dibuat.

---

## 4. Menjalankan Server

Jalankan Laravel development server:

```bash
php artisan serve
```

API dapat diakses melalui:

```text
http://127.0.0.1:8000
```

Base API:

```text
http://127.0.0.1:8000/api/v1
```

---

## 5. Autentikasi

API menggunakan **Laravel Sanctum Personal Access Token**.

Login dilakukan melalui:

```http
POST /api/v1/auth/login
```

Request:

```json
{
    "email": "ani@example.com",
    "password": "PASSWORD_DEMO",
    "device_name": "Postman"
}
```

Response berhasil mengembalikan token:

```json
{
    "token_type": "Bearer",
    "access_token": "...",
    "expires_at": "...",
    "user": {
        "id": 11,
        "name": "Ani"
    }
}
```

Token digunakan pada request berikutnya:

```http
Authorization: Bearer <access_token>
```

Token tidak boleh ditulis ke README, repository, screenshot publik, atau file koleksi yang akan dibagikan.

---

## 6. Kontrak Endpoint

### Authentication

| Method | Endpoint       | Keterangan                               |
| ------ | -------------- | ---------------------------------------- |
| POST   | `/auth/login`  | Login dan mendapatkan token              |
| POST   | `/auth/logout` | Mencabut token aktif                     |
| GET    | `/me`          | Melihat identitas user yang sedang login |

### Categories

| Method | Endpoint      | Keterangan              |
| ------ | ------------- | ----------------------- |
| GET    | `/categories` | Melihat daftar kategori |

### Tickets

| Method | Endpoint            | Keterangan                               |
| ------ | ------------------- | ---------------------------------------- |
| GET    | `/tickets`          | Daftar tiket milik user                  |
| POST   | `/tickets`          | Membuat tiket                            |
| GET    | `/tickets/{ticket}` | Melihat detail tiket                     |
| PUT    | `/tickets/{ticket}` | Memperbarui tiket                        |
| PATCH  | `/tickets/{ticket}` | Memperbarui tiket dengan payload lengkap |
| DELETE | `/tickets/{ticket}` | Menghapus tiket                          |

### Reports

| Method | Endpoint           | Keterangan                           |
| ------ | ------------------ | ------------------------------------ |
| GET    | `/reports/summary` | Ringkasan jumlah tiket, khusus admin |

---

## 7. Status HTTP

API menggunakan status HTTP sesuai hasil operasi.

| Status | Arti                                            |
| ------ | ----------------------------------------------- |
| 200    | Request berhasil                                |
| 201    | Resource berhasil dibuat                        |
| 204    | Berhasil tanpa response body                    |
| 401    | Belum terautentikasi atau token tidak valid     |
| 403    | Sudah terautentikasi tetapi tidak memiliki izin |
| 404    | Resource tidak ditemukan                        |
| 405    | HTTP method tidak didukung                      |
| 422    | Data request tidak valid                        |
| 429    | Terlalu banyak request                          |

Contoh:

* Login berhasil → `200`
* Create ticket → `201`
* Logout → `204`
* Token tidak valid → `401`
* Mengakses tiket milik user lain → `403`
* ID tiket tidak ditemukan → `404`
* Validation error → `422`
* Rate limit terlampaui → `429`

---

## 8. Kepemilikan dan Authorization

Tiket menggunakan aturan kepemilikan.

User hanya dapat membaca, mengubah, atau menghapus tiket miliknya sendiri.

Contoh:

```text
Ani → tiket Ani → diizinkan
Budi → tiket Ani → ditolak
Admin → tiket Ani → tetap mengikuti aturan ownership untuk operasi tiket
```

Akses yang tidak sesuai ownership menghasilkan:

```http
403 Forbidden
```

Authorization diterapkan melalui policy dan middleware/gate yang sesuai.

Admin memiliki akses terhadap endpoint laporan:

```http
GET /api/v1/reports/summary
```

User biasa mendapatkan:

```http
403 Forbidden
```

sedangkan admin mendapatkan response `200`.

---

## 9. Identitas User Berasal dari Server

Identitas pemilik tiket tidak ditentukan oleh client.

Client tidak boleh menggunakan field seperti:

```json
{
    "user_id": 12
}
```

untuk mengganti pemilik tiket.

Field manipulatif seperti:

```json
{
    "is_admin": true
}
```

juga tidak digunakan untuk memberikan hak admin.

Status dan identitas yang bersifat server-controlled ditentukan oleh sistem berdasarkan user yang sedang terautentikasi.

---

## 10. Validation

Request tiket divalidasi sebelum diproses.

Validasi mencakup antara lain:

* subject wajib diisi
* subject tidak boleh hanya berisi spasi
* subject memiliki batas panjang
* description memiliki batas panjang
* note memiliki batas panjang
* category harus valid
* `is_urgent` harus memiliki tipe yang sesuai
* status harus menggunakan nilai yang diizinkan

Request yang tidak memenuhi aturan menghasilkan:

```http
422 Unprocessable Entity
```

Request yang gagal validasi tidak boleh menghasilkan perubahan data.

---

## 11. Pagination

Endpoint daftar tiket mendukung pagination.

Contoh:

```http
GET /api/v1/tickets?page=1&per_page=10
```

Parameter:

```text
page
per_page
```

Nilai parameter harus memenuhi aturan validasi yang ditentukan API.

Pagination digunakan agar API tidak mengembalikan seluruh data sekaligus.

---

## 12. Update dan PATCH

Endpoint update:

```http
PUT /api/v1/tickets/{ticket}
```

dan:

```http
PATCH /api/v1/tickets/{ticket}
```

menggunakan payload lengkap sesuai kontrak request API.

Contoh:

```json
{
    "subject": "Judul tiket",
    "description": "Deskripsi tiket",
    "category_id": 1,
    "is_urgent": false,
    "status": "pending",
    "note": "Catatan perubahan"
}
```

Meskipun menggunakan method `PATCH`, implementasi API mengharuskan payload lengkap.

Field seperti `user_id` dan `is_admin` tidak boleh digunakan untuk memanipulasi ownership atau hak akses.

---

## 13. Closed Ticket

Tiket dengan status:

```text
closed
```

tidak dapat dihapus.

Percobaan menghapus tiket closed menghasilkan:

```http
422 Unprocessable Entity
```

Tiket dengan status yang masih dapat dihapus akan menghasilkan:

```http
204 No Content
```

Response `204` tidak memiliki response body.

---

## 14. Token Expiry dan Logout

Token autentikasi memiliki masa berlaku.

Token yang sudah kedaluwarsa tidak dapat digunakan untuk mengakses endpoint yang membutuhkan autentikasi.

Response:

```http
401 Unauthorized
```

Logout dilakukan melalui:

```http
POST /api/v1/auth/logout
```

Logout mencabut token aktif.

Setelah token dicabut, request menggunakan token yang sama akan menghasilkan:

```http
401 Unauthorized
```

Token user lain tidak ikut dicabut.

---

## 15. Rate Limiting

API menggunakan rate limiter untuk membatasi jumlah request dalam periode tertentu.

Jika jumlah request melebihi batas yang ditentukan, API dapat memberikan:

```http
429 Too Many Requests
```

Response rate limit dapat memberikan informasi:

```text
Retry-After
```

Client harus menunggu sampai periode pembatasan selesai sebelum melakukan request kembali.

Pengujian rate limit harus memperhitungkan request yang telah dilakukan sebelumnya pada periode yang sama.

---

## 16. CORS

API menyediakan konfigurasi CORS untuk client web yang diizinkan.

Origin yang digunakan untuk latihan:

```text
http://localhost:5173
```

Preflight menggunakan method:

```http
OPTIONS
```

Origin yang diizinkan mendapatkan header:

```text
Access-Control-Allow-Origin: http://localhost:5173
```

Origin lain tidak mendapatkan header `Access-Control-Allow-Origin` untuk origin tersebut.

CORS merupakan mekanisme browser dan bukan pengganti autentikasi atau authorization.

Postman dapat mengirim request tanpa mengikuti pembatasan browser CORS. Oleh karena itu, keberhasilan request di Postman tidak membuktikan bahwa request tersebut akan dapat dibaca oleh browser dari origin yang tidak diizinkan.

---

## 17. Keamanan Response

Response API tidak boleh mengekspos informasi sensitif seperti:

* password
* password hash
* remember token
* access token user lain
* credential database
* informasi internal yang tidak diperlukan client
* stack trace pada response produksi

Access token hanya diberikan ketika proses login berhasil.

Token tidak boleh disimpan dalam README atau repository.

---

## 18. Postman Collection

Koleksi pengujian API tersedia di:

```text
docs/helpdesk-v1.postman_collection.json
```

Import file tersebut ke Postman.

Setelah import:

1. Pastikan server Laravel sedang berjalan.
2. Jalankan login Ani.
3. Jalankan login Budi.
4. Jalankan login Admin.
5. Pastikan token tersimpan pada collection variable.
6. Jalankan request secara berurutan.
7. Periksa status HTTP dan response.
8. Jalankan seluruh collection menggunakan Collection Runner jika diperlukan.

Collection menggunakan variable sehingga token dan ID hasil request dapat digunakan oleh request berikutnya.

Sebelum mengunggah collection ke repository, pastikan:

* password tidak tersimpan
* access token tidak tersimpan
* cookie tidak tersimpan
* credential database tidak tersimpan
* secret environment tidak tersimpan

---

## 19. Versioning API

API menggunakan prefix:

```text
/api/v1
```

Controller API menggunakan namespace:

```text
App\Http\Controllers\Api\V1
```

Versioning digunakan untuk menjaga kontrak API.

Perubahan yang dapat memengaruhi client antara lain:

* perubahan arti field
* perubahan nama field
* perubahan struktur response
* perubahan field wajib
* perubahan aturan authentication
* perubahan status HTTP
* perubahan format request

Perubahan yang tidak kompatibel sebaiknya diperkenalkan melalui versi API baru, misalnya:

```text
/api/v2
```

Client lama tetap menggunakan:

```text
/api/v1
```

Selama masa transisi, perubahan dan penghentian versi lama perlu didokumentasikan dengan jelas.

Mengganti URL saja tidak cukup jika struktur atau perilaku kontrak API juga berubah.

---

## 20. Route Lama

Route CRUD lama untuk tiket tidak digunakan sebagai jalur publik API.

Akses ke route lama tanpa authentication tidak boleh memberikan akses CRUD yang tidak terlindungi.

Client API menggunakan route versioned:

```text
/api/v1/...
```

Route lama yang sudah ditutup dapat menghasilkan:

```text
404 Not Found
```

atau:

```text
405 Method Not Allowed
```

tergantung konfigurasi routing.

---

## 21. Batasan Demo

Project ini digunakan sebagai API latihan/demo.

Beberapa batasan:

* environment masih menggunakan konfigurasi lokal
* debug mode digunakan untuk development
* credential demo hanya digunakan untuk pengujian
* database demo bukan database produksi
* konfigurasi keamanan produksi perlu disesuaikan sebelum deployment
* token tidak boleh dibagikan secara publik
* `.env` tidak boleh dimasukkan ke repository

Untuk production, gunakan konfigurasi environment yang aman dan jangan mengaktifkan debug mode.

---

## 22. Perintah Utama

Install dependency:

```bash
composer install
```

Generate key:

```bash
php artisan key:generate
```

Migration:

```bash
php artisan migrate
```

Seeder:

```bash
php artisan db:seed --class=ApiDemoSeeder
```

Menjalankan server:

```bash
php artisan serve
```

Melihat route API:

```bash
php artisan route:list --path=api/v1
```

Melihat status project:

```bash
php artisan about
```

Membersihkan konfigurasi cache:

```bash
php artisan config:clear
```

---

## 23. Informasi Repository

Dokumentasi API dan collection pengujian disimpan dalam folder:

```text
docs/
```

Collection:

```text
docs/helpdesk-v1.postman_collection.json
```

Catatan:

```text
docs/notes.md
```

README ini menjadi dokumentasi utama penggunaan API v1.
