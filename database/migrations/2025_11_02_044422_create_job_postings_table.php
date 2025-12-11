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

            // Creator & Approver & Rejector & Unpublisher
            $table->foreignId('partner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->cascadeOnDelete(); // ✅ CHANGED
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('unpublished_by')->nullable()->constrained('users')->nullOnDelete();

            // Job type & basics
            $table->enum('job_type', ['fulltime', 'parttime', 'internship', 'other'])->default('fulltime');
            $table->string('title');

            // Experience level
            $table->enum('experience_level', ['entry', 'mid', 'senior', 'lead', 'student'])->default('entry');

            // Job description
            $table->longText('description');
            $table->longText('education_requirements')->nullable();
            $table->longText('experience_requirements')->nullable();
            $table->longText('benefits')->nullable();

            // Work setup
            $table->enum('work_setup', ['onsite', 'remote', 'hybrid'])->default('onsite');
            $table->string('location')->nullable();

            // Compensation
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->enum('salary_period', ['monthly', 'hourly', 'daily', 'project'])->nullable();
            $table->boolean('is_unpaid')->default(false);

            // Duration & timeline
            $table->integer('duration_months')->nullable();
            $table->date('preferred_start_date')->nullable();
            $table->date('application_deadline');

            // Requirements & Skills
            $table->json('technical_skills')->nullable();
            $table->longText('application_process')->nullable();

            // Positions & process
            $table->integer('positions_available')->default(1);
            $table->text('application_instructions')->nullable();

            // Approval workflow
            $table->enum('status', ['pending', 'approved', 'rejected', 'unpublished', 'completed'])->default('pending');
            $table->enum('sub_status', ['active', 'paused'])->nullable();
            $table->boolean('is_featured')->default(false);
            $table->text('rejection_reason')->nullable();
            $table->text('unpublish_reason')->nullable();

            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('unpublished_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });

        // Add indexes
        Schema::table('job_postings', function (Blueprint $table) {
            $table->index('partner_id', 'idx_job_postings_partner');
            $table->index('department_id', 'idx_job_postings_department'); // ✅ NEW
            $table->index('status', 'idx_job_postings_status');
            $table->index('published_at', 'idx_job_postings_published');
            $table->index(['status', 'published_at'], 'idx_jobs_status_published');
            $table->index('application_deadline', 'idx_job_postings_deadline');
            $table->index('is_featured', 'idx_job_postings_featured');
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
