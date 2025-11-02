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
        Schema::create('notifications', function (Blueprint $table) {
            // Use UUID as primary key (Laravel standard for notifications)
            $table->char('id', 36)->primary();

            // User who receives the notification
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Notification type
            $table->string('type'); // e.g., "JobApplicationReceived", "EventApproved"

            // What this notification is about (polymorphic)
            $table->string('notifiable_type')->nullable(); // e.g., "App\Models\JobPosting"
            $table->unsignedBigInteger('notifiable_id')->nullable(); // e.g., job posting ID

            // Flexible notification data (JSON)
            $table->json('data'); // Contains all notification-specific data

            // Read status
            $table->timestamp('read_at')->nullable(); // When did user read it?

            $table->timestamps();
        });

        // Add index
        Schema::table('notifications', function (Blueprint $table) {
            $table->index('user_id', 'idx_notifications_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
