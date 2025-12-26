<?php

namespace App\Support;

use Illuminate\Support\Str;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class ResourceHelper
{
    /**
     * Auto-generates slug from '$state' into '$targetField' if target is empty
     */
    public static function autoSlug(string $targetField = 'slug'): \Closure
    {
        return function ($state, callable $set, callable $get) use ($targetField): void {
            if (filled($get($targetField))) {
                return;
            }
            $set($targetField, Str::slug((string) $state));
        };
    }

    /**
     * Cancels 'DeleteBulkAction' if the "used count" query returns > 0
     */
    public static function cancelBulkDeleteIfUsed(
        Collection $records,
        DeleteBulkAction $action,
        callable $usedCountQuery,
        callable $notify,
    ): void {
        $ids = $records->pluck('id')->all();
        $query = $usedCountQuery($ids);
        $usedCount = (int) $query->count();
        
        if ($usedCount > 0) {
            $notify($usedCount);
            $action->cancel();
        }
    }
}
