@echo off
REM Run Laravel migrations in vendor-service container

echo ========================================
echo Running Laravel Migrations
echo ========================================
echo.

REM Check if Docker is running
docker ps >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Docker is not running or not installed
    exit /b 1
)

REM List all containers to verify vendor-service-app exists
echo Checking for vendor-service-app container...
docker ps --filter "name=vendor-service-app" --format "table {{.Names}}\t{{.Status}}"
echo.

REM Run migrations
echo Running: php artisan migrate --force
docker exec vendor-service-app php artisan migrate --force

if %errorlevel% equ 0 (
    echo.
    echo ========================================
    echo ✓ Migrations completed successfully!
    echo ========================================
) else (
    echo.
    echo ========================================
    echo ✗ Migration failed with error
    echo ========================================
    exit /b 1
)

REM Verify tables were created
echo.
echo Verifying tables in vendor_db...
docker exec vendor-service-db mysql -u root -proot vendor_db -e "SHOW TABLES;"

exit /b 0
