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
        Schema::create('partnerships', function (Blueprint $table) {
            $table->id();

            // Creator & Reviewer
            $table->foreignId('partner_id')->constrained('users')->cascadeOnDelete(); // Partner who submitted
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); // Admin who reviewed it

            // Activity type
            $table->enum('activity_type', [
                'feedingprogram',
                'brigadaeskwela',
                'communitycleanup',
                'treeplanting',
                'donationdrive',
                'other'
            ]);
            $table->string('custom_activity_type')->nullable(); // If "other" selected

            // Organization information
            $table->string('organization_name');
            $table->text('organization_background'); // About the organization
            $table->string('organization_website')->nullable();
            $table->string('organization_phone', 20);

            // Activity details
            $table->string('activity_title');
            $table->text('activity_description');
            $table->date('activity_date');
            $table->time('activity_time');
            $table->text('venue_address');
            $table->text('activity_objectives'); // What are the goals?
            $table->text('expected_impact'); // Expected outcome/impact

            // Contact information
            $table->string('contact_name');
            $table->string('contact_position');
            $table->string('contact_email');
            $table->string('contact_phone', 20);

            // Additional info
            $table->text('previous_experience')->nullable(); // Previous partnership experience
            $table->text('additional_notes')->nullable(); // Any other notes

            // Approval workflow (different from others - via email)
            $table->enum('status', ['submitted', 'under_review', 'approved', 'rejected', 'completed'])->default('submitted');
            $table->text('admin_notes')->nullable(); // Admin feedback/notes
            $table->timestamp('reviewed_at')->nullable(); // When was it reviewed?
            $table->timestamp('completed_at')->nullable(); // When was it completed?

            $table->timestamps();
        });

        // Add indexes
        Schema::table('partnerships', function (Blueprint $table) {
            $table->index('partner_id', 'idx_partnerships_partner');
            $table->index('status', 'idx_partnerships_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partnerships');
    }
};
