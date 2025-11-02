<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            // Staff-specific fields
            $table->string('phone', 20)->nullable();
            $table->string('staff_department')->nullable(); // e.g., "Events", "Communications"
            $table->string('position')->nullable(); // e.g., "Events Coordinator"
            $table->string('employee_id', 50)->nullable(); // Employee/Staff ID

            $table->timestamps();
        });

        // Add index
        Schema::table('staff_profiles', function (Blueprint $table) {
            $table->index('user_id', 'idx_staff_profiles_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_profiles');
    }
};
