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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Name fields
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('name_suffix', 20)->nullable(); // Jr., Sr., III, etc.

            // Authentication fields
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Role field (5 roles)
            $table->enum('role', ['admin', 'staff', 'partner', 'alumni', 'student']);

            // Department link (only for students)
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();

            // Status field
            $table->boolean('is_active')->default(true);

            // Password change tracking (for first login force password change)
            $table->timestamp('password_changed_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        // Add indexes for performance
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
            $table->index('department_id', 'idx_users_department');
            $table->index(['role', 'is_active'], 'idx_users_role_active');
        });

        // Note: The CHECK constraint for student department requirement
        // will be enforced at the application level (in the User model)
        // due to MySQL limitations with foreign key columns

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Add index on sessions.user_id
        Schema::table('sessions', function (Blueprint $table) {
            $table->index('user_id', 'idx_sessions_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
