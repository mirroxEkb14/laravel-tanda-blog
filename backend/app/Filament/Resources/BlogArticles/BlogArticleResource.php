<?php

namespace App\Filament\Resources\BlogArticles;

use App\Filament\Resources\BlogArticles\Pages\CreateBlogArticle;
use App\Filament\Resources\BlogArticles\Pages\EditBlogArticle;
use App\Filament\Resources\BlogArticles\Pages\ListBlogArticles;
use App\Filament\Resources\BlogArticles\Schemas\BlogArticleForm;
use App\Filament\Resources\BlogArticles\Tables\BlogArticlesTable;
use App\Filament\Resources\BlogArticles\Pages\ViewBlogArticle;
use App\Models\BlogArticle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BlogArticleResource extends Resource
{
    protected static ?string $model = BlogArticle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static UnitEnum|string|null $navigationGroup = null;
    protected static ?string $navigationLabel = null;
    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = null;
    protected static ?string $pluralModelLabel = null;

    public static function getNavigationGroup(): BackedEnum|string|null
    {
        return __('filament.blog.navigation.library');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.blog.navigation.articles');
    }

    public static function getModelLabel(): string
    {
        return __('filament.blog.articles.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.blog.articles.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return BlogArticleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogArticlesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlogArticles::route('/'),
            'create' => CreateBlogArticle::route('/create'),
            'view' => ViewBlogArticle::route('/{record}'),
            'edit' => EditBlogArticle::route('/{record}/edit'),
        ];
    }
}
