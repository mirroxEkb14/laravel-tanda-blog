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
        Schema::create('blog_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('blog_categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();                  // HTML
            $table->string('status', 20)->default('draft');           // draft | published | scheduled
            $table->timestamp('publish_at')->nullable();
            $table->unsignedSmallInteger('reading_time')->default(0);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->string('cover_image', 2048)->nullable();          // URL or path; later S3/CDN
            $table->json('related_types')->nullable();                // ["school","kindergarten"]
            $table->json('related_institutions')->nullable();         // [101,202]
            $table->string('seo_title', 255)->nullable();
            $table->string('seo_description', 255)->nullable();
            $table->string('seo_keywords', 255)->nullable();
            $table->string('canonical_url', 2048)->nullable();
            $table->timestamps();
            $table->index(['status', 'publish_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_articles');
    }
};
