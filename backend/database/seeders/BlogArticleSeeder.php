<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\User;

class BlogArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::first();
        if (! $author) {
            return;
        }

        $schoolCategory = BlogCategory::where('slug', 'schools')->first();
        $examCategory = BlogCategory::where('slug', 'exams-ielts')->first();

        $tags = BlogTag::orderBy('id')->pluck('id')->all();

        $published = BlogArticle::updateOrCreate(
            ['slug' => 'how-to-choose-private-school'],
            [
                'title' => 'How to Choose the Right Private School',
                'slug' => 'how-to-choose-private-school',
                'excerpt' => 'A practical guide for parents choosing a private school.',
                'content' => '<p>Choosing a private school is an important decision...</p>',
                'status' => 'published',
                'publish_at' => now()->subDays(5),
                'author_id' => $author->id,
                'category_id' => $schoolCategory?->id,
                'related_types' => ['school'],
            ]
        );
        $published->tags()->sync(array_slice($tags, 0, 3));

        $scheduled = BlogArticle::updateOrCreate(
            ['slug' => 'ielts-preparation-tips-2025'],
            [
                'title' => 'IELTS Preparation Tips for 2025',
                'slug' => 'ielts-preparation-tips-2025',
                'excerpt' => 'What to focus on when preparing for IELTS.',
                'content' => '<p>IELTS preparation requires a structured approach...</p>',
                'status' => 'scheduled',
                'publish_at' => Carbon::now()->addDays(3),
                'author_id' => $author->id,
                'category_id' => $examCategory?->id,
                'related_types' => ['school', 'agency'],
            ]
        );
        $scheduled->tags()->sync(array_slice($tags, 2, 3));

        $draft = BlogArticle::updateOrCreate(
            ['slug' => 'common-parent-mistakes-schools'],
            [
                'title' => 'Common Mistakes Parents Make When Choosing Schools',
                'slug' => 'common-parent-mistakes-schools',
                'excerpt' => 'Avoid these common pitfalls.',
                'content' => '<p>Many parents focus only on rankings...</p>',
                'status' => 'draft',
                'author_id' => $author->id,
                'category_id' => $schoolCategory?->id,
            ]
        );
        $draft->tags()->sync(array_slice($tags, 1, 2));
    }
}
