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
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Enums\BlogArticleStatusEnum;
use App\Support\ResourceHelper;

/**
 * "->live" in 'Main':                    Updates state after user leaves the field, not on every keystroke,
 * "->afterStateUpdated" in 'Main':       Auto-generates slug from title if not manually set,
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
                    Grid::make()
                        ->columns(1)
                        ->columnSpan(1)
                        ->schema([
                            Section::make(__('filament.blog.articles.sections.main'))
                                ->columns(2)
                                ->schema([
                                    TextInput::make('title')
                                        ->label(__('filament.blog.articles.fields.title'))
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(ResourceHelper::autoSlug('slug')),
                                    TextInput::make('slug')
                                        ->label(__('filament.fields.slug'))
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(ignoreRecord: true),
                                    Textarea::make('excerpt')
                                        ->label(__('filament.blog.articles.fields.excerpt'))
                                        ->rows(3)
                                        ->columnSpanFull()
                                        ->helperText(__('filament.blog.articles.fields.excerpt_help')),
                                    FileUpload::make('cover_image')
                                        ->label(__('filament.blog.articles.fields.cover'))
                                        ->image()
                                        ->imageEditor()
                                        ->disk('public')
                                        ->directory('blog/covers')
                                        ->visibility('public')
                                        ->getUploadedFileUrlUsing(function (?string $state): ?string {
                                            if (blank($state)) {
                                                return null;
                                            }

                                            return Str::startsWith($state, ['http://', 'https://'])
                                                ? $state
                                                : Storage::disk('public')->url($state);
                                        })
                                        ->maxSize(5120)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->columnSpanFull(),
                                ]),
                            Section::make(__('filament.sections.seo'))
                                ->collapsed()
                                ->schema([
                                    TextInput::make('seo_title')
                                        ->label(__('filament.blog.articles.fields.seo_title'))
                                        ->maxLength(255),
                                    Textarea::make('seo_description')
                                        ->label(__('filament.blog.articles.fields.seo_description'))
                                        ->rows(3)
                                        ->maxLength(255)
                                        ->columnSpanFull(),
                                    TextInput::make('seo_keywords')
                                        ->label(__('filament.blog.articles.fields.seo_keywords'))
                                        ->maxLength(255)
                                        ->helperText(__('filament.blog.articles.fields.seo_keywords_help')),
                                    TextInput::make('canonical_url')
                                        ->label(__('filament.blog.articles.fields.canonical_url'))
                                        ->maxLength(2048)
                                        ->url(),
                                ]),
                        ]),
                    Grid::make()
                        ->columns(1)
                        ->columnSpan(1)
                        ->schema([
                            Section::make(__('filament.blog.articles.sections.categories_tags'))
                                ->columns(2)
                                ->schema([
                                    Select::make('category_id')
                                        ->label(__('filament.blog.articles.fields.category'))
                                        ->required()
                                        ->searchable()
                                        ->preload()
                                        ->relationship('category', 'name'),
                                    MultiSelect::make('tags')
                                        ->label(__('filament.blog.articles.fields.tags'))
                                        ->relationship('tags', 'name')
                                        ->searchable()
                                        ->preload(),
                                ]),
                            Section::make(__('filament.blog.articles.sections.publication'))
                                ->schema([
                                    Select::make('status')
                                        ->label(__('filament.blog.articles.fields.status'))
                                        ->required()
                                        ->options(function ($record) {
                                            if ($record?->status === BlogArticleStatusEnum::Published->value) {
                                                return [
                                                    BlogArticleStatusEnum::Scheduled->value => BlogArticleStatusEnum::Scheduled->label(),
                                                    BlogArticleStatusEnum::Published->value => BlogArticleStatusEnum::Published->label(),
                                                ];
                                            }
                                            return BlogArticleStatusEnum::options();
                                        })
                                        ->default(BlogArticleStatusEnum::Draft->value)
                                        ->live()
                                        ->helperText(__('filament.blog.articles.fields.status_help_published')),
                                    DateTimePicker::make('publish_at')
                                        ->label(__('filament.blog.articles.fields.publish_at'))
                                        ->seconds(false)
                                        ->required(fn ($get) => $get('status') === BlogArticleStatusEnum::Scheduled->value)
                                        ->visible(fn ($get) => in_array(
                                            $get('status'),
                                            [BlogArticleStatusEnum::Scheduled->value, BlogArticleStatusEnum::Published->value]))
                                        ->helperText(__('filament.blog.articles.fields.publish_at_help')),
                                    Toggle::make('featured')
                                        ->label(__('filament.blog.articles.fields.featured'))
                                        ->helperText(__('filament.blog.articles.fields.featured_help'))
                                        ->default(false),
                                    Select::make('author_id')
                                        ->label(__('filament.blog.articles.fields.author'))
                                        ->required()
                                        ->relationship('author', 'name')
                                        ->searchable()
                                        ->preload(),
                                    TextInput::make('reading_time')
                                        ->label(__('filament.blog.articles.fields.reading_time'))
                                        ->numeric()
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->helperText(__('filament.blog.articles.fields.reading_time_help')),
                                    Hidden::make('views_count')->default(0),
                                ]),
                        ]),
                    Section::make(__('filament.blog.articles.sections.content'))
                        ->columnSpan(2)
                        ->schema([
                            RichEditor::make('content')
                                ->label(__('filament.blog.articles.fields.content_html'))
                                ->columnSpanFull()
                                ->helperText(__('filament.blog.articles.fields.content_html_help')),
                        ]),
                ])
        ]);
    }
}
