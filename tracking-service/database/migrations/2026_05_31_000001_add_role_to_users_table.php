<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role-based column (CUSTOMER, DRIVER, ADMIN, VENDOR)
            $table->enum('role', ['CUSTOMER', 'DRIVER', 'ADMIN', 'VENDOR'])
                ->default('CUSTOMER')
                ->after('email');

            // Customer/Driver common fields
            $table->string('phone', 20)->nullable()->unique()->after('role');
            $table->text('address')->nullable()->after('phone');
            $table->enum('status', ['active', 'inactive', 'suspended'])
                ->default('active')
                ->after('address');

            // Driver-specific fields
            $table->string('license_number', 50)->nullable()->unique()->after('status');
            $table->string('vehicle_number', 50)->nullable()->after('license_number');
            $table->timestamp('verified_at')->nullable()->after('vehicle_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'address',
                'status',
                'license_number',
                'vehicle_number',
                'verified_at'
            ]);
        });
    }
};
