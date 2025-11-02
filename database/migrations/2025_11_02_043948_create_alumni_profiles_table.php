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

            // Profile information
            $table->string('profile_photo')->nullable(); // File path
            $table->string('headline', 500)->nullable(); // Professional headline
            $table->string('email')->nullable(); // Current email
            $table->string('phone', 20)->nullable();
            $table->string('current_location')->nullable(); // City, Country

            // Social & Portfolio links
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();

            // Documents
            $table->string('resume_path')->nullable(); // File path to resume

            // Professional information
            $table->text('professional_summary')->nullable(); // Career summary
            $table->string('degree_program')->nullable(); // e.g., "Bachelor of Science in Computer Science"
            $table->unsignedSmallInteger('graduation_year')->nullable(); // e.g., 2023
            $table->decimal('gwa', 3, 2)->nullable(); // General Weighted Average
            $table->string('honors')->nullable(); // e.g., "Cum Laude", "Magna Cum Laude"
            $table->string('thesis_title', 500)->nullable();

            // Activities & Skills
            $table->text('organizations')->nullable(); // Organizations joined during college
            $table->text('technical_skills')->nullable();
            $table->text('soft_skills')->nullable();
            $table->text('certifications')->nullable();
            $table->text('languages')->nullable();

            $table->timestamps();
        });

        // Add index
        Schema::table('alumni_profiles', function (Blueprint $table) {
            $table->index('user_id', 'idx_alumni_profiles_user');
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
