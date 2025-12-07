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

            // ===== EMPLOYMENT STATUS =====
            $table->boolean('is_fresh_grad')->nullable(); // NULL until user selects (NOT default false!)

            // ===== PROFILE INFORMATION =====
            $table->string('profile_photo')->nullable();
            $table->string('headline', 500)->nullable();
            $table->string('personal_email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('current_location')->nullable();

            // ===== SOCIAL & PROFESSIONAL LINKS =====
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();

            // ===== DOCUMENTS - MULTIPLE UPLOADS =====
            $table->json('resumes')->nullable();
            $table->json('certifications')->nullable();

            // ===== ACADEMIC INFORMATION =====
            $table->string('degree_program')->nullable();
            $table->unsignedSmallInteger('graduation_year')->nullable();
            $table->decimal('gwa', 3, 2)->nullable();
            $table->string('honors')->nullable();
            $table->string('thesis_title', 500)->nullable();

            // ===== PROFESSIONAL INFORMATION =====
            $table->text('professional_summary')->nullable();
            $table->string('current_organization')->nullable();
            $table->string('current_position')->nullable();
            $table->string('current_industry')->nullable();
            $table->boolean('willing_to_relocate')->nullable();

            // ===== ACTIVITIES & EXPERIENCES =====
            $table->text('organizations')->nullable();
            $table->json('technical_skills')->nullable();
            $table->json('soft_skills')->nullable();
            $table->json('languages')->nullable();

            // ===== PROFILE COMPLETION =====
            $table->boolean('profile_complete')->default(false);
            $table->timestamp('profile_completed_at')->nullable();
            $table->boolean('is_public')->default(true);

            $table->timestamps();
        });

        // Add indexes
        Schema::table('alumni_profiles', function (Blueprint $table) {
            $table->index('user_id', 'idx_alumni_profiles_user');
            $table->index('is_fresh_grad', 'idx_alumni_profiles_fresh_grad');
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
