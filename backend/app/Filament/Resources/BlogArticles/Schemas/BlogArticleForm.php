<?php

namespace App\Filament\Resources\BlogArticles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Str;
use App\Rules\EnumArray;
use App\Enums\InstitutionType;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Enums\BlogArticleStatus;
use App\Support\ResourceHelper;

/**
 * "->live" in 'Main':                    Updates state after user leaves the field, not on every keystroke,
 * "->afterStateUpdated" in 'Main':       Auto-generates slug from title if not manually set,
 * "->schema" in 'Institution relations': 'related_types' uses Enum, 'related_institutions' transforms "101, 102" into int array,
 * "->schema" in 'Publication':           If already published, remove “draft” from the dropdown;
 *                                        '->live()' affects if 'publish_at' visibility is required;
 *                                        'publish_at' is required only when status is 'scheduled' and visible when status 'scheduled' or 'published';
 *                                        '->dehydrated' means not to be sent back to be saved; it’s display-only in the form.
 */
class BlogArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make()
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    Section::make('Main')
                        ->columns(2)
                        ->columnSpan(1)
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(ResourceHelper::autoSlug('slug')),
                            TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            Textarea::make('excerpt')
                                ->rows(3)
                                ->columnSpanFull()
                                ->helperText('Short description for listings'),
                            FileUpload::make('cover_image')
                                ->label('Cover image (16:9)')
                                ->image()
                                ->imageEditor()
                                ->directory('blog/covers')
                                ->visibility('public')
                                ->maxSize(5120)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->columnSpanFull(),
                        ]),
                    Grid::make()
                        ->columns(1)
                        ->columnSpan(1)
                        ->schema([
                            Section::make('Category & Tags')
                                ->columns(2)
                                ->schema([
                                    Select::make('category_id')
                                        ->label('Category')
                                        ->required()
                                        ->searchable()
                                        ->preload()
                                        ->relationship('category', 'name'),
                                    MultiSelect::make('tags')
                                        ->label('Tags')
                                        ->relationship('tags', 'name')
                                        ->searchable()
                                        ->preload(),
                                ]),
                            Section::make('Publication')
                                ->schema([
                                    Select::make('status')
                                        ->required()
                                        ->options(function ($record) {
                                            if ($record?->status === BlogArticleStatus::Published->value) {
                                                return [
                                                    BlogArticleStatus::Scheduled->value => BlogArticleStatus::Scheduled->label(),
                                                    BlogArticleStatus::Published->value => BlogArticleStatus::Published->label(),
                                                ];
                                            }
                                            return BlogArticleStatus::options();
                                        })
                                        ->default(BlogArticleStatus::Draft->value)
                                        ->live()
                                        ->helperText('If you set Published with a future Publish at, it will be treated as Scheduled automatically'),
                                    DateTimePicker::make('publish_at')
                                        ->label('Publish at')
                                        ->seconds(false)
                                        ->required(fn ($get) => $get('status') === BlogArticleStatus::Scheduled->value)
                                        ->visible(fn ($get) => in_array(
                                            $get('status'),
                                            [BlogArticleStatus::Scheduled->value, BlogArticleStatus::Published->value]))
                                        ->helperText('Required for Scheduled status'),
                                    Select::make('author_id')
                                        ->label('Author')
                                        ->required()
                                        ->relationship('author', 'name')
                                        ->searchable()
                                        ->preload(),
                                    TextInput::make('reading_time')
                                        ->numeric()
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->helperText('Auto-calculated (minutes)'),
                                    Hidden::make('views_count')->default(0),
                                ]),
                        ]),
                    Section::make('Content')
                        ->columnSpan(2)
                        ->schema([
                            RichEditor::make('content')
                                ->label('Content (HTML)')
                                ->columnSpanFull()
                                ->helperText('Store as HTML. Reading time will be calculated automatically'),
                        ]),
                    Section::make('Institution relations')
                        ->collapsed()
                        ->columnSpan(1)
                        ->schema([
                            CheckboxList::make('related_types')
                                ->label('Institution types')
                                ->options(InstitutionType::options())
                                ->columns(2)
                                ->helperText('Used for filtering articles by institution context (e.g., type=school)')
                                ->rules(['nullable', new EnumArray(InstitutionType::class)]),
                            TextInput::make('related_institutions')
                                ->helperText('Example: 101, 202')
                                ->dehydrateStateUsing(fn ($state) => blank($state)
                                    ? null
                                    : array_values(array_map(
                                        'intval',
                                        array_filter(array_map('trim', explode(',', $state)))
                                    )))
                                ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                                ->columnSpanFull(),
                        ]),
                    Section::make('SEO')
                        ->collapsed()
                        ->columns(2)
                        ->columnSpan(1)
                        ->schema([
                            TextInput::make('seo_title')->maxLength(255),
                            Textarea::make('seo_description')
                                ->rows(3)
                                ->maxLength(255)
                                ->columnSpanFull(),
                            TextInput::make('seo_keywords')
                                ->maxLength(255)
                                ->helperText('Comma-separated keywords'),
                            TextInput::make('canonical_url')
                                ->maxLength(2048)
                                ->url(),
                        ]),
                ])
        ]);
    }
}
