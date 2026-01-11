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
use Filament\Tables\Filters\TernaryFilter;
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
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->square()
                    ->toggleable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(60),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                ToggleColumn::make('featured')
                    ->label('Featured')
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable(),
                TextColumn::make('publish_at')
                    ->label('Publish at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
                SelectFilter::make('status')
                    ->options(BlogArticleStatus::options()),
                SelectFilter::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'name'),
                Filter::make('publish_at')
                    ->form([
                        DatePicker::make('from')->label('Published from'),
                        DatePicker::make('until')->label('Published until'),
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
                            $indicators[] = 'Published from: ' . $data['from'];
                        }
                        if (! empty($data['until'])) {
                            $indicators[] = 'Published until: ' . $data['until'];
                        }
                        return $indicators;
                    }),
            ])
            ->defaultSort('publish_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->requiresConfirmation(),
                DeleteAction::make()->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-cloud-arrow-up')
                        ->action(function (Collection $records) {
                            $count = $records->count();
                            $records->each->update(['status' => BlogArticleStatus::Published->value,]);
                            AdminNotifications::articlesPublished($count);
                        })
                        ->requiresConfirmation(),
                    BulkAction::make('unpublish')
                        ->label('Move to draft')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->action(function (Collection $records) {
                            $count = $records->count();
                            $records->each->update(['status' => BlogArticleStatus::Draft->value,]);
                            AdminNotifications::articlesMovedToDraft($count);
                        })
                        ->requiresConfirmation(),
                    BulkAction::make('assignCategory')
                        ->label('Assign category')
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
                        ->label('Mark as featured')
                        ->icon('heroicon-o-star')
                        ->action(function (Collection $records) {
                            $records->each->update(['featured' => true]);
                            AdminNotifications::success('Updated', "{$records->count()} article(s) marked as featured");
                        })
                        ->requiresConfirmation(),
                    BulkAction::make('unmarkFeatured')
                        ->label('Remove featured')
                        ->icon('heroicon-o-star')
                        ->action(function (Collection $records) {
                            $records->each->update(['featured' => false]);
                            AdminNotifications::success('Updated', "{$records->count()} article(s) removed from featured");
                        })
                        ->requiresConfirmation(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
