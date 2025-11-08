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
        Schema::create('alumni_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            // ===== PROFILE INFORMATION =====
            $table->string('profile_photo')->nullable(); // File path
            $table->string('headline', 500)->nullable(); // Professional headline
            $table->string('personal_email')->nullable(); // Current personal email
            $table->string('phone', 20)->nullable(); // Phone number
            $table->string('current_location')->nullable(); // City, Country

            // ===== SOCIAL & PROFESSIONAL LINKS =====
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();

            // ===== DOCUMENTS =====
            $table->string('resume_path')->nullable(); // File path to resume

            // ===== ACADEMIC INFORMATION =====
            $table->string('degree_program')->nullable(); // e.g., "Bachelor of Science in Computer Science"
            $table->unsignedSmallInteger('graduation_year')->nullable(); // e.g., 2023
            $table->decimal('gwa', 3, 2)->nullable(); // General Weighted Average (GPA equivalent)
            $table->string('honors')->nullable(); // e.g., "Cum Laude", "Magna Cum Laude", "Summa Cum Laude"
            $table->string('thesis_title', 500)->nullable(); // Thesis or capstone project title

            // ===== PROFESSIONAL INFORMATION =====
            $table->text('professional_summary')->nullable(); // Career summary / bio
            $table->string('current_organization')->nullable(); // Current company/organization
            $table->string('current_position')->nullable(); // Current job title
            $table->string('current_industry')->nullable(); // Industry (e.g., Tech, Finance, Healthcare)
            $table->boolean('willing_to_relocate')->nullable(); // Yes/No/Maybe

            // ===== ACTIVITIES & EXPERIENCES =====
            $table->text('organizations')->nullable(); // Organizations joined during college
            $table->text('technical_skills')->nullable(); // Comma-separated or JSON
            $table->text('soft_skills')->nullable(); // Comma-separated or JSON
            $table->text('certifications')->nullable(); // Professional certifications
            $table->text('languages')->nullable(); // Languages spoken

            // ===== PROFILE COMPLETION =====
            $table->boolean('profile_complete')->default(false); // Track if profile is complete
            $table->timestamp('profile_completed_at')->nullable(); // When profile was completed
            $table->boolean('is_public')->default(true); // Allow other alumni to view

            $table->timestamps();
        });

        // Add indexes
        Schema::table('alumni_profiles', function (Blueprint $table) {
            $table->index('user_id', 'idx_alumni_profiles_user');
            $table->index('graduation_year', 'idx_alumni_profiles_grad_year');
            $table->index('current_organization', 'idx_alumni_profiles_org');
            $table->index('is_public', 'idx_alumni_profiles_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni_profiles');
    }
};
