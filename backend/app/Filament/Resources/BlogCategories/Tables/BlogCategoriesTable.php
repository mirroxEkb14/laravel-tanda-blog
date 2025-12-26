<?php

namespace App\Filament\Resources\BlogCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Illuminate\Database\Eloquent\Collection;
use App\Models\BlogCategory;
use App\Support\AdminNotifications;
use App\Support\ResourceHelper;
use App\Models\BlogTag;

/**
 * "DeleteAction" in '->recordActions':        Is disabled if a category is used (has articles),
 * ""DeleteBulkAction" in '->toolbarActions':  Checks for used categories before deletion. If even 1 category is in use,
 *                                                  nothing gets deleted (“all-or-nothing”).
 */
class BlogCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('articles_count')
                    ->counts('articles')
                    ->label('Articles')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->disabled(fn ($record) => $record->isUsed())
                    ->tooltip(fn ($record) => $record->isUsed() ? $record->deleteBlockReason() : null),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (Collection $records, DeleteBulkAction $action) {
                            ResourceHelper::cancelBulkDeleteIfUsed(
                                records: $records,
                                action: $action,
                                usedCountQuery: fn (array $ids) => BlogTag::query()
                                    ->whereIn('id', $ids)
                                    ->whereHas('articles'),
                                notify: fn (int $usedCount) => AdminNotifications::cannotDeleteCategories($usedCount),
                            );
                        }),
                ]),
            ]);
    }
}
