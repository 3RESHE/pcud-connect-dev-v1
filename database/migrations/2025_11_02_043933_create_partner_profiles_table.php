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
        Schema::create('partner_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            // Company information
            $table->string('company_name')->nullable(); // Will be filled by partner later
            $table->string('company_logo')->nullable(); // File path
            $table->string('industry')->nullable(); // e.g., "Technology", "Finance"
            $table->string('company_size')->nullable(); // e.g., "1-50", "51-200", "201+"
            $table->unsignedSmallInteger('founded_year')->nullable(); // e.g., 2010
            $table->text('description')->nullable(); // Company description

            // Contact information
            $table->string('contact_person')->nullable(); // Primary contact name
            $table->string('contact_title')->nullable(); // e.g., "HR Manager"
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();

            // Online presence
            $table->string('website')->nullable();
            $table->string('linkedin_url')->nullable();

            $table->timestamps();
        });

        // Add index
        Schema::table('partner_profiles', function (Blueprint $table) {
            $table->index('user_id', 'idx_partner_profiles_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_profiles');
    }
};
