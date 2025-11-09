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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('job_posting_id')->constrained('job_postings')->cascadeOnDelete();
            $table->foreignId('applicant_id')->constrained('users')->cascadeOnDelete();

            // Applicant type (student or alumni)
            $table->enum('applicant_type', ['student', 'alumni']);

            // Application content
            $table->text('cover_letter')->nullable();
            $table->string('resume_path')->nullable(); // File path to resume/CV
            $table->json('additional_documents')->nullable(); // Array of file paths

            // Application status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('reviewed_at')->nullable(); // When did partner review it?

            $table->timestamps();

            // Unique constraint: one application per user per job posting
            $table->unique(['job_posting_id', 'applicant_id'], 'unique_application');
        });

        // Add indexes
        Schema::table('job_applications', function (Blueprint $table) {
            $table->index('applicant_id', 'idx_job_applications_applicant');
            $table->index('status', 'idx_job_applications_status');
            $table->index('job_posting_id', 'idx_job_applications_job');
            $table->index('applicant_type', 'idx_job_applications_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
