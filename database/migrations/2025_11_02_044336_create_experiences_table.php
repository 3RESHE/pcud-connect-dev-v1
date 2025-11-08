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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Track which user type (students or alumni can have experiences)
            $table->enum('user_type', ['student', 'alumni']);

            // Experience details
            $table->string('role_position')->nullable(); // Job title/position
            $table->string('organization')->nullable(); // Company/organization name
            $table->string('location')->nullable(); // Job location (City, State/Country)
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false); // Currently working here?

            // Experience type
            $table->enum('experience_type', [
                'internship',
                'part_time',
                'full_time',
                'volunteer',
                'freelance',
                'organization',
                'competition',
                'project'
            ]);

            // Description
            $table->text('description')->nullable(); // What they did / responsibilities

            $table->timestamps();
        });

        // Add indexes
        Schema::table('experiences', function (Blueprint $table) {
            $table->index('user_id', 'idx_experiences_user');
            $table->index('user_type', 'idx_experiences_type');
            $table->index('is_current', 'idx_experiences_current');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
