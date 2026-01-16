<?php

declare(strict_types=1);

namespace App\Filament\ShieldResources;

use App\Filament\ShieldResources\RoleResource\Pages;
use BezhanSalleh\FilamentShield\Resources\RoleResource as BaseRoleResource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;

class RoleResource extends BaseRoleResource
{
    public static function table(Table $table): Table
    {
        $table = parent::table($table);

        return $table->actions([
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        $pages = parent::getPages();

        if (! array_key_exists('view', $pages)) {
            $pages['view'] = Pages\ViewRole::route('/{record}');
        }

        return $pages;
    }
}
