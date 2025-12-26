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
             Section::make('Category')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(120)
                        ->live(onBlur: true)
                        ->afterStateUpdated(ResourceHelper::autoSlug('slug')),
                    TextInput::make('slug')
                        ->required()
                        ->maxLength(160)
                        ->unique(ignoreRecord: true),
                    Textarea::make('description')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
            Section::make('SEO')
                ->collapsed()
                ->schema([
                    TextInput::make('seo_title')
                        ->maxLength(255),
                    Textarea::make('seo_description')
                        ->rows(3)
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
