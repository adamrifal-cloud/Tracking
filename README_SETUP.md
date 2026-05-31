# 🎯 QUICK FIX - Database Migration Error

## ❌ Error Yang Dialami
```
SQLSTATE[42S02]: Base table or view not found: 1146 
Table 'vendor_db.jobs' doesn't exist
```

## ✅ Solusi: Jalankan Setup Script

### 💻 WINDOWS - Opsi Tercepat
```bash
cd c:\xampp\htdocs\logistik-app
setup.bat
```

**Atau:**
```bash
cd c:\xampp\htdocs\logistik-app
python run_setup.py
```

### 🐧 LINUX / MAC
```bash
cd /path/to/logistik-app
bash setup.sh
```

### 🐍 Manual dengan Python (Semua OS)
```bash
cd c:\xampp\htdocs\logistik-app
python run_setup.py
```

---

## 🔧 Jika Containers Belum Running

Sebelum menjalankan setup, pastikan containers sudah berjalan:

```bash
cd c:\xampp\htdocs\logistik-app
docker-compose up -d
```

Tunggu ~10 detik hingga database fully initialized, kemudian jalankan setup.

---

## 📋 Apa yang Dilakukan Script?

✅ Verify Docker installed  
✅ Verify containers running  
✅ Create `jobs` table  
✅ Create `job_batches` table  
✅ Create `failed_jobs` table  
✅ Create `cache` table  
✅ Create `cache_locks` table  
✅ Create `users` table  
✅ Create `sessions` table  
✅ Clear Laravel caches  

---

## 🎬 Step-by-Step Execution

### 1. Open Terminal
```bash
# Windows: cmd.exe atau Git Bash
# Mac/Linux: Terminal
```

### 2. Navigate to Project
```bash
cd c:\xampp\htdocs\logistik-app
```

### 3. Run One of Setup Scripts
```bash
# Windows Batch (Simplest)
setup.bat

# Python (Recommended for all OS)
python run_setup.py

# Or manually with SQL
docker exec vendor-service-db mysql -u root -proot vendor_db < setup-vendor-db.sql
```

### 4. Verify
```bash
# Check if jobs table exists
docker exec vendor-service-db mysql -u root -proot vendor_db -e "SHOW TABLES;"

# Expected output should include:
# | jobs          |
# | job_batches   |
# | failed_jobs   |
```

### 5. Retry Your Operation
The error should now be fixed!

---

## 📝 File Reference

| File | Usage |
|------|-------|
| `setup.bat` | 🔥 Windows setup (EASIEST) |
| `run_setup.py` | 🐍 Python setup (RECOMMENDED) |
| `setup.sh` | 🐧 Linux/Mac setup |
| `setup-vendor-db.sql` | 📄 SQL script for vendor_db |
| `setup-tracking-db.sql` | 📄 SQL script for tracking_db |

---

## ⚡ Expected Result

After running setup:

```
=========================================================================
 SETUP COMPLETED SUCCESSFULLY!
=========================================================================

Database Details:
  Database: vendor_db
  Host: vendor-service-db (docker) / 127.0.0.1:33062
  User: root
  Password: root

Tables Created:
  [OK] jobs
  [OK] job_batches
  [OK] failed_jobs
  [OK] cache
  [OK] cache_locks
  [OK] users
  [OK] sessions

✅ Error FIXED - Your operation can now proceed!
```

---

## 🆘 Troubleshooting

### "Docker is not installed"
→ Install Docker Desktop: https://www.docker.com/products/docker-desktop

### "Container not running"
→ Run: `docker-compose up -d`

### "Permission denied"
→ Run CMD/Terminal as Administrator

### "File not found"
→ Make sure you're in: `c:\xampp\htdocs\logistik-app`

### "MySQL error"
→ Check Docker logs: `docker logs vendor-service-db`

---

## 🚀 Ready?

Just run:
```bash
setup.bat
```

That's it! The error will be fixed in less than 2 minutes.

---

**Created**: 2026-05-31  
**Status**: ✅ Ready to Deploy
