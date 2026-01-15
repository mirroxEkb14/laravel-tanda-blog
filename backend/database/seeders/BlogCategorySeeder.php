<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\BlogCategory;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Советы',
                'description' => 'Практические рекомендации для родителей и учеников.',
            ],
            [
                'name' => 'Родителям',
                'description' => 'Материалы для семей о выборе школы и учебном процессе.',
            ],
            [
                'name' => 'Частные школы',
                'description' => 'Обзоры и критерии выбора частных школ.',
            ],
            [
                'name' => 'Поступление',
                'description' => 'Этапы и документы для поступления в учебные заведения.',
            ],
            [
                'name' => 'Подготовка к экзаменам',
                'description' => 'Стратегии подготовки к тестам и олимпиадам.',
            ],
            [
                'name' => 'Дошкольное образование',
                'description' => 'Как выбрать детский сад и подготовить ребенка.',
            ],
            [
                'name' => 'Международное обучение',
                'description' => 'Советы по программам за рубежом и адаптации.',
            ],
            [
                'name' => 'Внеклассные активности',
                'description' => 'Кружки, секции и развитие навыков вне школы.',
            ],
            [
                'name' => 'Психология и мотивация',
                'description' => 'Поддержка ребенка и формирование учебной мотивации.',
            ],
            [
                'name' => 'Технологии в образовании',
                'description' => 'Цифровые инструменты и онлайн-обучение.',
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
