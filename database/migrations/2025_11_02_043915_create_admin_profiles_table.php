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
        Schema::create('admin_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            // Admin-specific fields
            $table->string('phone', 20)->nullable();
            $table->string('admin_department')->nullable(); // Free text, e.g., "HR", "IT", "Finance"
            $table->string('position')->nullable(); // e.g., "System Administrator"

            $table->timestamps();
        });

        // Add index
        Schema::table('admin_profiles', function (Blueprint $table) {
            $table->index('user_id', 'idx_admin_profiles_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_profiles');
    }
};
