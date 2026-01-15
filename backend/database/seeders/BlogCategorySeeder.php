<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\BlogCategory;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tips',
                'description' => 'Practical guidance for parents and students.',
            ],
            [
                'name' => 'For Parents',
                'description' => 'Resources for families about school choice and learning.',
            ],
            [
                'name' => 'Private Schools',
                'description' => 'Reviews and criteria for choosing private schools.',
            ],
            [
                'name' => 'Admissions',
                'description' => 'Steps and documents needed for enrollment.',
            ],
            [
                'name' => 'Exam Preparation',
                'description' => 'Strategies for tests and competitions.',
            ],
            [
                'name' => 'Preschool Education',
                'description' => 'How to choose a preschool and prepare your child.',
            ],
            [
                'name' => 'International Education',
                'description' => 'Tips on studying abroad programs and adaptation.',
            ],
            [
                'name' => 'Extracurricular Activities',
                'description' => 'Clubs, sports, and skill-building beyond school.',
            ],
            [
                'name' => 'Psychology and Motivation',
                'description' => 'Supporting students and building motivation.',
            ],
            [
                'name' => 'Education Technology',
                'description' => 'Digital tools and online learning.',
            ],
            [
                'name' => 'Community Programs',
                'description' => 'Local initiatives and community learning opportunities.',
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::firstOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );
        }
    }
}
