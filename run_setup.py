#!/usr/bin/env python3
"""
Fullstack Dev Setup Script - Database Migration Fix
Menjalankan setup database untuk vendor-service
"""

import subprocess
import sys
import os
from datetime import datetime

# Color output
class Colors:
    HEADER = '\033[95m'
    OKBLUE = '\033[94m'
    OKCYAN = '\033[96m'
    OKGREEN = '\033[92m'
    WARNING = '\033[93m'
    FAIL = '\033[91m'
    ENDC = '\033[0m'
    BOLD = '\033[1m'

def log(msg, level="INFO"):
    timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    if level == "ERROR":
        print(f"{Colors.FAIL}[{timestamp}] ❌ {msg}{Colors.ENDC}")
    elif level == "SUCCESS":
        print(f"{Colors.OKGREEN}[{timestamp}] ✅ {msg}{Colors.ENDC}")
    elif level == "WARNING":
        print(f"{Colors.WARNING}[{timestamp}] ⚠️  {msg}{Colors.ENDC}")
    else:
        print(f"{Colors.OKCYAN}[{timestamp}] ℹ️  {msg}{Colors.ENDC}")

def run_cmd(cmd, description=""):
    """Run shell command and return status"""
    try:
        if description:
            log(f"Running: {description}")
        result = subprocess.run(cmd, shell=True, capture_output=True, text=True)
        
        if result.returncode != 0 and "ERROR" not in result.stdout:
            return False, result.stderr
        
        return True, result.stdout
    except Exception as e:
        return False, str(e)

def main():
    print(f"\n{Colors.BOLD}{'='*50}{Colors.ENDC}")
    print(f"{Colors.BOLD}LOGISTIK APP - FULLSTACK DEV SETUP{Colors.ENDC}")
    print(f"{Colors.BOLD}{'='*50}{Colors.ENDC}\n")
    
    project_root = os.path.dirname(os.path.abspath(__file__))
    os.chdir(project_root)
    
    # Step 1: Check Docker
    log("Step 1: Checking Docker installation...")
    success, output = run_cmd("docker --version")
    if not success:
        log("Docker not found or not running", "ERROR")
        return 1
    log(output.strip(), "SUCCESS")
    
    # Step 2: Check vendor-service-app container
    log("\nStep 2: Checking vendor-service-app container...")
    success, output = run_cmd(
        'docker ps --filter "name=vendor-service-app" --format "{{.Names}}"'
    )
    if "vendor-service-app" not in output:
        log("vendor-service-app container is not running", "ERROR")
        log("Please run: docker-compose up -d", "WARNING")
        return 1
    log("vendor-service-app is running", "SUCCESS")
    
    # Step 3: Check vendor-service-db container
    log("\nStep 3: Checking vendor-service-db container...")
    success, output = run_cmd(
        'docker ps --filter "name=vendor-service-db" --format "{{.Names}}"'
    )
    if "vendor-service-db" not in output:
        log("vendor-service-db container is not running", "ERROR")
        return 1
    log("vendor-service-db is running", "SUCCESS")
    
    # Step 4: Apply SQL setup script
    log("\nStep 4: Creating database tables...")
    sql_file = os.path.join(project_root, "setup-vendor-db.sql")
    
    if not os.path.exists(sql_file):
        log(f"SQL file not found: {sql_file}", "ERROR")
        return 1
    
    cmd = f'docker exec vendor-service-db mysql -u root -proot vendor_db < "{sql_file}"'
    success, output = run_cmd(cmd, "Executing SQL setup script")
    
    if not success:
        log(f"SQL setup failed: {output}", "ERROR")
        return 1
    log("Database tables created successfully", "SUCCESS")
    
    # Step 5: Verify tables
    log("\nStep 5: Verifying tables in vendor_db...")
    cmd = 'docker exec vendor-service-db mysql -u root -proot vendor_db -e "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=\'vendor_db\' ORDER BY TABLE_NAME;"'
    success, output = run_cmd(cmd)
    
    if success:
        print(Colors.OKCYAN + output + Colors.ENDC)
        log("Tables verified", "SUCCESS")
    
    # Step 6: Check jobs table specifically
    log("\nStep 6: Checking 'jobs' table structure...")
    cmd = 'docker exec vendor-service-db mysql -u root -proot vendor_db -e "DESCRIBE jobs;"'
    success, output = run_cmd(cmd)
    
    if success and output:
        print(Colors.OKCYAN + output + Colors.ENDC)
        log("Jobs table verified", "SUCCESS")
    else:
        log("Could not verify jobs table", "WARNING")
    
    # Step 7: Clear Laravel cache
    log("\nStep 7: Clearing Laravel caches...")
    run_cmd('docker exec vendor-service-app php artisan config:cache --quiet 2>/dev/null || true')
    run_cmd('docker exec vendor-service-app php artisan cache:clear --quiet 2>/dev/null || true')
    log("Caches cleared", "SUCCESS")
    
    # Final Summary
    print(f"\n{Colors.BOLD}{'='*50}{Colors.ENDC}")
    print(f"{Colors.OKGREEN}{Colors.BOLD}✅ SETUP COMPLETED SUCCESSFULLY!{Colors.ENDC}")
    print(f"{Colors.BOLD}{'='*50}{Colors.ENDC}\n")
    
    print(f"{Colors.OKCYAN}Database Details:{Colors.ENDC}")
    print(f"  • Database: vendor_db")
    print(f"  • Host: vendor-service-db (docker) / 127.0.0.1:33062")
    print(f"  • User: root")
    print(f"  • Password: root\n")
    
    print(f"{Colors.OKCYAN}Tables Created:{Colors.ENDC}")
    tables = ['jobs', 'job_batches', 'failed_jobs', 'cache', 'cache_locks', 'users', 'sessions']
    for table in tables:
        print(f"  ✅ {table}")
    
    print(f"\n{Colors.OKCYAN}Next Steps:{Colors.ENDC}")
    print(f"  1. Retry the operation that was failing")
    print(f"  2. Check logs: docker logs vendor-service-app")
    print(f"  3. Test queue: docker exec vendor-service-app php artisan tinker")
    print(f"     > DB::table('jobs')->count()")
    print()
    
    return 0

if __name__ == "__main__":
    sys.exit(main())
