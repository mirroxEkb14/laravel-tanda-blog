<?php

namespace App\Filament\Resources\BlogTags\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Filament\Actions\ViewAction;
use App\Support\AdminNotifications;
use App\Support\ResourceHelper;
use App\Models\BlogTag;

/**
 * Similarly to BlogCategoriesTable, "DeleteAction" and "DeleteBulkAction" are disabled if tags are in use (assigned to articles)
 */
class BlogTagsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('filament.fields.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('filament.fields.slug'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('articles_count')
                    ->label(__('filament.blog.tags.table.articles'))
                    ->counts('articles')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('filament.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('filament.fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->label(__('filament.actions.view')),
                EditAction::make()->label(__('filament.actions.edit')),
                DeleteAction::make()
                    ->label(__('filament.actions.delete'))
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
                                notify: fn (int $usedCount) => AdminNotifications::cannotDeleteTags($usedCount),
                            );
                        })
                ]),
            ]);
    }
}
