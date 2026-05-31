#!/bin/bash

# Navigate to tracking-service
cd C:/xampp/htdocs/logistik-app/tracking-service || cd C:\\xampp\\htdocs\\logistik-app\\tracking-service

# Create all necessary directories
mkdir -p app/Http/Middleware
mkdir -p app/Http/Controllers/Auth
mkdir -p resources/views/auth/customer
mkdir -p resources/views/auth/driver
mkdir -p resources/views/customer

echo "✓ Directories created!"

# Run Laravel migrations
php artisan migrate

echo "✓ Migrations ran!"
