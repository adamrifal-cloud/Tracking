@echo off
setlocal enabledelayedexpansion

REM Set base path
set BASE_PATH=C:\xampp\htdocs\logistik-app\tracking-service

REM Create directories
echo Creating directories...
if not exist "!BASE_PATH!\app\Http\Middleware" mkdir "!BASE_PATH!\app\Http\Middleware"
if not exist "!BASE_PATH!\app\Http\Controllers\Auth" mkdir "!BASE_PATH!\app\Http\Controllers\Auth"
if not exist "!BASE_PATH!\resources\views\auth\customer" mkdir "!BASE_PATH!\resources\views\auth\customer"
if not exist "!BASE_PATH!\resources\views\auth\driver" mkdir "!BASE_PATH!\resources\views\auth\driver"
if not exist "!BASE_PATH!\resources\views\customer" mkdir "!BASE_PATH!\resources\views\customer"

echo ✓ Directories created successfully!

REM Change to tracking service directory
cd /d "!BASE_PATH!"

REM Run migrations
echo.
echo Running migrations...
php artisan migrate

echo.
echo ✓ Setup complete! All directories and migrations ready.
pause
