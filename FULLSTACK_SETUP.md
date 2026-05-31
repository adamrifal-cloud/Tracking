# 🎯 LOGISTIK APP - DATABASE SETUP GUIDE

**Created**: 2026-05-31  
**Status**: ✅ READY TO DEPLOY  
**Estimated Time**: 2 minutes  

---

## 📌 MASALAH

```
SQLSTATE[42S02]: Base table or view not found: 1146 
Table 'vendor_db.jobs' doesn't exist
```

**Penyebab**: Tabel `jobs` belum dibuat di database `vendor_db`

---

## 🚀 SOLUSI CEPAT (Pilih 1)

### ✅ Opsi 1: Windows Batch Script (EASIEST)
```batch
cd c:\xampp\htdocs\logistik-app
setup.bat
```

### ✅ Opsi 2: Python (ALL OS)
```bash
cd c:\xampp\htdocs\logistik-app
python run_setup.py
```

### ✅ Opsi 3: Linux/Mac
```bash
cd /path/to/logistik-app
bash setup.sh
```

### ✅ Opsi 4: Manual SQL
```bash
docker exec vendor-service-db mysql -u root -proot vendor_db < setup-vendor-db.sql
```

---

## ⚠️ PRE-REQUIREMENTS

### Pastikan Containers Running:
```bash
cd c:\xampp\htdocs\logistik-app
docker-compose up -d

# Tunggu ~10 detik, kemudian jalankan setup
```

### Verify Containers:
```bash
docker ps | grep vendor-service
```

Output harus menunjukkan:
- ✅ `vendor-service-app` (running)
- ✅ `vendor-service-db` (running)
- ✅ `rabbitmq` (running)

---

## 📋 SCRIPT YANG TERSEDIA

### 1. **setup.bat** - Windows Batch Script
- **File**: `c:\xampp\htdocs\logistik-app\setup.bat`
- **Usage**: Double-click or `setup.bat`
- **Best for**: Windows users, beginner-friendly
- **Output**: Detailed log file

### 2. **run_setup.py** - Python Script
- **File**: `c:\xampp\htdocs\logistik-app\run_setup.py`
- **Usage**: `python run_setup.py`
- **Best for**: Cross-platform, professional
- **Output**: Colored output, detailed progress

### 3. **setup.sh** - Bash Script
- **File**: `c:\xampp\htdocs\logistik-app\setup.sh`
- **Usage**: `bash setup.sh`
- **Best for**: Linux/Mac users
- **Output**: Full logging

### 4. **setup-vendor-db.sql** - Raw SQL
- **File**: `c:\xampp\htdocs\logistik-app\setup-vendor-db.sql`
- **Usage**: `docker exec vendor-service-db mysql -u root -proot vendor_db < setup-vendor-db.sql`
- **Best for**: Manual control, CI/CD

### 5. **setup-tracking-db.sql** - Tracking Service Setup
- **File**: `c:\xampp\htdocs\logistik-app\setup-tracking-db.sql`
- **Usage**: Apply same way as vendor-db
- **For**: tracking_db database

### 6. **verify-db.bat** - Verification Script
- **File**: `c:\xampp\htdocs\logistik-app\verify-db.bat`
- **Usage**: `verify-db.bat`
- **Purpose**: Check if all tables were created successfully

---

## 🎬 STEP-BY-STEP EXECUTION

### Step 1: Open Terminal
```bash
# Windows: cmd.exe / PowerShell / Git Bash
# Mac: Terminal
# Linux: Terminal
```

### Step 2: Navigate to Project
```bash
cd c:\xampp\htdocs\logistik-app
```

### Step 3: Run Setup Script
```bash
# Windows (Simplest)
setup.bat

# Or Python (Recommended)
python run_setup.py

# Or Manual SQL
docker exec vendor-service-db mysql -u root -proot vendor_db < setup-vendor-db.sql
```

### Step 4: Wait for Completion
- Script akan membuat tabel secara otomatis
- Tunggu sampai melihat "✅ SETUP COMPLETED SUCCESSFULLY!"

### Step 5: Verify (Optional)
```bash
# Check all tables created
verify-db.bat

# Or manual check
docker exec vendor-service-db mysql -u root -proot vendor_db -e "SHOW TABLES;"
```

### Step 6: Retry Your Operation
Error sekarang sudah fixed! Coba operation yang sebelumnya error.

---

## 📊 TABEL YANG AKAN DIBUAT

| Table | Purpose |
|-------|---------|
| `jobs` | Store queued jobs (MAIN FIX) |
| `job_batches` | Track batch job status |
| `failed_jobs` | Store failed job records |
| `cache` | Application caching (CACHE_STORE=database) |
| `cache_locks` | Cache locking mechanism |
| `users` | User data |
| `sessions` | User sessions |

---

## 🔍 VERIFIKASI

### Check Tabel Jobs
```bash
docker exec vendor-service-db mysql -u root -proot vendor_db -e "DESCRIBE jobs;"
```

### Expected Output
```
+--------------+------------------+------+-----+---------+----------------+
| Field        | Type             | Null | Key | Default | Extra          |
+--------------+------------------+------+-----+---------+----------------+
| id           | bigint unsigned  | NO   | PRI | NULL    | auto_increment |
| queue        | varchar(255)     | NO   | MUL | NULL    |                |
| payload      | longtext         | NO   |     | NULL    |                |
| attempts     | tinyint unsigned | NO   |     | NULL    |                |
| reserved_at  | int unsigned     | YES  |     | NULL    |                |
| available_at | int unsigned     | NO   |     | NULL    |                |
| created_at   | int unsigned     | NO   |     | NULL    |                |
+--------------+------------------+------+-----+---------+----------------+
```

