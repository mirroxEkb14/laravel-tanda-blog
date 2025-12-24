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
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Models\BlogCategory;

/**
 * "BulkActionGroup" force-publishes selected articles and downgrades selected articles back to draft
 */
class BlogArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
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
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'published' => 'Published',
                    ]),
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
                        ->action(fn (Collection $records) => $records->each->update([
                            'status' => 'published',
                        ]))
                        ->requiresConfirmation(),
                    BulkAction::make('unpublish')
                        ->label('Move to draft')
                        ->action(fn (Collection $records) => $records->each->update([
                            'status' => 'draft',
                        ]))
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
                            Notification::make()
                                ->title('Category updated')
                                ->body('Selected articles were assigned to the chosen category')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
