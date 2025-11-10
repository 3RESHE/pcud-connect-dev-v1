<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();

            // Creator & Approver
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            // Content
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary'); // ✅ ADD: Summary field
            $table->longText('content');

            // Categorization
            $table->enum('category', [
                'university_updates',
                'alumni_success',
                'partnership_highlights',
                'campus_events',
                'general'
            ]);

            // ✅ ADD: Event & Partnership Info
            $table->date('event_date')->nullable(); // Event date
            $table->string('partnership_with')->nullable(); // Partner organization

            // Media
            $table->string('featured_image')->nullable();

            // Publishing settings
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_archived')->default(false);

            // Approval workflow
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'published'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('published_at')->nullable();


            $table->timestamps();
        });

        // Add indexes
        Schema::table('news_articles', function (Blueprint $table) {
            $table->index('status', 'idx_news_status');
            $table->index('published_at', 'idx_news_published');
            $table->index('is_featured', 'idx_news_featured');
            $table->index('event_date', 'idx_news_event_date'); // ✅ ADD
            $table->index(['is_featured', 'status', 'published_at'], 'idx_news_featured_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