### Count All Tables
```bash
docker exec vendor-service-db mysql -u root -proot vendor_db -e "SELECT COUNT(*) as table_count FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='vendor_db';"
```

Expected: 7 tables

---

## 🆘 TROUBLESHOOTING

### Problem: "Docker is not installed"
**Solution**:
1. Install Docker Desktop: https://www.docker.com/products/docker-desktop
2. Or enable WSL: `wsl --install`
3. Restart computer and retry

### Problem: "Container not running"
**Solution**:
```bash
docker-compose up -d
# Wait 10 seconds
setup.bat
```

### Problem: "MySQL Error: Access denied"
**Solution**:
```bash
# Check .env file for correct credentials
cat vendor-service/.env | grep DB_

# Default should be:
# DB_USERNAME=root
# DB_PASSWORD=root
```

### Problem: "File not found"
**Solution**:
```bash
# Make sure you're in the right directory
cd c:\xampp\htdocs\logistik-app
dir setup*.bat  # Should show setup.bat
dir setup*.sql  # Should show setup-vendor-db.sql
```

### Problem: "Containers are running but setup fails"
**Solution**:
```bash
# Check database logs
docker logs vendor-service-db

# Or check app logs
docker logs vendor-service-app

# Force reconnect
docker exec vendor-service-db mysql -u root -proot -e "SELECT 1;"
```

### Problem: "Setup runs but tables not created"
**Solution**:
1. Run verify script: `verify-db.bat`
2. Check manual: `docker exec vendor-service-db mysql -u root -proot vendor_db -e "SHOW TABLES;"`
3. Try running SQL directly:
```bash
docker exec vendor-service-db mysql -u root -proot vendor_db < setup-vendor-db.sql
```

---

## 🔧 ADVANCED OPTIONS

### Reset Database (CAUTION: DATA LOSS)
```bash
# Drop all tables
docker exec vendor-service-db mysql -u root -proot vendor_db -e "DROP DATABASE IF EXISTS vendor_db; CREATE DATABASE vendor_db;"

# Recreate tables
docker exec vendor-service-db mysql -u root -proot vendor_db < setup-vendor-db.sql
```

### Direct Database Access
```bash
# MySQL CLI
mysql -h 127.0.0.1 -P 33062 -u root -proot vendor_db

# In MySQL:
# SHOW TABLES;
# DESCRIBE jobs;
# SELECT COUNT(*) FROM jobs;
# EXIT;
```

### Via Laravel Tinker
```bash
docker exec vendor-service-app php artisan tinker

# In tinker:
# >>> DB::table('jobs')->count()
# >>> Schema::hasTable('jobs')
# >>> exit
```

---

## ✅ SUCCESS CHECKLIST

After running setup, verify:

- [ ] No error messages shown
- [ ] "SETUP COMPLETED SUCCESSFULLY!" displayed
- [ ] All 7 tables listed when running `SHOW TABLES;`
- [ ] `jobs` table has correct structure (7 columns)
- [ ] Can query: `SELECT COUNT(*) FROM jobs;` (returns 0)
- [ ] Docker containers still running
- [ ] No log errors in `docker logs vendor-service-app`

---

## 🎯 NEXT STEPS

After successful setup:

1. **Retry Operation**: Run the operation that was failing before
2. **Check Logs**: `docker logs vendor-service-app`
3. **Monitor Queue**: Watch job queue in realtime
4. **Test with Artisan**:
   ```bash
   docker exec vendor-service-app php artisan tinker
   # Test: DB::table('jobs')->count()
   ```

---

## 📞 STILL NEED HELP?

1. **Read**: `MIGRATION_FIX.md` (detailed guide)
2. **Check**: Docker logs: `docker logs vendor-service-app`
3. **Verify**: Database: `verify-db.bat`
4. **Search**: "Laravel queue jobs table" in Google

---

## 📝 FILE MANIFEST

| File | Description | Purpose |
|------|-------------|---------|
| `setup.bat` | Windows batch script | Main setup (Windows) |
| `setup.sh` | Bash script | Main setup (Linux/Mac) |
| `run_setup.py` | Python script | Main setup (All OS) |
| `setup-vendor-db.sql` | SQL script | Create vendor_db tables |
| `setup-tracking-db.sql` | SQL script | Create tracking_db tables |
| `verify-db.bat` | Verification script | Check tables created |
| `README_SETUP.md` | Quick reference | Quick start guide |
| `MIGRATION_FIX.md` | Full documentation | Detailed guide |
| `FULLSTACK_SETUP.md` | This file | Complete guide |

---

## ⏱️ EXECUTION TIME

| Method | Time |
|--------|------|
| setup.bat | 30 seconds |
| run_setup.py | 45 seconds |
| setup.sh | 45 seconds |
| Manual SQL | 10 seconds |

---

## 🏁 SUMMARY

**Error**: Table `jobs` doesn't exist  
**Cause**: Database migrations not executed  
**Fix**: Run `setup.bat` or equivalent  
**Time**: ~30 seconds  
**Result**: All required tables created  
**Status**: ✅ READY TO USE

---

**Version**: 1.0  
**Last Updated**: 2026-05-31  
**Status**: Production Ready
