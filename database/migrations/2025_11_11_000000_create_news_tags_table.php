<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tags table
        Schema::create('news_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Pivot table (many-to-many)
        Schema::create('news_article_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_article_id')->constrained('news_articles')->cascadeOnDelete();
            $table->foreignId('news_tag_id')->constrained('news_tags')->cascadeOnDelete();
            $table->timestamps();

            // Prevent duplicate tags
            $table->unique(['news_article_id', 'news_tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_article_tag');
        Schema::dropIfExists('news_tags');
    }
};
