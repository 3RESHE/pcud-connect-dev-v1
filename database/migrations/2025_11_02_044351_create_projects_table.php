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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Track which user type (students or alumni can have projects)
            $table->enum('user_type', ['student', 'alumni']);

            // Project details
            $table->string('title')->nullable(); // Project name
            $table->string('url')->nullable(); // Link to project (GitHub, portfolio, etc.)
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable(); // Project description

            $table->timestamps();
        });

        // Add indexes
        Schema::table('projects', function (Blueprint $table) {
            $table->index('user_id', 'idx_projects_user');
            $table->index('user_type', 'idx_projects_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
