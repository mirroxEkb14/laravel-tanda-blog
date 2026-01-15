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
use App\Enums\BlogArticleStatusEnum;
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
                    ->label(__('filament.blog.articles.table.cover'))
                    ->square()
                    ->toggleable(),
                TextColumn::make('title')
                    ->label(__('filament.blog.articles.table.title'))
                    ->searchable()
                    ->sortable()
                    ->limit(60),
                TextColumn::make('category.name')
                    ->label(__('filament.blog.articles.table.category'))
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament.blog.articles.table.status'))
                    ->badge()
                    ->sortable(),
                ToggleColumn::make('featured')
                    ->label(__('filament.blog.articles.table.featured'))
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label(__('filament.blog.articles.table.author'))
                    ->sortable(),
                TextColumn::make('publish_at')
                    ->label(__('filament.blog.articles.table.published_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('views_count')
                    ->label(__('filament.blog.articles.table.views'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('filament.blog.articles.table.category'))
                    ->relationship('category', 'name'),
                SelectFilter::make('status')
                    ->label(__('filament.blog.articles.table.status'))
                    ->options(BlogArticleStatusEnum::options()),
                SelectFilter::make('author_id')
                    ->label(__('filament.blog.articles.table.author'))
                    ->relationship('author', 'name'),
                Filter::make('publish_at')
                    ->label(__('filament.blog.articles.table.published_at'))
                    ->form([
                        DatePicker::make('from')->label(__('filament.blog.articles.table.published_from')),
                        DatePicker::make('until')->label(__('filament.blog.articles.table.published_until')),
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
                            $indicators[] = __('filament.blog.articles.table.indicator_from', ['date' => $data['from']]);
                        }
                        if (! empty($data['until'])) {
                            $indicators[] = __('filament.blog.articles.table.indicator_until', ['date' => $data['until']]);
                        }
                        return $indicators;
                    }),
            ])
            ->defaultSort('publish_at', 'desc')
            ->recordActions([
                ViewAction::make()->label(__('filament.actions.view')),
                EditAction::make()->requiresConfirmation()->label(__('filament.actions.edit')),
                DeleteAction::make()->requiresConfirmation()->label(__('filament.actions.delete')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->label(__('filament.blog.articles.actions.publish'))
                        ->icon('heroicon-o-cloud-arrow-up')
                        ->action(function (Collection $records) {
                            $count = $records->count();
                            $records->each->update(['status' => BlogArticleStatusEnum::Published->value,]);
                            AdminNotifications::articlesPublished($count);
                        })
                        ->requiresConfirmation(),
                    BulkAction::make('unpublish')
                        ->label(__('filament.blog.articles.actions.move_to_draft'))
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->action(function (Collection $records) {
                            $count = $records->count();
                            $records->each->update(['status' => BlogArticleStatusEnum::Draft->value,]);
                            AdminNotifications::articlesMovedToDraft($count);
                        })
                        ->requiresConfirmation(),
                    BulkAction::make('assignCategory')
                        ->label(__('filament.blog.articles.actions.assign_category'))
                        ->icon('heroicon-o-rectangle-stack')
                        ->form([
                            Select::make('category_id')
                                ->label(__('filament.blog.articles.fields.category'))
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
                        ->label(__('filament.blog.articles.actions.mark_featured'))
                        ->icon('heroicon-o-star')
                        ->action(function (Collection $records) {
                            $records->each->update(['featured' => true]);
                            AdminNotifications::success(
                                __('filament.blog.articles.actions.featured_updated_title'),
                                __('filament.blog.articles.actions.featured_marked', ['count' => $records->count()])
                            );
                        })
                        ->requiresConfirmation(),
                    BulkAction::make('unmarkFeatured')
                        ->label(__('filament.blog.articles.actions.unmark_featured'))
                        ->icon('heroicon-o-star')
                        ->action(function (Collection $records) {
                            $records->each->update(['featured' => false]);
                            AdminNotifications::success(
                                __('filament.blog.articles.actions.featured_updated_title'),
                                __('filament.blog.articles.actions.featured_unmarked', ['count' => $records->count()])
                            );
                        })
                        ->requiresConfirmation(),
                    DeleteBulkAction::make()->label(__('filament.actions.delete_selected')),
                ]),
            ]);
    }
}
