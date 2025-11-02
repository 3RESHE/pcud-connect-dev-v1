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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            // Student identification
            $table->string('student_id', 50)->unique(); // e.g., "2023-00123"

            // Profile information
            $table->string('profile_photo')->nullable(); // File path
            $table->string('headline', 500)->nullable(); // Short bio/headline
            $table->string('personal_email')->nullable(); // Personal email
            $table->string('phone', 20)->nullable();
            $table->string('emergency_contact', 20)->nullable();
            $table->text('address')->nullable();

            // Personal details
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();

            // Social & Portfolio links
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();

            // Documents
            $table->string('resume_path')->nullable(); // File path to resume

            // Skills & Certifications
            $table->text('technical_skills')->nullable(); // Comma-separated or JSON
            $table->text('soft_skills')->nullable();
            $table->text('certifications')->nullable();
            $table->text('languages')->nullable();
            $table->text('hobbies')->nullable();

            $table->timestamps();
        });

        // Add index
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->index('user_id', 'idx_student_profiles_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
