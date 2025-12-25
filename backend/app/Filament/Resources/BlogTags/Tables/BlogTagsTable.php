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

/**
 * Similarly to BlogCategoriesTable, "DeleteAction" and "DeleteBulkAction" are disabled if tags are in use (assigned to articles)
 */
class BlogTagsTable
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
                    ->label('Articles')
                    ->counts('articles')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
                            $ids = $records->pluck('id')->all();
                            $usedCount = \App\Models\BlogTag::query()
                                ->whereIn('id', $ids)
                                ->whereHas('articles')
                                ->count();
                            if ($usedCount > 0) {
                                Notification::make()
                                    ->title('Cannot delete selected tags')
                                    ->body("{$usedCount} of the selected tag(s) are assigned to articles")
                                    ->danger()
                                    ->send();
                                $action->cancel();
                            }
                        })
                ]),
            ]);
    }
}
