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
             Section::make(__('filament.blog.categories.section'))
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label(__('filament.fields.name'))
                        ->required()
                        ->maxLength(120)
                        ->live(onBlur: true)
                        ->afterStateUpdated(ResourceHelper::autoSlug('slug')),
                    TextInput::make('slug')
                        ->label(__('filament.fields.slug'))
                        ->required()
                        ->maxLength(160)
                        ->unique(ignoreRecord: true),
                    Textarea::make('description')
                        ->label(__('filament.fields.description'))
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
            Section::make(__('filament.sections.seo'))
                ->collapsed()
                ->schema([
                    TextInput::make('seo_title')
                        ->label(__('filament.fields.seo_title'))
                        ->maxLength(255),
                    Textarea::make('seo_description')
                        ->label(__('filament.fields.seo_description'))
                        ->rows(3)
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
