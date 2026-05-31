# Solusi: Tabel Jobs Tidak Ditemukan - Database Migration Fix

## 🔴 Masalah
```
SQLSTATE[42S02]: Base table or view not found: 1146 
Table 'vendor_db.jobs' doesn't exist
```

Tabel `jobs` belum dibuat di database `vendor_db` meskipun migration file sudah ada.

---

## ✅ Solusi Cepat (Windows CMD)

### Opsi 1: Menggunakan Batch Script
```batch
run-migrations.bat
```

### Opsi 2: Menggunakan MySQL CLI langsung
```bash
# Terminal/CMD di folder project
docker exec vendor-service-db mysql -u root -proot vendor_db < setup-vendor-db.sql
```

### Opsi 3: Menggunakan Laravel Artisan (jika aplikasi running)
```bash
docker exec vendor-service-app php artisan migrate --force
```

---

## 📋 Langkah-Langkah Manual

### 1️⃣ Verifikasi Docker Containers Running
```bash
docker ps | grep vendor-service
```

Output harus menunjukkan:
- `vendor-service-app` (Laravel app)
- `vendor-service-db` (MySQL database)

### 2️⃣ Jalankan SQL Setup
```bash
docker exec vendor-service-db mysql -u root -proot vendor_db < setup-vendor-db.sql
```

Ini akan membuat tabel:
- ✅ jobs
- ✅ job_batches
- ✅ failed_jobs
- ✅ cache
- ✅ cache_locks
- ✅ users
- ✅ sessions

### 3️⃣ Verifikasi Tabel
```bash
docker exec vendor-service-db mysql -u root -proot vendor_db -e "SHOW TABLES;"
```

Output harus menunjukkan semua tabel di atas.

### 4️⃣ Test Koneksi Database
```bash
docker exec vendor-service-app php artisan tinker
# Lalu jalankan:
# DB::table('jobs')->count()
# exit
```

---

## 🔧 Troubleshooting

### Container Tidak Running?
```bash
# Start containers
docker-compose up -d

# Atau rebuild
docker-compose down
docker-compose up -d --build
```

### Masih Error setelah setup?
```bash
# Clear Laravel cache
docker exec vendor-service-app php artisan cache:clear
docker exec vendor-service-app php artisan config:cache

# Check migrations status
docker exec vendor-service-app php artisan migrate:status
```

### Mau reset database?
```bash
# ⚠️ WARNING: Ini akan hapus semua data
docker exec vendor-service-db mysql -u root -proot vendor_db -e "DROP TABLE IF EXISTS jobs, job_batches, failed_jobs, cache, cache_locks, users, sessions;"

# Kemudian jalankan setup lagi
docker exec vendor-service-db mysql -u root -proot vendor_db < setup-vendor-db.sql
```

---

## 📊 Database Connection Details

- **Host**: vendor-service-db (docker network) atau 127.0.0.1:33062
- **Database**: vendor_db
- **Username**: root
- **Password**: root
- **Port**: 3306 (internal) / 33062 (external)

---

## 🚀 Berikutnya

Setelah setup berhasil:

1. ✅ Tabel `jobs` sudah ada
2. ✅ Queue system siap menerima job
3. ✅ Cache storage siap digunakan
4. 🔄 Retry operasi yang sebelumnya error

---

## 📝 File yang Dibuat

| File | Fungsi |
|------|--------|
| `setup.sh` | Bash script lengkap untuk Linux/Mac |
| `run-migrations.bat` | Batch script untuk Windows |
| `setup-vendor-db.sql` | SQL script untuk membuat tabel |
| `MIGRATION_FIX.md` | File dokumentasi ini |

---

## 💡 Tips Fullstack Dev

1. **Jangan hardcode migrations** - Selalu jalankan migrations setelah setup project
2. **Dokumentasi database** - Keep track of all tables dan migrations
3. **Automated setup** - Gunakan migration scripts untuk environment setup
4. **Version control** - Commit migration files, jangan database
5. **Testing** - Test queue operations setelah setup database

---

**Last Updated**: 2026-05-31  
**Status**: ✅ Ready to Deploy
