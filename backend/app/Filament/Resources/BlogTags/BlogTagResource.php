<?php

namespace App\Filament\Resources\BlogTags;

use App\Filament\Resources\BlogTags\Pages\CreateBlogTag;
use App\Filament\Resources\BlogTags\Pages\EditBlogTag;
use App\Filament\Resources\BlogTags\Pages\ListBlogTags;
use App\Filament\Resources\BlogTags\Schemas\BlogTagForm;
use App\Filament\Resources\BlogTags\Tables\BlogTagsTable;
use App\Filament\Resources\BlogTags\Pages\ViewBlogTag;
use App\Models\BlogTag;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class BlogTagResource extends Resource
{
    protected static ?string $model = BlogTag::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static UnitEnum|string|null $navigationGroup = 'Библиотека';
    protected static ?string $navigationParentItem = 'Статьи блога';
    protected static ?string $navigationLabel = 'Теги блога';
    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Тег';
    protected static ?string $pluralModelLabel = 'Теги блога';


    public static function form(Schema $schema): Schema
    {
        return BlogTagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogTagsTable::configure($table);
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
            'index' => ListBlogTags::route('/'),
            'create' => CreateBlogTag::route('/create'),
            'view' => ViewBlogTag::route('/{record}'),
            'edit' => EditBlogTag::route('/{record}/edit'),
        ];
    }
}
