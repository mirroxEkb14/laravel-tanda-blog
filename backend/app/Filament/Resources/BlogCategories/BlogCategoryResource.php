<?php

namespace App\Filament\Resources\BlogCategories;

use App\Filament\Resources\BlogCategories\Pages\CreateBlogCategory;
use App\Filament\Resources\BlogCategories\Pages\EditBlogCategory;
use App\Filament\Resources\BlogCategories\Pages\ListBlogCategories;
use App\Filament\Resources\BlogCategories\Schemas\BlogCategoryForm;
use App\Filament\Resources\BlogCategories\Tables\BlogCategoriesTable;
use App\Filament\Resources\BlogCategories\Pages\ViewBlogCategory;
use App\Models\BlogCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class BlogCategoryResource extends Resource
{
    protected static ?string $model = BlogCategory::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static UnitEnum|string|null $navigationGroup = null;
    protected static ?string $navigationParentItem = null;
    protected static ?string $navigationLabel = null;
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = null;
    protected static ?string $pluralModelLabel = null;

    public static function getNavigationGroup(): BackedEnum|string|null
    {
        return __('filament.blog.navigation.library');
    }

    public static function getNavigationParentItem(): ?string
    {
        return __('filament.blog.navigation.articles');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.blog.navigation.categories');
    }

    public static function getModelLabel(): string
    {
        return __('filament.blog.categories.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.blog.categories.plural_label');
    }


    public static function form(Schema $schema): Schema
    {
        return BlogCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogCategoriesTable::configure($table);
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
            'index' => ListBlogCategories::route('/'),
            'create' => CreateBlogCategory::route('/create'),
            'view' => ViewBlogCategory::route('/{record}'),
            'edit' => EditBlogCategory::route('/{record}/edit'),
        ];
    }
}
