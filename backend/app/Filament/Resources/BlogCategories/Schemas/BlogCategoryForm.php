<?php

namespace App\Filament\Resources\BlogCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Str;
use App\Support\ResourceHelper;

class BlogCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
             Section::make('Категория')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Название')
                        ->required()
                        ->maxLength(120)
                        ->live(onBlur: true)
                        ->afterStateUpdated(ResourceHelper::autoSlug('slug')),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(160)
                        ->unique(ignoreRecord: true),
                    Textarea::make('description')
                        ->label('Описание')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
            Section::make('SEO')
                ->collapsed()
                ->schema([
                    TextInput::make('seo_title')
                        ->label('SEO Заголовок')
                        ->maxLength(255),
                    Textarea::make('seo_description')
                        ->label('SEO Описание')
                        ->rows(3)
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
