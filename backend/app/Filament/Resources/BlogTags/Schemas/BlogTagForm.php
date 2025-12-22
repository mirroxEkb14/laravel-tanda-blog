<?php

namespace App\Filament\Resources\BlogTags\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Str;

class BlogTagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Tag')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(80)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set, $get) {
                            if (filled($get('slug'))) {
                                return;
                            }
                            $set('slug', Str::slug($state));
                        }),
                    TextInput::make('slug')
                        ->required()
                        ->maxLength(120)
                        ->unique(ignoreRecord: true),
                ]),
        ]);
    }
}
