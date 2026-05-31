@echo off
REM =========================================================================
REM Database Verification Script
REM Check if all required tables exist in vendor_db
REM =========================================================================

echo.
echo =========================================================================
echo  DATABASE VERIFICATION
echo =========================================================================
echo.

echo Checking connection to vendor_db...
docker exec vendor-service-db mysql -u root -proot vendor_db -e "SELECT 1;" >nul 2>&1

if %errorlevel% neq 0 (
    echo ERROR: Cannot connect to vendor_db
    pause
    exit /b 1
)

echo [OK] Connected to vendor_db
echo.

echo Checking required tables:
echo.

setlocal enabledelayedexpansion
set tables=jobs job_batches failed_jobs cache cache_locks users sessions
set missing=0

for %%t in (%tables%) do (
    docker exec vendor-service-db mysql -u root -proot vendor_db -e "SHOW TABLES LIKE '%%t';" >check_table.txt 2>&1
    
    for /f %%a in (check_table.txt) do (
        if "%%a"=="%%t" (
            echo   [OK] %%t
        )
    )
    
    if %errorlevel% neq 0 (
        echo   [MISSING] %%t
        set /a missing=!missing!+1
    )
)

del check_table.txt >nul 2>&1
echo.

if %missing% equ 0 (
    echo =========================================================================
    echo  ALL TABLES VERIFIED - SETUP SUCCESSFUL!
    echo =========================================================================
    echo.
    echo You can now:
    echo   1. Retry your failing operation
    echo   2. Run: docker logs vendor-service-app
    echo   3. Test: docker exec vendor-service-app php artisan tinker
    echo.
) else (
    echo =========================================================================
    echo  VERIFICATION FAILED - %missing% TABLES MISSING
    echo =========================================================================
    echo.
    echo Run setup script first:
    echo   setup.bat
    echo.
)

pause
