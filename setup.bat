@echo off
REM =========================================================================
REM LOGISTIK APP - Full Stack Dev Database Setup Script
REM Mengatasi error: Table 'vendor_db.jobs' doesn't exist
REM =========================================================================

setlocal enabledelayedexpansion
set LOGFILE=setup_%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%.log

echo.
echo =========================================================================
echo  LOGISTIK APP - FULLSTACK DEV SETUP
echo =========================================================================
echo.
echo [%date% %time%] Starting setup... >> %LOGFILE%

REM =======================
REM STEP 1: Check Docker
REM =======================
echo Step 1: Checking Docker installation...
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo.
    echo ERROR: Docker is not installed or not running
    echo.
    echo Fix:
    echo   - Install Docker Desktop: https://www.docker.com/products/docker-desktop
    echo   - Or enable Docker in WSL: wsl --install
    echo.
    pause
    exit /b 1
)

echo [%date% %time%] Docker found >> %LOGFILE%
echo [PASS] Docker is installed
echo.

REM =======================
REM STEP 2: Check Containers
REM =======================
echo Step 2: Checking Docker containers...

docker ps --filter "name=vendor-service-app" --format "{{.Names}}" >nul 2>&1
if %errorlevel% neq 0 (
    echo.
    echo ERROR: vendor-service-app container is not running
    echo.
    echo Fix:
    echo   1. Navigate to: c:\xampp\htdocs\logistik-app
    echo   2. Run: docker-compose up -d
    echo   3. Wait 10 seconds
    echo   4. Run this script again
    echo.
    pause
    exit /b 1
)
echo [PASS] vendor-service-app is running

docker ps --filter "name=vendor-service-db" --format "{{.Names}}" >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: vendor-service-db container is not running
    pause
    exit /b 1
)
echo [PASS] vendor-service-db is running
echo [%date% %time%] Containers verified >> %LOGFILE%
echo.

REM =======================
REM STEP 3: Apply SQL Setup
REM =======================
echo Step 3: Creating database tables...
echo.

if not exist setup-vendor-db.sql (
    echo ERROR: setup-vendor-db.sql not found
    echo Please run this script from: c:\xampp\htdocs\logistik-app
    pause
    exit /b 1
)

echo Running SQL script...
docker exec vendor-service-db mysql -u root -proot vendor_db < setup-vendor-db.sql >> %LOGFILE% 2>&1

if %errorlevel% neq 0 (
    echo ERROR: SQL setup failed
    echo Check %LOGFILE% for details
    pause
    exit /b 1
)

echo [PASS] Database tables created
echo [%date% %time%] SQL setup completed >> %LOGFILE%
echo.

REM =======================
REM STEP 4: Verify Tables
REM =======================
echo Step 4: Verifying tables...

docker exec vendor-service-db mysql -u root -proot vendor_db -e "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='vendor_db' ORDER BY TABLE_NAME;" > tables.txt 2>&1

echo.
echo Tables in vendor_db:
type tables.txt
echo.
del tables.txt

echo [%date% %time%] Tables verified >> %LOGFILE%

REM =======================
REM STEP 5: Verify Jobs Table
REM =======================
echo Step 5: Verifying 'jobs' table structure...
echo.

docker exec vendor-service-db mysql -u root -proot vendor_db -e "DESCRIBE jobs;" > jobs_structure.txt 2>&1

if %errorlevel% equ 0 (
    type jobs_structure.txt
    del jobs_structure.txt
    echo.
    echo [PASS] Jobs table verified successfully
) else (
    echo ERROR: Could not verify jobs table
    del jobs_structure.txt
    pause
    exit /b 1
)

echo [%date% %time%] Jobs table verified >> %LOGFILE%
echo.

REM =======================
REM STEP 6: Clear Caches
REM =======================
echo Step 6: Clearing Laravel caches...

docker exec vendor-service-app php artisan config:cache --quiet >nul 2>&1
docker exec vendor-service-app php artisan cache:clear --quiet >nul 2>&1

echo [PASS] Caches cleared
echo [%date% %time%] Caches cleared >> %LOGFILE%
echo.

REM =======================
REM FINAL SUMMARY
REM =======================
echo =========================================================================
echo  SETUP COMPLETED SUCCESSFULLY!
echo =========================================================================
echo.
echo Database Details:
echo   Database: vendor_db
echo   Host: vendor-service-db (docker) / 127.0.0.1:33062
echo   User: root
echo   Password: root
echo.
echo Tables Created:
echo   [OK] jobs
echo   [OK] job_batches
echo   [OK] failed_jobs
echo   [OK] cache
echo   [OK] cache_locks
echo   [OK] users
echo   [OK] sessions
echo.
echo Next Steps:
echo   1. Retry the operation that was failing
echo   2. Check logs: docker logs vendor-service-app
echo   3. Setup log: %LOGFILE%
echo.
echo Log file saved to: %LOGFILE%
echo.
echo [%date% %time%] Setup completed successfully >> %LOGFILE%

pause
exit /b 0
