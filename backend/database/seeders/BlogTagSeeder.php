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
            'Рекомендации',
            'Выбор школы',
            'Психология',
            'Подготовка',
            'Экзамены',
            'Мотивация',
            'Внеурочка',
            'Учебный план',
            'Цифровые сервисы',
            'Стипендии',
            'Переезд',
            'Инклюзия',
            'Питание',
            'Безопасность',
            'Родительское сообщество',
        ];

        foreach ($tags as $name) {
            BlogTag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
