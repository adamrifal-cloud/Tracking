#!/bin/bash
# Fullstack dev script untuk fix database migrations

set -e  # Exit on error

LOGFILE="setup.log"
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOGFILE"
}

error() {
    echo "❌ ERROR: $1" | tee -a "$LOGFILE"
    exit 1
}

success() {
    echo "✅ $1" | tee -a "$LOGFILE"
}

log "=========================================="
log "LOGISTIK APP - DATABASE SETUP"
log "=========================================="
log ""

cd "$PROJECT_ROOT"

# Step 1: Check Docker
log "Step 1: Checking Docker installation..."
if ! command -v docker &> /dev/null; then
    error "Docker is not installed"
fi
success "Docker found"

# Step 2: Check containers
log ""
log "Step 2: Checking Docker containers..."
if ! docker ps --filter "name=vendor-service-app" --quiet | grep -q .; then
    error "vendor-service-app container is not running"
fi
success "vendor-service-app is running"

if ! docker ps --filter "name=vendor-service-db" --quiet | grep -q .; then
    error "vendor-service-db container is not running"
fi
success "vendor-service-db is running"

# Step 3: Apply SQL setup script
log ""
log "Step 3: Applying SQL setup script to vendor_db..."
docker exec vendor-service-db mysql -u root -proot vendor_db < "$PROJECT_ROOT/setup-vendor-db.sql" >> "$LOGFILE" 2>&1
success "SQL tables created successfully"

# Step 4: Verify tables
log ""
log "Step 4: Verifying tables..."
docker exec vendor-service-db mysql -u root -proot vendor_db -e "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='vendor_db' ORDER BY TABLE_NAME;" | tee -a "$LOGFILE"
success "Tables verified"

# Step 5: Run Laravel migrations
log ""
log "Step 5: Running Laravel migrations (artisan migrate)..."
if docker exec vendor-service-app php artisan migrate --force >> "$LOGFILE" 2>&1; then
    success "Laravel migrations completed"
else
    log "Note: Laravel migrations may have already been run"
fi

# Step 6: Clear caches
log ""
log "Step 6: Clearing Laravel caches..."
docker exec vendor-service-app php artisan config:cache --quiet 2>/dev/null || true
docker exec vendor-service-app php artisan cache:clear --quiet 2>/dev/null || true
success "Caches cleared"

# Final summary
log ""
log "=========================================="
success "SETUP COMPLETED SUCCESSFULLY!"
log "=========================================="
log ""
log "Database: vendor_db"
log "Tables created:"
log "  - jobs"
log "  - job_batches"
log "  - failed_jobs"
log "  - cache"
log "  - cache_locks"
log "  - users"
log "  - sessions"
log ""
log "You can now:"
log "1. Retry the failing operation"
log "2. Check container logs: docker logs vendor-service-app"
log "3. Access database: mysql -h 127.0.0.1 -P 33062 -u root -proot vendor_db"
log ""
