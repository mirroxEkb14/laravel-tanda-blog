<?php

namespace App\Filament\Resources\BlogTags\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Str;
use App\Support\ResourceHelper;

class BlogTagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Тег')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Название')
                        ->required()
                        ->maxLength(80)
                        ->live(onBlur: true)
                        ->afterStateUpdated(ResourceHelper::autoSlug('slug')),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(120)
                        ->unique(ignoreRecord: true),
                ]),
        ]);
    }
}
