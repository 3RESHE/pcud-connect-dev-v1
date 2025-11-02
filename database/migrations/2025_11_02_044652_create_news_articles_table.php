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
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();

            // Creator & Approver
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // Staff member who created it
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // Admin who approved it

            // Content
            $table->string('title');
            $table->string('slug')->unique(); // URL-friendly slug
            $table->longText('content'); // Article body

            // Categorization
            $table->enum('category', [
                'university_updates',
                'alumni_success',
                'partnership_highlights',
                'campus_events',
                'general'
            ]);

            // Media
            $table->string('featured_image')->nullable(); // Featured image/thumbnail

            // Publishing settings
            $table->boolean('is_featured')->default(false); // Featured on homepage?
            $table->boolean('is_archived')->default(false); // Soft delete

            // Approval workflow
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'published'])->default('draft');
            $table->text('rejection_reason')->nullable(); // Why was it rejected?
            $table->timestamp('published_at')->nullable(); // When was it published?

            // Engagement metrics
            $table->integer('views_count')->default(0); // Track number of views

            $table->timestamps();
        });

        // Add indexes
        Schema::table('news_articles', function (Blueprint $table) {
            $table->index('status', 'idx_news_status');
            $table->index('published_at', 'idx_news_published');
            $table->index('is_featured', 'idx_news_featured');
            $table->index(['is_featured', 'status', 'published_at'], 'idx_news_featured_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
