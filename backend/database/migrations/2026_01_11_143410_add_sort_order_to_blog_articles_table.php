<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blog_articles', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->index();
        });

        $ids = DB::table('blog_articles')
            ->orderByDesc('publish_at')
            ->orderByDesc('id')
            ->pluck('id');

        $i = 1;
        foreach ($ids as $id) {
            DB::table('blog_articles')->where('id', $id)->update(['sort_order' => $i++]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_articles', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
