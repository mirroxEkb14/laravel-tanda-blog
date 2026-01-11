<?php

namespace App\Filament\Resources\BlogArticles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Models\BlogCategory;
use App\Enums\BlogArticleStatus;
use App\Support\AdminNotifications;

/**
 * "BulkActionGroup" force-publishes selected articles and downgrades selected articles back to draft
 */
class BlogArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                ImageColumn::make('cover_image')
                    ->label('Обложка')
                    ->square()
                    ->toggleable(),
                TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->sortable()
                    ->limit(60),
                TextColumn::make('category.name')
                    ->label('Категория')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->sortable(),
                ToggleColumn::make('featured')
                    ->label('Избранное')
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Автор')
                    ->sortable(),
                TextColumn::make('publish_at')
                    ->label('Дата публикации')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('views_count')
                    ->label('Просмотры')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Категория')
                    ->relationship('category', 'name'),
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(BlogArticleStatus::options()),
                SelectFilter::make('author_id')
                    ->label('Автор')
                    ->relationship('author', 'name'),
                Filter::make('publish_at')
                    ->label('Дата публикации')
                    ->form([
                        DatePicker::make('from')->label('Опубликовано от'),
                        DatePicker::make('until')->label('Опубликовано до'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('publish_at', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('publish_at', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if (! empty($data['from'])) {
                            $indicators[] = 'От: ' . $data['from'];
                        }
                        if (! empty($data['until'])) {
                            $indicators[] = 'До: ' . $data['until'];
                        }
                        return $indicators;
                    }),
            ])
            ->defaultSort('publish_at', 'desc')
            ->recordActions([
                ViewAction::make()->label('Просмотр'),
                EditAction::make()->requiresConfirmation()->label('Изменить'),
                DeleteAction::make()->requiresConfirmation()->label('Удалить'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->label('Опубликовать')
                        ->icon('heroicon-o-cloud-arrow-up')
                        ->action(function (Collection $records) {
                            $count = $records->count();
                            $records->each->update(['status' => BlogArticleStatus::Published->value,]);
                            AdminNotifications::articlesPublished($count);
                        })
                        ->requiresConfirmation(),
                    BulkAction::make('unpublish')
                        ->label('Перевести в черновик')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->action(function (Collection $records) {
                            $count = $records->count();
                            $records->each->update(['status' => BlogArticleStatus::Draft->value,]);
                            AdminNotifications::articlesMovedToDraft($count);
                        })
                        ->requiresConfirmation(),
                    BulkAction::make('assignCategory')
                        ->label('Присвоить категорию')
                        ->icon('heroicon-o-rectangle-stack')
                        ->form([
                            Select::make('category_id')
                                ->label('Category')
                                ->options(BlogCategory::query()->orderBy('name')->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each->update([
                                'category_id' => (int) $data['category_id'],
                            ]);
                            AdminNotifications::categoryUpdated();
                        })
                        ->requiresConfirmation(),
                    BulkAction::make('markFeatured')
                        ->label('Пометить как избранное')
                        ->icon('heroicon-o-star')
                        ->action(function (Collection $records) {
                            $records->each->update(['featured' => true]);
                            AdminNotifications::success('Updated', "{$records->count()} article(s) marked as featured");
                        })
                        ->requiresConfirmation(),
                    BulkAction::make('unmarkFeatured')
                        ->label('Удалить из избранного')
                        ->icon('heroicon-o-star')
                        ->action(function (Collection $records) {
                            $records->each->update(['featured' => false]);
                            AdminNotifications::success('Updated', "{$records->count()} article(s) removed from featured");
                        })
                        ->requiresConfirmation(),
                    DeleteBulkAction::make()->label('Удалить выделенные'),
                ]),
            ]);
    }
}
