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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();

            // Creator & Approver
            $table->foreignId('partner_id')->constrained('users')->cascadeOnDelete(); // Partner who created it
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // Admin who approved it

            // Job type & basics
            $table->enum('job_type', ['fulltime', 'parttime', 'internship', 'contract']);
            $table->string('title'); // Job title
            $table->string('department')->nullable(); // Department within company
            $table->string('custom_department')->nullable(); // Custom department if not in predefined list

            // Experience level
            $table->enum('experience_level', ['entry', 'mid', 'senior', 'lead', 'student']);

            // Job description
            $table->longText('description'); // Full job description

            // Work setup
            $table->enum('work_setup', ['onsite', 'remote', 'hybrid']);
            $table->string('location')->nullable(); // City/address for on-site jobs

            // Compensation
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->enum('salary_period', ['monthly', 'hourly', 'daily', 'project'])->nullable();
            $table->boolean('is_unpaid')->default(false); // For internships/volunteer

            // Duration & timeline
            $table->integer('duration_months')->nullable(); // For contract/internship
            $table->date('preferred_start_date')->nullable();
            $table->date('application_deadline');

            // Requirements
            $table->text('education_requirements')->nullable();
            $table->json('technical_skills')->nullable(); // Array of skills
            $table->text('experience_requirements')->nullable();

            // Positions & process
            $table->integer('positions_available')->default(1);
            $table->text('application_process')->nullable(); // How to apply
            $table->text('benefits')->nullable(); // Additional benefits

            // Approval workflow
            $table->enum('status', ['pending', 'approved', 'rejected', 'published', 'paused', 'completed'])->default('pending');
            $table->text('rejection_reason')->nullable(); // Why was it rejected?
            $table->timestamp('published_at')->nullable(); // When was it published?

            $table->timestamps();
        });

        // Add indexes
        Schema::table('job_postings', function (Blueprint $table) {
            $table->index('partner_id', 'idx_job_postings_partner');
            $table->index('status', 'idx_job_postings_status');
            $table->index('published_at', 'idx_job_postings_published');
            $table->index(['status', 'published_at'], 'idx_jobs_status_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
