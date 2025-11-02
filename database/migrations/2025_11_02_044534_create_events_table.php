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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Creator & Approver
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // Staff member who created it
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // Admin who approved it

            // Basic event info
            $table->string('title');
            $table->longText('description');

            // Event format (in-person, virtual, hybrid)
            $table->enum('event_format', ['inperson', 'virtual', 'hybrid']);

            // Date & time
            $table->date('event_date');
            $table->boolean('is_multiday')->default(false); // Multi-day event?
            $table->date('end_date')->nullable(); // For multi-day events
            $table->time('start_time');
            $table->time('end_time');

            // In-person details
            $table->string('venue_name')->nullable(); // Venue name
            $table->integer('venue_capacity')->nullable(); // Max capacity
            $table->text('venue_address')->nullable(); // Full address

            // Virtual details
            $table->string('platform')->nullable(); // e.g., "Zoom", "Google Meet"
            $table->string('custom_platform')->nullable(); // If not in predefined list
            $table->integer('virtual_capacity')->nullable(); // Max participants
            $table->string('meeting_link', 500)->nullable(); // Link to join meeting

            // Registration settings
            $table->boolean('registration_required')->default(true);
            $table->boolean('walkin_allowed')->default(false);
            $table->date('registration_deadline')->nullable();
            $table->integer('max_attendees')->nullable(); // Total max attendees

            // Target audience
            $table->enum('target_audience', ['allstudents', 'alumni', 'openforall']);
            $table->json('selected_departments')->nullable(); // Array of department IDs if targeting specific departments

            // Additional info
            $table->string('event_tags', 500)->nullable(); // Tags for filtering
            $table->string('contact_person');
            $table->string('contact_email');
            $table->string('contact_phone', 20)->nullable();
            $table->text('special_instructions')->nullable(); // Special requirements/instructions
            $table->string('event_image')->nullable(); // Event banner/image

            // Approval workflow
            $table->enum('status', ['pending', 'approved', 'rejected', 'published', 'ongoing', 'completed'])->default('pending');
            $table->text('rejection_reason')->nullable(); // Why was it rejected?
            $table->timestamp('published_at')->nullable(); // When was it published?

            $table->timestamps();
        });

        // Add indexes
        Schema::table('events', function (Blueprint $table) {
            $table->index('created_by', 'idx_events_creator');
            $table->index('status', 'idx_events_status');
            $table->index('event_date', 'idx_events_date');
            $table->index(['status', 'event_date'], 'idx_events_status_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
