<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\BlogTag;

class BlogTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Recommendations',
            'School Choice',
            'Psychology',
            'Preparation',
            'Exams',
            'Motivation',
            'Extracurriculars',
            'Curriculum',
            'Digital Services',
            'Scholarships',
            'Relocation',
            'Inclusion',
            'Nutrition',
            'Safety',
            'Parent Community',
        ];

        foreach ($tags as $name) {
            BlogTag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
