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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // User type (student or alumni)
            $table->enum('user_type', ['student', 'alumni']);

            // Registration type
            $table->enum('registration_type', ['online', 'walkin']);

            // Attendance tracking
            $table->enum('attendance_status', ['registered', 'attended', 'no_show'])->default('registered');
            $table->timestamp('checked_in_at')->nullable(); // When did they check in?

            $table->timestamps();
        });

        // Add indexes
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->index('user_id', 'idx_event_registrations_user');
            $table->index('event_id', 'idx_event_registrations_event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
