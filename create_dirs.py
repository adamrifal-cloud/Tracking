import os
import sys

base_path = r"C:\xampp\htdocs\logistik-app\tracking-service"

# Create directories
directories = [
    r"app\Http\Middleware",
    r"app\Http\Controllers\Auth",
    r"resources\views\auth\customer",
    r"resources\views\auth\driver",
    r"resources\views\customer",
]

for dir_path in directories:
    full_path = os.path.join(base_path, dir_path)
    os.makedirs(full_path, exist_ok=True)
    print(f"✓ Created: {dir_path}")

print("\n✓ All directories created successfully!")
